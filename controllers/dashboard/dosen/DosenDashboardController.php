<?php

require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/DashboardDosen.php';
require_once __DIR__ . '/../../../models/Logbook.php';

class DosenDashboardController
{
    public function index($params = []): void
    {
        AuthMiddleware::handle('dosen');
        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        $dashboardModel = new DashboardDosen();
        $students = $dashboardModel->getBimbingan($userId);
        $statistics = $dashboardModel->getBimbinganStatistics($students);
        $logbookModel = new Logbook();
        $pendingLogbooks = $logbookModel->getReviewQueue($userId, 'dosen');

        require __DIR__ . '/../../../pages/dashboard/dosen/index.php';
    }
}