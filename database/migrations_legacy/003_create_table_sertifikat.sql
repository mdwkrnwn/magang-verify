-- Migration: Membuat tabel sertifikat

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