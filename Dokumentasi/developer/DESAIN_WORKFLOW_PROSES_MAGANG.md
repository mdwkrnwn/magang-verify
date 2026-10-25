# Desain Workflow Proses Magang dan Penugasan Pembimbing

Status: rancangan implementasi, migration belum dijalankan.

## Keputusan bisnis

- Satu `proses_magang` aktif maksimal untuk satu `profil_mahasiswa`.
- Proses magang dibuat sebelum pengajuan ke mitra.
- Penugasan pembimbing berlaku pada proses, bukan pada `profil_mahasiswa` dan bukan pertama kali pada `penempatan_magang`.
- Tendik dan Koordinator Magang dapat menetapkan serta mengganti pembimbing.
- Pergantian harus memiliki alasan dan menghasilkan riwayat.
- Pengajuan menyimpan snapshot pembimbing yang berlaku ketika pengajuan dibuat.

## Rancangan schema

### `proses_magang`

- `id` identity primary key.
- `mahasiswa_id` references `profil_mahasiswa(id)`.
- `status` (`aktif`, `selesai`, `dibatalkan`).
- `dibuat_oleh`, `created_at`, `updated_at`.
- Partial unique index: satu baris berstatus `aktif` per mahasiswa.

### `penugasan_pembimbing`

- `id` identity primary key.
- `proses_magang_id` references `proses_magang(id)`.
- `dosen_pembimbing_id` references `profil_dosen(id)`.
- `ditetapkan_oleh` references `users(id)`.
- `alasan` wajib untuk assignment pertama maupun penggantian; panjang maksimal 5.000 karakter.
- `status` (`aktif`, `diganti`, `dibatalkan`).
- `ditetapkan_pada`, `berakhir_pada`, `created_at`.
- Partial unique index: satu penugasan `aktif` per proses.
- Riwayat dipertahankan sebagai baris, bukan update/destructive delete.

### Snapshot pada `pendaftaran_magang`

Migration menambahkan:

- `proses_magang_id` sebagai referensi proses.
- `penugasan_pembimbing_id` sebagai referensi penugasan yang dipakai.
- `dosen_pembimbing_snapshot_id` sebagai referensi profil dosen.
- `dosen_pembimbing_snapshot_nama` dan `dosen_pembimbing_snapshot_nidn` sebagai snapshot tampilan.

Snapshot diisi dalam transaksi saat pengajuan dibuat. Keputusan Dosen memakai `penugasan_pembimbing_id`/snapshot, bukan `penempatan_magang`.

`penempatan_magang.dosen_pembimbing_id` dipertahankan untuk kompatibilitas logbook/laporan dan diisi dari snapshot ketika penempatan dibuat. Penempatan tidak lagi menjadi sumber assignment administratif.

## Rancangan backfill konservatif

Migration tidak boleh memilih pembimbing secara otomatis jika sumber data ambigu.

1. Buat `proses_magang` untuk mahasiswa yang memiliki pengajuan aktif atau penempatan legacy.
2. Untuk mahasiswa dengan tepat satu pembimbing legacy yang konsisten pada penempatan terkait, buat satu riwayat penugasan aktif dengan alasan `Backfill dari penempatan legacy; perlu verifikasi pengelola.`
3. Untuk konflik pembimbing, proses dibuat tetapi tidak diberi penugasan; konflik masuk laporan audit melalui query read-only.
4. Pengajuan legacy diisi `proses_magang_id` jika pemetaan tunggal jelas.
5. Snapshot pengajuan legacy diisi dari `penugasan_pembimbing` hanya jika relasi tunggal jelas; jika tidak, dibiarkan NULL dan harus ditangani manual.
6. Tidak ada penghapusan data, perubahan status pengajuan, atau pemilihan dosen berdasarkan urutan ID.

Migration dibuat sebagai berkas saja dan tidak dijalankan.

## Status terpisah

- Proses: `aktif`, `selesai`, `dibatalkan`.
- Penugasan: `aktif`, `diganti`, `dibatalkan`.
- Review Dosen: `menunggu`, `disetujui`, `ditolak`, `perlu_revisi`.
- Respons Mitra: `menunggu`, `diterima`, `ditolak`, `seleksi`.
- Pengajuan: status yang sudah ada, termasuk `menunggu_persetujuan_dosen`.
- Penempatan: status yang sudah ada, termasuk `persiapan` dan `berlangsung`.

## Otorisasi

- Controller assignment memakai allowlist eksplisit `tendik` dan `koordinator_magang`.
- Mutation model memvalidasi actor melalui role aktif dari session/middleware dan mencatat actor.
- Mahasiswa tidak mengirim atau memilih ID dosen.
- Dosen hanya dapat memutuskan pengajuan yang snapshot/penugasannya menunjuk profil Dosen tersebut.
- Penggantian setelah pengajuan dibuat tidak mengubah snapshot pengajuan lama.

## Batas implementasi batch

Migration SQL, source model/controller/view, dan static/isolated tests boleh dibuat. Migration, seeder, server, koneksi database, dan operasi tulis tidak dijalankan.
