
BEGIN;

-- ============================================
-- LOGBOOK MINGGUAN
-- ============================================

CREATE TABLE logbook_mingguan (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    penempatan_id BIGINT NOT NULL
        REFERENCES penempatan_magang(id) ON DELETE RESTRICT,

    minggu_ke SMALLINT NOT NULL,

    tanggal_mulai DATE NOT NULL,
    tanggal_selesai DATE NOT NULL,

    versi_terkini INTEGER NOT NULL DEFAULT 1,

    status VARCHAR(30) NOT NULL DEFAULT 'draft',

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT logbook_minggu_check
        CHECK (minggu_ke > 0),

    CONSTRAINT logbook_tanggal_check
        CHECK (tanggal_selesai >= tanggal_mulai),

    CONSTRAINT logbook_versi_check
        CHECK (versi_terkini > 0),

    CONSTRAINT logbook_status_check
        CHECK (
            status IN (
                'draft',
                'diajukan',
                'menunggu_mitra',
                'menunggu_dosen',
                'menunggu_verifikasi',
                'perlu_revisi',
                'disetujui',
                'ditolak'
            )
        ),

    CONSTRAINT logbook_minggu_unik
        UNIQUE (penempatan_id, minggu_ke)
);


-- ============================================
-- RIWAYAT / VERSI LOGBOOK
-- Setiap perubahan isi dibuat sebagai versi baru.
-- ============================================

CREATE TABLE logbook_revisi (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    logbook_id BIGINT NOT NULL
        REFERENCES logbook_mingguan(id) ON DELETE RESTRICT,

    nomor_versi INTEGER NOT NULL,

    aktivitas TEXT NOT NULL,
    hasil_pekerjaan TEXT,
    kendala TEXT,
    rencana_selanjutnya TEXT,

    catatan_revisi TEXT,

    dibuat_oleh BIGINT
        REFERENCES users(id) ON DELETE SET NULL,

    dibuat_pada TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT logbook_revisi_versi_check
        CHECK (nomor_versi > 0),

    CONSTRAINT logbook_revisi_unik
        UNIQUE (logbook_id, nomor_versi)
);


-- ============================================
-- TANDA TANGAN / PERSETUJUAN LOGBOOK
-- Tanda tangan mengacu ke versi tertentu.
-- ============================================

CREATE TABLE logbook_tanda_tangan (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    revisi_id BIGINT NOT NULL
        REFERENCES logbook_revisi(id) ON DELETE RESTRICT,

    tahap VARCHAR(30) NOT NULL,

    penanda_tangan_id BIGINT NOT NULL
        REFERENCES users(id) ON DELETE RESTRICT,

    keputusan VARCHAR(20) NOT NULL,

    catatan TEXT,

    ditandatangani_pada TIMESTAMPTZ NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT logbook_tahap_check
        CHECK (
            tahap IN (
                'mahasiswa',
                'mitra',
                'dosen',
                'koordinator',
                'tendik'
            )
        ),

    CONSTRAINT logbook_keputusan_check
        CHECK (
            keputusan IN (
                'disetujui',
                'perlu_revisi',
                'ditolak'
            )
        ),

    CONSTRAINT logbook_tanda_tangan_unik
        UNIQUE (revisi_id, tahap)
);


-- ============================================
-- INDEX
-- ============================================

CREATE INDEX idx_logbook_penempatan
    ON logbook_mingguan(penempatan_id);

CREATE INDEX idx_logbook_status
    ON logbook_mingguan(status);

CREATE INDEX idx_logbook_revisi_logbook
    ON logbook_revisi(logbook_id, nomor_versi);

CREATE INDEX idx_logbook_ttd_revisi
    ON logbook_tanda_tangan(revisi_id);

CREATE INDEX idx_logbook_ttd_penanda_tangan
    ON logbook_tanda_tangan(penanda_tangan_id);


INSERT INTO schema_migrations (migration)
VALUES ('005_create_logbook_magang.sql');

COMMIT;