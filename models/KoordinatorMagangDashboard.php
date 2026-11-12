<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class KoordinatorMagangDashboard
{
    public function getData(): array
    {
        global $pdo;

        $stmt = $pdo->query(
            "WITH counts AS (
                SELECT COUNT(*) AS total_pengajuan,
                       COUNT(DISTINCT mahasiswa_id) AS total_mahasiswa,
                       COUNT(*) FILTER (WHERE status_pendaftaran IN
                           ('diajukan', 'menunggu_persetujuan_dosen', 'menunggu_respons_mitra', 'seleksi', 'diterima', 'perlu_revisi')) AS pengajuan_aktif,
                       COUNT(*) FILTER (WHERE status_penempatan = 'berlangsung') AS penempatan_berlangsung,
                       COUNT(*) FILTER (WHERE status_penempatan = 'persiapan') AS penempatan_persiapan,
                       COUNT(*) FILTER (WHERE status_pendaftaran = 'diterima' AND penempatan_id IS NOT NULL AND dosen_pembimbing_id IS NULL) AS tanpa_pembimbing
                FROM public.view_rekap_magang_koordinator
             ),
             recent AS (
                SELECT r.mahasiswa_id, r.nim, r.nama_mahasiswa, r.program_studi,
                       r.nama_formasi, r.nama_perusahaan, r.status_pendaftaran,
                       r.status_penempatan, r.tanggal_pengajuan, r.penempatan_id,
                       r.dosen_pembimbing_id, r.nama_dosen_pembimbing, r.pendaftaran_id
                FROM public.view_rekap_magang_koordinator r
                ORDER BY r.tanggal_pengajuan DESC NULLS LAST, r.pendaftaran_id DESC
                LIMIT 8
             ),
             recent_enriched AS (
                SELECT r.mahasiswa_id, r.nim, r.nama_mahasiswa, r.program_studi,
                       r.nama_formasi, r.nama_perusahaan, r.status_pendaftaran,
                       r.status_penempatan, r.tanggal_pengajuan, r.penempatan_id,
                       r.dosen_pembimbing_id, r.nama_dosen_pembimbing, r.pendaftaran_id,
                       COALESCE(lm_stats.total_minggu, 0) AS total_minggu,
                       COALESCE(lm_stats.minggu_perlu_revisi, 0) AS minggu_perlu_revisi,
                       COALESCE(validasi_stats.validasi_menunggu, 0) AS validasi_menunggu
                FROM recent r
                LEFT JOIN LATERAL (
                    SELECT COUNT(*) AS total_minggu,
                           COUNT(*) FILTER (WHERE lm.status = 'perlu_revisi') AS minggu_perlu_revisi
                    FROM logbook_mingguan lm
                    WHERE lm.penempatan_id = r.penempatan_id
                ) lm_stats ON TRUE
                LEFT JOIN LATERAL (
                    SELECT COUNT(*) AS validasi_menunggu
                    FROM logbook_mingguan lm
                    INNER JOIN logbook_harian lh ON lh.logbook_id = lm.id
                    WHERE lm.penempatan_id = r.penempatan_id
                      AND lh.status_validasi = 'menunggu'
                ) validasi_stats ON TRUE
             )
             SELECT counts.total_pengajuan,
                    counts.total_mahasiswa,
                    counts.pengajuan_aktif,
                    counts.penempatan_berlangsung,
                    counts.penempatan_persiapan,
                    counts.tanpa_pembimbing,
                    (SELECT COALESCE(
                                json_agg(
                                    json_build_object(
                                        'mahasiswa_id', re.mahasiswa_id,
                                        'nim', re.nim,
                                        'nama_mahasiswa', re.nama_mahasiswa,
                                        'program_studi', re.program_studi,
                                        'nama_formasi', re.nama_formasi,
                                        'nama_perusahaan', re.nama_perusahaan,
                                        'status_pendaftaran', re.status_pendaftaran,
                                        'status_penempatan', re.status_penempatan,
                                        'tanggal_pengajuan', re.tanggal_pengajuan,
                                        'penempatan_id', re.penempatan_id,
                                        'dosen_pembimbing_id', re.dosen_pembimbing_id,
                                        'nama_dosen_pembimbing', re.nama_dosen_pembimbing,
                                        'total_minggu', re.total_minggu,
                                        'minggu_perlu_revisi', re.minggu_perlu_revisi,
                                        'validasi_menunggu', re.validasi_menunggu
                                    )
                                    ORDER BY re.tanggal_pengajuan DESC NULLS LAST, re.pendaftaran_id DESC
                                ),
                                '[]'::json
                            )
                     FROM recent_enriched re) AS recent_json
             FROM counts"
        );
        $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

        $recent = json_decode((string) ($row['recent_json'] ?? '[]'), true);

        return [
            'statistics' => [
                'total_pengajuan' => (int) ($row['total_pengajuan'] ?? 0),
                'total_mahasiswa' => (int) ($row['total_mahasiswa'] ?? 0),
                'pengajuan_aktif' => (int) ($row['pengajuan_aktif'] ?? 0),
                'penempatan_berlangsung' => (int) ($row['penempatan_berlangsung'] ?? 0),
                'penempatan_persiapan' => (int) ($row['penempatan_persiapan'] ?? 0),
                'tanpa_pembimbing' => (int) ($row['tanpa_pembimbing'] ?? 0),
            ],
            'recent' => is_array($recent) ? $recent : [],
        ];
    }
}
