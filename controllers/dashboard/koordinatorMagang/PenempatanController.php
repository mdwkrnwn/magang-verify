<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../function/Helpers.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/PenempatanMagangCoordinator.php';

class KoordinatorPenempatanController
{
    public function index($params = []): void
    {
        AuthMiddleware::handle('koordinator_magang');
        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        $model = new PenempatanMagangCoordinator();
        $items = $model->getAcceptedWithoutPlacement();
        $error = null;
        $success = ($_GET['success'] ?? '') === '1';

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            if (!verifyCsrfToken()) {
                http_response_code(403);
                exit('Permintaan tidak valid. Silakan muat ulang halaman.');
            }
            $applicationId = filter_var($_POST['pendaftaran_id'] ?? null, FILTER_VALIDATE_INT);
            if (!$applicationId) {
                $error = 'Pengajuan magang tidak valid.';
            } else {
                try {
                    $model->create(
                        $applicationId,
                        $userId,
                        (string) ($_POST['tanggal_mulai'] ?? ''),
                        (string) ($_POST['tanggal_selesai'] ?? '')
                    );
                    header('Location: ' . url('/dashboard/koordinator-magang/penempatan?success=1'));
                    exit;
                } catch (PDOException $e) {
                    error_log('Gagal membuat penempatan magang: ' . $e->getMessage());
                    $error = 'Penempatan belum dapat dibuat. Silakan coba lagi.';
                } catch (Throwable $e) {
                    $error = $e->getMessage();
                }
                $items = $model->getAcceptedWithoutPlacement();
            }
        }

        require __DIR__ . '/../../../pages/dashboard/koordinatorMagang/penempatan/index.php';
    }
}
