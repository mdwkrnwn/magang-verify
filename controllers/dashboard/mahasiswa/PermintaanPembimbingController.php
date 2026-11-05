<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../function/Helpers.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/PermintaanPembimbing.php';

class MahasiswaPermintaanPembimbingController
{
    public function index($params = []): void
    {
        AuthMiddleware::handle('mahasiswa');

        $userId = (int) ($_SESSION['user']['id'] ?? 0);

        if ($userId < 1) {
            http_response_code(401);
            exit('Sesi pengguna tidak valid.');
        }

        $model = new PermintaanPembimbing();

        // Mengambil data penempatan beserta dosen pembimbing
        $placements = $model->getStudentPlacements($userId);

        require __DIR__ . '/../../../pages/dashboard/mahasiswa/pembimbing/index.php';
    }
}