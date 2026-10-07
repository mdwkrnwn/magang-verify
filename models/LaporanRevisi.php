<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class LaporanRevisi
{
    public function getByLaporan(int $laporanId): array
    {
        global $pdo;

        $stmt = $pdo->prepare(
            "SELECT
                lr.*,
                u.name AS nama_pengunggah
             FROM laporan_revisi lr
             LEFT JOIN users u ON u.id = lr.diunggah_oleh
             WHERE lr.laporan_id = :laporan_id
             ORDER BY lr.nomor_versi DESC, lr.id DESC"
        );

        $stmt->execute(['laporan_id' => $laporanId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLatest(int $laporanId): ?array
    {
        global $pdo;

        $stmt = $pdo->prepare(
            "SELECT lr.*, u.name AS nama_pengunggah
             FROM laporan_revisi lr
             LEFT JOIN users u ON u.id = lr.diunggah_oleh
             WHERE lr.laporan_id = :laporan_id
             ORDER BY lr.nomor_versi DESC, lr.id DESC
             LIMIT 1"
        );

        $stmt->execute(['laporan_id' => $laporanId]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function nextVersion(int $laporanId): int
    {
        global $pdo;

        $stmt = $pdo->prepare(
            'SELECT COALESCE(MAX(nomor_versi), 0) + 1 FROM laporan_revisi WHERE laporan_id = :laporan_id'
        );
        $stmt->execute(['laporan_id' => $laporanId]);

        return (int) $stmt->fetchColumn();
    }

    public function create(
        int $laporanId,
        int $nomorVersi,
        string $filePath,
        ?string $ringkasan,
        ?string $catatanRevisi,
        int $userId
    ): int {
        if ($nomorVersi < 1) {
            throw new InvalidArgumentException('Nomor versi tidak valid.');
        }

        $filePath = trim($filePath);
        if ($filePath === '') {
            throw new InvalidArgumentException('Path file laporan wajib diisi.');
        }

        global $pdo;

        $stmt = $pdo->prepare(
            "INSERT INTO laporan_revisi (
                laporan_id,
                nomor_versi,
                file_path,
                ringkasan,
                catatan_revisi,
                diunggah_oleh
            ) VALUES (
                :laporan_id,
                :nomor_versi,
                :file_path,
                :ringkasan,
                :catatan_revisi,
                :diunggah_oleh
            )
            RETURNING id"
        );

        $stmt->execute([
            'laporan_id' => $laporanId,
            'nomor_versi' => $nomorVersi,
            'file_path' => $filePath,
            'ringkasan' => $ringkasan !== null ? trim($ringkasan) : null,
            'catatan_revisi' => $catatanRevisi !== null ? trim($catatanRevisi) : null,
            'diunggah_oleh' => $userId,
        ]);

        return (int) $stmt->fetchColumn();
    }
}
