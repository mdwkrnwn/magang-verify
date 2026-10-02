<?php

require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';

class DashboardController
{
    public function index($params = [])
    {
        AuthMiddleware::handle('mahasiswa');

        require __DIR__ . '/../../../pages/dashboard/mahasiswa/index.php';
    }
}