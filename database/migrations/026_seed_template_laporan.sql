BEGIN;

INSERT INTO public.template_laporan (
    jenis_laporan,
    nama_template,
    file_template,
    versi,
    status_aktif
)
VALUES (
    'laporan_akhir',
    'Template Laporan Magang Industri POLINEMA',
    'storage/templates/laporan/Template_Laporan_Magang_Industri_Polinema.docx',
    1,
    TRUE
);

INSERT INTO public.schema_migrations (migration)
VALUES ('026_seed_template_laporan.sql')
ON CONFLICT (migration) DO NOTHING;

COMMIT;