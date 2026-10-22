-- ============================================================
-- QUERY CTE: REKAP LOGBOOK MAHASISWA UNTUK DASHBOARD KOORDINATOR
-- Database: PostgreSQL
-- Sumber struktur: migrations 003, 004, 005, dan 020-023.
--
-- Tujuan:
-- 1. Rekap jumlah logbook mingguan berdasarkan status.
-- 2. Rekap aktivitas harian dan validasi ketidakhadiran.
-- 3. Menampilkan aktivitas dari revisi/versi terkini.
-- 4. Dapat dipakai sebagai data tabel monitoring Koordinator Magang.
--
-- Catatan:
-- - Ini query CTE biasa, bukan objek database permanen.
-- - Status logbook mingguan mengikuti CHECK constraint pada tabel.
-- - Logbook harian dengan status_validasi='menunggu' umumnya berkaitan
--   dengan validasi ketidakhadiran; data ini dipisahkan dari status mingguan.
-- ============================================================

WITH rekap_mingguan AS (
    SELECT
        lm.penempatan_id,
        COUNT(*) AS total_logbook_mingguan,
        COUNT(*) FILTER (WHERE lm.status = 'draft') AS logbook_draft,
        COUNT(*) FILTER (WHERE lm.status = 'diajukan') AS logbook_diajukan,
        COUNT(*) FILTER (
            WHERE lm.status IN ('menunggu_mitra', 'menunggu_dosen', 'menunggu_verifikasi')
        ) AS logbook_menunggu_persetujuan,
        COUNT(*) FILTER (WHERE lm.status = 'perlu_revisi') AS logbook_perlu_revisi,
        COUNT(*) FILTER (WHERE lm.status = 'disetujui') AS logbook_disetujui,
        COUNT(*) FILTER (WHERE lm.status = 'ditolak') AS logbook_ditolak
    FROM logbook_mingguan lm
    GROUP BY lm.penempatan_id
),
rekap_harian AS (
    SELECT
        lm.penempatan_id,
        COUNT(lh.id) AS total_catatan_harian,
        COUNT(lh.id) FILTER (WHERE lh.status_kehadiran = 'hadir') AS total_hari_hadir,
        COUNT(lh.id) FILTER (WHERE lh.status_kehadiran = 'tidak_hadir') AS total_hari_tidak_hadir,
        COUNT(lh.id) FILTER (WHERE lh.status_validasi = 'menunggu') AS validasi_harian_menunggu,
        COUNT(lh.id) FILTER (WHERE lh.status_validasi = 'disetujui') AS validasi_harian_disetujui,
        COUNT(lh.id) FILTER (WHERE lh.status_validasi = 'ditolak') AS validasi_harian_ditolak
    FROM logbook_mingguan lm
    LEFT JOIN logbook_harian lh ON lh.logbook_id = lm.id
    GROUP BY lm.penempatan_id
),
aktivitas_terkini AS (
    SELECT DISTINCT ON (lm.penempatan_id)
        lm.penempatan_id,
        lm.id AS logbook_id_terkini,
        lm.minggu_ke AS minggu_terakhir,
        lm.status AS status_logbook_terakhir,
        lr.aktivitas AS aktivitas_minggu_terakhir,
        lr.hasil_pekerjaan AS hasil_minggu_terakhir,
        lr.nomor_versi AS versi_terkini,
        lr.dibuat_pada AS waktu_update_aktivitas
    FROM logbook_mingguan lm
    LEFT JOIN logbook_revisi lr
        ON lr.logbook_id = lm.id
       AND lr.nomor_versi = lm.versi_terkini
    ORDER BY lm.penempatan_id, lm.minggu_ke DESC, lm.id DESC
)
SELECT
    pm.id AS penempatan_id,
    pma.nim,
    u.name AS nama_mahasiswa,
    fm.judul AS nama_formasi,
    mt.nama_perusahaan,
    pm.status AS status_penempatan,
    COALESCE(rm.total_logbook_mingguan, 0) AS total_logbook_mingguan,
    COALESCE(rm.logbook_draft, 0) AS logbook_draft,
    COALESCE(rm.logbook_diajukan, 0) AS logbook_diajukan,
    COALESCE(rm.logbook_menunggu_persetujuan, 0) AS logbook_menunggu_persetujuan,
    COALESCE(rm.logbook_perlu_revisi, 0) AS logbook_perlu_revisi,
    COALESCE(rm.logbook_disetujui, 0) AS logbook_disetujui,
    COALESCE(rm.logbook_ditolak, 0) AS logbook_ditolak,
    COALESCE(rh.total_catatan_harian, 0) AS total_catatan_harian,
    COALESCE(rh.total_hari_hadir, 0) AS total_hari_hadir,
    COALESCE(rh.total_hari_tidak_hadir, 0) AS total_hari_tidak_hadir,
    COALESCE(rh.validasi_harian_menunggu, 0) AS validasi_harian_menunggu,
    COALESCE(rh.validasi_harian_disetujui, 0) AS validasi_harian_disetujui,
    COALESCE(rh.validasi_harian_ditolak, 0) AS validasi_harian_ditolak,
    at.minggu_terakhir,
    at.status_logbook_terakhir,
    at.aktivitas_minggu_terakhir,
    at.hasil_minggu_terakhir,
    at.versi_terkini,
    at.waktu_update_aktivitas
FROM penempatan_magang pm
JOIN pendaftaran_magang pd ON pd.id = pm.pendaftaran_id
JOIN profil_mahasiswa pma ON pma.id = pd.mahasiswa_id
JOIN users u ON u.id = pma.user_id
JOIN formasi_magang fm ON fm.id = pd.formasi_id
JOIN mitra mt ON mt.id = fm.mitra_id
LEFT JOIN rekap_mingguan rm ON rm.penempatan_id = pm.id
LEFT JOIN rekap_harian rh ON rh.penempatan_id = pm.id
LEFT JOIN aktivitas_terkini at ON at.penempatan_id = pm.id
ORDER BY u.name, pma.nim;
