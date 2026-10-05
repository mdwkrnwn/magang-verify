
BEGIN;

-- Tambahkan kolom yang dibutuhkan form portofolio.
ALTER TABLE public.portofolios
    ADD COLUMN peran VARCHAR(100),
    ADD COLUMN tautan_github VARCHAR(500),
    ADD COLUMN tautan_demo VARCHAR(500);

-- Menyimpan teknologi yang digunakan pada setiap proyek.
CREATE TABLE public.portofolio_teknologi (
    portofolio_id BIGINT NOT NULL
        REFERENCES public.portofolios(id) ON DELETE CASCADE,

    nama_teknologi VARCHAR(100) NOT NULL,

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (portofolio_id, nama_teknologi)
);

INSERT INTO schema_migrations (migration)
VALUES ('010_adjust_portofolio_for_form.sql');

COMMIT;