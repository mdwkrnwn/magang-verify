<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../function/Helpers.php';
require_once __DIR__ . '/../function/PrivateStorage.php';
require_once __DIR__ . '/../models/ProfilMahasiswa.php';

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
        $slug = trim((string) ($params['slug'] ?? ''));
        $m = $this->profilModel->getPublicMahasiswaBySlug($slug);

        if (!$m) {
            http_response_code(404);
            require __DIR__ . '/../pages/errors/404.php';
            exit;
        }

        require __DIR__ . '/../pages/landingPage/mahasiswa/profil/index.php';
    }

    /** Menampilkan CV PDF mahasiswa secara publik. */
    public function cv($params = [])
    {
        $slug = trim((string) ($params['slug'] ?? ''));
        $m = $this->profilModel->getPublicMahasiswaBySlug($slug);

        if (!$m || empty($m['cv_path']) || !str_starts_with((string) $m['cv_path'], 'uploads/cv/')) {
            http_response_code(404);
            exit('CV mahasiswa belum tersedia.');
        }

        $filePath = resolvePrivateStorageFile(
            dirname(__DIR__),
            (string) $m['cv_path'],
            ['uploads/cv']
        );

        if ($filePath === null || !is_readable($filePath)) {
            http_response_code(404);
            exit('File CV tidak ditemukan.');
        }

        if (
            (new finfo(FILEINFO_MIME_TYPE))->file($filePath) !== 'application/pdf'
            || file_get_contents($filePath, false, null, 0, 5) !== '%PDF-'
        ) {
            http_response_code(415);
            exit('File CV tidak valid.');
        }

        $namaFile = basename((string) ($m['cv_nama_asli'] ?: 'CV-Mahasiswa.pdf'));
        $namaFile = preg_replace('/[^A-Za-z0-9._-]+/', '_', $namaFile) ?: 'CV-Mahasiswa.pdf';
        $download = isset($_GET['download']) && $_GET['download'] === '1';

        header('Content-Type: application/pdf');
        header('Content-Length: ' . filesize($filePath));
        header('X-Content-Type-Options: nosniff');
        header('Content-Disposition: ' . ($download ? 'attachment' : 'inline') . '; filename="' . $namaFile . '"');

        readfile($filePath);
        exit;
    }
}
