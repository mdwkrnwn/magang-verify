<?php

declare(strict_types=1);

$controller = file_get_contents(__DIR__ . '/../controllers/dashboard/tendik/VerifikasiDokumenController.php');
$portfolioModel = file_get_contents(__DIR__ . '/../models/Portofolio.php');
$certificateModel = file_get_contents(__DIR__ . '/../models/Sertifikat.php');
$studentController = file_get_contents(__DIR__ . '/../controllers/dashboard/mahasiswa/PortofolioController.php');
$studentCertificate = file_get_contents(__DIR__ . '/../controllers/dashboard/mahasiswa/SertifikatController.php');
$routes = file_get_contents(__DIR__ . '/../routes/web.php');
$sidebar = file_get_contents(__DIR__ . '/../components/dashboard/tendik/Sidebar.php');
$studentPage = file_get_contents(__DIR__ . '/../pages/dashboard/mahasiswa/pembimbing/index.php');

function expectVerification(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException('Verification regression failed: ' . $message);
    }
}

expectVerification(str_contains($controller, "AuthMiddleware::handle('tendik')"), 'Tendik middleware protects verification endpoints');
expectVerification(str_contains($controller, 'verifyCsrfToken()'), 'decisions require CSRF');
expectVerification(str_contains($controller, 'resolvePrivateStorageFile'), 'evidence uses private storage resolver');
expectVerification(str_contains($controller, 'portfolioFile'), 'portfolio evidence endpoint exists');
expectVerification(str_contains($controller, 'certificateFile'), 'certificate evidence endpoint exists');
expectVerification(str_contains($portfolioModel, 'riwayat_verifikasi_portofolio'), 'portfolio decision preserves history');
expectVerification(str_contains($portfolioModel, "in_array(\$previous, ['belum_diverifikasi', 'perlu_perbaikan'], true)"), 'portfolio transition is state guarded');
expectVerification(str_contains($portfolioModel, 'status_sebelumnya'), 'portfolio history stores previous status');
expectVerification(str_contains($certificateModel, "status_verifikasi IN ('belum_terverifikasi', 'dalam_peninjauan', 'perlu_perbaikan')"), 'certificate transition is state guarded including revision state');
expectVerification(str_contains($certificateModel, "if (\$decision !== 'terverifikasi' && \$note === '')"), 'certificate revision and rejection require a note while approval does not');
expectVerification(str_contains($studentController, "AuthMiddleware::handle('mahasiswa')"), 'portfolio remains student-owned');
expectVerification(str_contains($studentCertificate, "AuthMiddleware::handle('mahasiswa')"), 'certificate remains student-owned');
expectVerification(str_contains($routes, "'/dashboard/tendik/verifikasi'"), 'verification list route exists');
expectVerification(str_contains($routes, 'TendikVerifikasiDokumenController'), 'verification dynamic routes use dedicated controller');
expectVerification(str_contains($sidebar, "url('/dashboard/tendik/verifikasi/portofolio')") && str_contains($sidebar, "url('/dashboard/tendik/verifikasi/sertifikat')"), 'Tendik sidebar exposes separate portfolio and certificate verification menus');
expectVerification(!str_contains($studentPage, 'name="dosen_id"'), 'unrelated student page does not expose advisor selector');

fwrite(STDOUT, "Verification document source authorization checks passed.\n");
