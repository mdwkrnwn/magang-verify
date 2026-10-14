-- Sumber tabel mengikuti schema.sql MagangVerify.
-- Jalankan setelah struktur tabel mitra, formasi_magang, pendaftaran_magang,
-- penempatan_magang, profil_mahasiswa, profil_dosen, dan users tersedia.

CREATE OR REPLACE VIEW view_rekap_magang_koordinator AS
SELECT
    pm.id AS mahasiswa_id,
    pm.nim,
    um.name AS nama_mahasiswa,
    pm.program_studi,
    pm.angkatan,

    p.id AS pendaftaran_id,
    p.tanggal_pengajuan,
    p.status_pendaftaran,
    p.status_persetujuan_dosen,
    p.status_respons_mitra,

    fm.id AS formasi_id,
    fm.judul AS nama_formasi,
    fm.tahun_akademik,
    fm.tanggal_mulai AS tanggal_mulai_formasi,
    fm.tanggal_selesai AS tanggal_selesai_formasi,

    m.id AS mitra_id,
    m.nama_perusahaan,

    pen.id AS penempatan_id,
    pen.status AS status_penempatan,
    pen.tanggal_mulai AS tanggal_mulai_magang,
    pen.tanggal_selesai AS tanggal_selesai_magang,

    pd.id AS dosen_pembimbing_id,
    ud.name AS nama_dosen_pembimbing
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
    ON ud.id = pd.user_id;

COMMENT ON VIEW view_rekap_magang_koordinator IS
'Rekap pendaftaran dan penempatan magang mahasiswa untuk monitoring Koordinator Magang.';

-- Catat migration agar sesuai dengan mekanisme migration proyek.
INSERT INTO public.schema_migrations (migration)
VALUES ('028_create_view_rekap_magang_koordinator.sql')
ON CONFLICT (migration) DO NOTHING;
