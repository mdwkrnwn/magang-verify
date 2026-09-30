<?php

class MahasiswaDashboardController
{
    public function index($params = [])
    {
        require __DIR__ . '/../pages/dashboard/mahasiswa/index.php';
    }

    public function profil($params = [])
    {
        require __DIR__ . '/../pages/dashboard/mahasiswa/profil/index.php';
    }

    // public function portofolio($params = [])
    // {
    //     require __DIR__ . '/../pages/dashboard/mahasiswa/portofolio/index.php';
    // }

    // public function sertifikat($params = [])
    // {
    //     require __DIR__ . '/../pages/dashboard/mahasiswa/sertifikat/index.php';
    // }

    // public function formasiMagang($params = [])
    // {
    //     require __DIR__ . '/../pages/dashboard/mahasiswa/formasi-magang/index.php';
    // }

    // public function pengajuan($params = [])
    // {
    //     require __DIR__ . '/../pages/dashboard/mahasiswa/pengajuan/index.php';
    // }

    // public function logbook($params = [])
    // {
    //     require __DIR__ . '/../pages/dashboard/mahasiswa/logbook/index.php';
    // }
}