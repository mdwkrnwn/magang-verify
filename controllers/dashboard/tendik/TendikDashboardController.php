<?php

require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/Logbook.php';

class TendikDashboardController
{
    public function index($params = []): void
    {
        AuthMiddleware::handle('tendik');
        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        $items = (new Logbook())->getReviewQueue($userId, 'tendik');
        require __DIR__ . '/../../../pages/dashboard/tendik/index.php';
    }
}