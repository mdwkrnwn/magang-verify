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

    /** Serve profile photos only when a public profile references the exact stored path. */
    public function foto($params = []): never
    {
        $filename = (string) ($params['filename'] ?? '');
        if (!preg_match('/^[a-f0-9]{32}\.(?:jpg|png|webp)$/', $filename)) {
            http_response_code(404);
            exit('Foto mahasiswa tidak ditemukan.');
        }

        $profile = $this->profilModel->getPublicMahasiswaByPhotoFilename($filename);
        if (!$profile) {
            http_response_code(404);
            exit('Foto mahasiswa tidak ditemukan.');
        }

        $filePath = resolvePrivateStorageFile(
            dirname(__DIR__),
            'uploads/profil/' . $filename,
            ['uploads/profil']
        );
        if ($filePath === null || !is_readable($filePath)) {
            http_response_code(404);
            exit('Foto mahasiswa tidak ditemukan.');
        }

        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($filePath);
        $expectedMime = match (pathinfo($filePath, PATHINFO_EXTENSION)) {
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
            default => null,
        };
        if ($expectedMime === null || $mime !== $expectedMime || @getimagesize($filePath) === false) {
            http_response_code(415);
            exit('Foto mahasiswa tidak valid.');
        }

        header('Content-Type: ' . $mime);
        header('Content-Length: ' . (string) filesize($filePath));
        header('Cache-Control: public, max-age=3600');
        header('X-Content-Type-Options: nosniff');
        readfile($filePath);
        exit;
    }

    /** Serve only published portfolio images through an explicit endpoint. */
    public function portofolio($params = []): never
    {
        $filename = (string) ($params['filename'] ?? '');
        if (!preg_match('/^[a-f0-9]{32}\.(?:jpg|png|webp)$/', $filename)) {
            http_response_code(404);
            exit('Gambar portofolio tidak ditemukan.');
        }
        if (!$this->profilModel->getPublicPortfolioImageByFilename($filename)) {
            http_response_code(404);
            exit('Gambar portofolio tidak ditemukan.');
        }
        $filePath = resolvePrivateStorageFile(dirname(__DIR__), 'uploads/portofolio/' . $filename, ['uploads/portofolio']);
        if ($filePath === null || !is_readable($filePath)) {
            http_response_code(404);
            exit('Gambar portofolio tidak ditemukan.');
        }
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($filePath);
        if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true) || @getimagesize($filePath) === false) {
            http_response_code(415);
            exit('Gambar portofolio tidak valid.');
        }
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . (string) filesize($filePath));
        header('Cache-Control: public, max-age=3600');
        header('X-Content-Type-Options: nosniff');
        readfile($filePath);
        exit;
    }
}
