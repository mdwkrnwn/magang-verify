-- 1. Validasi logbook harian

CREATE TRIGGER trg_logbook_harian_rules
BEFORE INSERT OR UPDATE
ON public.logbook_harian
FOR EACH ROW
EXECUTE FUNCTION public.enforce_logbook_harian_rules();