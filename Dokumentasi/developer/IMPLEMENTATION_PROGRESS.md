# Implementation Progress MagangVerify

Status: implementasi lintas-role masih berlangsung. Audit keamanan menyeluruh ditunda; pemeriksaan keamanan minimum tetap dilakukan pada fitur yang disentuh.

## Role aplikasi

Role yang terdaftar pada schema/auth saat ini:

- `mahasiswa`: profil, portofolio, sertifikat, pengalaman, formasi, pengajuan, logbook, laporan, notifikasi.
- `dosen`: bimbingan, keputusan pengajuan, logbook/signature, monitoring, penilaian, permintaan pembimbing, notifikasi.
- `koordinator_magang`: penempatan, monitoring/validasi logbook, notifikasi.
- `tendik`: dashboard, validasi logbook, notifikasi, dan Manajemen User pada batch ini.
- `mitra`: profil perusahaan/formasi, respons pengajuan, logbook/signature, penilaian, notifikasi.

Tidak ditemukan role `admin`/`developer` pada constraint role dan allowlist autentikasi.

## Status fitur ringkas

| Role | Selesai/terhubung | Parsial | Belum ada/bermasalah |
|---|---|---|---|
| Mahasiswa | Login, sebagian profil/portofolio/sertifikat/pengalaman, formasi, pengajuan, logbook, laporan, notifikasi | Ownership seluruh endpoint, media, workflow laporan/reviewer, E2E | Aturan reviewer laporan, administrasi user, sebagian media/seed path |
| Dosen | Dashboard, pengajuan, sebagian bimbingan/logbook/monitoring/penilaian/notifikasi | Seluruh query/route dan reviewer scope | Workflow pemeriksaan laporan belum operasional |
| Koordinator | Dashboard, penempatan, validasi logbook, notifikasi | Ownership dan semua dampak workflow | Kewenangan administrasi/reviewer laporan belum ditetapkan |
| Tendik | Dashboard, validasi logbook, notifikasi, Manajemen User | Administrasi di luar user/validasi | Aturan batasan role dan profil domain masih terbuka |
| Mitra | Dashboard, formasi, pengajuan, logbook, penilaian, notifikasi | Isolasi tenant seluruh endpoint | Profil/self-service dan direktori publik memerlukan keputusan |

## Frontend/backend gaps utama

- Manajemen User Tendik tersedia sebagai source-level pada batch ini: route, controller, model, page, sidebar, migration, dan regression checks.
- Login, middleware, dan query akun sudah memakai relasi multi-role, tetapi belum diuji terhadap PostgreSQL terisolasi.
- Workflow pemeriksaan laporan mempunyai model/schema tetapi belum ada endpoint reviewer yang disetujui.
- Media laporan/dokumen/logbook sudah sebagian terhubung, tetapi runtime HTTP belum diuji.
- Sebagian path seed (`uploads/mahasiswa`, `uploads/sertifikat/seed`) belum selaras dengan handler upload saat ini.

## Komponen dan pola yang dapat digunakan kembali

- `AuthMiddleware`, `Helpers::csrfToken/verifyCsrfToken`, `PdoParameterBinder`.
- Layout Tendik: `pages/dashboard/tendik`, `components/dashboard/tendik/{Head,Header,Sidebar,Footer}`.
- Pola controller POST: CSRF, validasi, redirect/error state.
- Model `User` sebagai boundary query akun.
- `routes/web.php` dynamic route dispatcher dan prefix auth pada `index.php`.

## Urutan implementasi

1. Verifikasi route/menu dan lint/regression tanpa DB write.
2. Audit/implementasi endpoint media laporan, dokumen pengajuan, evidence/signature.
3. Matriks endpoint semua role dan penyelesaian frontend/backend parsial berdasarkan dependency.
6. Runtime PostgreSQL/browser hanya setelah target terisolasi disetujui.

## Keputusan bisnis yang masih terbuka

- Apakah semua kombinasi role boleh ditetapkan Tendik atau perlu batasan role tertentu.
- Dashboard default saat user memiliki beberapa role; implementasi awal mempertahankan role yang dipilih saat login dan fallback ke role utama lama.
- Profil khusus (`profil_mahasiswa`, `profil_dosen`, `mitra`) untuk user baru yang diberi role tertentu; Manajemen User batch ini membuat akun inti dan relasi role, bukan mengarang data profil domain.
- Reviewer laporan dan status terminal belum ditetapkan.

## Batch: Bimbingan Dosen

### Sources checked

- PRD, `routes/web.php`, `index.php`, `middleware/AuthMiddleware.php`, CSRF/URL helpers.
- `database/schema.sql`, migrations `001`, `003`, `004`, `028`, and `029`.
- Existing placement, advisor-request, coordinator, lecturer, and student controllers/models/pages.
- Existing local Git changes were preserved; no migration, seeder, database write, commit, or push was performed.

### Findings and file changes

- The schema already has the authoritative relationship `penempatan_magang.dosen_pembimbing_id -> profil_dosen.id` and actor field `ditetapkan_oleh -> users.id`; no new schema was needed.
- Student-side advisor selection was removed by the prior local work, but there was no coordinator assignment UI/backend path and the student request path had no way to submit a request to the assigned advisor.
- Added coordinator assignment queue, active-dosen validation, one-time conditional assignment, transaction, notification, and activity logging in `models/PenempatanMagangCoordinator.php`.
- Extended `controllers/dashboard/koordinatorMagang/PenempatanController.php` and its page with advisor assignment while preserving placement creation.
- Changed `PermintaanPembimbing` student creation to derive `dosen_id` only from the placement assignment; added lecturer-owned request history.
- Restored the student request form without exposing a dosen ID, and added lecturer history rendering.
- Added `tests/bimbingan_dosen.php` static workflow/authorization regression checks.

### Test evidence

- `php tests/bimbingan_dosen.php` — passed.
- PHP syntax checks for all changed Bimbingan files and test — passed.
- `php tests/smoke_views.php` — passed (38 components).
- Isolated existing checks passed: `login_authentication.php`, `pdo_parameter_binder.php`, `pengajuan_decision.php`, `public_asset_resolution.php`, `tendik_user_management.php`, and `node tests/login_role_behavior.js`.
- `git diff --check` — passed.
- `php tests/workflow_static_checks.php` — passed in the follow-up session after two stale assertions were aligned from the old JOIN shape to the actual `SELECT role FROM user_roles WHERE user_id = :user_id`/`getRolesForUser(` contract and the actual legacy fallback comment in `User.php`. The tested behavior contract is unchanged: secondary roles still come from the relation and legacy fallback only handles a missing migration table.
- PostgreSQL, HTTP/browser, and live role/ownership tests remain unrun; no safe disposable target was established.

### Remaining risks

- Current confirmed authority path is Koordinator Magang. Tendik assignment authority remains intentionally unimplemented because repository documentation identifies Tendik for administration/validation but does not approve pembimbing assignment rights.
- Runtime schema compatibility, transactions, row counts, notifications, CSRF, and authorization were only source/isolated-test checked.
- During the follow-up audit, a dead placement/reassignment decision path was fixed in `PermintaanPembimbing::respond()` so lecturer approval now verifies the current assignment belongs to the deciding lecturer and no longer writes `penempatan_magang.dosen_pembimbing_id`.

### Next batch

1. Review whether Tendik may assign advisors and obtain/encode that business decision before adding access.
2. Run approved PostgreSQL/HTTP/browser scenarios if an isolated target is provided.
