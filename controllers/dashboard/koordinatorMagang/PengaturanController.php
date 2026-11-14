
<?php

require_once __DIR__ . '/../shared/PengaturanBaseController.php';

class KoordinatorMagangPengaturanController extends PengaturanBaseController
{
    protected function getRole(): string
    {
        return 'koordinator_magang';
    }

    protected function getViewPath(): string
    {
        return __DIR__ . '/../../../pages/dashboard/koordinatorMagang/pengaturan/index.php';
    }

    protected function getRedirectUrl(): string
    {
        return '/dashboard/koordinator-magang/pengaturan';
    }
}
