-- 2. Mencegah penghapusan logbook harian setelah tanda tangan mitra

CREATE TRIGGER trg_logbook_harian_delete_lock
BEFORE DELETE
ON public.logbook_harian
FOR EACH ROW
EXECUTE FUNCTION public.prevent_logbook_harian_delete_after_partner_signature();