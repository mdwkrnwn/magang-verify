CREATE OR REPLACE FUNCTION public.prevent_logbook_harian_delete_after_partner_signature()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_status VARCHAR(30);
BEGIN
    SELECT status
      INTO v_status
      FROM public.logbook_mingguan
     WHERE id = OLD.logbook_id;

    IF v_status IN ('menunggu_dosen', 'disetujui') THEN
        RAISE EXCEPTION 'Logbook harian terkunci karena mitra sudah menandatangani.';
    END IF;

    RETURN OLD;
END;
$function$;