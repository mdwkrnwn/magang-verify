# Progres Audit Total MagangVerify

Status: audit masih berlangsung; ini checkpoint untuk melanjutkan di sesi berikutnya, bukan pernyataan bahwa seluruh proyek sudah diaudit atau aman.

Repository: `/home/valencza/projects/magang-verify`  
Branch saat pemeriksaan: `main`  
Dokumen audit pendamping: `Dokumentasi/Repository-Audit-Matrix.md` dan `Dokumentasi/E2E-Test-Plan.md`.

## Status Git dan perlindungan perubahan lokal

- Pemeriksaan terakhir menunjukkan branch `main` mengikuti `origin/main`, dengan banyak file modified dan untracked sebelum checkpoint ini. Daftarnya mencakup kode role, schema, dokumentasi, migration/seed files, tes, uploads, dan rancangan.
- `git diff --name-only` mengonfirmasi perubahan tracked yang tersebar di dokumentasi, komponen, controller, schema, model, router, dan route. Banyak file untracked juga terlihat pada status.
- Karena tidak ada baseline bersih untuk membedakan kepemilikan tiap perubahan, jangan menganggap semua perubahan sebagai hasil audit. Pertahankan semuanya; jangan reset, clean, checkout destruktif, commit, atau push.
- `git diff --check` dijalankan bersama pemeriksaan status dan lulus (exit 0). Status Git diperiksa tanpa mengubah atau membuang perubahan.
- File ini dibuat di `Dokumentasi/developer/` sesuai permintaan; direktori tersebut sebelumnya belum ada. File dokumentasi lain tidak ditimpa.

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
- `function/PublicAsset.php` (baru)
- `router.php`
- `models/PdoParameterBinder.php` (baru)
- `models/PengajuanMitra.php`
- `models/PenilaianMagang.php`
- `database/seeders/user/mahasiswa/SeedDimasLogbook.php` (tidak dijalankan)
- `components/landingPage/mahasiswa/Hero.php`
- `components/landingPage/mahasiswa/profil/Hero.php`
- `tests/smoke_views.php`
- `tests/workflow_static_checks.php`
- `tests/pdo_parameter_binder.php` (baru)
- `tests/public_asset_resolution.php` (baru; belum dijalankan setelah helper tersedia)
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
- PHP lint untuk source PHP di luar `vendor/` dan `node_modules/` — lulus pada run terdahulu: 374 file, 0 kesalahan sintaks. File baru `function/PublicAsset.php` dan `tests/public_asset_resolution.php` ditambahkan setelah hitungan itu, jadi perlu lint ulang.
- Pemetaan source route/controller — 83 pasangan method, 0 unresolved; tidak membuktikan HTTP dispatch/otorisasi.
- Scan include literal — 713 kandidat diperiksa; laporan tersisa diperiksa sebagai potongan string test atau include dinamis. Tidak mencakup seluruh ekspresi dinamis.
- `git diff --check` — lulus pada pemeriksaan Git terakhir.
- `php tests/public_asset_resolution.php` — sempat dijalankan sebelum `function/PublicAsset.php` dibuat dan gagal karena file helper belum ada. **Belum ada hasil run ulang setelah implementasi. Jangan catat sebagai lulus.**

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
- Tes resolver asset baru, lint ulang file yang baru ditambahkan, dan semua tes setelah perubahan router/aset terbaru.
- Tidak ada hasil runtime database/browser/HTTP. Tidak diketahui apakah schema live sesuai snapshot/migration.

## Kendala dan langkah berikutnya (urut)

1. Jalankan `php tests/public_asset_resolution.php` di scratch yang diatur `TMPDIR`; pastikan fixture/symlink cleanup, akses CSS diizinkan, PHP/non-asset/missing/symlink escape ditolak. Jika gagal, perbaiki akar masalah dan jalankan ulang.
2. Jalankan `php -l` untuk `function/PublicAsset.php`, `tests/public_asset_resolution.php`, `router.php`, dua Hero, serta `tests/smoke_views.php`; lalu jalankan `php tests/smoke_views.php`, `php tests/workflow_static_checks.php`, semua tes terisolasi di atas, dan `git diff --check` lagi.
3. Review diff terbatas pada file audit yang disebut di atas, lalu periksa ulang `git status --short`; jangan menyentuh atau membuang perubahan lokal lain. Konfirmasi static asset yang dibutuhkan memang berada di `assets/` dan semua pemanggil yang sah hanya mengakses tipe allowlist.
4. Lanjutkan audit endpoint secara role-per-role dari `routes/web.php` dan dynamic dispatch; catat setiap route dengan caller, middleware, CSRF, controller, model/query, schema, ownership, state/history/notification, redirect/view dan hasil cek.
5. Telusuri seluruh kelompok query/schema serta workflow lintas-role. Untuk konflik migration/schema, kumpulkan bukti dan tulis opsi tanpa menerapkan perubahan atau menjalankan migration.
6. Lanjutkan audit upload/download/router dan browser/JavaScript/assets setelah langkah 1–3 lulus; bedakan pemeriksaan source dari hasil HTTP/browser.
7. Dapatkan persetujuan eksplisit serta target database/browser disposable yang terverifikasi sebelum tes runtime atau operasi data. Tanpa prasyarat tersebut, tandai runtime NOT RUN dan jangan menjalankan migration/seeder/query tulis.
8. Perbarui `Dokumentasi/Repository-Audit-Matrix.md` dan checkpoint ini dengan bukti/hasil aktual setiap batch; jangan tandai audit total selesai sebelum semua area terbuka dicatat dan pengujian yang diizinkan dilaporkan.

## Keputusan/konfirmasi bisnis yang belum boleh ditebak

- Apakah direktori Mitra publik harus tetap dummy, menampilkan data terverifikasi, dan bagaimana status publikasinya ditetapkan.
- Definisi schema Mitra kanonik serta strategi rekonsiliasi migration historis tanpa merusak database.
- Apakah Dosen yang boleh memutuskan harus terikat ke pembimbing/penugasan tertentu atau queue saat ini memang shared.
- Urutan reviewer dan status terminal yang berlaku untuk laporan mingguan/akhir.
- Batas kewenangan Koordinator Magang dan Tendik pada validasi/administrasi.
- Provisioning resmi akun Mitra, sumber kebenaran kode, status aktif, dan mitigasi login berbasis kode saja.
- Target PostgreSQL/browser disposable dan izin menjalankan tes integrasi; tanpa izin, runtime tetap tidak diuji.
