# MagangVerify — Rencana Integrasi dan Pengujian End-to-End

## Status lingkungan saat audit

Belum ada integration test atau browser E2E yang dijalankan. Semua skenario di bawah berstatus belum diuji kecuali baris yang secara eksplisit menyebut verifikasi otomatis parsial. Proposal canonical baseline dan rekonstruksi struktur aplikasi ada di [`Canonical-DB-Baseline-Proposal.md`](Canonical-DB-Baseline-Proposal.md).

- Kode koneksi aplikasi memakai PDO PostgreSQL; schema/migration menggunakan sintaks PostgreSQL. PostgreSQL identity columns pada snapshot membutuhkan PostgreSQL 10 atau lebih baru; versi deployment aktual belum diketahui.
- Tidak ditemukan listener PostgreSQL lokal pada port standar saat audit. Driver PDO `pgsql` tersedia, tetapi itu tidak membuktikan server/database testing ada atau terisolasi.
- Docker CLI dan daemon merespons (`Docker server version 29.1.3`). Tidak ada container dibuat/dijalankan dalam audit.
- `config/database.php` memerlukan PDO PostgreSQL. Untuk menghindari pembacaan `.env`, injeksikan kelima variabel berikut ke environment proses aplikasi secara non-empty: `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`. `config/env.php` kini melewati `.env` hanya jika seluruhnya tersedia; environment parsial tetap memakai loader `.env` lama, jadi jangan mulai aplikasi dengan konfigurasi parsial.
- `function/Environment.php` menyediakan pemeriksaan yang sama; tes menguji skip pada path file yang tidak ada, merge dari file fixture dummy tanpa menimpa nilai proses, dan menolak konfigurasi parsial. Tes ini tidak membuka `.env`.
- Pastikan `psql -h 127.0.0.1 -p 5432 -U <test_user> -d <test_db> -W -c 'SELECT current_database(), inet_server_addr();'` mengembalikan database terdedikasi dan alamat loopback sebelum mengonfigurasi PHP. Perintah tersebut hanya SELECT; jangan teruskan jika koneksi ternyata menuju target remote/shared.
- Repository belum menyediakan migration runner atau test suite PHPUnit/Playwright. `schema.sql` adalah snapshot, bukan file untuk dieksekusi sebagai migration.
- Bukti strategi yang ditemukan: migration bernomor menjalankan SQL secara manual, lalu operator mencatat entry ke `schema_migrations`; tidak ada auto-runner. `Dokumentasi/migration.md` bagian 5–8 dan `Dokumentasi/Migration/wsl.md` bagian 5–7 menyatakan ledger hanya catatan, bukan executor. Kepastian tinggi untuk strategi manual incremental; kepastian rendah untuk baseline kanonis karena dokumen WSL menyebut nama migration lama yang tidak cocok dengan tree saat ini.
- `Dokumentasi/Migration/wsl.md` masih menyebut host/database development Windows dan contoh nama migration lama (`001_create_table_users.sql`, `002_create_table_mahasiswa.sql`), bukan file yang ada sekarang. Itu tidak cukup untuk menyimpulkan bahwa database lama atau strategi snapshot merupakan baseline.
- Apache aktif, tetapi konfigurasi yang diperiksa tidak menunjukkan vhost project: `apachectl -S` melaporkan DocumentRoot default `/var/www/html`; `mod_rewrite` tidak loaded, dan konfigurasi default melarang `AllowOverride` untuk `/var/www`. Karena itu `.htaccess` repository tidak terbukti akan diproses pada Apache runtime ini. Nginx aktif; `nginx -t` syntax check menyatakan config syntax okay tetapi gagal saat membuka `/run/nginx.pid` karena permission user, dan `nginx -T` tidak menghasilkan dump aman karena pemeriksaan PID/config gagal. Tidak ada vhost yang terbukti memetakan repo.
- Tidak ada browser binary maupun browser automation tool yang tersedia. Checklist manual di bawah belum dijalankan.
- `.htaccess` hanya melindungi deployment Apache yang mengizinkan override. Untuk Nginx, operator harus menolak static access ke `/storage/` dan `/uploads/cv/` sebelum aplikasi diuji. Jangan mengubah konfigurasi deployment dari sesi audit ini.
- Tidak ada koneksi lokal ke aplikasi yang diuji: README menyarankan PHP built-in server dengan `router.php`, tetapi memulai aplikasi akan bootstrap koneksi database; target DB terisolasi belum dibuktikan, jadi server tidak dijalankan.

## Persiapan database lokal yang aman — operator lakukan setelah memeriksa target

1. Sediakan instance PostgreSQL lokal khusus testing dengan database kosong/terdedikasi; pastikan bukan Supabase, bukan staging bersama, dan bukan produksi.
2. Inject semua variabel `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, dan `DB_PASSWORD` secara non-empty ke environment proses aplikasi dari supervisor/secret manager; jangan menaruh nilainya di command history atau Git. `config/env.php` kini melewati pembacaan `.env` jika semua variabel tersebut sudah diinjeksi.
3. Cocokkan `config/database.php` dengan target: kode saat ini menggunakan PDO PostgreSQL. Pastikan target host/database terisolasi dan environment yang sama dipakai saat menjalankan PHP.
4. Sebelum menerapkan migration, ambil snapshot backup dari database testing (jika ada data) dan baca `schema_migrations` serta definisi aktual semua objek. Belum ada bukti migration mana yang sudah diterapkan.
5. Jangan menjalankan `database/schema.sql` sebagai migration. Audit repository menemukan `002_create_mitra_and_formasi.sql` sudah membuat tabel Mitra/Formasi, sementara migration 012 dan 013 membuat ulang tabel yang sama. Rangkaian bernomor tidak aman dijalankan buta pada database kosong maupun database yang sudah ada; pemilik proyek harus menyelaraskan baseline dan ledger terlebih dahulu.
6. Basis domain yang dirujuk kode meliputi `users`, `profil_mahasiswa`, `profil_dosen`, `mitra`, `formasi_magang`, `pendaftaran_magang`, `dokumen_pendaftaran_magang`, `riwayat_pendaftaran_magang`, `penempatan_magang`, `permintaan_pembimbing`, `logbook_mingguan`, `logbook_harian`, `logbook_revisi`, `logbook_tanda_tangan`, `template_laporan`, `laporan_magang`, `laporan_revisi`, `pemeriksaan_laporan`, `penilaian_magang`, `verifikasi_penyelesaian_magang`, `notifikasi`, `aktivitas_pengguna`, dan `schema_migrations`. Foreign key/check/index utama dapat dilihat pada `database/schema.sql` serta migration bernomor; kondisi aktual belum diketahui.
7. Migration `020`–`022` membangun/hardening workflow logbook, dan `023` adalah compatibility marker. `024` menambahkan tabel template/pemeriksaan laporan dan memperluas laporan. `025_verify_laporan_workflow.sql` berisi SELECT verifikasi, bukan perubahan schema. `026` dan `027` mengisi template (026 tidak aman untuk diulang tanpa cek ledger/data). `028` membuat view rekap Koordinator. `029` membuat permintaan pembimbing dan wajib sudah tercatat sebelum seeder fixture-nya dipertimbangkan. Migration logbook rekap lokal di `database/migrations/rekap_logbook_koordinator.sql` bersifat tambahan dan belum dieksekusi.
8. Tinjau setiap perbedaan migrasi–snapshot sebelum penerapan. Jangan menjalankan, mengedit ledger, seeder, atau query tulis sebagai bagian dari rencana ini.

### Opsi container disposable setelah operator menyetujui

Gunakan hanya setelah migrasi baseline conflict direkonsiliasi dan target disetujui. Placeholder di bawah bukan perintah yang dijalankan pada audit ini:

```bash
docker run --detach --rm \
  --name magangverify-e2e-<unique-name> \
  --publish 127.0.0.1:15432:5432 \
  --env-file <secure-file-outside-repository> \
  postgres:<version-approved-for-project>
```

Secure env-file yang dibuat operator hanya untuk container PostgreSQL tersebut perlu berisi `POSTGRES_DB`, `POSTGRES_USER`, dan `POSTGRES_PASSWORD`; jangan taruh nilainya di repo atau command-line literal. Tanpa volume mount, container disposable tidak memakai database project/Supabase. Setelah start, verifikasi `current_database()` dan `inet_server_addr()` melalui koneksi read-only sebelum inject parameter `DB_HOST=127.0.0.1`, `DB_PORT=15432`, `DB_NAME`, `DB_USER`, `DB_PASSWORD` ke proses PHP. Jangan menjalankan migration/seeder sebelum blocker urutan 002/012/013 dan keputusan target sudah dibereskan.

Perintah verifikasi yang direncanakan (read-only, belum dijalankan):

```bash
docker port magangverify-e2e-<unique-name> 5432/tcp
psql -h 127.0.0.1 -p 15432 -U <test_user> -d <test_db> -W \
  -v ON_ERROR_STOP=1 \
  -c 'SELECT current_database(), inet_server_addr(), inet_server_port(), current_user;'
psql -h 127.0.0.1 -p 15432 -U <test_user> -d <test_db> -W \
  -v ON_ERROR_STOP=1 \
  -c "SELECT n.nspname, c.relkind, count(*) FROM pg_class c JOIN pg_namespace n ON n.oid=c.relnamespace WHERE n.nspname NOT IN ('pg_catalog','information_schema') AND n.nspname !~ '^pg_toast' GROUP BY n.nspname,c.relkind ORDER BY n.nspname,c.relkind;"
docker stop magangverify-e2e-<unique-name>
```

Efek: `docker port` hanya membaca port mapping dan harus menunjukkan `127.0.0.1:15432`; `psql -c` hanya SELECT identitas server/database dan menghitung objek catalog non-system untuk mendeteksi schema tidak kosong; `docker stop` menghentikan container bernama unik dan `--rm` menghapus container setelah berhenti. Jangan lanjutkan bila mapping/identitas tidak cocok, nama DB bukan DB testing, atau query catalog menunjukkan objek yang tidak diharapkan. Env-file dipakai Docker saat create saja dan tidak dimount ke repository.

### Katalog urutan migration repository (audit file, bukan instruksi eksekusi langsung)

| Urutan nama | Peran dependency secara umum | Catatan kesiapan |
|---|---|---|
| `000`–`001` | Migration ledger, users/profil mahasiswa/profil dosen | Fondasi FK role/profile. |
| `002`–`004` | Mitra/formasi, pengajuan, placement | **Blocker:** snapshot menyatakan `002_create_mitra_and_formasi.sql` sudah membuat mitra/formasi, tetapi `012` dan `013` membuat tabel itu lagi dengan kolom/constraint berbeda. Jangan jalankan berurutan tanpa rekonsiliasi baseline. |
| `005`–`008` | Logbook legacy, laporan/penilaian/verifikasi, portofolio, notifikasi/activity | Perlu dibandingkan dengan constraint/field snapshot sebelum apply. |
| `009`–`019` | Perubahan profil, portfolio, dokumen pengajuan, sertifikat, CV, pengalaman, index | `014` membuat tabel dokumen dengan kind pendek; `015` mempertahankan kind pendek, `016` menambah constraint kind panjang di samping constraint lama; `017` menghapus keduanya dan menetapkan kind final panjang. `017` merekam ledger untuk `016`. Data lama perlu diperiksa sebelum 017 memvalidasi constraint final. |
| `020`–`023` | Logbook V2, trigger hardening, struktur, compatibility marker | `023` menyatakan harus setelah `020`–`022`; jangan anggap marker memperbaiki baseline lama. |
| `024` | Template dan pemeriksaan laporan; memperluas laporan yang sudah ada | Memerlukan tabel dasar dari `006`; aturan pemeriksaan bisnis tetap belum lengkap. |
| `025_verify_laporan_workflow.sql` | SELECT pemeriksaan prerequisite/data/ledger | Bukan migration schema yang merekam registry; jangan dihitung sebagai perubahan DDL. |
| `026`–`027` | Seed template laporan akhir/mingguan | Data seed; `026` tidak memiliki perlindungan conflict pada insert template. Periksa ledger/data, jangan ulangi. |
| `028` | View rekap Koordinator | Bergantung tabel/kolom yang digunakan view dan schema target. |
| `029` | Tabel/index permintaan pembimbing | Masih belum terbukti diterapkan; fixture seeder bergantung padanya. |
| `rekap_logbook_koordinator.sql`, `rekap_laporan_mingguan_koordinator.sql` | SQL tambahan untuk rekap Koordinator | File lokal; bukan bagian dari nomor urut dan tidak dijalankan pada audit. |

Daftar ini hanya dependency dari isi repository. Urutan aktual yang boleh diterapkan baru dapat ditetapkan setelah pemilik DB memeriksa `schema_migrations` dan skema target yang terisolasi.

### Baseline yang dapat dipertanggungjawabkan dari bukti saat ini

- Bukti cukup untuk menyimpulkan mekanisme repository saat ini adalah **incremental SQL manual + ledger**, bukan migration runner: setiap migration normal menyertakan `INSERT INTO schema_migrations`; dokumentasi mengatakan SQL dijalankan manual, lalu ledger dicatat setelah sukses.
- Bukti tidak cukup untuk memilih migration 002 versus 012/013 sebagai canonical historical baseline. 002 dan 012/013 berbeda objek dengan struktur berbeda; menjalankannya bersama pasti gagal pada tabel yang kedua kali dibuat. Snapshot/schema.sql secara eksplisit mencatat konflik tersebut. Tidak ada bukti resmi yang menyatakan salah satu kelompok historis boleh dihapus/dilewati pada DB existing.
- Untuk database kosong baru, rekomendasi aman adalah **menyetujui baseline fresh terpisah berdasarkan canonical `database/schema.sql` yang sudah direkonsiliasi**, lalu mencatat baseline/version yang eksplisit dan menetapkan hanya migration setelah baseline itu yang boleh dijalankan. Jangan menjalankan seluruh file 000–029 secara numerik tanpa koreksi karena 009 tidak tercatat, 010 DDL-nya tidak idempotent tetapi tidak tercatat di snapshot ledger, 012/013 bertabrakan dengan 002, 025 bukan DDL, dan 026 berisiko replay.
- Opsi kedua adalah replay migration incremental dari 000 pada database disposable hanya setelah pemilik proyek menyetujui perubahan strategi/migration baru yang menyelesaikan 002/012/013, 009 ledger, 010 ledger, dan seed template. Jangan mengubah migration historis; gunakan bootstrap/baseline migration baru yang berisi canonical schema, atau revisi sequence lewat migration forward-only yang didesain untuk target aktual. Opsi ini memerlukan keputusan pemilik proyek tentang asal baseline.
- Migration 029 tetap dipertahankan sebagai incremental post-baseline feature migration; tidak diketahui apakah pernah diterapkan pada suatu DB. Query `schema_migrations` aktual diperlukan sebelum menerapkannya pada target yang sudah ada.

### Audit file migrasi satu per satu

Audit ini hanya membaca SQL; tidak ada statement yang dieksekusi.

| File migrasi | Objek database | Dependensi | Masalah/risiko teridentifikasi | Bukti dalam file | Rekomendasi |
|---|---|---|---|---|---|
| `000_create_schema_migrations.sql` | `schema_migrations` | Tidak ada | Baseline harus dijalankan pertama dan hanya sekali pada DB baru. | Membuat tabel ledger identity. | Pakai hanya setelah target kosong/disposable dikonfirmasi. |
| `001_create_users_and_profiles.sql` | `users`, `profil_mahasiswa`, `profil_dosen` | 000; identity root dan FK user/profile | Menjadi root FK seluruh role; seed user/profile perlu konsisten. | `CREATE TABLE` users dan dua profil; role check. | Pertahankan bentuk ini sebagai fondasi baseline yang telah direkonsiliasi. |
| `002_create_mitra_and_formasi.sql` | `mitra`, `formasi_magang`, index | 001 users | Model/kode saat ini menggunakan bentuk dari migration ini. | `mitra.user_id`, `nama_perusahaan`; `formasi_magang.judul`, `jumlah_kuota`. | Baseline saat ini perlu mempertahankan kolom/constraint ini atau semua consumer harus dimigrasikan bersama. |
| `003_create_pendaftaran_magang.sql` | `pendaftaran_magang`, `riwayat_pendaftaran_magang`, partial unique index | 001 profil; 002 formasi; users | Partial unique index membatasi pengajuan aktif; status/kinds harus cocok dengan aplikasi. | FK profil/formasi, status check, `uq_pendaftaran_mahasiswa_aktif`. | Periksa data sebelum migration jika ledger target tidak kosong; jangan drop index untuk mengatasi data konflik. |
| `004_create_penempatan_magang.sql` | `penempatan_magang`, index | 003 pendaftaran; 001 profil_dosen/users | FK pembimbing mengarah `profil_dosen.id`, sedangkan penetap user ke `users.id`. | Kolom `dosen_pembimbing_id`, `ditetapkan_oleh`, FK dan check periode. | Pertahankan pembedaan ID tersebut saat seed dan query. |
| `005_create_logbook_magang.sql` | `logbook_mingguan`, `logbook_revisi`, `logbook_tanda_tangan`, index | 004 placement; 001 users | Ini struktur logbook lama; 020–022 membentuk/hardening versi harian. | Tabel minggu/revisi/signature dengan relasi placement. | Terapkan hanya sesuai ledger dan rangkaian 020–023 yang terverifikasi. |
| `006_create_laporan_dan_penilaian.sql` | Laporan, revisi, penilaian, verifikasi penyelesaian, index | 004 placement; 001 users | 024 mengubah/menambah workflow laporan; definisi tabel verifikasi tidak dengan sendirinya mendefinisikan workflow bisnis. | Tabel dasar `laporan_magang`, `laporan_revisi`, `penilaian_magang`, `verifikasi_penyelesaian_magang`. | Cocokkan dengan 024 dan snapshot sebelum test; jangan menyimpulkan syarat lulus dari enum status. |
| `007_create_portofolio_mahasiswa.sql` | Portfolio, berkas, skills, relasi, history, index | 001 profil/users | Kolom portofolio diperluas lagi oleh 010. | Lima tabel dan FK profil/user. | Jalankan sebelum 010 hanya pada baseline yang diketahui. |
| `008_create_notifikasi_dan_aktivitas.sql` | `notifikasi`, `aktivitas_pengguna`, index | 001 users | Dipakai oleh banyak alur transaksi; kondisi aktual diperlukan untuk verifikasi history. | FK ke users dan index queue/history. | Pastikan tersedia sebelum menjalankan fitur yang menulis notifikasi/activity. |
| `009_add_deskripsi_profil_mahasiswa.sql` | Kolom `profil_mahasiswa.deskripsi` | 001 | File tidak mencatat dirinya sendiri di `schema_migrations`; ledger tidak dapat menjadi bukti penerapan file ini. | Hanya satu `ALTER TABLE`; tidak ada insert ledger. Snapshot memiliki kolom deskripsi. | Untuk fresh baseline, masukkan hasilnya ke canonical snapshot/baseline; untuk DB ada, verifikasi column + ledger tanpa menebak apakah pernah diterapkan. |
| `010_adjust_portofolio_for_form.sql` | Kolom portfolio dan `portofolio_teknologi` | 007 | `ALTER TABLE ADD COLUMN` dan `CREATE TABLE` tanpa guard; replay gagal. Snapshot memuat objeknya tetapi ledger snapshot tidak mencatat 010. | Menambah `peran`, link dan tabel teknologi; SQL menulis ledger 010. | Periksa ledger/kolom dulu. Pada disposable baseline, pilih snapshot sebagai baseline eksplisit atau replay chain yang sudah direkonsiliasi, bukan keduanya. |
| `011_create_sertifikat.sql` | `sertifikat`, index | 001 users | Tabel memakai `CREATE TABLE IF NOT EXISTS`, namun bentuk tabel existing yang berbeda tidak otomatis diperbaiki. | Status/tanggal check dan `IF NOT EXISTS`. | Bandingkan definisi actual jika tabel sudah ada. |
| `012_create_mitra.sql` | `mitra`, index | 001 users | Konflik fatal dengan 002: membuat tabel yang sama tanpa `IF NOT EXISTS`, dengan kolom berbeda. | Mendefinisikan `nama`, `slug`; tidak tampak `user_id`/`is_active` yang dipakai code. | Jangan jalankan setelah 002. Putuskan canonical schema/migration strategy dulu; migration lama jangan diubah sepihak. |
| `013_create_formasi_magang.sql` | `formasi_magang`, index | 012 mitra | Konflik fatal dengan 002: tabel sama, nama kolom berbeda dan akan gagal dibuat ulang. | `nama_posisi`, `program_studi`, `kuota`; code/snapshot memakai `judul`, `jumlah_kuota`. | Sama seperti 012; jangan buat urutan numerik otomatis sebelum baseline diselaraskan. |
| `014_create_dokumen_pendaftaran_magang.sql` | `dokumen_pendaftaran_magang`, index | 003 pendaftaran | Membuat check awal menggunakan alias pendek jenis optional dokumen. | `bpjs`, `proposal` dalam check awal; unique per pendaftaran/jenis. | Ikuti kind final 017 pada fresh/current schema; review nilai existing sebelum check berubah. |
| `015_expand_jenis_dokumen_pendaftaran.sql` | Constraint jenis dokumen | 014 dokumen | Replace constraint lama memakai alias pendek; belum cukup untuk target akhir. | Drop `dokumen_pendaftaran_jenis_check`, add check `bpjs`/`proposal`. | Jangan anggap 015 sebagai schema final; lanjutkan rekonsiliasi 016/017 sesuai ledger/data. |
| `016_add_constraint_dokumen_pendaftaran_jenis_check.sql` | Constraint jenis dokumen | 014/015 | Membuat constraint kedua bernama lain tanpa menghapus constraint 015 dan tidak menulis registry sendiri; nilai optional long bertabrakan dengan check pendek yang masih aktif. | Drop hanya `_jenis_dokumen_check`, add long kinds; tidak ada insert ledger. | Jangan dijalankan sendirian. 017 menghapus dua nama check dan mencatat 016+017, tetapi existing short-kind rows perlu dibaca sebelum add constraint final. |
| `017_add_cv_to_profil_mahasiswa.sql` | Constraint dokumen final; kolom dan check CV | 014–016; 001 profil | Constraint akhir menggunakan `bpjs_asuransi`/`proposal_magang`, sesuai PRD; bila existing rows hanya short alias, penambahan check bisa gagal. | Drop dua constraint; add long kinds; insert registry 016 dan 017; CV max 5 MiB. | Snapshot dan mapping aplikasi kini diselaraskan; sebelum penerapan target, cek nilai existing read-only dan setujui compatibility/normalization bila diperlukan. |
| `018_add_social_links_and_pengalaman.sql` | Kolom social profile, `pengalaman`, index | 001 profile; 003 pendaftaran | `IF NOT EXISTS` dipakai pada object sebagian; tidak membuktikan kolom tabel existing konsisten. | Tiga kolom URL; tabel pengalaman dan FK pendaftaran optional. | Bandingkan actual schema untuk DB lama. |
| `019_optimize_dashboard_mahasiswa.sql` | Index query dashboard | 007, 011, 018, 005 dan tabel terkait | Index names dan ledger harus cocok; tidak membentuk ulang tabel. | Index `IF NOT EXISTS`; insert 019. | Bisa dipakai setelah tabel target tersedia dan status index dicek. |
| `020_rebuild_logbook_workflow.sql` | Kolom snapshot; `logbook_harian`; kolom signature; index | 005, 004, 001 users | `CREATE TABLE IF NOT EXISTS` tidak menyelaraskan tabel lama yang parsial; trigger enforcement datang setelahnya. | Daily unique/date/time/absence checks, FK dan status_validasi. | Verifikasi 005 dan kolom existing sebelum apply 020 pada baseline lama. |
| `021_harden_logbook_workflow.sql` | PL/pgSQL functions/triggers signature dan daily rules | 020 daily; 005 weekly/revision/signatures; 004 placement | Mengganti trigger bernama via drop/recreate; bergantung pada kolom yang tepat di schema existing. | `CREATE OR REPLACE FUNCTION`, trigger signature ownership/order/immutability. | Terapkan setelah tabel/kolom dependency diverifikasi; uji pada PostgreSQL versi target. |
| `022_harden_logbook_structure.sql` | Trigger jadwal minggu, delete lock, revision immutability | 020–021, placement/logbook tables | Validasi jadwal menganggap urutan minggu terakhir dapat dihitung dari logbook existing. | Functions/triggers untuk periode dan history signed. | Periksa legacy weeks sebelum penerapan; jangan replay di target yang ledger-nya tidak diketahui. |
| `023_restructure_logbook_magang.sql` | Registry marker saja | 020–022 | File menyatakan compatibility marker; bukan pembuat ulang schema. | Hanya insert ledger 023 dengan conflict handling. | Catat setelah 020–022 berhasil, bukan sebagai pengganti migration tersebut. |
| `024_create_laporan_workflow.sql` | `template_laporan`, perubahan `laporan_magang`, `pemeriksaan_laporan`, index | 006 existing laporan/revisi/placement; 001 users | DDL tidak sepenuhnya idempotent; urutan reviewer/status transisi belum didefinisikan bisnis. | Drop/add constraints, partial unique weekly/final, reviewer-stage enum. | Pastikan tabel 006 aktual dan evaluasi data sebelum index/check changes. Jangan implementasi keputusan reviewer tanpa kebijakan. |
| `025_verify_laporan_workflow.sql` | Tidak ada; SELECT saja | 024 dan tabel laporan | Nama berawalan 025 bukan migrasi DDL atau ledger entry. | Empat query SELECT; tidak ada CREATE/ALTER/INSERT. | Jalankan sebagai pemeriksaan read-only hanya pada target yang sudah diidentifikasi; jangan masukkan dalam urutan apply. |
| `026_seed_template_laporan.sql` | Baris template laporan akhir | 024 dan file DOCX | Insert tidak idempotent/`ON CONFLICT`-guarded; replay dapat gagal pada partial unique index template aktif. | INSERT template aktif lalu catat 026. | Periksa ledger dan row aktif sebelum run; jangan replay blind. |
| `027_seed_template_laporan_mingguan.sql` | Baris template mingguan | 024 dan file DOCX | `ON CONFLICT DO NOTHING` membantu replay tetapi tidak membuktikan template benar/aktif yang dipilih. | INSERT template weekly; catat 027. | Periksa row aktif/versi terlebih dahulu. |
| `028_create_view_rekap_magang_koordinator.sql` | View `view_rekap_magang_koordinator` | 001, 002, 003, 004 dan kolom status | View mengasumsikan canonical kolom dari 002 (`nama_perusahaan`, `judul`); akan gagal pada layout 012/013. | JOIN pendaftaran/profil/formasi/mitra/placement/dosen. | Terapkan hanya setelah baseline 002-like yang cocok diverifikasi. |
| `029_create_permintaan_pembimbing.sql` | Tabel/index request advisor dan registry | 001 users/profil_dosen; 004 placement; 003 pendaftaran | Dibuat tanpa `IF NOT EXISTS`; diterapkan status belum diketahui. | FK placement/profil_dosen; check status/respons; partial unique pending; ledger 029. | Cocokkan registry dan schema target sebelum fixture seeder; file dipertahankan dan tidak dijalankan. |
| `rekap_logbook_koordinator.sql` | Tidak ada persistent object; CTE query | 003–005, 020–023 | Query statusnya adalah CTE read-only, bukan migration; status weekly mengikuti check schema. | `WITH` dan SELECT akhir saja. | Audit nama/status saat query dipakai; jangan masukkan ke migration runner. |
| `rekap_laporan_mingguan_koordinator.sql` | Tidak ada persistent object; CTE query | 004, 006, 024 | CTE membedakan status yang hanya ada setelah workflow pemeriksaan; hasil bisa nol tanpa reviewer workflow. | `WITH` dan SELECT laporan mingguan saja. | Jangan anggap hasilnya sebagai workflow review yang sudah berjalan. |

Audit rinci dokumen pengajuan menemukan bahwa 014/015 memakai kind `bpjs` dan `proposal`; 016 menambahkan constraint lain dengan `bpjs_asuransi`/`proposal_magang` tanpa membuang constraint 015; 017 kemudian membuang kedua nama constraint dan menetapkan kind final `bpjs_asuransi`/`proposal_magang`, yang sama dengan PRD. Snapshot schema sebelumnya masih memakai kind final yang salah (`bpjs`/`proposal`) dan halaman detail memetakan alias pendek. Snapshot dan label detail kini selaras ke 017; `LamarController` mempertahankan nama input form pendek tetapi memetakan metadata DB ke kind final panjang. Bila target memiliki dokumen yang sudah tersimpan dengan alias pendek, constraint 017 final dapat gagal saat validasi data; periksa data secara read-only dan sepakati compatibility/data-normalization migration sebelum penerapan. Migration lama tidak diubah.

## Konvensi status

- `Otomatis—parsial`: yang terbukti hanya tes render/helper yang disebutkan; bukan integrasi DB/browser.
- `Manual—belum dijalankan`: perlu browser dan database lokal terisolasi.
- `Terblokir—database`: schema/migration/fixture belum tersedia pada target aman.
- `Terblokir—keputusan bisnis`: implementasi tidak menetapkan perilaku; jangan mengarang hasil.

## Matriks Mahasiswa

| Fitur | Prasyarat data | Langkah dan hasil yang diharapkan | Akses yang harus ditolak | Status |
|---|---|---|---|---|
| Login/profil | User role mahasiswa dan profil terhubung | Login; buka profil/dashboard; data diri yang dimiliki sendiri tampil dan perubahan valid tersimpan | Anonymous/role lain ke dashboard; CSRF salah pada POST; perubahan profil user lain | Manual—belum dijalankan |
| Portofolio/sertifikat/pengalaman | Profil mahasiswa; fixture item opsional | Buat/ubah item; item tampil sesuai pemilik; publikasi mengikuti status pada schema | ID item milik mahasiswa lain; POST tanpa CSRF; upload MIME/ukuran salah | Manual—belum dijalankan |
| Formasi dan pengajuan | Formasi dibuka, mitra aktif/terverifikasi, kuota tersedia, profil lengkap, tidak ada pengajuan aktif | Lihat detail; unggah 6 dokumen wajib; kirim; status awal mengikuti `PendaftaranMagang`; data pemilik tampil | Pengajuan kedua saat masih aktif; formasi tertutup/penuh; dokumen hilang/salah; CSRF salah; IDOR dokumen | Manual—terblokir database |
| Riwayat/revisi pengajuan | Pengajuan milik mahasiswa; record status tertentu | Lihat status dan dokumen yang sudah ada | Akses pengajuan/dokumen orang lain | Manual—belum dijalankan; upload ulang terblokir keputusan bisnis/version history |
| Permintaan pembimbing | Migration 029 tersedia; pendaftaran diterima; placement persiapan/berlangsung tanpa dosen; dosen aktif | Pilih profil dosen dan isi alasan; request menjadi `menunggu`; notifikasi target; lihat histori | Placement orang lain, belum diterima/selesai/sudah dibimbing, duplikasi pending, POST tanpa CSRF | Manual—terblokir database (029 belum diverifikasi/diterapkan) |
| Logbook | Placement mahasiswa berlangsung/selesai dan berada pada periodenya | Buat minggu; isi tanggal satu per record; tanggal depan/duplikat ditolak; alasan ketidakhadiran wajib; bukti opsional tervalidasi | Placement orang lain; future date; edit setelah Mitra sign; validasi pending belum selesai menghalangi signature | Otomatis—parsial untuk render saja; workflow DB belum diuji |
| Signature | Minggu lengkap dan periode berakhir; tahapan sesuai status | Mahasiswa sign; edit sebelum Mitra sign membuat versi baru dan perlu sign ulang | Sign minggu tidak lengkap/masa depan; Mitra/Dosen sign sebelum tahap yang diwajibkan; CSRF salah | Manual—terblokir database |
|| Laporan mingguan | Placement sendiri; minggu logbook terkait dan mempunyai aktivitas; template aktif | Buat draft untuk minggu N; unggah PDF; pastikan laporan mengambil kegiatan minggu N dari placement yang sama; unduh current dan historical revision melalui endpoint owner-scoped | ID laporan/revisi milik mahasiswa lain; status selain `draft`/`perlu_revisi` mengunggah; URL storage langsung; path DB keluar allowlist | Render versi parsial otomatis; SQL ownership/download HTTP manual belum diuji |
| Laporan akhir | Placement sendiri; tanggal selesai placement sudah tercapai; template akhir aktif | Buat laporan akhir hanya saat gate tanggal terlewati; unduh template/file melalui controller | Sebelum gate tanggal; ID laporan lain; path storage traversal | Manual—terblokir database |
| CV publik | Profil publik mahasiswa dan CV PDF | Akses route `/mahasiswa/profil/{slug}/cv`; PDF yang valid dikirim inline/attachment sesuai parameter; path static langsung ditolak | Slug/CV tidak tersedia; database path traversal; URL langsung ke `/uploads/cv/...` | Otomatis—helper path parsial; endpoint HTTP belum diuji |

## Matriks Dosen

| Fitur | Prasyarat data | Langkah dan hasil yang diharapkan | Akses yang harus ditolak | Status |
|---|---|---|---|---|
| Login/dashboard | User role dosen dan `profil_dosen` | Login; dashboard/monitoring hanya menampilkan bimbingan dan antrean yang sesuai query | Anonymous/role lain; session user invalid | Otomatis—render dashboard parsial; login/DB belum diuji |
| Persetujuan pengajuan | Pengajuan menunggu persetujuan; enam dokumen wajib untuk approval | Buka antrean; pilih setuju/tolak; cek status, history, notifikasi mahasiswa | Keputusan kedua, status sudah berubah, CSRF salah | Manual—terblokir database; schema tidak menetapkan reviewer tertentu sehingga antrean saat ini role-wide Dosen |
| Review dokumen pengajuan | Dokumen terkait pengajuan pending pada tahap aktor | Buka endpoint dokumen; response PDF hanya setelah relasi/tahap lolos | Mitra lain, status di luar antrean, ID dokumen tebakan, static URL storage | Manual—belum dijalankan |
| Permintaan pembimbing | Request `menunggu` yang ditujukan ke dosen terkait | Buka antrean target sendiri; setujui/tolak; assignment memakai `profil_dosen.id` dan notifikasi mahasiswa | Request target dosen lain, proses ganda, placement tidak lagi memenuhi syarat | Manual—terblokir database (029) |
| Logbook/signature | Placement memakai profil dosen; status `menunggu_dosen` | Buka minggu setelah Mitra sign; baca; sign; status selesai/disetujui | Placement dosen lain; sebelum Mitra sign; CSRF salah; signature file di luar relasi | Manual—terblokir database |
| Penilaian | Placement bimbingan berstatus `menunggu_penilaian`/`selesai` | Simpan draft; validasi aspek 0–100; submit menghasilkan `diajukan`, activity/notifikasi | Placement dosen lain; status lain; nilai di luar rentang; CSRF salah; ubah setelah submit | Otomatis—view parsial; model/DB belum diuji |
| Pemeriksaan laporan lanjutan | Belum ada workflow reviewer yang disepakati | Jangan menjalankan keputusan pemeriksaan tanpa definisi tahap/hasil | — | Terblokir—keputusan bisnis |

## Matriks Mitra

| Fitur | Prasyarat data | Langkah dan hasil yang diharapkan | Akses yang harus ditolak | Status |
|---|---|---|---|---|
| Login/dashboard | User mitra, record perusahaan terhubung | Login; data dashboard/placement milik perusahaan tampil | Anonymous/role lain; perusahaan lain | Otomatis—render dashboard parsial; DB belum diuji |
| Formasi | Mitra terverifikasi dan aktif | Buat draft/dibuka; ubah/tutup sesuai status; kuota dan periode tervalidasi | Mitra tidak aktif; owner lain; formasi yang memiliki pengajuan diubah/dihapus; CSRF salah | Manual—belum dijalankan |
| Respons pengajuan | Aplikasi perusahaan sendiri setelah Dosen setuju | Respons `seleksi`, `diterima`, atau `ditolak`; acceptance mengunci kuota; history/notifikasi tercatat | Perusahaan lain; tahap belum masuk; kuota penuh; respons ganda; CSRF salah | Manual—terblokir database |
| Dokumen aplikasi | Dokumen dari aplikasi yang sedang pada tahap respons Mitra | Buka endpoint authorized; PDF inline, no-store | Dokumen perusahaan lain/status lain; tebakan ID; static URL | Manual—belum dijalankan |
| Logbook | Placement perusahaan sendiri, setelah mahasiswa sign | Buka detail setelah `menunggu_mitra`; lihat aktivitas/bukti; sign; status Dosen tidak ditampilkan sebagai status Mitra | Placement perusahaan lain; sebelum student sign; Dosen signature download; POST tanpa CSRF | Manual—terblokir database |
| Penilaian | Placement perusahaan sendiri berstatus yang diizinkan | Simpan draft/submit; tidak ada formula nilai otomatis | Placement lain; status lain; nilai invalid; CSRF salah | Otomatis—view parsial; model/DB belum diuji |

## Matriks Koordinator Magang

| Fitur | Prasyarat data | Langkah dan hasil yang diharapkan | Akses yang harus ditolak | Status |
|---|---|---|---|---|
| Login/monitoring | User koordinator; data aplikasi dan placement | Dashboard/rekap menunjukkan sumber data aktual | Anonymous/role lain | Otomatis—view parsial; DB belum diuji |
| Penempatan | Pengajuan accepted dan belum memiliki placement | Pilih rentang tanggal; create placement `persiapan`; notifikasi mahasiswa | Status bukan accepted; placement duplikat; tanggal invalid; CSRF salah | Manual—terblokir database |
| Validasi bukti absen | Minggu mengandung ketidakhadiran `menunggu` | Lihat hanya minggu relevan; buka bukti; setujui/tolak dengan catatan; transaction/activity konsisten | Minggu tanpa pending; decision ulang; signature; akses file placement lain | Manual—terblokir database; split authority dengan Tendik belum ditentukan |
| Admin global/review laporan/verifikasi selesai | Belum ada batas kewenangan/workflow | Tidak melakukan mutasi global atau keputusan laporan/kelulusan | — | Terblokir—keputusan bisnis |

## Matriks Tendik/Manajemen

| Fitur | Prasyarat data | Langkah dan hasil yang diharapkan | Akses yang harus ditolak | Status |
|---|---|---|---|---|
| Login/dashboard | User role tendik; pending absence tersedia | Antrean menampilkan validasi yang dapat ditindak | Anonymous/role lain | Otomatis—view parsial; DB belum diuji |
| Validasi ketidakhadiran | Bukti absence `menunggu` | Periksa alasan/bukti; setujui atau tolak dengan catatan; status/revisi/snapshot/activity commit atomik | Hari hadir, sudah diproses, minggu tidak ada pending, signature, file lain | Manual—terblokir database; batas pembagian dengan Koordinator belum disepakati |
| Administrasi luas/review laporan/verifikasi selesai | Definisi field, ownership, tahapan, hasil belum lengkap | Tidak memberikan edit global atau membuat transisi | — | Terblokir—keputusan bisnis |

## Cross-role/security matrix

| Skenario | Langkah dan expected | Status |
|---|---|---|
| Session/role | Anonymous dan setiap role mencoba dashboard role lain; response ditolak/redirect sesuai middleware | Manual—belum dijalankan |
| CSRF | Hilangkan/ubah `_csrf_token` pada setiap POST penting (apply, decision, sign, upload, placement, formasi, penilaian); mutation tidak terjadi | Manual—belum dijalankan |
| IDOR | Tukar ID application, placement, logbook, report, signature, evidence, CV ke milik akun lain; tidak ada file/data terungkap dan mutation ditolak | Manual—belum dijalankan |
| Static file bypass | Coba storage/CV direct URL dengan slash encoded, double encoded, traversal, backslash, case, prefix app; Apache deny, router helper returns private | Otomatis—helper path lulus; HTTP server belum diuji |
| File-path allowlist | Pengujian unit resolver: template fixture di allowlist diterima; absolute/traversal/storage lain/outside-root ditolak | Otomatis—helper lulus; bukan request HTTP |
|| Notifikasi/history | Jalankan perubahan status sah; cek history/activity/notifikasi tepat satu kali; request ulang tidak menambah record | Manual—belum dijalankan |
|| Inbox notifikasi | Buka bell pada setiap role; lihat daftar milik sendiri; tandai satu/semua dibaca; badge/empty state sesuai hasil | Render otomatis parsial; query/update database manual belum dijalankan |

## Cakupan otomatis yang sudah dijalankan

- `php tests/smoke_views.php`: 9 komponen view termasuk inbox notifikasi dengan fixture in-memory; bukan test login, controller POST, query SQL, atau browser.
- `php tests/workflow_static_checks.php`: helper path dan env loader diuji dengan fixture sementara di `TMPDIR` untuk encoding/traversal/prefix/case/public path, path/symlink allowlist, skip `.env` bila proses env lengkap, precedence process env, optional-document kind mapping, duplikasi table-create migration bernomor, ledger 016→017, dan read-only status file 025; pemeriksaan query/workflow lainnya tetap berupa source assertions.
- Lint seluruh PHP dan `git diff --check` lulus pada audit terakhir.

## Urutan eksekusi E2E setelah environment aman

1. Provision PostgreSQL lokal terdedikasi; inject config secara aman dan buktikan koneksi hanya ke instance itu.
2. Audit kondisi DB/ledger. Jangan apply blind: migration 012/013 membuat ulang tabel yang telah dibuat 002; 025 adalah query verifikasi; 026 memiliki insert template yang tidak aman diulang tanpa pemeriksaan; `schema.sql` bukan migration.
3. Review/selaraskan baseline dan migration sequence secara manual; baru setelah approval, operator menerapkan migration yang diperlukan dan fixture seeder pada DB disposable.
4. Verifikasi relasi akun test → profil → formasi → pendaftaran → placement → dosen/mitra sebelum tes role.
5. Jalankan matriks role; tes setiap request langsung/IDOR/CSRF dan akses file. Simpan bukti HTTP/browser dan hasil DB read-only.
6. Ulangi negative cases dan cek history/activity/notifikasi; catat hasil yang dijalankan, bukan asumsi.
7. Untuk deployment Apache, pastikan `mod_rewrite` dan `AllowOverride` sesuai. Untuk Nginx, siapkan static deny pada `/storage/` dan `/uploads/cv/`. Jangan ekspos folder private sebagai static.

## Register skenario E2E siap eksekusi (semua belum dijalankan)

Semua identitas/record/file pada register ini wajib synthetic dan dibuat hanya pada DB testing yang sudah disetujui. Jangan gunakan data pengguna atau file asli. Simpan request/response yang sudah disanitasi, hasil query read-only sesudah request, screenshot bila relevan, dan error log yang tidak memuat credential. Status semua skenario di bawah: **PLANNED / NOT RUN**.

| ID / tujuan | Prasyarat dan data dummy | Pemilik data → role penguji | Langkah/request | Hasil yang diharapkan | Bukti yang dicatat | Lapisan |
|---|---|---|---|---|---|---|
| AUTH-01 — autentikasi dan batas role | Akun synthetic aktif untuk mahasiswa, dosen, koordinator_magang, tendik, dan mitra; satu endpoint read-only per role | Akun role target → anonymous dan empat role lain | GET dashboard tiap role; kirim request langsung ke dashboard role yang tidak sesuai | Anonymous diarahkan/ditolak; role salah mendapat 403/penolakan; tidak ada data dashboard bocor | Method/path, role synthetic, status HTTP, body yang disanitasi, session sebelum/sesudah | PostgreSQL + server |
| OWN-01 — profil, portofolio, sertifikat | Dua mahasiswa synthetic; profil dan item dummy milik A; B memiliki item sendiri | Mahasiswa A → mahasiswa B | B GET detail/download ID/slug A; B POST edit/hapus dengan ID milik A | Tidak ada data/file A yang dikembalikan atau berubah; record A tetap utuh | Status HTTP, hash/nama file dummy, SELECT read-only sebelum/sesudah | PostgreSQL + server |
| OWN-02 — pengajuan dan dokumen pendaftaran | Dua mahasiswa, dua perusahaan/formasi dan pengajuan synthetic dengan file PDF dummy | Pengajuan A → mahasiswa lain dan perusahaan B | Ganti ID pengajuan/dokumen pada GET detail/download serta request mutation yang relevan | Data/dokumen A tidak terbaca atau berubah oleh akun yang tidak berhak | Request path, status, content-type/length, hasil DB read-only | PostgreSQL + server |
| OWN-03 — logbook dan bukti/signature | Placement, minggu, bukti dan signature dummy; relasi aktor synthetic A/B | Placement A → mahasiswa/dosen/mitra/validator lain | Ubah ID minggu/hari/file pada detail, validasi, signature dan endpoint file | Data/file hanya tersedia sesuai relasi, role, status dan gate tanda tangan; mutation IDOR ditolak | Status, response headers, ID fixture, keadaan status sebelum/sesudah | PostgreSQL + server |
| OWN-04 — penempatan monitoring Dosen | Dosen A membimbing placement A; Dosen B membimbing placement B | Dosen A → Dosen B; juga mahasiswa non-pembimbing | GET `/dashboard/dosen/monitoring`; GET `/dashboard/dosen/monitoring/detail/{id}` dengan ID A dan B | Daftar A hanya berisi bimbingan A; detail B ditolak/404; detail A menampilkan field placement terkait saja | Status, daftar ID synthetic, response redacted, SELECT read-only | PostgreSQL + server |
| CSRF-01 — semua mutasi | Fixture role dan record synthetic yang memenuhi prasyarat; session dan token valid untuk kontrol positif | Pemilik masing-masing record → role pemilik, lalu request tanpa/CSRF salah | Ulangi POST tanpa token dan dengan token salah untuk apply, keputusan, profil/upload, hapus, logbook/sign, validasi, penempatan, formasi, permintaan pembimbing, notifikasi, dan penilaian | Request ditolak; tidak ada row/status/file/notifikasi baru. Request kontrol dengan token valid hanya pada target disposable | Status HTTP, request yang disanitasi, snapshot DB/files sebelum/sesudah | PostgreSQL + server |
| UP-01 — validasi upload | File dummy kecil: PDF valid, gambar JPEG/PNG/WebP valid; PDF palsu, gambar rusak, MIME palsu, file kosong, over-limit, upload error | Akun mahasiswa pemilik profil/item → aktor mahasiswa | POST upload ke form CV, foto, portofolio, sertifikat, dokumen pengajuan, logbook/laporan yang tersedia | Hanya format/ukuran yang diizinkan diterima; invalid ditolak; tidak ada metadata/berkas parsial tertinggal | Status/form error, ukuran/MIME dummy, daftar path storage sebelum/sesudah, row DB read-only | PostgreSQL + server |
| FILE-01 — download pemilik/nonpemilik dan static bypass | File PDF/dokumen synthetic pada storage terisolasi; satu file public CV dummy sesuai kebijakan publik | Pemilik file → pemilik, role lain, anonymous | GET authorized handler sebagai pemilik dan bukan pemilik; coba direct static URL ke file privat; uji path traversal/encoded | Pemilik menerima file yang sah; pengguna tak berhak ditolak; storage privat tidak tersaji statis; CV hanya mengikuti kontrak route publik yang ada | Status, content-type/disposition, byte/hash fixture, status direct URL | PostgreSQL + server + web-server config |
| ASSESS-01 — penilaian Dosen/Mitra | Placement synthetic milik Dosen/Mitra penguji dengan status yang diizinkan; score dummy tanpa formula | Dosen/Mitra pemilik placement → role sama pemilik, lalu role lain | Simpan draft; submit; coba score di luar 0–100, edit setelah submit dan placement milik pihak lain | Validasi server menolak input/status/owner salah; score yang sah tersimpan; nilai akhir tetap manual, tanpa formula | Status, error, row penilaian dan notifikasi/activity read-only | PostgreSQL + server |
| FLOW-MHS-01 — alur utama Mahasiswa | Profil lengkap, formasi terbuka, mitra synthetic, fixture dokumen wajib dan placement untuk logbook/laporan | Mahasiswa A → A; kontrol negatif oleh mahasiswa B | Login; lihat status; ajukan dengan dokumen dummy; buka pengajuan sendiri; jalankan logbook/laporan yang memenuhi gate existing | Status/riwayat sesuai source dan PRD; data scoped; file private lewat handler; jangan menguji reviewer laporan yang belum disepakati | Session role, status HTTP, ID fixture, read-only status/history, file hashes | PostgreSQL + server |
| FLOW-DOS-01 — Dosen | Pengajuan pending dan dokumen dummy; placement bimbingan; minggu pada tahap Dosen; placement menunggu penilaian | Dosen A → A; request silang dari Dosen B | Review pengajuan sesuai aksi tersedia; buka monitoring/detail; review/sign logbook sesuai tahap; simpan draft/submit penilaian | Owner dan status ditegakkan; form/redirect menampilkan halaman benar; tidak ada nilai formula otomatis | Request/response, penempatan/riwayat/notifikasi read-only, signature hash | PostgreSQL + server |
| FLOW-KOOR-01 — Koordinator | Pengajuan diterima tanpa placement; minggu dengan bukti absen pending | Koordinator synthetic → Koordinator lain dan aktor pemilik fixture | Buat placement dengan tanggal valid; buka antrean/validasi bukti sesuai hak yang sudah ada | Owner/state/CSRF ditegakkan; tidak membuat placement/decision ganda. Jangan uji admin global atau pemeriksaan laporan | Status, row placement/validation/history read-only, activity | PostgreSQL + server |
| FLOW-TENDIK-01 — Tendik | Minggu synthetic dengan satu bukti absen pending dan satu record non-pending | Tendik synthetic → Tendik; aktor role lain sebagai kontrol | Buka queue/detail/bukti; kirim validasi sah dan request untuk record non-pending/ID pihak lain | Queue dan file dibatasi ke pending record yang berwenang; update ulang/CSRF salah ditolak | Status, file headers, status record/revisi/activity read-only | PostgreSQL + server |
| FLOW-MITRA-01 — Mitra | Perusahaan verified synthetic, formasi dan aplikasi dummy, kuota tercatat, placement untuk logbook/penilaian | Mitra A → Mitra B dan pemilik Mitra A | Buat/ubah/tutup formasi; lihat applicant; buka dokumen; respons; buka/sign logbook pada tahapnya; nilai bila status diizinkan | Semua query dibatasi ke perusahaan; transisi mengikuti status existing; aplikasi/respons berulang ditolak; kuota/history/notifikasi konsisten | Request/response, IDs, kuota/history/notifikasi read-only, PDF hash | PostgreSQL + server |
| CORE-01 — dashboard/empty/error seluruh role | Akun synthetic tanpa record dan akun dengan fixture | Setiap role → akun sendiri | Buka dashboard/menu utama saat kosong, normal, dan saat DB test sengaja tidak tersedia hanya dalam harness terisolasi yang disetujui | Data bukan fixture hardcoded; empty/error tampil; error tidak memaparkan exception/credential | Screenshot tersanitasi, status/body, log dummy | PostgreSQL + server; error-injection perlu test harness |

**Dapat dijalankan tanpa DB/server saat ini:** hanya smoke render fixture, source-level guard assertions, pure helper tests untuk normalisasi path, allowlist/symlink, pemetaan jenis dokumen dan environment fixture. Semua skenario autentikasi/ownership/CSRF/upload/download/role di atas memerlukan server dan/atau PostgreSQL untuk membuktikan hasil runtime. Jangan mengubah status PLANNED menjadi PASS tanpa menjalankan lapisan yang disebut.
