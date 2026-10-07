<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class TemplateLaporan
{
    public function getActiveByType(string $jenisLaporan): ?array
    {
        global $pdo;

        $stmt = $pdo->prepare(
            "SELECT id, jenis_laporan, nama_template, file_template, versi,
                    status_aktif, created_at, updated_at
             FROM template_laporan
             WHERE jenis_laporan = :jenis_laporan
               AND status_aktif = TRUE
             LIMIT 1"
        );

        $stmt->execute(['jenis_laporan' => $jenisLaporan]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getById(int $id): ?array
    {
        global $pdo;

        $stmt = $pdo->prepare(
            'SELECT * FROM template_laporan WHERE id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getAllActive(): array
    {
        global $pdo;

        $stmt = $pdo->query(
            "SELECT id, jenis_laporan, nama_template, file_template, versi,
                    status_aktif, created_at, updated_at
             FROM template_laporan
             WHERE status_aktif = TRUE
             ORDER BY jenis_laporan ASC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
