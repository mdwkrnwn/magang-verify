BEGIN;

-- Proses administratif magang dibuat sebelum mahasiswa mengirim pengajuan.
CREATE TABLE proses_magang (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    mahasiswa_id BIGINT NOT NULL REFERENCES profil_mahasiswa(id) ON DELETE RESTRICT,
    status VARCHAR(20) NOT NULL DEFAULT 'aktif',
    dibuat_oleh BIGINT REFERENCES users(id) ON DELETE SET NULL,
    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT proses_magang_status_check CHECK (status IN ('aktif', 'selesai', 'dibatalkan'))
);

CREATE UNIQUE INDEX uq_proses_magang_mahasiswa_aktif
    ON proses_magang(mahasiswa_id) WHERE status = 'aktif';
CREATE INDEX idx_proses_magang_mahasiswa ON proses_magang(mahasiswa_id);
CREATE INDEX idx_proses_magang_status ON proses_magang(status);

-- Setiap row adalah histori penugasan. Row aktif sebelumnya ditutup saat diganti.
CREATE TABLE penugasan_pembimbing (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    proses_magang_id BIGINT NOT NULL REFERENCES proses_magang(id) ON DELETE RESTRICT,
    dosen_pembimbing_id BIGINT NOT NULL REFERENCES profil_dosen(id) ON DELETE RESTRICT,
    ditetapkan_oleh BIGINT NOT NULL REFERENCES users(id) ON DELETE RESTRICT,
    alasan TEXT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'aktif',
    ditetapkan_pada TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    berakhir_pada TIMESTAMPTZ,
    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT penugasan_pembimbing_status_check CHECK (status IN ('aktif', 'diganti', 'dibatalkan')),
    CONSTRAINT penugasan_pembimbing_alasan_check CHECK (char_length(btrim(alasan)) BETWEEN 1 AND 5000),
    CONSTRAINT penugasan_pembimbing_dates_check CHECK (berakhir_pada IS NULL OR berakhir_pada >= ditetapkan_pada)
);

CREATE UNIQUE INDEX uq_penugasan_pembimbing_proses_aktif
    ON penugasan_pembimbing(proses_magang_id) WHERE status = 'aktif';
CREATE INDEX idx_penugasan_pembimbing_dosen ON penugasan_pembimbing(dosen_pembimbing_id);
CREATE INDEX idx_penugasan_pembimbing_proses ON penugasan_pembimbing(proses_magang_id, ditetapkan_pada);

ALTER TABLE pendaftaran_magang
    ADD COLUMN proses_magang_id BIGINT REFERENCES proses_magang(id) ON DELETE RESTRICT,
    ADD COLUMN penugasan_pembimbing_id BIGINT REFERENCES penugasan_pembimbing(id) ON DELETE RESTRICT,
    ADD COLUMN dosen_pembimbing_snapshot_id BIGINT REFERENCES profil_dosen(id) ON DELETE RESTRICT,
    ADD COLUMN dosen_pembimbing_snapshot_nama VARCHAR(150),
    ADD COLUMN dosen_pembimbing_snapshot_nidn VARCHAR(50);

CREATE INDEX idx_pendaftaran_proses_magang ON pendaftaran_magang(proses_magang_id);
CREATE INDEX idx_pendaftaran_penugasan_pembimbing ON pendaftaran_magang(penugasan_pembimbing_id);

-- Backfill konservatif: proses dibuat untuk pengajuan/penempatan aktif.
-- Penugasan hanya dibentuk bila seluruh penempatan aktif mahasiswa memiliki satu dosen legacy yang sama.
INSERT INTO proses_magang (mahasiswa_id, status)
SELECT DISTINCT source.mahasiswa_id, 'aktif'
FROM (
    SELECT p.mahasiswa_id FROM pendaftaran_magang p
    WHERE p.status_pendaftaran IN ('diajukan', 'menunggu_persetujuan_dosen', 'menunggu_respons_mitra', 'seleksi', 'diterima', 'perlu_revisi')
    UNION
    SELECT p.mahasiswa_id FROM penempatan_magang pm
    INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id
    WHERE pm.status IN ('persiapan', 'berlangsung', 'menunggu_penilaian')
) source
WHERE NOT EXISTS (
    SELECT 1 FROM proses_magang existing
    WHERE existing.mahasiswa_id = source.mahasiswa_id AND existing.status = 'aktif'
);

INSERT INTO penugasan_pembimbing
    (proses_magang_id, dosen_pembimbing_id, ditetapkan_oleh, alasan, status, ditetapkan_pada)
SELECT pr.id, legacy.dosen_pembimbing_id,
       COALESCE(legacy.ditetapkan_oleh, pr.dibuat_oleh),
       'Backfill dari penempatan legacy; perlu verifikasi pengelola.', 'aktif',
       COALESCE(legacy.tanggal_penetapan, CURRENT_TIMESTAMP)
FROM proses_magang pr
INNER JOIN (
    SELECT p.mahasiswa_id, MIN(pm.dosen_pembimbing_id) AS dosen_pembimbing_id,
           MIN(pm.ditetapkan_oleh) AS ditetapkan_oleh,
           MIN(pm.tanggal_penetapan) AS tanggal_penetapan,
           COUNT(DISTINCT pm.dosen_pembimbing_id) AS advisor_count,
           COUNT(*) AS placement_count,
           COUNT(pm.dosen_pembimbing_id) AS assigned_placement_count,
           COUNT(DISTINCT pm.ditetapkan_oleh) AS actor_count,
           COUNT(*) FILTER (WHERE pm.ditetapkan_oleh IS NULL) AS missing_actor_count
    FROM penempatan_magang pm
    INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id
    WHERE pm.status IN ('persiapan', 'berlangsung', 'menunggu_penilaian')
    GROUP BY p.mahasiswa_id
) legacy ON legacy.mahasiswa_id = pr.mahasiswa_id
WHERE pr.status = 'aktif'
  AND legacy.advisor_count = 1
  AND legacy.placement_count = legacy.assigned_placement_count
  AND legacy.actor_count = 1
  AND legacy.missing_actor_count = 0
  AND COALESCE(legacy.ditetapkan_oleh, pr.dibuat_oleh) IS NOT NULL
  AND NOT EXISTS (
      SELECT 1 FROM penugasan_pembimbing current_assignment
      WHERE current_assignment.proses_magang_id = pr.id AND current_assignment.status = 'aktif'
  );

UPDATE pendaftaran_magang p
SET proses_magang_id = pr.id,
    penugasan_pembimbing_id = assignment.id,
    dosen_pembimbing_snapshot_id = assignment.dosen_pembimbing_id,
    dosen_pembimbing_snapshot_nama = dosen.name,
    dosen_pembimbing_snapshot_nidn = advisor.nidn
FROM proses_magang pr
LEFT JOIN penugasan_pembimbing assignment
  ON assignment.proses_magang_id = pr.id AND assignment.status = 'aktif'
LEFT JOIN profil_dosen advisor ON advisor.id = assignment.dosen_pembimbing_id
LEFT JOIN users dosen ON dosen.id = advisor.user_id
WHERE p.mahasiswa_id = pr.mahasiswa_id
  AND p.status_pendaftaran IN ('diajukan', 'menunggu_persetujuan_dosen', 'menunggu_respons_mitra', 'seleksi', 'diterima', 'perlu_revisi')
  AND p.proses_magang_id IS NULL;

INSERT INTO public.schema_migrations (migration)
VALUES ('031_create_proses_magang_and_penugasan.sql')
ON CONFLICT (migration) DO NOTHING;

COMMIT;
