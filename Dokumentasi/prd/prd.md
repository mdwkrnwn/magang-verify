# PRD — MagangVerify

## Product Requirements Document & Business Workflow Specification

> Dokumen acuan utama untuk Developer dan AI Coding Assistant.
>
> Tujuan dokumen ini adalah menjaga agar seluruh pengembangan MagangVerify mengikuti **alur bisnis yang telah disepakati**, struktur data yang ada, role/otorisasi, workflow magang, logbook, portofolio, sertifikat, dan histori magang.
>
> **Aturan utama:** jangan mengubah business flow hanya karena implementasi teknis lebih mudah. Jika implementasi membutuhkan perubahan flow, perubahan tersebut harus dikonfirmasi terlebih dahulu.

---

# 1. Identitas Produk

**Nama:** MagangVerify  
**Konsep:** Platform Portofolio & Magang Terpadu Mahasiswa

MagangVerify mengintegrasikan profil mahasiswa, portofolio, sertifikat, formasi magang, pendaftaran, persetujuan dosen, respons mitra, penempatan, logbook, verifikasi penyelesaian, penilaian, dan histori magang.

## Masalah yang Diselesaikan

1. Portofolio mahasiswa tersebar.
2. Sertifikat dan pengalaman sulit dikelola/diverifikasi.
3. Informasi formasi magang tidak terpusat.
4. Proses pengajuan magang tidak transparan.
5. Persetujuan dosen dan respons mitra tidak terdokumentasi baik.
6. Logbook masih berpotensi manual.
7. Tanda tangan dapat tersebar di luar platform.
8. Histori magang harus tetap tersedia ketika mahasiswa memiliki lebih dari satu magang.
9. Kampus membutuhkan rekam jejak proses yang dapat diaudit.

# 2. Tujuan Produk

Alur utama:

```text
Profil Mahasiswa
      ↓
Portofolio & Sertifikat
      ↓
Formasi Magang
      ↓
Pengajuan Magang
      ↓
Persetujuan Dosen
      ↓
Respons Mitra
      ↓
Penempatan Magang
      ↓
Logbook
      ↓
Verifikasi / Penilaian / Penyelesaian
      ↓
Histori Magang & Pengalaman
```

Prinsip:

1. Database adalah source of truth.
2. Business rule kritis harus divalidasi backend.
3. UI bukan pengganti authorization.
4. Histori tidak boleh hilang.
5. Signature dan proses penting memiliki audit trail.
6. AI/developer tidak boleh mengarang schema, role, status, route, atau relasi.
7. Migration existing tidak boleh sembarangan dihapus atau dijalankan ulang.

# 3. Role Sistem

| Role | Fungsi |
|---|---|
| Mahasiswa | Profil, portofolio, sertifikat, pengalaman, formasi, pengajuan, logbook |
| Dosen | Persetujuan/pembimbingan dan tanda tangan logbook |
| Koordinator Magang | Monitoring/validasi proses magang sesuai kewenangan |
| Tendik/Manajemen | Administrasi, validasi dokumen/ketidakhadiran, monitoring |
| Mitra | Data perusahaan/formasi, respons pengajuan, tanda tangan logbook |

Role database:

```text
mahasiswa
dosen
koordinator_magang
tendik
mitra
```

# 4. Authorization

Semua endpoint wajib memvalidasi:

1. session/login;
2. role;
3. kepemilikan data;
4. relasi data;
5. status workflow;
6. hak tindakan pada status tersebut.

Menyembunyikan tombol di UI **bukan authorization**.

Contoh:

- Mahasiswa tidak boleh mengedit mahasiswa lain.
- Mitra hanya melihat mahasiswa yang ditempatkan pada mitra tersebut.
- Dosen hanya melihat mahasiswa yang menjadi bimbingannya.
- Mitra tidak dapat sign sebelum mahasiswa.
- Dosen tidak dapat sign sebelum mitra.

# 5. Entitas Utama

```text
users
profil_mahasiswa
profil_dosen

mitra
formasi_magang

pendaftaran_magang
riwayat_pendaftaran_magang

penempatan_magang

logbook_mingguan
logbook_revisi

portofolios
sertifikat
pengalaman

aktivitas_pengguna
notifikasi

verifikasi_penyelesaian_magang
penilaian_magang
```

Konsep relasi:

```text
users
├── profil_mahasiswa
└── profil_dosen

mitra
└── formasi_magang
        ↓
pendaftaran_magang
        ↓
penempatan_magang
        ↓
logbook
        ↓
verifikasi/penilaian
```

# 6. Profil Mahasiswa

Profil utama meliputi:

- NIM
- nama
- program studi
- angkatan
- email
- nomor telepon
- alamat
- foto
- CV

CV dikelola di **Dashboard Mahasiswa → Profil**, bukan sebagai input landing page.

CV:

- PDF;
- maksimal 5 MB;
- validasi MIME/signature;
- nama file random/aman;
- CV lama diganti saat upload baru;
- metadata disimpan;
- akses file melalui endpoint aplikasi.

# 7. Landing Page

Landing page mahasiswa harus database-backed.

Fitur:

- daftar mahasiswa;
- pencarian/filter;
- profil;
- portofolio publik;
- sertifikat sesuai aturan publikasi;
- pengalaman;
- CV jika diizinkan;
- detail portofolio.

Mahasiswa unggulan menggunakan ranking yang telah dirancang:

1. jumlah portofolio publik;
2. jumlah sertifikat terverifikasi;
3. angkatan lebih baru;
4. nama.

# 8. Portofolio

Mahasiswa dapat:

- create;
- read;
- update;
- delete;
- mengatur publikasi;
- mengelola data pendukung.

Status publikasi:

```text
draft
publik
arsip
```

Status verifikasi:

```text
belum_diverifikasi
terverifikasi
ditolak
perlu_perbaikan
```

Portofolio publik hanya ditampilkan jika memenuhi aturan publikasi.

Aksi berhasil seperti create/update/delete dicatat pada `aktivitas_pengguna`.

Activity hanya dibuat setelah mutation berhasil.

# 9. Sertifikat

Mahasiswa dapat menambah, melihat, mengedit, menghapus, dan mengajukan verifikasi sertifikat.

Data meliputi nama, penerbit, tanggal terbit, nomor sertifikat, deskripsi, tautan, dan file.

Status harus mengikuti schema existing.

# 10. Pengalaman

Jenis:

```text
magang
pekerjaan
organisasi
freelance
proyek
lainnya
```

Status publikasi:

```text
draft
publik
arsip
```

Pengalaman magang dapat dibuat otomatis setelah magang selesai, tanpa menghapus histori proses magang.

# 11. Mitra

Kategori valid:

```text
BUMN
BUMD
Perusahaan Swasta
Instansi Pemerintah
Institusi Pendidikan
UMKM
Organisasi
Lainnya
```

Jangan memasukkan kategori di luar constraint database.

# 12. Formasi Magang

Satu mitra dapat memiliki banyak formasi.

Field utama:

- judul;
- deskripsi;
- bidang;
- jumlah kuota;
- jumlah diterima;
- persyaratan;
- lokasi;
- sistem kerja;
- tanggal mulai;
- tanggal selesai;
- tahun akademik;
- status;
- pembuat.

`jumlah_diterima` tidak bertambah saat mahasiswa baru mengajukan. Jumlah diterima bertambah ketika mahasiswa benar-benar diterima.

# 13. Pengajuan Magang

Workflow:

```text
Mahasiswa
    ↓
Pilih formasi
    ↓
Ajukan
    ↓
Persetujuan Dosen
    ↓
Respons Mitra
    ↓
Diterima
    ↓
Penempatan
```

Status pendaftaran:

```text
diajukan
menunggu_persetujuan_dosen
menunggu_respons_mitra
seleksi
diterima
ditolak_dosen
ditolak_mitra
perlu_revisi
dibatalkan
selesai
```

Status persetujuan dosen:

```text
menunggu
disetujui
ditolak
perlu_revisi
```

Status respons mitra:

```text
menunggu
diterima
ditolak
seleksi
```

Mahasiswa tidak boleh memiliki lebih dari satu pendaftaran aktif sesuai partial unique index existing.

# 14. Dokumen Pendaftaran

Wajib:

```text
pakta_integritas
daftar_riwayat_hidup
khs
ktp
ktm
surat_izin_orang_tua
```

Opsional:

```text
bpjs_asuransi
sktm_kip
proposal_magang
sertifikat_kompetensi
```

Validasi file mencakup ukuran, MIME, ekstensi, dan keamanan penyimpanan.

# 15. Penempatan Magang

`penempatan_magang` adalah parent untuk histori dan logbook.

Status:

```text
persiapan
berlangsung
menunggu_penilaian
selesai
dibatalkan
```

**FK penting:**

```text
penempatan_magang.dosen_pembimbing_id
→ profil_dosen.id
```

Sedangkan:

```text
penempatan_magang.ditetapkan_oleh
→ users.id
```

Jangan menukar keduanya.

# 16. Multi-Magang

Mahasiswa dapat memiliki banyak histori magang:

```text
Mahasiswa
├── Magang #1
│   ├── Penempatan
│   ├── Logbook
│   ├── Verifikasi
│   └── Pengalaman
└── Magang #2
    ├── Penempatan
    ├── Logbook
    ├── Verifikasi
    └── Pengalaman
```

Magang #1 tidak boleh hilang ketika Magang #2 dibuat.

# 17. LOGBOOK — BUSINESS RULE UTAMA

Struktur wajib:

```text
Penempatan Magang
        ↓
Minggu
        ↓
Hari
        ↓
Aktivitas
```

Bukan satu record besar yang mencampur seluruh aktivitas.

# 18. Pembuatan Minggu

Minggu pertama mengikuti **tanggal mulai penempatan**.

Jika mulai Rabu 7 Oktober:

```text
Minggu 1:
7 Oktober → 11 Oktober

Minggu 2:
12 Oktober → 18 Oktober

Minggu 3:
19 Oktober → 25 Oktober
```

Minggu pertama bukan dipaksa Senin.

Minggu berikutnya mengikuti Senin–Minggu.

## Future Period

Tidak boleh membuat minggu masa depan.

```text
Hari ini 6 Oktober

Minggu 1: 6–11 Oktober ✓
Minggu 2: 12–18 Oktober ✗
```

Namun periode masa lalu yang belum dibuat tetap boleh dibuat.

Prinsip:

> **Past period boleh dibuat; future period tidak boleh dibuat.**

# 19. Logbook Harian

Setiap tanggal = satu record.

```text
6 Oktober → 1 record
7 Oktober → 1 record
8 Oktober → 1 record
```

Tidak boleh:

- membuat satu record untuk banyak tanggal;
- membuat tanggal masa depan;
- membuat dua record untuk tanggal sama pada penempatan sama.

Sabtu/Minggu tetap diperbolehkan karena hari kerja tiap perusahaan dapat berbeda.

# 20. Isi Harian

Mengacu pada berkas contoh logbook:

- Hari/Tanggal
- Jam Masuk
- Jam Pulang
- Kegiatan

Aktivitas adalah **free text**.

Mahasiswa dapat menulis:

```text
Melakukan konfigurasi jaringan...
Sakit
Izin keperluan keluarga
Libur perusahaan
```

Sistem tidak boleh memaksa alasan hanya melalui dropdown jika kebutuhan bisnis memerlukan teks bebas.

# 21. Kelengkapan Minggu

Sebelum mahasiswa dapat sign:

1. seluruh tanggal dalam periode harus memiliki record;
2. hari tidak masuk tetap harus memiliki penjelasan;
3. bukti yang membutuhkan validasi harus sudah memenuhi workflow validasi;
4. field wajib harus lengkap.

Jika belum lengkap:

```text
Tanda tangan = tidak tersedia
```

Backend tetap melakukan validasi final.

# 22. Digital Signature

Signature dilakukan langsung di platform.

Tidak menggunakan:

```text
Download
→ Print
→ Sign
→ Scan
→ Upload
```

Signature menggunakan canvas yang mendukung mouse/touch/stylus.

Signature terikat pada:

- user;
- role;
- minggu;
- waktu;
- versi/data yang ditandatangani;
- status.

# 23. Urutan Signature

Wajib:

```text
Mahasiswa
    ↓
Mitra
    ↓
Dosen Pembimbing
```

Mahasiswa hanya dapat sign setelah minggu lengkap.

Mitra hanya dapat sign setelah mahasiswa sign.

Dosen hanya dapat sign setelah mitra sign.

# 24. Edit Setelah Signature

Aturan:

```text
Belum sign mahasiswa
→ edit ✓

Mahasiswa sign, Mitra belum sign
→ edit ✓

Mitra sudah sign
→ edit ✗
```

Jika mahasiswa mengedit setelah signature mahasiswa tetapi sebelum mitra:

```text
Data berubah
    ↓
Signature mahasiswa dibatalkan
    ↓
Mahasiswa wajib sign ulang
    ↓
Mitra baru dapat sign
```

UI wajib memberikan keterangan:

> Logbook telah berubah. Tanda tangan mahasiswa sebelumnya tidak lagi berlaku dan harus ditandatangani ulang sebelum dapat diteruskan ke mitra.

Setelah Mitra sign, backend wajib menolak perubahan.

# 25. Status di Mitra

Mitra hanya melihat:

```text
Belum ditandatangani
Sudah ditandatangani
```

Tidak perlu menampilkan status tanda tangan dosen kepada mitra.

# 26. Tendik / Koordinator

Mahasiswa sendiri mengisi aktivitas dan dapat menulis kondisi seperti sakit/izin.

Jika diperlukan bukti:

```text
Mahasiswa
    ↓
Alasan + bukti
    ↓
Tendik/Koordinator
    ↓
Validasi
```

Minggu tidak dapat dianggap lengkap jika validasi yang diwajibkan belum selesai.

# 27. Penyelesaian Minggu

Setelah:

```text
Mahasiswa Sign
↓
Mitra Sign
↓
Dosen Sign
```

minggu selesai.

Workflow berulang untuk minggu berikutnya sampai periode penempatan selesai.

# 28. Penyelesaian Magang

Setelah penempatan selesai:

- histori logbook tetap tersedia;
- signature tetap tersedia;
- PDF tetap dapat dibuat;
- data tidak boleh hilang;
- verifikasi penyelesaian mengikuti `verifikasi_penyelesaian_magang`;
- pengalaman magang dapat dibuat sesuai aturan.

# 29. PDF Logbook

Download seluruh logbook harus berurutan:

```text
Page 1 → Minggu 1
Page 2 → Minggu 2
Page 3 → Minggu 3
...
```

Jika satu minggu membutuhkan beberapa halaman, urutan minggu tetap dipertahankan.

Format mengikuti contoh berkas:

- identitas mahasiswa;
- identitas mitra;
- periode;
- Hari/Tanggal;
- Jam Masuk;
- Jam Pulang;
- Kegiatan;
- signature Mahasiswa;
- signature Mitra;
- signature Dosen.

# 30. Dashboard Mahasiswa

Data statistik harus berasal dari database.

Dapat menampilkan:

- jumlah portofolio;
- jumlah sertifikat;
- pengajuan;
- status magang;
- progres profil;
- progres magang;
- aktivitas terbaru;
- logbook;
- notifikasi.

Gunakan `aktivitas_pengguna` existing.

Aksi yang berhasil dapat dicatat:

```text
Portofolio create/update/delete
Sertifikat create/update/delete
Profil update
CV upload
Pengajuan
Perubahan status
Logbook create/update
Signature mahasiswa
Signature mitra
Signature dosen
```

Activity dibuat setelah mutation berhasil.

# 31. Notifikasi

Gunakan entitas `notifikasi` existing.

Contoh:

- pengajuan berubah status;
- perlu revisi;
- respons mitra;
- logbook siap ditandatangani;
- logbook dikembalikan;
- signature selesai;
- validasi selesai.

Notifikasi berbeda dari activity log.

# 32. Header / Sidebar

Settings dan Logout berada pada profile dropdown header.

Menu dropdown:

```text
Beranda
Pengaturan
Keluar
```

Logout menggunakan POST + CSRF.

# 33. Theme

Gunakan theme global existing:

```text
light
dark
```

Jangan membuat theme berbeda per halaman.

UI harus tetap konsisten dengan dashboard.

# 34. Security

## Authentication

Dashboard harus memeriksa session.

Session user sudah memuat:

```text
id
login_id
name
role
```

Jangan membuat sistem session authentication kedua tanpa alasan.

## CSRF

Semua mutation POST menggunakan:

```text
csrfToken()
verifyCsrfToken()
```

dengan key:

```text
_csrf_token
```

## File

Validasi MIME, signature/magic bytes bila relevan, ukuran, ekstensi, lokasi, dan nama file.

## Signature

Signature harus terikat pada data yang ditandatangani. Jika data berubah, signature yang terdampak menjadi tidak valid.

# 35. Database Rules

Sebelum migration baru:

1. cek `schema_migrations`;
2. cek schema aktual;
3. cek FK;
4. cek constraint;
5. cek index;
6. cek migration existing.

Jangan menghapus migration existing hanya untuk memperbaiki implementasi.

# 36. Seed Testing

Akun yang sudah digunakan:

| Role | Login | Password |
|---|---|---|
| Mahasiswa | `mhs_dimas` | `Password123!` |
| Dosen | `dosen_seed` | `Password123!` |
| Mitra | `mitra_seed` | `Password123!` |
| Mahasiswa | `mhs_andi` | `Password123!` |
| Mahasiswa | `mhs_budi` | `Password123!` |
| Mahasiswa | `mhs_citra` | `Password123!` |
| Mahasiswa | `mhs_eka` | `Password123!` |

Dimas digunakan sebagai skenario mahasiswa aktif.

Hubungan:

```text
mhs_dimas
    ↓
Penempatan aktif
    ↓
dosen_seed
    ↓
mitra_seed
```

`dosen_seed`:

```text
users.id = 18
profil_dosen.id = 2
```

Jangan menggunakan `users.id` sebagai `dosen_pembimbing_id`.

# 37. End-to-End Testing

## Mahasiswa

Login:

```text
mhs_dimas
Password123!
```

Test:

1. melihat penempatan;
2. membuat minggu;
3. mencoba future week → ditolak;
4. membuat minggu terlambat → diperbolehkan;
5. membuat aktivitas harian;
6. future date → ditolak;
7. duplicate date → ditolak;
8. melengkapi minggu;
9. sign;
10. edit setelah student sign;
11. signature mahasiswa dibatalkan;
12. sign ulang.

## Mitra

Login:

```text
mitra_seed
Password123!
```

Sebelum mahasiswa sign, minggu belum dapat ditandatangani.

Setelah mahasiswa sign:

```text
Sign Mitra
```

Setelah sign, mahasiswa tidak dapat edit.

## Dosen

Login:

```text
dosen_seed
Password123!
```

Sebelum mitra sign, minggu belum dapat ditandatangani.

Setelah mitra sign:

```text
Sign Dosen
```

## Multi-Magang

Pastikan:

```text
Magang #1
    tetap tersimpan

Magang #2
    memiliki logbook sendiri
```

Tidak boleh bercampur.

# 38. UI/UX

UI harus:

- responsive;
- mobile friendly;
- tidak menyebabkan horizontal overflow;
- konsisten dengan dashboard;
- mendukung dark mode;
- memiliki loading/empty/error state;
- memberikan feedback;
- tidak hanya mengandalkan warna.

Status gunakan kombinasi:

```text
icon + label + color
```

# 39. Empty States

Belum magang:

```text
Anda belum menjalani magang.

[ Cari Formasi ]
```

Magang belum mulai:

```text
Magang Anda belum dimulai.
Logbook akan tersedia ketika periode dimulai.
```

Magang selesai:

```text
Magang telah selesai.
Anda masih dapat melihat histori logbook.
```

Belum ada logbook:

```text
Belum ada logbook pada minggu ini.
```

# 40. Activity & Audit

Untuk mutation penting:

```text
BEGIN TRANSACTION
    mutation
    activity
COMMIT
```

Jika gagal:

```text
ROLLBACK
```

Tidak boleh ada activity sukses palsu.

# 41. Struktur Implementasi

Developer wajib mengikuti struktur folder project existing.

Jangan membuat struktur framework baru hanya karena lebih nyaman.

Contoh area yang relevan:

```text
models/
controllers/
components/
pages/
routes/
database/migrations/
database/seeders/
assets/
function/
services/        # hanya jika memang sesuai arsitektur project
Dokumentasi/
```

Nama dan lokasi final harus mengikuti pola existing project.

# 42. AI Coding Rules

AI WAJIB:

1. Membaca file existing sebelum mengubah.
2. Mengikuti struktur folder project.
3. Mengikuti naming convention.
4. Menggunakan schema aktual.
5. Mengecek migration sebelum migration baru.
6. Mengecek model/controller existing.
7. Tidak mengarang kolom.
8. Tidak mengarang route.
9. Tidak mengarang role.
10. Tidak mengarang status.
11. Tidak mengubah business workflow tanpa persetujuan.
12. Tidak membuat duplicate helper/function.
13. Tidak membuat tabel duplicate.
14. Tidak menghapus histori.
15. Tidak menjadikan hide-button sebagai authorization.
16. Tidak menyimpan password plaintext.
17. Tidak melewati CSRF.
18. Menggunakan transaction untuk workflow multi-step.
19. Membuat activity setelah mutation berhasil.
20. Tidak mengubah FK hanya agar seed berhasil.
21. Tidak menggunakan `users.id` sebagai `profil_dosen.id`.
22. Tidak menganggap mahasiswa hanya memiliki satu magang.
23. Tidak membolehkan future logbook.
24. Tidak membolehkan duplicate tanggal.
25. Tidak membolehkan edit setelah Mitra sign.
26. Membatalkan signature mahasiswa jika data berubah sebelum Mitra sign.
27. Tidak membolehkan Mitra sign sebelum mahasiswa.
28. Tidak membolehkan Dosen sign sebelum Mitra.
29. Menjaga urutan PDF.
30. Mendokumentasikan perubahan.

# 43. AI Change Protocol

Sebelum mengubah code:

```text
1. Inspect
2. Understand
3. Identify dependencies
4. Plan
5. Implement
6. Validate
7. Document
```

Jika ada konflik:

```text
Business Requirement
        ↓
Database Constraint
        ↓
Backend
        ↓
UI
```

Business rule tidak boleh dikorbankan demi quick fix.

# 44. Dokumentasi

Feature besar wajib didokumentasikan dengan:

```text
business rule
database
backend
authorization
UI
testing
known limitation
```

Minimal dokumentasi Logbook menjelaskan:

- workflow;
- database;
- signature;
- authorization;
- PDF;
- testing.

# 45. Definition of Done

Feature dianggap selesai jika:

- [ ] database benar;
- [ ] migration benar;
- [ ] model sesuai schema;
- [ ] controller memiliki authorization;
- [ ] CSRF diterapkan;
- [ ] validation backend tersedia;
- [ ] UI sesuai workflow;
- [ ] responsive;
- [ ] dark mode aman;
- [ ] activity tercatat;
- [ ] notification sesuai kebutuhan;
- [ ] error state tersedia;
- [ ] empty state tersedia;
- [ ] seed/testing tersedia jika diperlukan;
- [ ] tidak ada duplicate route/function;
- [ ] tidak merusak feature existing;
- [ ] histori tidak hilang;
- [ ] dokumentasi diperbarui.

# 46. Prioritas Implementasi Logbook

```text
Audit schema
    ↓
Finalisasi migration
    ↓
Model/domain logic
    ↓
Mahasiswa workflow
    ↓
Daily logbook
    ↓
Student signature
    ↓
Mitra workflow
    ↓
Mitra signature
    ↓
Dosen workflow
    ↓
Dosen signature
    ↓
Tendik/Koordinator validation
    ↓
Activity & notification
    ↓
PDF
    ↓
Seeder
    ↓
End-to-end testing
    ↓
Documentation
```

# 47. Prinsip Akhir

MagangVerify bukan sekadar CRUD. Sistem adalah **workflow management system**.

Data memiliki:

```text
STATUS
OWNERSHIP
AUTHORIZATION
WORKFLOW
SIGNATURE
AUDIT TRAIL
HISTORY
```

Workflow Logbook:

```text
Penempatan
   ↓
Minggu
   ↓
Hari
   ↓
Lengkap
   ↓
Mahasiswa Sign
   ↓
Mitra Sign
   ↓
LOCK
   ↓
Dosen Sign
   ↓
Selesai
```

Workflow keseluruhan:

```text
Mahasiswa
   ↓
Profil / Portfolio / Sertifikat
   ↓
Formasi
   ↓
Pengajuan
   ↓
Dosen
   ↓
Mitra
   ↓
Penempatan
   ↓
Logbook
   ↓
Verifikasi
   ↓
Histori / Pengalaman
```

Semua data harus dapat ditelusuri kembali ke proses yang menghasilkan data tersebut.

---

# 48. Aturan Konflik

Jika requirement bisnis di dokumen ini bertentangan dengan implementasi existing:

1. identifikasi konflik;
2. jelaskan dampak;
3. jangan mengubah business rule secara diam-diam;
4. minta keputusan untuk konflik yang menyangkut alur bisnis;
5. gunakan migration baru untuk perubahan schema yang memang diperlukan.

**Jangan melakukan quick fix yang membuat workflow bisnis tidak konsisten.**
