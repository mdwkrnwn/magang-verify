-- 5. Melindungi riwayat tanda tangan

CREATE TRIGGER trg_logbook_signature_immutable_update
BEFORE DELETE OR UPDATE
ON public.logbook_tanda_tangan
FOR EACH ROW
EXECUTE FUNCTION public.prevent_logbook_signature_mutation();