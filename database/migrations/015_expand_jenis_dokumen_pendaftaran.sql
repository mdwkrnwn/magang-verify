BEGIN;

ALTER TABLE public.dokumen_pendaftaran_magang
    DROP CONSTRAINT IF EXISTS dokumen_pendaftaran_jenis_check;

ALTER TABLE public.dokumen_pendaftaran_magang
    ADD CONSTRAINT dokumen_pendaftaran_jenis_check
    CHECK (jenis_dokumen IN (
        'pakta_integritas',
        'daftar_riwayat_hidup',
        'khs',
        'ktp',
        'ktm',
        'surat_izin_orang_tua',
        'bpjs',
        'sktm_kip',
        'proposal',
        'sertifikat_kompetensi'
    ));

INSERT INTO public.schema_migrations (migration)
VALUES ('015_expand_jenis_dokumen_pendaftaran.sql')
ON CONFLICT (migration) DO NOTHING;

COMMIT;
