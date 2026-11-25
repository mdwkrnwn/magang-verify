-- 4. Melindungi revisi yang sudah ditandatangani

CREATE TRIGGER trg_signed_logbook_revision_mutation
BEFORE DELETE OR UPDATE
ON public.logbook_revisi
FOR EACH ROW
EXECUTE FUNCTION public.prevent_signed_logbook_revision_mutation();