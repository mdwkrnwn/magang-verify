-- ============================================
-- VerifyMagang: Database Schema
-- Snapshot struktur database
-- ============================================

CREATE TABLE IF NOT EXISTS schema_migrations (
    id BIGSERIAL PRIMARY KEY,
    migration VARCHAR(255) NOT NULL UNIQUE,
    executed_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users (
    id BIGSERIAL PRIMARY KEY,
    login_id VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(150) NOT NULL,
    password VARCHAR(255),
    role VARCHAR(30) NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT users_role_check
        CHECK (
            role IN (
                'mahasiswa',
                'dosen',
                'koordinator_magang',
                'tendik',
                'mitra'
            )
        ),

    CONSTRAINT users_password_check
        CHECK (role = 'mitra' OR password IS NOT NULL)
);

-- =====================================================
-- TABEL PORTOFOLIOS
-- =====================================================

CREATE TABLE IF NOT EXISTS portofolios (
    id BIGSERIAL PRIMARY KEY,

    user_id BIGINT NOT NULL,

    judul VARCHAR(200) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,
    deskripsi TEXT NOT NULL,

    gambar_path VARCHAR(500),

    peran VARCHAR(100),
    tahun SMALLINT,

    tautan_github VARCHAR(500),
    tautan_demo VARCHAR(500),

    status_verifikasi VARCHAR(30)
        NOT NULL DEFAULT 'belum_terverifikasi',

    catatan_verifikasi TEXT,
    diverifikasi_oleh BIGINT,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_portofolio_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_portofolio_verifikator
        FOREIGN KEY (diverifikasi_oleh)
        REFERENCES users(id)
        ON DELETE SET NULL,

    CONSTRAINT chk_portofolio_status
        CHECK (
            status_verifikasi IN (
                'belum_terverifikasi',
                'dalam_peninjauan',
                'terverifikasi',
                'ditolak'
            )
        ),

    CONSTRAINT chk_portofolio_tahun
        CHECK (
            tahun IS NULL
            OR tahun BETWEEN 2000 AND 2100
        )
);

-- =====================================================
-- TABEL TEKNOLOGI PORTOFOLIO
-- =====================================================

CREATE TABLE IF NOT EXISTS portofolio_teknologi (
    id BIGSERIAL PRIMARY KEY,

    portofolio_id BIGINT NOT NULL,
    nama_teknologi VARCHAR(100) NOT NULL,

    CONSTRAINT fk_teknologi_portofolio
        FOREIGN KEY (portofolio_id)
        REFERENCES portofolios(id)
        ON DELETE CASCADE,

    CONSTRAINT uq_portofolio_teknologi
        UNIQUE (portofolio_id, nama_teknologi)
);

-- =====================================================
-- INDEX PORTOFOLIOS
-- =====================================================

CREATE INDEX IF NOT EXISTS idx_portofolios_user_id
    ON portofolios(user_id);

CREATE INDEX IF NOT EXISTS idx_portofolios_status
    ON portofolios(status_verifikasi);

CREATE INDEX IF NOT EXISTS idx_portofolio_teknologi_id
    ON portofolio_teknologi(portofolio_id);

-- =====================================================
-- TABEL SERTIFIKAT
-- =====================================================

CREATE TABLE IF NOT EXISTS sertifikat (
    id BIGSERIAL PRIMARY KEY,

    user_id BIGINT NOT NULL
        REFERENCES users(id)
        ON DELETE CASCADE,

    nama VARCHAR(200) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,
    penerbit VARCHAR(200) NOT NULL,
    tanggal_terbit DATE,
    nomor_sertifikat VARCHAR(150),

    -- Lokasi file sertifikat yang diunggah
    file_path VARCHAR(500),

    -- Status verifikasi sertifikat
    status_verifikasi VARCHAR(30) NOT NULL
        DEFAULT 'belum_terverifikasi',

    catatan_verifikasi TEXT,

    diverifikasi_oleh BIGINT
        REFERENCES users(id)
        ON DELETE SET NULL,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT sertifikat_status_check
        CHECK (
            status_verifikasi IN (
                'belum_terverifikasi',
                'dalam_peninjauan',
                'terverifikasi',
                'ditolak'
            )
        )
);

-- Indeks untuk pencarian sertifikat milik mahasiswa
CREATE INDEX IF NOT EXISTS idx_sertifikat_user_id
    ON sertifikat(user_id);

-- Indeks untuk filter tahun melalui tanggal terbit
CREATE INDEX IF NOT EXISTS idx_sertifikat_tanggal_terbit
    ON sertifikat(tanggal_terbit);

-- Indeks untuk filter status verifikasi
CREATE INDEX IF NOT EXISTS idx_sertifikat_status_verifikasi
    ON sertifikat(status_verifikasi);

-- Penambahan Constraint Foreign Key ke tabel portofolio_teknologi
ALTER TABLE portofolio_teknologi
ADD CONSTRAINT fk_portofolio_teknologi_portofolio
FOREIGN KEY (portofolio_id)
REFERENCES portofolios(id)
ON DELETE CASCADE;

-- =====================================================
-- MENAMBAH KOLOM TABEL SERTIFIKAT
-- =====================================================

ALTER TABLE sertifikat
ADD COLUMN IF NOT EXISTS deskripsi TEXT,
ADD COLUMN IF NOT EXISTS tautan VARCHAR(500);