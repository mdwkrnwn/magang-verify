<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class KoordinatorMagangDashboard
{
    public function getData(): array
    {
        global $pdo;

        $stmt = $pdo->query(
            "SELECT COUNT(*) AS total_pengajuan,
                    COUNT(DISTINCT mahasiswa_id) AS total_mahasiswa,
                    COUNT(*) FILTER (WHERE status_pendaftaran IN
                        ('diajukan', 'menunggu_persetujuan_dosen', 'menunggu_respons_mitra', 'seleksi', 'diterima', 'perlu_revisi')) AS pengajuan_aktif,
                    COUNT(*) FILTER (WHERE status_penempatan = 'berlangsung') AS penempatan_berlangsung,
                    COUNT(*) FILTER (WHERE status_penempatan = 'persiapan') AS penempatan_persiapan,
                    COUNT(*) FILTER (WHERE status_pendaftaran = 'diterima' AND penempatan_id IS NOT NULL AND dosen_pembimbing_id IS NULL) AS tanpa_pembimbing
             FROM public.view_rekap_magang_koordinator"
        );
        $counts = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

        $rows = $pdo->query(
            "WITH minggu AS (
                SELECT penempatan_id,
                       COUNT(*) AS total_minggu,
                       COUNT(*) FILTER (WHERE status = 'perlu_revisi') AS minggu_perlu_revisi
                FROM logbook_mingguan
                GROUP BY penempatan_id
             ), validasi AS (
                SELECT lm.penempatan_id,
                       COUNT(lh.id) FILTER (WHERE lh.status_validasi = 'menunggu') AS validasi_menunggu
                FROM logbook_mingguan lm
                LEFT JOIN logbook_harian lh ON lh.logbook_id = lm.id
                GROUP BY lm.penempatan_id
             )
             SELECT r.mahasiswa_id, r.nim, r.nama_mahasiswa, r.program_studi,
                    r.nama_formasi, r.nama_perusahaan, r.status_pendaftaran,
                    r.status_penempatan, r.tanggal_pengajuan, r.penempatan_id,
                    r.dosen_pembimbing_id, r.nama_dosen_pembimbing,
                    COALESCE(m.total_minggu, 0) AS total_minggu,
                    COALESCE(m.minggu_perlu_revisi, 0) AS minggu_perlu_revisi,
                    COALESCE(v.validasi_menunggu, 0) AS validasi_menunggu
             FROM public.view_rekap_magang_koordinator r
             LEFT JOIN minggu m ON m.penempatan_id = r.penempatan_id
             LEFT JOIN validasi v ON v.penempatan_id = r.penempatan_id
             ORDER BY r.tanggal_pengajuan DESC NULLS LAST, r.pendaftaran_id DESC
             LIMIT 8"
        )->fetchAll(PDO::FETCH_ASSOC);

        return [
            'statistics' => array_map('intval', $counts),
            'recent' => $rows,
        ];
    }
}
