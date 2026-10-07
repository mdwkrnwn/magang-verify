BEGIN;

-- ============================================================
-- LOGBOOK V2 - STRUCTURE HARDENING
-- Menjaga periode minggu dan histori versi agar tidak dapat
-- diubah lewat request/database yang melewati controller.
-- ============================================================

CREATE OR REPLACE FUNCTION public.enforce_logbook_week_schedule()
RETURNS TRIGGER
LANGUAGE plpgsql
AS $$
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
$$;

DROP TRIGGER IF EXISTS trg_logbook_week_schedule
    ON public.logbook_mingguan;

CREATE TRIGGER trg_logbook_week_schedule
BEFORE INSERT OR UPDATE ON public.logbook_mingguan
FOR EACH ROW
EXECUTE FUNCTION public.enforce_logbook_week_schedule();


-- Tidak ada fitur hapus harian pada workflow aplikasi. Karena itu,
-- hapus langsung ke database juga tidak boleh digunakan untuk
-- mengubah dokumen yang sudah disahkan mitra.
CREATE OR REPLACE FUNCTION public.prevent_logbook_harian_delete_after_partner_signature()
RETURNS TRIGGER
LANGUAGE plpgsql
AS $$
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
$$;

DROP TRIGGER IF EXISTS trg_logbook_harian_delete_lock
    ON public.logbook_harian;

CREATE TRIGGER trg_logbook_harian_delete_lock
BEFORE DELETE ON public.logbook_harian
FOR EACH ROW
EXECUTE FUNCTION public.prevent_logbook_harian_delete_after_partner_signature();


-- Snapshot yang sudah ditandatangani adalah bagian dari bukti audit.
-- Snapshot tidak boleh diubah/hapus setelah memiliki tanda tangan.
CREATE OR REPLACE FUNCTION public.prevent_signed_logbook_revision_mutation()
RETURNS TRIGGER
LANGUAGE plpgsql
AS $$
BEGIN
    IF EXISTS (
        SELECT 1
        FROM public.logbook_tanda_tangan
        WHERE revisi_id = OLD.id
    ) THEN
        RAISE EXCEPTION 'Versi logbook yang sudah ditandatangani tidak boleh diubah atau dihapus.';
    END IF;

    IF TG_OP = 'DELETE' THEN
        RETURN OLD;
    END IF;

    RETURN NEW;
END;
$$;

DROP TRIGGER IF EXISTS trg_signed_logbook_revision_mutation
    ON public.logbook_revisi;

CREATE TRIGGER trg_signed_logbook_revision_mutation
BEFORE UPDATE OR DELETE ON public.logbook_revisi
FOR EACH ROW
EXECUTE FUNCTION public.prevent_signed_logbook_revision_mutation();


INSERT INTO public.schema_migrations (migration)
VALUES ('022_harden_logbook_structure.sql')
ON CONFLICT (migration) DO NOTHING;

COMMIT;
