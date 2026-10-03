
BEGIN;

-- ============================================
-- PORTOFOLIO MAHASISWA
-- ============================================

CREATE TABLE portofolios (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    mahasiswa_id BIGINT NOT NULL
        REFERENCES profil_mahasiswa(id) ON DELETE RESTRICT,

    judul VARCHAR(200) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,

    jenis VARCHAR(30) NOT NULL,

    deskripsi TEXT NOT NULL,
    tanggal_perolehan DATE,

    penyelenggara VARCHAR(200),
    tautan VARCHAR(500),

    gambar_sampul VARCHAR(500),

    status_verifikasi VARCHAR(30) NOT NULL
        DEFAULT 'belum_diverifikasi',

    catatan_verifikasi TEXT,

    diverifikasi_oleh BIGINT
        REFERENCES users(id) ON DELETE SET NULL,

    diverifikasi_pada TIMESTAMPTZ,

    status_publikasi VARCHAR(20) NOT NULL
        DEFAULT 'draft',

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT portofolio_jenis_check
        CHECK (
            jenis IN (
                'proyek',
                'sertifikat',
                'prestasi',
                'pengalaman',
                'karya'
            )
        ),

    CONSTRAINT portofolio_verifikasi_check
        CHECK (
            status_verifikasi IN (
                'belum_diverifikasi',
                'terverifikasi',
                'ditolak',
                'perlu_perbaikan'
            )
        ),

    CONSTRAINT portofolio_publikasi_check
        CHECK (
            status_publikasi IN (
                'draft',
                'publik',
                'arsip'
            )
        )
);


-- ============================================
-- BERKAS PENDUKUNG PORTOFOLIO
-- ============================================

CREATE TABLE berkas_portofolio (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    portofolio_id BIGINT NOT NULL
        REFERENCES portofolios(id) ON DELETE RESTRICT,

    nama_berkas VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    tipe_mime VARCHAR(100),
    ukuran_byte BIGINT,

    diunggah_pada TIMESTAMPTZ NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT berkas_portofolio_ukuran_check
        CHECK (ukuran_byte IS NULL OR ukuran_byte >= 0)
);


-- ============================================
-- KETERAMPILAN MAHASISWA
-- ============================================

CREATE TABLE keterampilan (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    nama VARCHAR(100) NOT NULL UNIQUE,
    kategori VARCHAR(50),

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP
);


-- ============================================
-- RELASI MAHASISWA DAN KETERAMPILAN
-- ============================================

CREATE TABLE mahasiswa_keterampilan (
    mahasiswa_id BIGINT NOT NULL
        REFERENCES profil_mahasiswa(id) ON DELETE RESTRICT,

    keterampilan_id BIGINT NOT NULL
        REFERENCES keterampilan(id) ON DELETE RESTRICT,

    tingkat VARCHAR(20),
    keterangan TEXT,

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (mahasiswa_id, keterampilan_id),

    CONSTRAINT mahasiswa_keterampilan_tingkat_check
        CHECK (
            tingkat IS NULL
            OR tingkat IN (
                'pemula',
                'menengah',
                'mahir'
            )
        )
);


-- ============================================
-- RIWAYAT VERIFIKASI PORTOFOLIO
-- ============================================

CREATE TABLE riwayat_verifikasi_portofolio (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    portofolio_id BIGINT NOT NULL
        REFERENCES portofolios(id) ON DELETE RESTRICT,

    status_sebelumnya VARCHAR(30),
    status_baru VARCHAR(30) NOT NULL,

    catatan TEXT,

    diverifikasi_oleh BIGINT
        REFERENCES users(id) ON DELETE SET NULL,

    waktu_verifikasi TIMESTAMPTZ NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT riwayat_portofolio_status_check
        CHECK (
            status_baru IN (
                'belum_diverifikasi',
                'terverifikasi',
                'ditolak',
                'perlu_perbaikan'
            )
        )
);


-- ============================================
-- INDEX
-- ============================================

CREATE INDEX idx_portofolio_mahasiswa
    ON portofolios(mahasiswa_id);

CREATE INDEX idx_portofolio_jenis
    ON portofolios(jenis);

CREATE INDEX idx_portofolio_verifikasi
    ON portofolios(status_verifikasi);

CREATE INDEX idx_portofolio_publikasi
    ON portofolios(status_publikasi);

CREATE INDEX idx_berkas_portofolio
    ON berkas_portofolio(portofolio_id);

CREATE INDEX idx_mahasiswa_keterampilan
    ON mahasiswa_keterampilan(keterampilan_id);

CREATE INDEX idx_riwayat_verifikasi_portofolio
    ON riwayat_verifikasi_portofolio(
        portofolio_id,
        waktu_verifikasi
    );


INSERT INTO schema_migrations (migration)
VALUES ('007_create_portofolio_mahasiswa.sql');

COMMIT;