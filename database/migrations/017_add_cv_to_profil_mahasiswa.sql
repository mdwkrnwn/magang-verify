BEGIN;

-- Finalisasi constraint dokumen pendaftaran.
-- Migration 015 lama menggunakan nama/nilai lama; migration ini
-- memastikan database tim memiliki satu constraint dengan nilai final.
ALTER TABLE public.dokumen_pendaftaran_magang
    DROP CONSTRAINT IF EXISTS dokumen_pendaftaran_jenis_check;

ALTER TABLE public.dokumen_pendaftaran_magang
    DROP CONSTRAINT IF EXISTS dokumen_pendaftaran_magang_jenis_dokumen_check;

ALTER TABLE public.dokumen_pendaftaran_magang
    ADD CONSTRAINT dokumen_pendaftaran_magang_jenis_dokumen_check
    CHECK (
        jenis_dokumen IN (
            'pakta_integritas',
            'daftar_riwayat_hidup',
            'khs',
            'ktp',
            'ktm',
            'surat_izin_orang_tua',
            'bpjs_asuransi',
            'sktm_kip',
            'proposal_magang',
            'sertifikat_kompetensi'
        )
    );

-- Kolom CV profil mahasiswa.
ALTER TABLE public.profil_mahasiswa
    ADD COLUMN IF NOT EXISTS cv_path VARCHAR(500),
    ADD COLUMN IF NOT EXISTS cv_nama_asli VARCHAR(255),
    ADD COLUMN IF NOT EXISTS cv_mime_type VARCHAR(100),
    ADD COLUMN IF NOT EXISTS cv_ukuran_bytes BIGINT,
    ADD COLUMN IF NOT EXISTS cv_updated_at TIMESTAMPTZ;

ALTER TABLE public.profil_mahasiswa
    DROP CONSTRAINT IF EXISTS profil_mahasiswa_cv_ukuran_check;

ALTER TABLE public.profil_mahasiswa
    ADD CONSTRAINT profil_mahasiswa_cv_ukuran_check
    CHECK (
        cv_ukuran_bytes IS NULL
        OR (
            cv_ukuran_bytes > 0
            AND cv_ukuran_bytes <= 5242880
        )
    );

-- 016 sebelumnya sudah dijalankan manual di database saat perbaikan
-- constraint, tetapi belum tercatat di schema_migrations.
INSERT INTO public.schema_migrations (migration)
VALUES ('016_add_constraint_dokumen_pendaftaran_jenis_check.sql')
ON CONFLICT (migration) DO NOTHING;

INSERT INTO public.schema_migrations (migration)
VALUES ('017_add_cv_to_profil_mahasiswa.sql')
ON CONFLICT (migration) DO NOTHING;

COMMIT;
