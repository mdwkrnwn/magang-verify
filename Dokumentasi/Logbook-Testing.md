# Panduan Testing Logbook

Dokumen ini digunakan untuk menguji workflow Logbook V2 secara end-to-end.

## 1. Skenario utama Dimas

Gunakan tiga sesi/browser terpisah agar perpindahan role mudah diuji:

| Role | Login ID | Password | Fungsi |
|---|---|---|---|
| Mahasiswa | `mhs_dimas` | `Password123!` | Membuat minggu, mengisi harian, tanda tangan |
| Mitra | `mitra_seed` | `Password123!` | Menandatangani setelah mahasiswa |
| Dosen | `dosen_seed` | `Password123!` | Menandatangani setelah mitra |

Hubungan Dimas:

```text
mhs_dimas
   ↓
Penempatan Magang Dimas
   ↓
PT Teknologi Nusantara
   ↓
Dosen Pembimbing: dosen_seed
```

`dosen_seed` harus memiliki `profil_dosen` karena `penempatan_magang.dosen_pembimbing_id` mengarah ke `profil_dosen.id`, sedangkan `dosen_penyetuju_id` dan `ditetapkan_oleh` mengarah ke `users.id`.

## 2. Akun pengujian role lain

Seeder `database/seeders/SeedTestUsers.php` menyediakan:

| Role | Login ID | Password |
|---|---|---|
| Mahasiswa | `mhs_test` | `MhsTest123!` |
| Dosen | `dosen_test` | `DosenTest123!` |
| Koordinator Magang | `koord_test` | `KoordTest123!` |
| Tendik | `tendik_test` | `TendikTest123!` |
| Mitra | `mitra_test` | tidak memiliki password pada seeder ini |

Akun `mhs_test`, `dosen_test`, `koord_test`, `tendik_test`, dan `mitra_test` dibuat oleh seeder role testing. Untuk pengujian alur Dimas, gunakan akun seed khusus di bagian sebelumnya.

## 3. Urutan pengujian

### A. Mahasiswa

1. Login sebagai `mhs_dimas`.
2. Buka **Dashboard → Logbook**.
3. Pastikan penempatan PT Teknologi Nusantara tampil.
4. Buat Minggu 1.
5. Pastikan periode minggu mengikuti tanggal mulai penempatan dan berakhir pada Minggu.
6. Isi aktivitas satu tanggal per record.
7. Coba mengisi tanggal besok → harus ditolak.
8. Coba membuat tanggal yang sama dua kali → harus ditolak.
9. Coba mengisi Sabtu/Minggu → harus dapat dilakukan.
10. Untuk tidak hadir, pilih **Tidak hadir**, isi alasan, dan unggah bukti bila diperlukan.
11. Pastikan validasi ketidakhadiran membuat tanda tangan mahasiswa tertahan sampai validasi selesai.
12. Setelah seluruh tanggal lengkap dan minggu selesai, tanda tangani minggu.

### B. Uji edit setelah tanda tangan mahasiswa

1. Pastikan status minggu menjadi **Menunggu tanda tangan mitra**.
2. Kembali sebagai mahasiswa.
3. Edit salah satu aktivitas harian.
4. Pastikan perubahan berhasil.
5. Pastikan versi logbook bertambah.
6. Pastikan tanda tangan mahasiswa versi lama tidak ditampilkan sebagai tanda tangan versi terbaru.
7. Pastikan status kembali menjadi **Belum ditandatangani mahasiswa**.
8. Pastikan muncul keterangan bahwa mahasiswa harus tanda tangan ulang.
9. Tanda tangani ulang.
10. Pastikan baru setelah itu mitra dapat menandatangani.

### C. Mitra

1. Login sebagai `mitra_seed`.
2. Buka **Logbook Mahasiswa**.
3. Pastikan Minggu yang sudah ditandatangani mahasiswa muncul.
4. Pastikan status yang ditampilkan hanya terkait tanda tangan mitra.
5. Tanda tangani menggunakan area tanda tangan digital.
6. Setelah berhasil, minggu tidak lagi muncul sebagai antrean tanda tangan.

### D. Uji lock setelah mitra

Kembali sebagai mahasiswa.

1. Buka minggu yang sudah ditandatangani mitra.
2. Pastikan tombol edit tidak tersedia.
3. Coba akses URL edit secara langsung.
4. Request harus ditolak oleh backend.
5. Coba ubah database langsung sebagai simulasi bypass UI.
6. Trigger database harus menolak perubahan/hapus aktivitas pada minggu yang sudah dikunci.

### E. Dosen

1. Login sebagai `dosen_seed`.
2. Buka **Logbook Mahasiswa Bimbingan**.
3. Pastikan minggu baru muncul setelah mitra tanda tangan.
4. Buka detail.
5. Pastikan aktivitas mahasiswa dapat dibaca.
6. Tanda tangani menggunakan signature pad.
7. Pastikan status minggu menjadi selesai/disetujui.

### F. PDF

Uji:

```text
Download Minggu 1
Download semua minggu
```

Untuk download semua, hasil harus tetap berurutan:

```text
Page 1 → Minggu 1
Page 2 → Minggu 2
Page 3 → Minggu 3
...
```

Signature yang tampil harus berasal dari signature versi yang sedang direkap.

## 4. Skenario keterlambatan

Untuk menguji keterlambatan:

- gunakan minggu yang periodenya sudah lewat tetapi belum dibuat;
- sistem harus tetap mengizinkan pembuatan minggu tersebut;
- sistem tidak boleh membuat minggu berikutnya sebelum periode minggu sebelumnya selesai.

## 5. Skenario multi-penempatan

Jika mahasiswa mempunyai Penempatan #1 dan Penempatan #2:

```text
Penempatan #1
├── Minggu 1
├── Minggu 2
└── ...

Penempatan #2
├── Minggu 1
├── Minggu 2
└── ...
```

Nomor minggu kembali ke 1 untuk penempatan baru, dan logbook penempatan lama tetap tersedia.

## 6. Migration

Jalankan secara berurutan hanya jika belum tercatat:

```text
020_rebuild_logbook_workflow.sql
021_harden_logbook_workflow.sql
022_harden_logbook_structure.sql
```

Verifikasi:

```sql
SELECT migration
FROM schema_migrations
WHERE migration LIKE '02%logbook%'
ORDER BY migration;
```
