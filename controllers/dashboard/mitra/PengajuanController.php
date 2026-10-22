<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../function/Helpers.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/PengajuanMitra.php';

class MitraPengajuanController
{
    public function index($params = []): void
    {
        AuthMiddleware::handle('mitra');
        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        if ($userId < 1) {
            http_response_code(401);
            exit('Sesi pengguna tidak valid.');
        }

        $model = new PengajuanMitra();
        $error = null;
        $formValues = [];
        $success = ($_GET['success'] ?? '') === '1';
        try {
            $items = $model->getApplications($userId);
        } catch (PDOException $e) {
            error_log('Gagal memuat antrean pengajuan Mitra: ' . $e->getMessage());
            http_response_code(500);
            $items = [];
            $error = 'Antrean pengajuan belum dapat dimuat. Coba muat ulang halaman.';
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            if (!verifyCsrfToken()) {
                http_response_code(403);
                exit('Permintaan tidak valid. Silakan muat ulang halaman.');
            }
            $id = filter_var($_POST['application_id'] ?? null, FILTER_VALIDATE_INT);
            $decision = (string) ($_POST['decision'] ?? '');
            $note = trim((string) ($_POST['catatan_mitra'] ?? ''));
            if ($id && mb_strlen($note) <= 5000) {
                $formValues[(int) $id] = $note;
            }
            if (!$id || !in_array($decision, ['diterima', 'ditolak', 'seleksi'], true)) {
                $error = 'Data respons pengajuan tidak valid.';
            } elseif (mb_strlen($note) > 5000) {
                $error = 'Catatan respons maksimal 5.000 karakter.';
            } else {
                try {
                    $model->respond($userId, $id, $decision, $note);
                    header('Location: ' . url('/dashboard/mitra/pengajuan?success=1'));
                    exit;
                } catch (PDOException $e) {
                    error_log('Gagal menyimpan respons pengajuan mitra: ' . $e->getMessage());
                    $error = 'Respons belum dapat disimpan. Silakan coba lagi.';
                } catch (Throwable $e) {
                    $error = $e->getMessage();
                }
            }
            try {
                $items = $model->getApplications($userId);
            } catch (PDOException $e) {
                error_log('Gagal memuat ulang antrean pengajuan Mitra: ' . $e->getMessage());
                $items = [];
                $error = 'Antrean pengajuan belum dapat dimuat ulang. Muat ulang halaman.';
            }
        }

        require __DIR__ . '/../../../pages/dashboard/mitra/pengajuan/index.php';
    }
}
