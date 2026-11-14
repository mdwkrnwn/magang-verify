
<?php

require_once __DIR__ . '/../shared/PengaturanBaseController.php';

class DosenPengaturanController extends PengaturanBaseController
{
    protected function getRole(): string
    {
        return 'dosen';
    }

    protected function getViewPath(): string
    {
        return __DIR__ . '/../../../pages/dashboard/dosen/pengaturan/index.php';
    }

    protected function getRedirectUrl(): string
    {
        return '/dashboard/dosen/pengaturan';
    }
}
