BEGIN;

-- ============================================================
-- LOGBOOK V2
-- Struktur baru: 1 penempatan -> banyak minggu -> banyak hari.
-- Tanda tangan tetap terikat pada versi/revisi tertentu.
-- ============================================================

ALTER TABLE public.logbook_revisi
    ADD COLUMN IF NOT EXISTS snapshot_data JSONB,
    ADD COLUMN IF NOT EXISTS snapshot_hash CHAR(64);

CREATE TABLE IF NOT EXISTS public.logbook_harian (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    logbook_id BIGINT NOT NULL
        REFERENCES public.logbook_mingguan(id) ON DELETE RESTRICT,

    tanggal DATE NOT NULL,

    jam_masuk TIME,
    jam_pulang TIME,

    kegiatan TEXT NOT NULL,

    status_kehadiran VARCHAR(20) NOT NULL DEFAULT 'hadir',
    alasan_ketidakhadiran TEXT,
    bukti_path VARCHAR(500),

    status_validasi VARCHAR(20) NOT NULL DEFAULT 'tidak_perlu',
    catatan_validasi TEXT,
    divalidasi_oleh BIGINT
        REFERENCES public.users(id) ON DELETE SET NULL,
    divalidasi_pada TIMESTAMPTZ,

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT logbook_harian_unik
        UNIQUE (logbook_id, tanggal),

    CONSTRAINT logbook_harian_kehadiran_check
        CHECK (status_kehadiran IN ('hadir', 'tidak_hadir')),

    CONSTRAINT logbook_harian_validasi_check
        CHECK (status_validasi IN ('tidak_perlu', 'menunggu', 'disetujui', 'ditolak')),

    CONSTRAINT logbook_harian_alasan_check
        CHECK (
            status_kehadiran = 'hadir'
            OR NULLIF(BTRIM(alasan_ketidakhadiran), '') IS NOT NULL
        ),

    CONSTRAINT logbook_harian_validasi_consistency_check
        CHECK (
            (status_kehadiran = 'hadir' AND status_validasi = 'tidak_perlu')
            OR status_kehadiran = 'tidak_hadir'
        ),

    CONSTRAINT logbook_harian_jam_check
        CHECK (
            jam_pulang IS NULL
            OR jam_masuk IS NULL
            OR jam_pulang >= jam_masuk
        )
);

CREATE INDEX IF NOT EXISTS idx_logbook_harian_logbook_tanggal
    ON public.logbook_harian(logbook_id, tanggal);

CREATE INDEX IF NOT EXISTS idx_logbook_harian_validasi
    ON public.logbook_harian(status_validasi);

CREATE INDEX IF NOT EXISTS idx_logbook_harian_validasi_oleh
    ON public.logbook_harian(divalidasi_oleh);

ALTER TABLE public.logbook_tanda_tangan
    ADD COLUMN IF NOT EXISTS signature_path VARCHAR(500),
    ADD COLUMN IF NOT EXISTS signature_hash CHAR(64),
    ADD COLUMN IF NOT EXISTS snapshot_hash CHAR(64),
    ADD COLUMN IF NOT EXISTS metadata JSONB;

CREATE INDEX IF NOT EXISTS idx_logbook_ttd_tahap_snapshot
    ON public.logbook_tanda_tangan(revisi_id, tahap, snapshot_hash);

INSERT INTO public.schema_migrations (migration)
VALUES ('020_rebuild_logbook_workflow.sql')
ON CONFLICT (migration) DO NOTHING;

COMMIT;
