
<?php

require_once __DIR__ . '/../shared/PengaturanBaseController.php';

class TendikPengaturanController extends PengaturanBaseController
{
    protected function getRole(): string
    {
        return 'tendik';
    }

    protected function getViewPath(): string
    {
        return __DIR__ . '/../../../pages/dashboard/tendik/pengaturan/index.php';
    }

    protected function getRedirectUrl(): string
    {
        return '/dashboard/tendik/pengaturan';
    }
}
