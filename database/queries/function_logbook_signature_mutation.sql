CREATE OR REPLACE FUNCTION public.prevent_logbook_signature_mutation()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
BEGIN
    RAISE EXCEPTION 'Riwayat tanda tangan logbook tidak boleh diubah atau dihapus.';
END;
$function$;