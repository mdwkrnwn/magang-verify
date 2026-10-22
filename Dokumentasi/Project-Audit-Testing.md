# MagangVerify — Catatan Audit dan Pengujian

## Status

Dokumen ini mencatat audit sumber yang dilakukan dan batasan verifikasi saat ini. Database tidak dibaca atau ditulis saat audit ini; `database/schema.sql` adalah snapshot repository, bukan bukti kondisi server/database aktual.

Status overall: DIIMPLEMENTASIKAN, BELUM TERUJI PENUH.

## Sumber yang diperiksa

- `Dokumentasi/prd/prd.md` dan seluruh `Dokumentasi/Developer/`.
- Dokumentasi logbook, migrasi, dan testing yang tersedia.
- `database/schema.sql` dan nama/definisi migrasi di repository secara read-only.
- Diagram use case, ERD, laporan PBL, dan PDF contoh laporan pada `rancangan/`.
- Entry point, route, autentikasi, helper, pola model/controller, logbook, laporan, dashboard role, dan seeders terkait.

Diagram use case memuat enam domain: akses publik/autentikasi; portofolio mahasiswa dan verifikasinya; pengajuan/pengelolaan magang termasuk permintaan dosen pembimbing; logbook mingguan; laporan magang; administrasi. Aktor yang tampak: Tamu, Mahasiswa, Mitra, Dosen Pembimbing, Koordinator Magang, dan Tendik/Manajemen. PDF ERD membedakan `permintaan_pembimbing` sebagai rancangan belum tersedia di SQL dari entitas yang sudah ada. Laporan PBL adalah rancangan yang lebih awal; PRD terbaru tetap menjadi acuan aturan bisnis. PDF laporan Fikri merupakan contoh struktur laporan industri, bukan template resmi aplikasi.

## Perubahan kode pada audit ini

- `controllers/dashboard/dosen/LogbookController.php`: aktifkan alur queue, detail, dan tanda tangan dosen yang sebelumnya dikomentari. Detail menggunakan query role-scoped dan tanda tangan melewati validasi CSRF serta validasi workflow di model.
- `components/dashboard/dosen/logbook/LogbookList.php`, `Statistics.php`, dan `SearchFilter.php`: ganti data contoh dan angka statis dengan antrean tanda tangan dosen serta ringkasan dari model. Tombol review mengarah ke detail yang benar.
- `models/DashboardDosen.php`, `controllers/dashboard/dosen/MahasiswaBimbinganController.php`, dan komponen dashboard/bimbingan dosen: daftar penempatan, label status, dan statistik bimbingan diambil berdasarkan profil dosen yang terhubung ke user; progress buatan dihilangkan.
- `controllers/dashboard/dosen/DosenDashboardController.php` dan komponen dashboard dosen: beranda dosen memakai data penempatan bimbingan dan antrean logbook aktual.
- `models/PermintaanPembimbing.php`, dua controller dan halaman role, route/sidebar, serta migration `029_create_permintaan_pembimbing.sql`: implementasi request dosen pembimbing, keputusan target dosen, dan assignment pada `profil_dosen.id` dibuat sesuai rancangan ERD.
- `models/PersetujuanPengajuan.php` dan komponen dosen: antrean persetujuan memakai pendaftaran pending aktual; persetujuan/penolakan mengunci record, mencatat status/riwayat/aktivitas/notifikasi dalam transaksi, serta memeriksa enam dokumen wajib sebelum approval.
- `models/PengajuanMitra.php` dan halaman Mitra: respons diterima/ditolak/seleksi dibatasi ke pengajuan perusahaan sendiri; penerimaan memeriksa dan menaikkan kuota di transaksi, menyimpan riwayat dan notifikasi. Dokumen aplikasi hanya diakses melalui endpoint yang memeriksa role dan relasi.
- `models/PenempatanMagangCoordinator.php` dan halaman Koordinator: penempatan dapat dibuat hanya dari pengajuan diterima yang belum mempunyai penempatan; pembuatan memberi notifikasi mahasiswa.
- Dashboard Koordinator memakai view rekap dan ringkasan logbook; dashboard Tendik memakai antrean validasi ketidakhadiran; dashboard Mitra memakai formasi, pelamar, penempatan, dan antrean logbook milik perusahaan.
- `models/FormasiMitra.php` dan route/UI Mitra: create/read/update/status/delete formasi dengan owner checks, status verifikasi/keaktifan mitra, kuota, validasi tanggal/sistem kerja, CSRF, audit log, serta larangan mengubah/menghapus formasi yang sudah memiliki pengajuan.
- `models/PenilaianMagang.php` dan route/UI Dosen-Mitra: tiap penilai dapat mengisi draft/submit penilaian pada penempatan dalam kewenangannya. Nilai divalidasi 0–100 dan tidak dihitung otomatis.
- `models/PersetujuanPengajuan.php`, `models/PengajuanMitra.php`, dan route dokumen role: alur approval Dosen → respons Mitra menjaga status dan riwayat, kuota hanya bertambah saat diterima, dokumen wajib diperiksa sebelum approve, dan berkas pengajuan privat disajikan via endpoint dengan relasi dan MIME check.
- `models/PenempatanMagangCoordinator.php`: Koordinator dapat membuat penempatan hanya dari pendaftaran diterima yang belum ditempatkan; periode divalidasi, penempatan baru berstatus persiapan dan dosen nullable sesuai schema.
- `router.php`: akses static langsung ke `storage/pendaftaran/` diblokir agar file dokumen pengajuan hanya dikirim lewat endpoint berotorisasi.
- `controllers/LoginController.php`: setiap role, termasuk mitra, kini wajib memiliki password valid; request login POST wajib melewati CSRF.
- `components/login/Form.php`: tambahkan token CSRF pada form dan jadikan password wajib di browser.
- `database/seeders/SeedTestUsers.php`: akun baru `mitra_test` memakai hash password testing; akun bernama sama yang sudah ada dilewati tanpa ditimpa.
- `database/seeders/SeedFormasiMagang.php`: seeder tidak lagi mengubah mitra yang sudah ada menjadi terverifikasi/aktif. Jika record mitra seed yang ditemukan tidak memenuhi syarat, proses gagal dengan pesan yang jelas.

File SQL lokal yang dibuat/diubah: `database/migrations/029_create_permintaan_pembimbing.sql` dan `database/schema.sql`. Keduanya hanya disiapkan; migration tidak diterapkan dan tidak ada query tulis yang dijalankan. File lokal `database/migrations/rekap_logbook_koordinator.sql` dan folder `rancangan/` tetap dipertahankan.

## Akun pengujian dari seeders

Seeders yang tersedia menyebut akun berikut. Password ini hanya untuk lingkungan testing yang terisolasi.

- Mahasiswa: `mhs_dimas` / `Password123!`
- Dosen: `dosen_seed` / `Password123!`
- Mitra: `mitra_seed` / `Password123!`
- Mahasiswa: `mhs_andi`, `mhs_budi`, `mhs_citra`, `mhs_eka` / `Password123!`
- Mahasiswa testing: `mhs_test` / `MhsTest123!`
- Dosen testing: `dosen_test` / `DosenTest123!`
- Koordinator testing: `koord_test` / `KoordTest123!`
- Tendik testing: `tendik_test` / `TendikTest123!`
- Mitra testing: `mitra_test` / `MitraTest123!` (untuk akun baru yang dibuat oleh seeder versi ini)

Fixture permintaan pembimbing: setelah akun dan formasi testing dibuat, `database/seeders/SeedPermintaanPembimbing.php` membuat skenario `mhs_test` mengajukan `dosen_test`.

Jalankan seeder hanya setelah operator memastikan target DB lokal khusus testing dan isinya boleh ditambah. Jangan jalankan pada database Supabase/produksi. Perintah seeders tidak dijalankan dalam audit ini. Jika `mitra_test` sudah ada dari seed lama dengan password kosong, seeder versi ini melewatinya agar tidak mengubah data; akun tersebut belum dapat dipakai untuk login sampai passwordnya diatur secara aman pada database testing.

Urutan test yang terdokumentasi untuk logbook Dimas: seed akun/profil dan mitra/formasi terlebih dahulu sesuai README seeder, lalu jalankan `SeedDimasLogbook.php`; login mahasiswa, isi hari-hari periode, uji tanggal depan/duplikasi, tanda tangan mahasiswa, edit sebelum tanda tangan mitra, lalu uji tanda tangan mitra dan dosen. Alur laporan mingguan bergantung pada logbook minggu yang sama.

## Pengujian yang dilakukan

- Pemeriksaan sintaks PHP terhadap seluruh file PHP terlacak dan untracked di luar `vendor/` dan `node_modules/`: lolos pada pemeriksaan awal.
- Pemeriksaan sintaks ulang pada file PHP yang diubah: lolos.
- `git diff --check`: lolos.
- `php tests/smoke_views.php`: lolos, 7 komponen (logbook/bimbingan/pengajuan/penilaian dosen serta dashboard Koordinator/Tendik/Mitra) dengan fixture in-memory dan assertion escaping/tautan.
- Render smoke test daftar logbook dosen, daftar bimbingan dosen, dashboard statistik logbook, dan halaman permintaan pembimbing mahasiswa/dosen: assertion lolos dengan data fixture in-memory; header profile mencatat bahwa PDO tidak tersedia pada smoke test tanpa database.
- Pemeriksaan statis migration 029: tabel/FK/check/index/registry terdeteksi dan tidak berisi DROP/TRUNCATE/DELETE. SQL tidak dieksekusi.
- Render smoke test halaman dosen untuk antrean pengajuan dan file dokumen fixture: assertion lolos; header profil mencatat PDO tidak tersedia pada pengujian render tanpa database.
- Render smoke test daftar/formasi Mitra dan tabel antrean review Dosen: assertion lolos dengan fixture; belum menguji SQL/PDO atau aksi POST terhadap database.
- Render smoke test form create dan list formasi Mitra: assertion lolos; header profile mencatat PDO tidak tersedia pada render tanpa database.
- Tidak ada test suite PHPUnit atau command test yang didefinisikan pada `composer.json`/`package.json` yang diperiksa.
- Seeder dan alur UI/DB tidak dijalankan. Login end-to-end, integritas FK aktual, trigger, akses berdasarkan role, upload, serta unduhan belum diverifikasi dengan database/browser.

## Temuan dan pekerjaan tersisa

- Schema aktual server belum diverifikasi; hanya snapshot schema repository yang tersedia untuk analisis ini.
- Migration `029_create_permintaan_pembimbing.sql` belum diterapkan. Fitur permintaan pembimbing sudah ditambahkan pada kode tetapi DIIMPLEMENTASIKAN, BELUM TERUJI PENUH sampai migration tersebut diterapkan secara manual ke database testing dan alur semua role diuji.
- Diagram use case mencakup pengelolaan data mahasiswa/dosen/mitra/formasi/periode/template dan penetapan status lulus. CRUD formasi Mitra dan input penilaian Dosen/Mitra tersedia; CRUD administrasi Koordinator/Tendik, verifikasi penyelesaian, dan testing end-to-end masih belum tuntas.
- Belum semua aksi pemeriksaan laporan, verifikasi portofolio/sertifikat, administrasi LOA, serta variasi pengajuan diuji. Sumber PBL menggambarkan proses pengajuan bertahap kampus dan pengelolaan LOA; implementasi harus mengikuti aturan PRD terbaru dan schema, bukan mengasumsikan proses awal identik.
- `database/schema.sql` sendiri mencatat bahwa migrasi 012 dan 013 mendefinisikan ulang mitra/formasi yang sudah dibuat migration 002. Sejumlah migration yang lebih baru juga harus dinilai terhadap riwayat schema_migrations aktual sebelum fresh install atau penerapan. Jangan jalankan ulang snapshot sebagai migration.
- Login Mitra memakai kode perusahaan dari profil `mitra`, bukan `users.login_id` atau password. Akun harus tertaut melalui `mitra.user_id`, memiliki `users.role='mitra'`, dan kedua record harus aktif. Role lain tetap mencari `users.login_id` dan memverifikasi password hash.
- Tidak ditemukan route registrasi akun publik, model create user, atau controller provisioning akun Mitra. Pembuatan akun aplikasi yang terbukti pada source terbatas pada `database/seeders/SeedTestUsers.php` untuk akun synthetic testing (password di-hash saat insert baru); seeder tidak memperbarui akun dengan login ID yang sudah ada. Jangan membuat registrasi publik tanpa keputusan produk. Provision akun Mitra operasional memerlukan proses admin/Koordinator yang disetujui atau mekanisme provisioning eksternal yang aman; belum ditemukan fitur UI admin akun pada routes saat ini.
- Login menelusuri form POST `/login` → `LoginController` (CSRF; Mitra lewat `mitra.kode_perusahaan` dan relasi `mitra.user_id`, role lain lewat `users.login_id` + password hash) → session regeneration dan `$_SESSION['user']` → dashboard role. `AuthMiddleware` memuat ulang user/active/role dari DB; Mitra diarahkan ke `/dashboard/mitra`. Database dan session runtime belum diuji.
- Login/logout evidence regression checks memeriksa form password, role script tanpa prefill, password verification, session, dashboard redirect, middleware, serta CSRF/logout. Ini source/render checks, bukan pengujian kredensial aktual.
- Login Mitra tanpa password adalah autentikasi berbasis kode yang dapat diketahui/ditebak; ini bukan autentikasi kuat dan memungkinkan pengambilalihan akun. Belum ditemukan pembatasan percobaan login. Sediakan rate limiting dan pertimbangkan faktor rahasia tambahan melalui keputusan keamanan terpisah, tanpa menyamarkan risikonya.

- Status `perlu_revisi` untuk persetujuan pengajuan belum memiliki alur unggah ulang dokumen dan version history yang konsisten; controller dosen saat ini hanya menawarkan setuju/tolak agar tidak membuat transisi tanpa jalur pemulihan.
- Persetujuan pengajuan dosen belum memiliki penugasan reviewer pada schema. Sesuai kolom yang tersedia, antrean `menunggu_persetujuan_dosen` dapat diproses oleh role dosen mana pun; scope ini perlu dikonfirmasi bila kampus menghendaki penugasan dosen spesifik.
- Pengelolaan formasi Mitra telah tersedia; antarmuka admin Koordinator/Tendik untuk mengelola data seluruh mitra/formasi belum selesai.
- Penilaian menyimpan aspek individual dan nilai akhir manual opsional tanpa formula. Schema/dokumentasi tidak menjelaskan bobot, syarat lulus, pemeriksa akhir, atau kapan verifikasi penyelesaian dapat diberikan; tidak dibuat keputusan lulus otomatis.

## Cara verifikasi lanjutan

## Pemeriksaan lanjutan repository

- Route `/dashboard/mitra/penilaian` hanya terdaftar sekali di `routes/web.php` (entry static pada baris 347); tidak ada route duplikat yang perlu diperbaiki.
- `php tests/smoke_views.php` lulus dan menampilkan `Repository view smoke checks passed (7 components).`
- `git diff --check` lulus.
- Pemeriksaan `php -l` terhadap seluruh file PHP yang ditemukan di luar `vendor/` dan `node_modules/` lulus. Ini hanya pemeriksaan sintaks dan render fixture, bukan pengujian integrasi/database/browser.
- Workflow pemeriksaan laporan belum aman untuk diselesaikan hanya dengan memakai tabel `pemeriksaan_laporan`: tabel memiliki tahap `dosen`, `koordinator`, `tendik` dan status keputusan, tetapi dokumen bisnis tidak menetapkan urutan wajib, pemicu penugasan/antrean tiap tahap, hubungan pemeriksaan dengan transisi status laporan, atau aturan apakah keputusan `ditolak` terminal. Tidak ditambahkan endpoint keputusan yang akan mengunci asumsi tersebut.
- Unggah ulang laporan mahasiswa sudah memiliki jejak versi pada `laporan_revisi` dan status `perlu_revisi`, namun tidak tersedia alur pemeriksa yang menetapkan status/keterangan revisi. Revisi pengajuan magang juga masih terblokir sebagaimana dicatat di bawah: belum ada aturan konsisten untuk unggah ulang/version history dokumen pengajuan.
- Administrasi Koordinator/Tendik yang memberi kemampuan mengubah seluruh data belum ditambahkan: PRD membatasi kewenangan monitoring/validasi, dan dokumen role melarang pemberian hak edit menyeluruh tanpa aturan kewenangan yang spesifik.

### Inventaris fitur Koordinator dan Tendik

| Status | Fitur yang diwajibkan dokumentasi | Dukungan aktual / kelayakan |
|---|---|---|
| Sudah tersedia | Tendik: melihat antrean validasi ketidakhadiran, memeriksa detail/alasan, menyetujui atau menolak dengan catatan | `Logbook::getReviewQueue`, `getWeekForRole`, `getDailyForValidator`, `validateDaily`; controller `TendikLogbookController`; route daftar/detail/validasi; halaman detail dengan CSRF. Sesuai PRD yang membolehkan validasi Tendik/Koordinator. |
| Sudah tersedia | Koordinator: monitoring mahasiswa/penempatan/logbook, laporan ringkas, kontrol penempatan yang ditetapkan | Dashboard `KoordinatorMagangDashboard`, halaman antrean logbook/validasi, dan controller penempatan tersedia. Operasi penempatan terbatas pada pengajuan diterima yang belum punya penempatan. |
| Belum tersedia | Tampilan terpisah yang hanya memuat antrean validasi khusus Tendik | Secara perilaku antrean sudah ada, tetapi model yang sama juga dipakai Koordinator. Aturan sumber tidak mengharuskan antrean terpisah; tidak perlu duplikasi. |
| Belum tersedia | Pengelolaan global data mahasiswa/dosen/mitra, periode, template, LOA atau CRUD administrasi luas | Use-case/rancangan menyebut domain administrasi, tetapi PRD dan dokumen role tidak menentukan cakupan record, batas kepemilikan, field yang boleh diubah, state transitions, atau workflow persetujuannya. Model/schema yang ada saja tidak cukup untuk memberi kewenangan edit global; implementasi ditahan. |
| Belum tersedia | Workflow pemeriksaan laporan oleh Koordinator/Tendik | Schema memiliki tahap/status pemeriksaan, tetapi belum ada aturan pemicu, urutan, assignment, dampak terhadap status laporan, maupun terminalitas penolakan. Belum cukup untuk implementasi aman. |
| Belum tersedia | Verifikasi penyelesaian magang | PRD menyebut tabel `verifikasi_penyelesaian_magang`, tetapi aturan pemeriksa, prasyarat, field/hasil, dan transisi penempatan tidak ditetapkan di role docs; belum cukup bukti untuk mutation. |
| Belum jelas kewenangannya | Koordinator dan Tendik sama-sama dapat memutuskan validasi bukti ketidakhadiran | Dokumen PRD/role menamai keduanya pada validasi, tetapi tidak menjelaskan pembagian kasus, eksklusivitas, atau siapa yang memutus saat konflik. Kode existing mengizinkan kedua role pada antrean tersebut; perlu keputusan jika akses eksklusif dimaksudkan. |
| Belum jelas kewenangannya | Kontrol workflow Koordinator dan administrasi Tendik selain validasi logbook | Dokumen menyebut kontrol/administrasi secara umum, tanpa aksi dan batas otorisasi konkret. Tidak boleh ditafsirkan sebagai hak edit semua data. |

Fitur yang dipilih untuk dikerjakan: mengamankan keputusan validasi ketidakhadiran agar hanya satu permintaan dapat memutus record berstatus `menunggu`. Backend memakai conditional update dan memeriksa row count sebelum membuat revisi/snapshot; kegagalan tetap menggagalkan transaksi. Audit akses file juga menemukan bahwa `router.php` belum memblokir `/storage/laporan/`, walaupun laporan diunduh melalui controller berotorisasi. Prefix tersebut kini ikut ditolak untuk mencegah bypass controller. `Logbook::getSignatureForUser` kini menolak akses signature untuk Tendik/Koordinator (dokumentasi hanya memberi akses bukti ketidakhadiran), sedangkan `getEvidenceForUser` membatasi kedua role tersebut ke ketidakhadiran berstatus `menunggu`. Route, auth role, CSRF, catatan, dan activity log untuk keputusan validasi tetap memakai implementasi existing.

### Hasil verifikasi tahap ini

- `php tests/workflow_static_checks.php` — lulus; memeriksa source guard conditional validasi, urutan cek row count sebelum revisi, otorisasi file logbook, private cache headers, serta pemblokiran static `/storage/laporan/`. Ini source-level assertion, bukan runtime authorization/integration test.
- `php tests/smoke_views.php` — lulus untuk 7 komponen render; tidak menjalankan mutation model.
- `git diff --check` — lulus.
- Lint file terkait — lulus untuk `models/Logbook.php`, `controllers/dashboard/LogbookFileController.php`, `router.php`, dan `tests/workflow_static_checks.php`.
- Tidak ada koneksi database, migration, atau seeder dijalankan. Race behavior dan conditional update belum diuji terhadap PostgreSQL lokal; upload/unduh juga belum dijalankan melalui browser.

### Audit lanjutan: akses file dan cakupan role

- Menemukan bahwa `.htaccess` root membiarkan file yang sudah ada dilewati rewrite (`!-f`), sehingga deny-list di `router.php` saja tidak cukup untuk mencegah Apache menyajikan file dari storage. `storage/.htaccess` kini menolak seluruh akses HTTP langsung ke storage; controller aplikasi tetap menjadi jalur unduh. Deployment Nginx/servers tanpa Apache `AllowOverride` tetap memerlukan aturan deny setara untuk `/storage/` dan `/uploads/cv/`.
- Pemeriksaan path di `router.php` memakai `function/PrivateStorage.php`, yang mengurai path URL, decoding berulang, menolak traversal/backslash, dan mendeteksi storage privat serta `uploads/cv/` meski ada prefix mount point atau variasi kapitalisasi. Tes mencakup URL biasa, slash ter-encode satu/dua/tiga kali, traversal, backslash, query, casing, prefix mount, exact directory, dan path publik yang tidak boleh ikut diblokir.
- `LaporanController::downloadPrivateFile` sebelumnya menerima path relatif dari database tanpa membatasi root kanonis. Kini hanya path dalam `storage/laporan/` atau `storage/templates/laporan/` yang diterima, dan `realpath` harus tetap berada di direktori izin yang berada di dalam repository. Pengecekan MIME dan ekstensi yang sudah ada tetap berlaku.
- CV mahasiswa memang publik melalui route profil publik, tetapi sebelumnya file fisiknya di `uploads/cv/` bisa diakses statis langsung dan `MahasiswaProfilController::cv` hanya memeriksa prefix path. Helper path kini membatasi file ke direktori kanonis CV; endpoint tetap publik sesuai UI/PRD, memeriksa MIME dan `%PDF-`, sementara router serta `uploads/cv/.htaccess` menolak bypass URL static. Folder upload portofolio tidak diblokir karena dipakai aset portofolio publik.
- `getWeekForRole` sebelumnya membatasi Mitra/Dosen berdasarkan relasi saja dan memberi validator detail minggu apa pun. Berdasarkan gate di `Dokumentasi/Logbook/authorization.md` dan status workflow logbook, detail Mitra kini menunggu mahasiswa sign, detail Dosen menunggu Mitra sign, dan detail Tendik/Koordinator harus mengandung bukti ketidakhadiran berstatus menunggu. Role selain Mahasiswa/Mitra/Dosen/validator ditolak.
- Endpoint bukti logbook kini menerapkan gate status yang sama untuk Mitra/Dosen selain ownership; Tendik/Koordinator hanya dapat mengunduh bukti ketidakhadiran yang menunggu. Signature dapat diakses Mahasiswa pemilik; Mitra hanya setelah mahasiswa sign dan bukan signature tahap Dosen; Dosen hanya setelah Mitra sign; validator tidak mendapat akses signature.
- Alur laporan mingguan yang ada memang memilih minggu logbook dengan `minggu_ke` pada penempatan yang telah lolos ownership, mengambil aktivitas harian dari minggu tersebut, lalu menolak pembuatan jika minggu/logbook belum ada atau kosong. Laporan akhir dibuka saat tanggal akhir penempatan tercapai. Belum ada reviewer/keputusan laporan yang dapat diimplementasikan tanpa aturan bisnis tambahan.
- Aktivitas validasi ketidakhadiran sekarang ditulis sebelum commit; jika `AktivitasPengguna::log` gagal dan mengembalikan ID 0, update status, revisi, snapshot, dan activity seluruhnya di-rollback.
- `LamarController::validPdf` kini menolak ukuran nol, memastikan file benar-benar berasal dari upload PHP, dan mengecek `%PDF-` selain MIME dan ekstensi. Upload aktual tidak dijalankan.
- `resolvePrivateStorageFile` diuji secara perilaku terhadap template DOCX lokal yang diizinkan dan path traversal/absolute/storage lain yang harus ditolak. Ini memverifikasi helper path tanpa mengunduh file lewat HTTP.
- Uji symlink resolver awalnya menemukan directory symlink yang diarahkan ke folder lain di dalam project masih diterima sebagai allowed root. Resolver kini mensyaratkan allowed root canonical sama dengan direktori allowlist yang dideklarasikan. Tes menguji safe file, file symlink keluar, dan allowed-directory symlink; tes path helper lulus setelah perbaikan.
- `config/env.php` tidak lagi membaca `.env` jika semua variabel koneksi DB diinjeksi lengkap ke environment proses. Helper dites dengan nilai dummy dan file `.ini` sementara di scratch; `.env` aktual tidak dibuka. Environment parsial tetap memakai loader `.env` lama sehingga tidak aman untuk dites tanpa konfigurasi eksplisit.
- Ditemukan mismatch tipe dokumen opsional: migration 017 final dan PRD menetapkan `bpjs_asuransi`/`proposal_magang`, tetapi snapshot schema dan `LamarController` sebelumnya menggunakan alias `bpjs`/`proposal`. Snapshot diperbaiki ke nilai final; nama input form tetap dipertahankan dan controller sekarang memetakan input ke jenis database, sementara halaman detail menamai kind final. Regression source check membandingkan schema, migration 017, dan mapping. Karena data target tidak dibaca, cek terlebih dahulu secara read-only apakah nilai lama sudah tersimpan sebelum menerapkan constraint 017.
- Matriks audit SQL untuk seluruh migration bernomor dan dua CTE rekap lokal tersedia pada bagian “Audit file migrasi satu per satu” di `Dokumentasi/E2E-Test-Plan.md`. Pemeriksaan otomatis source-level kini mengunci duplikasi table-create `mitra`/`formasi_magang`, status file 025 read-only, riwayat 016→017, dan penyelarasan kind dokumen; ini tidak menguji DDL PostgreSQL.
- `php tests/workflow_static_checks.php` menggabungkan uji perilaku helper normalisasi path di memori dengan source assertions atas query status/ownership, conditional update, dan root allowlist download. Bagian SQL/source assertion tetap bukan runtime PDO atau integration test.
- Verifikasi sesi lanjutan: `php tests/smoke_views.php` lulus (7 komponen), `php tests/workflow_static_checks.php` lulus, seluruh lint PHP di luar `vendor/`/`node_modules/` lulus, `git diff --check` lulus, route `/dashboard/mitra/penilaian` tetap terdaftar sekali, dan path lokal yang diwajibkan tetap tersedia.
- Detail migrasi dan struktur terkait diperiksa hanya terhadap snapshot repository. Driver PDO yang tersedia pada CLI adalah MySQL dan PostgreSQL; tidak ada database dipilih atau koneksi dilakukan. Skema aktual, transaksi PostgreSQL, rowCount, constraint/trigger, dan perilaku concurrent update belum teruji.

## Final integration readiness checkpoint

Matriks per-role, kondisi kesiapan database/server, urutan migrasi repository, dan langkah E2E tersimpan di [`Dokumentasi/E2E-Test-Plan.md`](E2E-Test-Plan.md). Status siap integrasi saat ini: **belum siap untuk E2E** sampai database lokal disposable dan web-server target dibuktikan.

Proposal struktur source-driven per tabel, tingkat kepastian, perbandingan baseline fresh vs replay historical vs snapshot, serta dependency kolom source ada di [`Dokumentasi/Canonical-DB-Baseline-Proposal.md`](Canonical-DB-Baseline-Proposal.md). Rekomendasi sementara adalah baseline fresh terpisah hasil rekonsiliasi yang disetujui, menggunakan `schema.sql` sebagai input referensi (bukan menjalankannya langsung); keputusan baseline historical tetap belum dapat dibuktikan.

- PDO PostgreSQL driver terpasang, tetapi tidak ada listener PostgreSQL lokal yang teramati. `config/env.php` kini melewati pembacaan `.env` jika kelima variabel DB diinjeksi non-empty; tes helper memastikan perilaku skip/merge pada fixture dummy, bukan dengan membuka `.env` aktual. Jangan jalankan aplikasi sampai target diinjeksi lewat mekanisme aman dan terbukti disposable/loopback.
- Docker daemon lokal merespons (versi server 29.1.3), tetapi tidak ada container dibuat. Runbook container disposable yang loopback-only dan tanpa volume ada di `Dokumentasi/E2E-Test-Plan.md`; hanya operator yang melanjutkan setelah baseline/target disetujui.
- `database/migrations/012_create_mitra.sql` dan `013_create_formasi_magang.sql` membuat ulang tabel yang telah dibuat `002_create_mitra_and_formasi.sql`; migration tidak aman dijalankan berurutan tanpa baseline/ledger reconciliation. File `025_verify_laporan_workflow.sql` adalah SELECT verifikasi, bukan perubahan DDL. Migration 026 menambah template dan tidak aman untuk diulang tanpa memeriksa data/ledger.
- Server lokal yang terdeteksi bukan bukti deployment aplikasi: Apache default DocumentRoot `/var/www/html`, `mod_rewrite` tidak aktif, dan `AllowOverride` untuk `/var/www` tidak membolehkan aturan `.htaccess`; tidak ada vhost repository yang terbukti. `nginx -t` melaporkan syntax config valid tetapi berhenti pada akses PID; dump vhost tidak berhasil diverifikasi. Tidak ada browser automation tool maupun browser binary.
- Perintah yang aman dan sudah dijalankan terbatas ke `php -l` seluruh PHP, `php tests/smoke_views.php`, `php tests/workflow_static_checks.php`, pemeriksaan route/file dan `git diff --check`. Tidak ada server aplikasi, migration, seeder, query, koneksi database, login, upload/download HTTP, atau browser E2E dijalankan.
- Sebelum E2E: provision PostgreSQL disposable; injeksikan environment connection eksplisit secara aman; selaraskan migration baseline conflict; terapkan migration dan fixture secara manual hanya setelah otorisasi lokal; siapkan vhost/server yang memetakan repository dan menolak static private paths; baru jalankan checklist role pada matrix.
- Keputusan bisnis tetap dibutuhkan untuk urutan reviewer laporan, dampak revisi/penolakan, versioning dokumen pengajuan, verifier/prasyarat penyelesaian, penugasan reviewer Dosen, dan batas kewenangan validator Tendik/Koordinator. Tidak ada formula kelulusan otomatis.

### Batch UI/UX lintas role: inbox notifikasi

- Tombol bell di header Mahasiswa, Dosen, Mitra, Koordinator, dan Tendik kini membuka `/dashboard/notifikasi`; badge count menggunakan angka dari dashboard bila tersedia atau mengambil count unread dari model.
- Route inbox memvalidasi session/role untuk lima role yang terdaftar, menampilkan notifikasi milik user saja, membatasi hasil terbaru, mendukung tandai satu/semua dibaca melalui POST+CSRF dan owner-scoped UPDATE. Link terkait hanya dirender sebagai URL internal setelah validasi scheme/host. Empty/error state tersedia.
- Pengujian render fixture ditambahkan ke `tests/smoke_views.php`; source regression checks memeriksa route, middleware, CSRF, user scoping, dan limit. Query database serta mutasi mark-read belum diuji karena target DB disposable belum disiapkan.
- Audit nav juga menemukan dropdown Pengaturan di empat header non-mahasiswa menaut ke route mahasiswa yang tidak berwenang mereka akses. Link tersebut kini hanya ditampilkan untuk role mahasiswa, satu-satunya role dengan route settings yang terdaftar. Halaman settings khusus Dosen/Mitra/Koordinator/Tendik belum dibuat karena requirement dan backend-nya belum ada.

### Batch Mahasiswa: unduh versi laporan

- `laporan/detail.php` sebelumnya menampilkan metadata riwayat versi tetapi tidak menyediakan tombol untuk mengunduh file versi lama; tautan download di ringkasan hanya mengarah ke file terkini.
- Riwayat versi kini memakai `RevisionHistory.php` dengan tautan per revisi. Route `/dashboard/mahasiswa/laporan/revisi/{id}/download` menggunakan session mahasiswa dan lookup yang mengikat revisi → laporan → placement → pendaftaran → profil mahasiswa. File lalu melewati canonical path allowlist dan MIME/PDF checks yang sudah ada.
- Smoke view fixture memverifikasi link per version, nomor versi dan escaping. Source check memverifikasi owner predicate dan route mapping. Kepemilikan SQL dan download HTTP belum diuji pada database/browser.

### Batch Pengajuan: respons Mitra dan keputusan Dosen

- Form respons Mitra kini mempertahankan catatan pada validasi/kegagalan, menolak catatan >5.000 karakter sebelum submit, memberi label aksi yang menyebut pelamar, dan mencegah pengiriman UI berulang dengan menyimpan aksi yang dipilih sebelum men-disable tombol.
- Update respons Mitra kini juga memiliki state predicate `status_persetujuan_dosen='disetujui'` dan source states `menunggu_respons_mitra`/`seleksi`, serta memeriksa row count sebelum history/notifikasi ditulis. Kunci row/kuota dan transisi diterima/ditolak/seleksi yang sudah ada dipertahankan.
- Form keputusan Dosen mempertahankan catatan ketika validasi atau proses gagal. Kedua controller memeriksa CSRF; model Dosen yang ada mengunci pendaftaran, mengecek status pending, dokumen wajib saat approval dan conditional update sebelum history/notifikasi.
- Error muat awal/refresh antrean Dosen/Mitra kini ditampilkan sebagai pesan umum dan detail driver hanya ditulis ke server log. Empty state yang ada tetap dipakai.
- Tes render `tests/smoke_views.php` kini merender queue Mitra dengan fixture dan memeriksa catatan dipulihkan, CSRF, destination dokumen, decision field, double-submit state, escaping serta empty state yang sudah ada. Belum menguji POST/model/DB.

1. Siapkan database lokal/testing terisolasi dan pastikan `DB_HOST`, `DB_NAME`, serta pengguna DB mengarah ke database tersebut; jangan gunakan kredensial/database produksi.
2. Terapkan migration secara manual sesuai instruksi project setelah audit riwayat migration dan schema aktual. Migration tidak diterapkan oleh pekerjaan ini.
3. Jalankan seeder akun dan data dalam urutan yang tercantum pada README masing-masing. Seeder dapat menambah data; evaluasi terlebih dahulu akun/record yang sudah ada.
4. Jalankan aplikasi sesuai `Readme.md`, lalu uji login/logout CSRF untuk semua role dan skenario logbook di `Dokumentasi/Logbook-Testing.md`.
5. Verifikasi semua perubahan status, relasi, antrean, dan akses file pada database testing; uji IDOR dan request tanpa token CSRF.
6. Ulangi seeder untuk memastikan idempotensi setiap script sebelum mengandalkannya. Beberapa seeder lain belum diaudit untuk sifat idempotent/non-mutating.

### Audit lintas-role dan monitoring Dosen

- Monitoring Dosen sebelumnya dispatch ke `PenilaianController`, menampilkan form penilaian alih-alih monitoring; daftar monitoring juga memuat sampel hardcoded, tombol detail tidak memiliki handler, dan tautan penilaian menunjuk path yang tidak dirutekan. Kini monitoring memakai data bimbingan owner-scoped, menampilkan statistik/empty/error state dari data tersebut, memiliki detail penempatan owner-scoped, serta tautan penilaian Dosen diarahkan ke route eksplisit `/dashboard/dosen/penilaian`. Redirect setelah menyimpan penilaian juga kembali ke route tersebut.
- Audit menemukan controller Portofolio, Sertifikat, dan Pengaturan Mahasiswa sebelumnya hanya menguji sesi/ID tanpa memeriksa role. Semuanya kini memanggil middleware role `mahasiswa`. Validasi upload profil turut memeriksa provenance file; unggahan gambar portofolio harus dapat didekode sebagai gambar.
- Pemeriksaan source menunjukkan workflow mahasiswa profil/portofolio/sertifikat memiliki form, route, kontrol kepemilikan, dan jalur file; detail database/upload runtime belum diuji. Mitra memiliki dashboard, formasi, pengajuan, penilaian dan logbook pada source; belum ditemukan endpoint dashboard untuk mengubah profil perusahaan yang dijabarkan dokumen Mitra. Dosen memiliki pengajuan, bimbingan, monitoring, logbook, permintaan pembimbing, dan penilaian. Tendik memiliki dashboard/antrean validasi logbook; Koordinator memiliki monitoring, penempatan dan antrean validasi.
- Belum ada uji runtime yang membuktikan penolakan role/IDOR/CSRF/upload/unduh. Static checks adalah source assertions, bukan pengganti runtime. Pengujian runtime perlu target PostgreSQL disposable dan server lokal yang pemetaannya telah dikonfirmasi; database testing saat ini belum dikonfirmasi aman.
- Pemeriksaan laporan lintas reviewer, verifikasi penyelesaian, administrasi global, serta profil perusahaan Mitra tetap memerlukan keputusan kebutuhan/kewenangan sebelum endpoint baru dibuat.

### Persiapan test tanpa runtime

- `Dokumentasi/E2E-Test-Plan.md` kini memiliki register test ber-ID dengan prasyarat fixture synthetic, pemilik data dan role penguji, aksi/request, expected result, bukti pencatatan, serta lapisan (DB/server/config). Seluruh skenario autentikasi, IDOR, CSRF, upload/download, monitoring/penilaian Dosen dan alur lima role berstatus PLANNED / NOT RUN.
- Pengujian tanpa DB/server yang tersedia saat ini terbatas pada render fixture (`tests/smoke_views.php`), pure helper path/allowlist/symlink, pemetaan jenis dokumen, dan environment fixture di `tests/workflow_static_checks.php`; source assertions tetap bukan tes runtime.
- `Dokumentasi/Canonical-DB-Baseline-Proposal.md` memiliki decision register BASE-01–BASE-08 untuk struktur Mitra/Formasi, marker ledger, seed template, migration 029, alias dokumen, versi PostgreSQL, target disposable, dan cleanup. Semua masih OPEN; proposal bukan persetujuan atau otorisasi penerapan.
- Belum dibuat harness role/CSRF/upload/download berbasis server karena bootstrap controller memuat koneksi PDO dan database testing belum dibuktikan aman. Test semacam itu memerlukan persetujuan target dan refactor/injection seam; jangan menggantinya dengan source assertion yang diklaim sebagai runtime.

### Backlog workflow lintas-role berbasis bukti

| ID | Role/fitur | Kondisi dan risiko berbasis source | Tindakan/file | Prioritas/status | Cara uji dan dependency |
|---|---|---|---|---|---|
| WA-01 | Mahasiswa — ganti foto/CV | Sebelum perbaikan, penghapusan file lama mengandalkan prefix `uploads/profil/` atau `uploads/cv/`, lalu menggabungkan path langsung. Stored path dengan traversal dapat menunjuk ke file lain di repository. | Diselesaikan: gunakan `resolvePrivateStorageFile()` dengan allowlist terpisah untuk masing-masing direktori pada `controllers/dashboard/mahasiswa/ProfilController.php`; tambah tes helper path lintas direktori di `tests/workflow_static_checks.php`. | P1 — file integrity — FIXED SOURCE-LEVEL | Tes fixture/symlink helper tanpa DB; penggantian file lewat endpoint masih memerlukan server/DB disposable. |
| WA-02 | Dosen — monitoring/detail/penilaian | Route monitoring dahulu menuju controller penilaian, daftar memakai fixture hardcoded, dan detail/penilaian tidak tersambung pada dispatch. | Sudah diperbaiki pada batch sebelumnya: `DosenMonitoringController`, `DashboardDosen`, monitoring components, detail view dan route assessment/detail. | P1 workflow — SOURCE FIXED | Smoke/source checks; query ownership dan redirect HTTP masih perlu DB/server. |
| WA-03 | Mahasiswa — Portofolio/Sertifikat/Pengaturan | Controller sebelumnya hanya memastikan session ID tanpa cek role. | Sudah diperbaiki pada batch sebelumnya dengan `AuthMiddleware::handle('mahasiswa')` dan regression source checks. | P1 authorization — SOURCE FIXED | Static check tersedia; penolakan HTTP setiap role belum runtime-tested. |
| WA-04 | Semua role — auth, IDOR, CSRF, upload/download, alur workflow | Belum ada bukti runtime lintas-role. Smoke/source checks tidak mengeksekusi controller terhadap DB atau server. | Jalankan register ID `AUTH-*`, `OWN-*`, `CSRF-*`, `UP-*`, `FILE-*`, `FLOW-*` pada `Dokumentasi/E2E-Test-Plan.md` setelah target disetujui. | P1 verification — BLOCKED BY ENVIRONMENT | Synthetic PostgreSQL disposable + server/vhost terverifikasi; lihat checklist persiapan E2E. |
| WA-05 | Mitra — profil perusahaan | Dokumen role menyebut data profil perusahaan, tetapi source route yang ditemukan menyediakan profil publik baca-saja; belum ditemukan route/dashboard edit profil milik perusahaan. Cakupan perubahan/verifikasi status tidak dijelaskan. | Konfirmasi apakah self-service edit diperlukan dan apa dampak perubahan field terhadap verifikasi; jangan menambah endpoint sebelum aturan itu jelas. | P1 candidate — BUSINESS REQUIREMENT OPEN | Setelah disepakati: cek owner perusahaan, validasi, CSRF, dan review status pada DB/server test. |
| WA-06 | Dosen/Koordinator/Tendik — pemeriksaan laporan dan verifikasi selesai | Schema memuat pemeriksaan/verification, tetapi urutan reviewer, assignment, pemicu, status akhir, dan efek revisi/penolakan belum didefinisikan. | Menunggu keputusan bisnis; jangan menambahkan transisi baru. | P1 workflow — BUSINESS BLOCKED | Tes baru dirancang setelah aktor, transisi, dan terminal states disepakati. |
| WA-07 | Koordinator/Tendik — administrasi global | Use case/role docs menyebut administrasi/monitoring umum, tanpa daftar field/record atau batas kewenangan mutasi. | Minta keputusan tentang entitas, ownership, dan aksi yang boleh dilakukan sebelum membuat CRUD global. | P1 authority — BUSINESS BLOCKED | Setelah scope disepakati, tambah positive/denied ownership tests pada target disposable. |
| WA-08 | Mahasiswa — detail formasi dan lamaran | `FormasiMagang::getBySlug()` sebelumnya mengabaikan status formasi; draft yang belum dipublikasikan dapat dibuka lewat ID/slug, dan `formatFormasi()` memberi label `tersedia` hanya dari kuota sehingga formasi ditutup tetapi masih punya slot dapat menampilkan CTA Lamar. | Detail mahasiswa kini menolak draft; status ketersediaan mensyaratkan formasi dibuka; detail memberi label sesuai status DB. Link apply stale merender error 409 dan submit nonaktif, dengan validasi server tetap aktif. File: `models/FormasiMagang.php`, `components/dashboard/mahasiswa/formasi-magang/detail/Header.php`, `controllers/dashboard/mahasiswa/LamarController.php`. | P1 data exposure / P2 status UX — FIXED SOURCE-LEVEL | Static check guard draft; smoke fixture status open/closed; query/GET/POST sebenarnya memerlukan DB/server. |
| WA-09 | Mitra — keputusan pengajuan | Model masih menerima `seleksi` saat status sudah `seleksi`; update timestamp menulis ulang status yang sama tetapi menambah history/notifikasi sehingga klik ulang menghasilkan event duplikat. | Model kini menolak transition no-op `seleksi → seleksi`; antrean menampilkan badge status dan menyembunyikan tombol seleksi berulang. Terima/tolak tetap tersedia. File: `models/PengajuanMitra.php`, `components/dashboard/mitra/pengajuan/ApplicationQueue.php`. | P1 duplicate decision — FIXED SOURCE-LEVEL | Smoke fixture menguji badge/aksi tersedia; static check mengunci guard model. Validasi transaksi/rowCount/notifikasi masih perlu PostgreSQL disposable. |
| WA-10 | Mahasiswa — alur tahapan lamaran | Aside formulir lamaran menyebut Verifikasi Koordinator, KPS, Kajur, Wadir 1, Surat Pengantar dan LOA, padahal PRD/source menetapkan alur pengajuan → Dosen → Mitra → penempatan. | Timeline diganti dengan empat tahap sesuai PRD, termasuk status awal dan kondisi penempatan. File: `components/dashboard/mahasiswa/lamar/Alur.php`, `MainContent.php`. | P1 workflow clarity — FIXED SOURCE-LEVEL | Smoke render teks alur; belum divalidasi di browser. |
| WA-11 | Dosen — pemeriksaan dokumen | Antrean menampilkan tautan dokumen, tetapi tidak merangkum dokumen wajib yang belum ada; tombol Setujui tetap tampak aktif walau backend menolak jika enam dokumen wajib kurang. | UI kini menunjukkan hitungan 0–6 dan daftar yang belum ada; aksi persetujuan disabled jika kurang, dengan validasi server tetap berlaku; rejection tetap tersedia. File: `components/dashboard/dosen/pengajuanmagang/SubmissionList.php`. | P1 decision UX — FIXED SOURCE-LEVEL | Smoke fixture incomplete/complete dan source check menyamakan jenis dokumen UI/backend; DB/HTTP belum diuji. |
| WA-12 | Dosen/Mitra — antrean pengajuan | Saat query antrean gagal, view dapat menampilkan pesan error sekaligus empty-state “tidak ada pengajuan”, seolah antrean memang kosong. | Empty-state kini hanya tampil setelah load berhasil dan hasilnya memang kosong. Form keputusan Dosen dan aksi terima/tolak Mitra kini meminta konfirmasi, lalu disable tombol/menampilkan busy state pada submit. File: `SubmissionList.php`, `ApplicationQueue.php`. | P2 feedback/action clarity — FIXED SOURCE-LEVEL | Smoke render failure state dan assert confirm/busy markup; dialog/browser interaction belum dijalankan. |
| WA-13 | Mahasiswa — logbook/laporan | Catch-all di controller Logbook dan Laporan sebelumnya menampilkan `Throwable::getMessage()` ke UI sehingga error PDO/runtime internal dapat bocor; upload laporan juga mengosongkan ringkasan/catatan saat gagal. | Controller kini menampilkan pesan domain untuk exception validasi/runtime yang dikenali, mengirim detail PDO/exception internal ke server log dan pesan umum untuk lainnya; adapter Logbook mengecek `PDOException` sebelum `RuntimeException`; teks laporan dipertahankan saat gagal. | P1 error disclosure / P2 input recovery — FIXED SOURCE-LEVEL | Static assertion dan render komponen; tidak ada pemaksaan exception PDO/HTTP runtime dilakukan. |
| WA-14 | Mahasiswa — detail Logbook dan preview laporan mingguan | Detail minggu menampilkan hitungan validasi menunggu namun tidak hitungan yang ditolak atau instruksi berikutnya; preview laporan mingguan hanya menampilkan tanggal/kegiatan, bukan status kehadiran/validasi. | Tambah count/alert validasi ditolak, status per record, empty/missing-week states, serta status kehadiran/validasi pada preview mingguan. | P2 status clarity — FIXED SOURCE-LEVEL | Smoke fixture render mencakup hadir, tidak hadir, validasi ditolak/menunggu, bukti ada/tidak, dan minggu kosong/missing; backend/database belum diuji. |

Temuan P1 pada perlindungan integritas file yang ditemukan pada sesi audit ini telah ditangani pada source dan diuji helper-nya. Item bertanda SOURCE FIXED bukan berarti perilaku runtime sudah lulus. Tidak ada E2E yang dinyatakan selesai.

### Logbook harian dan laporan mingguan/akhir — penguatan UI dan input

- Detail logbook kini membedakan hadir/tidak hadir dari status validasi; catatan validator dan tautan bukti tetap berasal dari baris milik minggu mahasiswa dan diarahkan ke endpoint bukti yang sudah ada. Ringkasan mingguan dan detail kini juga memperlihatkan jumlah validasi ditolak; halaman detail menjelaskan bahwa status ini menghalangi signature, serta mengarahkan mahasiswa memeriksa catatan dan mengedit sebelum Mitra sign. Tidak diubah aturan lock/validasi model.
- Preview laporan mingguan diekstrak ke `components/dashboard/mahasiswa/laporan/WeeklyActivities.php`, yang hanya menerima kegiatan controller untuk minggu terpilih; ia kini memperlihatkan status kehadiran/validasi, escape kegiatan, serta membedakan logbook yang belum ada dari yang kosong. Kegiatan tetap tidak difilter berdasarkan hasil validasi karena belum ada aturan yang menyatakan baris ditolak/menunggu harus dikeluarkan dari laporan.
- Upload laporan kini memakai komponen `UploadPanel.php` dengan status tindakan, format/ukuran, CSRF, label file, submit busy state, dan pengisian ulang ringkasan/catatan setelah kegagalan. Controller mempertahankan kedua teks saat gagal dan membatasi masing-masing hingga 5.000 karakter sebelum penyimpanan. File input sengaja tidak dipertahankan oleh browser saat render ulang karena keamanan; pengguna perlu memilih ulang PDF.
- Catatan pemeriksaan laporan sekarang menggunakan label lokal untuk stage/status yang ada dan menampilkan nama pemeriksa/catatan bila tersedia. UI secara eksplisit menyebut bahwa daftar adalah catatan tersimpan, bukan instruksi urutan tahap; tidak ditambahkan endpoint/transition pemeriksa.
- Smoke fixture memeriksa status validasi (termasuk ditolak, hadir, bukti tidak tersedia), state sumber kegiatan laporan, escape konten, dan pemulihan ringkasan upload. Ini render test; tidak menjalankan query, transaksi, upload/download HTTP, atau browser.
- Follow-up audit menemukan status kehadiran yang tidak dikenal pada tampilan logbook dan preview mingguan sebelumnya jatuh ke cabang `else` yang tampak seperti tidak hadir; kini status unknown ditampilkan netral, bukan sebagai sukses/ketidakhadiran yang diasumsikan.
- Exception PDO pada adapter Logbook kini ditangani sebelum RuntimeException (PDOException mewarisi RuntimeException), detail dicatat ke server log dan pesan umum dikirim ke pengguna. Controller laporan juga memisahkan PDOException/exception internal dari pesan validasi yang aman.
- `Logbook::getReviewQueue()` sebelumnya selalu mengirim binding `user_id`, padahal kondisi antrean Koordinator/Tendik hanya memfilter `status_validasi='menunggu'` dan tidak memiliki placeholder `:user_id`; ini cocok dengan `SQLSTATE[HY093]`. Binding kini dibuat per cabang: Mitra/Dosen menerima user_id untuk owner predicate, sedangkan validator menjalankan query role-wide dengan array parameter kosong. Role gate ada pada `AuthMiddleware` controller dan method menolak role di luar daftar yang didukung. Regression source check menguji cabang/execute parameter; query PostgreSQL dan coverage antrean Koordinator belum diuji runtime.

### Autentikasi Mitra — koreksi source dan batas provisioning

- `routes/web.php` menyediakan GET/POST `/login` melalui `LoginController` serta POST `/logout` melalui `LogoutController`; dashboard Mitra memakai `MitraDashboardController`. Mitra dicari dengan `User::findActiveMitraByCode()` memakai `mitra.kode_perusahaan`, relasi `mitra.user_id`, role Mitra, dan status aktif pada kedua record. Role lain tetap memakai `findByLoginId()` dan verifikasi password hash. Session diregenerasi; middleware tetap memeriksa user, status aktif, dan role.
- Kode resmi bukan `users.login_id`: migration 002 dan schema snapshot mendefinisikan `mitra.kode_perusahaan` unik dan `mitra.user_id`; aplikasi lain juga memakai kolom itu. Migration 012 memiliki definisi tabel Mitra yang bertentangan dan perlu rekonsiliasi schema terpisah sebelum migration dijalankan.
- Tidak ditemukan registrasi publik atau UI provisioning operasional Mitra. Seed test menghubungkan `users` dan `mitra` serta menyediakan kode perusahaan; tidak ada seeder yang dijalankan. Seeder yang melewati akun yang sudah ada tidak memperbaiki relasi yang hilang. Provisioning operasional memerlukan proses admin/Koordinator yang disetujui.
- Login tanpa password memakai sesuatu yang berfungsi sebagai identifier sekaligus rahasia. Kode yang diketahui/ditebak dapat memungkinkan pengambilalihan akun; ini bukan autentikasi kuat. Belum ditemukan pembatasan percobaan login. Rate limiting/faktor autentikasi tambahan memerlukan keputusan implementasi keamanan tersendiri.
- Tes perilaku JavaScript dan policy PHP menguji pemilihan role serta cabang autentikasi secara terisolasi; static checks memeriksa SQL role/relasi, CSRF, session, redirect, middleware dan logout. Tidak ada klaim autentikasi DB/HTTP/browser runtime.

### Keputusan persetujuan pengajuan Dosen

- Parameter kelima pada urutan placeholder `UPDATE pendaftaran_magang` adalah `:terminal`, hasil ekspresi boolean `$decision === 'ditolak'`. `PDOStatement::execute([...])` mengirim boolean false untuk persetujuan melalui konversi string kosong, yang ditolak PostgreSQL untuk kolom/tindakan boolean. Kini parameter diikat secara eksplisit memakai `PDO::PARAM_BOOL`; parameter lain juga diikat per-placeholder dengan tipe yang sesuai.
- Catatan persetujuan opsional dinormalisasi menjadi SQL `NULL` bila kosong. Penolakan memerlukan alasan setelah trim, divalidasi pada UI dan controller serta ditegakkan ulang oleh helper/model.
- Lock/status pending, validasi enam dokumen wajib sebelum setuju, update kondisional, pemeriksaan satu baris terdampak, riwayat, notifikasi, transaksi dan CSRF tetap dipertahankan. Tes unit menguji validasi dan tipe binding lewat PDO double; tidak mengeksekusi SQL di PostgreSQL.
