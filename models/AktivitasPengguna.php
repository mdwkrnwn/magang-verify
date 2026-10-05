<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class AktivitasPengguna
{
    public function log(
        int $userId,
        string $aksi,
        string $deskripsi,
        ?string $modul = null,
        ?string $referensiTabel = null,
        ?int $referensiId = null
    ): int {
        global $pdo;

        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        if (!is_string($ip) || filter_var($ip, FILTER_VALIDATE_IP) === false) {
            $ip = null;
        }

        try {
            $stmt = $pdo->prepare(
                'INSERT INTO aktivitas_pengguna '
                . '(user_id, aksi, deskripsi, modul, referensi_tabel, referensi_id, ip_address) '
                . 'VALUES (:user_id, :aksi, :deskripsi, :modul, :referensi_tabel, :referensi_id, :ip_address) '
                . 'RETURNING id'
            );

            $stmt->execute([
                'user_id' => $userId,
                'aksi' => mb_substr(trim($aksi), 0, 100),
                'deskripsi' => trim($deskripsi),
                'modul' => $modul !== null ? mb_substr(trim($modul), 0, 100) : null,
                'referensi_tabel' => $referensiTabel !== null ? mb_substr(trim($referensiTabel), 0, 100) : null,
                'referensi_id' => $referensiId,
                'ip_address' => $ip,
            ]);

            return (int) $stmt->fetchColumn();
        } catch (Throwable $e) {
            // Kegagalan pencatatan aktivitas tidak boleh membatalkan aksi bisnis utama.
            error_log('Gagal mencatat aktivitas pengguna: ' . $e->getMessage());
            return 0;
        }
    }
}
