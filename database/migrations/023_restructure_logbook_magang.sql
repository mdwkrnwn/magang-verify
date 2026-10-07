BEGIN;

CREATE TABLE IF NOT EXISTS public.logbook_harian (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    logbook_id BIGINT NOT NULL REFERENCES public.logbook_mingguan(id) ON DELETE RESTRICT,
    tanggal DATE NOT NULL,
    jam_masuk TIME,
    jam_pulang TIME,
    kegiatan TEXT NOT NULL,
    bukti_path VARCHAR(500),
    bukti_nama_asli VARCHAR(255),
    perlu_validasi BOOLEAN NOT NULL DEFAULT FALSE,
    validasi_status VARCHAR(20) NOT NULL DEFAULT 'tidak_diperlukan',
    validasi_catatan TEXT,
    divalidasi_oleh BIGINT REFERENCES public.users(id) ON DELETE SET NULL,
    divalidasi_pada TIMESTAMPTZ,
    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT logbook_harian_tanggal_unik UNIQUE (logbook_id, tanggal),
    CONSTRAINT logbook_harian_jam_check CHECK (
        jam_masuk IS NULL OR jam_pulang IS NULL OR jam_pulang >= jam_masuk
    ),
    CONSTRAINT logbook_harian_validasi_check CHECK (
        validasi_status IN ('tidak_diperlukan','menunggu','disetujui','ditolak')
    ),
    CONSTRAINT logbook_harian_validasi_konsisten_check CHECK (
        (perlu_validasi = FALSE AND validasi_status = 'tidak_diperlukan')
        OR
        (perlu_validasi = TRUE AND validasi_status IN ('menunggu','disetujui','ditolak'))
    )
);

CREATE INDEX IF NOT EXISTS idx_logbook_harian_logbook_tanggal
    ON public.logbook_harian(logbook_id, tanggal);
CREATE INDEX IF NOT EXISTS idx_logbook_harian_validasi
    ON public.logbook_harian(validasi_status);
CREATE INDEX IF NOT EXISTS idx_logbook_harian_tanggal
    ON public.logbook_harian(tanggal);

ALTER TABLE public.logbook_tanda_tangan
    ADD COLUMN IF NOT EXISTS signature_path VARCHAR(500),
    ADD COLUMN IF NOT EXISTS signature_hash VARCHAR(64),
    ADD COLUMN IF NOT EXISTS data_hash VARCHAR(64),
    ADD COLUMN IF NOT EXISTS user_agent TEXT;

ALTER TABLE public.logbook_mingguan
    DROP CONSTRAINT IF EXISTS logbook_status_check;

ALTER TABLE public.logbook_mingguan
    ADD CONSTRAINT logbook_status_check CHECK (
        status IN (
            'draft',
            'diajukan',
            'menunggu_mitra',
            'menunggu_dosen',
            'menunggu_verifikasi',
            'perlu_revisi',
            'disetujui',
            'ditolak'
        )
    );

CREATE INDEX IF NOT EXISTS idx_logbook_ttd_tahap
    ON public.logbook_tanda_tangan(revisi_id, tahap);

INSERT INTO public.schema_migrations (migration)
VALUES ('020_restructure_logbook_magang.sql')
ON CONFLICT (migration) DO NOTHING;

COMMIT;
