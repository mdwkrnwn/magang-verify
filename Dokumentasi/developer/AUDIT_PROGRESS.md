# Progres Audit Total MagangVerify

Status: audit masih berlangsung; ini checkpoint untuk melanjutkan di sesi berikutnya, bukan pernyataan bahwa seluruh proyek sudah diaudit atau aman.

Repository: `/home/valencza/projects/magang-verify`  
Branch saat pemeriksaan: `main`  
Dokumen audit pendamping: `Dokumentasi/Repository-Audit-Matrix.md` dan `Dokumentasi/E2E-Test-Plan.md`.

## Status Git dan perlindungan perubahan lokal

- Saat audit dilanjutkan, `git status --short --branch` menunjukkan `## main...origin/main` dengan empat file modified dari checkpoint sebelumnya: dokumen progres, model profil mahasiswa, dan dua file tes.
- Setelah batch audit ini, status akhir menunjukkan sembilan file modified: checkpoint; controller/profile helper/model/routing; private path helper; dan tiga file tes. Tidak ada file lokal lain yang dihapus atau ditimpa.
- `git diff --check` lulus pada pemeriksaan awal dan akhir sesi. Tidak ada commit, push, migrasi, seeder, server, atau perubahan database.
- Perubahan batch saat ini mencakup delapan file implementasi/tes ditambah checkpoint; perubahan historis yang tidak ada di diff batch tidak diubah.
- File ini dibuat di `Dokumentasi/developer/` tanpa menimpa dokumentasi lain.
- Progres ini tidak boleh dianggap menutup audit total. `router.php` kini mengandalkan `function/PrivateStorage.php` ditambah `function/PublicAsset.php`; verifikasi HTTP belum dijalankan. Perubahan lanjutan di bawah menambah endpoint foto profil publik dan memperlebar pemblokiran statis; periksa ulang regresi sebelum merge/deploy.

## Inventaris role, fitur, dan cakupan audit

Pada inventaris source sebelumnya tercatat 5 role aplikasi dan 57 file halaman PHP. Route statis dan dynamic dispatch ditemukan; audit individual seluruh endpoint belum selesai.

| Role | Fitur dan workflow yang ditemukan | Cakupan saat ini |
|---|---|---|
| Mahasiswa | Dashboard, profil/pengaturan, portofolio, sertifikat, pengalaman, katalog/detail formasi, pengajuan dan dokumen, permintaan pembimbing, logbook harian/mingguan dan tanda tangan, laporan mingguan/akhir/revisi/unduh, notifikasi | Inventaris ada. Source ditinjau sebagian untuk login, pengajuan, logbook/laporan, profil/dokumen, permintaan pembimbing dan notifikasi. Seluruh CRUD, ownership, filter, dan endpoint belum ditelusuri. |
| Dosen | Dashboard, bimbingan, review pengajuan/dokumen, logbook/signature/validasi, monitoring/detail, permintaan pembimbing, penilaian, notifikasi | Inventaris ada. Alur keputusan pengajuan dan beberapa alur monitoring/logbook/penilaian ditinjau; review seluruh route/form/query belum selesai. |
| Koordinator Magang (`koordinator_magang`) | Dashboard, penempatan, validasi logbook/detail, notifikasi | Role dibedakan dari Tendik pada source. Penempatan/logbook ditinjau sebagian; hak akses dan seluruh query belum selesai diperiksa. |
| Tendik (`tendik`) | Dashboard, validasi logbook/detail, notifikasi | Inventaris ada; scope kewenangan dan seluruh endpoint belum selesai diperiksa. |
| Mitra (`mitra`) | Dashboard, formasi, respons pengajuan/dokumen, logbook/tanda tangan, penilaian, notifikasi | Inventaris ada. Beberapa ownership/status response, formasi, penilaian, dan notifikasi ditinjau; isolasi seluruh data antarperusahaan belum selesai. |
| Developer/admin | Tidak ditemukan pada allowlist autentikasi atau constraint role yang diperiksa. | Tidak dianggap sinonim Tendik/Koordinator. Pencarian seluruh alias role masih perlu dituntaskan. |

Workflow lintas-role yang dipetakan:

1. Login/logout → autentikasi dan akun/profil → session → middleware → dashboard role.
2. Pengajuan Mahasiswa/dokumen → keputusan Dosen → respons Mitra → penempatan Koordinator → logbook/laporan/penilaian serta history/notifikasi/dashboard.
3. Logbook Mahasiswa → tanda tangan Mitra/Dosen dan validasi Dosen/Tendik/Koordinator → history/revisi → laporan/unduhan.
4. Laporan mingguan/akhir → pengajuan/revisi → peninjauan dan unduhan. Urutan reviewer dan persetujuan terminal masih memerlukan aturan produk yang jelas.
5. Notifikasi → query berdasarkan pemilik → tandai dibaca → tujuan internal. Daftar tujuan belum seluruhnya diverifikasi dapat dicapai oleh role penerima.

Peta ini menunjukkan dependensi, bukan bukti bahwa setiap transisi telah diuji end-to-end.

## Temuan bug dan status perbaikan

| Prioritas | Temuan | Perbaikan/status |
|---|---|---|
| Tinggi | Halaman notifikasi menghitung root include ke luar repository sehingga komponen role/header/sidebar bersama tidak ditemukan. | Diperbaiki pada `pages/dashboard/shared/notifikasi.php`; pemilihan layout dibatasi pada role yang tersedia dan file include diperiksa. Smoke test memeriksa kelima layout. Database/browser render belum dijalankan. |
| Sedang | `components/dashboard/mahasiswa/portofolio/MainContent.php` mengarah ke path footer yang tidak ada. | Diperbaiki ke footer sibling. Smoke test merender empty state dan footer aktual. |
| Sedang | `pages/dashboard/index.php` adalah halaman legacy tanpa route/caller, tetapi meng-include komponen dashboard generik yang tidak tersedia. | Dijadikan fail-safe 404 menggunakan halaman error yang ada. |
| Tinggi | `LogbookFileController` dipanggil lewat dynamic route langsung, sebelum middleware prefix dashboard; guard sebelumnya tidak memuat ulang status akun/role. | `AuthMiddleware::handle($role)` ditambahkan sebelum metadata/file privat diambil. Runtime authorization belum diuji. |
| Tinggi | Nilai boolean PHP yang dikirim lewat `execute([...])` dapat tiba di PostgreSQL sebagai string kosong. Posisi `$4` pada UPDATE respons Mitra adalah `:terminal` (false untuk keputusan seleksi); assessment juga memiliki `CASE WHEN :is_submitted` pada INSERT dan UPDATE. | Binding typed melalui `models/PdoParameterBinder.php` diterapkan pada `PengajuanMitra` dan `PenilaianMagang`; Dosen memakai helper binding eksplisit yang sudah ada. Seeder Dimas memakai binder pada update boolean. Tidak ada query/migrasi/seeder dijalankan. PostgreSQL belum diuji. |
| Tinggi | Media upload profil dan sertifikat tidak memiliki jalur serving publik yang aman. Sebelum perbaikan, foto profil diakses langsung dari `/uploads/profil/...` dan router tidak secara eksplisit memblokir `/uploads/profil/`, `/uploads/sertifikat/`, atau `/storage/.../sertifikat`; sementara `ViewHelpers::foto()` bisa mengembalikan URL atau string remote yang tidak divalidasi. | Foto profil kini memakai endpoint `GET /mahasiswa/profil/foto/{filename}` dengan basename allowlist (`[a-f0-9]{32}.jpg/png/webp`), lookup profil publik ke `foto_path` yang tersimpan persis, resolver privat canonical, pengecekan MIME JPEG/PNG/WEBP plus decode gambar, dan tidak lagi mengembalikan URL mentah. Helper hanya mengembalikan route tersebut bila path diawali `uploads/profil/` dan bentuknya valid. `/uploads/profil/`, `/uploads/sertifikat/`, dan `/storage/*sertifikat` ditolak router; CV tetap dikirim lewat endpoint CV yang memeriksa kepemilikan path dan `%PDF-`. Perubahan foto tergantung logika get-by-user yang sudah ada. Sertifikat portofolio publik masih perlu rekonsiliasi jalur serving; jangan menganggap media tersebut sudah aman hanya dari deny rule. |
| Tinggi | Preview sertifikat pada dashboard mengeluarkan URL langsung `/uploads/sertifikat/...`, sehingga file tersaji tanpa endpoint ownership walaupun download memakai controller mahasiswa. Form edit juga membentuk URL langsung. | Ditambahkan `SertifikatController::preview()` dan route `/dashboard/mahasiswa/sertifikat/pratinjau/{slug}`; endpoint memerlukan session mahasiswa, owner-scoped slug, generated basename, canonical containment, MIME gambar dan decode, serta `private, no-store`. Card/detail/form preview kini memakai route tersebut; download juga membatasi basename generated. Static router tetap memblokir uploads/sertifikat. Runtime HTTP belum diuji. |
| Tinggi | Gambar portofolio publik pada profil publik memakai URL langsung `uploads/portofolio/...` tanpa pengecekan publikasi. Sebelumnya helper foto profil juga bisa meneruskan URL mentah. | Ditambahkan endpoint publikasi-aware `GET /mahasiswa/profil/portofolio/{filename}`; endpoint memeriksa basename generated, lalu kecocokan `gambar_sampul` persis dengan `status_publikasi='publik'` dan role mahasiswa, kemudian canonical containment + MIME/decode gambar. Query profil publik hanya membangun route baru bila path cocok pola; selain itu dikosongkan. File yatim berupa record DB menunjuk gambar yang tidak ada di disk kini fail 404 lewat endpoint, belum ada pencacahan/orphan cleanup. Runtime HTTP belum diuji. |
| Sedang | Referensi asset hero Mahasiswa dan foto kampus menunjuk file yang tidak ada (`hero-mahasiswa.png`, `polinema.png`). | Diganti ke aset yang memang ditemukan (`hero.png`, `gedung-polinema.jpeg`) dan pemeriksaan source/file ditambahkan ke smoke test. |
| Tinggi, baru ditemukan | `router.php` sebelumnya menyajikan file repository arbitrer bila path adalah file, bukan hanya asset publik; ini berpotensi mengekspos source/config jika front controller menerima request tersebut. Tidak diperiksa apakah `.env` ada atau dibaca. | Baru dibatasi ke resolver asset allowlist `function/PublicAsset.php`, canonical path di bawah `assets/`, symlink keluar ditolak, dan ekstensi publik yang dikenal diberi MIME eksplisit. Regression test disposable dibuat tetapi **belum dijalankan setelah helper dibuat**. Perlu uji resolver dan review router sebelum menganggap perbaikan terverifikasi. |
| Belum diputuskan | Direktori `/mitra` publik menggunakan data dummy sesuai komentar source, sementara struktur migration/schema Mitra bertentangan pada beberapa definisi. | Tidak diubah: perlu keputusan publikasi data dan rekonsiliasi schema, serta tidak boleh menjalankan migration tanpa izin. |
| Belum tuntas | `controllers/dashboard/dosen/DosenBimbinganController.php` kosong dan tidak ditemukan route/caller pada pemetaan yang diperiksa; route aktif memakai controller bimbingan lain. | Dibiarkan; perlu penelusuran file legacy lebih luas sebelum penghapusan/perubahan. |

Catatan workflow Dosen: aturan catatan approve opsional dan reject wajib, validasi server, syarat enam dokumen, status pending, CSRF, otorisasi, history/notifikasi, dan binding boolean tercatat di tes/source sebelumnya. Tesnya terisolasi, bukan PostgreSQL/HTTP runtime.

## File yang diketahui terkait perubahan audit

Daftar ini adalah file yang tercatat disentuh untuk temuan/perbaikan audit; bukan daftar seluruh file modified/untracked pada working tree dan bukan klaim bahwa semua perubahan lokal berasal dari audit.

- `pages/dashboard/shared/notifikasi.php`
- `components/dashboard/mahasiswa/portofolio/MainContent.php`
- `pages/dashboard/index.php`
- `controllers/dashboard/LogbookFileController.php`
- `controllers/dashboard/mahasiswa/SertifikatController.php`
- `function/PublicAsset.php` (baru)
- `router.php`
- `models/PdoParameterBinder.php` (baru)
- `models/PengajuanMitra.php`
- `models/PenilaianMagang.php`
- `database/seeders/user/mahasiswa/SeedDimasLogbook.php` (tidak dijalankan)
- `components/landingPage/mahasiswa/Hero.php`
- `components/landingPage/mahasiswa/profil/Hero.php`
- `components/dashboard/mahasiswa/sertifikat/SertifikatCard.php`
- `components/dashboard/mahasiswa/sertifikat/detail/Preview.php`
- `components/dashboard/mahasiswa/sertifikat/form/SertifikatForm.php`
- `controllers/MahasiswaProfilController.php`
- `models/ProfilMahasiswa.php`
- `tests/smoke_views.php`
- `tests/workflow_static_checks.php`
- `tests/pdo_parameter_binder.php` (baru)
- `routes/web.php`
- `tests/public_asset_resolution.php` (baru; telah dijalankan ulang dan lulus setelah perbaikan MIME, lihat riwayat tes di bawah)
- `function/landingPage/mahasiswa/ViewHelpers.php`
- `function/PrivateStorage.php`
- `Dokumentasi/Repository-Audit-Matrix.md`
- `Dokumentasi/developer/AUDIT_PROGRESS.md` (checkpoint ini)

Implementasi autentikasi Mitra sebelumnya juga memiliki perubahan lokal pada `components/login/Form.php`, `components/login/Script.php`, `controllers/LoginController.php`, `models/User.php`, dan `models/LoginAuthenticator.php`. Source/test terisolasi pernah diperiksa, tetapi login dengan akun dan database nyata belum dibuktikan. Login hanya dengan Kode Mitra memiliki risiko pengambilalihan jika kode dapat diketahui/ditebak; risiko dicatat, bukan disamarkan sebagai autentikasi kuat. Provisioning akun Mitra sesuai schema dan kebijakan operasional masih perlu penelusuran/keputusan.

## Tes yang telah dijalankan

Hasil berikut berasal dari eksekusi terdahulu yang tercatat di matrix audit; bedakan tes terisolasi/statis dari runtime:

- `php tests/smoke_views.php` — lulus pada run terdahulu: `Repository view smoke checks passed (38 components).` Run tersebut mencakup render footer portfolio; **belum dijalankan ulang setelah assertion aset hero ditambahkan**.
- `php tests/workflow_static_checks.php` — lulus: `Workflow static security checks passed (conditional validation and private file access).`
- `php tests/pdo_parameter_binder.php` — lulus: `Typed PDO parameter binder checks passed.` Menggunakan PDO statement double; bukan koneksi PostgreSQL.
- `php tests/pengajuan_decision.php` — lulus: `Isolated Dosen decision validation and PDO binding tests passed.` Isolated, bukan HTTP/DB runtime.
- `php tests/login_authentication.php` — lulus: `Isolated login authentication policy checks passed.` PDO double, bukan bukti akun aktif/schema live.
- `node tests/login_role_behavior.js` — lulus: `Login role behavior checks passed (synthetic DOM; no server/database).`
- PHP lint repository terdahulu — 374 file, 0 kesalahan sintaks. Lint target pada sesi lanjutan untuk `function/PublicAsset.php`, `tests/public_asset_resolution.php`, `router.php`, kedua Hero, `tests/smoke_views.php`, `tests/workflow_static_checks.php`, `models/PdoParameterBinder.php`, serta `models/ProfilMahasiswa.php` — lulus.
- Pemetaan source route/controller — 83 pasangan method, 0 unresolved; tidak membuktikan HTTP dispatch/otorisasi.
- Scan include literal — 713 kandidat diperiksa; laporan tersisa diperiksa sebagai potongan string test atau include dinamis. Tidak mencakup seluruh ekspresi dinamis.
- `git diff --check` — lulus pada pemeriksaan Git terakhir.
- `php tests/public_asset_resolution.php` — lulus pada run ulang: `Public asset containment checks passed (scratch fixtures; no server/database).` Run awal yang tercatat di checkpoint lama gagal sebelum helper tersedia; pada sesi ini juga sempat gagal karena assertion MIME test tidak cocok dengan MIME yang benar-benar dikembalikan, lalu assertion diperbaiki dan tes lulus.
- `php -l models/ProfilMahasiswa.php`, `php -l tests/workflow_static_checks.php`, `php -l tests/public_asset_resolution.php` — lulus setelah perubahan sesi ini.
- Rerun regresi batch ini: `php tests/smoke_views.php`, `php tests/workflow_static_checks.php`, `node tests/login_role_behavior.js`, `php tests/public_asset_resolution.php`, `php tests/pdo_parameter_binder.php`, `php tests/pengajuan_decision.php`, `php tests/login_authentication.php` — semua lulus dengan output yang tercatat pada terminal sesi; hasil masing-masing ada di run terminal terakhir. `git diff --check` juga lulus.
- `php -l` dijalankan untuk `function/PrivateStorage.php`, `function/landingPage/mahasiswa/ViewHelpers.php`, `controllers/MahasiswaProfilController.php`, `models/ProfilMahasiswa.php`, `routes/web.php`, `tests/smoke_views.php`, dan `tests/workflow_static_checks.php` — semua lulus tanpa syntax error setelah perubahan batch ini.

Runtime PostgreSQL, browser, HTTP, migrasi, seeder, server, dan container tidak dijalankan. Tes statis/render/isolated tidak membuktikan database, session runtime, transaksi, keamanan HTTP, responsif, atau alur end-to-end.

## Area belum diperiksa atau belum diuji

- Semua route/endpoint satu per satu: metode HTTP, middleware, CSRF, role, ownership, form/link caller, dynamic dispatch, redirect, dan destination.
- Seluruh query DML/SELECT terhadap schema aktual: placeholder/tipenya, nullable, enum, tanggal, duplikasi, constraint, filter role, transaksi/rollback, concurrency, dan data setengah tersimpan.
- Seluruh alur CRUD Mahasiswa dan IDOR, termasuk profil/portofolio/sertifikat/pengalaman/pengajuan/logbook/laporan dan file.
- Isolasi Mitra antarperusahaan pada semua formasi, pengajuan, dokumen, tanda tangan, logbook dan penilaian.
- Seluruh otorisasi Dosen/Koordinator/Tendik dan kewenangan reviewer/admin yang tidak ditetapkan dokumen.
- Semua komponen per halaman/role, empty/error/success state, navigasi, form field ↔ backend, filter, pencarian, pagination, JavaScript/fetch dan endpoint aset.
- Seluruh urutan perubahan status, pengaruh ke dashboard/antrian role lain, history, notifikasi, tujuan tautan, dan duplikasi request.
- Upload/download: validasi signature/MIME/ukuran, containment, deletion, header, akses langsung storage, serta konfigurasi virtual host efektif.
- Seluruh dokumen bisnis dibandingkan source; direktori Mitra dummy dan kebijakan datanya.
- Uji HTTP/browser atas router, resolver asset, dan media upload; helper resolver sudah lolos test terisolasi, tetapi server tidak dijalankan.
- Tidak ada hasil runtime database/browser/HTTP. Tidak diketahui apakah schema live sesuai snapshot/migration.
- Jalur serving media: foto profil kini memiliki endpoint otorisasi; sertifikat, gambar portofolio publik, CV selain endpoint yang ada, dan generated laporan template masih perlu dipetakan route-by-route ke handler resmi versus deny rule agar tidak ada file privat yang tersaji langsung. Jangan menambahkan akses statis untuk uploads tanpa memeriksa aturan publikasi dan kepemilikan.
- Query `getPublicMahasiswa()` dan `getPublicMahasiswaBySlug()` menyertakan beberapa kolom kontak/identitas untuk semua profil yang dirender publik. PRD menghendaki direktori publik, tetapi tidak mendefinisikan pilihan/consent per field; tinjau kebijakan privasi sebelum mengubah field mana yang tampak.
- Workflow pengajuan ditelusuri tambahan pada source; penerima notifikasi untuk pengajuan baru belum jelas karena schema/source tidak menetapkan reviewer Dosen spesifik. Jangan menebak broadcast/assignment.

## Kendala dan langkah berikutnya (urut)

1. Audit rute serving untuk setiap aset media yang masih dirender publik: gambar portofolio publik, file sertifikat, avatar/dashboard, generated laporan, template, bukti/ttd logbook, dokumen pendaftaran, dan CV. Tandai handler resmi, aturan publikasi, dan kemungkinan bypass static/Apache/Nginx. Uji HTTP/browser hanya pada target aman yang disetujui; tanpa itu tetap NOT RUN.
2. Periksa/putuskan field profil mahasiswa yang boleh tampil publik; dokumentasikan keputusan sebelum mengubah SELECT atau UI.
3. Lanjutkan route audit per-role dari `routes/web.php`/dynamic dispatch; untuk setiap aksi catat caller, method, middleware, CSRF, controller, model/query, ownership, state/history/notification dan destination.
4. Lanjutkan tracing seluruh query/schema dan workflow lintas-role, terutama notifikasi pengajuan baru, revisi pengajuan, pemeriksaan laporan, serta verifikasi penyelesaian yang kebijakan bisnisnya belum ditetapkan.
5. Selesaikan audit upload/download dan aset, termasuk HTTP/server mapping; uji browser/HTTP hanya jika target yang aman disetujui.
6. Setelah code changes berikutnya, jalankan tes yang relevan, lint file berubah, semua tes proyek yang tersedia, `git diff --check`, dan cek ulang status; jangan membuang perubahan lokal.
7. Dapatkan target PostgreSQL/browser disposable serta persetujuan eksplisit sebelum tes runtime atau operasi data. Jangan menjalankan migration/seeder/query tulis.
8. Perbarui `Dokumentasi/Repository-Audit-Matrix.md` dan checkpoint ini berdasarkan bukti; audit total tetap terbuka sampai seluruh role/route/fitur/workflow diperiksa.

## Batch lanjutan sesi ini

- Memulihkan konteks dengan membaca checkpoint ini, `Dokumentasi/Repository-Audit-Matrix.md`, `Dokumentasi/E2E-Test-Plan.md`, PRD, daftar tes, `routes/web.php`, `index.php`, `router.php`, helper aset, serta workflow pengajuan/logbook terkait. Snapshot schema dan migration dibaca secara statis; konfigurasi credential, `.env`, database, server, dan browser tidak digunakan.
- Status awal sesi bersih (`main` mengikuti `origin/main`). Audit awal router mengonfirmasi helper allowlist hanya menghidangkan asset di `/assets/`; `Readme.md` menggunakan custom router PHP built-in. Referensi media upload pada source perlu jalur pemeriksaan publikasi sebelum dapat diperbaiki dengan aman.
- `php tests/public_asset_resolution.php` awalnya gagal karena test mengharapkan MIME `text/css`, sedangkan resolver dengan benar menghasilkan `text/css; charset=utf-8`. Assertion test diselaraskan dengan MIME eksplisit. Hasil run ulang lulus.
- Ditemukan akar masalah baru: `MahasiswaProfilController::index()` dan `cv()` memanggil `ProfilMahasiswa::getPublicMahasiswaBySlug()`, dan method model itu dahulu menjalankan `INSERT INTO pengalaman` pada request GET (termasuk sebelum mengirim CV publik). Insert tersebut bisa terjadi tanpa pencatatan aktivitas pada jalur ini. Pengalaman auto juga sudah dapat ditampilkan sebagai derived row oleh `Pengalaman::getCombinedQuery()` dan disinkronkan di alur model Pengalaman.
- Perbaikan source: `getPublicMahasiswaBySlug()` kini membaca pengalaman tersimpan dan pengalaman magang selesai yang eligible lewat `UNION ALL`, de-duplicate melalui `pendaftaran_id`, dan tidak memutasi tabel. Kriteria status `selesai` serta verifikasi `terverifikasi`/record verifikasi kosong dipertahankan dari implementasi sebelumnya; tidak ada aturan bisnis baru. File: `models/ProfilMahasiswa.php`.
- Regression test source-level ditambahkan pada `tests/workflow_static_checks.php`. Tes baru dijalankan merah dan gagal tepat karena `INSERT INTO pengalaman` ada dalam getter publik; setelah perbaikan tes lulus. Ini static assertion, bukan validasi SQL PostgreSQL.
- Pengajuan lintas role ditelusuri pada `LamarController`, `PendaftaranMagang`, `PersetujuanPengajuan`, `PengajuanMitra`, `PenempatanMagangCoordinator`, tiga controller role dan migration schema: file/documen upload dan insert/history transaction mahasiswa; keputusan Dosen pending + syarat enam dokumen + history/notifikasi transaction; respons Mitra ownership + status + kuota + binding bool + history/notifikasi; placement Koordinator untuk diterima/tanpa placement. Belum ditemukan tes HTTP/database atau alur revision upload. Potensi notifikasi pengajuan baru ke Dosen tidak diimplementasikan tanpa aturan penerima yang jelas.
- File yang berubah pada batch audit saat ini: `controllers/MahasiswaProfilController.php`, `function/PrivateStorage.php`, `function/landingPage/mahasiswa/ViewHelpers.php`, `models/ProfilMahasiswa.php`, `routes/web.php`, `tests/public_asset_resolution.php`, `tests/smoke_views.php`, `tests/workflow_static_checks.php`, `Dokumentasi/developer/AUDIT_PROGRESS.md`.

## Keputusan/konfirmasi bisnis yang belum boleh ditebak

- Apakah direktori Mitra publik harus tetap dummy, menampilkan data terverifikasi, dan bagaimana status publikasinya ditetapkan.
- Definisi schema Mitra kanonik serta strategi rekonsiliasi migration historis tanpa merusak database.
- Apakah Dosen yang boleh memutuskan harus terikat ke pembimbing/penugasan tertentu atau queue saat ini memang shared.
- Urutan reviewer dan status terminal yang berlaku untuk laporan mingguan/akhir.
- Batas kewenangan Koordinator Magang dan Tendik pada validasi/administrasi.
- Provisioning resmi akun Mitra, sumber kebenaran kode, status aktif, dan mitigasi login berbasis kode saja.
- Target PostgreSQL/browser disposable dan izin menjalankan tes integrasi; tanpa izin, runtime tetap tidak diuji.

## Batch terbaru: audit media sertifikat dan portofolio publik

### Source yang diperiksa

- `controllers/dashboard/mahasiswa/SertifikatController.php`
- `models/Sertifikat.php`
- `components/dashboard/mahasiswa/sertifikat/SertifikatCard.php`
- `components/dashboard/mahasiswa/sertifikat/detail/Preview.php`
- `components/dashboard/mahasiswa/sertifikat/form/SertifikatForm.php`
- `routes/web.php` pada dynamic route sertifikat
- `controllers/MahasiswaProfilController.php`
- `models/ProfilMahasiswa.php` pada query portofolio publik/foto profil
- `function/landingPage/mahasiswa/ViewHelpers.php`
- `function/PrivateStorage.php`, `router.php`, `storage/.htaccess`, `uploads/cv/.htaccess`
- `tests/workflow_static_checks.php` dan `tests/smoke_views.php`

### Temuan dan perubahan

- Preview gambar sertifikat memakai URL langsung ke `uploads/sertifikat`, sehingga ownership hanya dijaga pada page/download controller dan dapat dilewati melalui URL file. Ditambahkan `SertifikatController::preview()` dengan role mahasiswa, owner-scoped slug, pola basename generated, canonical containment, MIME/image validation, dan cache privat. Card, detail, dan form edit diarahkan ke route preview; PDF tetap memakai download handler.
- Gambar portofolio publik memakai URL langsung dari nilai database. Ditambahkan `MahasiswaProfilController::portofolio()` dan route `/mahasiswa/profil/portofolio/{filename}`. Model memerlukan exact stored path, `u.role='mahasiswa'`, dan `status_publikasi='publik'` sebelum file dikirim. Query profil publik mengubah path gambar yang cocok menjadi endpoint ini dan mengosongkan path yang tidak memenuhi pola generated.
- Foto profil dari batch sebelumnya tetap memakai handler publik profile-photo yang owner/path scoped. Private path helper tetap memblokir `uploads/cv`, `uploads/profil`, `uploads/sertifikat`, dan storage private yang terdaftar; static router tidak dibuka untuk seluruh uploads.
- Seed path yang ditemukan (`uploads/mahasiswa/...` pada `SeedProfilMahasiswa.php` dan `uploads/sertifikat/seed/...` pada `SeedSertifikatMahasiswa.php`) belum diubah karena seed tidak boleh dijalankan dan data fixture/operasional belum dikonfirmasi. Ini dicatat sebagai potensi mismatch/path yatim untuk batch seed/fixture terpisah.
- `storage/pendaftaran/README.txt` muncul sebagai modified pada status akhir, tetapi tidak disentuh oleh batch ini dan dipertahankan sebagai perubahan lokal pengguna.

### Verification batch

- `php tests/smoke_views.php` — lulus: `Repository view smoke checks passed (38 components).`
- `php tests/workflow_static_checks.php` — lulus: `Workflow static security checks passed (conditional validation and private file access).`
- `node tests/login_role_behavior.js` — lulus; synthetic DOM, tanpa server/database.
- `php tests/public_asset_resolution.php` — lulus; scratch fixtures, tanpa server/database.
- `php tests/pdo_parameter_binder.php` — lulus.
- `php tests/pengajuan_decision.php` — lulus; PDO statement double.
- `php tests/login_authentication.php` — lulus; isolated policy/PDO double.
- PHP lint untuk controller Sertifikat, komponen sertifikat, `MahasiswaProfilController`, `ProfilMahasiswa`, `routes/web.php`, dan `tests/workflow_static_checks.php` — lulus tanpa syntax error.
- `git diff --check` — lulus.
- Tidak ada HTTP/browser/PostgreSQL/runtime authorization, migration, seeder, atau query tulis yang dijalankan.

### Tugas tertunda berikutnya

1. Audit jalur file laporan: upload PDF, template DOCX, generated DOCX/PDF, revision download, direct static bypass, dan ownership/role.
2. Audit dokumen pengajuan dan logbook evidence/signature secara per endpoint, termasuk path yang tersimpan dan MIME/content checks.
3. Audit semua route/controller per role dengan matriks endpoint hidup; status saat ini masih partial, bukan selesai.
4. Rekonsiliasi seed paths dan file orphan secara read-only/source-only tanpa menjalankan seeder.
5. Lanjutkan audit query/schema lintas modul dan update matriks endpoint. Runtime tetap NOT RUN sampai target terisolasi disetujui.

## Batch berikutnya: keamanan laporan dan workflow file

### Source yang diperiksa

- `controllers/dashboard/mahasiswa/LaporanController.php`
- `models/LaporanMagang.php`, `models/LaporanRevisi.php`, `models/TemplateLaporan.php`, `models/PemeriksaanLaporan.php`
- `database/schema.sql` dan migration laporan `006_create_laporan_dan_penilaian.sql`, `024_create_laporan_workflow.sql`
- `function/PrivateStorage.php`, `router.php`, `storage/.htaccess`

### Temuan dan perubahan

- Upload laporan Mahasiswa divalidasi server-side: POST+CSRF, status hanya `draft`/`perlu_revisi`, max 10 MB, `is_uploaded_file`, MIME `application/pdf`, ekstensi `.pdf`, dan signature `%PDF-`. Penyimpanan memakai random 48-hex basename di `storage/laporan`; update laporan dan record revisi berada dalam transaksi; file baru dihapus jika transaksi gagal. Ownership update dan download ditelusuri melalui `prof.user_id`.
- Download laporan terbaru/revisi dan template melewati `resolvePrivateStorageFile()` dengan allowlist `storage/laporan`/`storage/templates/laporan`, MIME/extension checks, dan owner-scoped lookup untuk Mahasiswa. Direct static storage ditolak oleh router/`storage/.htaccess` pada source yang diperiksa.
- Ditemukan kelemahan pada generated weekly report: template path sebelumnya dibentuk dengan konkatenasi root + nilai `file_template` database. Walaupun download akhir memakai resolver, generator membaca file template sebelum itu. Ini dapat membuka path template arbitrer jika record database berisi path manipulatif. Diperbaiki memakai `resolvePrivateStorageFile(..., ['storage/templates/laporan'])` sebelum generator berjalan.
- `PemeriksaanLaporan` memiliki model CRUD/status, tetapi tidak ada route/controller yang menjalankan `decide()` pada source yang diperiksa; urutan reviewer/otorisasi Koordinator/Tendik belum menjadi workflow operasional. Tidak dibuat endpoint baru tanpa aturan bisnis.
- Path record/file seed dan orphan cleanup belum diubah. Tidak ada database dibaca atau file dihapus. Akses lintas role reviewer laporan belum dapat dibuktikan runtime dan perlu keputusan kewenangan.

### Verification batch

- `php tests/smoke_views.php` — lulus, 38 komponen.
- `php tests/workflow_static_checks.php` — lulus; termasuk regression bahwa generated report template memakai canonical allowlist.
- `node tests/login_role_behavior.js` — lulus; synthetic DOM.
- `php tests/public_asset_resolution.php` — lulus; scratch fixtures.
- `php tests/pdo_parameter_binder.php` — lulus.
- `php tests/pengajuan_decision.php` — lulus; PDO double.
- `php tests/login_authentication.php` — lulus; isolated policy/PDO double.
- PHP lint `LaporanController.php` dan `tests/workflow_static_checks.php` — lulus.
- `git diff --check` — lulus.
- Tidak ada HTTP/browser/PostgreSQL, migration, seeder, server, atau query tulis.

### Tugas tertunda berikutnya

1. Lanjutkan audit dokumen pengajuan magang: upload, endpoint dokumen Dosen/Mitra, static bypass, ownership perusahaan/mahasiswa, MIME dan orphan paths.
2. Lanjutkan audit evidence/signature Logbook termasuk upload replacement dan role/status gates.
3. Susun matriks endpoint hidup per role dari dynamic routes; source coverage masih partial.
4. Rekonsiliasi seed path laporan/sertifikat/profil dan file orphan secara read-only.

## Batch: Pemisahan Administrasi Penetapan Dosen Pembimbing

### Sources checked

- `Dokumentasi/prd/prd.md`, `Dokumentasi/developer/IMPLEMENTATION_PROGRESS.md`, dan checkpoint audit ini.
- `components/dashboard/koordinatorMagang/Sidebar.php` dan `components/dashboard/tendik/Sidebar.php`.
- `controllers/dashboard/mahasiswa/LamarController.php`, `models/PendaftaranMagang.php`, `models/PersetujuanPengajuan.php`, dan `pages/dashboard/mahasiswa/pengajuan/detail.php`.
- `models/PenempatanMagangCoordinator.php`, controller/page Penempatan Koordinator, `models/PermintaanPembimbing.php`, controller/page Mahasiswa dan Dosen, serta `routes/web.php`.

### Findings and file changes

- `menunggu_persetujuan_dosen` tetap merupakan status awal `pendaftaran_magang`, dibuat oleh `PendaftaranMagang::create()` dan ditampilkan pada timeline Status Pengajuan. Status ini tetap dipakai `PersetujuanPengajuan` untuk persetujuan pengajuan magang; tidak diubah dan tidak dicampur dengan assignment pembimbing.
- Relasi assignment tetap `penempatan_magang.dosen_pembimbing_id`, dengan actor `ditetapkan_oleh`. Query assignment hanya menampilkan pendaftaran `diterima` dan penempatan `persiapan`/`berlangsung`; mutation tetap memakai Koordinator, CSRF, transaksi, validasi dosen aktif, dan guard `dosen_pembimbing_id IS NULL`.
- Ditambahkan route khusus `/dashboard/koordinator-magang/penetapan-dosen`, `KoordinatorPenetapanDosenController`, dan halaman `pages/dashboard/koordinatorMagang/penetapan-dosen/index.php`.
- Sidebar Koordinator sekarang memiliki menu `Penetapan Dosen Pembimbing` menuju route khusus tersebut. Sidebar Tendik tidak diberi menu atau hak assignment.
- Halaman `/dashboard/koordinator-magang/penempatan` kini hanya membuat penempatan; UI pemilihan dosen dan assignment dipindahkan ke halaman administrasi khusus.
- Teks halaman mahasiswa dikoreksi agar menyatakan penetapan dilakukan Koordinator Magang, tanpa membuka selector dosen. Permintaan bimbingan mahasiswa dan keputusan Dosen tetap dipertahankan.
- Tidak ada perubahan schema, migration, seeder, operasi database, commit, atau push.

### Test evidence

- PHP lint untuk controller/page baru, controller/page penempatan, Sidebar Koordinator, halaman mahasiswa, route, model, dan test — passed.
- `php tests/bimbingan_dosen.php` — passed setelah regression checks diperluas untuk route/sidebar/page terpisah dan status awal pengajuan.
- `php tests/workflow_static_checks.php` — passed.
- `php tests/smoke_views.php` — passed (38 components).
- `php tests/login_authentication.php` — passed.
- `php tests/pdo_parameter_binder.php` — passed.
- `php tests/pengajuan_decision.php` — passed.
- `php tests/public_asset_resolution.php` — passed.
- `php tests/tendik_user_management.php` — passed.
- `node tests/login_role_behavior.js` — passed.
- `git diff --check` — passed.
- Output juga menunjukkan `Permission denied` saat Git mencoba membaca file existing di `storage/pendaftaran`; file tersebut tidak disentuh.

### Remaining risks

- HTTP/browser dan PostgreSQL runtime belum diuji; tidak ada target disposable yang disetujui.
- Route baru belum diverifikasi melalui server aktif.
- Status workflow pengajuan tetap mengikuti PRD/source saat ini; jika produk bermaksud mengubah urutan persetujuan, keputusan bisnis eksplisit masih diperlukan.

### Next batch

1. Uji manual route baru pada database/browser disposable yang telah disetujui.
2. Lanjutkan audit dokumen pengajuan dan endpoint file tanpa menjalankan operasi tulis.
