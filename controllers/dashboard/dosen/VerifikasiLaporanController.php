<?php

require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../function/Helpers.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../models/PemeriksaanLaporan.php';
require_once __DIR__ . '/../../../models/LaporanMagang.php';
require_once __DIR__ . '/../../../models/DashboardDosen.php';

class VerifikasiLaporanController
{
    public function index($params = []): void
    {
        AuthMiddleware::handle('dosen');

        $userId = (int) ($_SESSION['user']['id'] ?? 0);

        error_log('DEBUG VERIFIKASI LAPORAN');
        error_log('SESSION USER ID: ' . $userId);
        error_log('SESSION USER: ' . print_r($_SESSION['user'] ?? null, true));

        $model = new DashboardDosen();

        try {
            $laporan = $model->getLaporanUntukVerifikasi($userId);

            error_log('JUMLAH LAPORAN: ' . count($laporan));
            error_log('DATA LAPORAN: ' . print_r($laporan, true));

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

        $reports = $laporan;
        $active = 'verifikasi-laporan';

        require __DIR__ . '/../../../pages/dashboard/dosen/verifikasi-laporan/index.php';
    }

    public function detail($params = []): void
    {
        AuthMiddleware::handle('dosen');

        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        $laporanId = (int) ($params['id'] ?? 0);

        if ($userId < 1 || $laporanId < 1) {
            http_response_code(404);
            exit('Laporan tidak ditemukan.');
        }

        $model = new DashboardDosen();

        $laporan = $model->getDetailLaporanUntukVerifikasi(
            $userId,
            $laporanId
        );

        if (!$laporan) {
            http_response_code(404);
            require __DIR__ . '/../../../pages/errors/404.php';
            exit;
        }

        $base = url('/');
        $active = 'verifikasi-laporan';

        require __DIR__
            . '/../../../pages/dashboard/dosen/verifikasi-laporan/detail.php';
    }


    public function previewFile($params = []): void
    {
        AuthMiddleware::handle('dosen');

        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        $laporanId = (int) ($params['id'] ?? 0);

        if ($userId < 1 || $laporanId < 1) {
            http_response_code(404);
            exit('File tidak ditemukan.');
        }

        $model = new DashboardDosen();

        $laporan = $model->getDetailLaporanUntukVerifikasi(
            $userId,
            $laporanId
        );

        if (!$laporan || empty($laporan['file_path'])) {
            http_response_code(404);
            exit('File laporan tidak ditemukan.');
        }

        $relativePath = str_replace(
            '\\',
            '/',
            (string) $laporan['file_path']
        );

        // File hanya boleh berasal dari storage/laporan.
        if (
            !str_starts_with($relativePath, 'storage/laporan/')
            || str_contains($relativePath, "\0")
        ) {
            http_response_code(403);
            exit('Akses file tidak diizinkan.');
        }

        $projectRoot = realpath(dirname(__DIR__, 3));
        $storageRoot = realpath(
            $projectRoot . DIRECTORY_SEPARATOR . 'storage/laporan'
        );

        $filePath = realpath(
            $projectRoot . DIRECTORY_SEPARATOR
                . str_replace('/', DIRECTORY_SEPARATOR, $relativePath)
        );

        if (
            !$projectRoot
            || !$storageRoot
            || !$filePath
            || !is_file($filePath)
            || !str_starts_with(
                $filePath,
                $storageRoot . DIRECTORY_SEPARATOR
            )
        ) {
            http_response_code(404);
            exit('File laporan tidak ditemukan.');
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($filePath);

        if ($mimeType !== 'application/pdf') {
            http_response_code(415);
            exit('Format file bukan PDF.');
        }

        header('Content-Type: application/pdf');
        header('Content-Length: ' . filesize($filePath));
        header('Content-Disposition: inline; filename="laporan-magang.pdf"');
        header('X-Content-Type-Options: nosniff');
        header('Cache-Control: private, no-store, max-age=0');

        readfile($filePath);
        exit;
    }


    public function prosesVerifikasi($params = []): void
    {
        AuthMiddleware::handle('dosen');

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            http_response_code(405);
            header('Allow: POST');
            exit('Method Not Allowed');
        }

        if (!verifyCsrfToken()) {
            http_response_code(403);
            exit('Token CSRF tidak valid.');
        }

        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        $laporanId = (int) ($params['id'] ?? 0);

        $keputusan = trim((string) ($_POST['keputusan'] ?? ''));
        $catatan = trim((string) ($_POST['catatan'] ?? ''));

        if ($userId < 1 || $laporanId < 1) {
            http_response_code(404);
            exit('Laporan tidak ditemukan.');
        }

        $statusPemeriksaan = match ($keputusan) {
            'diterima' => PemeriksaanLaporan::DISETUJUI,
            'revisi' => PemeriksaanLaporan::PERLU_REVISI,
            'ditolak' => PemeriksaanLaporan::DITOLAK,
            default => null,
        };

        if ($statusPemeriksaan === null) {
            $_SESSION['flash_error'] = 'Pilihan keputusan tidak valid.';
            header('Location: ' . url(
                '/dashboard/dosen/verifikasi-laporan/detail/' . $laporanId
            ));
            exit;
        }

        if (
            in_array($keputusan, ['revisi', 'ditolak'], true)
            && $catatan === ''
        ) {
            $_SESSION['flash_error'] = 'Catatan wajib diisi untuk revisi atau penolakan.';
            header('Location: ' . url(
                '/dashboard/dosen/verifikasi-laporan/detail/' . $laporanId
            ));
            exit;
        }

        if (mb_strlen($catatan) > 5000) {
            $_SESSION['flash_error'] = 'Catatan maksimal 5.000 karakter.';
            header('Location: ' . url(
                '/dashboard/dosen/verifikasi-laporan/detail/' . $laporanId
            ));
            exit;
        }

        global $pdo;

        $dashboardModel = new DashboardDosen();
        $pemeriksaanModel = new PemeriksaanLaporan();

        try {
            $pdo->beginTransaction();

            // Pastikan laporan merupakan milik mahasiswa bimbingan dosen ini.
            $laporan = $dashboardModel->getDetailLaporanUntukVerifikasi(
                $userId,
                $laporanId
            );

            if (!$laporan) {
                throw new RuntimeException('Laporan tidak ditemukan atau bukan mahasiswa bimbingan Anda.');
            }

            // Hanya laporan yang sedang diajukan yang dapat diperiksa.
            if ($laporan['status_laporan'] !== LaporanMagang::SUBMITTED) {
                throw new RuntimeException('Laporan ini sudah tidak berada dalam antrean pemeriksaan dosen.');
            }

            // Ambil pemeriksaan dosen terbaru.
            $pemeriksaan = $pemeriksaanModel->getLatestByStage(
                $laporanId,
                PemeriksaanLaporan::DOSEN
            );

            // Jika belum ada pemeriksaan menunggu, buat pemeriksaan baru.
            // Ini juga mendukung laporan yang diajukan ulang setelah revisi.
            if (
                !$pemeriksaan
                || $pemeriksaan['status'] !== PemeriksaanLaporan::MENUNGGU
            ) {
                $pemeriksaanId = $pemeriksaanModel->createPending(
                    $laporanId,
                    $userId,
                    PemeriksaanLaporan::DOSEN
                );
            } else {
                $pemeriksaanId = (int) $pemeriksaan['id'];
            }

            // Simpan keputusan dosen.
            $berhasil = $pemeriksaanModel->decide(
                $pemeriksaanId,
                $userId,
                $statusPemeriksaan,
                $catatan !== '' ? $catatan : null
            );

            if (!$berhasil) {
                throw new RuntimeException('Pemeriksaan gagal disipmpan. Silakan coba kembali.');
            }

            // Petakan keputusan pemeriksaan ke status utama laporan.
            $statusLaporan = match ($keputusan) {
                'diterima' => LaporanMagang::REVIEWED_BY_LECTURER,
                'revisi' => LaporanMagang::NEEDS_REVISION,
                'ditolak' => LaporanMagang::REJECTED,
            };

            $berhasilUpdate = $dashboardModel->updateStatusLaporan(
                $userId,
                $laporanId,
                $statusLaporan
            );

            if (!$berhasilUpdate) {
                throw new RuntimeException('Status laporan gagal diperbarui.');
            }

            $pdo->commit();

            $_SESSION['flash_success'] = match ($keputusan) {
                'diterima' => 'Laporan berhasil disetujui dan diteruskan ke tahap berikutnya.',
                'revisi' => 'Laporan dikembalikan kepada mahasiswa untuk diperbaiki.',
                'ditolak' => 'Laporan berhasil ditolak.',
            };

            header('Location: ' . url('/dashboard/dosen/verifikasi-laporan'));
            exit;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            error_log(
                'Gagal memproses verifikasi laporan dosen: '
                    . $e->getMessage()
            );

            $_SESSION['flash_error'] = $e instanceof RuntimeException
                ? $e->getMessage()
                : 'Terjadi kesalahan saat menyimpan pemeriksaan laporan.';

            header('Location: ' . url(
                '/dashboard/dosen/verifikasi-laporan/detail/' . $laporanId
            ));
            exit;
        }
    }
}
