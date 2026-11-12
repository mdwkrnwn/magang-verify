<?php

require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../function/Helpers.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/DashboardDosen.php';

class VerifikasiLaporanController
{
    public function index($params = []): void
    {
        AuthMiddleware::handle('dosen');

        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        $model = new DashboardDosen();

        try {
            $laporan = $model->getLaporanUntukVerifikasi($userId);

            $statistics = [
                'total' => count($laporan),
                'belum_diperiksa' => 0,
                'diterima' => 0,
                'revisi' => 0,
                'ditolak' => 0,
            ];

            foreach ($laporan as $item) {
                $status = $item['status_pemeriksaan'] ?? '';

                if ($status === '') {
                    $statistics['belum_diperiksa']++;
                } elseif ($status === 'diterima') {
                    $statistics['diterima']++;
                } elseif ($status === 'revisi') {
                    $statistics['revisi']++;
                } elseif ($status === 'ditolak') {
                    $statistics['ditolak']++;
                }
            }
        } catch (Throwable $e) {
            error_log(
                'Gagal memuat laporan untuk verifikasi: ' . $e->getMessage()
            );

            http_response_code(500);

            $laporan = [];
            $statistics = [
                'total' => 0,
                'belum_diperiksa' => 0,
                'diterima' => 0,
                'revisi' => 0,
                'ditolak' => 0,
            ];
        }

        $active = 'verifikasi-laporan';

        require __DIR__ . '/../../../pages/dashboard/dosen/verifikasi-laporan/index.php';
    }
}