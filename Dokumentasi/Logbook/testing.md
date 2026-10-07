# Testing End-to-End Logbook

## Akun utama

| Role | Login ID | Password | Catatan |
|---|---|---|---|
| Mahasiswa aktif | `mhs_dimas` | `Password123!` | Penempatan aktif 1 Sep–30 Nov 2026 |
| Dosen pembimbing | `dosen_seed` | `Password123!` | Profil dosen dipakai sebagai `dosen_pembimbing_id` |
| Mitra | `mitra_seed` | tidak digunakan | Login mitra memang tidak memerlukan password pada `LoginController` |
| Koordinator | `koord_test` | `KoordTest123!` | Dibuat oleh `database/seeders/SeedTestUsers.php` |
| Tendik | `tendik_test` | `TendikTest123!` | Dibuat oleh `database/seeders/SeedTestUsers.php` |

## Skenario utama

1. Login `mhs_dimas`.
2. Buka Logbook.
3. Buat Minggu 1.
4. Isi satu record untuk setiap tanggal periode minggu.
5. Coba membuat tanggal masa depan: harus ditolak.
6. Coba membuat dua record pada tanggal yang sama: harus ditolak.
7. Setelah semua hari lengkap dan periode selesai, tanda tangani minggu.
8. Login sebagai `mitra_seed`.
9. Review Minggu 1 dan tanda tangani.
10. Kembali sebagai mahasiswa dan coba edit: harus ditolak.
11. Sebelum mitra sign pada minggu berikutnya, edit aktivitas setelah mahasiswa sign: signature mahasiswa harus dibatalkan dan harus sign ulang.
12. Login `dosen_seed` dan pastikan minggu baru muncul hanya setelah mitra sign.
13. Tanda tangani sebagai dosen.
14. Login `tendik_test` atau `koord_test` untuk memvalidasi bukti ketidakhadiran yang berstatus menunggu.
15. Download seluruh logbook dan pastikan urutan Minggu 1, Minggu 2, dan seterusnya.
