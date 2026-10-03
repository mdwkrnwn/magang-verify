BEGIN;

-- ============================================
-- DATA PERUSAHAAN / MITRA
-- ============================================

CREATE TABLE mitra (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    user_id BIGINT UNIQUE
        REFERENCES users(id) ON DELETE SET NULL,

    nama_perusahaan VARCHAR(200) NOT NULL,
    kode_perusahaan VARCHAR(50) NOT NULL UNIQUE,

    kategori VARCHAR(50) NOT NULL,
    bidang_usaha VARCHAR(150),
    deskripsi TEXT,

    email VARCHAR(255),
    no_telepon VARCHAR(30),
    website VARCHAR(255),

    provinsi VARCHAR(100),
    kota VARCHAR(100),
    alamat TEXT,

    status_verifikasi VARCHAR(30) NOT NULL
        DEFAULT 'dalam_peninjauan',

    catatan_verifikasi TEXT,
    diverifikasi_oleh BIGINT
        REFERENCES users(id) ON DELETE SET NULL,
    diverifikasi_pada TIMESTAMPTZ,

    is_active BOOLEAN NOT NULL DEFAULT TRUE,

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT mitra_kategori_check
        CHECK (
            kategori IN (
                'BUMN',
                'BUMD',
                'Perusahaan Swasta',
                'Instansi Pemerintah',
                'Institusi Pendidikan',
                'UMKM',
                'Organisasi',
                'Lainnya'
            )
        ),

    CONSTRAINT mitra_status_verifikasi_check
        CHECK (
            status_verifikasi IN (
                'dalam_peninjauan',
                'terverifikasi',
                'ditolak'
            )
        )
);

CREATE INDEX idx_mitra_nama
    ON mitra(nama_perusahaan);

CREATE INDEX idx_mitra_status_verifikasi
    ON mitra(status_verifikasi);

CREATE INDEX idx_mitra_kota
    ON mitra(kota);

-- ============================================
-- FORMASI / LOWONGAN MAGANG
-- ============================================

CREATE TABLE formasi_magang (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    mitra_id BIGINT NOT NULL
        REFERENCES mitra(id) ON DELETE RESTRICT,

    judul VARCHAR(200) NOT NULL,
    deskripsi TEXT NOT NULL,
    bidang VARCHAR(150),

    jumlah_kuota SMALLINT NOT NULL,
    jumlah_diterima SMALLINT NOT NULL DEFAULT 0,

    persyaratan TEXT,
    lokasi_magang VARCHAR(255),
    sistem_kerja VARCHAR(30) NOT NULL DEFAULT 'onsite',

    tanggal_mulai DATE NOT NULL,
    tanggal_selesai DATE NOT NULL,

    tahun_akademik VARCHAR(20) NOT NULL,

    status VARCHAR(30) NOT NULL DEFAULT 'draft',

    dibuat_oleh BIGINT
        REFERENCES users(id) ON DELETE SET NULL,

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT formasi_kuota_check
        CHECK (
            jumlah_kuota > 0
            AND jumlah_diterima >= 0
            AND jumlah_diterima <= jumlah_kuota
        ),

    CONSTRAINT formasi_tanggal_check
        CHECK (tanggal_selesai >= tanggal_mulai),

    CONSTRAINT formasi_sistem_kerja_check
        CHECK (
            sistem_kerja IN (
                'onsite',
                'hybrid',
                'remote'
            )
        ),

    CONSTRAINT formasi_status_check
        CHECK (
            status IN (
                'draft',
                'dibuka',
                'ditutup',
                'selesai',
                'dibatalkan'
            )
        )
);

CREATE INDEX idx_formasi_mitra
    ON formasi_magang(mitra_id);

CREATE INDEX idx_formasi_status
    ON formasi_magang(status);

CREATE INDEX idx_formasi_periode
    ON formasi_magang(tanggal_mulai, tanggal_selesai);

CREATE INDEX idx_formasi_tahun_akademik
    ON formasi_magang(tahun_akademik);


INSERT INTO schema_migrations (migration)
VALUES ('002_create_mitra_and_formasi.sql');

COMMIT;