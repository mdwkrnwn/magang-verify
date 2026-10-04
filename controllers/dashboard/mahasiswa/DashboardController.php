<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/DashboardMahasiswa.php';

class DashboardController
{
    public function index($params = []): void
    {
        AuthMiddleware::handle('mahasiswa');

        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        if ($userId <= 0) {
            header('Location: ' . url('/login'));
            exit;
        }

        $dashboardModel = new DashboardMahasiswa();
        $dashboard = $dashboardModel->getData($userId);

        require __DIR__ . '/../../../pages/dashboard/mahasiswa/index.php';
    }
}
