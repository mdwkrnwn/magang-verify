<?php

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/FormasiMagang.php';

class FormasiMagangController
{
    private FormasiMagang $model;
    public function __construct() { $this->model = new FormasiMagang(); }

    public function index($params = [])
    {
        AuthMiddleware::handle('mahasiswa');
        $q = trim($_GET['q'] ?? '');
        $fLokasi = trim($_GET['lokasi'] ?? '');
        $fDurasi = trim($_GET['durasi'] ?? '');
        $fStatus = trim($_GET['status'] ?? '');
        $fSistemKerja = trim($_GET['sistem_kerja'] ?? '');
        $fTahun = trim($_GET['tahun_akademik'] ?? '');
        $all = $this->model->getAll(['q' => $q, 'lokasi' => $fLokasi, 'sistem_kerja' => $fSistemKerja, 'tahun_akademik' => $fTahun]);
        if ($fDurasi !== '') $all = array_values(array_filter($all, fn($x) => $x['durasi'] === $fDurasi));
        if ($fStatus !== '') $all = array_values(array_filter($all, fn($x) => $x['status'] === $fStatus));
        $options = $this->model->getFilterOptions();
        $optLokasi = $options['lokasi'];
        $optTahun = $options['tahun_akademik'];
        $optDurasi = array_values(array_unique(array_column($all, 'durasi')));
        sort($optDurasi);
        $optStatus = ['tersedia'];
        $perPage = 5;
        $total = count($all);
        $pages = max(1, (int)ceil($total / $perPage));
        $page = min(max(1, (int)($_GET['page'] ?? 1)), $pages);
        $tampil = array_slice($all, ($page - 1) * $perPage, $perPage);
        $url = fn($n) => '?' . http_build_query(array_merge($_GET, ['page' => $n]));
        require __DIR__ . '/../../../pages/dashboard/mahasiswa/formasi-magang/index.php';
    }

    public function detail($params = [])
    {
        AuthMiddleware::handle('mahasiswa');
        $formasi = $this->model->getBySlug((string)($params['slug'] ?? ''));
        if (!$formasi) {
            http_response_code(404);
            require __DIR__ . '/../../../pages/errors/404.php';
            exit;
        }
        require __DIR__ . '/../../../pages/dashboard/mahasiswa/formasi-magang/detail.php';
    }
}
