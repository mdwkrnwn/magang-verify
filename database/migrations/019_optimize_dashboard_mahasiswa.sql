BEGIN;

-- Index tambahan untuk query dashboard mahasiswa.
-- Tidak membuat tabel baru karena kebutuhan dashboard sudah ditopang
-- oleh tabel portofolio, sertifikat, pengalaman, pendaftaran, penempatan,
-- logbook, notifikasi, dan aktivitas yang sudah tersedia.

CREATE INDEX IF NOT EXISTS idx_portofolios_mahasiswa_created
    ON public.portofolios(mahasiswa_id, created_at DESC);

CREATE INDEX IF NOT EXISTS idx_portofolios_mahasiswa_status
    ON public.portofolios(mahasiswa_id, status_publikasi, status_verifikasi);

CREATE INDEX IF NOT EXISTS idx_sertifikat_user_created
    ON public.sertifikat(user_id, created_at DESC);

CREATE INDEX IF NOT EXISTS idx_pengalaman_mahasiswa_created
    ON public.pengalaman(mahasiswa_id, created_at DESC);

CREATE INDEX IF NOT EXISTS idx_logbook_penempatan_created
    ON public.logbook_mingguan(penempatan_id, created_at DESC);

INSERT INTO public.schema_migrations (migration)
VALUES ('019_optimize_dashboard_mahasiswa.sql')
ON CONFLICT (migration) DO NOTHING;

COMMIT;
