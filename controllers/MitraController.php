
<?php

class MitraController
{
    public function index($params = [])
    {
        // Load data di dalam method agar variabel tersedia
        require __DIR__ . '/../data/landingPage/Mitra.php';

        // Validasi data
        if (!isset($mitra) || !is_array($mitra)) {
            throw new RuntimeException(
                'File Mitra.php tidak menghasilkan array $mitra.'
            );
        }

        // Kirim data ke halaman
        require __DIR__ . '/../pages/landingPage/mitra/index.php';
    }
}
