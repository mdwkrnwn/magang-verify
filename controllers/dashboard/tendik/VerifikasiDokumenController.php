<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../function/Helpers.php';
require_once __DIR__ . '/../../../function/PrivateStorage.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/Portofolio.php';
require_once __DIR__ . '/../../../models/Sertifikat.php';
require_once __DIR__ . '/../../../models/AktivitasPengguna.php';

final class TendikVerifikasiDokumenController
{
    private function actorId(): int
    {
        AuthMiddleware::handle('tendik');
        $actorId = (int) ($_SESSION['user']['id'] ?? 0);
        if ($actorId < 1) {
            http_response_code(401);
            exit('Sesi pengguna tidak valid.');
        }
        return $actorId;
    }

    public function index(): void
    {
        $this->actorId();
        $portfolioStatus = trim((string) ($_GET['portfolio_status'] ?? 'belum_diverifikasi'));
        $certificateStatus = trim((string) ($_GET['certificate_status'] ?? 'belum_terverifikasi'));
        $page = max(1, (int) (filter_var($_GET['page'] ?? 1, FILTER_VALIDATE_INT) ?: 1));
        $portfolios = (new Portofolio())->getVerificationQueue($portfolioStatus, $page);
        $certificates = (new Sertifikat())->getVerificationQueue($certificateStatus, $page);
        $error = null;
        require __DIR__ . '/../../../pages/dashboard/tendik/verifikasi/index.php';
    }

    public function portfolioQueue(): void
    {
        $this->actorId();
        $status = trim((string) ($_GET['status'] ?? 'belum_diverifikasi'));
        $page = max(1, (int) (filter_var($_GET['page'] ?? 1, FILTER_VALIDATE_INT) ?: 1));
        $portfolios = (new Portofolio())->getVerificationQueue($status, $page);
        require __DIR__ . '/../../../pages/dashboard/tendik/verifikasi/portofolio-index.php';
    }

    public function certificateQueue(): void
    {
        $this->actorId();
        $status = trim((string) ($_GET['status'] ?? 'belum_terverifikasi'));
        $page = max(1, (int) (filter_var($_GET['page'] ?? 1, FILTER_VALIDATE_INT) ?: 1));
        $certificates = (new Sertifikat())->getVerificationQueue($status, $page);
        require __DIR__ . '/../../../pages/dashboard/tendik/verifikasi/sertifikat-index.php';
    }

    public function portfolioDetail(array $params = []): void
    {
        $this->actorId();
        $id = $this->validatedId($params['id'] ?? null);
        $item = (new Portofolio())->getForVerification($id);
        if (!$item) {
            $this->notFound();
        }
        $history = $this->portfolioHistory($id);
        $error = null;
        require __DIR__ . '/../../../pages/dashboard/tendik/verifikasi/portofolio.php';
    }

    public function certificateDetail(array $params = []): void
    {
        $this->actorId();
        $id = $this->validatedId($params['id'] ?? null);
        $item = (new Sertifikat())->getForVerification($id);
        if (!$item) {
            $this->notFound();
        }
        $history = $this->certificateHistory($id);
        $error = null;
        require __DIR__ . '/../../../pages/dashboard/tendik/verifikasi/sertifikat.php';
    }

    public function decidePortfolio(array $params = []): never
    {
        $actorId = $this->actorId();
        $this->requirePostAndCsrf();
        $id = $this->validatedId($params['id'] ?? null);
        $decision = (string) ($_POST['decision'] ?? '');
        $note = is_string($_POST['note'] ?? null) ? $_POST['note'] : '';
        try {
            (new Portofolio())->verify($id, $actorId, $decision, $note);
            (new AktivitasPengguna())->log($actorId, 'Portofolio diverifikasi', 'Keputusan ' . $decision . ' dicatat untuk portofolio #' . $id . '.', 'verifikasi', 'portofolios', $id);
            header('Location: ' . url('/dashboard/tendik/verifikasi?success=portofolio'));
            exit;
        } catch (PDOException $exception) {
            error_log('Gagal memverifikasi portofolio: ' . $exception->getMessage());
            http_response_code(500);
            exit('Keputusan portofolio gagal disimpan.');
        } catch (Throwable $exception) {
            http_response_code($exception instanceof InvalidArgumentException ? 422 : 409);
            exit($exception->getMessage());
        }
    }

    public function decideCertificate(array $params = []): never
    {
        $actorId = $this->actorId();
        $this->requirePostAndCsrf();
        $id = $this->validatedId($params['id'] ?? null);
        $decision = (string) ($_POST['decision'] ?? '');
        $note = is_string($_POST['note'] ?? null) ? $_POST['note'] : '';
        try {
            (new Sertifikat())->verify($id, $actorId, $decision, $note);
            (new AktivitasPengguna())->log($actorId, 'Sertifikat diverifikasi', 'Keputusan ' . $decision . ' untuk sertifikat #' . $id . '. Catatan: ' . trim($note), 'verifikasi', 'sertifikat', $id);
            header('Location: ' . url('/dashboard/tendik/verifikasi?success=sertifikat'));
            exit;
        } catch (PDOException $exception) {
            error_log('Gagal memverifikasi sertifikat: ' . $exception->getMessage());
            http_response_code(500);
            exit('Keputusan sertifikat gagal disimpan.');
        } catch (Throwable $exception) {
            http_response_code($exception instanceof InvalidArgumentException ? 422 : 409);
            exit($exception->getMessage());
        }
    }

    public function portfolioCover(array $params = []): never
    {
        $this->actorId();
        $id = $this->validatedId($params['id'] ?? null);
        $stmt = $GLOBALS['pdo']->prepare('SELECT gambar_sampul FROM portofolios WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $relativePath = $stmt->fetchColumn();
        $path = resolvePrivateStorageFile(dirname(__DIR__, 3), (string) $relativePath, ['uploads/portofolio']);
        if ($path === null) {
            $this->notFound();
        }
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($path);
        if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true) || @getimagesize($path) === false) {
            http_response_code(415);
            exit('Format gambar tidak didukung.');
        }
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . (string) filesize($path));
        header('Cache-Control: private, no-store, max-age=0');
        header('X-Content-Type-Options: nosniff');
        readfile($path);
        exit;
    }

    public function portfolioFile(array $params = []): never
    {
        $this->actorId();
        $id = $this->validatedId($params['id'] ?? null);
        $fileId = $this->validatedId($params['file_id'] ?? null);
        $stmt = $GLOBALS['pdo']->prepare(
            'SELECT b.file_path, b.nama_berkas, b.tipe_mime
             FROM berkas_portofolio b
             INNER JOIN portofolios p ON p.id = b.portofolio_id
             WHERE p.id = :portfolio_id AND b.id = :file_id LIMIT 1'
        );
        $stmt->execute(['portfolio_id' => $id, 'file_id' => $fileId]);
        $metadata = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$metadata) {
            $this->notFound();
        }
        $path = resolvePrivateStorageFile(dirname(__DIR__, 3), (string) $metadata['file_path'], ['uploads/portofolio']);
        if ($path === null) {
            $this->notFound();
        }
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($path);
        if (!in_array($mime, ['application/pdf', 'image/jpeg', 'image/png', 'image/webp'], true)) {
            http_response_code(415);
            exit('Format berkas tidak didukung.');
        }
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . (string) filesize($path));
        header('Content-Disposition: inline; filename="bukti-portofolio-' . $fileId . '"');
        header('Cache-Control: private, no-store, max-age=0');
        header('X-Content-Type-Options: nosniff');
        readfile($path);
        exit;
    }

    public function certificateFile(array $params = []): never
    {
        $this->actorId();
        $id = $this->validatedId($params['id'] ?? null);
        $model = new Sertifikat();
        $item = $model->getForVerification($id);
        if (!$item) {
            $this->notFound();
        }
        $path = resolvePrivateStorageFile(dirname(__DIR__, 3), (string) $item['file_path'], ['uploads/sertifikat']);
        if ($path === null) {
            $this->notFound();
        }
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($path);
        if (!in_array($mime, ['application/pdf', 'image/jpeg', 'image/png', 'image/webp'], true)) {
            http_response_code(415);
            exit('Format berkas tidak didukung.');
        }
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . (string) filesize($path));
        header('Content-Disposition: inline; filename="sertifikat-' . $id . '"');
        header('Cache-Control: private, no-store, max-age=0');
        header('X-Content-Type-Options: nosniff');
        readfile($path);
        exit;
    }

    private function certificateHistory(int $id): array
    {
        $stmt = $GLOBALS['pdo']->prepare(
            "SELECT a.aksi, a.deskripsi, a.created_at, u.name AS petugas
             FROM aktivitas_pengguna a
             LEFT JOIN users u ON u.id = a.user_id
             WHERE a.referensi_tabel = 'sertifikat' AND a.referensi_id = :id
               AND a.modul = 'verifikasi'
             ORDER BY a.created_at DESC, a.id DESC"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function portfolioHistory(int $id): array
    {
        $stmt = $GLOBALS['pdo']->prepare(
            'SELECT h.status_sebelumnya, h.status_baru, h.catatan, h.waktu_verifikasi, u.name AS petugas
             FROM riwayat_verifikasi_portofolio h
             LEFT JOIN users u ON u.id = h.diverifikasi_oleh
             WHERE h.portofolio_id = :id ORDER BY h.waktu_verifikasi DESC, h.id DESC'
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function requirePostAndCsrf(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            http_response_code(405);
            header('Allow: POST');
            exit('Method Not Allowed');
        }
        if (!verifyCsrfToken()) {
            http_response_code(403);
            exit('Token CSRF tidak valid.');
        }
    }

    private function validatedId(mixed $value): int
    {
        $id = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($id === false) {
            $this->notFound();
        }
        return (int) $id;
    }

    private function notFound(): never
    {
        http_response_code(404);
        exit('Dokumen tidak ditemukan.');
    }
}
