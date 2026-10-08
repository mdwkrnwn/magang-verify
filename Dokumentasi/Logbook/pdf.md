# PDF Logbook

PDF dibuat oleh `services/LogbookPdfService.php` menggunakan Dompdf yang sudah tersedia di project.

Satu penempatan menghasilkan satu dokumen dengan urutan:

```text
Minggu 1
Minggu 2
Minggu 3
...
```

Setiap minggu dimulai pada halaman baru. Isi utama mengikuti contoh berkas yang diberikan: identitas mahasiswa/mitra, tabel Hari/Tanggal, Jam Masuk, Jam Pulang, Kegiatan, serta area tanda tangan mahasiswa, mitra, dan dosen.
