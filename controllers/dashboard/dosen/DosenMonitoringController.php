<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../function/Helpers.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/DashboardDosen.php';

class DosenMonitoringController
{
    public function index($params = []): void
    {
        AuthMiddleware::handle('dosen');
        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        $error = null;
        $model = new DashboardDosen();

        try {
            $placements = $model->getBimbingan($userId);
            $statistics = $model->getBimbinganStatistics($placements);
            $monitoringData = array_map(static function (array $placement): array {
                $start = strtotime((string) ($placement['tanggal_mulai'] ?? ''));
                $end = strtotime((string) ($placement['tanggal_selesai'] ?? ''));
                $name = (string) ($placement['mahasiswa_nama'] ?? 'Mahasiswa');
                $nameParts = preg_split('/\s+/', trim($name)) ?: [];

                return [
                    'id' => (int) $placement['penempatan_id'],
                    'nama' => $name,
                    'nim' => (string) ($placement['nim'] ?? ''),
                    'prodi' => (string) ($placement['program_studi'] ?? ''),
                    'mitra' => (string) ($placement['nama_perusahaan'] ?? ''),
                    'periode' => $start && $end
                        ? date('d M Y', $start) . ' – ' . date('d M Y', $end)
                        : 'Periode belum tersedia',
                    'status' => (string) $placement['status_penempatan'],
                    'status_label' => (string) $placement['status_label'],
                    'initial' => strtoupper(substr(implode('', array_map(static fn(string $part): string => $part[0] ?? '', array_slice($nameParts, 0, 2))), 0, 2)),
                ];
            }, $placements);
        } catch (Throwable $e) {
            error_log('Gagal memuat monitoring Dosen: ' . $e->getMessage());
            http_response_code(500);
            $monitoringData = [];
            $statistics = ['mahasiswa' => 0, 'berlangsung' => 0, 'persiapan' => 0, 'menunggu_penilaian' => 0, 'selesai' => 0];
            $error = 'Data monitoring belum dapat dimuat. Silakan coba muat ulang halaman.';
        }

        $active = 'monitoring';
        require __DIR__ . '/../../../pages/dashboard/dosen/monitoring/index.php';
    }

    public function detail(array $params = []): void
    {
        AuthMiddleware::handle('dosen');
        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        $placementId = filter_var($params['id'] ?? null, FILTER_VALIDATE_INT);
        if (!$placementId || $placementId < 1) {
            http_response_code(404);
            require __DIR__ . '/../../../pages/errors/404.php';
            return;
        }

        try {
            $placement = (new DashboardDosen())->getPlacementForLecturer($userId, $placementId);
        } catch (Throwable $e) {
            error_log('Gagal memuat detail monitoring Dosen: ' . $e->getMessage());
            http_response_code(500);
            exit('Detail monitoring belum dapat dimuat.');
        }

        if (!$placement) {
            http_response_code(404);
            require __DIR__ . '/../../../pages/errors/404.php';
            return;
        }

        $active = 'monitoring';
        require __DIR__ . '/../../../pages/dashboard/dosen/monitoring/detail.php';
    }
}
