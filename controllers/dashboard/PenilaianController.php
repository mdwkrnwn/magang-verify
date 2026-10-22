<?php

declare(strict_types=1);

require_once __DIR__ . '/../../function/Helpers.php';
require_once __DIR__ . '/../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../models/PenilaianMagang.php';

class PenilaianController
{
    public function index($params = []): void
    {
        $role = (string) ($_SESSION['user']['role'] ?? '');
        if (!in_array($role, ['dosen', 'mitra'], true)) {
            http_response_code(403);
            exit('Role tidak memiliki akses ke penilaian magang.');
        }
        AuthMiddleware::handle($role);
        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        $model = new PenilaianMagang();
        $items = $model->getAssignments($userId, $role);
        $error = null;
        $success = ($_GET['success'] ?? '') === '1';

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            if (!verifyCsrfToken()) {
                http_response_code(403);
                exit('Permintaan tidak valid. Silakan muat ulang halaman.');
            }
            $placementId = filter_var($_POST['penempatan_id'] ?? null, FILTER_VALIDATE_INT);
            $action = (string) ($_POST['action'] ?? '');
            if (!$placementId || !in_array($action, ['draft', 'submit'], true)) {
                $error = 'Data penilaian tidak valid.';
            } else {
                try {
                    $model->save($userId, $role, $placementId, $_POST, $action === 'submit');
                    header('Location: ' . url(($role === 'dosen' ? '/dashboard/dosen/penilaian' : '/dashboard/mitra/penilaian') . '?success=1'));
                    exit;
                } catch (PDOException $e) {
                    error_log('Gagal menyimpan penilaian magang: ' . $e->getMessage());
                    $error = 'Penilaian belum dapat disimpan. Silakan coba lagi.';
                } catch (Throwable $e) {
                    $error = $e->getMessage();
                }
                $items = $model->getAssignments($userId, $role);
            }
        }

        if ($role === 'dosen') {
            $active = 'monitoring';
            require __DIR__ . '/../../pages/dashboard/dosen/penilaian/index.php';
        } else {
            $active = 'penilaian';
            require __DIR__ . '/../../pages/dashboard/mitra/penilaian/index.php';
        }
    }
}
