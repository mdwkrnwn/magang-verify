BEGIN;

-- ============================================
-- VERIFYMAGANG — LAPORAN WORKFLOW V2
-- ============================================
-- Migration ini memperluas struktur laporan existing tanpa
-- menghapus data laporan_magang / laporan_revisi yang sudah ada.
--
-- Perubahan utama:
-- 1. Mendukung laporan mingguan dan laporan akhir.
-- 2. Menambahkan template laporan.
-- 3. Menambahkan pemeriksaan laporan bertahap.
-- 4. Menyimpan minggu_ke dan ketepatan waktu laporan mingguan.
-- 5. Mempertahankan laporan_revisi sebagai histori versi file.
-- ============================================

-- ============================================
-- TEMPLATE LAPORAN
-- ============================================

CREATE TABLE public.template_laporan (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    jenis_laporan VARCHAR(30) NOT NULL,
    nama_template VARCHAR(200) NOT NULL,
    file_template VARCHAR(500) NOT NULL,
    versi INTEGER NOT NULL DEFAULT 1,
    status_aktif BOOLEAN NOT NULL DEFAULT TRUE,

    created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT template_laporan_jenis_check
        CHECK (
            jenis_laporan IN (
                'laporan_mingguan',
                'laporan_akhir'
            )
        ),

    CONSTRAINT template_laporan_versi_check
        CHECK (versi > 0)
);

CREATE INDEX idx_template_laporan_jenis
    ON public.template_laporan(jenis_laporan);

CREATE UNIQUE INDEX uq_template_laporan_aktif
    ON public.template_laporan(jenis_laporan)
    WHERE status_aktif = TRUE;


-- ============================================
-- PERLUAS LAPORAN MAGANG EXISTING
-- ============================================

-- Constraint lama hanya mengizinkan laporan_akhir dan
-- UNIQUE (penempatan_id, jenis_laporan) tidak memungkinkan
-- banyak laporan mingguan pada satu penempatan.
ALTER TABLE public.laporan_magang
    DROP CONSTRAINT IF EXISTS laporan_jenis_check;

ALTER TABLE public.laporan_magang
    DROP CONSTRAINT IF EXISTS laporan_penempatan_jenis_unik;

ALTER TABLE public.laporan_magang
    ADD COLUMN IF NOT EXISTS template_id BIGINT,
    ADD COLUMN IF NOT EXISTS minggu_ke INTEGER,
    ADD COLUMN IF NOT EXISTS file_path VARCHAR(500),
    ADD COLUMN IF NOT EXISTS tanggal_unggah TIMESTAMPTZ,
    ADD COLUMN IF NOT EXISTS status_ketepatan_waktu VARCHAR(20);

ALTER TABLE public.laporan_magang
    ADD CONSTRAINT laporan_jenis_check
        CHECK (
            jenis_laporan IN (
                'laporan_mingguan',
                'laporan_akhir'
            )
        );

ALTER TABLE public.laporan_magang
    ADD CONSTRAINT laporan_minggu_check
        CHECK (
            (jenis_laporan = 'laporan_mingguan'
                AND minggu_ke IS NOT NULL
                AND minggu_ke > 0)
            OR
            (jenis_laporan = 'laporan_akhir'
                AND minggu_ke IS NULL)
        );

ALTER TABLE public.laporan_magang
    ADD CONSTRAINT laporan_ketepatan_waktu_check
        CHECK (
            status_ketepatan_waktu IS NULL
            OR status_ketepatan_waktu IN (
                'tepat_waktu',
                'terlambat'
            )
        );

ALTER TABLE public.laporan_magang
    ADD CONSTRAINT laporan_template_fk
        FOREIGN KEY (template_id)
        REFERENCES public.template_laporan(id)
        ON DELETE RESTRICT;

CREATE INDEX idx_laporan_template
    ON public.laporan_magang(template_id);

CREATE INDEX idx_laporan_jenis
    ON public.laporan_magang(jenis_laporan);

CREATE INDEX idx_laporan_penempatan_jenis
    ON public.laporan_magang(penempatan_id, jenis_laporan);

CREATE UNIQUE INDEX uq_laporan_mingguan
    ON public.laporan_magang(penempatan_id, minggu_ke)
    WHERE jenis_laporan = 'laporan_mingguan';

CREATE UNIQUE INDEX uq_laporan_akhir
    ON public.laporan_magang(penempatan_id)
    WHERE jenis_laporan = 'laporan_akhir';


-- ============================================
-- PEMERIKSAAN LAPORAN
-- ============================================

CREATE TABLE public.pemeriksaan_laporan (
    id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,

    laporan_id BIGINT NOT NULL
        REFERENCES public.laporan_magang(id) ON DELETE RESTRICT,

    pemeriksa_id BIGINT NOT NULL
        REFERENCES public.users(id) ON DELETE RESTRICT,

    tahap_pemeriksaan VARCHAR(20) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'menunggu',
    catatan TEXT,
    tanggal_pemeriksaan TIMESTAMPTZ,

    CONSTRAINT pemeriksaan_laporan_tahap_check
        CHECK (
            tahap_pemeriksaan IN (
                'dosen',
                'koordinator',
                'tendik'
            )
        ),

    CONSTRAINT pemeriksaan_laporan_status_check
        CHECK (
            status IN (
                'menunggu',
                'disetujui',
                'perlu_revisi',
                'ditolak'
            )
        ),

    CONSTRAINT pemeriksaan_laporan_tanggal_check
        CHECK (
            status = 'menunggu'
            OR tanggal_pemeriksaan IS NOT NULL
        )
);

CREATE INDEX idx_pemeriksaan_laporan_laporan
    ON public.pemeriksaan_laporan(laporan_id);

CREATE INDEX idx_pemeriksaan_laporan_pemeriksa
    ON public.pemeriksaan_laporan(pemeriksa_id);

CREATE INDEX idx_pemeriksaan_laporan_tahap_status
    ON public.pemeriksaan_laporan(tahap_pemeriksaan, status);


-- ============================================
-- RIWAYAT REVISI EXISTING
-- ============================================
-- laporan_revisi tetap digunakan sebagai histori versi file.
-- Tidak membuat tabel riwayat_revisi_laporan baru karena
-- fungsi tersebut sudah dipenuhi oleh laporan_revisi existing.

CREATE INDEX IF NOT EXISTS idx_laporan_revisi_laporan_versi
    ON public.laporan_revisi(laporan_id, nomor_versi DESC);


-- ============================================
-- MIGRATION HISTORY
-- ============================================

INSERT INTO public.schema_migrations (migration)
VALUES ('024_create_laporan_workflow.sql')
ON CONFLICT (migration) DO NOTHING;

COMMIT;
