BEGIN;

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

COMMIT;