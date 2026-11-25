CREATE OR REPLACE FUNCTION public.enforce_logbook_week_schedule()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_penempatan_mulai DATE;
    v_penempatan_selesai DATE;
    v_expected_week SMALLINT;
    v_expected_start DATE;
    v_expected_end DATE;
    v_previous_end DATE;
    v_has_previous BOOLEAN;
BEGIN
    SELECT tanggal_mulai, tanggal_selesai
      INTO v_penempatan_mulai, v_penempatan_selesai
      FROM public.penempatan_magang
     WHERE id = NEW.penempatan_id;

    IF NOT FOUND THEN
        RAISE EXCEPTION 'Penempatan magang tidak ditemukan.';
    END IF;

    SELECT EXISTS (
        SELECT 1
        FROM public.logbook_mingguan
        WHERE penempatan_id = NEW.penempatan_id
          AND id <> COALESCE(NEW.id, 0)
    ) INTO v_has_previous;

    IF v_has_previous THEN
        SELECT minggu_ke, tanggal_selesai
          INTO v_expected_week, v_previous_end
          FROM public.logbook_mingguan
         WHERE penempatan_id = NEW.penempatan_id
           AND id <> COALESCE(NEW.id, 0)
         ORDER BY minggu_ke DESC
         LIMIT 1;

        v_expected_week := v_expected_week + 1;
        v_expected_start := v_previous_end + 1;
        v_expected_end := LEAST(v_expected_start + 6, v_penempatan_selesai);
    ELSE
        v_expected_week := 1;
        v_expected_start := v_penempatan_mulai;
        v_expected_end := LEAST(
            v_expected_start + ((7 - EXTRACT(DOW FROM v_expected_start)::INTEGER) % 7),
            v_penempatan_selesai
        );
    END IF;

    IF TG_OP = 'INSERT' THEN
        IF NEW.minggu_ke <> v_expected_week
           OR NEW.tanggal_mulai <> v_expected_start
           OR NEW.tanggal_selesai <> v_expected_end THEN
            RAISE EXCEPTION
                'Periode Minggu % tidak sesuai dengan jadwal penempatan. Periode yang benar: % sampai %.',
                v_expected_week, v_expected_start, v_expected_end;
        END IF;

        IF NEW.tanggal_mulai > CURRENT_DATE THEN
            RAISE EXCEPTION 'Minggu logbook masa depan tidak dapat dibuat.';
        END IF;
    ELSE
        IF NEW.penempatan_id <> OLD.penempatan_id
           OR NEW.minggu_ke <> OLD.minggu_ke
           OR NEW.tanggal_mulai <> OLD.tanggal_mulai
           OR NEW.tanggal_selesai <> OLD.tanggal_selesai THEN
            RAISE EXCEPTION 'Identitas dan periode minggu logbook tidak dapat diubah.';
        END IF;
    END IF;

    RETURN NEW;
END;
$function$;