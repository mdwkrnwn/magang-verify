BEGIN;

-- Index komposit untuk query dashboard lintas role.
-- Tujuan utama: mengurangi biaya scan/join pada jalur akses yang paling
-- sering dipakai dashboard (pengajuan terbaru, penempatan, logbook, validasi).

-- Dashboard mahasiswa: pengajuan terbaru + progress.
CREATE INDEX IF NOT EXISTS idx_pendaftaran_mahasiswa_recent
    ON public.pendaftaran_magang(mahasiswa_id, tanggal_pengajuan DESC, id DESC);

-- Dashboard mitra: hitung pelamar per formasi berdasarkan status.
CREATE INDEX IF NOT EXISTS idx_pendaftaran_formasi_status
    ON public.pendaftaran_magang(formasi_id, status_pendaftaran);

-- Dashboard koordinator: 8 pengajuan terbaru dari view rekap.
CREATE INDEX IF NOT EXISTS idx_pendaftaran_recent
    ON public.pendaftaran_magang(tanggal_pengajuan DESC, id DESC);

-- Dashboard dosen: daftar bimbingan berdasarkan pembimbing dan status.
CREATE INDEX IF NOT EXISTS idx_penempatan_dosen_status
    ON public.penempatan_magang(dosen_pembimbing_id, status);

-- Dashboard/review logbook: filter minggu per penempatan dan status.
CREATE INDEX IF NOT EXISTS idx_logbook_penempatan_status
    ON public.logbook_mingguan(penempatan_id, status);

-- Antrean validasi Tendik/Koordinator: hitung hari menunggu per minggu.
CREATE INDEX IF NOT EXISTS idx_logbook_harian_logbook_validasi
    ON public.logbook_harian(logbook_id, status_validasi);

INSERT INTO public.schema_migrations (migration)
VALUES ('033_optimize_dashboard_queries.sql')
ON CONFLICT (migration) DO NOTHING;

COMMIT;
