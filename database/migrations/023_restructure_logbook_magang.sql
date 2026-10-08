BEGIN;

-- Migration ini dipertahankan sebagai compatibility marker.
-- Struktur Logbook V2 sudah diselesaikan oleh:
--   020_rebuild_logbook_workflow.sql
--   021_harden_logbook_workflow.sql
--   022_harden_logbook_structure.sql
--
-- Versi sebelumnya dari file ini menggunakan nama kolom validasi yang tidak
-- sesuai dengan schema Logbook V2 dan mencatat migration yang salah.
-- Jangan melakukan perubahan struktur tambahan di sini.

INSERT INTO public.schema_migrations (migration)
VALUES ('023_restructure_logbook_magang.sql')
ON CONFLICT (migration) DO NOTHING;

COMMIT;