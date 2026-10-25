-- Post-migration assertions and isolated behavior probes for migration 031.
-- RUN ONLY in the same approved disposable database after:
--   1) baseline migrations 000, 001, 002, 003, 004
--   2) database/testing/031_fixture_pre_migration.sql
--   3) database/migrations/031_create_proses_magang_and_penugasan.sql
-- This script mutates only synthetic fixture rows and must never run on a project DB.

DO $$
DECLARE
    v_count INTEGER;
BEGIN
    -- 01 and 08 have neither active application nor active placement.
    SELECT COUNT(*) INTO v_count
    FROM proses_magang pr JOIN profil_mahasiswa pm ON pm.id = pr.mahasiswa_id
    JOIN users u ON u.id = pm.user_id
    WHERE u.login_id IN ('fixture_mhs_01_031', 'fixture_mhs_08_031');
    IF v_count <> 0 THEN RAISE EXCEPTION '01/08 unexpectedly received process rows: %', v_count; END IF;

    -- 02 receives a process but no assignment.
    SELECT COUNT(*) INTO v_count
    FROM proses_magang pr JOIN profil_mahasiswa pm ON pm.id = pr.mahasiswa_id
    JOIN users u ON u.id = pm.user_id
    WHERE u.login_id = 'fixture_mhs_02_031';
    IF v_count <> 1 THEN RAISE EXCEPTION '02 process count expected 1, got %', v_count; END IF;
    SELECT COUNT(*) INTO v_count
    FROM penugasan_pembimbing pp JOIN proses_magang pr ON pr.id = pp.proses_magang_id
    JOIN profil_mahasiswa pm ON pm.id = pr.mahasiswa_id JOIN users u ON u.id = pm.user_id
    WHERE u.login_id = 'fixture_mhs_02_031';
    IF v_count <> 0 THEN RAISE EXCEPTION '02 assignment count expected 0, got %', v_count; END IF;

    -- 03 and 09 have one conservative backfilled assignment each.
    SELECT COUNT(*) INTO v_count
    FROM penugasan_pembimbing pp JOIN proses_magang pr ON pr.id = pp.proses_magang_id
    JOIN profil_mahasiswa pm ON pm.id = pr.mahasiswa_id JOIN users u ON u.id = pm.user_id
    WHERE u.login_id IN ('fixture_mhs_03_031', 'fixture_mhs_09_031')
      AND pp.status = 'aktif'
      AND pp.dosen_pembimbing_id = (SELECT id FROM profil_dosen WHERE nidn = 'FIXTURE-NIDN-A-031')
      AND pp.ditetapkan_oleh = (SELECT id FROM users WHERE login_id = 'fixture_mgr_031');
    IF v_count <> 2 THEN RAISE EXCEPTION '03/09 expected two consistent assignments, got %', v_count; END IF;

    -- 04, 05, 06, and 07 are ambiguous and must have no backfilled assignment.
    SELECT COUNT(*) INTO v_count
    FROM penugasan_pembimbing pp JOIN proses_magang pr ON pr.id = pp.proses_magang_id
    JOIN profil_mahasiswa pm ON pm.id = pr.mahasiswa_id JOIN users u ON u.id = pm.user_id
    WHERE u.login_id IN ('fixture_mhs_04_031', 'fixture_mhs_05_031', 'fixture_mhs_06_031', 'fixture_mhs_07_031');
    IF v_count <> 0 THEN RAISE EXCEPTION 'ambiguous students received assignments: %', v_count; END IF;

    -- Active applications with a conservative assignment receive snapshots.
    SELECT COUNT(*) INTO v_count
    FROM pendaftaran_magang p JOIN profil_mahasiswa pm ON pm.id = p.mahasiswa_id JOIN users u ON u.id = pm.user_id
    WHERE u.login_id = 'fixture_mhs_09_031'
      AND p.proses_magang_id IS NOT NULL
      AND p.penugasan_pembimbing_id IS NOT NULL
      AND p.dosen_pembimbing_snapshot_id = (SELECT id FROM profil_dosen WHERE nidn = 'FIXTURE-NIDN-A-031')
      AND p.dosen_pembimbing_snapshot_nama = 'Fixture Dosen A 031'
      AND p.dosen_pembimbing_snapshot_nidn = 'FIXTURE-NIDN-A-031';
    IF v_count <> 1 THEN RAISE EXCEPTION '09 snapshot was not backfilled exactly once: %', v_count; END IF;
END $$;

-- Replacement must preserve the old assignment and the old application snapshot.
BEGIN;
WITH target AS (
    SELECT pr.id AS proses_id, pp.id AS old_assignment_id, p.id AS application_id
    FROM proses_magang pr
    JOIN profil_mahasiswa pm ON pm.id = pr.mahasiswa_id
    JOIN users u ON u.id = pm.user_id AND u.login_id = 'fixture_mhs_09_031'
    JOIN penugasan_pembimbing pp ON pp.proses_magang_id = pr.id AND pp.status = 'aktif'
    JOIN pendaftaran_magang p ON p.proses_magang_id = pr.id
)
UPDATE penugasan_pembimbing pp
SET status = 'diganti', berakhir_pada = CURRENT_TIMESTAMP
FROM target
WHERE pp.id = target.old_assignment_id;

INSERT INTO penugasan_pembimbing
    (proses_magang_id, dosen_pembimbing_id, ditetapkan_oleh, alasan, status)
SELECT pr.id, pd.id, actor.id, 'Fixture replacement verification 031', 'aktif'
FROM proses_magang pr
JOIN profil_mahasiswa pm ON pm.id = pr.mahasiswa_id
JOIN users student ON student.id = pm.user_id AND student.login_id = 'fixture_mhs_09_031'
JOIN profil_dosen pd ON pd.nidn = 'FIXTURE-NIDN-B-031'
JOIN users actor ON actor.login_id = 'fixture_mgr_031';

DO $$
DECLARE
    v_count INTEGER;
    v_snapshot BIGINT;
BEGIN
    SELECT COUNT(*) INTO v_count
    FROM penugasan_pembimbing pp
    JOIN proses_magang pr ON pr.id = pp.proses_magang_id
    JOIN profil_mahasiswa pm ON pm.id = pr.mahasiswa_id JOIN users u ON u.id = pm.user_id
    WHERE u.login_id = 'fixture_mhs_09_031' AND pp.status = 'diganti';
    IF v_count <> 1 THEN RAISE EXCEPTION 'old assignment history was not preserved'; END IF;

    SELECT dosen_pembimbing_snapshot_id INTO v_snapshot
    FROM pendaftaran_magang p JOIN profil_mahasiswa pm ON pm.id=p.mahasiswa_id JOIN users u ON u.id=pm.user_id
    WHERE u.login_id='fixture_mhs_09_031';
    IF v_snapshot <> (SELECT id FROM profil_dosen WHERE nidn='FIXTURE-NIDN-A-031') THEN
        RAISE EXCEPTION 'application snapshot changed after replacement';
    END IF;
END $$;
COMMIT;

-- Constraint probes. Each block must fail closed inside its own subtransaction.
DO $$
DECLARE
    v_student BIGINT;
    v_actor BIGINT;
    v_process BIGINT;
    v_advisor BIGINT;
BEGIN
    SELECT pm.id INTO v_student FROM profil_mahasiswa pm JOIN users u ON u.id=pm.user_id WHERE u.login_id='fixture_mhs_09_031';
    SELECT id INTO v_actor FROM users WHERE login_id='fixture_mgr_031';
    SELECT id INTO v_advisor FROM profil_dosen WHERE nidn='FIXTURE-NIDN-A-031';
    BEGIN
        INSERT INTO proses_magang (mahasiswa_id, status, dibuat_oleh) VALUES (v_student, 'aktif', v_actor);
        RAISE EXCEPTION 'duplicate active process unexpectedly succeeded';
    EXCEPTION WHEN unique_violation THEN NULL;
    END;

    SELECT id INTO v_process FROM proses_magang WHERE mahasiswa_id=v_student AND status='aktif';
    BEGIN
        INSERT INTO penugasan_pembimbing (proses_magang_id, dosen_pembimbing_id, ditetapkan_oleh, alasan)
        VALUES (v_process, v_advisor, v_actor, 'duplicate active assignment probe');
        RAISE EXCEPTION 'duplicate active assignment unexpectedly succeeded';
    EXCEPTION WHEN unique_violation THEN NULL;
    END;
END $$;
