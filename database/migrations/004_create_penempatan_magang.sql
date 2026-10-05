
BEGIN;

-- ============================================
-- PENEMPATAN MAGANG
-- ============================================

CREATE TABLE penempatan_magang (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    pendaftaran_id BIGINT NOT NULL UNIQUE
        REFERENCES pendaftaran_magang(id) ON DELETE RESTRICT,

    dosen_pembimbing_id BIGINT
        REFERENCES profil_dosen(id) ON DELETE RESTRICT,

    -- Periode pelaksanaan magang
    tanggal_mulai DATE NOT NULL,
    tanggal_selesai DATE NOT NULL,

    -- Status pelaksanaan magang
    status VARCHAR(30) NOT NULL DEFAULT 'persiapan',

    catatan TEXT,

    ditetapkan_oleh BIGINT
        REFERENCES users(id) ON DELETE SET NULL,

    tanggal_penetapan TIMESTAMPTZ
        NOT NULL DEFAULT CURRENT_TIMESTAMP,

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT penempatan_tanggal_check
        CHECK (tanggal_selesai >= tanggal_mulai),

    CONSTRAINT penempatan_status_check
        CHECK (
            status IN (
                'persiapan',
                'berlangsung',
                'menunggu_penilaian',
                'selesai',
                'dibatalkan'
            )
        )
);


-- ============================================
-- INDEX
-- ============================================

CREATE INDEX idx_penempatan_dosen
    ON penempatan_magang(dosen_pembimbing_id);

CREATE INDEX idx_penempatan_status
    ON penempatan_magang(status);

CREATE INDEX idx_penempatan_periode
    ON penempatan_magang(tanggal_mulai, tanggal_selesai);


INSERT INTO schema_migrations (migration)
VALUES ('004_create_penempatan_magang.sql');

COMMIT;