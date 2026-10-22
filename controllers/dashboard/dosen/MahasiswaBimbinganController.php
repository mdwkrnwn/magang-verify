<?php

require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/DashboardDosen.php';

class MahasiswaBimbinganController
{
    public function index($params = []): void
    {
        AuthMiddleware::handle('dosen');
        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        $model = new DashboardDosen();
        $students = $model->getBimbingan($userId);
        $statistics = $model->getBimbinganStatistics($students);

        require __DIR__ . '/../../../pages/dashboard/dosen/bimbingan/index.php';
    }
}
