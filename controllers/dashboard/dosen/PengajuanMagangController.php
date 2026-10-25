<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../function/Helpers.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/PersetujuanPengajuan.php';
require_once __DIR__ . '/../../../models/PengajuanDecision.php';

class PengajuanMagangController
{
    public function index($params = []): void
    {
        AuthMiddleware::handle('dosen');
        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        $model = new PersetujuanPengajuan();
        $error = null;
        $formValues = [];
        $success = ($_GET['success'] ?? '') === '1';
        try {
            $items = $model->getPending($userId);
            $statistics = $model->getStatistics($userId);
        } catch (PDOException $loadException) {
            error_log('Gagal memuat antrean persetujuan Dosen: ' . $loadException->getMessage());
            $items = [];
            $statistics = ['total' => 0, 'menunggu' => 0, 'disetujui' => 0, 'perlu_revisi' => 0];
            $error = 'Antrean persetujuan belum dapat dimuat. Silakan coba lagi nanti.';
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            if (!verifyCsrfToken()) {
                http_response_code(403);
                exit('Permintaan tidak valid. Silakan muat ulang halaman.');
            }
            $id = filter_var($_POST['application_id'] ?? null, FILTER_VALIDATE_INT);
            $decision = (string) ($_POST['decision'] ?? '');
            $submittedNote = $_POST['catatan_dosen'] ?? '';
            $note = is_string($submittedNote) ? trim($submittedNote) : '';
            if ($id && mb_strlen($note) <= 5000) {
                $formValues[(int) $id] = $note;
            }
            if (!$id || !in_array($decision, ['disetujui', 'ditolak'], true)) {
                $error = 'Data keputusan pengajuan tidak valid.';
            } elseif (mb_strlen($note) > 5000) {
                $error = 'Catatan keputusan maksimal 5.000 karakter.';
            } elseif ($decision === 'ditolak' && $note === '') {
                $error = 'Alasan penolakan wajib diisi.';
            } else {
                try {
                    $normalizedNote = PengajuanDecision::normalizeNote($decision, $note);
                    $model->respond($userId, $id, $decision, $normalizedNote);
                    header('Location: ' . url('/dashboard/dosen/pengajuan?success=1'));
                    exit;
                } catch (PDOException $e) {
                    error_log('Gagal menyimpan keputusan dosen: ' . $e->getMessage());
                    $error = 'Keputusan belum dapat disimpan. Silakan coba lagi.';
                } catch (Throwable $e) {
                    $error = $e->getMessage();
                }
                try {
                    $items = $model->getPending($userId);
                    $statistics = $model->getStatistics($userId);
                } catch (PDOException $refreshException) {
                    error_log('Gagal memuat ulang antrean persetujuan Dosen: ' . $refreshException->getMessage());
                    $items = [];
                    $error = 'Antrean persetujuan belum dapat dimuat ulang. Silakan muat ulang halaman.';
                }
            }
        }

        require __DIR__ . '/../../../pages/dashboard/dosen/pengajuanmagang/index.php';
    }

    public function document($params = []): never
    {
        $role = (string) ($_SESSION['user']['role'] ?? '');
        if (!in_array($role, ['dosen', 'mitra'], true)) {
            http_response_code(403);
            exit('Anda tidak memiliki akses ke dokumen ini.');
        }
        AuthMiddleware::handle($role);
        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        $id = (int) ($params['id'] ?? 0);
        $document = $id > 0 ? (new PersetujuanPengajuan())->getPendingDocument($id, $userId, $role) : null;
        if (!$document) {
            http_response_code(404);
            exit('Dokumen tidak ditemukan atau pengajuan bukan pada antrean persetujuan.');
        }

        $root = dirname(__DIR__, 3);
        $storageRoot = realpath($root . '/storage/pendaftaran');
        $path = realpath($root . '/' . ltrim((string) $document['path_file'], '/'));
        if ($storageRoot === false || $path === false
            || !str_starts_with($path, $storageRoot . DIRECTORY_SEPARATOR)
            || !is_file($path)) {
            http_response_code(404);
            exit('Dokumen tidak ditemukan.');
        }

        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($path);
        if ($mime !== 'application/pdf' || ($document['mime_type'] ?? '') !== 'application/pdf') {
            http_response_code(415);
            exit('Format dokumen tidak valid.');
        }

        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', basename((string) $document['nama_asli'])) ?: 'dokumen.pdf';
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($path));
        header('X-Content-Type-Options: nosniff');
        header('Cache-Control: private, no-store');
        readfile($path);
        exit;
    }
}
