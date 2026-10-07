# Digital Signature

Signature dibuat melalui HTML Canvas dan dikirim sebagai PNG data URL melalui POST.

Kolom yang digunakan pada `logbook_mingguan`:

- `mahasiswa_ttd_data`, `mahasiswa_ttd_hash`, `mahasiswa_ttd_user_id`, `mahasiswa_ttd_pada`
- `mitra_ttd_data`, `mitra_ttd_hash`, `mitra_ttd_user_id`, `mitra_ttd_pada`
- `dosen_ttd_data`, `dosen_ttd_hash`, `dosen_ttd_user_id`, `dosen_ttd_pada`

Hash dibuat dari snapshot minggu + aktivitas harian + `versi_data`. Jika data berubah, signature lama dihapus dan harus dibuat ulang.
