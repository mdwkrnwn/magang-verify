<?php

require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/DashboardMitra.php';
require_once __DIR__ . '/../../../models/Logbook.php';

class MitraDashboardController
{
    public function index($params = []): void
    {
        AuthMiddleware::handle('mitra');
        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        $dashboard = (new DashboardMitra())->getData($userId);
        $items = (new Logbook())->getReviewQueue($userId, 'mitra');
        require __DIR__ . '/../../../pages/dashboard/mitra/index.php';
    }
}