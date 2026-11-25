create or replace
function public.prevent_signed_logbook_revision_mutation()
 returns trigger
 language plpgsql
as $function$
begin
    if exists (
select
	1
from
	public.logbook_tanda_tangan
where
	revisi_id = OLD.id
    ) then
        raise exception 'Versi logbook yang sudah ditandatangani tidak boleh diubah atau dihapus.';
end if;

if TG_OP = 'DELETE' then
        return old;
end if;

return new;
end;

$function$;