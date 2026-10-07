-- VERIFYMAGANG
-- Verifikasi prerequisite fitur Laporan Magang Mahasiswa
-- File ini hanya SELECT, tidak mengubah data.

-- 1. Cek template laporan
SELECT
    id,
    jenis_laporan,
    nama_template,
    file_template,
    versi,
    status_aktif
FROM public.template_laporan
ORDER BY jenis_laporan, versi DESC, id DESC;

-- 2. Cek penempatan magang yang sudah tersedia
SELECT
    pm.id AS penempatan_id,
    pm.status,
    pm.tanggal_mulai,
    pm.tanggal_selesai,
    u.id AS user_id,
    u.name AS mahasiswa,
    u.role,
    m.nama_perusahaan
FROM public.penempatan_magang pm
JOIN public.pendaftaran_magang p
    ON p.id = pm.pendaftaran_id
JOIN public.profil_mahasiswa pmhs
    ON pmhs.id = p.mahasiswa_id
JOIN public.users u
    ON u.id = pmhs.user_id
JOIN public.formasi_magang f
    ON f.id = p.formasi_id
JOIN public.mitra m
    ON m.id = f.mitra_id
WHERE u.role = 'mahasiswa'
ORDER BY pm.tanggal_mulai DESC, pm.id DESC;

-- 3. Cek jumlah laporan
SELECT
    COUNT(*) AS total_laporan,
    COUNT(*) FILTER (
        WHERE jenis_laporan = 'laporan_mingguan'
    ) AS total_mingguan,
    COUNT(*) FILTER (
        WHERE jenis_laporan = 'laporan_akhir'
    ) AS total_akhir
FROM public.laporan_magang;

-- 4. Cek migration workflow
SELECT migration
FROM public.schema_migrations
WHERE migration = '024_create_laporan_workflow.sql';
