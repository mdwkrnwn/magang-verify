# Seeder VerifyMagang

Seeder dipisah berdasarkan domain agar mudah dijalankan dan di-debug.

## Urutan

```bash
php database/seeders/user/mahasiswa/SeedUsersMahasiswa.php
php database/seeders/user/mahasiswa/SeedProfilMahasiswa.php
php database/seeders/user/mahasiswa/SeedPortofolioMahasiswa.php
php database/seeders/user/mahasiswa/SeedSertifikatMahasiswa.php
php database/seeders/user/mahasiswa/SeedMitraMagang.php
php database/seeders/user/mahasiswa/SeedPendaftaranMagang.php
php database/seeders/user/mahasiswa/SeedPengalamanMahasiswa.php
php database/seeders/user/mahasiswa/SeedDashboardMahasiswa.php
```

Password seluruh akun seed:
`Password123!`

Akun mahasiswa:
- mhs_andi
- mhs_budi
- mhs_citra
- mhs_dimas
- mhs_eka

Akun pendukung:
- dosen_seed
- mitra_seed

Distribusi portofolio:
- Andi: terverifikasi + publik
- Budi: terverifikasi + publik
- Citra: belum_diverifikasi + draft
- Dimas: perlu_perbaikan + draft
- Eka: ditolak + draft

Distribusi sertifikat:
- Andi: terverifikasi
- Budi: terverifikasi
- Citra: belum_terverifikasi
- Dimas: dalam_peninjauan
- Eka: ditolak

Magang:
- Andi: selesai + penempatan selesai + verifikasi penyelesaian terverifikasi
- Budi: menunggu persetujuan dosen

Pengalaman:
- Andi: dibuat dari magang selesai (is_otomatis=true)
- Budi/Citra/Dimas/Eka: pengalaman manual untuk pengujian UI

Dashboard:
- Aktivitas dan notifikasi dibuat per mahasiswa.
- Andi memiliki 4 logbook mingguan dari penempatan yang selesai.
