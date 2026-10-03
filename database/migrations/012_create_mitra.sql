
BEGIN;

CREATE TABLE public.mitra (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    nama VARCHAR(150) NOT NULL,
    slug VARCHAR(180) NOT NULL UNIQUE,
    kategori VARCHAR(50) NOT NULL,
    bidang_industri VARCHAR(100),
    deskripsi TEXT,

    email VARCHAR(150),
    telepon VARCHAR(30),
    website VARCHAR(255),
    logo_path TEXT,

    provinsi VARCHAR(100),
    kota VARCHAR(100),
    alamat TEXT NOT NULL,

    status_verifikasi VARCHAR(30) NOT NULL DEFAULT 'dalam_peninjauan'
        CHECK (
            status_verifikasi IN (
                'dalam_peninjauan',
                'terverifikasi',
                'ditolak'
            )
        ),

    catatan_verifikasi TEXT,
    diverifikasi_oleh BIGINT
        REFERENCES public.users(id) ON DELETE RESTRICT,
    diverifikasi_pada TIMESTAMPTZ,

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_mitra_status_verifikasi
    ON public.mitra(status_verifikasi);

CREATE INDEX idx_mitra_kategori
    ON public.mitra(kategori);

CREATE INDEX idx_mitra_kota
    ON public.mitra(kota);

INSERT INTO schema_migrations (migration)
VALUES ('012_create_mitra.sql');

COMMIT;