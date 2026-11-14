
<?php


require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../function/Helpers.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';

require_once __DIR__ . '/../../../models/LaporanMagang.php';
require_once __DIR__ . '/../../../models/PemeriksaanLaporan.php';

class TendikVerifikasiLaporanController
{
    /**
     * Antrean laporan.
     */
    public function index($params = []): void
    {
        AuthMiddleware::handle('tendik');

        $userId = (int) ($_SESSION['user']['id'] ?? 0);

        $laporanModel = new LaporanMagang();
        $items = $laporanModel->getQueueForTendik($userId);

        require __DIR__ . '/../../../pages/dashboard/tendik/verifikasi-laporan/index.php';
    }

    /**
     * Detail laporan.
     */
    public function detail($params = []): void
    {
        AuthMiddleware::handle('tendik');

        $laporanId = (int) ($params['id'] ?? 0);

        if ($laporanId < 1) {
            http_response_code(404);
            exit('Laporan tidak ditemukan.');
        }

        $laporanModel = new LaporanMagang();
        $laporan = $laporanModel->getDetailForTendik($laporanId);

        if (!$laporan) {
            http_response_code(404);
            require __DIR__ . '/../../../pages/errors/404.php';
            exit;
        }

        $base = url('/');
        $active = 'verifikasi-laporan';

        require __DIR__ . '/../../../pages/dashboard/tendik/verifikasi-laporan/detail.php';
    }

    /**
     * Pratinjau PDF laporan.
     */
    public function previewFile($params = []): void
    {
        AuthMiddleware::handle('tendik');

        $laporanId = (int) ($params['id'] ?? 0);

        if ($laporanId < 1) {
            http_response_code(404);
            exit('File tidak ditemukan.');
        }

        $laporanModel = new LaporanMagang();
        $laporan = $laporanModel->getDetailForTendik($laporanId);

        if (!$laporan || empty($laporan['file_path'])) {
            http_response_code(404);
            exit('File laporan tidak ditemukan.');
        }

        $relativePath = str_replace(
            '\\',
            '/',
            (string) $laporan['file_path']
        );

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

    /**
     * Menyimpan keputusan pemeriksaan Tendik.
     */
    public function prosesVerifikasi($params = []): void
    {
        AuthMiddleware::handle('tendik');

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

        $detailUrl = url(
            '/dashboard/tendik/verifikasi/laporan/detail/' . $laporanId
        );

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
            header('Location: ' . $detailUrl);
            exit;
        }

        if (
            in_array($keputusan, ['revisi', 'ditolak'], true)
            && $catatan === ''
        ) {
            $_SESSION['flash_error'] =
                'Catatan wajib diisi untuk revisi atau penolakan.';

            header('Location: ' . $detailUrl);
            exit;
        }

        if (mb_strlen($catatan) > 5000) {
            $_SESSION['flash_error'] = 'Catatan maksimal 5.000 karakter.';
            header('Location: ' . $detailUrl);
            exit;
        }

        global $pdo;

        $laporanModel = new LaporanMagang();
        $pemeriksaanModel = new PemeriksaanLaporan();

        try {
            $pdo->beginTransaction();

            // Pastikan laporan masih berada dalam antrean Tendik.
            $laporan = $laporanModel->getDetailForTendik($laporanId);

            if (!$laporan) {
                throw new RuntimeException(
                    'Laporan tidak ditemukan atau sudah diproses.'
                );
            }

            // Ambil pemeriksaan Tendik terbaru.
            $pemeriksaan = $pemeriksaanModel->getLatestByStage(
                $laporanId,
                PemeriksaanLaporan::TENDIK
            );

            // Buat pemeriksaan baru jika belum ada,
            // sudah selesai, atau masih dimiliki pemeriksa lain.
            if (
                !$pemeriksaan
                || $pemeriksaan['status'] !== PemeriksaanLaporan::MENUNGGU
                || (int) $pemeriksaan['pemeriksa_id'] !== $userId
            ) {
                $pemeriksaanId = $pemeriksaanModel->createPending(
                    $laporanId,
                    $userId,
                    PemeriksaanLaporan::TENDIK
                );
            } else {
                $pemeriksaanId = (int) $pemeriksaan['id'];
            }

            // Simpan keputusan Tendik.
            $berhasil = $pemeriksaanModel->decide(
                $pemeriksaanId,
                $userId,
                $statusPemeriksaan,
                $catatan !== '' ? $catatan : null
            );

            if (!$berhasil) {
                throw new RuntimeException(
                    'Pemeriksaan gagal disimpan. Silakan coba kembali.'
                );
            }

            // Petakan keputusan ke status utama laporan.
            $statusLaporan = match ($keputusan) {
                'diterima' => LaporanMagang::VERIFIED,
                'revisi' => LaporanMagang::NEEDS_REVISION,
                'ditolak' => LaporanMagang::REJECTED,
            };

            $berhasilUpdate = $laporanModel->updateStatusByTendik(
                $laporanId,
                $statusLaporan
            );

            if (!$berhasilUpdate) {
                throw new RuntimeException(
                    'Status laporan gagal diperbarui.'
                );
            }

            $pdo->commit();

            $_SESSION['flash_success'] = match ($keputusan) {
                'diterima' => 'Laporan berhasil diverifikasi.',
                'revisi' => 'Laporan dikembalikan kepada mahasiswa untuk diperbaiki.',
                'ditolak' => 'Laporan berhasil ditolak.',
            };

            header('Location: ' . url('/dashboard/tendik/verifikasi/laporan'));
            exit;

        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            error_log(
                'Gagal memproses verifikasi laporan Tendik: '
                . $e->getMessage()
            );

            $_SESSION['flash_error'] = $e instanceof RuntimeException
                ? $e->getMessage()
                : 'Terjadi kesalahan saat menyimpan pemeriksaan laporan.';

            header('Location: ' . $detailUrl);
            exit;
        }
    }
}
