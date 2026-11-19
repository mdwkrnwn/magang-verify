
<?php

require_once __DIR__ . '/../../../models/DashboardMitra.php';
require_once __DIR__ . '/../../../models/Logbook.php';

class MitraDashboardController
{
    public function index($params = []): void
    {
        // Session Mitra telah divalidasi oleh
        // MitraAuthMiddleware di index.php.

        $mitraId = (int) ($_SESSION['mitra']['id'] ?? 0);

        $dashboard = (new DashboardMitra())->getData($mitraId);

        $items = (new Logbook())->getReviewQueue($mitraId, 'mitra');

        require __DIR__ . '/../../../pages/dashboard/mitra/index.php';
    }
}