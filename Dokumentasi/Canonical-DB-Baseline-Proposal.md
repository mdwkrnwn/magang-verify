# Proposal Canonical Database Baseline MagangVerify

Status: proposal untuk database testing baru yang kosong; BELUM disetujui dan BELUM diterapkan.

Batas ruang lingkup:

- A — Fresh testing DB kosong: hanya ini yang dirancang di sini.
- B — Database existing: tidak disentuh. Skema/data/ledger aktual belum diperiksa; butuh assessment terpisah dengan backup dan keputusan pemilik DB.
- C — Sejarah migration lama: file historis tidak diubah/dihapus. Proposal tidak menyatakan migration yang pernah dibagikan aman ditulis ulang.

Semua tipe/constraint di bawah yang bersumber dari `database/schema.sql` adalah kandidat struktur snapshot, bukan bukti schema live. Kolom/model yang belum dapat dicocokkan ditandai BELUM DIPUTUSKAN.

## 1. Strategi dan baseline yang direkomendasikan

Bukti repository menunjukkan migrasi dijalankan manual dan ledger dicatat manual, bukan migration runner otomatis: `Dokumentasi/migration.md:70-97`; `Dokumentasi/Migration/wsl.md:46-137`. `schema_migrations` sendiri hanya mencatat nama migration.

Rekomendasi untuk A: setelah persetujuan pemilik proyek, buat artefak baru khusus fresh bootstrap (mis. `database/baselines/001_magangverify_canonical.sql`) yang menghasilkan struktur canonical yang sudah direkonsiliasi dan hanya mendaftarkan satu marker baseline versi. Jangan salin `schema.sql` mentah sebagai executable baseline: snapshot mencampur DDL, inserts template, registry yang tidak lengkap, serta conflict sejarah 002/012/013. Pisahkan:

1. DDL baseline dan constraints/index/triggers/views.
2. Seed wajib idempotent yang disetujui (mis. satu template aktif per jenis; file DOCX harus tersedia).
3. Fixture/sample testing terpisah di `database/seeders/`.

Setelah baseline, buat urutan daftar allowlist migration incremental baru untuk fitur setelah baseline; jangan menjalankan seluruh migration historis otomatis. Marker baseline harus berbeda dari nama migration historis. Jangan mencatat 000-029 seolah-olah dieksekusi jika struktur dipasang dari snapshot.

Keying ledger yang diusulkan: marker eksplisit seperti `baseline_magangverify_v1.sql` pada `schema_migrations`; detail checksum/versi baseline BELUM DIPUTUSKAN karena tabel saat ini hanya memiliki `id`, `migration`, `executed_at` (`database/migrations/000_create_schema_migrations.sql:3-7`). Jangan menambahkan field ledger tanpa keputusan kompatibilitas.

## 2. Rekonstruksi canonical tables yang dibutuhkan

Tabel berikut adalah matriks proposal inti. Bukti schema berada di `database/schema.sql`; penggunaan source menunjukkan aplikasi memerlukan bentuk tersebut. Ini belum merupakan DDL implementasi.

| Tabel | Struktur yang terbukti dibutuhkan (kolom utama; tipe dari snapshot bila tampak) | PK/FK/unik/check/default yang terbukti | Migration / bukti pemakaian | Konflik atau keputusan |
|---|---|---|---|---|
| `schema_migrations` | `id BIGINT identity`, `migration VARCHAR(255)`, `executed_at TIMESTAMPTZ` | PK id; migration UNIQUE; waktu default CURRENT_TIMESTAMP | 000:3-7; migration manual; dokumentasi di atas | Marker baseline fresh serta strategi ledger setelah baseline harus disetujui. |
| `users` | id, login_id, name, password nullable di snapshot, role, is_active, timestamps | PK; login_id UNIQUE; role CHECK; is_active default true; snapshot password CHECK hanya memaksa non-null untuk selain mitra | 001; auth `models/User.php`, `controllers/LoginController.php`; schema:19-42 | Snapshot/migration history password constraint perlu dikonfirmasi; auth menolak password tidak valid bagi semua role. |
| `profil_mahasiswa` | id, user_id, nim, program_studi, angkatan, kontak/alamat/deskripsi, foto_path, CV metadata, social URLs, timestamps | PK; user_id UNIQUE FK users CASCADE; nim UNIQUE; CV size 0<bytes<=5MiB bila ada | 001, 009, 017, 018; `ProfilMahasiswa`, dashboard, Lamar, public profile; schema:46-77 | 009 tidak masuk ledger; isi snapshot final lebih lengkap daripada sejarah registry. |
| `profil_dosen` | id, user_id, nidn, email, telepon, timestamps | PK; user_id UNIQUE FK users CASCADE; nidn UNIQUE nullable | 001; query advisor/placement/logbook/penilaian; schema:81-90 | Pastikan `dosen_pembimbing_id` selalu mereferensi id profil, bukan users.id. |
| `mitra` | id, user_id, nama_perusahaan, kode_perusahaan, kategori, bidang_usaha, deskripsi, kontak, website, lokasi/alamat, status_verifikasi, catatan, verifier, aktif, timestamps | PK; user_id UNIQUE nullable FK users SET NULL; kode UNIQUE; verifier FK users; kategori/status CHECK; is_active default true | 002 canonical-by-code shape; `FormasiMitra`, `DashboardMitra`, pengajuan/placement; schema:96-162 | 012 tabrakan: tidak ada user_id, nama_perusahaan, kode, is_active; ada `nama`, slug, logo_path, alamat NOT NULL. Pilihan canonical historical belum disetujui. |
| `formasi_magang` | id, mitra_id, judul, deskripsi, bidang, jumlah_kuota, jumlah_diterima, persyaratan, lokasi_magang, sistem_kerja, tanggal mulai/selesai, tahun akademik, status, dibuat_oleh, timestamps | PK; FK mitra RESTRICT, dibuat_oleh users SET NULL; kuota >0 dan diterima<=kuota; tanggal selesai>=mulai; sistem/status CHECK; diterima default 0 | 002 canonical-by-code shape; `FormasiMitra`, `PendaftaranMagang`, `PengajuanMitra`, dashboards; schema:167-238 | 013 tabrakan: nama_posisi, arrays program_studi/keahlian, kuota, lokasi/durasi, periode nullable, status enum berbeda. 002 bentuk yang digunakan source saat ini, tetapi persetujuan baseline tetap perlu. |
| `pendaftaran_magang` | id, mahasiswa_id, formasi_id, status persetujuan dosen/respons mitra/status keseluruhan, actor/times/notes, tanggal pengajuan/selesai, timestamps | PK; FK profil/formasi; dosen_penyetuju users; CHECK status; partial UNIQUE satu aplikasi aktif per mahasiswa | 003; `PendaftaranMagang`, `PersetujuanPengajuan`, `PengajuanMitra`; schema:244-317 | Status aplikasi dan status schema perlu tetap sejalan; daftar status aktif tercantum 367-376. |
| `riwayat_pendaftaran_magang` | id, pendaftaran_id, status sebelumnya/baru, catatan, diubah_oleh, waktu | PK; FK pendaftaran RESTRICT; actor FK users SET NULL | 003; model persetujuan/respons mitra; schema:324-340 | Tidak ada version table untuk dokumen pengajuan; versioning revisi belum diputuskan. |
| `dokumen_pendaftaran_magang` | id, pendaftaran_id, jenis_dokumen, nama_asli, path_file, mime_type, ukuran_bytes, created_at | PK; FK pendaftaran RESTRICT; ukuran >0; UNIQUE(pendaftaran_id, jenis); final kind CHECK | 014-017; `LamarController`, `PendaftaranMagang`, `PersetujuanPengajuan`; schema:1333-1347 | Final names `bpjs_asuransi`/`proposal_magang` dari 017 dan PRD; data alias lama harus diperiksa sebelum constraint. |
| `penempatan_magang` | id, pendaftaran_id, dosen_pembimbing_id, periode, status, catatan, ditetapkan_oleh, waktu/timestamps | PK; pendaftaran UNIQUE FK RESTRICT; pembimbing FK profil_dosen RESTRICT; ditetapkan_oleh FK users SET NULL; date/status CHECK | 004; `PenempatanMagangCoordinator`, `PermintaanPembimbing`, Logbook, laporan, penilaian; schema:383-423 | FK user dan profil dosen berbeda dan tidak boleh disatukan. |
| `permintaan_pembimbing` | id, penempatan_id, dosen_id, alasan, status, catatan, tanggal pengajuan/respons, timestamps | PK; FK placement & profil_dosen RESTRICT; status CHECK; pending UNIQUE partial per placement; alasan not blank; response timestamp consistency | 029; `PermintaanPembimbing`; schema:445-474 | Migration belum dijalankan/keadaan server tidak diketahui. Fresh baseline dapat memasukkan objek setara 029 setelah persetujuan; alternatif apply 029 sesudah baseline, jangan keduanya. |
| `logbook_mingguan` | id, penempatan_id, minggu_ke, tanggal mulai/selesai, versi_terkini, status, timestamps | PK; FK placement RESTRICT; minggu >0; end>=start; version>0; status CHECK; UNIQUE(placement,minggu) | 005 lalu hardening 020-022; `Logbook`; schema:481-524 | Snapshot menyatukan final structure, tetapi aturan trigger harus diuji PostgreSQL. |
| `logbook_revisi` | id, logbook_id, nomor_versi, aktivitas/hasil/kendala/rencana, catatan, actor, timestamps, snapshot_data JSONB, snapshot_hash | PK; FK logbook RESTRICT; actor FK users SET NULL; version >0; UNIQUE(logbook,version) | 005, 020, 022; `Logbook`; schema:532-563, additions 1452-1454 | Trigger immutability terkait signature pada 022; include semua fields required by model. |
| `logbook_harian` | id, logbook_id, tanggal, jam, kegiatan, kehadiran, alasan, bukti path, validasi, validator, timestamps | PK; FK logbook RESTRICT, validator users SET NULL; UNIQUE(logbook,tanggal); status checks; alasan wajib jika tidak hadir; jam pulang>=masuk | 020-022; `Logbook`; schema:1456-1495 | Trigger 021/022 wajib terpasang untuk target yang mengandalkan DB hardening. |
| `logbook_tanda_tangan` | id/revisi_id/tahap/penanda tangan/keputusan; signature_path/hash, snapshot_hash, metadata JSONB; timestamp/IP/user agent dari migration | PK/FK revisi dan user; tahap CHECK; unique tahap-per-revision dalam migration 005 | 005, 020-022; `Logbook`, `LogbookFileController`; schema:565-630, additions 1486-1492 | Kolom/trigger harus direkonsiliasi seluruhnya, bukan hanya tabel snapshot awal. |
| `template_laporan` | id, jenis, nama, file path, versi, aktif, timestamps | PK; jenis CHECK; versi>0; unique partial satu aktif per jenis | 024; `TemplateLaporan`, `LaporanController`; schema:632-654 | Template seed 026/027 harus dimasukkan terpisah/idempotent dan file fixture tersedia. |
| `laporan_magang` | id, penempatan_id, jenis, template_id, minggu_ke, judul, file_path, versi, upload/status/times | PK; FK placement/template RESTRICT; type/week consistency; version>0; status/timing CHECK; unique weekly per minggu dan satu final per placement | 006 + 024; `LaporanMagang`, `LaporanController`; schema:661-735 | Semantik reviewer status belum ditetapkan; schema enum lebih luas dari workflow yang diketahui. |
| `laporan_revisi` | id, laporan_id, nomor_versi, file_path, ringkasan, catatan_revisi, uploader, uploaded_at | PK; FK laporan RESTRICT, user SET NULL; version>0; UNIQUE(laporan,version) | 006 + 024 index; `LaporanRevisi`; schema:742-765 | Cukup untuk version history laporan, bukan dokumen pengajuan. |
| `pemeriksaan_laporan` | id, laporan_id, pemeriksa_id, tahap, status, catatan, waktu | PK; FK laporan/users RESTRICT; tahap/status CHECK; tanggal wajib setelah keputusan | 024; `PemeriksaanLaporan`; schema:772-810 | Urutan/pemicu/assignment/transisi status BELUM DIPUTUSKAN. |
| `penilaian_magang` | id, placement, penilai user, peran, 5 aspek, nilai akhir manual, catatan, status/times | PK; FK placement/users; unique placement+penilai+role; role/status/range checks | 006; `PenilaianMagang`; schema:817-874 | Formula/bobot/kelulusan tidak ditetapkan; nilai akhir tetap manual. |
| `verifikasi_penyelesaian_magang` | id, placement, verifier user, status, catatan/times | PK; placement UNIQUE FK RESTRICT; verifier FK users; status CHECK | 006; `Pengalaman` reads; schema:881-908 | Prasyarat, actor role, outcome effects BELUM DIPUTUSKAN. |
| `portofolios`, `portofolio_teknologi` | Portfolio id/student/title/slug/type/description/links/role/publication/verification/timestamps; technology composite key | PK; FK profil/users; slug UNIQUE; status/type CHECK; technology PK(portfolio, technology), FK CASCADE | 007, 010; `Portofolio`; schema:974-1055 | Snapshot includes 010 objects but its ledger entry is absent. |
| `notifikasi`, `aktivitas_pengguna` | Notification owner/content/type/reference/read/timestamps; activity actor/action/description/module/reference/IP/time | PK; notification FK user CASCADE and kind/read consistency; activity actor FK SET NULL | 008; `AktivitasPengguna`, workflow models; schema:1186-1265 | References are polymorphic ids without FK in snapshot; preserve as existing design, no inferred constraints. |
| `sertifikat`, `pengalaman` | User/profile-linked credential or experience data | PK/FK, checks per 011/018 | 011, 018; `Sertifikat`, `Pengalaman`; schema:1275+, 1354-1375 | Exact additional consumer columns should be checked before DDL generation; type shape follows checked-in snapshot. |

Not every column in the long legacy feature surface can be exhaustively certified by this proposal. The table lists core workflow columns grounded in schema and model reads/writes. Full canonical DDL generation requires a column-reference scan for every model/controller and an explicit approval of each remaining optional field/constraint.

### Evidence pointers for matrix rows

- Identity/profile/type base: `database/schema.sql:19-90`; corresponding user/profile queries in `models/User.php`, `models/ProfilMahasiswa.php`, and `models/DashboardMahasiswa.php`.
- Mitra/formasi exact column use: `database/schema.sql:96-238`; `models/FormasiMitra.php:13-74,77-115,124-162,219-272`, `models/FormasiMagang.php`, and `models/DashboardMitra.php`. The alternate declarations are `database/migrations/012_create_mitra.sql:4-50` and `013_create_formasi_magang.sql:4-57`; original shape is `002_create_mitra_and_formasi.sql:7-153`.
- Application, document and history fields: `database/schema.sql:244-360,1333-1347`; `models/PendaftaranMagang.php:7-50,54-90`, `models/PersetujuanPengajuan.php:38-73,76-185,188-219`, `models/PengajuanMitra.php:10-46,49-174`, `controllers/dashboard/mahasiswa/LamarController.php:44-80,94-120`.
- Placement and advisor request: `database/schema.sql:383-423,445-474`; `models/PenempatanMagangCoordinator.php:10-105`, `models/PermintaanPembimbing.php:10-110,113-208,210-315`.
- Logbook tables/trigger contract: `database/schema.sql:481-630,1456-1495,1508-1874`; `models/Logbook.php:15-229,260-448,450-741,823-933,1131-1227`.
- Reports/review/assessment/completion: `database/schema.sql:632-908`; `models/LaporanMagang.php:23-333`, `models/LaporanRevisi.php`, `models/PemeriksaanLaporan.php:18-134`, `models/PenilaianMagang.php:18-185`, `models/Pengalaman.php:10-30,179-199`.
- Portfolio and relation tables: `database/schema.sql:974-1184`; `models/Portofolio.php:21-90,194-220,302-436` and certificate/experience models. Note 010's additional columns/table are defined in `database/migrations/010_adjust_portofolio_for_form.sql:4-23`.
- Notification/activity: `database/schema.sql:1186-1267`; `models/AktivitasPengguna.php:9-47` and transaction workflow models.

## 3. Minimum dependency order for a reconciled fresh baseline

Order is a topological proposal, not an apply script:

1. `schema_migrations` baseline marker mechanism and `users`.
2. `profil_mahasiswa`, `profil_dosen`.
3. Canonical `mitra`, then `formasi_magang`.
4. `pendaftaran_magang`, `riwayat_pendaftaran_magang`, `dokumen_pendaftaran_magang`.
5. `penempatan_magang`, `permintaan_pembimbing` (029-equivalent, once only).
6. Legacy weekly logbook/revision/signature parent, then daily logbook and hardening functions/triggers.
7. `template_laporan`, `laporan_magang`, `laporan_revisi`, `pemeriksaan_laporan`.
8. `penilaian_magang`, `verifikasi_penyelesaian_magang`.
9. Portfolio/skills/technology, certificate, experience.
10. Notifications/activity, views and all indexes after their referenced objects.
11. Seed data only after DDL verification: required templates are a separate idempotent step, fixture user/data seeders separate again.

Important: constraints must be defined before fixture rows are inserted; trigger functions must be created only after all referenced columns and tables exist. Migration 028 view depends on canonical Mitra/Formasi columns; it belongs after those objects and their dependent registration/placement/profile tables.

## 4. Options A/B/C and risk

### A — Fresh baseline baru, migration lama dipertahankan

Pros: isolates new test bootstrap from ambiguous history; can define canonical schema once and record one explicit baseline marker; leaves deployed databases untouched. Cons: creates a new supported path and requires approval/maintenance policy; a baseline assembled from snapshot alone can carry stale/missing ledger or accidental seed data. Existing databases must never be pointed at a fresh-bootstrap command.

Risk/ledger: low risk to existing DB if tool refuses nonempty schemas and baseline marker already exists; high risk if guard is merely a shell convention. Do not execute schema and then mark old migrations 000–029 applied. Record only the baseline marker actually installed.

### B — Repair old migration chain

Pros: one incremental history can theoretically build fresh and upgrade existing DB. Cons: changing shared migrations is disallowed without evidence; adding forward migrations cannot resolve duplicate create-table files on clean replay unless chain orchestration/baseline explicitly skips one branch. Existing data may need column rename/copy/backfill, unique/check conflict handling and schema inspection.

Risk/ledger: highest risk to deployed DB; migration contents may already have run partially or manually. Needs source/deployment history and DB-owner approval; do not rewrite history or synthesize ledger rows.

### C — Use `database/schema.sql` as initial DDL

Pros: file claims to be a consolidated schema snapshot and includes many final structures, including 029-equivalent and view/template definitions. Cons: it currently inserts template data unguarded, contains an incomplete/inconsistent migration ledger (notably omits 009/010 among others), can include historical migration conflict commentary, and is not a verified executable bootstrap with an emptiness guard. Schema source does not prove it equals production or all runtime queries.

Risk/ledger: moderate for a truly empty disposable DB after review, unacceptable for any DB with existing objects/data. If selected, first derive a new reviewed baseline artifact from reconciled DDL, split seed data, remove fabricated historical ledger entries, and add explicit baseline marker; do not execute this snapshot unchanged.

Recommendation: Option A for testing after review, using C only as one input to a fresh reviewed baseline. Keep B as a separate future upgrade project for known existing databases. This recommendation is design advice, not owner approval or proof of historical baseline.

## 5. Ledger and seed policies proposed

- A fresh baseline should create ledger table, then insert exactly one explicit baseline identifier only after all DDL committed successfully, in the same transaction if one script is used.
- A baseline guard must refuse to run if non-system application objects already exist, or if its baseline marker exists. Do not use `DROP`, truncate, or reset to make it pass.
- Migration application remains manual unless an approved runner is later introduced. A runner, if proposed, must use a reviewed allowlist; never discover and blindly execute every `.sql` in lexical order (025 is SELECT, local rekap CTE files are not migrations, 026/027 are data seed scripts, and 012/013 conflict).
- Keep `025_verify_laporan_workflow.sql` as an explicitly read-only verification script; do not record it as DDL applied.
- Do not embed template rows in the structural baseline. Add a separate seed step with uniqueness/active-template checks and deterministic conflict behavior, after verifying DOCX files exist. Migration 026 is not safely replayable as written; 027's `ON CONFLICT DO NOTHING` does not establish that the desired active row is present. Operator approval required to replace/retire these seed paths for the fresh baseline.
- Migration 029 can either be included once in the approved baseline and its legacy migration not run on that baseline, or remain a post-baseline incremental migration. Choose one path per DB; never both. Existing DBs require ledger/schema read-only inspection first.
- Keep local CTE reports outside the migration list; verify referenced status enums against canonical status checks before relying on results. `rekap_logbook_koordinator.sql` includes weekly states that are not the active final weekly status set; its semantics need separate review before use as a canonical view.

## 6. Existing DB compatibility boundary

This proposal does not authorize, cover, or predict upgrades to any existing development, shared, staging, or production DB. Before a separate compatibility plan:

1. Obtain explicit DB owner and target authorization.
2. Take a restorable backup using the operator's approved process.
3. Use read-only catalog and data checks to establish server/database identity, ledger entries, table/column/constraint/function/trigger/view definitions, document kind values, and existing template rows.
4. Compare actual state to canonical schema; do not infer applied migrations from filenames or snapshot ledger inserts.
5. Write forward-only migration(s) with data preservation, preconditions, rollback/backup plan, and tested path on a clone of that exact schema.

If short document aliases exist, migration 017's final check will reject them unless data is normalized or compatibility is intentionally retained. Do not normalize or drop constraints without an approved data migration.

## 7. Read-only validation/equivalence plan after approval

For fresh DB and a cloned existing DB separately:

- Compare `pg_catalog` table/column types, nullability, defaults, PK/FK/unique/check constraints and indexes with the approved canonical manifest.
- Compare trigger/function definitions (especially 021/022), view definitions (028), and ledger marker(s).
- Verify required template rows and document kinds by SELECT; no duplicate active templates per kind.
- Run schema-diff tooling in read-only mode against the two targets and review differences; no automatic DDL apply.
- Seed fixture data only into the fresh disposable target after schema checks; never use production-derived personal data without separate approval.

## 8. Approval gates

Before any executable baseline is authored/applied, project owner must approve: canonical Mitra/Formasi structure; baseline marker naming/ledger convention; whether baseline is generated from reconciled snapshot or explicit DDL; inclusion/omission of object 029; seed template policy; compatibility treatment for old document aliases; PostgreSQL supported version; and the target identity/cleanup procedure.

## 9. Checklist keputusan pemilik proyek (belum disetujui)

Status setiap keputusan di bawah tetap **OPEN**. Fakta repository dan usulan teknis bukan persetujuan. Jangan membuat atau menerapkan executable baseline sebelum pemilik proyek menandai keputusan yang relevan telah disetujui.

| ID / keputusan | Fakta yang dapat dibuktikan dari source | Usulan untuk ditinjau (bukan keputusan) | Persetujuan pemilik |
|---|---|---|---|
| BASE-01 — Struktur kanonis Mitra | Source/model saat ini menggunakan `mitra.user_id`, `nama_perusahaan`, `kode_perusahaan`, `is_active`; migration 002 membuat bentuk serupa. Migration 012 membuat ulang tabel dengan bentuk berbeda (`nama`, `slug`, `logo_path`, tanpa beberapa kolom pemakai aplikasi). | Reconcile snapshot dengan consumer aktual, lalu pilih bentuk canonical yang memenuhi seluruh query dan kontrak; jangan pilih hanya berdasarkan urutan/nama migration. | [ ] OPEN — pilih/konfirmasi DDL dan kompatibilitas data |
| BASE-02 — Struktur kanonis Formasi Magang | Source/model menggunakan `judul`, `jumlah_kuota`, `jumlah_diterima`, `sistem_kerja`, periode/status; 002 konsisten dengan consumer, 013 mendefinisikan ulang nama kolom/status yang berbeda. | Bentuk canonical harus ditelusuri ke semua model/controller dan constraints pada snapshot sebelum dijadikan baseline. | [ ] OPEN — konfirmasi kolom, status, constraint, dan FK |
| BASE-03 — Marker `schema_migrations` | Ledger saat ini hanya mencatat `id`, `migration`, `executed_at`; strategi repository adalah SQL manual + ledger manual, tanpa runner. | Untuk fresh DB gunakan satu marker baseline eksplisit, misalnya `baseline_magangverify_v1.sql`; jangan mencatat 000–029 seolah-olah dijalankan. Checksum/kolom ledger tambahan belum dirancang. | [ ] OPEN — nama/format marker dan kebijakan incremental setelah baseline |
| BASE-04 — Seed template laporan | 026 menambahkan template aktif dengan insert yang tidak aman diulang; 027 memakai `ON CONFLICT DO NOTHING` namun tidak menjamin row aktif/versi yang diinginkan. File DOCX harus tersedia. | Pisahkan data seed dari DDL, definisikan pemeriksaan uniqueness/active-template serta verifikasi file; buat seed idempotent hanya setelah konten disetujui. | [ ] OPEN — template wajib, versi/file, dan strategi seed |
| BASE-05 — Migration 029 | File `029_create_permintaan_pembimbing.sql` tersedia dan membuat tabel/ledger 029; belum ada bukti ia diterapkan pada target mana pun. | Pilih satu jalur: objek setara dimasukkan ke approved baseline lalu 029 tidak dijalankan pada DB itu, atau tetap incremental setelah baseline. Jangan lakukan keduanya. | [ ] OPEN — baseline inclusion atau post-baseline migration |
| BASE-06 — Alias jenis dokumen lama | Migration 017 dan PRD menetapkan `bpjs_asuransi`/`proposal_magang`; migration sebelumnya memakai `bpjs`/`proposal`. Data pada DB target belum dibaca. | Untuk DB fresh pakai kind final. Untuk DB existing, tentukan kebijakan kompatibilitas/normalisasi hanya setelah inspeksi read-only data dan rencana migrasi disetujui. | [ ] OPEN — perlakuan alias lama dan syarat sebelum constraint final |
| BASE-07 — Versi PostgreSQL | Snapshot menggunakan identity columns dan trigger/function PostgreSQL; E2E plan mencatat identity memerlukan PostgreSQL 10+, tetapi versi deployment/test yang disetujui belum diketahui. | Pilih versi minimum yang didukung dan uji DDL/trigger terhadap versi tersebut sebelum baseline diterapkan. | [ ] OPEN — versi minimum/target yang disupport |
| BASE-08 — Target disposable dan cleanup | Tidak ada target DB disposable yang identitas/isolasinya telah dibuktikan. Container/port mapping bukan bukti sendiri bahwa target terpisah. | Gunakan instance khusus loopback, tanpa volume data bersama; buktikan identitas DB dan catalog read-only sebelum test. Tetapkan siapa yang berwenang menghentikan/menghapus resource dan kapan; jangan menghapus sesuatu yang tidak dibuat oleh operator test. | [ ] OPEN — target, operator, bukti identitas, masa hidup, cleanup/retensi |

### Gate sebelum baseline executable

- [ ] Semua BASE-01 s.d. BASE-08 yang relevan ditinjau dan disetujui tertulis.
- [ ] Baseline DDL direkonsiliasi dengan model/controller, trigger, view, dan constraint; seed template tetap terpisah.
- [ ] Target benar-benar kosong/disposable dibuktikan lewat identitas read-only dan pemeriksaan object catalog; tidak mengandalkan nama database/host.
- [ ] Otorisasi eksplisit untuk operasi yang akan mengubah DB diberikan terpisah. Checklist ini sendiri bukan izin menjalankan migration, seed, atau query tulis.
