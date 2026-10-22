# MagangVerify Repository Audit Matrix

This is a working audit record, not a claim that every path has passed runtime testing. It records repository-derived inventory and separates audited/changed areas from areas still open. Existing local work is preserved.

## Baseline and scope

- Repository/branch at audit start: `/home/valencza/projects/magang-verify`, `main`.
- Worktree was already heavily modified and contained untracked code, docs, SQL, fixtures, and uploaded PDFs. No pre-existing changes were discarded.
- No `AGENTS.md` was found at repository root in the bounded file lookup. Project guidance read includes `Dokumentasi/Developer/01-ROLE-DAN-SIDEBAR.md`, role-specific developer guides, authorization/logbook docs, PRD, and the existing database-audit proposal.
- Manifests: PHP application with `dompdf/dompdf`; `package.json` only declares Supabase JS packages. No test runner script is defined there.
- Database mutations, migrations, seeders, servers, containers, and external-system writes are prohibited for this audit and have not been run.

## Role × feature inventory

Status legend: `Inventory` means routes/pages/controllers were enumerated; it does not prove the entire workflow works. `Partial source audit` means selected vertical paths have been followed. `Runtime NOT RUN` applies unless evidence below explicitly says otherwise.

| Role | Pages/features found in repository | Access/feature status from source | Audit state |
|---|---|---|---|
| Mahasiswa | Dashboard; profile/settings; portfolio; certificates; experience; placement/formasi browse/detail; application and documents; mentor request; logbook/week/day/signatures; weekly/final reports, revisions and downloads; notifications | Explicit `mahasiswa` middleware on role controllers; owner-scoped operations exist in several models. A full endpoint-by-endpoint ownership audit remains open. | Inventory; Partial source audit for login, application, logbook/report, profile/document protections, mentor request and notifications. Other CRUD/filter paths open. |
| Dosen | Dashboard; bimbingan; application review/document access; logbook validation/signatures; monitoring/detail; mentor-request response; assessment; notifications | Explicit `dosen` guards on inspected controllers. Application queue is not tied to a specific assigned reviewer in the schema/doc notes; current shared Dosen queue is preserved. | Inventory; Partial source audit for application decision, monitoring, logbook, assessment, notifications. Remaining form/query/render paths open. |
| Koordinator Magang (`koordinator_magang`) | Dashboard; placement creation; logbook validation/detail; notifications | Role is distinct from Tendik in route/middleware. Placement and validator scope need full query/controller review. | Inventory; Partial source audit for logbook queue binding and placement. Full workflow open. |
| Tendik (`tendik`) | Dashboard; logbook validation/detail; notifications | Role is distinct from Koordinator. Do not assume global administration rights beyond explicit routes. | Inventory; Partial source audit for logbook validator path. Remaining routes/features open. |
| Mitra (`mitra`) | Dashboard; formasi CRUD/status; applications/responses/documents; logbook/signature; assessment; notifications | Company reads/writes are scoped through `mitra.user_id` in inspected models. The Mitra response update had a confirmed boolean bind defect, corrected in this audit. | Inventory; Partial source audit for response ownership/status, formasi, notification and assessment paths. Full tenant-isolation review open. |
| Developer/administrator | No developer/admin account role found in login role allowlist or `users` role constraint. Repository contains deployment/development docs but that is not an application role. | No admin dashboard/role should be inferred from Tendik or Koordinator. | Checked in auth allowlist/schema excerpt; broader role-alias search remains part of audit. |

## Page and route inventory (source-level)

- `pages/` contains 57 PHP page files at inventory time: public landing/directory and profiles; login; shared dashboard/notifications; Mahasiswa dashboard/profile/settings/portfolio/certificate/experience/formasi/application/mentor/logbook/reports; Dosen dashboard/bimbingan/application/logbook/monitoring/assessment/mentor requests; Mitra dashboard/formasi/applications/logbook/assessment; Koordinator dashboard/placement/logbook; Tendik dashboard/logbook; and 404.
- Page-file inventory (57): `pages/errors/404.php`, `pages/login/index.php`, `pages/landingPage/index.php`, `pages/landingPage/mahasiswa/index.php`, `pages/landingPage/mahasiswa/profil/index.php`, `pages/landingPage/mitra/index.php`, `pages/landingPage/mitra/profil/index.php`, `pages/landingPage/tentang/index.php`, `pages/dashboard/index.php` (legacy/unrouted; now safe 404), `pages/dashboard/shared/notifikasi.php`; Mahasiswa: `pages/dashboard/mahasiswa/index.php`, `profil/index.php`, `pengaturan/index.php`, `portofolio/index.php`, `portofolio/detail.php`, `portofolio/tambah.php`, `sertifikat/index.php`, `sertifikat/detail.php`, `sertifikat/form.php`, `pengalaman/index.php`, `pengalaman/form.php`, `formasi-magang/index.php`, `formasi-magang/detail.php`, `lamar/index.php`, `pengajuan/index.php`, `pengajuan/detail.php`, `pembimbing/index.php`, `logbook/index.php`, `logbook/detail.php`, `logbook/form.php`, `logbook/daily-form.php`, `laporan/index.php`, `laporan/form.php`, `laporan/detail.php`; Dosen: `pages/dashboard/dosen/index.php`, `bimbingan/index.php`, `pengajuanmagang/index.php`, `permintaan-pembimbing/index.php`, `logbook/index.php`, `logbook/detail.php`, `monitoring/index.php`, `monitoring/detail.php`, `penilaian/index.php`; Koordinator: `pages/dashboard/koordinatorMagang/index.php`, `logbook/index.php`, `logbook/detail.php`, `penempatan/index.php`; Tendik: `pages/dashboard/tendik/index.php`, `logbook/index.php`, `logbook/detail.php`; Mitra: `pages/dashboard/mitra/index.php`, `formasi/index.php`, `formasi/form.php`, `pengajuan/index.php`, `logbook/index.php`, `logbook/detail.php`, `penilaian/index.php`.
- `routes/web.php` contains public landing/profile/login/logout routes; role dashboard route families; shared notification route; application, file, signature, report, placement, mentor-request, assessment, and dynamic detail/action dispatch. Static route declarations plus dynamic dispatch exist; every route has not yet been traced individually.
- Dashboard prefix authorization in `index.php` is a second guard for role prefixes. Controllers also have direct middleware guards on inspected protected paths. Dynamic route fallback reachability and every handler's own guard still require a full matrix pass.
- `pages/dashboard/shared/notifikasi.php` used four parent traversals from `pages/dashboard/shared`, resolving outside the repository. Corrected to resolve project root with `dirname(__DIR__, 3)`, select only known role layout directories, and verify each role layout's `Head.php`, `Sidebar.php`, and `Header.php` before include. `Notifications.php` uses the same resolved project root. Render/runtime with DB-backed headers remains NOT RUN.

## Workflow dependency map

1. Login/logout → `LoginController`/`LogoutController` → `users` / linked Mitra profile lookup → session → `AuthMiddleware` → role dashboard. Mitra and Dosen/RBAC fixes are recorded in prior task documentation; live session/authentication is NOT RUN.
2. Application → Mahasiswa form/documents → `pendaftaran_magang`, docs and active formasi → Dosen decision → Mitra response → Coordinator placement → assessment/logbook/report → notifications, history and dashboards. Dosen review requires six listed documents; Mitra query is scoped by `mitra.user_id`; Coordinator placement has its own transaction and status checks. Full cross-role transition walk remains open.
3. Logbook → Mahasiswa week/day input/sign → Mitra sign → Dosen sign and Tendik/Coordinator daily validation → revision/signature/history → report previews/downloads. Prior source-level checks cover owner/status gates and private-file endpoints; PostgreSQL transaction/signature behavior is NOT RUN.
4. Reports → placement/week selection → `laporan_magang` and `laporan_revisi` → document/template/download routes → review records. Reviewer order and terminal approval policy remain explicitly unresolved in existing audit docs.
5. Notifications → role layout → owner-scoped `notifikasi` query/read updates → internal destination validation. Path include is corrected; runtime read/write and target-page reachability remain NOT RUN.

## Confirmed findings and fixes in this audit pass

### High: shared notification page resolved paths outside the repository

- Evidence: `pages/dashboard/shared/notifikasi.php` is three directories below project root, but computed components using `../../../../`; layout selection is set by `NotifikasiController` for the five known roles. Actual role layout directories and the shared `Notifications.php` component exist under `components/dashboard/`.
- Fix: derive repository root from the page location, whitelist the five existing layout directory names, fail safely if required layout files are absent, and include shared `Notifications.php` from the resolved repository root.
- Regression checks added to `tests/smoke_views.php`: verify include-root logic and required layout/component files for all five controller-supported roles.
- Layer: path/render-source check only; page is not rendered with database-backed headers/browser.

### Medium: other dead/wrong include path and private-file role freshness

- `components/dashboard/mahasiswa/portofolio/MainContent.php` used `__DIR__ . "../../Footer.php"`, which formed a nonexistent path; corrected to the actual sibling `components/dashboard/mahasiswa/Footer.php`. Smoke test renders the real empty-state component tree and confirms footer output.
- `pages/dashboard/index.php` had references to four generic dashboard components that do not exist, and no route or other source caller references this legacy page. It now emits the existing 404 page rather than a sequence of include warnings. Role dashboards use their actual role-specific pages.
- Dynamic dispatch in `routes/web.php` calls `LogbookFileController` directly and exits before the front-controller prefix middleware. The controller checked only the session role allowlist and did not refresh account role/active state; added `AuthMiddleware::handle($role)` before fetching protected file metadata. Existing model owner/role predicates and private storage checks remain in place. Runtime authorization still NOT RUN.
- Empty `controllers/dashboard/dosen/DosenBimbinganController.php` has no route/caller in source; the active route uses `MahasiswaBimbinganController`. It remains untouched as an unreferenced file pending broader legacy-file review.

### High: PostgreSQL boolean execution-array coercion found in three workflow paths

- Mitra response (`models/PengajuanMitra.php`): SQL placeholder order is `:decision`, `:note`, `:next_status`, `:terminal`, `:id`; therefore `$4` is the boolean `$decision !== 'seleksi'`. Sending `false` in `execute([...])` can become empty text for PostgreSQL. Changed this and assessment DML to a shared binder that calls `bindValue()` with `PDO::PARAM_BOOL`, `PDO::PARAM_NULL`, `PDO::PARAM_INT`, or `PDO::PARAM_STR`, then executes without the untyped array.
- Assessment (`models/PenilaianMagang.php`): both update and insert have `CASE WHEN :is_submitted`; `scoreParams()` passed `$submit` through `execute($params)`. Both paths now use the typed binder. Draft binds false as boolean, submit binds true.
- Dosen application decision (`models/PersetujuanPengajuan.php`): previous fix already introduced `PengajuanDecision::bindUpdate()` with explicit parameter types; this pass retains it and adds broader audit checks. The historical report's `$5` was `:terminal`, false for approval.
- No schema or migration change was made. Unit test `tests/pdo_parameter_binder.php` checks types with a PDOStatement double; this does not execute PostgreSQL SQL.

## Query, schema, and security review coverage

- Schema snapshot and migrations were inventoried. The schema/migration history includes conflicting duplicate definitions for Mitra/Formasi (including migration 012's Mitra shape versus the application-used `user_id`, `kode_perusahaan`, `is_active` fields); live database structure/history has not been inspected. Do not apply migrations until a canonical baseline is approved.
- All role prefixes exist in the auth allowlist. Auth middleware re-reads role and active status. Forms for inspected mutations have CSRF tokens and controllers verify them. This is not yet a per-handler exhaustive proof.
- Inspected Mitra response mutation obtains the application via company ownership, locks the application, checks Dosen-approved/current-stage statuses, serializes quota increment on acceptance, conditionally updates, checks one affected row, writes history/notification in the transaction, and logs activity after commit. Runtime rollback, concurrency and row-count behavior remain unverified.
- Inspected Dosen decision locks the pending application, preserves six-document approval gate, conditionally updates only pending decisions, records history/notification transactionally, and logs activity after commit. No assigned reviewer FK/policy was found in the checked schema; broader Dosen authority is a product decision, not changed here.
- Notifikasi model scopes list/read/all-read by authenticated `user_id`; mutating controller requires CSRF. Notification destinations reject external schemes/hosts and malformed paths, but all emitted destination routes have not been checked for role reachability.
- Static private-file protections exist in router/authorized endpoints and `.htaccess`; every upload/download callsite and effective web-server mapping still need review.
- Public directory caveat: `controllers/MitraController.php` loads `data/landingPage/Mitra.php`, whose own comment explicitly labels company/formasi data as dummy and not database-backed. Authenticated Mitra management and student formasi browsing use database models; the public `/mitra` directory/detail remains prototype content. Whether to replace it with verified live records is a product/data-publication decision, so no data source or publication rule was invented.
- The public Mahasiswa directory uses `ProfilMahasiswa::getPublicMahasiswa()` and the landing hero uses `getFeaturedMahasiswa()`; these are database-backed source paths, with public visibility predicates to be included in the remaining cross-feature review.

## Tests discovered and status

At inventory time, no project-level test runner is declared in `composer.json` or `package.json`. Tests present under `tests/` include `smoke_views.php`, `workflow_static_checks.php`, `login_role_behavior.js`, `login_authentication.php`, `pengajuan_decision.php`, and `pdo_parameter_binder.php`.

- `php tests/smoke_views.php`: PASSED — `Repository view smoke checks passed (38 components).` Includes notification include-root/layout-file checks for all five role layouts.
- `php tests/workflow_static_checks.php`: PASSED — `Workflow static security checks passed (conditional validation and private file access).` Includes typed boolean use and protected workflow guards.
- `node tests/login_role_behavior.js`: PASSED — synthetic DOM only.
- `php tests/login_authentication.php`: PASSED — isolated policy and PDO-double model invocation only.
- `php tests/pengajuan_decision.php`: PASSED — note rules and Dosen typed bindings using a statement double.
- `php tests/pdo_parameter_binder.php`: PASSED — bool/null/int/string PDO binding types using a statement double.
- PHP lint outside `vendor/` and `node_modules/`: PASSED — 374 files, 0 syntax errors.
- `git diff --check`: PASSED.
- Supplemental route/controller source audit: 83 static/dynamic class-method pairs checked, 0 unresolved. This does not test HTTP dispatch or method restrictions.
- Supplemental literal-include scan: 713 string candidates; the only 3 scanner reports are two path fragments embedded inside test assertion strings and the front controller's dynamic `$route['view']` include. Manual inspection confirmed those are not missing target files; notification and portfolio include paths were separately resolved and checked.
- PostgreSQL/browser/HTTP tests: NOT RUN; no database writes, migrations, seeds, server, or container permitted.

## Remaining audit checklist

- [x] Inventory the five stored roles, route families, and 57 page files; developer/admin is not in the inspected role allowlist/schema role constraint.
- [x] Correct the shared notification page's repository-root calculation and add role-layout file checks.
- [x] Correct the active Mahasiswa portfolio MainContent footer include; quarantine the unreferenced generic dashboard page to a safe 404 instead of preserving nonexistent generic component includes.
- [x] Refresh role/status authorization on private Logbook file requests; dynamic route dispatch invokes this controller before the front-controller dashboard-prefix middleware.
- [x] Type-bind the confirmed Mitra response `$4`, assessment submission flags (both insert/update), and retained Dosen decision `$5`; checked the remaining application-side `CASE WHEN :...` cases and direct boolean execute arrays. Seed helper inserts already bind booleans explicitly; the one direct boolean update in `SeedDimasLogbook.php` now uses the typed binder. No seeder was run.
- [x] Add static/render/isolated tests for the corrections; all listed local checks pass as recorded above.
- [ ] Full feature-by-feature workflow audit and browser/database integration verification remain open; see the checklist below.
- [ ] Enumerate every static and dynamic route with HTTP method, controller action, role boundary, form/link caller and destination; verify no endpoint is reachable solely through navigation assumptions.
- [ ] Trace every DML model/query and compare placeholder names/types/values to schema; include boolean, enum, date, nullable, duplicate named-placeholder and transaction branches.
- [ ] Complete ownership/IDOR review for all Mahasiswa profile/portfolio/certificate/experience/application/logbook/report CRUD and file endpoints.
- [ ] Complete Mitra cross-company isolation review for formasi, applications, documents, signatures and assessment; check all list and direct-ID endpoints.
- [ ] Complete Dosen/Koordinator/Tendik authorization review and compare authority with documented rules; flag unassigned reviewer/admin scope questions rather than inventing policy.
- [ ] Verify every route's view includes, every included component file, role header/sidebar links, JS event/query endpoint, form field names and success/error/empty states.
- [ ] Trace all application/status transition producers and consumers through reports, dashboard counts, history, placement, notifications and role queues.
- [ ] Review notification destination route validity, ownership after click, mark-read semantics and cross-role rendering.
- [ ] Audit upload validation, storage containment, MIME/signature checks, download headers, deletion behavior, static router blocking, and all upload/download endpoints.
- [ ] Review all public landing-page data/filters/pagination and determine where fixture/dummy data is documented versus operational.
- [ ] Compare all five role menus and implemented pages with PRD/developer docs; reconcile stale docs and missing/placeholder workflows.
- [ ] Review JavaScript/assets and CSS references, fetch error states, responsive interactions, and browser behavior.
- [ ] Rerun required tests/lint/diff checks; secure a separately authorized disposable PostgreSQL/browser target before runtime assertions.
