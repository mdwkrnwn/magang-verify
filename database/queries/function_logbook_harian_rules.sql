CREATE OR REPLACE FUNCTION public.enforce_logbook_harian_rules()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_mulai DATE;
    v_selesai DATE;
    v_status VARCHAR(30);
BEGIN
    SELECT tanggal_mulai, tanggal_selesai, status
      INTO v_mulai, v_selesai, v_status
      FROM public.logbook_mingguan
     WHERE id = NEW.logbook_id;

    IF NOT FOUND THEN
        RAISE EXCEPTION 'Minggu logbook tidak ditemukan.';
    END IF;

    IF NEW.tanggal < v_mulai OR NEW.tanggal > v_selesai THEN
        RAISE EXCEPTION 'Tanggal logbook harian harus berada di dalam periode minggu.';
    END IF;

    IF NEW.tanggal > CURRENT_DATE THEN
        RAISE EXCEPTION 'Logbook harian tidak boleh dibuat untuk tanggal masa depan.';
    END IF;

    IF v_status IN ('menunggu_dosen', 'disetujui') THEN
        RAISE EXCEPTION 'Logbook terkunci karena mitra sudah menandatangani.';
    END IF;

    RETURN NEW;
END;
$function$;