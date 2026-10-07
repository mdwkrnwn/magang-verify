<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class LaporanMagang
{
    public const WEEKLY = 'laporan_mingguan';
    public const FINAL = 'laporan_akhir';

    public const DRAFT = 'draft';
    public const SUBMITTED = 'diajukan';
    public const REVIEWED_BY_LECTURER = 'diperiksa_dosen';
    public const NEEDS_REVISION = 'perlu_revisi';
    public const WAITING_VERIFICATION = 'menunggu_verifikasi';
    public const VERIFIED = 'terverifikasi';
    public const REJECTED = 'ditolak';

    /**
     * Mengambil seluruh penempatan milik mahasiswa beserta ringkasan laporan.
     */
    public function getPlacements(int $userId): array
    {
        global $pdo;

        $stmt = $pdo->prepare(
            "SELECT
                pm.id,
                pm.pendaftaran_id,
                pm.status,
                pm.tanggal_mulai,
                pm.tanggal_selesai,
                f.judul,
                m.nama_perusahaan,
                COALESCE(r.total_laporan, 0) AS total_laporan,
                COALESCE(r.total_mingguan, 0) AS total_mingguan,
                COALESCE(r.total_akhir, 0) AS total_akhir
            FROM penempatan_magang pm
            INNER JOIN pendaftaran_magang p
                ON p.id = pm.pendaftaran_id
            INNER JOIN profil_mahasiswa prof
                ON prof.id = p.mahasiswa_id
            INNER JOIN formasi_magang f
                ON f.id = p.formasi_id
            INNER JOIN mitra m
                ON m.id = f.mitra_id
            LEFT JOIN (
                SELECT
                    penempatan_id,
                    COUNT(*) AS total_laporan,
                    COUNT(*) FILTER (
                        WHERE jenis_laporan = 'laporan_mingguan'
                    ) AS total_mingguan,
                    COUNT(*) FILTER (
                        WHERE jenis_laporan = 'laporan_akhir'
                    ) AS total_akhir
                FROM laporan_magang
                GROUP BY penempatan_id
            ) r ON r.penempatan_id = pm.id
            WHERE prof.user_id = :user_id
            ORDER BY pm.tanggal_mulai DESC, pm.id DESC"
        );

        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil satu penempatan yang benar-benar dimiliki mahasiswa.
     */
    public function getPlacement(int $userId, ?int $placementId = null): ?array
    {
        global $pdo;

        $sql =
            "SELECT
                pm.id,
                pm.pendaftaran_id,
                pm.status,
                pm.tanggal_mulai,
                pm.tanggal_selesai,
                pm.dosen_pembimbing_id,
                f.judul,
                m.id AS mitra_id,
                m.nama_perusahaan,
                pd.user_id AS dosen_user_id
            FROM penempatan_magang pm
            INNER JOIN pendaftaran_magang p
                ON p.id = pm.pendaftaran_id
            INNER JOIN profil_mahasiswa prof
                ON prof.id = p.mahasiswa_id
            INNER JOIN formasi_magang f
                ON f.id = p.formasi_id
            INNER JOIN mitra m
                ON m.id = f.mitra_id
            LEFT JOIN profil_dosen pd
                ON pd.id = pm.dosen_pembimbing_id
            WHERE prof.user_id = :user_id";

        $params = ['user_id' => $userId];

        if ($placementId !== null) {
            $sql .= ' AND pm.id = :placement_id';
            $params['placement_id'] = $placementId;
        } else {
            $sql .= " ORDER BY
                CASE WHEN pm.status = 'berlangsung' THEN 0 ELSE 1 END,
                pm.tanggal_mulai DESC,
                pm.id DESC
                LIMIT 1";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Mengambil daftar laporan berdasarkan penempatan.
     */
    public function getByPlacement(int $placementId): array
    {
        global $pdo;

        $stmt = $pdo->prepare(
            "SELECT
                lm.*,
                tl.nama_template,
                tl.versi AS template_versi
            FROM laporan_magang lm
            LEFT JOIN template_laporan tl
                ON tl.id = lm.template_id
            WHERE lm.penempatan_id = :penempatan_id
            ORDER BY
                CASE WHEN lm.jenis_laporan = 'laporan_mingguan'
                     THEN 0 ELSE 1 END,
                lm.minggu_ke ASC NULLS LAST,
                lm.id ASC"
        );

        $stmt->execute(['penempatan_id' => $placementId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil detail laporan sekaligus memastikan kepemilikan melalui user mahasiswa.
     */
    public function getById(int $laporanId, int $userId): ?array
    {
        global $pdo;

        $stmt = $pdo->prepare(
            "SELECT
                lm.*,
                pm.tanggal_mulai,
                pm.tanggal_selesai,
                pm.status AS status_penempatan,
                f.judul AS posisi_magang,
                m.nama_perusahaan,
                tl.nama_template,
                tl.file_template,
                tl.versi AS template_versi
            FROM laporan_magang lm
            INNER JOIN penempatan_magang pm
                ON pm.id = lm.penempatan_id
            INNER JOIN pendaftaran_magang p
                ON p.id = pm.pendaftaran_id
            INNER JOIN profil_mahasiswa prof
                ON prof.id = p.mahasiswa_id
            INNER JOIN formasi_magang f
                ON f.id = p.formasi_id
            INNER JOIN mitra m
                ON m.id = f.mitra_id
            LEFT JOIN template_laporan tl
                ON tl.id = lm.template_id
            WHERE lm.id = :laporan_id
              AND prof.user_id = :user_id
            LIMIT 1"
        );

        $stmt->execute([
            'laporan_id' => $laporanId,
            'user_id' => $userId,
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Mengambil template aktif berdasarkan jenis laporan.
     */
    public function getActiveTemplate(string $jenisLaporan): ?array
    {
        global $pdo;

        $stmt = $pdo->prepare(
            "SELECT id, jenis_laporan, nama_template, file_template, versi
             FROM template_laporan
             WHERE jenis_laporan = :jenis_laporan
               AND status_aktif = TRUE
             LIMIT 1"
        );

        $stmt->execute(['jenis_laporan' => $jenisLaporan]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Mengecek apakah laporan akhir sudah boleh dibuat.
     * Aturan fitur: laporan akhir terbuka pada akhir periode magang.
     */
    public function isFinalReportOpen(array $placement): bool
    {
        $tanggalSelesai = (string) ($placement['tanggal_selesai'] ?? '');

        if ($tanggalSelesai === '') {
            return false;
        }

        return (new DateTimeImmutable('today')) >= new DateTimeImmutable($tanggalSelesai);
    }

    /**
     * Membuat record laporan baru.
     * File fisik dan histori versi pertama dibuat oleh layer upload/controller.
     */
    public function createDraft(
        int $placementId,
        string $jenisLaporan,
        ?int $mingguKe,
        string $judul,
        ?int $templateId = null
    ): int {
        global $pdo;

        $this->assertValidType($jenisLaporan, $mingguKe);

        $judul = trim($judul);
        if ($judul === '') {
            throw new InvalidArgumentException('Judul laporan wajib diisi.');
        }

        if (mb_strlen($judul) > 200) {
            throw new InvalidArgumentException('Judul laporan maksimal 200 karakter.');
        }

        $stmt = $pdo->prepare(
            "INSERT INTO laporan_magang (
                penempatan_id,
                template_id,
                jenis_laporan,
                minggu_ke,
                judul,
                status
            ) VALUES (
                :penempatan_id,
                :template_id,
                :jenis_laporan,
                :minggu_ke,
                :judul,
                'draft'
            )
            RETURNING id"
        );

        $stmt->execute([
            'penempatan_id' => $placementId,
            'template_id' => $templateId,
            'jenis_laporan' => $jenisLaporan,
            'minggu_ke' => $mingguKe,
            'judul' => $judul,
        ]);

        return (int) $stmt->fetchColumn();
    }

    /**
     * Menyimpan file terbaru pada laporan dan memperbarui metadata pengajuan.
     */
    public function submit(
        int $laporanId,
        int $userId,
        string $filePath,
        int $version = 1,
        ?string $statusKetepatanWaktu = null
    ): bool {
        global $pdo;

        if ($version < 1) {
            throw new InvalidArgumentException('Versi laporan tidak valid.');
        }

        $allowedTiming = [null, 'tepat_waktu', 'terlambat'];
        if (!in_array($statusKetepatanWaktu, $allowedTiming, true)) {
            throw new InvalidArgumentException('Status ketepatan waktu tidak valid.');
        }

        $stmt = $pdo->prepare(
            "UPDATE laporan_magang lm
             SET file_path = :file_path,
                 versi_terkini = :versi,
                 tanggal_unggah = CURRENT_TIMESTAMP,
                 status_ketepatan_waktu = :status_ketepatan_waktu,
                 status = 'diajukan',
                 diajukan_pada = CURRENT_TIMESTAMP,
                 updated_at = CURRENT_TIMESTAMP
             WHERE lm.id = :laporan_id
               AND EXISTS (
                   SELECT 1
                   FROM penempatan_magang pm
                   INNER JOIN pendaftaran_magang p
                       ON p.id = pm.pendaftaran_id
                   INNER JOIN profil_mahasiswa prof
                       ON prof.id = p.mahasiswa_id
                   WHERE pm.id = lm.penempatan_id
                     AND prof.user_id = :user_id
               )"
        );

        $stmt->execute([
            'file_path' => $filePath,
            'versi' => $version,
            'status_ketepatan_waktu' => $statusKetepatanWaktu,
            'laporan_id' => $laporanId,
            'user_id' => $userId,
        ]);

        return $stmt->rowCount() === 1;
    }

    public function assertValidType(string $jenisLaporan, ?int $mingguKe): void
    {
        if ($jenisLaporan === self::WEEKLY) {
            if ($mingguKe === null || $mingguKe < 1) {
                throw new InvalidArgumentException('Minggu laporan mingguan harus lebih dari 0.');
            }
            return;
        }

        if ($jenisLaporan === self::FINAL) {
            if ($mingguKe !== null) {
                throw new InvalidArgumentException('Laporan akhir tidak memiliki minggu ke.');
            }
            return;
        }

        throw new InvalidArgumentException('Jenis laporan tidak valid.');
    }
}
