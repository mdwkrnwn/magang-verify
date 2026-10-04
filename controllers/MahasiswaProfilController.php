<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../function/Helpers.php';
require_once __DIR__ . '/../models/ProfilMahasiswa.php';
require_once __DIR__ . '/../data/landingPage/Mahasiswa.php';

class MahasiswaProfilController
{
    private ProfilMahasiswa $profilModel;

    public function __construct()
    {
        global $pdo;

        $this->profilModel = new ProfilMahasiswa($pdo);
    }

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

        // CV publik berasal dari profil mahasiswa di database.
        $cv = $this->profilModel->getCvBySlug($slug);
        $m['cv_path'] = $cv['cv_path'] ?? null;
        $m['cv_nama_asli'] = $cv['cv_nama_asli'] ?? null;
        $m['cv_ukuran_bytes'] = $cv['cv_ukuran_bytes'] ?? null;
        $m['cv_updated_at'] = $cv['cv_updated_at'] ?? null;

        require __DIR__ . '/../pages/landingPage/mahasiswa/profil/index.php';
    }
    /**
     * Menampilkan CV PDF mahasiswa secara publik.
     */
    public function cv($params = [])
    {
        $slug = trim((string) ($params['slug'] ?? ''));

        if ($slug === '') {
            http_response_code(404);
            exit('CV mahasiswa tidak ditemukan.');
        }

        $cv = $this->profilModel->getCvBySlug($slug);

        if (
            !$cv ||
            empty($cv['cv_path']) ||
            !str_starts_with($cv['cv_path'], 'uploads/cv/')
        ) {
            http_response_code(404);
            exit('CV mahasiswa belum tersedia.');
        }

        $projectRoot = dirname(__DIR__);
        $filePath = $projectRoot . '/' . ltrim($cv['cv_path'], '/');

        if (!is_file($filePath) || !is_readable($filePath)) {
            http_response_code(404);
            exit('File CV tidak ditemukan.');
        }

        $mime = $cv['cv_mime_type'] ?: 'application/pdf';

        if ($mime !== 'application/pdf') {
            http_response_code(415);
            exit('Format CV tidak didukung.');
        }

        $namaFile = trim((string) ($cv['cv_nama_asli'] ?? 'CV-Mahasiswa.pdf'));
        $namaFile = basename($namaFile);

        if ($namaFile === '') {
            $namaFile = 'CV-Mahasiswa.pdf';
        }

        $namaFileAscii = preg_replace(
            '/[^A-Za-z0-9._-]+/',
            '_',
            $namaFile
        );

        if (!is_string($namaFileAscii) || $namaFileAscii === '') {
            $namaFileAscii = 'CV-Mahasiswa.pdf';
        }

        $download = isset($_GET['download']) && $_GET['download'] === '1';

        header('Content-Type: application/pdf');
        header('Content-Length: ' . filesize($filePath));
        header('X-Content-Type-Options: nosniff');
        header(
            'Content-Disposition: '
            . ($download ? 'attachment' : 'inline')
            . '; filename="' . $namaFileAscii . '"'
        );

        readfile($filePath);
        exit;
    }


}