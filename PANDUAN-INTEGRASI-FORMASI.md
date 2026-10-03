# Integrasi Formasi dan Pendaftaran Magang

## Yang sudah disambungkan
- Daftar formasi membaca `formasi_magang` dan `mitra` dari PostgreSQL/Supabase.
- Formasi yang tampil harus berstatus `dibuka`, mitra `terverifikasi` dan aktif, serta kuota belum penuh.
- Pencarian, lokasi, sistem kerja, dan tahun akademik menggunakan kolom yang tersedia pada skema.
- Detail formasi mengambil data berdasarkan ID database. Bidang, sistem kerja, periode, kuota, dan informasi mitra ditampilkan dari database.
- Form Lamar membaca identitas mahasiswa dari `users` + `profil_mahasiswa`; nama, NIM, dan program studi tidak dipercaya dari input browser.
- Pengajuan memvalidasi token CSRF dan dua file PDF (maksimal 5 MB per file), menyimpan pendaftaran, metadata dokumen, dan riwayat status awal.
- Halaman Pengajuan Saya dan detailnya mengambil data milik mahasiswa yang sedang login.
- Database tetap menjadi otoritas untuk memeriksa status formasi, kuota, dan pengajuan aktif saat submit.

## Migration tambahan yang wajib diterapkan
Jalankan isi `database/migrations/014_create_dokumen_pendaftaran_magang.sql` pada SQL Editor Supabase. Migration ini menambahkan tabel metadata dokumen karena skema `pendaftaran_magang` yang ada tidak memiliki kolom dokumen. Migration lama tidak diubah.

## Penyimpanan file
File PDF disimpan di `storage/pendaftaran` dengan nama acak; database hanya menyimpan path relatif dan metadata. `.htaccess` disertakan untuk server Apache. Jika deployment memakai Nginx, tambahkan aturan server agar request ke `/storage/` ditolak dan file hanya bisa diakses melalui endpoint unduhan terautentikasi. Jangan membuat folder tersebut menjadi penyimpanan publik.

## Batasan yang berasal dari skema saat ini
- `formasi_magang` tidak mempunyai kolom batas pendaftaran, daftar program studi yang diperbolehkan, atau daftar keterampilan yang dibutuhkan. UI tidak mengarang nilai untuk kolom tersebut; yang ditampilkan adalah bidang, sistem kerja, periode, kuota, dan persyaratan bebas dari database.
- `jumlah_diterima` tidak dinaikkan saat mahasiswa baru mengajukan, karena pendaftaran masih harus melewati persetujuan dosen dan respons mitra. Proses penerimaan final perlu memperbarui jumlah diterima dalam alur persetujuan yang berwenang.
- Akses unduh dokumen dari halaman detail belum dibuat; detail saat ini menampilkan nama dokumen yang tersimpan.

## Pengujian
Jalankan `php -l` pada file PHP yang diubah dan uji alur dengan akun mahasiswa yang memiliki baris pada `profil_mahasiswa`. Koneksi Supabase dan unggahan berkas perlu diuji di lingkungan deployment karena kredensial dan konfigurasi server tidak disertakan dalam arsip.
