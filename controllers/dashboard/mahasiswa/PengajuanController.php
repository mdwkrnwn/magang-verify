<?php

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/PendaftaranMagang.php';

class PengajuanController
{
    private PendaftaranMagang $model;
    public function __construct() { $this->model = new PendaftaranMagang(); }

    public function index($params = [])
    {
        AuthMiddleware::handle('mahasiswa');
        $userId = (int)($_SESSION['user']['id'] ?? 0);
        $profil = $this->model->getProfilMahasiswa($userId);
        if (!$profil) { http_response_code(404); exit('Profil mahasiswa tidak ditemukan.'); }
        $pengajuan = $this->model->getByMahasiswa((int)$profil['mahasiswa_id']);
        $search = trim($_GET['search'] ?? '');
        $status = trim($_GET['status'] ?? '');
        $sort = ($_GET['sort'] ?? 'terbaru') === 'terlama' ? 'terlama' : 'terbaru';
        if ($search !== '') {
            $needle = mb_strtolower($search);
            $pengajuan = array_values(array_filter($pengajuan, fn($item) => str_contains(mb_strtolower($item['perusahaan']), $needle) || str_contains(mb_strtolower($item['posisi']), $needle)));
        }
        $statusMap = ['Menunggu Verifikasi' => ['Diajukan', 'Menunggu Persetujuan Dosen'], 'Dalam Proses' => ['Menunggu Respons Mitra', 'Tahap Seleksi', 'Perlu Revisi'], 'Diterima' => ['Diterima', 'Selesai'], 'Ditolak' => ['Ditolak Dosen', 'Ditolak Mitra']];
        if (isset($statusMap[$status])) $pengajuan = array_values(array_filter($pengajuan, fn($item) => in_array($item['status'], $statusMap[$status], true)));
        usort($pengajuan, fn($a, $b) => ($sort === 'terlama' ? 1 : -1) * (strtotime($a['tanggal']) <=> strtotime($b['tanggal'])));
        $perPage = 5; $totalData = count($pengajuan); $pages = max(1, (int)ceil($totalData / $perPage));
        $page = min(max(1, (int)($_GET['page'] ?? 1)), $pages);
        $pengajuan = array_slice($pengajuan, ($page - 1) * $perPage, $perPage);
        $url = fn($pageNumber) => '?' . http_build_query(array_merge($_GET, ['page' => $pageNumber]));
        require __DIR__ . '/../../../pages/dashboard/mahasiswa/pengajuan/index.php';
    }

    public function detail($params = [])
    {
        AuthMiddleware::handle('mahasiswa');
        $profil = $this->model->getProfilMahasiswa((int)($_SESSION['user']['id'] ?? 0));
        $id = (string)($params['slug'] ?? '');
        $pengajuanDetail = ($profil && ctype_digit($id)) ? $this->model->getDetail((int)$id, (int)$profil['mahasiswa_id']) : null;
        if (!$pengajuanDetail) { http_response_code(404); require __DIR__ . '/../../../pages/errors/404.php'; exit; }
        require __DIR__ . '/../../../pages/dashboard/mahasiswa/pengajuan/detail.php';
    }
}
