<?php

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../models/FormasiMagang.php';

class FormasiMagangController
{
    private FormasiMagang $model;

    public function __construct()
    {
        $this->model = new FormasiMagang();
    }


    public function index($params = [])
    {
        $q = trim($_GET['q'] ?? '');
        $fLokasi = trim($_GET['lokasi'] ?? '');
        $fDurasi = trim($_GET['durasi'] ?? '');
        $fStatus = trim($_GET['status'] ?? '');
        $fSistemKerja = trim($_GET['sistem_kerja'] ?? '');
        $fTahun = trim($_GET['tahun_akademik'] ?? '');

        $perPage = 5;
        $page = max(1, (int) ($_GET['page'] ?? 1));

        $filter = [
            'q' => $q,
            'lokasi' => $fLokasi,
            'durasi' => $fDurasi,
            'status' => $fStatus,
            'sistem_kerja' => $fSistemKerja,
            'tahun_akademik' => $fTahun
        ];

        // Ambil data sesuai halaman.
        $result = $this->model->getPaginated(
            $filter,
            $page,
            $perPage
        );

        $tampil = $result['data'];
        $total = $result['total'];

        $pages = max(1, (int) ceil($total / $perPage));
        $page = min($page, $pages);

        // Jika halaman melebihi jumlah halaman, ambil ulang halaman terakhir.
        if ($page !== max(1, (int) ($_GET['page'] ?? 1))) {
            $result = $this->model->getPaginated(
                $filter,
                $page,
                $perPage
            );

            $tampil = $result['data'];
        }

        // Opsi filter tidak dipengaruhi filter durasi dan status.
        $filterOptions = [
            'q' => $q,
            'lokasi' => $fLokasi,
            'sistem_kerja' => $fSistemKerja,
            'tahun_akademik' => $fTahun
        ];

        $options = $this->model->getFilterOptions($filterOptions);

        $optLokasi = $options['lokasi'];
        $optTahun = $options['tahun_akademik'];

        $optDurasi = $this->model->getDurasiOptions($filterOptions);

        $optStatus = ['tersedia'];

        $url = fn($n) => '?' . http_build_query(
            array_merge($_GET, ['page' => $n])
        );

        $perf = microtime(true);

        $result = $this->model->getPaginated(
            $filter,
            $page,
            $perPage
        );

        error_log(sprintf(
            '[FORMASI PERF] getPaginated: %.3f s',
            microtime(true) - $perf
        ));

        $tampil = $result['data'];
        $total = $result['total'];

        $perf = microtime(true);

        $options = $this->model->getFilterOptions($filterOptions);

        error_log(sprintf(
            '[FORMASI PERF] getFilterOptions: %.3f s',
            microtime(true) - $perf
        ));

        $perf = microtime(true);

        $optDurasi = $this->model->getDurasiOptions($filterOptions);

        error_log(sprintf(
            '[FORMASI PERF] getDurasiOptions: %.3f s',
            microtime(true) - $perf
        ));

        require __DIR__ . '/../../../pages/dashboard/mahasiswa/formasi-magang/index.php';
    }


    public function detail($params = [])
    {
        $formasi = $this->model->getBySlug(
            (string) ($params['slug'] ?? '')
        );

        if (!$formasi) {
            http_response_code(404);
            require __DIR__ . '/../../../pages/errors/404.php';
            exit;
        }

        require __DIR__ . '/../../../pages/dashboard/mahasiswa/formasi-magang/detail.php';
    }
}
