<?php

require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../function/Helpers.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/DashboardDosen.php';

class MahasiswaBimbinganController
{
    public function index($params = []): void
    {
        AuthMiddleware::handle('dosen');

        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        $model = new DashboardDosen();

        try {
            $students = $model->getBimbingan($userId);
            $statistics = $model->getBimbinganStatistics($students);
        } catch (Throwable $e) {
            error_log('Gagal memuat mahasiswa bimbingan: ' . $e->getMessage());

            http_response_code(500);

            $students = [];
            $statistics = ['mahasiswa' => 0];
        }

        $active = 'bimbingan';

        require __DIR__ . '/../../../pages/dashboard/dosen/bimbingan/index.php';
    }

    public function detail(array $params = []): void
    {
        AuthMiddleware::handle('dosen');

        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        $placementId = filter_var(
            $params['id'] ?? null,
            FILTER_VALIDATE_INT
        );

        if (!$placementId || $placementId < 1) {
            http_response_code(404);
            require __DIR__ . '/../../../pages/errors/404.php';
            return;
        }

        try {
            $model = new DashboardDosen();

            $placement = $model->getPlacementForLecturer(
                $userId,
                $placementId
            );
        } catch (Throwable $e) {
            error_log('Gagal memuat detail bimbingan: ' . $e->getMessage());

            http_response_code(500);
            exit('Detail mahasiswa bimbingan belum dapat dimuat.');
        }

        if (!$placement) {
            http_response_code(404);
            require __DIR__ . '/../../../pages/errors/404.php';
            return;
        }

        $active = 'bimbingan';

        require __DIR__ . '/../../../pages/dashboard/dosen/bimbingan/detail.php';
    }
}
