-- 3. Validasi jadwal logbook mingguan

CREATE TRIGGER trg_logbook_week_schedule
BEFORE INSERT OR UPDATE
ON public.logbook_mingguan
FOR EACH ROW
EXECUTE FUNCTION public.enforce_logbook_week_schedule();