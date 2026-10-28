BEGIN;

ALTER TABLE public.sertifikat
    DROP CONSTRAINT IF EXISTS sertifikat_status_verifikasi_check;

ALTER TABLE public.sertifikat
    ADD CONSTRAINT sertifikat_status_verifikasi_check
    CHECK (
        status_verifikasi IN (
            'belum_terverifikasi',
            'dalam_peninjauan',
            'terverifikasi',
            'ditolak',
            'perlu_perbaikan'
        )
    );

INSERT INTO schema_migrations (migration)
VALUES ('031_add_sertifikat_perlu_perbaikan.sql')
ON CONFLICT (migration) DO NOTHING;

COMMIT;
