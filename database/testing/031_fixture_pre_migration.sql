-- Synthetic fixture for migration 031 review.
-- RUN ONLY inside a newly created, approved disposable PostgreSQL database.
-- This script must run after migrations 000, 001, 002, 003, and 004,
-- and before database/migrations/031_create_proses_magang_and_penugasan.sql.
-- It contains no project-user identifiers or production data.

BEGIN;

-- Synthetic users: one manager, two lecturers, and ten students.
INSERT INTO users (login_id, name, password, role)
VALUES
    ('fixture_mgr_031', 'Fixture Manager 031', 'fixture-password-hash', 'tendik'),
    ('fixture_dsn_a_031', 'Fixture Dosen A 031', 'fixture-password-hash', 'dosen'),
    ('fixture_dsn_b_031', 'Fixture Dosen B 031', 'fixture-password-hash', 'dosen'),
    ('fixture_mhs_01_031', 'Fixture Mahasiswa 01 031', 'fixture-password-hash', 'mahasiswa'),
    ('fixture_mhs_02_031', 'Fixture Mahasiswa 02 031', 'fixture-password-hash', 'mahasiswa'),
    ('fixture_mhs_03_031', 'Fixture Mahasiswa 03 031', 'fixture-password-hash', 'mahasiswa'),
    ('fixture_mhs_04_031', 'Fixture Mahasiswa 04 031', 'fixture-password-hash', 'mahasiswa'),
    ('fixture_mhs_05_031', 'Fixture Mahasiswa 05 031', 'fixture-password-hash', 'mahasiswa'),
    ('fixture_mhs_06_031', 'Fixture Mahasiswa 06 031', 'fixture-password-hash', 'mahasiswa'),
    ('fixture_mhs_07_031', 'Fixture Mahasiswa 07 031', 'fixture-password-hash', 'mahasiswa'),
    ('fixture_mhs_08_031', 'Fixture Mahasiswa 08 031', 'fixture-password-hash', 'mahasiswa'),
    ('fixture_mhs_09_031', 'Fixture Mahasiswa 09 031', 'fixture-password-hash', 'mahasiswa'),
    ('fixture_mhs_10_031', 'Fixture Mahasiswa 10 031', 'fixture-password-hash', 'mahasiswa');

INSERT INTO profil_dosen (user_id, nidn)
SELECT id, CASE login_id
    WHEN 'fixture_dsn_a_031' THEN 'FIXTURE-NIDN-A-031'
    WHEN 'fixture_dsn_b_031' THEN 'FIXTURE-NIDN-B-031'
END
FROM users
WHERE login_id IN ('fixture_dsn_a_031', 'fixture_dsn_b_031');

INSERT INTO profil_mahasiswa (user_id, nim, program_studi, angkatan)
SELECT id, replace(login_id, 'fixture_mhs_', 'FIXTURE-NIM-'), 'Fixture Informatika', 2024
FROM users
WHERE login_id LIKE 'fixture_mhs_%_031';

INSERT INTO mitra (nama_perusahaan, kode_perusahaan, kategori, alamat)
VALUES ('Fixture Mitra 031', 'FIXTURE-MITRA-031', 'UMKM', 'Alamat fixture 031');

INSERT INTO formasi_magang
    (mitra_id, judul, deskripsi, jumlah_kuota, tanggal_mulai, tanggal_selesai, tahun_akademik, status)
SELECT id, 'Fixture Formasi 031', 'Formasi sintetis untuk validasi migration.', 100,
       DATE '2026-01-01', DATE '2026-06-30', '2025/2026', 'dibuka'
FROM mitra WHERE kode_perusahaan = 'FIXTURE-MITRA-031';

-- 01: student without application.
-- No pendaftaran_magang row is inserted for fixture_mhs_01_031.

-- 02: active application without placement.
INSERT INTO pendaftaran_magang (mahasiswa_id, formasi_id, status_pendaftaran)
SELECT pm.id, f.id, 'menunggu_persetujuan_dosen'
FROM profil_mahasiswa pm, formasi_magang f, users u
WHERE u.login_id = 'fixture_mhs_02_031'
  AND pm.user_id = u.id
  AND f.judul = 'Fixture Formasi 031';

-- 03: active placement with one consistent lecturer and one actor.
INSERT INTO pendaftaran_magang (mahasiswa_id, formasi_id, status_pendaftaran)
SELECT pm.id, f.id, 'diterima'
FROM profil_mahasiswa pm, formasi_magang f, users u
WHERE u.login_id = 'fixture_mhs_03_031'
  AND pm.user_id = u.id
  AND f.judul = 'Fixture Formasi 031';
INSERT INTO penempatan_magang (pendaftaran_id, dosen_pembimbing_id, tanggal_mulai, tanggal_selesai, status, ditetapkan_oleh)
SELECT p.id, pd.id, DATE '2026-01-01', DATE '2026-06-30', 'berlangsung', actor.id
FROM pendaftaran_magang p
JOIN profil_mahasiswa pm ON pm.id = p.mahasiswa_id
JOIN users student ON student.id = pm.user_id AND student.login_id = 'fixture_mhs_03_031'
JOIN profil_dosen pd ON pd.nidn = 'FIXTURE-NIDN-A-031'
JOIN users actor ON actor.login_id = 'fixture_mgr_031';

-- 04: active placements with different lecturers; should not backfill an assignment.
INSERT INTO pendaftaran_magang (mahasiswa_id, formasi_id, status_pendaftaran)
SELECT pm.id, f.id, 'selesai'
FROM profil_mahasiswa pm, formasi_magang f, users u
WHERE u.login_id = 'fixture_mhs_04_031' AND pm.user_id = u.id AND f.judul = 'Fixture Formasi 031';
INSERT INTO pendaftaran_magang (mahasiswa_id, formasi_id, status_pendaftaran)
SELECT pm.id, f.id, 'selesai'
FROM profil_mahasiswa pm, formasi_magang f, users u
WHERE u.login_id = 'fixture_mhs_04_031' AND pm.user_id = u.id AND f.judul = 'Fixture Formasi 031';
INSERT INTO penempatan_magang (pendaftaran_id, dosen_pembimbing_id, tanggal_mulai, tanggal_selesai, status, ditetapkan_oleh)
SELECT p.id, pd.id, DATE '2026-01-01', DATE '2026-03-31', 'berlangsung', actor.id
FROM pendaftaran_magang p JOIN profil_mahasiswa pm ON pm.id=p.mahasiswa_id JOIN users s ON s.id=pm.user_id AND s.login_id='fixture_mhs_04_031'
JOIN profil_dosen pd ON pd.nidn='FIXTURE-NIDN-A-031' JOIN users actor ON actor.login_id='fixture_mgr_031'
ORDER BY p.id ASC LIMIT 1;
INSERT INTO penempatan_magang (pendaftaran_id, dosen_pembimbing_id, tanggal_mulai, tanggal_selesai, status, ditetapkan_oleh)
SELECT p.id, pd.id, DATE '2026-04-01', DATE '2026-06-30', 'berlangsung', actor.id
FROM pendaftaran_magang p JOIN profil_mahasiswa pm ON pm.id=p.mahasiswa_id JOIN users s ON s.id=pm.user_id AND s.login_id='fixture_mhs_04_031'
JOIN profil_dosen pd ON pd.nidn='FIXTURE-NIDN-B-031' JOIN users actor ON actor.login_id='fixture_mgr_031'
ORDER BY p.id DESC LIMIT 1;

-- 05: mixed active placements, one with and one without lecturer; must be skipped.
INSERT INTO pendaftaran_magang (mahasiswa_id, formasi_id, status_pendaftaran)
SELECT pm.id, f.id, 'selesai' FROM profil_mahasiswa pm, formasi_magang f, users u
WHERE u.login_id='fixture_mhs_05_031' AND pm.user_id=u.id AND f.judul='Fixture Formasi 031';
INSERT INTO pendaftaran_magang (mahasiswa_id, formasi_id, status_pendaftaran)
SELECT pm.id, f.id, 'selesai' FROM profil_mahasiswa pm, formasi_magang f, users u
WHERE u.login_id='fixture_mhs_05_031' AND pm.user_id=u.id AND f.judul='Fixture Formasi 031';
INSERT INTO penempatan_magang (pendaftaran_id, dosen_pembimbing_id, tanggal_mulai, tanggal_selesai, status, ditetapkan_oleh)
SELECT p.id, pd.id, DATE '2026-01-01', DATE '2026-03-31', 'berlangsung', actor.id
FROM pendaftaran_magang p JOIN profil_mahasiswa pm ON pm.id=p.mahasiswa_id JOIN users s ON s.id=pm.user_id AND s.login_id='fixture_mhs_05_031'
JOIN profil_dosen pd ON pd.nidn='FIXTURE-NIDN-A-031' JOIN users actor ON actor.login_id='fixture_mgr_031'
ORDER BY p.id ASC LIMIT 1;
INSERT INTO penempatan_magang (pendaftaran_id, tanggal_mulai, tanggal_selesai, status)
SELECT p.id, DATE '2026-04-01', DATE '2026-06-30', 'berlangsung'
FROM pendaftaran_magang p JOIN profil_mahasiswa pm ON pm.id=p.mahasiswa_id JOIN users s ON s.id=pm.user_id AND s.login_id='fixture_mhs_05_031'
ORDER BY p.id DESC LIMIT 1;

-- 06: active placement with lecturer but NULL actor; must be skipped.
INSERT INTO pendaftaran_magang (mahasiswa_id, formasi_id, status_pendaftaran)
SELECT pm.id, f.id, 'selesai' FROM profil_mahasiswa pm, formasi_magang f, users u
WHERE u.login_id='fixture_mhs_06_031' AND pm.user_id=u.id AND f.judul='Fixture Formasi 031';
INSERT INTO penempatan_magang (pendaftaran_id, dosen_pembimbing_id, tanggal_mulai, tanggal_selesai, status)
SELECT p.id, pd.id, DATE '2026-01-01', DATE '2026-06-30', 'berlangsung'
FROM pendaftaran_magang p JOIN profil_mahasiswa pm ON pm.id=p.mahasiswa_id JOIN users s ON s.id=pm.user_id AND s.login_id='fixture_mhs_06_031'
JOIN profil_dosen pd ON pd.nidn='FIXTURE-NIDN-A-031';

-- 07: active placements with different actors; must be skipped.
INSERT INTO pendaftaran_magang (mahasiswa_id, formasi_id, status_pendaftaran)
SELECT pm.id, f.id, 'selesai' FROM profil_mahasiswa pm, formasi_magang f, users u
WHERE u.login_id='fixture_mhs_07_031' AND pm.user_id=u.id AND f.judul='Fixture Formasi 031';
INSERT INTO pendaftaran_magang (mahasiswa_id, formasi_id, status_pendaftaran)
SELECT pm.id, f.id, 'selesai' FROM profil_mahasiswa pm, formasi_magang f, users u
WHERE u.login_id='fixture_mhs_07_031' AND pm.user_id=u.id AND f.judul='Fixture Formasi 031';
INSERT INTO penempatan_magang (pendaftaran_id, dosen_pembimbing_id, tanggal_mulai, tanggal_selesai, status, ditetapkan_oleh)
SELECT p.id, pd.id, DATE '2026-01-01', DATE '2026-03-31', 'berlangsung', actor.id
FROM pendaftaran_magang p JOIN profil_mahasiswa pm ON pm.id=p.mahasiswa_id JOIN users s ON s.id=pm.user_id AND s.login_id='fixture_mhs_07_031'
JOIN profil_dosen pd ON pd.nidn='FIXTURE-NIDN-A-031' JOIN users actor ON actor.login_id='fixture_mgr_031'
ORDER BY p.id ASC LIMIT 1;
INSERT INTO penempatan_magang (pendaftaran_id, dosen_pembimbing_id, tanggal_mulai, tanggal_selesai, status, ditetapkan_oleh)
SELECT p.id, pd.id, DATE '2026-04-01', DATE '2026-06-30', 'berlangsung', actor.id
FROM pendaftaran_magang p JOIN profil_mahasiswa pm ON pm.id=p.mahasiswa_id JOIN users s ON s.id=pm.user_id AND s.login_id='fixture_mhs_07_031'
JOIN profil_dosen pd ON pd.nidn='FIXTURE-NIDN-A-031' JOIN users actor ON actor.login_id='fixture_dsn_a_031'
ORDER BY p.id DESC LIMIT 1;

-- 08: terminal application without active placement; should not create an active process.
INSERT INTO pendaftaran_magang (mahasiswa_id, formasi_id, status_pendaftaran)
SELECT pm.id, f.id, 'selesai' FROM profil_mahasiswa pm, formasi_magang f, users u
WHERE u.login_id='fixture_mhs_08_031' AND pm.user_id=u.id AND f.judul='Fixture Formasi 031';

-- 09: active application plus active placement; used for snapshot/replacement checks after 031.
INSERT INTO pendaftaran_magang (mahasiswa_id, formasi_id, status_pendaftaran)
SELECT pm.id, f.id, 'diterima' FROM profil_mahasiswa pm, formasi_magang f, users u
WHERE u.login_id='fixture_mhs_09_031' AND pm.user_id=u.id AND f.judul='Fixture Formasi 031';
INSERT INTO penempatan_magang (pendaftaran_id, dosen_pembimbing_id, tanggal_mulai, tanggal_selesai, status, ditetapkan_oleh)
SELECT p.id, pd.id, DATE '2026-01-01', DATE '2026-06-30', 'berlangsung', actor.id
FROM pendaftaran_magang p JOIN profil_mahasiswa pm ON pm.id=p.mahasiswa_id JOIN users s ON s.id=pm.user_id AND s.login_id='fixture_mhs_09_031'
JOIN profil_dosen pd ON pd.nidn='FIXTURE-NIDN-A-031' JOIN users actor ON actor.login_id='fixture_mgr_031';

-- 10: terminal/inactive record with active placement is deliberately omitted;
-- add an explicit non-active process only in the post-migration probe if needed.

COMMIT;
