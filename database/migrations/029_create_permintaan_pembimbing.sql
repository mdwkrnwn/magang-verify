-- Add the advisor request workflow described as planned (not yet present) in the ERD.
-- Non-destructive: creates a new table and index only; do not apply until the
-- target database's schema_migrations and deployed schema have been checked.

CREATE TABLE public.permintaan_pembimbing (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    penempatan_id BIGINT NOT NULL
        REFERENCES public.penempatan_magang(id) ON DELETE RESTRICT,
    dosen_id BIGINT NOT NULL
        REFERENCES public.profil_dosen(id) ON DELETE RESTRICT,
    alasan_pengajuan TEXT NOT NULL,
    status_permintaan VARCHAR(20) NOT NULL DEFAULT 'menunggu',
    catatan_dosen TEXT,
    tanggal_pengajuan TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    tanggal_respons TIMESTAMPTZ,
    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT permintaan_pembimbing_alasan_check
        CHECK (length(btrim(alasan_pengajuan)) > 0),
    CONSTRAINT permintaan_pembimbing_status_check
        CHECK (status_permintaan IN ('menunggu', 'disetujui', 'ditolak')),
    CONSTRAINT permintaan_pembimbing_respons_check
        CHECK (
            (status_permintaan = 'menunggu' AND tanggal_respons IS NULL)
            OR (status_permintaan IN ('disetujui', 'ditolak') AND tanggal_respons IS NOT NULL)
        )
);

-- Preserve rejected requests as history while preventing simultaneous requests
-- for one placement. Application logic also verifies the current placement state.
CREATE UNIQUE INDEX uq_permintaan_pembimbing_menunggu
    ON public.permintaan_pembimbing(penempatan_id)
    WHERE status_permintaan = 'menunggu';

CREATE INDEX idx_permintaan_pembimbing_dosen_status
    ON public.permintaan_pembimbing(dosen_id, status_permintaan, tanggal_pengajuan DESC);

INSERT INTO public.schema_migrations (migration)
VALUES ('029_create_permintaan_pembimbing.sql')
ON CONFLICT (migration) DO NOTHING;
