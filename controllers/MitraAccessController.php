<?php

declare(strict_types=1);

require_once __DIR__ . '/../function/Helpers.php';
require_once __DIR__ . '/../models/GenerateLoginMitra.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/MitraSessionManager.php';

final class MitraAccessController
{
    public function access($params = []): void
    {
        global $pdo;

        // Ambil token dari parameter route
        $token = (string) ($params['token'] ?? '');

        if ($token === '') {
            http_response_code(400);
            exit('Token akses tidak valid.');
        }

        try {
            $model = new GenerateLoginMitra();

            // Validasi token akses
            $mitra = $model->validateToken($token);

            if (!$mitra) {
                http_response_code(403);
                exit('Link akses tidak valid, telah dicabut, atau Mitra tidak aktif.');
            }

            // Buat session Mitra
            MitraSessionManager::createSession(
                $pdo,
                (int) $mitra['mitra_id'],
                (string) ($mitra['nama_perusahaan'] ?? ''),
                (int) $mitra['access_id']
            );

            // Redirect ke dashboard Mitra
            header('Location: ' . url('/dashboard/mitra'));
            exit;
        } catch (Throwable $e) {
            error_log(
                'Mitra Access Error: ' .
                    $e->getMessage() .
                    ' | File: ' .
                    $e->getFile() .
                    ' | Line: ' .
                    $e->getLine()
            );

            http_response_code(500);

            echo '<pre>';
            echo 'ERROR: ' . htmlspecialchars($e->getMessage());
            echo "\nFILE: " . htmlspecialchars($e->getFile());
            echo "\nLINE: " . $e->getLine();
            echo '</pre>';

            exit;
        }
    }
}
