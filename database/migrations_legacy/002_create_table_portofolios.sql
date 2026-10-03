-- =====================================================
-- 1. TABEL PORTOFOLIOS
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
-- 2. TABEL TEKNOLOGI PORTOFOLIO
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
-- 3. INDEX
-- =====================================================

CREATE INDEX IF NOT EXISTS idx_portofolios_user_id
    ON portofolios(user_id);

CREATE INDEX IF NOT EXISTS idx_portofolios_status
    ON portofolios(status_verifikasi);

CREATE INDEX IF NOT EXISTS idx_portofolio_teknologi_id
    ON portofolio_teknologi(portofolio_id);