# Permintaan Dosen Pembimbing

## Status

Fitur ini menambahkan alur yang ditandai sebagai rancangan pada ERD. Implementasi membutuhkan migration `029_create_permintaan_pembimbing.sql`; route dan halaman belum dapat digunakan pada database sebelum migration itu diterapkan secara terkontrol.

## Aturan alur

1. Mahasiswa hanya dapat mengajukan permintaan untuk penempatannya sendiri.
2. Pengajuan pendaftaran harus berstatus `diterima`, penempatan harus berstatus `persiapan` atau `berlangsung`, dan penempatan belum memiliki dosen pembimbing.
3. Hanya satu permintaan berstatus `menunggu` yang boleh ada untuk satu penempatan. Permintaan yang ditolak tetap tersimpan; mahasiswa dapat memilih dosen lain.
4. Hanya dosen yang menjadi target permintaan dapat memprosesnya.
5. Keputusan hanya `disetujui` atau `ditolak`, satu kali. Catatan bersifat opsional.
6. Persetujuan memperbarui `penempatan_magang.dosen_pembimbing_id` ke `profil_dosen.id` pada transaksi yang sama dengan perubahan status permintaan.
7. Jika penempatan sudah ditetapkan kepada dosen lain atau request sudah diproses, backend menolak perubahan.

## Implementasi

- Model: `models/PermintaanPembimbing.php`
- Mahasiswa: `controllers/dashboard/mahasiswa/PermintaanPembimbingController.php`, route `/dashboard/mahasiswa/pembimbing`
- Dosen: `controllers/dashboard/dosen/PermintaanPembimbingController.php`, route `/dashboard/dosen/permintaan-pembimbing`
- Fixture: `database/seeders/SeedPermintaanPembimbing.php` menyiapkan skenario `mhs_test` → `dosen_test` dengan pengajuan diterima, penempatan persiapan, dan request menunggu.
- UI mengikuti header, sidebar, kartu, warna, dan komponen dashboard yang telah digunakan project.
- POST memerlukan token `_csrf_token`; akses dan kepemilikan diverifikasi backend.
- Audit dicatat melalui `aktivitas_pengguna` setelah transaksi berhasil.

## Database

Migration membuat tabel baru dengan FK ke `penempatan_magang` dan `profil_dosen`, check status/alasan/respons, partial unique index untuk request pending, dan index antrean dosen. Tidak mengubah atau menghapus tabel/data existing. Migration belum diterapkan ke database mana pun. Cek `schema_migrations`, foreign key, dan definisi aktual pada target sebelum penerapan. Tidak tersedia rollback otomatis karena menghapus histori permintaan akan merusak jejak keputusan.

## Pengujian pada database testing setelah migration

- Coba request tanpa login, sebagai dosen, dengan ID penempatan mahasiswa lain, atau tanpa CSRF: harus ditolak.
- Mahasiswa dengan pengajuan belum diterima, penempatan selesai, atau penempatan sudah punya dosen: tidak dapat membuat request.
- Request pertama ke dosen aktif disimpan dengan status menunggu.
- Request kedua ketika masih pending ditolak.
- Dosen lain tidak melihat atau memproses request tersebut.
- Dosen target dapat menolak dengan catatan; record tetap ada dan mahasiswa dapat meminta dosen lain.
- Dosen target dapat menyetujui; status request dan foreign key pembimbing penempatan berubah atomik.
- Request yang sudah diproses tidak dapat diproses ulang.
- Dua persetujuan bersamaan tidak dapat menugaskan dua dosen pada satu penempatan.

Urutan fixture (hanya pada database lokal/testing yang sudah diverifikasi): jalankan `SeedTestUsers.php`, `SeedFormasiMagang.php`, lalu `SeedPermintaanPembimbing.php`. Seeder fixture menolak berjalan jika migration 029 belum tercatat, tidak mengubah pengajuan/penempatan existing yang tidak cocok, dan transaksi dibatalkan jika kuota tidak tersedia. Jangan jalankan pada Supabase/produksi.

Pengujian database/browser belum dijalankan karena target database belum dipastikan sebagai testing.
