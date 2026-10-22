<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../function/Helpers.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/PermintaanPembimbing.php';

class MahasiswaPermintaanPembimbingController
{
    public function index($params = []): void
    {
        AuthMiddleware::handle('mahasiswa');
        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        if ($userId < 1) {
            http_response_code(401);
            exit('Sesi pengguna tidak valid.');
        }

        $model = new PermintaanPembimbing();
        $placements = $model->getStudentPlacements($userId);
        $dosen = $model->getDosenOptions();
        $history = $model->getStudentHistory($userId);
        $error = null;
        $success = ($_GET['success'] ?? '') === '1';

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            if (!verifyCsrfToken()) {
                http_response_code(403);
                exit('Permintaan tidak valid. Silakan muat ulang halaman.');
            }

            $placementId = filter_var($_POST['penempatan_id'] ?? null, FILTER_VALIDATE_INT);
            $dosenId = filter_var($_POST['dosen_id'] ?? null, FILTER_VALIDATE_INT);
            if (!$placementId || !$dosenId) {
                $error = 'Pilih penempatan dan dosen pembimbing yang valid.';
            } else {
                try {
                    $model->create(
                        $userId,
                        $placementId,
                        $dosenId,
                        (string) ($_POST['alasan_pengajuan'] ?? '')
                    );
                    header('Location: ' . url('/dashboard/mahasiswa/pembimbing?success=1'));
                    exit;
                } catch (PDOException $e) {
                    error_log('Gagal menyimpan permintaan dosen pembimbing: ' . $e->getMessage());
                    $error = 'Permintaan belum dapat disimpan. Silakan coba lagi.';
                } catch (Throwable $e) {
                    $error = $e->getMessage();
                }
            }
        }

        require __DIR__ . '/../../../pages/dashboard/mahasiswa/pembimbing/index.php';
    }
}
