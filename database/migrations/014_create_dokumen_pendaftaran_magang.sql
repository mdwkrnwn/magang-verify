BEGIN;

CREATE TABLE IF NOT EXISTS public.dokumen_pendaftaran_magang (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    pendaftaran_id BIGINT NOT NULL REFERENCES public.pendaftaran_magang(id) ON DELETE RESTRICT,
    jenis_dokumen VARCHAR(40) NOT NULL,
    nama_asli VARCHAR(255) NOT NULL,
    path_file VARCHAR(500) NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    ukuran_bytes BIGINT NOT NULL CHECK (ukuran_bytes > 0),
    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT dokumen_pendaftaran_jenis_check CHECK (jenis_dokumen IN ('pakta_integritas', 'daftar_riwayat_hidup', 'khs', 'ktp', 'ktm', 'surat_izin_orang_tua', 'bpjs', 'sktm_kip', 'proposal', 'sertifikat_kompetensi')),
    CONSTRAINT dokumen_pendaftaran_unique_jenis UNIQUE (pendaftaran_id, jenis_dokumen)
);

CREATE INDEX IF NOT EXISTS idx_dokumen_pendaftaran_magang
    ON public.dokumen_pendaftaran_magang(pendaftaran_id);

INSERT INTO public.schema_migrations (migration)
VALUES ('014_create_dokumen_pendaftaran_magang.sql')
ON CONFLICT (migration) DO NOTHING;

COMMIT;
