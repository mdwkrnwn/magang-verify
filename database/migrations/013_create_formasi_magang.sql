
BEGIN;

CREATE TABLE public.formasi_magang (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    mitra_id BIGINT NOT NULL
        REFERENCES public.mitra(id) ON DELETE RESTRICT,

    nama_posisi VARCHAR(150) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    deskripsi TEXT NOT NULL,
    kualifikasi TEXT,

    -- Kriteria mahasiswa yang dapat mendaftar
    program_studi TEXT[] NOT NULL DEFAULT '{}',
    keahlian TEXT[] NOT NULL DEFAULT '{}',

    kuota INTEGER NOT NULL CHECK (kuota > 0),

    lokasi VARCHAR(150) NOT NULL,
    durasi VARCHAR(100) NOT NULL,
    tahun_akademik VARCHAR(20) NOT NULL,
    periode_mulai DATE,
    periode_selesai DATE,

    status VARCHAR(30) NOT NULL DEFAULT 'tersedia'
        CHECK (
            status IN (
                'tersedia',
                'ditutup',
                'selesai'
            )
        ),

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT chk_formasi_periode
        CHECK (
            periode_mulai IS NULL
            OR periode_selesai IS NULL
            OR periode_selesai >= periode_mulai
        )
);

CREATE INDEX idx_formasi_mitra
    ON public.formasi_magang(mitra_id);

CREATE INDEX idx_formasi_status
    ON public.formasi_magang(status);

CREATE INDEX idx_formasi_tahun_akademik
    ON public.formasi_magang(tahun_akademik);

INSERT INTO schema_migrations (migration)
VALUES ('013_create_formasi_magang.sql');

COMMIT;