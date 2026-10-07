# Logbook Magang

## Tujuan

Logbook dibangun berdasarkan **penempatan magang**, bukan berdasarkan akun mahasiswa secara global. Setiap penempatan mempunyai rangkaian minggu dan aktivitas harian sendiri sehingga histori Magang #1 tetap tersimpan ketika mahasiswa menjalani Magang #2.

## Struktur

```text
Penempatan Magang
└── Logbook Mingguan
    └── Logbook Harian
```

- Minggu pertama dimulai dari `penempatan_magang.tanggal_mulai`.
- Minggu pertama berakhir pada hari Minggu.
- Minggu berikutnya Senin–Minggu.
- Minggu terakhir boleh berakhir tepat pada tanggal selesai magang jika periode berakhir sebelum Minggu.
- Minggu masa depan tidak boleh dibuat.
- Minggu yang terlewat boleh dibuat sebagai pengisian terlambat.
- Setiap tanggal hanya mempunyai satu logbook harian.
- Sabtu dan Minggu tetap tersedia.

## Workflow tanda tangan

```text
Mahasiswa isi semua hari
        ↓
Mahasiswa tanda tangan
        ↓
Mitra tanda tangan
        ↓
Dosen pembimbing tanda tangan
        ↓
Disetujui
```

Tanda tangan dilakukan langsung di platform menggunakan canvas.

### Edit setelah tanda tangan mahasiswa

Mahasiswa masih boleh mengedit selama mitra belum menandatangani. Setiap perubahan:

1. menaikkan `versi_data`;
2. membatalkan tanda tangan mahasiswa;
3. membatalkan tanda tangan mitra/dosen;
4. mengembalikan status minggu menjadi `draft`;
5. mahasiswa harus tanda tangan ulang.

Setelah mitra menandatangani, data minggu terkunci.

## Validasi ketidakhadiran

Mahasiswa menulis kondisi hari tersebut pada field aktivitas. Jika membutuhkan bukti, mahasiswa mengunggah PDF/JPG/PNG maksimal 5 MB. Record tersebut menjadi `menunggu` validasi sehingga dapat ditangani oleh Tendik/Koordinator pada workflow validasi berikutnya.

## Keamanan

- CSRF pada seluruh aksi POST.
- Authorization berdasarkan role dan kepemilikan relasi.
- Validasi tanggal di controller dan database trigger.
- Tanggal masa depan ditolak.
- Unique `(logbook_id, tanggal)` mencegah duplikasi satu hari.
- Signature menyimpan hash snapshot data logbook.
- Signature downstream hanya dapat dibuat jika signature sebelumnya valid.
- Edit setelah mahasiswa sign membatalkan signature lama.
- Mitra sign mengunci data minggu dari perubahan mahasiswa.

## PDF

`LogbookPdfService` menggabungkan seluruh minggu dari satu penempatan secara berurutan. Setiap minggu dimulai pada halaman baru sehingga Minggu 1, Minggu 2, dan seterusnya tetap terurut.
