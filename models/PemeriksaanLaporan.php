<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class PemeriksaanLaporan
{
    public const DOSEN = 'dosen';
    public const KOORDINATOR = 'koordinator';
    public const TENDIK = 'tendik';

    public const MENUNGGU = 'menunggu';
    public const DISETUJUI = 'disetujui';
    public const PERLU_REVISI = 'perlu_revisi';
    public const DITOLAK = 'ditolak';

    public function getByLaporan(int $laporanId): array
    {
        global $pdo;

        $stmt = $pdo->prepare(
            "SELECT
                pl.*,
                u.name AS nama_pemeriksa,
                u.role AS role_pemeriksa
             FROM pemeriksaan_laporan pl
             INNER JOIN users u ON u.id = pl.pemeriksa_id
             WHERE pl.laporan_id = :laporan_id
             ORDER BY pl.tanggal_pemeriksaan DESC NULLS LAST, pl.id DESC"
        );

        $stmt->execute(['laporan_id' => $laporanId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLatestByStage(int $laporanId, string $stage): ?array
    {
        $this->assertStage($stage);

        global $pdo;

        $stmt = $pdo->prepare(
            "SELECT pl.*, u.name AS nama_pemeriksa
             FROM pemeriksaan_laporan pl
             INNER JOIN users u ON u.id = pl.pemeriksa_id
             WHERE pl.laporan_id = :laporan_id
               AND pl.tahap_pemeriksaan = :stage
             ORDER BY pl.id DESC
             LIMIT 1"
        );

        $stmt->execute([
            'laporan_id' => $laporanId,
            'stage' => $stage,
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function createPending(int $laporanId, int $pemeriksaId, string $stage): int
    {
        $this->assertStage($stage);

        global $pdo;

        $stmt = $pdo->prepare(
            "INSERT INTO pemeriksaan_laporan (
                laporan_id,
                pemeriksa_id,
                tahap_pemeriksaan,
                status
            ) VALUES (
                :laporan_id,
                :pemeriksa_id,
                :stage,
                'menunggu'
            )
            RETURNING id"
        );

        $stmt->execute([
            'laporan_id' => $laporanId,
            'pemeriksa_id' => $pemeriksaId,
            'stage' => $stage,
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function decide(
        int $pemeriksaanId,
        int $pemeriksaId,
        string $status,
        ?string $catatan = null
    ): bool {
        $this->assertStatus($status);

        global $pdo;

        $stmt = $pdo->prepare(
            "UPDATE pemeriksaan_laporan
             SET status = :status,
                 catatan = :catatan,
                 tanggal_pemeriksaan = CURRENT_TIMESTAMP
             WHERE id = :id
               AND pemeriksa_id = :pemeriksa_id
               AND status = 'menunggu'"
        );

        $stmt->execute([
            'status' => $status,
            'catatan' => $catatan !== null ? trim($catatan) : null,
            'id' => $pemeriksaanId,
            'pemeriksa_id' => $pemeriksaId,
        ]);

        return $stmt->rowCount() === 1;
    }

    private function assertStage(string $stage): void
    {
        if (!in_array($stage, [self::DOSEN, self::KOORDINATOR, self::TENDIK], true)) {
            throw new InvalidArgumentException('Tahap pemeriksaan tidak valid.');
        }
    }

    private function assertStatus(string $status): void
    {
        if (!in_array($status, [self::DISETUJUI, self::PERLU_REVISI, self::DITOLAK], true)) {
            throw new InvalidArgumentException('Status pemeriksaan tidak valid.');
        }
    }
}
