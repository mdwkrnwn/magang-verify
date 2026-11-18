<?php

require_once __DIR__ . '/../shared/PengaturanBaseController.php';

class MitraPengaturanController extends PengaturanBaseController
{
    protected function getRole(): string
    {
        return 'mitra';
    }

    protected function getViewPath(): string
    {
        return __DIR__ . '/../../../pages/dashboard/mitra/pengaturan/index.php';
    }

    protected function getRedirectUrl(): string
    {
        return '/dashboard/mitra/pengaturan';
    }
}
