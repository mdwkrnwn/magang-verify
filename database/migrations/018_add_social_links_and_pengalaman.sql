BEGIN;

ALTER TABLE public.profil_mahasiswa
    ADD COLUMN IF NOT EXISTS github_url VARCHAR(500),
    ADD COLUMN IF NOT EXISTS linkedin_url VARCHAR(500),
    ADD COLUMN IF NOT EXISTS portfolio_url VARCHAR(500);

CREATE TABLE IF NOT EXISTS public.pengalaman (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    mahasiswa_id BIGINT NOT NULL REFERENCES public.profil_mahasiswa(id) ON DELETE RESTRICT,
    pendaftaran_id BIGINT UNIQUE REFERENCES public.pendaftaran_magang(id) ON DELETE SET NULL,
    jenis VARCHAR(30) NOT NULL DEFAULT 'lainnya',
    posisi VARCHAR(200) NOT NULL,
    instansi VARCHAR(200) NOT NULL,
    lokasi VARCHAR(200),
    deskripsi TEXT,
    tanggal_mulai DATE,
    tanggal_selesai DATE,
    status_publikasi VARCHAR(20) NOT NULL DEFAULT 'publik',
    is_otomatis BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pengalaman_jenis_check CHECK (jenis IN ('magang','pekerjaan','organisasi','freelance','proyek','lainnya')),
    CONSTRAINT pengalaman_publikasi_check CHECK (status_publikasi IN ('draft','publik','arsip')),
    CONSTRAINT pengalaman_tanggal_check CHECK (tanggal_selesai IS NULL OR tanggal_mulai IS NULL OR tanggal_selesai >= tanggal_mulai)
);

CREATE INDEX IF NOT EXISTS idx_pengalaman_mahasiswa ON public.pengalaman(mahasiswa_id);
CREATE INDEX IF NOT EXISTS idx_pengalaman_publikasi ON public.pengalaman(status_publikasi);
CREATE INDEX IF NOT EXISTS idx_pengalaman_pendaftaran ON public.pengalaman(pendaftaran_id);

INSERT INTO public.schema_migrations (migration)
VALUES ('018_add_social_links_and_pengalaman.sql')
ON CONFLICT (migration) DO NOTHING;

COMMIT;
