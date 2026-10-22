<?php

require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/KoordinatorMagangDashboard.php';

class KoordinatorMagangDashboardController
{
    public function index($params = []): void
    {
        AuthMiddleware::handle('koordinator_magang');
        $dashboard = (new KoordinatorMagangDashboard())->getData();
        require __DIR__ . '/../../../pages/dashboard/koordinatorMagang/index.php';
    }
}