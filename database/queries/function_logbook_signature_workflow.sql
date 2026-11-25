CREATE OR REPLACE FUNCTION public.enforce_logbook_signature_workflow()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
DECLARE
    v_status VARCHAR(30);
    v_current_version INTEGER;
    v_revision_version INTEGER;
    v_mahasiswa_user_id BIGINT;
    v_mitra_user_id BIGINT;
    v_dosen_user_id BIGINT;
BEGIN
    SELECT
        lm.status,
        lm.versi_terkini,
        lr.nomor_versi,
        prof.user_id,
        m.user_id,
        pd.user_id
      INTO
        v_status,
        v_current_version,
        v_revision_version,
        v_mahasiswa_user_id,
        v_mitra_user_id,
        v_dosen_user_id
      FROM public.logbook_revisi lr
      INNER JOIN public.logbook_mingguan lm
              ON lm.id = lr.logbook_id
      INNER JOIN public.penempatan_magang pm
              ON pm.id = lm.penempatan_id
      INNER JOIN public.pendaftaran_magang p
              ON p.id = pm.pendaftaran_id
      INNER JOIN public.profil_mahasiswa prof
              ON prof.id = p.mahasiswa_id
      INNER JOIN public.formasi_magang f
              ON f.id = p.formasi_id
      INNER JOIN public.mitra m
              ON m.id = f.mitra_id
      LEFT JOIN public.profil_dosen pd
             ON pd.id = pm.dosen_pembimbing_id
     WHERE lr.id = NEW.revisi_id;

    IF NOT FOUND THEN
        RAISE EXCEPTION 'Versi logbook untuk tanda tangan tidak ditemukan.';
    END IF;

    IF v_revision_version <> v_current_version THEN
        RAISE EXCEPTION 'Tanda tangan harus diberikan pada versi logbook terbaru.';
    END IF;

    IF NEW.tahap = 'mahasiswa' THEN
        IF NEW.penanda_tangan_id <> v_mahasiswa_user_id THEN
            RAISE EXCEPTION 'Penanda tangan mahasiswa tidak sesuai dengan pemilik logbook.';
        END IF;
        IF v_status NOT IN ('draft', 'perlu_revisi') THEN
            RAISE EXCEPTION 'Logbook belum berada pada tahap tanda tangan mahasiswa.';
        END IF;

    ELSIF NEW.tahap = 'mitra' THEN
        IF NEW.penanda_tangan_id <> v_mitra_user_id THEN
            RAISE EXCEPTION 'Penanda tangan mitra tidak sesuai dengan mitra penempatan.';
        END IF;
        IF v_status <> 'menunggu_mitra' THEN
            RAISE EXCEPTION 'Logbook belum berada pada tahap tanda tangan mitra.';
        END IF;

    ELSIF NEW.tahap = 'dosen' THEN
        IF v_dosen_user_id IS NULL OR NEW.penanda_tangan_id <> v_dosen_user_id THEN
            RAISE EXCEPTION 'Penanda tangan dosen tidak sesuai dengan dosen pembimbing penempatan.';
        END IF;
        IF v_status <> 'menunggu_dosen' THEN
            RAISE EXCEPTION 'Logbook belum berada pada tahap tanda tangan dosen.';
        END IF;
    END IF;

    RETURN NEW;
END;
$function$;