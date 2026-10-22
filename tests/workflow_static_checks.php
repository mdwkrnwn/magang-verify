<?php

declare(strict_types=1);

$model = file_get_contents(__DIR__ . '/../models/Logbook.php');
$fileController = file_get_contents(__DIR__ . '/../controllers/dashboard/LogbookFileController.php');
$router = file_get_contents(__DIR__ . '/../router.php');
$apacheStorageRules = file_get_contents(__DIR__ . '/../storage/.htaccess');
$reportController = file_get_contents(__DIR__ . '/../controllers/dashboard/mahasiswa/LaporanController.php');
$studentLogbookController = file_get_contents(__DIR__ . '/../controllers/dashboard/mahasiswa/LogbookController.php');
$coordinatorLogbookController = file_get_contents(__DIR__ . '/../controllers/dashboard/koordinatorMagang/LogbookController.php');
$studentDailyLogbookForm = file_get_contents(__DIR__ . '/../pages/dashboard/mahasiswa/logbook/daily-form.php');
$reportDetailPage = file_get_contents(__DIR__ . '/../pages/dashboard/mahasiswa/laporan/detail.php');
$weeklyReportForm = file_get_contents(__DIR__ . '/../pages/dashboard/mahasiswa/laporan/form.php');
$weeklyActivitiesComponent = file_get_contents(__DIR__ . '/../components/dashboard/mahasiswa/laporan/WeeklyActivities.php');
$logbookDetailPage = file_get_contents(__DIR__ . '/../pages/dashboard/mahasiswa/logbook/detail.php');
$dailyValidationStatus = file_get_contents(__DIR__ . '/../components/dashboard/mahasiswa/logbook/DailyValidationStatus.php');
$reportUploadPanel = file_get_contents(__DIR__ . '/../components/dashboard/mahasiswa/laporan/UploadPanel.php');
$reportRevisionModel = file_get_contents(__DIR__ . '/../models/LaporanRevisi.php');
$applicationController = file_get_contents(__DIR__ . '/../controllers/dashboard/mahasiswa/LamarController.php');
$applicationModel = file_get_contents(__DIR__ . '/../models/PendaftaranMagang.php');
$approvalModel = file_get_contents(__DIR__ . '/../models/PersetujuanPengajuan.php');
$schemaSnapshot = file_get_contents(__DIR__ . '/../database/schema.sql');
$finalDocumentMigration = file_get_contents(__DIR__ . '/../database/migrations/017_add_cv_to_profil_mahasiswa.sql');
$migration016 = file_get_contents(__DIR__ . '/../database/migrations/016_add_constraint_dokumen_pendaftaran_jenis_check.sql');
$migration017 = file_get_contents(__DIR__ . '/../database/migrations/017_add_cv_to_profil_mahasiswa.sql');
$migration025 = file_get_contents(__DIR__ . '/../database/migrations/025_verify_laporan_workflow.sql');
$publicProfileController = file_get_contents(__DIR__ . '/../controllers/MahasiswaProfilController.php');
$privateStorageHelper = file_get_contents(__DIR__ . '/../function/PrivateStorage.php');
$environmentConfig = file_get_contents(__DIR__ . '/../config/env.php');
$notificationModel = file_get_contents(__DIR__ . '/../models/Notifikasi.php');
$notificationController = file_get_contents(__DIR__ . '/../controllers/dashboard/NotifikasiController.php');
$notificationRoute = file_get_contents(__DIR__ . '/../routes/web.php');
$loginController = file_get_contents(__DIR__ . '/../controllers/LoginController.php');
$logoutController = file_get_contents(__DIR__ . '/../controllers/LogoutController.php');
$userModel = file_get_contents(__DIR__ . '/../models/User.php');
$loginAuthenticator = file_get_contents(__DIR__ . '/../models/LoginAuthenticator.php');
$authMiddleware = file_get_contents(__DIR__ . '/../middleware/AuthMiddleware.php');
$loginScript = file_get_contents(__DIR__ . '/../components/login/Script.php');
$testAccountSeeder = file_get_contents(__DIR__ . '/../database/seeders/SeedTestUsers.php');
$lecturerMonitoringController = file_get_contents(__DIR__ . '/../controllers/dashboard/dosen/DosenMonitoringController.php');
$lecturerDashboardModel = file_get_contents(__DIR__ . '/../models/DashboardDosen.php');
$assessmentController = file_get_contents(__DIR__ . '/../controllers/dashboard/PenilaianController.php');
$assessmentModel = file_get_contents(__DIR__ . '/../models/PenilaianMagang.php');
$typedPdoBinder = file_get_contents(__DIR__ . '/../models/PdoParameterBinder.php');
$mitraApplicationModel = file_get_contents(__DIR__ . '/../models/PengajuanMitra.php');
$mitraApplicationController = file_get_contents(__DIR__ . '/../controllers/dashboard/mitra/PengajuanController.php');
$mitraApplicationView = file_get_contents(__DIR__ . '/../components/dashboard/mitra/pengajuan/ApplicationQueue.php');
$lecturerApplicationController = file_get_contents(__DIR__ . '/../controllers/dashboard/dosen/PengajuanMagangController.php');
$lecturerApplicationView = file_get_contents(__DIR__ . '/../components/dashboard/dosen/pengajuanmagang/SubmissionList.php');
$lecturerApplicationModel = file_get_contents(__DIR__ . '/../models/PersetujuanPengajuan.php');
$lecturerDecisionHelper = file_get_contents(__DIR__ . '/../models/PengajuanDecision.php');
$studentPortfolioController = file_get_contents(__DIR__ . '/../controllers/dashboard/mahasiswa/PortofolioController.php');
$studentProfileController = file_get_contents(__DIR__ . '/../controllers/dashboard/mahasiswa/ProfilController.php');
$studentCertificateController = file_get_contents(__DIR__ . '/../controllers/dashboard/mahasiswa/SertifikatController.php');
$studentSettingsController = file_get_contents(__DIR__ . '/../controllers/dashboard/mahasiswa/PengaturanController.php');
$studentFormasiModel = file_get_contents(__DIR__ . '/../models/FormasiMagang.php');
$studentFormasiHeader = file_get_contents(__DIR__ . '/../components/dashboard/mahasiswa/formasi-magang/detail/Header.php');
$applicationAction = file_get_contents(__DIR__ . '/../components/dashboard/mahasiswa/lamar/Action.php');
$applicationMainContent = file_get_contents(__DIR__ . '/../components/dashboard/mahasiswa/lamar/MainContent.php');
$cvApacheRules = file_get_contents(__DIR__ . '/../uploads/cv/.htaccess');
$environmentHelper = __DIR__ . '/../function/Environment.php';
$documentTypeHelper = __DIR__ . '/../function/PendaftaranDocuments.php';
if (!is_file($environmentHelper)) {
    throw new RuntimeException('Environment helper must be present for isolated configuration tests.');
}
if (!is_file($documentTypeHelper)) {
    throw new RuntimeException('Document type helper must exist for database-kind regression checks.');
}
require_once __DIR__ . '/../function/PrivateStorage.php';
require_once $environmentHelper;
require_once $documentTypeHelper;

if ($model === false || $fileController === false || $router === false || $apacheStorageRules === false || $reportController === false || $studentLogbookController === false || $coordinatorLogbookController === false || $studentDailyLogbookForm === false || $reportDetailPage === false || $weeklyReportForm === false || $weeklyActivitiesComponent === false || $logbookDetailPage === false || $dailyValidationStatus === false || $reportUploadPanel === false || $reportRevisionModel === false || $applicationController === false || $applicationModel === false || $approvalModel === false || $mitraApplicationModel === false || $mitraApplicationController === false || $mitraApplicationView === false || $lecturerApplicationController === false || $lecturerApplicationView === false || $lecturerMonitoringController === false || $lecturerDashboardModel === false || $assessmentController === false || $studentPortfolioController === false || $studentProfileController === false || $studentCertificateController === false || $studentSettingsController === false || $studentFormasiModel === false || $studentFormasiHeader === false || $applicationAction === false || $applicationMainContent === false || $loginController === false || $logoutController === false || $userModel === false || $authMiddleware === false || $loginScript === false || $testAccountSeeder === false || $publicProfileController === false || $privateStorageHelper === false || $environmentConfig === false || $notificationModel === false || $notificationController === false || $notificationRoute === false || $cvApacheRules === false || $schemaSnapshot === false || $finalDocumentMigration === false || $migration016 === false || $migration017 === false || $migration025 === false) {
    throw new RuntimeException('Unable to read source files for static workflow checks.');
}

function assertSourceContains(string $source, string $needle, string $assertion): void
{
    if (!str_contains($source, $needle)) {
        throw new RuntimeException($assertion . ' failed: expected source fragment not found.');
    }
}

function assertSourceNotContains(string $source, string $needle, string $assertion): void
{
    if (str_contains($source, $needle)) {
        throw new RuntimeException($assertion . ' failed: forbidden source fragment found.');
    }
}

$validationMethod = substr(
    $model,
    strpos($model, 'public function validateDaily('),
    strpos($model, 'public function getReviewQueue(') - strpos($model, 'public function validateDaily(')
);
assertSourceContains($validationMethod, "AND status_validasi = \\'menunggu\\'", 'validation is conditional on pending state');
assertSourceContains($validationMethod, '$stmt->rowCount() !== 1', 'validation checks affected row count');
assertSourceContains($validationMethod, '$pdo->rollBack();', 'failed validation rolls back transaction');
assertSourceContains($validationMethod, '$this->insertRevision(', 'revision is created only in the transaction after conditional update');
assertSourceContains($validationMethod, '$activityId < 1', 'failed activity insert aborts validation');
assertSourceContains($validationMethod, "'Validasi logbook harian'", 'successful validation is audited');
$activityLogPosition = strpos($validationMethod, "'Validasi logbook harian'");
$commitPosition = strpos($validationMethod, '$pdo->commit();');
if ($activityLogPosition === false || $commitPosition === false || $activityLogPosition > $commitPosition) {
    throw new RuntimeException('Validation activity must be written before transaction commit.');
}

$signatureMethod = substr(
    $model,
    strpos($model, 'public function getSignatureForUser('),
    strpos($model, 'public function getEvidenceForUser(') - strpos($model, 'public function getSignatureForUser(')
);
assertSourceContains($signatureMethod, "'tendik', 'koordinator_magang' => false", 'validators cannot download unrelated signatures');
assertSourceContains($signatureMethod, "\$row['tahap'] !== 'dosen'", 'partner cannot download lecturer signature');

$evidenceMethod = substr(
    $model,
    strpos($model, 'public function getEvidenceForUser('),
    strpos($model, 'public function getEvidenceAbsolutePath(') - strpos($model, 'public function getEvidenceForUser(')
);
assertSourceContains($evidenceMethod, "'tendik', 'koordinator_magang' =>", 'validator evidence access is explicitly scoped');
assertSourceContains($evidenceMethod, "\$row['status_validasi'] === 'menunggu'", 'validators can access evidence only while pending');
assertSourceContains($fileController, 'getEvidenceForUser($id, $userId, $role)', 'file endpoint delegates evidence authorization');
assertSourceContains($fileController, 'AuthMiddleware::handle($role)', 'private logbook file endpoint refreshes active role authorization');
assertSourceContains($fileController, "'Cache-Control: private, no-store", 'private file response disables caching');
assertSourceContains($router, 'isPrivateStorageRequestPath((string) $path)', 'router checks canonical private storage path');
assertSourceContains($apacheStorageRules, 'Require all denied', 'Apache denies direct requests to storage');
assertSourceNotContains($router, 'readfile($path)', 'router does not serve arbitrary private paths directly');

$roleWeekMethod = substr(
    $model,
    strpos($model, 'public function getWeekForRole('),
    strpos($model, 'public function getDailyEntries(') - strpos($model, 'public function getWeekForRole(')
);
assertSourceContains($roleWeekMethod, "lm.status IN ('menunggu_mitra', 'menunggu_dosen', 'disetujui')", 'partner detail follows student-sign gate');
assertSourceContains($roleWeekMethod, "lm.status IN ('menunggu_dosen', 'disetujui')", 'lecturer detail follows partner-sign gate');
assertSourceContains($roleWeekMethod, 'pending_day.status_validasi = \'menunggu\'', 'validator detail is limited to pending absence records');

$reviewQueueMethod = substr(
    $model,
    strpos($model, 'public function getReviewQueue('),
    strpos($model, 'public function getLecturerStatistics(') - strpos($model, 'public function getReviewQueue(')
);
assertSourceContains($reviewQueueMethod, '$params = [];', 'review queue starts with only parameters required by selected role');
assertSourceContains($reviewQueueMethod, '$params[\'user_id\'] = $userId', 'partner/lecturer review queue scopes parameter to their owner predicate');
assertSourceContains($reviewQueueMethod, '$stmt->execute($params);', 'review queue executes with role-specific bound parameters');
assertSourceNotContains($reviewQueueMethod, '$stmt->execute([\'user_id\' => $userId]);', 'validator queue does not bind a placeholder absent from its SQL condition');
assertSourceContains($reviewQueueMethod, 'in_array($role, self::VALIDATORS, true)', 'validator queue branch remains role-gated');
assertSourceContains($coordinatorLogbookController, "AuthMiddleware::handle('koordinator_magang')", 'coordinator review queue requires coordinator role');
assertSourceContains($coordinatorLogbookController, 'getReviewQueue($userId, \'koordinator_magang\')', 'coordinator forwards authenticated user and role to review queue');

assertSourceContains($reportController, 'resolvePrivateStorageFile(', 'report controller resolves downloads using private storage helper');
assertSourceContains($reportController, "'ringkasan' => trim((string) (\$_POST['ringkasan'] ?? ''))", 'report upload controller retains submitted summary on failure');
assertSourceContains($reportController, 'Ringkasan dan catatan revisi maksimal 5.000 karakter.', 'report upload controller validates submitted text length server-side');
assertSourceContains($reportController, '$this->logbookModel->getDailyEntries(', 'weekly report preview reads activities from selected logbook');
assertSourceContains($weeklyReportForm, "include __DIR__ . '/../../../../components/dashboard/mahasiswa/laporan/WeeklyActivities.php'", 'weekly report form includes its activity source component');
assertSourceContains($weeklyActivitiesComponent, 'status_validasi', 'weekly activity preview reflects source logbook validation state');
assertSourceContains($reportUploadPanel, 'uploadFormValues[\'ringkasan\']', 'report upload form repopulates valid text after failure');
assertSourceContains($reportUploadPanel, 'name="_csrf_token"', 'report upload form retains CSRF protection');
assertSourceContains($reportDetailPage, '$reviewStatusLabels', 'report review results are rendered with user-facing status labels');
assertSourceContains($reportDetailPage, 'Urutan dan keputusan berikutnya mengikuti proses yang ditetapkan pengelola.', 'report detail does not invent reviewer ordering');
assertSourceContains($logbookDetailPage, "\$completeness['rejected_validation']", 'logbook detail explains rejected absence validation');
assertSourceContains($dailyValidationStatus, 'catatan_validasi', 'daily row exposes validator notes safely');
assertSourceContains($dailyValidationStatus, 'file/evidence/', 'daily evidence uses authorized file endpoint');
assertSourceContains($model, "COUNT(*) FILTER (WHERE status_validasi = \\'ditolak\\') AS validasi_ditolak", 'logbook week list counts rejected validations separately');
assertSourceContains($reportController, "'catatan_revisi' => trim((string) (\$_POST['catatan_revisi'] ?? ''))", 'report upload controller retains revision note on failure');
assertSourceContains($reportController, 'mb_strlen(', 'report upload controller validates optional text bounds');
assertSourceContains($reportController, "catch (PDOException \$e)", 'report database failures are caught separately from user validation');
assertSourceContains($reportController, 'error_log(\'Gagal mengunggah laporan:', 'report database error details go to server log');
assertSourceContains($studentLogbookController, 'private function safeError(', 'student logbook controller has a safe error adapter');
assertSourceContains($studentLogbookController, 'if ($exception instanceof PDOException)', 'logbook adapter handles database exceptions before general runtime exceptions');
assertSourceContains($studentLogbookController, 'Kesalahan internal workflow logbook mahasiswa:', 'logbook internals are logged rather than echoed');
assertSourceContains($studentLogbookController, 'Aktivitas harian belum dapat disimpan. Silakan coba lagi.', 'daily logbook persistence failures use a safe user message');
assertSourceContains($studentDailyLogbookForm, 'reason.required = absent', 'absence reason frontend requirement follows attendance selection');
assertSourceContains($studentDailyLogbookForm, 'name="_csrf_token"', 'daily logbook mutation form contains CSRF');
assertSourceContains($weeklyActivitiesComponent, 'status_validasi', 'weekly report preview reflects source logbook validation state');
assertSourceContains($reportUploadPanel, 'Mengunggah...', 'report upload UI has a pending submit state');
assertSourceContains($reportController, 'getByIdForOwner(', 'report revision download uses owner-scoped lookup');
assertSourceContains($reportRevisionModel, 'public function getByIdForOwner(', 'report revision model provides owner-scoped lookup');
assertSourceContains($reportRevisionModel, 'AND prof.user_id = :user_id', 'report revision lookup scopes through student ownership');
assertSourceContains($notificationRoute, 'laporan/revisi/', 'report revision download route includes revision path');
assertSourceContains($notificationRoute, "'method' => 'downloadRevision'", 'report revision route maps to download handler');
assertSourceContains($applicationController, 'is_uploaded_file($tmp)', 'application documents must originate from upload handler');
assertSourceContains($applicationController, '$signature !== ' . "'%PDF-'", 'application PDF upload checks file signature');
assertSourceContains($schemaSnapshot, "CHECK (jenis_dokumen IN ('pakta_integritas', 'daftar_riwayat_hidup', 'khs', 'ktp', 'ktm', 'surat_izin_orang_tua', 'bpjs_asuransi', 'sktm_kip', 'proposal_magang', 'sertifikat_kompetensi'))", 'schema snapshot document kinds match final migration');
assertSourceContains($finalDocumentMigration, "'bpjs_asuransi',", 'final document migration uses BPJS database kind');
assertSourceContains($finalDocumentMigration, "'proposal_magang',", 'final document migration uses proposal database kind');
assertSourceContains($applicationController, 'applicationDocumentKind($field)', 'application maps upload fields through tested database-kind helper');
assertSourceContains($applicationController, '$existingApplication = $hasActiveApplication || !$formasiTersedia', 'unavailable formations disable application submission');
assertSourceContains($applicationController, 'Formasi ini sudah tidak menerima pendaftaran.', 'stale application link gives clear unavailable-form feedback');
assertSourceContains($applicationMainContent, "include __DIR__ . '/Alur.php'", 'application page includes the documented workflow timeline');
assertSourceContains($applicationModel, "f.status='dibuka'", 'application insert rechecks open status on the server');
assertSourceContains($applicationModel, 'FOR UPDATE OF f', 'application creation serializes against formation quota changes');
assertSourceContains($applicationModel, '$this->hasActiveApplication($mahasiswaId)', 'application creation rechecks active applications inside its transaction');
assertSourceContains($publicProfileController, "['uploads/cv']", 'public CV handler constrains stored path to CV directory');
assertSourceContains($publicProfileController, 'file_get_contents($filePath', 'public CV handler checks PDF content signature');
assertSourceContains($cvApacheRules, 'Require all denied', 'Apache denies direct CV file requests');
if (
    applicationDocumentKind('bpjs') !== 'bpjs_asuransi'
    || applicationDocumentKind('proposal') !== 'proposal_magang'
    || applicationDocumentKind('ktp') !== 'ktp'
) {
    throw new RuntimeException('Application document input fields must map to schema kinds without changing required kinds.');
}
assertSourceContains($privateStorageHelper, "'..'", 'private file resolver rejects traversal segments');
assertSourceContains($privateStorageHelper, 'str_starts_with($file, $allowedRoot . DIRECTORY_SEPARATOR)', 'private file resolver checks canonical containment');
assertSourceContains($environmentConfig, 'loadEnvironmentFileIfRequired(', 'environment loader supports isolated process injection');
assertSourceContains($environmentConfig, "['DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER', 'DB_PASSWORD']", 'all database connection parameters are required');

assertSourceContains($notificationRoute, "'/dashboard/notifikasi' =>", 'notification inbox route is registered');
assertSourceContains($notificationController, 'AuthMiddleware::handle($role)', 'notification page authenticates its active role');
assertSourceContains($notificationController, 'verifyCsrfToken()', 'notification mutations require CSRF');
if (substr_count($notificationRoute, "'/dashboard/notifikasi' =>") !== 1) {
    throw new RuntimeException('Notification route must be registered exactly once.');
}
assertSourceContains($notificationModel, 'WHERE id = :id AND user_id = :user_id AND sudah_dibaca = FALSE', 'mark-read mutation is owner-scoped and one-way');
assertSourceContains($notificationModel, 'WHERE user_id = :user_id AND sudah_dibaca = FALSE', 'mark-all mutation is owner-scoped');
assertSourceContains($notificationModel, 'LIMIT :limit', 'notification listing is bounded');

assertSourceContains($mitraApplicationModel, "AND status_pendaftaran IN ('menunggu_respons_mitra', 'seleksi')", 'mitra response update is restricted to allowed source states');
assertSourceContains($mitraApplicationModel, "if (\$decision === 'seleksi' && \$application['status_pendaftaran'] === 'seleksi')", 'repeated transition to selection is rejected at the model boundary');
assertSourceContains($mitraApplicationModel, '$update->rowCount() !== 1', 'mitra response update rejects repeats');
assertSourceContains($mitraApplicationController, 'verifyCsrfToken()', 'mitra application mutations require CSRF');
assertSourceContains($mitraApplicationController, '$formValues[(int) $id] = $note', 'mitra response note is retained after validation failure');
assertSourceContains($mitraApplicationView, 'name="_csrf_token"', 'mitra application form includes CSRF');
assertSourceContains($mitraApplicationView, 'application_id', 'mitra application form sends application identifier');
assertSourceContains($mitraApplicationView, 'name="decision" value=""', 'mitra form submits selected documented decision');
assertSourceContains($lecturerApplicationController, 'verifyCsrfToken()', 'lecturer decisions require CSRF');
assertSourceContains($lecturerApplicationController, '$formValues[(int) $id] = $note', 'lecturer decision note is retained on errors');
assertSourceContains($lecturerApplicationView, 'name="_csrf_token"', 'lecturer application form includes CSRF');
assertSourceContains($lecturerApplicationView, 'name="application_id"', 'lecturer application form sends application identifier');
assertSourceContains($approvalModel, 'COUNT(DISTINCT jenis_dokumen)', 'dosen approval backend checks distinct required documents');
foreach (['pakta_integritas', 'daftar_riwayat_hidup', 'khs', 'ktp', 'ktm', 'surat_izin_orang_tua'] as $requiredApplicationDocument) {
    assertSourceContains($approvalModel, "'{$requiredApplicationDocument}'", 'dosen approval backend includes required document ' . $requiredApplicationDocument);
    assertSourceContains($lecturerApplicationView, "'{$requiredApplicationDocument}'", 'dosen review UI tracks required document ' . $requiredApplicationDocument);
}
assertSourceContains($studentPortfolioController, "AuthMiddleware::handle('mahasiswa')", 'portfolio controller restricts every action to student role');
assertSourceContains($studentPortfolioController, 'is_uploaded_file($tmpName)', 'portfolio images must originate from PHP uploads');
assertSourceContains($studentPortfolioController, '@getimagesize($tmpName)', 'portfolio image must decode as an image');
assertSourceContains($studentProfileController, "AuthMiddleware::handle('mahasiswa')", 'student profile controller checks role');
assertSourceContains($studentProfileController, 'is_uploaded_file(', 'profile uploads must originate from PHP uploads');
assertSourceContains($studentProfileController, "resolvePrivateStorageFile(\$projectRoot, \$oldPath, ['uploads/profil'])", 'old profile photo deletion is constrained to its canonical upload directory');
assertSourceContains($studentProfileController, "resolvePrivateStorageFile(\$projectRoot, \$oldPath, ['uploads/cv'])", 'old CV deletion is constrained to its canonical upload directory');
assertSourceContains($studentCertificateController, "AuthMiddleware::handle('mahasiswa')", 'certificate controller restricts reads and mutations to student role');
assertSourceContains($studentCertificateController, 'getSertifikatBySlug($slug, $userId)', 'certificate document operations are owner-scoped');
assertSourceContains($studentSettingsController, "AuthMiddleware::handle('mahasiswa')", 'student settings controller restricts password changes to student role');
assertSourceContains($studentFormasiModel, '$statusFormasi === \'dibuka\'', 'available student formasi must also be open');
assertSourceContains($studentFormasiModel, "\$formasi['status_formasi'] === 'draft'", 'student formasi details do not disclose unpublished drafts');
assertSourceContains($studentFormasiHeader, "'ditutup' => 'Pendaftaran Ditutup'", 'closed formasi receives an accurate status label');
assertSourceContains($studentFormasiHeader, "&& (\$formasi['status_formasi'] ?? '') === 'dibuka'", 'student apply link requires the database form status to be open');
assertSourceContains($studentFormasiHeader, 'if ($isTersedia)', 'application action is conditional on availability');
assertSourceContains($applicationController, 'http_response_code(409);', 'unavailable application form returns conflict status');
assertSourceContains($applicationAction, "<?= \$existingApplication ? 'disabled' : '' ?>", 'server-detected application blockers disable form submission');
assertSourceContains($notificationRoute, "'/login' => [", 'login has a route');
assertSourceContains($notificationRoute, "'/logout' => [", 'logout has a route');
$mitraSeederHashVerified = false;
if (preg_match_all("/\\[\\s*'login_id'\\s*=>[\\s\\S]*?\\n\\s*\\],/", $testAccountSeeder, $accountBlocks)) {
    foreach ($accountBlocks[0] as $accountBlock) {
        if (str_contains($accountBlock, "'role' => 'mitra'")) {
            $mitraSeederHashVerified = str_contains($accountBlock, "'password' => password_hash(");
            break;
        }
    }
}
if (!$mitraSeederHashVerified) {
    throw new RuntimeException('Synthetic Mitra account seeding must store a password hash.');
}
assertSourceContains($loginAuthenticator, 'password_verify($password, $hash)', 'non-Mitra roles still verify password hashes');
assertSourceContains($loginController, 'session_regenerate_id(true)', 'successful login regenerates session id');
assertSourceContains($loginController, "'mitra'              => '/dashboard/mitra'", 'mitra login redirects to mitra dashboard');
assertSourceContains($loginController, 'verifyCsrfToken()', 'login POST requires CSRF');
assertSourceContains($userModel, 'FROM users', 'authentication reads identity from users table');
assertSourceContains($userModel, 'WHERE login_id = :login_id', 'authentication looks up account by login id');
assertSourceContains($loginController, "\$role === 'mitra'", 'login explicitly branches into Mitra authentication');
assertSourceContains($loginController, 'findActiveMitraByCode($loginId)', 'Mitra login resolves the company code');
assertSourceContains($loginController, 'findByLoginId($loginId)', 'other roles retain the users.login_id lookup');
assertSourceContains($loginAuthenticator, "if (\$requestedRole === 'mitra')", 'Mitra authentication is distinct from password authentication');
assertSourceContains($loginAuthenticator, 'password_verify($password, $hash)', 'non-Mitra roles still verify password hashes');
assertSourceContains($userModel, 'm.kode_perusahaan = :kode_perusahaan', 'official Mitra company code maps to its profile');
assertSourceContains($userModel, 'INNER JOIN users u ON u.id = m.user_id', 'Mitra code resolves only through its linked user');
assertSourceContains($userModel, "u.role = 'mitra'", 'Mitra code lookup cannot resolve a different user role');
assertSourceContains($userModel, 'u.is_active = TRUE', 'Mitra lookup requires active user account');
assertSourceContains($userModel, 'm.is_active = TRUE', 'Mitra lookup requires active company profile');
assertSourceContains($logoutController, 'session_destroy()', 'logout destroys server session');
assertSourceContains($logoutController, 'verifyCsrfToken()', 'logout requires CSRF');
assertSourceContains($authMiddleware, "\$user['role'] !== \$requiredRole", 'dashboard middleware enforces stored database role');
assertSourceContains($loginScript, 'passwordSection.hidden = isMitra', 'role selection hides password only for Mitra');
assertSourceContains($loginScript, 'passwordInput.required = !isMitra', 'password requirement follows the selected role');
assertSourceContains($loginScript, 'passwordInput.disabled = isMitra', 'hidden Mitra password is excluded from submission');
assertSourceNotContains($loginScript, 'config.password', 'login script does not inject preset passwords');
assertSourceNotContains($loginScript, 'config.loginId', 'login script does not inject preset account identifiers');
assertSourceContains($lecturerMonitoringController, "AuthMiddleware::handle('dosen')", 'lecturer monitoring list/detail requires lecturer role');
assertSourceContains($lecturerDashboardModel, 'WHERE pd.user_id = :user_id AND pm.id = :placement_id', 'lecturer monitoring detail is scoped to assigned placement owner');
assertSourceContains($notificationRoute, "'/dashboard/dosen/monitoring' => [", 'lecturer monitoring page has a static route');
assertSourceContains($notificationRoute, "'controller' => DosenMonitoringController::class", 'lecturer monitoring route dispatches its monitoring controller');
assertSourceContains($notificationRoute, "'/dashboard/dosen/penilaian' => [", 'lecturer assessment action has a static route');
assertSourceContains($notificationRoute, 'monitoring/detail/', 'lecturer placement detail has a dynamic route');
assertSourceContains($assessmentController, "'/dashboard/dosen/penilaian'", 'lecturer assessment save returns to the assessment page');
assertSourceContains($notificationRoute, "'/dashboard/dosen/pengajuan' => [", 'Dosen decision form posts to its registered application route');
assertSourceContains($lecturerApplicationController, "AuthMiddleware::handle('dosen')", 'Dosen decision route enforces lecturer role');
assertSourceContains($lecturerApplicationController, 'verifyCsrfToken()', 'Dosen decision POST requires CSRF');
assertSourceContains($lecturerApplicationController, "\$decision === 'ditolak' && \$note === ''", 'Dosen rejection without a trimmed note is rejected server-side');
assertSourceContains($lecturerApplicationModel, "\$application['status_pendaftaran'] !== 'menunggu_persetujuan_dosen'", 'Dosen decision preserves pending workflow state guard');
assertSourceContains($lecturerApplicationModel, "\$application['status_persetujuan_dosen'] !== 'menunggu'", 'Dosen decision prevents duplicate or stale decisions');
assertSourceContains($lecturerApplicationModel, "if ((int) \$documents->fetchColumn() !== 6)", 'approval continues to require all six mandatory documents');
assertSourceContains($lecturerApplicationModel, 'PengajuanDecision::bindUpdate(', 'Dosen update uses the typed parameter binder');
assertSourceNotContains($lecturerApplicationModel, '$update->execute([', 'Dosen update does not pass loosely typed execute parameters');
assertSourceContains($lecturerDecisionHelper, "PDO::PARAM_BOOL", 'terminal flag is explicitly bound as a PostgreSQL-compatible boolean');
assertSourceContains($lecturerDecisionHelper, "PDO::PARAM_NULL", 'empty approval note binds SQL NULL');
assertSourceContains($lecturerApplicationView, "note.required=false", 'approval UI keeps the note optional');
assertSourceContains($lecturerApplicationView, "note.required=true", 'rejection UI marks the note required');
assertSourceContains($schemaSnapshot, 'catatan_dosen TEXT,', 'decision note remains nullable in the existing schema');
assertSourceContains($mitraApplicationModel, 'PdoParameterBinder::execute($update', 'Mitra response update binds parameters using their PHP types');
assertSourceContains($mitraApplicationModel, "'terminal' => \$decision !== 'seleksi'", 'Mitra terminal flag remains true only for final decisions');
assertSourceContains($assessmentModel, "'is_submitted' => \$submit", 'assessment timestamp flag remains aligned with draft/submit action');
if (substr_count($assessmentModel, 'PdoParameterBinder::execute($stmt, $params)') !== 2) {
    throw new RuntimeException('Both assessment insert and update paths must use typed parameter binding.');
}
assertSourceContains($typedPdoBinder, 'is_bool($value) => PDO::PARAM_BOOL', 'shared binder explicitly types true/false values');
assertSourceContains($typedPdoBinder, '$statement->execute()', 'shared binder executes after typed bindValue calls');
foreach (['mahasiswa', 'dosen', 'mitra', 'koordinatorMagang', 'tendik'] as $headerRole) {
    $header = file_get_contents(__DIR__ . '/../components/dashboard/' . $headerRole . '/Header.php');
    if ($header === false || !str_contains($header, "../shared/NotificationBell.php")) {
        throw new RuntimeException('A role header is missing the shared notification navigation.');
    }
    if ($header === false || !str_contains($header, 'if ($role === ' . "'mahasiswa')")) {
        throw new RuntimeException('A role header exposes the student-only settings route without a role gate.');
    }
}
$studentHeader = file_get_contents(__DIR__ . '/../components/dashboard/mahasiswa/Header.php');
if ($studentHeader === false || substr_count($studentHeader, "url('/dashboard/mahasiswa/pengaturan')") !== 1) {
    throw new RuntimeException('Student settings route should remain available in the student header.');
}

assertSourceNotContains($migration016, 'INSERT INTO public.schema_migrations', '016 relies on 017 to register its history entry');
assertSourceContains($migration017, "('016_add_constraint_dokumen_pendaftaran_jenis_check.sql')", '017 records the manually applied 016 migration');
if (preg_match('/^\\s*(?:CREATE\\s+(?:TABLE|INDEX|VIEW|TRIGGER)|ALTER\\s+TABLE|INSERT\\s+INTO)\\b/im', $migration025)) {
    throw new RuntimeException('The report workflow verification script must remain read-only.');
}

$migrationCreates = [];
foreach (glob(__DIR__ . '/../database/migrations/[0-9][0-9][0-9]_*.sql') ?: [] as $migrationFile) {
    $sql = file_get_contents($migrationFile);
    if ($sql === false) {
        throw new RuntimeException('Unable to inspect a numbered migration file.');
    }
    preg_match_all('/CREATE\\s+TABLE(?:\\s+IF\\s+NOT\\s+EXISTS)?\\s+(?:public\\.)?([a-z_][a-z0-9_]*)/i', $sql, $matches);
    foreach ($matches[1] as $table) {
        $migrationCreates[strtolower($table)][] = basename($migrationFile);
    }
}
$duplicateCreates = array_keys(array_filter($migrationCreates, static fn(array $files): bool => count($files) > 1));
sort($duplicateCreates);
if ($duplicateCreates !== ['formasi_magang', 'mitra']) {
    throw new RuntimeException('Numbered table-creation duplicates changed; audit migration baseline before applying.');
}

$scratchRoot = (string) getenv('TMPDIR');
if ($scratchRoot === '' || !is_dir($scratchRoot)) {
    throw new RuntimeException('TMPDIR must point to an isolated scratch directory for local security tests.');
}
$envKeys = ['MAGANGVERIFY_TEST_DB_HOST', 'MAGANGVERIFY_TEST_DB_PORT'];
$previousEnv = array_map('getenv', $envKeys);
$previousSuperglobals = [];
foreach ($envKeys as $key) {
    $previousSuperglobals[$key] = array_key_exists($key, $_ENV) ? $_ENV[$key] : null;
}
$envFixture = rtrim($scratchRoot, DIRECTORY_SEPARATOR) . '/magangverify-env-' . bin2hex(random_bytes(8)) . '.ini';
try {
    putenv($envKeys[0] . '=127.0.0.1');
    putenv($envKeys[1] . '=');
    if (hasEnvironmentValues($envKeys)) {
        throw new RuntimeException('Environment completeness must reject an empty value.');
    }
    putenv($envKeys[1] . '=test-value-not-a-secret');
    if (!hasEnvironmentValues($envKeys)) {
        throw new RuntimeException('Environment completeness must accept explicit non-empty values.');
    }
    loadEnvironmentFileIfRequired($envKeys, $envFixture);

    putenv($envKeys[1] . '=0');
    if (hasEnvironmentValues($envKeys)) {
        throw new RuntimeException('Environment completeness must reject a value treated as false by PHP fallback logic.');
    }

    putenv($envKeys[1] . '=');
    $incompleteEnvironmentRejected = false;
    try {
        loadEnvironmentFileIfRequired($envKeys, $envFixture);
    } catch (RuntimeException) {
        $incompleteEnvironmentRejected = true;
    }
    if (!$incompleteEnvironmentRejected) {
        throw new RuntimeException('Incomplete environment must not silently continue without configuration.');
    }

    file_put_contents($envFixture, $envKeys[0] . "=from-file\n");
    $missingFileValueRejected = false;
    try {
        loadEnvironmentFileIfRequired($envKeys, $envFixture);
    } catch (RuntimeException) {
        $missingFileValueRejected = true;
    }
    if (!$missingFileValueRejected) {
        throw new RuntimeException('Environment loader must reject a file missing required database parameters.');
    }

    file_put_contents($envFixture, $envKeys[0] . "=from-file\n" . $envKeys[1] . "=5433\n");
    loadEnvironmentFileIfRequired($envKeys, $envFixture);
    if (getenv($envKeys[0]) !== '127.0.0.1' || getenv($envKeys[1]) !== '5433') {
        throw new RuntimeException('Explicit process environment must take precedence over environment-file values.');
    }
} finally {
    foreach ($envKeys as $index => $key) {
        if ($previousEnv[$index] === false) {
            putenv($key);
        } else {
            putenv($key . '=' . $previousEnv[$index]);
        }
        if ($previousSuperglobals[$key] === null) {
            unset($_ENV[$key]);
        } else {
            $_ENV[$key] = $previousSuperglobals[$key];
        }
    }
    @unlink($envFixture);
}

$projectRoot = dirname(__DIR__);
$validTemplatePath = 'storage/templates/laporan/Template_Laporan_Magang_Industri_Polinema.docx';
if (resolvePrivateStorageFile($projectRoot, $validTemplatePath, ['storage/laporan', 'storage/templates/laporan']) === null) {
    throw new RuntimeException('A valid owned report template path was rejected.');
}
foreach ([
    '../../../.htaccess',
    'storage/laporan/../../.htaccess',
    'storage/pendaftaran/README.txt',
    '/etc/passwd',
] as $invalidFilePath) {
    if (resolvePrivateStorageFile($projectRoot, $invalidFilePath, ['storage/laporan', 'storage/templates/laporan']) !== null) {
        throw new RuntimeException('Private file resolver accepted a path outside allowed storage.');
    }
}

$temporaryRoot = rtrim($scratchRoot, DIRECTORY_SEPARATOR) . '/magangverify-path-' . bin2hex(random_bytes(8));
$allowedDirectory = $temporaryRoot . '/storage/laporan';
$outsideDirectory = $temporaryRoot . '/outside';
$profileDirectory = $temporaryRoot . '/uploads/profil';
$cvDirectory = $temporaryRoot . '/uploads/cv';
if (!mkdir($allowedDirectory, 0700, true) || !mkdir($outsideDirectory, 0700, true) || !mkdir($profileDirectory, 0700, true) || !mkdir($cvDirectory, 0700, true)) {
    throw new RuntimeException('Unable to create isolated path test fixture.');
}
$safeFile = $allowedDirectory . '/safe.pdf';
$outsideFile = $outsideDirectory . '/outside.pdf';
$fileSymlink = $allowedDirectory . '/escape.pdf';
$directorySymlink = $temporaryRoot . '/storage/laporan-link';
try {
    file_put_contents($safeFile, 'fixture');
    file_put_contents($outsideFile, 'fixture');
    file_put_contents($profileDirectory . '/photo.jpg', 'fixture');
    file_put_contents($cvDirectory . '/resume.pdf', 'fixture');
    if (!symlink($outsideFile, $fileSymlink) || !symlink($outsideDirectory, $directorySymlink)) {
        throw new RuntimeException('Symlink creation is unavailable in the test scratch directory.');
    }
    if (resolvePrivateStorageFile($temporaryRoot, 'storage/laporan/safe.pdf', ['storage/laporan']) === null) {
        throw new RuntimeException('Resolver rejected a file inside the allowed canonical directory.');
    }
    if (resolvePrivateStorageFile($temporaryRoot, 'storage/laporan/escape.pdf', ['storage/laporan']) !== null) {
        throw new RuntimeException('Resolver accepted a file symlink escaping the allowed directory.');
    }
    if (resolvePrivateStorageFile($temporaryRoot, 'storage/laporan-link/outside.pdf', ['storage/laporan-link']) !== null) {
        throw new RuntimeException('Resolver accepted an allowed-directory symlink escaping the project root.');
    }
    if (resolvePrivateStorageFile($temporaryRoot, 'uploads/profil/photo.jpg', ['uploads/profil']) === null
        || resolvePrivateStorageFile($temporaryRoot, 'uploads/cv/resume.pdf', ['uploads/cv']) === null) {
        throw new RuntimeException('Resolver rejected an existing profile/CV upload in its allowed directory.');
    }
    foreach (['uploads/profil/../cv/resume.pdf', 'uploads/cv/../profil/photo.jpg'] as $crossUploadPath) {
        if (resolvePrivateStorageFile($temporaryRoot, $crossUploadPath, ['uploads/profil', 'uploads/cv']) !== null) {
            throw new RuntimeException('Resolver accepted a stored path crossing between profile and CV directories.');
        }
    }
} finally {
    @unlink($fileSymlink);
    @unlink($directorySymlink);
    @unlink($safeFile);
    @unlink($outsideFile);
    @unlink($profileDirectory . '/photo.jpg');
    @unlink($cvDirectory . '/resume.pdf');
    @rmdir($allowedDirectory);
    @rmdir($outsideDirectory);
    @rmdir($profileDirectory);
    @rmdir($cvDirectory);
    @rmdir($temporaryRoot . '/uploads');
    @rmdir($temporaryRoot . '/storage');
    @rmdir($temporaryRoot);
}

foreach ([
    '/storage/laporan/report.pdf',
    '/magang-verify/storage/laporan/report.pdf',
    '/storage%2flaporan/report.pdf',
    '/storage%252flaporan/report.pdf',
    '/storage%25252flaporan/report.pdf?download=1',
    '/assets/../storage/laporan/report.pdf',
    '/storage\\laporan\\report.pdf',
    '/STORAGE/LAPORAN/report.pdf',
    '/storage/laporan',
    '/storage/logbook/evidence/1.pdf',
    '/uploads/cv/random.pdf',
    '/magang-verify/uploads/cv/random.pdf',
] as $privatePath) {
    if (!isPrivateStorageRequestPath($privatePath)) {
        throw new RuntimeException('Private path normalization failed for a protected request.');
    }
}

foreach ([
    '/assets/app.css',
    '/storage/public/report.pdf',
    '/storage/laporan-public/report.pdf',
    '/uploads/portofolio/public.jpg',
] as $publicPath) {
    if (isPrivateStorageRequestPath($publicPath)) {
        throw new RuntimeException('Private path normalization incorrectly blocked an unrelated public path.');
    }
}

echo "Workflow static security checks passed (conditional validation and private file access).\n";
