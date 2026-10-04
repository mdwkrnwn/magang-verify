<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../function/Helpers.php';
require_once __DIR__ . '/../models/ProfilMahasiswa.php';

class MahasiswaController
{
    private ProfilMahasiswa $profilModel;

    public function __construct()
    {
        global $pdo;
        $this->profilModel = new ProfilMahasiswa($pdo);
    }

    public function index($params = [])
    {
        try {
            $mahasiswa = $this->profilModel->getPublicMahasiswa();
        } catch (Throwable $e) {
            error_log('Gagal mengambil daftar mahasiswa publik: ' . $e->getMessage());
            http_response_code(500);
            exit('Terjadi kesalahan saat mengambil data mahasiswa.');
        }

        require __DIR__ . '/../pages/landingPage/mahasiswa/index.php';
    }
}
