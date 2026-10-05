
BEGIN;

-- ============================================
-- PENDAFTARAN MAGANG MAHASISWA
-- ============================================

CREATE TABLE pendaftaran_magang (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    mahasiswa_id BIGINT NOT NULL
        REFERENCES profil_mahasiswa(id) ON DELETE RESTRICT,

    formasi_id BIGINT NOT NULL
        REFERENCES formasi_magang(id) ON DELETE RESTRICT,

    -- Status proses persetujuan dosen
    status_persetujuan_dosen VARCHAR(30) NOT NULL
        DEFAULT 'menunggu',

    dosen_penyetuju_id BIGINT
        REFERENCES users(id) ON DELETE SET NULL,

    waktu_persetujuan_dosen TIMESTAMPTZ,
    catatan_dosen TEXT,

    -- Status keputusan perusahaan
    status_respons_mitra VARCHAR(30) NOT NULL
        DEFAULT 'menunggu',

    waktu_respons_mitra TIMESTAMPTZ,
    catatan_mitra TEXT,
    jadwal_seleksi TIMESTAMPTZ,

    -- Status keseluruhan pendaftaran
    status_pendaftaran VARCHAR(30) NOT NULL
        DEFAULT 'diajukan',

    tanggal_pengajuan TIMESTAMPTZ NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    tanggal_selesai_proses TIMESTAMPTZ,

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT pendaftaran_status_dosen_check
        CHECK (
            status_persetujuan_dosen IN (
                'menunggu',
                'disetujui',
                'ditolak',
                'perlu_revisi'
            )
        ),

    CONSTRAINT pendaftaran_status_mitra_check
        CHECK (
            status_respons_mitra IN (
                'menunggu',
                'diterima',
                'ditolak',
                'seleksi'
            )
        ),

    CONSTRAINT pendaftaran_status_check
    CHECK (
        status_pendaftaran IN (
            'diajukan',
            'menunggu_persetujuan_dosen',
            'menunggu_respons_mitra',
            'seleksi',
            'diterima',
            'ditolak_dosen',
            'ditolak_mitra',
            'perlu_revisi',
            'dibatalkan',
            'selesai'
        )
    )
);


-- ============================================
-- RIWAYAT PERUBAHAN STATUS PENDAFTARAN
-- ============================================

CREATE TABLE riwayat_pendaftaran_magang (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    pendaftaran_id BIGINT NOT NULL
        REFERENCES pendaftaran_magang(id) ON DELETE RESTRICT,

    status_sebelumnya VARCHAR(30),
    status_baru VARCHAR(30) NOT NULL,

    catatan TEXT,

    diubah_oleh BIGINT
        REFERENCES users(id) ON DELETE SET NULL,

    waktu_perubahan TIMESTAMPTZ NOT NULL
        DEFAULT CURRENT_TIMESTAMP
);


-- ============================================
-- INDEX
-- ============================================

CREATE INDEX idx_pendaftaran_mahasiswa
    ON pendaftaran_magang(mahasiswa_id);

CREATE INDEX idx_pendaftaran_formasi
    ON pendaftaran_magang(formasi_id);

CREATE INDEX idx_pendaftaran_status
    ON pendaftaran_magang(status_pendaftaran);

CREATE INDEX idx_pendaftaran_dosen
    ON pendaftaran_magang(dosen_penyetuju_id);

CREATE INDEX idx_riwayat_pendaftaran
    ON riwayat_pendaftaran_magang(pendaftaran_id, waktu_perubahan);


-- ============================================
-- BATASI PENDAFTARAN AKTIF MAHASISWA
-- ============================================

CREATE UNIQUE INDEX uq_pendaftaran_mahasiswa_aktif
    ON pendaftaran_magang(mahasiswa_id)
    WHERE status_pendaftaran IN (
        'diajukan',
        'menunggu_persetujuan_dosen',
        'menunggu_respons_mitra',
        'seleksi',
        'diterima',
        'perlu_revisi'
    );


INSERT INTO schema_migrations (migration)
VALUES ('003_create_pendaftaran_magang.sql');

COMMIT;