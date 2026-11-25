-- 6. Validasi alur tanda tangan
CREATE TRIGGER trg_logbook_signature_workflow
BEFORE INSERT
ON public.logbook_tanda_tangan
FOR EACH ROW
EXECUTE FUNCTION public.enforce_logbook_signature_workflow();

SELECT
    event_object_table AS nama_tabel,
    trigger_name,
    event_manipulation AS event
FROM information_schema.triggers
WHERE trigger_schema = 'public'
  AND event_object_table IN (
      'logbook_harian',
      'logbook_mingguan',
      'logbook_revisi',
      'logbook_tanda_tangan'
  )
ORDER BY event_object_table, trigger_name;



SELECT
    routine_name,
    routine_type
FROM information_schema.routines
WHERE routine_schema = 'public'
  AND routine_name IN (
      'enforce_logbook_harian_rules',
      'enforce_logbook_signature_workflow',
      'enforce_logbook_week_schedule',
      'prevent_logbook_harian_delete_after_partner_signature',
      'prevent_logbook_signature_mutation',
      'prevent_signed_logbook_revision_mutation'
  )
ORDER BY routine_name;

SELECT pg_get_viewdef(
    'public.view_rekap_magang_koordinator'::regclass,
    true
);