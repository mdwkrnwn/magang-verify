<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../function/Helpers.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/PermintaanPembimbing.php';

class DosenPermintaanPembimbingController
{
    public function index($params = []): void
    {
        AuthMiddleware::handle('dosen');
        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        if ($userId < 1) {
            http_response_code(401);
            exit('Sesi pengguna tidak valid.');
        }

        $model = new PermintaanPembimbing();
        $requests = $model->getPendingForDosen($userId);
        $error = null;
        $success = ($_GET['success'] ?? '') === '1';

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            if (!verifyCsrfToken()) {
                http_response_code(403);
                exit('Permintaan tidak valid. Silakan muat ulang halaman.');
            }

            $requestId = filter_var($_POST['request_id'] ?? null, FILTER_VALIDATE_INT);
            $decision = (string) ($_POST['decision'] ?? '');
            if (!$requestId || !in_array($decision, ['disetujui', 'ditolak'], true)) {
                $error = 'Data keputusan permintaan tidak valid.';
            } else {
                try {
                    $model->respond(
                        $userId,
                        $requestId,
                        $decision,
                        (string) ($_POST['catatan_dosen'] ?? '')
                    );
                    header('Location: ' . url('/dashboard/dosen/permintaan-pembimbing?success=1'));
                    exit;
                } catch (PDOException $e) {
                    error_log('Gagal memproses permintaan dosen pembimbing: ' . $e->getMessage());
                    $error = 'Keputusan belum dapat disimpan. Silakan coba lagi.';
                } catch (Throwable $e) {
                    $error = $e->getMessage();
                }
            }
        }

        require __DIR__ . '/../../../pages/dashboard/dosen/permintaan-pembimbing/index.php';
    }
}
