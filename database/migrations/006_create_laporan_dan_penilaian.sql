
BEGIN;

-- ============================================
-- LAPORAN MAGANG
-- ============================================

CREATE TABLE laporan_magang (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    penempatan_id BIGINT NOT NULL
        REFERENCES penempatan_magang(id) ON DELETE RESTRICT,

    jenis_laporan VARCHAR(30) NOT NULL,

    judul VARCHAR(200) NOT NULL,
    versi_terkini INTEGER NOT NULL DEFAULT 1,

    status VARCHAR(30) NOT NULL DEFAULT 'draft',

    diajukan_pada TIMESTAMPTZ,

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT laporan_jenis_check
        CHECK (
            jenis_laporan IN (
                'laporan_akhir'
            )
        ),

    CONSTRAINT laporan_versi_check
        CHECK (versi_terkini > 0),

    CONSTRAINT laporan_status_check
        CHECK (
            status IN (
                'draft',
                'diajukan',
                'diperiksa_dosen',
                'perlu_revisi',
                'menunggu_verifikasi',
                'terverifikasi',
                'ditolak'
            )
        ),

    CONSTRAINT laporan_penempatan_jenis_unik
        UNIQUE (penempatan_id, jenis_laporan)
);


-- ============================================
-- VERSI / REVISI LAPORAN
-- ============================================

CREATE TABLE laporan_revisi (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    laporan_id BIGINT NOT NULL
        REFERENCES laporan_magang(id) ON DELETE RESTRICT,

    nomor_versi INTEGER NOT NULL,

    file_path VARCHAR(500) NOT NULL,
    ringkasan TEXT,

    catatan_revisi TEXT,

    diunggah_oleh BIGINT
        REFERENCES users(id) ON DELETE SET NULL,

    diunggah_pada TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT laporan_revisi_versi_check
        CHECK (nomor_versi > 0),

    CONSTRAINT laporan_revisi_unik
        UNIQUE (laporan_id, nomor_versi)
);


-- ============================================
-- PENILAIAN MAGANG
-- ============================================

CREATE TABLE penilaian_magang (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    penempatan_id BIGINT NOT NULL
        REFERENCES penempatan_magang(id) ON DELETE RESTRICT,

    penilai_id BIGINT NOT NULL
        REFERENCES users(id) ON DELETE RESTRICT,

    peran_penilai VARCHAR(20) NOT NULL,

    nilai_kedisiplinan NUMERIC(5,2),
    nilai_komunikasi NUMERIC(5,2),
    nilai_kerja_sama NUMERIC(5,2),
    nilai_tanggung_jawab NUMERIC(5,2),
    nilai_keterampilan NUMERIC(5,2),

    nilai_akhir NUMERIC(5,2),

    catatan TEXT,

    status VARCHAR(20) NOT NULL DEFAULT 'draft',

    dinilai_pada TIMESTAMPTZ,
    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT penilaian_peran_check
        CHECK (
            peran_penilai IN (
                'dosen',
                'mitra'
            )
        ),

    CONSTRAINT penilaian_status_check
        CHECK (
            status IN (
                'draft',
                'diajukan',
                'disetujui',
                'perlu_revisi'
            )
        ),

    CONSTRAINT penilaian_nilai_check
        CHECK (
            (nilai_kedisiplinan IS NULL OR nilai_kedisiplinan BETWEEN 0 AND 100)
            AND (nilai_komunikasi IS NULL OR nilai_komunikasi BETWEEN 0 AND 100)
            AND (nilai_kerja_sama IS NULL OR nilai_kerja_sama BETWEEN 0 AND 100)
            AND (nilai_tanggung_jawab IS NULL OR nilai_tanggung_jawab BETWEEN 0 AND 100)
            AND (nilai_keterampilan IS NULL OR nilai_keterampilan BETWEEN 0 AND 100)
            AND (nilai_akhir IS NULL OR nilai_akhir BETWEEN 0 AND 100)
        ),

    CONSTRAINT penilaian_penilai_unik
        UNIQUE (penempatan_id, penilai_id, peran_penilai)
);


-- ============================================
-- VERIFIKASI PENYELESAIAN MAGANG
-- ============================================

CREATE TABLE verifikasi_penyelesaian_magang (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    penempatan_id BIGINT NOT NULL UNIQUE
        REFERENCES penempatan_magang(id) ON DELETE RESTRICT,

    diverifikasi_oleh BIGINT
        REFERENCES users(id) ON DELETE RESTRICT,

    status VARCHAR(20) NOT NULL DEFAULT 'menunggu',

    catatan TEXT,

    diverifikasi_pada TIMESTAMPTZ,

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT verifikasi_penyelesaian_petugas_check
    CHECK (
        status NOT IN ('terverifikasi', 'ditolak')
        OR (
            diverifikasi_oleh IS NOT NULL
            AND diverifikasi_pada IS NOT NULL
        )
    )
);


-- ============================================
-- INDEX
-- ============================================

CREATE INDEX idx_laporan_penempatan
    ON laporan_magang(penempatan_id);

CREATE INDEX idx_laporan_status
    ON laporan_magang(status);

CREATE INDEX idx_laporan_revisi_laporan
    ON laporan_revisi(laporan_id, nomor_versi);

CREATE INDEX idx_penilaian_penempatan
    ON penilaian_magang(penempatan_id);

CREATE INDEX idx_penilaian_penilai
    ON penilaian_magang(penilai_id);

CREATE INDEX idx_penilaian_status
    ON penilaian_magang(status);

CREATE INDEX idx_verifikasi_penyelesaian_status
    ON verifikasi_penyelesaian_magang(status);


INSERT INTO schema_migrations (migration)
VALUES ('006_create_laporan_dan_penilaian.sql');

COMMIT;