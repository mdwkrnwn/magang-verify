<?php

declare(strict_types=1);

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function url(string $path = ''): string
{
    return '/magang-verify/' . ltrim($path, '/');
}

function initials(string $name): string
{
    $parts = preg_split('/\s+/', trim($name)) ?: [];
    return strtoupper(substr(implode('', array_map(static fn(string $part): string => $part[0] ?? '', array_slice($parts, 0, 2))), 0, 2));
}

function icon(string $name, string $classes = ''): string
{
    return '<span class="' . e($classes) . '">' . e($name) . '</span>';
}

function csrfToken(): string
{
    return 'smoke-test-token';
}

function renderComponent(string $path, array $variables = []): string
{
    extract($variables, EXTR_SKIP);
    ob_start();
    include $path;
    return (string) ob_get_clean();
}

function expectContains(string $html, string $needle, string $test): void
{
    if (!str_contains($html, $needle)) {
        throw new RuntimeException($test . ': missing expected output ' . $needle);
    }
}

function expectNotContains(string $html, string $needle, string $test): void
{
    if (str_contains($html, $needle)) {
        throw new RuntimeException($test . ': found unexpected output ' . $needle);
    }
}

$profilePhotoForm = renderComponent(__DIR__ . '/../components/dashboard/mahasiswa/profil/forms/EditPhotoForm.php');
$profileCvForm = renderComponent(__DIR__ . '/../components/dashboard/mahasiswa/profil/forms/EditCvForm.php');
expectContains($profilePhotoForm, 'name="foto_profil"', 'profile photo form submits expected upload field');
expectContains($profilePhotoForm, 'name="_csrf_token"', 'profile photo form includes CSRF');
expectContains($profilePhotoForm, 'enctype="multipart/form-data"', 'profile photo form enables upload encoding');
expectContains($profileCvForm, 'name="cv"', 'profile CV form submits expected upload field');
expectContains($profileCvForm, 'application/pdf,.pdf', 'profile CV form communicates PDF-only requirement');
expectContains($profileCvForm, 'name="_csrf_token"', 'profile CV form includes CSRF');
$loginFormHtml = renderComponent(__DIR__ . '/../components/login/Form.php');
expectContains($loginFormHtml, 'id="password"', 'login form exposes password input for every selected role');
expectContains($loginFormHtml, 'name="password"', 'login form submits password to authentication controller');
expectContains($loginFormHtml, 'autocomplete="current-password"', 'login password field has browser credential semantics');
expectContains($loginFormHtml, 'name="_csrf_token"', 'login form includes CSRF');
expectNotContains($loginFormHtml, 'type="password" hidden', 'login password control is not hidden for Mitra');
$dosenDecisionForm = renderComponent(
    __DIR__ . '/../components/dashboard/dosen/pengajuanmagang/SubmissionList.php',
    [
        'items' => [[
            'id' => 71,
            'mahasiswa_nama' => 'Mahasiswa Fixture',
            'nim' => 'NIM-FIXTURE',
            'nama_perusahaan' => 'Perusahaan Fixture',
            'judul' => 'Magang Fixture',
            'tanggal_pengajuan' => '2026-10-20 09:00:00',
            'dokumen' => array_map(
                static fn(string $kind): array => ['id' => 1, 'jenis' => $kind, 'nama' => $kind . '.pdf'],
                ['pakta_integritas', 'daftar_riwayat_hidup', 'khs', 'ktp', 'ktm', 'surat_izin_orang_tua']
            ),
        ]],
        'formValues' => [],
    ]
);
expectContains($dosenDecisionForm, 'opsional saat menyetujui, wajib saat menolak', 'Dosen decision form explains decision-specific note requirement');
expectContains($dosenDecisionForm, 'note.required=false', 'approval form permits an empty decision note');
expectContains($dosenDecisionForm, 'note.required=true', 'rejection form requires a decision note');
expectContains($dosenDecisionForm, 'Alasan penolakan wajib diisi.', 'rejection form provides an explicit empty-note validation message');
$notificationPageSource = file_get_contents(__DIR__ . '/../pages/dashboard/shared/notifikasi.php');
expectContains((string) $notificationPageSource, 'dirname(__DIR__, 3)', 'shared notification page resolves project root from its own location');
$notificationProjectRoot = dirname(__DIR__ . '/../pages/dashboard/shared', 3);
expectContains((string) $notificationPageSource, '$componentRoot . \'/Head.php\'', 'notification page includes the resolved role layout head');
expectContains((string) $notificationPageSource, '$projectRoot . \'/components/dashboard/shared/Notifications.php\'', 'notification page includes the shared notification view');
foreach (['mahasiswa', 'dosen', 'mitra', 'koordinatorMagang', 'tendik'] as $notificationLayout) {
    foreach (['Head.php', 'Sidebar.php', 'Header.php'] as $layoutComponent) {
        if (!is_file($notificationProjectRoot . '/components/dashboard/' . $notificationLayout . '/' . $layoutComponent)) {
            throw new RuntimeException('Notification role layout is missing ' . $notificationLayout . '/' . $layoutComponent);
        }
    }
}
if (!is_file($notificationProjectRoot . '/components/dashboard/shared/Notifications.php')) {
    throw new RuntimeException('Shared notification component path does not resolve.');
}
$portfolioMainContent = file_get_contents(__DIR__ . '/../components/dashboard/mahasiswa/portofolio/MainContent.php');
expectContains((string) $portfolioMainContent, "__DIR__ . '/../Footer.php'", 'portfolio page resolves the Mahasiswa footer relative to its component directory');
if (!is_file(__DIR__ . '/../components/dashboard/mahasiswa/Footer.php')) {
    throw new RuntimeException('Mahasiswa portfolio footer include target is missing.');
}
$portfolioEmptyPage = renderComponent(
    __DIR__ . '/../components/dashboard/mahasiswa/portofolio/MainContent.php',
    [
        'base' => url('/'),
        'q' => '',
        'tahun' => '',
        'tahunList' => [],
        'status' => '',
        'teknologi' => '',
        'teknologiList' => [],
        'tampil' => [],
        'pagination' => ['current_page' => 1, 'total_page' => 1, 'total_data' => 0, 'per_page' => 6],
    ]
);
expectContains($portfolioEmptyPage, 'Portofolio tidak ditemukan.', 'portfolio MainContent renders its empty state through the corrected footer include');
expectContains($portfolioEmptyPage, '© 2026 VerifyMagang.', 'portfolio MainContent resolves and renders the shared Mahasiswa footer');
$legacyDashboardPage = file_get_contents(__DIR__ . '/../pages/dashboard/index.php');
expectContains((string) $legacyDashboardPage, 'http_response_code(404)', 'unrouted generic dashboard page fails safely instead of including nonexistent generic components');
$studentDirectoryHero = file_get_contents(__DIR__ . '/../components/landingPage/mahasiswa/Hero.php');
expectContains((string) $studentDirectoryHero, "url('/assets/images/hero.png')", 'student directory hero uses an existing student-themed image');
if (!is_file(__DIR__ . '/../assets/images/hero.png')) {
    throw new RuntimeException('Student directory hero asset is missing.');
}
$studentProfileHero = file_get_contents(__DIR__ . '/../components/landingPage/mahasiswa/profil/Hero.php');
expectContains((string) $studentProfileHero, "url('/assets/images/gedung-polinema.jpeg')", 'student profile hero uses the existing campus image');
if (!is_file(__DIR__ . '/../assets/images/gedung-polinema.jpeg')) {
    throw new RuntimeException('Student profile campus image is missing.');
}

$rejectedAbsenceStatus = renderComponent(
    __DIR__ . '/../components/dashboard/mahasiswa/logbook/DailyValidationStatus.php',
    ['daily' => ['id' => 9, 'status_kehadiran' => 'tidak_hadir', 'status_validasi' => 'ditolak', 'catatan_validasi' => 'Bukti kurang jelas', 'bukti_path' => 'storage/logbook/evidence/9.pdf']]
);
expectContains($rejectedAbsenceStatus, 'Tidak hadir', 'daily validation status displays attendance state separately');
expectContains($rejectedAbsenceStatus, 'Ketidakhadiran ditolak', 'daily validation status distinguishes rejected validation');
expectContains($rejectedAbsenceStatus, 'Bukti kurang jelas', 'daily validation status displays validator note');
expectContains($rejectedAbsenceStatus, '/dashboard/logbook/file/evidence/9', 'daily evidence links use the authorized file endpoint');
$absenceWithoutEvidence = renderComponent(
    __DIR__ . '/../components/dashboard/mahasiswa/logbook/DailyValidationStatus.php',
    ['daily' => ['id' => 10, 'status_kehadiran' => 'tidak_hadir', 'status_validasi' => 'menunggu', 'bukti_path' => null]]
);
expectNotContains($absenceWithoutEvidence, 'file/evidence/', 'daily validation status hides evidence link when no evidence exists');
$presentAttendance = renderComponent(
    __DIR__ . '/../components/dashboard/mahasiswa/logbook/DailyValidationStatus.php',
    ['daily' => ['id' => 11, 'status_kehadiran' => 'hadir', 'status_validasi' => 'tidak_perlu', 'bukti_path' => null]]
);
expectContains($presentAttendance, 'Hadir', 'present daily logbook displays attendance state');
expectNotContains($presentAttendance, 'validasi', 'present daily logbook does not imply an absence validation stage');
$unknownAttendance = renderComponent(
    __DIR__ . '/../components/dashboard/mahasiswa/logbook/DailyValidationStatus.php',
    ['daily' => ['id' => 12, 'status_kehadiran' => 'unexpected', 'status_validasi' => 'disetujui', 'bukti_path' => null]]
);
expectContains($unknownAttendance, 'Status kehadiran belum dikenali', 'unknown daily attendance is not represented as success');
expectNotContains($unknownAttendance, 'Ketidakhadiran disetujui', 'unknown attendance does not show a successful validation label');
$weeklyLogbookList = renderComponent(
    __DIR__ . '/../components/dashboard/mahasiswa/logbook/LogbookContent.php',
    [
        'placement' => ['id' => 5, 'status' => 'berlangsung', 'nama_perusahaan' => 'Mitra Fixture', 'judul' => 'Developer', 'tanggal_mulai' => '2026-10-01', 'tanggal_selesai' => '2026-12-01'],
        'placements' => [],
        'weeks' => [['id' => 21, 'minggu_ke' => 2, 'status' => 'draft', 'tanggal_mulai' => '2026-10-12', 'tanggal_selesai' => '2026-10-18', 'hari_terisi' => 5, 'validasi_menunggu' => 1, 'validasi_ditolak' => 2]],
        'nextWeek' => null,
        'summary' => ['total' => 1, 'disetujui' => 0, 'menunggu' => 0, 'revisi' => 0],
    ]
);
expectContains($weeklyLogbookList, 'Validasi menunggu: 1', 'weekly logbook list displays waiting validation count');
expectContains($weeklyLogbookList, 'Validasi ditolak: 2', 'weekly logbook list displays rejected validation count');
$rejectedWeeklyReport = renderComponent(
    __DIR__ . '/../components/dashboard/mahasiswa/laporan/WeeklyActivities.php',
    ['selectedLogbook' => ['id' => 12], 'selectedWeek' => 2, 'weeklyActivities' => [[
        'tanggal' => '2026-10-14',
        'kegiatan' => 'Mengikuti rapat <script>alert(1)</script>',
        'status_kehadiran' => 'tidak_hadir',
        'status_validasi' => 'ditolak',
    ]]]
);
expectContains($rejectedWeeklyReport, 'Kehadiran / Validasi', 'weekly report preview includes logbook attendance/validation state');
expectContains($rejectedWeeklyReport, 'Tidak hadir · Ditolak', 'weekly report preview shows rejected absence validation');
expectContains($rejectedWeeklyReport, 'Mengikuti rapat &lt;script&gt;alert(1)&lt;/script&gt;', 'weekly report preview escapes logbook activity');
$emptyWeeklyReport = renderComponent(
    __DIR__ . '/../components/dashboard/mahasiswa/laporan/WeeklyActivities.php',
    ['selectedLogbook' => ['id' => 12], 'selectedWeek' => 2, 'weeklyActivities' => []]
);
expectContains($emptyWeeklyReport, 'belum memiliki kegiatan', 'weekly report preview explains an empty source logbook');
$missingWeeklyLogbook = renderComponent(
    __DIR__ . '/../components/dashboard/mahasiswa/laporan/WeeklyActivities.php',
    ['selectedLogbook' => null, 'selectedWeek' => 3, 'weeklyActivities' => []]
);
expectContains($missingWeeklyLogbook, 'belum tersedia pada penempatan ini', 'weekly report preview explains an unavailable source week');
$presentWeeklyReport = renderComponent(
    __DIR__ . '/../components/dashboard/mahasiswa/laporan/WeeklyActivities.php',
    ['selectedLogbook' => ['id' => 12], 'selectedWeek' => 2, 'weeklyActivities' => [['tanggal' => '2026-10-14', 'kegiatan' => 'Hadir dan bekerja', 'status_kehadiran' => 'hadir', 'status_validasi' => 'tidak_perlu']]]
);
expectContains($presentWeeklyReport, 'Hadir</span>', 'weekly report preview distinguishes attendance from absence validation');
expectNotContains($presentWeeklyReport, 'Tidak hadir ·', 'weekly report preview does not mislabel present days');
$unknownWeeklyReport = renderComponent(
    __DIR__ . '/../components/dashboard/mahasiswa/laporan/WeeklyActivities.php',
    ['selectedLogbook' => ['id' => 12], 'selectedWeek' => 2, 'weeklyActivities' => [['tanggal' => '2026-10-14', 'kegiatan' => 'Status tak dikenal', 'status_kehadiran' => 'unexpected', 'status_validasi' => 'disetujui']]]
);
expectContains($unknownWeeklyReport, 'Status kehadiran belum dikenali', 'weekly report preview does not treat unknown attendance as present');
expectNotContains($unknownWeeklyReport, 'Hadir</span>', 'unknown weekly attendance is not styled as successful attendance');

$uploadPanelHtml = renderComponent(
    __DIR__ . '/../components/dashboard/mahasiswa/laporan/UploadPanel.php',
    [
        'formAction' => '/magang-verify/dashboard/mahasiswa/laporan/detail/10',
        'uploadHeading' => 'Unggah Revisi',
        'hasRevisionContext' => true,
        'uploadFormValues' => ['ringkasan' => 'Perbaikan <b>bab 1</b>', 'catatan_revisi' => 'Menanggapi catatan'],
    ]
);
expectContains($uploadPanelHtml, 'name="_csrf_token"', 'report upload panel retains CSRF');
expectContains($uploadPanelHtml, 'Perbaikan &lt;b&gt;bab 1&lt;/b&gt;', 'report upload panel preserves and escapes summary after failure');
expectContains($uploadPanelHtml, 'Menanggapi catatan', 'report upload panel preserves revision note after failure');
expectContains($uploadPanelHtml, 'aria-busy', 'report upload panel exposes loading state');
expectContains($uploadPanelHtml, 'application/pdf,.pdf', 'report upload panel communicates allowed file type');

$applicationFlowHtml = renderComponent(__DIR__ . '/../components/dashboard/mahasiswa/lamar/Alur.php');
expectContains($applicationFlowHtml, 'Persetujuan Dosen', 'application form explains the documented lecturer review stage');
expectContains($applicationFlowHtml, 'Respons Mitra', 'application form explains the partner response stage');
expectContains($applicationFlowHtml, 'Penempatan Magang', 'application form explains placement after acceptance');
expectNotContains($applicationFlowHtml, 'Persetujuan KPS', 'application form excludes obsolete undocumented approval stages');
$applicationActionHtml = renderComponent(
    __DIR__ . '/../components/dashboard/mahasiswa/lamar/Action.php',
    ['formasi' => ['slug' => '88'], 'errors' => [], 'success' => false, 'existingApplication' => false]
);
expectContains($applicationActionHtml, 'name="submit_pengajuan"', 'student application form submits through its existing endpoint');
expectContains($applicationActionHtml, '/dashboard/mahasiswa/lamar/88', 'student application form action targets the matching slug route');
expectContains($applicationActionHtml, 'aria-busy', 'student application submit shows loading state');
$blockedApplicationActionHtml = renderComponent(
    __DIR__ . '/../components/dashboard/mahasiswa/lamar/Action.php',
    ['formasi' => ['slug' => '88'], 'errors' => ['umum' => 'Formasi ditutup'], 'success' => false, 'existingApplication' => true]
);
expectContains($blockedApplicationActionHtml, 'disabled', 'student application submit is disabled when server blocks the application');

$openFormasiHeader = renderComponent(
    __DIR__ . '/../components/dashboard/mahasiswa/formasi-magang/detail/Header.php',
    ['formasi' => ['status' => 'tersedia', 'status_formasi' => 'dibuka', 'slug' => '88', 'perusahaan' => 'Mitra Dummy', 'posisi' => 'Intern', 'lokasi' => 'Bandung', 'durasi' => '3 Bulan']]
);
expectContains($openFormasiHeader, '/dashboard/mahasiswa/lamar/88', 'open formasi detail offers an application action');
$closedFormasiHeader = renderComponent(
    __DIR__ . '/../components/dashboard/mahasiswa/formasi-magang/detail/Header.php',
    ['formasi' => ['status' => 'tersedia', 'status_formasi' => 'ditutup', 'slug' => '89', 'perusahaan' => 'Mitra Dummy', 'posisi' => 'Intern', 'lokasi' => 'Bandung', 'durasi' => '3 Bulan']]
);
expectContains($closedFormasiHeader, 'Pendaftaran Ditutup', 'closed formasi detail explains why applications are unavailable');
expectNotContains($closedFormasiHeader, '/dashboard/mahasiswa/lamar/89', 'closed formasi detail hides the application action');

$monitoringHtml = renderComponent(
    __DIR__ . '/../components/dashboard/dosen/monitoring/MonitoringList.php',
    ['monitoringData' => [[
        'id' => 77,
        'nama' => 'Monitoring Fixture',
        'nim' => 'TEST-77',
        'prodi' => 'TI',
        'mitra' => 'Perusahaan Fixture',
        'periode' => '01 Jan 2026 – 30 Jun 2026',
        'status' => 'menunggu_penilaian',
        'status_label' => 'Menunggu Penilaian',
        'initial' => 'MF',
    ]]]
);
expectContains($monitoringHtml, 'Monitoring Fixture', 'lecturer monitoring renders database-provided student');
expectContains($monitoringHtml, '/dashboard/dosen/monitoring/detail/77', 'lecturer monitoring detail action has a matching detail endpoint');
expectContains($monitoringHtml, '/dashboard/dosen/penilaian', 'lecturer assessment action points to routed assessment page');
expectNotContains($monitoringHtml, 'Ahmad Rizki Pratama', 'lecturer monitoring excludes hardcoded sample records');
$emptyMonitoringHtml = renderComponent(__DIR__ . '/../components/dashboard/dosen/monitoring/MonitoringList.php', ['monitoringData' => []]);
expectContains($emptyMonitoringHtml, 'Belum ada mahasiswa bimbingan', 'lecturer monitoring renders empty state without database rows');
$monitoringStatsHtml = renderComponent(
    __DIR__ . '/../components/dashboard/dosen/monitoring/Statistics.php',
    ['statistics' => ['persiapan' => 1, 'berlangsung' => 2, 'menunggu_penilaian' => 3, 'selesai' => 4]]
);
if (!preg_match('/Menunggu Penilaian[\\s\\S]*?<p class="mt-2 text-3xl font-bold text-slate-900">\\s*3\\s*<\\/p>/', $monitoringStatsHtml)) {
    throw new RuntimeException('Lecturer monitoring statistics should render the supplied assessment count.');
}

$logbookHtml = renderComponent(
    __DIR__ . '/../components/dashboard/dosen/logbook/LogbookList.php',
    ['items' => [[
        'id' => 8,
        'mahasiswa_nama' => 'Nama <script>',
        'nim' => 'TEST-8',
        'nama_perusahaan' => 'Mitra Fixture',
        'minggu_ke' => 2,
        'tanggal_mulai' => '2026-10-12',
        'tanggal_selesai' => '2026-10-18',
        'status' => 'menunggu_dosen',
    ]]]
);
expectContains($logbookHtml, 'Nama &lt;script&gt;', 'dosen logbook list escapes student name');
expectContains($logbookHtml, 'detail/8', 'dosen logbook list links to real detail id');
expectNotContains($logbookHtml, 'Ahmad Rizki', 'dosen logbook list excludes sample rows');

$studentsHtml = renderComponent(
    __DIR__ . '/../components/dashboard/dosen/mhsbimbingan/StudentList.php',
    [
        'statistics' => ['mahasiswa' => 1],
        'students' => [[
            'penempatan_id' => 9,
            'status_penempatan' => 'berlangsung',
            'status_label' => 'Sedang Magang',
            'tanggal_mulai' => '2026-10-01',
            'tanggal_selesai' => '2026-12-01',
            'mahasiswa_id' => 3,
            'mahasiswa_nama' => 'Mhs <b>Fixture</b>',
            'nim' => 'TEST-9',
            'program_studi' => 'TI',
            'judul' => 'Developer',
            'nama_perusahaan' => 'Mitra Fixture',
        ]],
    ]
);
expectContains($studentsHtml, 'Mhs &lt;b&gt;Fixture&lt;/b&gt;', 'mentorship list escapes student name');
expectContains($studentsHtml, 'Mitra Fixture', 'mentorship list renders related company');
expectNotContains($studentsHtml, 'Ahmad Rizki', 'mentorship list excludes sample rows');

$submissionsHtml = renderComponent(
    __DIR__ . '/../components/dashboard/dosen/pengajuanmagang/SubmissionList.php',
    ['items' => [[
        'id' => 3,
        'mahasiswa_nama' => 'Applicant Fixture',
        'nim' => 'TEST-3',
        'judul' => 'Backend Developer',
        'nama_perusahaan' => 'Company Fixture',
        'tanggal_pengajuan' => '2026-10-19 10:00:00',
        'dokumen' => [['id' => 17, 'jenis' => 'khs', 'nama' => 'khs.pdf']],
    ]], 'formValues' => [3 => 'Catatan dosen tersimpan']]
);
expectContains($submissionsHtml, 'Applicant Fixture', 'dosen application queue renders applicant');
expectContains($submissionsHtml, 'pengajuan/dokumen/17', 'dosen application queue links to authorized file handler');
expectContains($submissionsHtml, 'name="_csrf_token"', 'dosen decision form contains CSRF token');
expectNotContains($submissionsHtml, '230101001', 'dosen application queue excludes sample rows');
expectContains($submissionsHtml, 'name="decision" value=""', 'dosen decision uses the explicit submitted decision field');
expectContains($submissionsHtml, 'Catatan dosen tersimpan', 'dosen queue preserves note after validation failure');
expectContains($submissionsHtml, 'window.confirm', 'dosen decision form confirms consequential actions before submit');
expectContains($submissionsHtml, '1/6 dokumen wajib', 'dosen queue makes missing required documents visible');
expectContains($submissionsHtml, 'disabled title="Lengkapi dokumen wajib terlebih dahulu"', 'dosen approval action is disabled when required documents are missing');

$completeSubmissionHtml = renderComponent(
    __DIR__ . '/../components/dashboard/dosen/pengajuanmagang/SubmissionList.php',
    ['items' => [[
        'id' => 4,
        'mahasiswa_nama' => 'Complete Applicant',
        'nim' => 'TEST-4',
        'judul' => 'Developer',
        'nama_perusahaan' => 'Company Fixture',
        'tanggal_pengajuan' => '2026-10-19 10:00:00',
        'dokumen' => array_map(static fn(string $kind): array => ['id' => 40, 'jenis' => $kind, 'nama' => $kind . '.pdf'], [
            'pakta_integritas', 'daftar_riwayat_hidup', 'khs', 'ktp', 'ktm', 'surat_izin_orang_tua',
        ]),
    ]]]
);
expectContains($completeSubmissionHtml, '6/6 dokumen wajib', 'dosen queue shows complete required document count');
expectContains($completeSubmissionHtml, 'value="disetujui"', 'dosen can see the existing approval action when documents are complete');
expectNotContains($completeSubmissionHtml, 'disabled title=', 'dosen approval action is enabled when required documents are complete');
$errorSubmissionsHtml = renderComponent(
    __DIR__ . '/../components/dashboard/dosen/pengajuanmagang/SubmissionList.php',
    ['items' => [], 'error' => 'Antrean belum dapat dimuat.']
);
expectNotContains($errorSubmissionsHtml, 'Tidak ada pengajuan yang menunggu persetujuan', 'dosen queue does not confuse load failure with empty data');

$mitraApplicationsHtml = renderComponent(
    __DIR__ . '/../components/dashboard/mitra/pengajuan/ApplicationQueue.php',
    [
        'items' => [[
            'id' => 42,
            'mahasiswa_nama' => 'Mitra Applicant',
            'nim' => 'M-42',
            'program_studi' => 'TI',
            'judul' => 'QA Intern',
            'status_pendaftaran' => 'menunggu_respons_mitra',
            'nama_perusahaan' => 'Company Fixture',
            'tanggal_pengajuan' => '2026-10-19 10:00:00',
            'tanggal_mulai' => '2026-11-01',
            'tanggal_selesai' => '2027-01-01',
            'catatan_dosen' => 'Dokumen lengkap',
            'dokumen' => [['id' => 77, 'jenis' => 'khs', 'nama' => 'khs.pdf']],
        ]],
        'formValues' => [42 => 'Catatan sebelumnya'],
        'success' => false,
        'error' => null,
    ]
);
expectContains($mitraApplicationsHtml, 'Mitra Applicant', 'mitra application page renders applicant');
expectContains($mitraApplicationsHtml, 'pengajuan/dokumen/77', 'mitra application page links to secured document endpoint');
expectContains($mitraApplicationsHtml, 'Catatan sebelumnya', 'mitra application page restores note after validation error');
expectContains($mitraApplicationsHtml, 'value="seleksi"', 'mitra decision offers documented selection action');
expectContains($mitraApplicationsHtml, 'name="_csrf_token"', 'mitra decision form contains CSRF token');
expectContains($mitraApplicationsHtml, 'aria-busy', 'mitra decision form exposes submitting state');
expectContains($mitraApplicationsHtml, 'window.confirm', 'mitra accept/reject asks for confirmation before submit');
expectNotContains($mitraApplicationsHtml, '<script>alert', 'mitra applicant content is escaped');

$selectionApplicationsHtml = renderComponent(
    __DIR__ . '/../components/dashboard/mitra/pengajuan/ApplicationQueue.php',
    ['items' => [[
        'id' => 43,
        'mahasiswa_nama' => 'Selection Fixture',
        'nim' => 'M-43',
        'judul' => 'QA Intern',
        'status_pendaftaran' => 'seleksi',
        'tanggal_pengajuan' => '2026-10-19 10:00:00',
        'dokumen' => [],
    ]]]
);
expectContains($selectionApplicationsHtml, 'Tahap Seleksi', 'mitra queue displays the current selection status');
expectNotContains($selectionApplicationsHtml, 'value="seleksi"', 'mitra queue hides a no-op repeat selection action');
expectContains($selectionApplicationsHtml, 'value="diterima"', 'mitra can still accept after the selection stage');
$errorApplicationsHtml = renderComponent(
    __DIR__ . '/../components/dashboard/mitra/pengajuan/ApplicationQueue.php',
    ['items' => [], 'error' => 'Antrean belum dapat dimuat.']
);
expectNotContains($errorApplicationsHtml, 'Tidak ada pengajuan yang menunggu respons.', 'mitra queue does not confuse load failure with empty data');

$coordinatorHtml = renderComponent(
    __DIR__ . '/../components/dashboard/koordinatorMagang/dashboard/MainContent.php',
    ['dashboard' => [
        'statistics' => ['total_pengajuan' => 1, 'total_mahasiswa' => 1, 'pengajuan_aktif' => 1, 'tanpa_pembimbing' => 1],
        'recent' => [[
            'nama_mahasiswa' => 'Koordinator Fixture', 'nim' => 'K-1', 'program_studi' => 'TI',
            'nama_formasi' => 'Magang Uji', 'nama_perusahaan' => 'Perusahaan Uji',
            'status_pendaftaran' => 'diterima', 'status_penempatan' => 'persiapan',
            'nama_dosen_pembimbing' => null, 'total_minggu' => 0, 'validasi_menunggu' => 0,
        ]],
    ]]
);
expectContains($coordinatorHtml, 'Koordinator Fixture', 'coordinator dashboard renders database fixture');
expectContains($coordinatorHtml, 'Tanpa Pembimbing', 'coordinator dashboard displays real summary card');
expectNotContains($coordinatorHtml, 'PT. Semarsoft Technology Indonesia', 'coordinator dashboard excludes sample student page data');

$tendikHtml = renderComponent(
    __DIR__ . '/../components/dashboard/tendik/dashboard/MainContent.php',
    ['items' => [[
        'id' => 11, 'mahasiswa_nama' => 'Tendik Fixture', 'nim' => 'T-11',
        'nama_perusahaan' => 'Mitra Fixture', 'minggu_ke' => 1, 'validasi_menunggu' => 2,
    ]]]
);
expectContains($tendikHtml, 'Tendik Fixture', 'tendik dashboard renders validation queue');
expectContains($tendikHtml, 'logbook/detail/11', 'tendik dashboard links to authorized week detail');

$mitraHtml = renderComponent(
    __DIR__ . '/../components/dashboard/mitra/dashboard/MainContent.php',
    [
        'dashboard' => [
            'company' => ['nama_perusahaan' => 'Mitra Fixture'],
            'statistics' => ['formasi' => 1, 'pelamar' => 1, 'penempatan' => 1, 'menunggu_logbook' => 1],
            'students' => [[
                'penempatan_id' => 4, 'status' => 'berlangsung', 'mahasiswa_nama' => 'Mitra Applicant',
                'nim' => 'M-4', 'judul' => 'Developer',
            ]],
        ],
        'items' => [[
            'id' => 12, 'mahasiswa_nama' => 'Mitra Applicant', 'nim' => 'M-4',
            'nama_perusahaan' => 'Mitra Fixture', 'minggu_ke' => 3,
        ]],
    ]
);
expectContains($mitraHtml, 'Mitra Fixture', 'mitra dashboard renders company-scoped data');
expectContains($mitraHtml, 'logbook/detail/12', 'mitra dashboard links to review detail');

$assessmentHtml = renderComponent(
    __DIR__ . '/../components/dashboard/penilaian/MainContent.php',
    ['items' => [[
        'penempatan_id' => 14, 'mahasiswa_nama' => 'Assessment Fixture', 'nim' => 'P-14',
        'nama_perusahaan' => 'Company Fixture', 'judul' => 'QA Intern',
        'status_penilaian' => null, 'status_penempatan' => 'menunggu_penilaian',
        'tanggal_mulai' => '2026-01-01', 'tanggal_selesai' => '2026-02-01',
    ]], 'success' => false, 'error' => null]
);
expectContains($assessmentHtml, 'Assessment Fixture', 'assessment view renders authorized placement');
expectContains($assessmentHtml, 'name="nilai_kedisiplinan"', 'assessment view renders rubric inputs');
expectContains($assessmentHtml, 'tidak menghitung bobot', 'assessment view avoids an invented scoring formula');
expectContains($assessmentHtml, 'name="_csrf_token"', 'assessment submit form contains CSRF token');

$notificationsHtml = renderComponent(
    __DIR__ . '/../components/dashboard/shared/Notifications.php',
    [
        'items' => [[
            'id' => 31,
            'judul' => 'Status <script>alert(1)</script>',
            'pesan' => 'Pengajuan Anda disetujui. <img src=x onerror=alert(1)>',
            'tautan' => '/dashboard/mahasiswa/pengajuan/detail/9',
            'sudah_dibaca' => false,
            'created_at' => '2026-10-19 09:00:00',
        ], [
            'id' => 32,
            'judul' => 'Target eksternal ditolak',
            'pesan' => 'Fixture URL external.',
            'tautan' => '//evil.example/path',
            'sudah_dibaca' => true,
            'created_at' => '2026-10-19 08:00:00',
        ]],
        'unreadCount' => 1,
        'error' => null,
    ]
);
expectContains($notificationsHtml, 'Status &lt;script&gt;alert(1)&lt;/script&gt;', 'notification list escapes untrusted heading');
expectContains($notificationsHtml, 'pengajuan/detail/9', 'notification list renders internal destination');
expectContains($notificationsHtml, 'name="_csrf_token"', 'notification mutation form includes CSRF');
expectContains($notificationsHtml, 'name="notification_id" value="31"', 'notification action targets record id');
expectNotContains($notificationsHtml, '<script>', 'notification list never renders executable markup');
expectContains($notificationsHtml, '&lt;img src=x onerror=alert(1)&gt;', 'notification list escapes injected image markup');
expectNotContains($notificationsHtml, '<img', 'notification list never renders injected image element');
expectNotContains($notificationsHtml, 'evil.example', 'notification list suppresses external destinations');

$notificationBellHtml = renderComponent(
    __DIR__ . '/../components/dashboard/shared/NotificationBell.php',
    ['unreadCount' => 4]
);
expectContains($notificationBellHtml, '/dashboard/notifikasi', 'notification bell navigates to inbox');
expectContains($notificationBellHtml, '>4</span>', 'notification bell displays unread count');
expectContains($notificationBellHtml, 'aria-label="Notifikasi, 4 belum dibaca"', 'notification bell has accessible unread label');
$emptyNotificationsHtml = renderComponent(
    __DIR__ . '/../components/dashboard/shared/Notifications.php',
    ['items' => [], 'unreadCount' => 0, 'error' => null]
);
expectContains($emptyNotificationsHtml, 'Belum ada notifikasi', 'notification inbox renders an empty state');
expectNotContains($emptyNotificationsHtml, 'mark_all_read', 'empty notification inbox hides bulk action');

$reportHistoryHtml = renderComponent(
    __DIR__ . '/../components/dashboard/mahasiswa/laporan/RevisionHistory.php',
    ['revisi' => [[
        'id' => 55,
        'nomor_versi' => 2,
        'diunggah_pada' => '2026-10-19 09:30:00',
        'ringkasan' => 'Perbaikan <script>bab 2</script>',
    ]]]
);
expectContains($reportHistoryHtml, 'revisi/55/download', 'report version history links to its download handler');
expectContains($reportHistoryHtml, 'Perbaikan &lt;script&gt;bab 2&lt;/script&gt;', 'report version history escapes user text');
expectContains($reportHistoryHtml, 'Versi 2', 'report version history shows version number');

echo "Repository view smoke checks passed (38 components).\n";
