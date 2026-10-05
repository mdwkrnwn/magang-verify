# Dokumentasi Logbook Magang

Dokumentasi ini menjelaskan workflow Logbook Magang pada MagangVerify setelah implementasi Logbook V2.

## 1. Prinsip utama

Struktur logbook mengikuti satu penempatan magang:

```text
Mahasiswa
└── Penempatan Magang #1
    ├── Minggu 1
    │   ├── Hari 1
    │   ├── Hari 2
    │   └── ...
    ├── Minggu 2
    └── ...

└── Penempatan Magang #2
    ├── Minggu 1
    └── ...
```

Riwayat penempatan tidak saling menimpa. Logbook selalu terhubung ke `penempatan_magang`.

## 2. Pembentukan minggu

Minggu pertama mengikuti tanggal mulai `penempatan_magang`.

Contoh:

```text
Mulai: Rabu, 7 Oktober 2026
Minggu 1: 7 Oktober – 11 Oktober 2026
Minggu 2: 12 Oktober – 18 Oktober 2026
Minggu 3: 19 Oktober – 25 Oktober 2026
```

Minggu pertama berakhir pada hari Minggu. Minggu berikutnya berjalan Senin–Minggu.

Aturan backend:

- minggu masa depan tidak dapat dibuat;
- minggu yang sudah lewat tetap dapat dibuat sebagai keterlambatan;
- minggu berikutnya baru dapat dibuat setelah periode minggu sebelumnya selesai;
- nomor minggu tidak dikirim sebagai sumber kebenaran dari browser, tetapi dihitung oleh model berdasarkan penempatan dan minggu terakhir.

## 3. Logbook harian

Setiap tanggal dalam satu minggu mempunyai maksimal satu record:

```text
UNIQUE (logbook_id, tanggal)
```

Mahasiswa tidak dapat membuat logbook untuk tanggal masa depan.

Kolom utama:

- tanggal
- jam masuk
- jam pulang
- kegiatan
- status kehadiran
- alasan ketidakhadiran
- bukti/surat jika ada
- status validasi

Sabtu dan Minggu tetap dapat diisi karena jadwal kerja setiap mitra dapat berbeda.

## 4. Ketidakhadiran

Mahasiswa mengisi sendiri status kehadiran dan aktivitas menggunakan teks bebas.

Jika memilih tidak hadir, alasan wajib diisi. Bukti dapat diunggah dalam format PDF/JPG/PNG dengan ukuran maksimum 5 MB.

Validasi dilakukan oleh:

- Tendik; atau
- Koordinator Magang.

Status validasi:

```text
menunggu
   ↓
disetujui / ditolak
```

Minggu tidak dapat ditandatangani mahasiswa selama masih ada ketidakhadiran yang menunggu validasi atau ditolak.

## 5. Tanda tangan berjenjang

Urutan tanda tangan:

```text
Mahasiswa
    ↓
Mitra / Pembimbing Lapangan
    ↓
Dosen Pembimbing
```

Mahasiswa baru dapat menandatangani setelah periode minggu selesai, semua tanggal sudah mempunyai record, dan validasi ketidakhadiran selesai.

Setelah mahasiswa menandatangani:

```text
status = menunggu_mitra
```

Mitra baru dapat menandatangani pada status tersebut.

Setelah mitra menandatangani:

```text
status = menunggu_dosen
```

Dosen pembimbing yang benar-benar terhubung ke `penempatan_magang.dosen_pembimbing_id` baru dapat menandatangani.

Setelah dosen menandatangani:

```text
status = disetujui
```

### Tampilan status di mitra

Mitra hanya melihat status tanda tangan miliknya:

- Belum ditandatangani
- Sudah ditandatangani

Status tanda tangan dosen tidak ditampilkan sebagai status bisnis di dashboard mitra.

## 6. Edit dan penguncian

Mahasiswa boleh mengedit logbook selama mitra belum menandatangani.

Kondisi yang diperbolehkan:

```text
draft                 → boleh edit
menunggu_mitra        → boleh edit
```

Kondisi terkunci:

```text
menunggu_dosen        → tidak boleh edit
disetujui             → tidak boleh edit
```

Jika mahasiswa sudah menandatangani tetapi kemudian mengubah data sebelum mitra menandatangani:

1. dibuat versi/revisi baru;
2. versi tanda tangan lama tidak lagi menjadi versi terkini;
3. status kembali menjadi `draft`;
4. mahasiswa harus tanda tangan ulang;
5. mitra belum dapat menandatangani versi baru.

Tanda tangan lama tetap disimpan sebagai histori dan terikat ke `revisi_id` versi lama.

## 7. Versi dan keamanan tanda tangan

`logbook_revisi` digunakan sebagai versioning.

Setiap perubahan data harian membuat versi baru. Setiap versi mempunyai:

- `snapshot_data`
- `snapshot_hash`

Tanda tangan menyimpan:

- `revisi_id`
- `tahap`
- `penanda_tangan_id`
- `signature_path`
- `signature_hash`
- `snapshot_hash`
- metadata waktu, IP, dan user-agent

Dengan demikian tanda tangan tidak hanya menyimpan gambar, tetapi juga dikaitkan dengan snapshot data yang ditandatangani.

> Tanda tangan pada aplikasi ini merupakan tanda tangan elektronik internal untuk workflow dan audit aplikasi. Status legal/sertifikasi tanda tangan elektronik formal harus ditentukan sesuai kebijakan institusi dan regulasi yang berlaku.

## 8. Penyimpanan file privat

Bukti ketidakhadiran dan gambar tanda tangan disimpan di:

```text
storage/private/logbook/
├── evidence/
└── signatures/
```

File tidak boleh disajikan sebagai static file biasa.

`router.php` memblokir akses langsung ke `/storage/private/`.

File hanya disajikan melalui:

```text
/dashboard/logbook/file/signature/{id}
/dashboard/logbook/file/evidence/{id}
```

Controller file melakukan authorization berdasarkan role dan relasi logbook sebelum mengirim file.

## 9. PDF

PDF dibuat menggunakan Dompdf yang sudah tersedia di project.

Download per minggu:

```text
/dashboard/mahasiswa/logbook/download/{id}
```

Download seluruh logbook pada satu penempatan:

```text
/dashboard/mahasiswa/logbook/download-semua/{penempatan_id}
```

Urutan PDF mengikuti nomor minggu:

```text
Page 1 → Minggu 1
Page 2 → Minggu 2
Page 3 → Minggu 3
...
```

Format mengikuti contoh berkas logbook: identitas mahasiswa/mitra, periode minggu, tabel Hari/Tanggal, Jam Masuk, Jam Pulang, Kegiatan, serta tanda tangan mahasiswa, pembimbing lapangan/mitra, dan dosen pembimbing.

## 10. Migration

Migration baru:

```text
020_rebuild_logbook_workflow.sql
```

Jalankan setelah migration sebelumnya sudah tercatat:

```bash
php -v
```

Kemudian jalankan SQL migration menggunakan koneksi PostgreSQL project, misalnya:

```bash
PGPASSWORD="$DB_PASSWORD" psql \
  "host=$DB_HOST port=$DB_PORT dbname=$DB_NAME user=$DB_USER password=$DB_PASSWORD sslmode=require" \
  -f database/migrations/020_rebuild_logbook_workflow.sql
```

Verifikasi:

```sql
SELECT migration
FROM schema_migrations
WHERE migration IN (
    '019_optimize_dashboard_mahasiswa.sql',
    '020_rebuild_logbook_workflow.sql'
)
ORDER BY migration;
```

Tabel baru:

```text
logbook_harian
```

Perubahan tabel:

```text
logbook_revisi
    + snapshot_data
    + snapshot_hash

logbook_tanda_tangan
    + signature_path
    + signature_hash
    + snapshot_hash
    + metadata
```

## 11. Struktur kode

### Mahasiswa

```text
controllers/dashboard/mahasiswa/LogbookController.php
models/Logbook.php
pages/dashboard/mahasiswa/logbook/
├── index.php
├── form.php
├── detail.php
└── daily-form.php
components/dashboard/mahasiswa/logbook/
├── PageHeader.php
├── LogbookContent.php
└── MainContent.php
```

### Role workflow

```text
controllers/dashboard/dosen/LogbookController.php
controllers/dashboard/mitra/LogbookController.php
controllers/dashboard/tendik/LogbookController.php
controllers/dashboard/koordinatorMagang/LogbookController.php
```

### Signature dan file

```text
components/dashboard/logbook/SignaturePad.php
controllers/dashboard/LogbookFileController.php
```

### Database

```text
database/migrations/020_rebuild_logbook_workflow.sql
```

## 12. Seeder testing

`SeedDimasLogbook.php` digunakan untuk membuat mahasiswa Dimas sebagai mahasiswa yang sedang menjalani penempatan magang.

Data utama:

```text
Login ID : mhs_dimas
Password : Password123!
NIM      : 23410004
Status   : penempatan berlangsung
```

Seeder tidak membuat minggu secara paksa karena pembuatan minggu harus melewati aturan bisnis aplikasi. Setelah login sebagai Dimas:

1. buka Dashboard → Logbook;
2. buat Minggu 1;
3. isi tanggal yang sudah terjadi satu per satu;
4. jika tidak hadir, isi alasan dan bukti jika diperlukan;
5. setelah periodenya selesai dan lengkap, tanda tangan mahasiswa;
6. login sebagai Mitra dan tanda tangani;
7. login sebagai Dosen Pembimbing dan tanda tangani;
8. uji edit sebelum tanda tangan mitra;
9. pastikan edit membuat mahasiswa harus tanda tangan ulang;
10. uji bahwa edit ditolak setelah mitra menandatangani.

## 13. Checklist pengujian bisnis

### Pembuatan minggu

- [ ] Minggu pertama mengikuti tanggal mulai penempatan.
- [ ] Minggu pertama berakhir Minggu.
- [ ] Minggu berikutnya dimulai Senin.
- [ ] Minggu masa depan ditolak.
- [ ] Minggu yang terlambat tetap dapat dibuat.
- [ ] Minggu yang sama tidak dapat dibuat dua kali.

### Aktivitas harian

- [ ] Satu tanggal hanya satu logbook.
- [ ] Tanggal masa depan ditolak.
- [ ] Sabtu/Minggu dapat diisi.
- [ ] Tidak hadir membutuhkan alasan.
- [ ] Bukti dapat divalidasi Tendik/Koordinator.

### Tanda tangan

- [ ] Mahasiswa tidak dapat tanda tangan sebelum minggu selesai.
- [ ] Mahasiswa tidak dapat tanda tangan jika hari belum lengkap.
- [ ] Mahasiswa tidak dapat tanda tangan jika validasi masih menunggu.
- [ ] Mitra tidak dapat tanda tangan sebelum mahasiswa.
- [ ] Dosen tidak dapat tanda tangan sebelum mitra.
- [ ] Signature terikat pada versi logbook.
- [ ] Edit setelah tanda tangan mahasiswa membuat signature lama tidak berlaku untuk versi baru.
- [ ] Edit setelah tanda tangan mitra ditolak backend.

### Histori

- [ ] Penempatan lama tetap dapat dilihat.
- [ ] Penempatan baru tidak menimpa logbook lama.
- [ ] Download semua mengurutkan Minggu 1, Minggu 2, dst.

## 11. Database hardening

Migration `021_harden_logbook_workflow.sql` menambahkan perlindungan di level PostgreSQL untuk aturan yang tidak boleh dilewati hanya dengan memanggil endpoint secara langsung.

Database memvalidasi bahwa:

- tanggal logbook harian berada di dalam periode minggu;
- tanggal logbook harian tidak boleh berada di masa depan;
- logbook harian tidak dapat diubah setelah mitra menandatangani;
- tanda tangan selalu mengacu ke `revisi_id` versi terbaru;
- mahasiswa hanya dapat menandatangani sebagai pemilik logbook;
- mitra hanya dapat menandatangani sebagai mitra dari penempatan;
- dosen hanya dapat menandatangani sebagai dosen pembimbing penempatan;
- tanda tangan hanya dapat dilakukan pada tahap workflow yang sesuai;
- histori tanda tangan tidak dapat diubah atau dihapus.

Perlindungan database ini merupakan lapisan tambahan. Authorization pada controller/model tetap wajib dipertahankan.

Untuk instalasi baru, jalankan migration `020_rebuild_logbook_workflow.sql` kemudian `021_harden_logbook_workflow.sql` setelah migration sebelumnya tercatat.