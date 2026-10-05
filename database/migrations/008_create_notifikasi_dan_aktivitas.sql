
BEGIN;

-- ============================================
-- NOTIFIKASI DALAM APLIKASI
-- ============================================

CREATE TABLE notifikasi (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    user_id BIGINT NOT NULL
        REFERENCES users(id) ON DELETE CASCADE,

    judul VARCHAR(200) NOT NULL,
    pesan TEXT NOT NULL,

    jenis VARCHAR(50) NOT NULL,

    referensi_tabel VARCHAR(100),
    referensi_id BIGINT,

    tautan VARCHAR(500),

    sudah_dibaca BOOLEAN NOT NULL DEFAULT FALSE,
    dibaca_pada TIMESTAMPTZ,

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT notifikasi_jenis_check
        CHECK (
            jenis IN (
                'pendaftaran',
                'persetujuan',
                'logbook',
                'laporan',
                'penilaian',
                'portofolio',
                'verifikasi',
                'sistem'
            )
        ),

    CONSTRAINT notifikasi_status_baca_check
        CHECK (
            (sudah_dibaca = FALSE AND dibaca_pada IS NULL)
            OR
            (sudah_dibaca = TRUE AND dibaca_pada IS NOT NULL)
        )
);


-- ============================================
-- RIWAYAT AKTIVITAS PENGGUNA
-- ============================================

CREATE TABLE aktivitas_pengguna (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    user_id BIGINT
        REFERENCES users(id) ON DELETE SET NULL,

    aksi VARCHAR(100) NOT NULL,
    deskripsi TEXT NOT NULL,

    modul VARCHAR(100),

    referensi_tabel VARCHAR(100),
    referensi_id BIGINT,

    ip_address INET,

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP
);


-- ============================================
-- INDEX
-- ============================================

CREATE INDEX idx_notifikasi_user_waktu
    ON notifikasi(user_id, created_at DESC);

CREATE INDEX idx_notifikasi_belum_dibaca
    ON notifikasi(user_id, created_at DESC)
    WHERE sudah_dibaca = FALSE;

CREATE INDEX idx_aktivitas_user_waktu
    ON aktivitas_pengguna(user_id, created_at DESC);

CREATE INDEX idx_aktivitas_modul_waktu
    ON aktivitas_pengguna(modul, created_at DESC);


INSERT INTO schema_migrations (migration)
VALUES ('008_create_notifikasi_dan_aktivitas.sql');

COMMIT;