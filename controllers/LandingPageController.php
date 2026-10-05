<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../function/Helpers.php';
require_once __DIR__ . '/../models/ProfilMahasiswa.php';

class LandingPageController
{
    public function index($params = [])
    {
        global $pdo;

        try {
            $profilModel = new ProfilMahasiswa($pdo);
            $mahasiswaUnggulan = $profilModel->getFeaturedMahasiswa(3);
        } catch (Throwable $e) {
            error_log('Gagal mengambil mahasiswa unggulan: ' . $e->getMessage());
            $mahasiswaUnggulan = [];
        }

        require __DIR__ . '/../pages/landingPage/index.php';
    }
}
