-- ============================================================
-- Migration 025
-- Seed Template Laporan Mingguan
-- ============================================================

INSERT INTO template_laporan (
    jenis_laporan,
    nama_template,
    file_template,
    versi,
    status_aktif
)
VALUES (
    'laporan_mingguan',
    'Template Laporan Mingguan Magang Industri POLINEMA',
    'storage/templates/laporan/Template_Laporan_Mingguan_Magang_Industri_Polinema.docx',
    1,
    TRUE
)
ON CONFLICT DO NOTHING;


-- Catat migration
INSERT INTO public.schema_migrations (migration)
VALUES ('027_seed_template_laporan_mingguan.sql')
ON CONFLICT (migration) DO NOTHING;