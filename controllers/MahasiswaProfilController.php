<?php

require_once __DIR__ . '/../data/landingPage/Mahasiswa.php';

class MahasiswaProfilController
{
    public function index($params = [])
    {
        global $mahasiswa;

        $slug = $params['slug'] ?? '';

        $mahasiswaDetail = null;


        foreach ($mahasiswa as $row) {

            if (($row['slug'] ?? '') === $slug) {
                $mahasiswaDetail = $row;
                break;
            }

        }


        if (!$mahasiswaDetail) {

            http_response_code(404);

            echo '<h1>404 - Profil Mahasiswa Tidak Ditemukan</h1>';

            exit;
        }


        $m = $mahasiswaDetail;


        require __DIR__ . '/../pages/landingPage/mahasiswa/profil/index.php';
    }
}