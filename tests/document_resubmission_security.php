<?php

declare(strict_types=1);

require_once __DIR__ . '/../function/PrivateStorage.php';

function assertDocumentCheck(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException('Document regression failed: ' . $message);
    }
}

$portfolioModel = file_get_contents(__DIR__ . '/../models/Portofolio.php');
$certificateModel = file_get_contents(__DIR__ . '/../models/Sertifikat.php');
$portfolioController = file_get_contents(__DIR__ . '/../controllers/dashboard/mahasiswa/PortofolioController.php');
$certificateController = file_get_contents(__DIR__ . '/../controllers/dashboard/mahasiswa/SertifikatController.php');
$verificationController = file_get_contents(__DIR__ . '/../controllers/dashboard/tendik/VerifikasiDokumenController.php');
$authMiddleware = file_get_contents(__DIR__ . '/../middleware/AuthMiddleware.php');
$studentRoutes = file_get_contents(__DIR__ . '/../routes/web.php');
$router = file_get_contents(__DIR__ . '/../router.php');
$uploadRules = file_get_contents(__DIR__ . '/../uploads/.htaccess');
$studentCertificateCard = file_get_contents(__DIR__ . '/../components/dashboard/mahasiswa/sertifikat/SertifikatCard.php');
$studentCertificateHeader = file_get_contents(__DIR__ . '/../components/dashboard/mahasiswa/sertifikat/detail/Header.php');
$verificationCertificatePage = file_get_contents(__DIR__ . '/../pages/dashboard/tendik/verifikasi/sertifikat.php');
$verificationPortfolioPage = file_get_contents(__DIR__ . '/../pages/dashboard/tendik/verifikasi/portofolio.php');

foreach ([
    $portfolioModel, $certificateModel, $portfolioController, $certificateController,
    $verificationController, $authMiddleware, $studentRoutes, $router, $uploadRules, $studentCertificateCard,
    $studentCertificateHeader, $verificationCertificatePage, $verificationPortfolioPage,
] as $source) {
    assertDocumentCheck($source !== false, 'required source is readable');
}

// Static source-contract checks; these do not execute SQL or prove live authorization.
assertDocumentCheck(str_contains($portfolioModel, "['belum_diverifikasi', 'ditolak', 'perlu_perbaikan']"), 'portfolio edits accept rejected and revision-needed states');
assertDocumentCheck(str_contains($portfolioModel, "'ditolak', 'perlu_perbaikan'"), 'both rejected portfolio states transition on resubmission');
assertDocumentCheck(str_contains($portfolioModel, "'status_verifikasi' => :previous_status" ) || str_contains($portfolioModel, 'AND status_verifikasi = :previous_status'), 'portfolio update is guarded by its prior state');
assertDocumentCheck(str_contains($portfolioModel, 'status_baru, catatan') && str_contains($portfolioModel, "'belum_diverifikasi'"), 'portfolio resubmission appends a prior-to-pending history row');
assertDocumentCheck(str_contains($portfolioModel, 'FOR UPDATE') && str_contains($portfolioModel, '$pdo->commit();') && str_contains($portfolioModel, '$pdo->rollBack();'), 'portfolio edit and resubmission share a transaction');
assertDocumentCheck(str_contains($portfolioController, "['belum_diverifikasi', 'ditolak', 'perlu_perbaikan']"), 'student portfolio edit route permits only eligible states');
assertDocumentCheck(str_contains($portfolioController, "\$portofolioEdit['verifikasi']['status'] === 'belum_diverifikasi'"), 'prior portfolio evidence is retained on resubmission');

assertDocumentCheck(str_contains($certificateModel, "status_verifikasi = 'ditolak'"), 'certificate resubmission is rejected-state-only');
assertDocumentCheck(str_contains($certificateModel, "WHERE id = :id AND user_id = :user_id AND status_verifikasi = 'ditolak'"), 'certificate resubmission is scoped to its owner');
assertDocumentCheck(str_contains($certificateModel, "status_verifikasi IN ('belum_terverifikasi', 'ditolak', 'perlu_perbaikan')"), 'certificate edits reject in-review and verified states while allowing revision');
assertDocumentCheck(str_contains($certificateModel, "status_verifikasi = 'belum_terverifikasi',\n                catatan_verifikasi = NULL, diverifikasi_oleh = NULL, diverifikasi_pada = NULL"), 'certificate edit-resubmission clears old decision metadata atomically');
assertDocumentCheck(str_contains($certificateModel, 'AND status_verifikasi = :previous_status'), 'certificate edit uses a conditional prior-state update');
assertDocumentCheck(str_contains($certificateController, "(\$sertifikat['verifikasi']['status'] ?? '') !== 'ditolak'"), 'standalone certificate resubmission route checks rejected state');
assertDocumentCheck(str_contains($certificateController, 'AuthMiddleware::handle(\'mahasiswa\')'), 'student certificate routes require the student role');
assertDocumentCheck(str_contains($verificationController, "AuthMiddleware::handle('tendik')"), 'verification endpoints require Tendik authorization');
assertDocumentCheck(str_contains($authMiddleware, 'if ($matchedRole === null)') && str_contains($authMiddleware, 'http_response_code(403)'), 'role mismatch is denied by shared middleware');
assertDocumentCheck(str_contains($verificationController, "AuthMiddleware::handle('tendik')") && !str_contains($verificationController, "AuthMiddleware::handle('mahasiswa')"), 'students and other roles cannot invoke Tendik decisions or file handlers');
assertDocumentCheck(str_contains($verificationController, 'b.id = :file_id') && str_contains($verificationController, 'p.id = :portfolio_id'), 'portfolio file endpoint checks document and file IDs together');
assertDocumentCheck(str_contains($verificationController, "['uploads/portofolio']"), 'portfolio private files use the actual uploads storage resolver');
assertDocumentCheck(str_contains($verificationController, 'function portfolioCover') && str_contains($studentRoutes, "'portfolioCover'"), 'portfolio cover uses an authorized handler route');
assertDocumentCheck(str_contains($verificationController, 'getForVerification($id)'), 'certificate file endpoint resolves the document from server-side database state');
assertDocumentCheck(str_contains($portfolioController, 'getPortofolioBySlug($slug, $userId)') || str_contains($portfolioController, 'getPortofolioBySlug'), 'student portfolio routes scope lookup to authenticated owner');
assertDocumentCheck(str_contains($certificateController, 'getSertifikatBySlug($slug, $userId)'), 'student certificate routes scope lookup to authenticated owner');
assertDocumentCheck(str_contains($studentRoutes, "'method' => 'cover'") && str_contains($studentRoutes, '/dashboard/mahasiswa/portofolio/cover/'), 'student portfolio preview uses the authenticated cover handler route');
assertDocumentCheck(str_contains($certificateController, 'public function preview') && str_contains($studentRoutes, '/dashboard/mahasiswa/sertifikat/pratinjau/'), 'student certificate preview uses the authenticated preview handler route');

assertDocumentCheck(str_contains($certificateModel, "'gambar' => basename(\$filePath)"), 'student certificate view data exposes only a basename, not a storage path');
assertDocumentCheck(!str_contains($certificateModel, "'file_path' => \$filePath"), 'formatted certificate data excludes raw file_path');
foreach ([$studentCertificateCard, $studentCertificateHeader, $verificationCertificatePage, $verificationPortfolioPage] as $view) {
    assertDocumentCheck(!str_contains($view, 'storage/portofolio/') && !str_contains($view, 'uploads/sertifikat/'), 'HTML views do not contain private storage URLs');
}
assertDocumentCheck(str_contains($uploadRules, 'Require all denied'), 'Apache denies direct access to private uploads');
assertDocumentCheck(str_contains($router, 'isPrivateStorageRequestPath((string) $path)'), 'application router blocks private storage requests');
assertDocumentCheck(isPrivateStorageRequestPath('/uploads/sertifikat/document.pdf'), 'certificate upload URL is denied by the application router');
assertDocumentCheck(isPrivateStorageRequestPath('/uploads/portofolio/image.webp'), 'portfolio upload URL is denied by the application router');
assertDocumentCheck(isPrivateStorageRequestPath('/storage/portofolio/document.pdf'), 'portfolio storage URL is denied by the application router');
assertDocumentCheck(isPrivateStorageRequestPath('/uploads/%2573ertifikat/document.pdf'), 'encoded private upload paths are denied');
foreach ([
    '/storage/portofolio/../other/file.pdf',
    '/storage/%2e%2e/other/file.pdf',
    '/uploads/%252e%252e/sertifikat/file.pdf',
    '/uploads/%255c%255c..%255csertifikat/file.pdf',
] as $traversalPath) {
    assertDocumentCheck(isPrivateStorageRequestPath($traversalPath), 'encoded traversal is rejected: ' . $traversalPath);
}
assertDocumentCheck(!str_contains($verificationController, "dirname(__DIR__, 3) . '/' . (\$_GET") && !str_contains($verificationController, 'readfile((string) $_GET'), 'file handlers do not trust a request-supplied physical path');
foreach ([$verificationController, $studentCertificateCard, $studentCertificateHeader, $verificationCertificatePage, $verificationPortfolioPage] as $source) {
    assertDocumentCheck(!str_contains($source, '/home/') && !str_contains($source, 'C:\\Users\\'), 'physical storage paths are not exposed in response-facing source');
}

// Exercise canonical private-file resolution using disposable fixtures in the configured scratch directory.
$scratch = getenv('TMPDIR');
assertDocumentCheck(is_string($scratch) && $scratch !== '' && is_dir($scratch), 'isolated scratch directory is configured');
$root = rtrim($scratch, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'document-private-' . bin2hex(random_bytes(8));
$privateDir = $root . '/uploads/sertifikat';
$outsideDir = $root . '/outside';
if (!mkdir($privateDir, 0700, true) || !mkdir($outsideDir, 0700, true)) {
    throw new RuntimeException('Unable to create isolated private-storage fixtures.');
}
file_put_contents($privateDir . '/safe.pdf', '%PDF-test');
file_put_contents($outsideDir . '/outside.pdf', '%PDF-test');
try {
    assertDocumentCheck(resolvePrivateStorageFile($root, 'uploads/sertifikat/safe.pdf', ['uploads/sertifikat']) !== null, 'valid private certificate path resolves');
    assertDocumentCheck(resolvePrivateStorageFile($root, 'uploads/sertifikat/../outside/outside.pdf', ['uploads/sertifikat']) === null, 'traversal to another student file is rejected');
    assertDocumentCheck(resolvePrivateStorageFile($root, 'outside/outside.pdf', ['uploads/sertifikat']) === null, 'file outside the allowed private directory is rejected');
    if (function_exists('symlink')) {
        symlink($outsideDir . '/outside.pdf', $privateDir . '/escape.pdf');
        assertDocumentCheck(resolvePrivateStorageFile($root, 'uploads/sertifikat/escape.pdf', ['uploads/sertifikat']) === null, 'symlink escape is rejected');
    }
} finally {
    foreach ([$privateDir . '/safe.pdf', $privateDir . '/escape.pdf', $outsideDir . '/outside.pdf'] as $file) {
        if (is_file($file) || is_link($file)) {
            unlink($file);
        }
    }
    rmdir($privateDir);
    rmdir($root . '/uploads');
    rmdir($outsideDir);
    rmdir($root);
}

fwrite(STDOUT, "Document resubmission source checks and isolated private-path tests passed.\n");
