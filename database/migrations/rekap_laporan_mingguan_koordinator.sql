-- Query CTE: rekap laporan mingguan untuk dashboard Koordinator Magang.
-- CTE (WITH) merangkum jumlah laporan mingguan per mahasiswa.
-- Status terverifikasi dihitung sebagai sudah diverifikasi.
-- Status diajukan/diperiksa_dosen/menunggu_verifikasi dihitung sebagai menunggu verifikasi.
-- Laporan draft, perlu_revisi, dan ditolak tidak dimasukkan ke dua hitungan tersebut.

WITH rekap_laporan AS (
    SELECT
        pen.pendaftaran_id,
        pen.id AS penempatan_id,
        COUNT(lm.id) FILTER (
            WHERE lm.status = 'terverifikasi'
        ) AS jumlah_terverifikasi,
        COUNT(lm.id) FILTER (
            WHERE lm.status IN (
                'diajukan',
                'diperiksa_dosen',
                'menunggu_verifikasi'
            )
        ) AS jumlah_menunggu_verifikasi,
        COUNT(lm.id) FILTER (
            WHERE lm.status = 'perlu_revisi'
        ) AS jumlah_perlu_revisi,
        COUNT(lm.id) FILTER (
            WHERE lm.status = 'ditolak'
        ) AS jumlah_ditolak
    FROM penempatan_magang AS pen
    LEFT JOIN laporan_magang AS lm
        ON lm.penempatan_id = pen.id
       AND lm.jenis_laporan = 'laporan_mingguan'
    GROUP BY pen.pendaftaran_id, pen.id
)
SELECT
    pm.id AS mahasiswa_id,
    pm.nim,
    um.name AS nama_mahasiswa,
    fm.judul AS nama_formasi,
    m.nama_perusahaan,
    p.status_pendaftaran,
    ud.name AS nama_dosen_pembimbing,
    COALESCE(rl.jumlah_terverifikasi, 0) AS jumlah_laporan_terverifikasi,
    COALESCE(rl.jumlah_menunggu_verifikasi, 0) AS jumlah_laporan_menunggu_verifikasi,
    COALESCE(rl.jumlah_perlu_revisi, 0) AS jumlah_laporan_perlu_revisi,
    COALESCE(rl.jumlah_ditolak, 0) AS jumlah_laporan_ditolak
FROM pendaftaran_magang AS p
JOIN profil_mahasiswa AS pm
    ON pm.id = p.mahasiswa_id
JOIN users AS um
    ON um.id = pm.user_id
JOIN formasi_magang AS fm
    ON fm.id = p.formasi_id
JOIN mitra AS m
    ON m.id = fm.mitra_id
LEFT JOIN penempatan_magang AS pen
    ON pen.pendaftaran_id = p.id
LEFT JOIN profil_dosen AS pd
    ON pd.id = pen.dosen_pembimbing_id
LEFT JOIN users AS ud
    ON ud.id = pd.user_id
LEFT JOIN rekap_laporan AS rl
    ON rl.pendaftaran_id = p.id
WHERE pen.id IS NOT NULL
ORDER BY um.name ASC;
