<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../models/Logbook.php';

class LogbookFileController
{
    public function signature($params = []): void
    {
        $this->serve('signature', (int) ($params['id'] ?? 0));
    }

    public function evidence($params = []): void
    {
        $this->serve('evidence', (int) ($params['id'] ?? 0));
    }

    private function serve(string $type, int $id): void
    {
        if ($id <= 0) {
            http_response_code(404);
            exit('File tidak ditemukan.');
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $role = (string) ($_SESSION['user']['role'] ?? '');
        if (!in_array($role, ['mahasiswa', 'mitra', 'dosen', 'tendik', 'koordinator_magang'], true)) {
            http_response_code(403);
            exit('Akses ditolak.');
        }

        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        $model = new Logbook();

        if ($type === 'signature') {
            $row = $model->getSignatureForUser($id, $userId, $role);
            $path = ($row && !empty($row['signature_path'])) ? $model->getSignatureAbsolutePath((string) $row['signature_path']) : null;
        } else {
            $row = $model->getEvidenceForUser($id, $userId, $role);
            $path = ($row && !empty($row['bukti_path'])) ? $model->getEvidenceAbsolutePath((string) $row['bukti_path']) : null;
        }

        if (!$row || !$path) {
            http_response_code(404);
            exit('File tidak ditemukan.');
        }

        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($path) ?: 'application/octet-stream';
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($path));
        header('Content-Disposition: inline; filename="logbook-' . $id . '.' . ($mime === 'application/pdf' ? 'pdf' : ($mime === 'image/jpeg' ? 'jpg' : 'png')) . '"');
        header('X-Content-Type-Options: nosniff');
        readfile($path);
        exit;
    }
}
