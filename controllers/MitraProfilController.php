
<?php

class MitraProfilController
{
    public function index($params = [])
    {
        // Load data mitra di dalam method
        require __DIR__ . '/../data/landingPage/Mitra.php';

        // Pastikan data tersedia
        if (!isset($mitra) || !is_array($mitra)) {
            throw new RuntimeException('Data mitra gagal dimuat.');
        }

        // Load halaman profil
        require __DIR__ . '/../pages/landingPage/mitra/profil/index.php';
    }
}
