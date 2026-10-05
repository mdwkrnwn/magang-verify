# Panduan Migration — Windows

## 1. Tujuan

Dokumen ini digunakan oleh anggota tim yang menjalankan proyek secara langsung di Windows dan menggunakan PostgreSQL lokal, tanpa WSL.

Migration dapat dijalankan melalui PowerShell, SQL Shell (psql), atau pgAdmin.

## 2. Persiapan

Pastikan:

* PostgreSQL sudah terpasang dan servicenya berjalan.
* Database `magang_verify` sudah tersedia.
* File repository VerifyMagang sudah di-clone ke komputer.
* File migration tersedia di folder `database/migrations/`.
* Akun PostgreSQL yang digunakan memiliki izin untuk mengubah struktur database.

Contoh lokasi repository:

```text
D:\projects\magang-verify
```

Lokasi tersebut hanya contoh. Gunakan lokasi repository masing-masing.

## 3. Membuat Database Baru

Jika database `magang_verify` belum tersedia, buat melalui pgAdmin atau SQL Shell:

```sql
CREATE DATABASE magang_verify;
```

Jalankan perintah tersebut hanya jika database belum ada.

Setelah itu, hubungkan ke database `magang_verify`.

## 4. Menjalankan Migration melalui PowerShell

### Langkah 1 — Masuk ke folder proyek

```powershell
cd "D:\projects\magang-verify"
```

### Langkah 2 — Pastikan file migration tersedia

```powershell
Get-ChildItem .\database\migrations\
```

### Langkah 3 — Jalankan migration pertama

Jika tabel `schema_migrations` belum dibuat, jalankan:

```powershell
psql -h localhost -p 5432 `
  -U postgres -d magang_verify -W `
  -v ON_ERROR_STOP=1 `
  -f "database/migrations/000_create_schema_migrations.sql"
```

Jika `psql` tidak dikenali, gunakan path lengkap `psql.exe`. Contoh untuk PostgreSQL 16:

```powershell
& "C:\Program Files\PostgreSQL\16\bin\psql.exe" `
  -h localhost -p 5432 `
  -U postgres -d magang_verify -W `
  -v ON_ERROR_STOP=1 `
  -f "database/migrations/000_create_schema_migrations.sql"
```

Sesuaikan versi PostgreSQL dan username dengan konfigurasi lokal.

Jika berhasil, terminal akan menampilkan:

```text
CREATE TABLE
```

### Langkah 4 — Catat migration pertama

Setelah berhasil, catat migration:

```powershell
psql -h localhost -p 5432 `
  -U postgres -d magang_verify -W `
  -c "INSERT INTO schema_migrations (migration) VALUES ('000_create_schema_migrations.sql') ON CONFLICT (migration) DO NOTHING;"
```

Jika `psql` tidak ada di PATH, gunakan path lengkap `psql.exe` seperti langkah sebelumnya.

### Langkah 5 — Jalankan migration tabel users

```powershell
psql -h localhost -p 5432 `
  -U postgres -d magang_verify -W `
  -v ON_ERROR_STOP=1 `
  -f "database/migrations/001_create_table_users.sql"
```

### Langkah 6 — Catat migration users

```powershell
psql -h localhost -p 5432 `
  -U postgres -d magang_verify -W `
  -c "INSERT INTO schema_migrations (migration) VALUES ('001_create_table_users.sql') ON CONFLICT (migration) DO NOTHING;"
```

Lakukan langkah yang sama untuk migration berikutnya, dengan mengganti nama file sesuai urutannya.

## 5. Alternatif Menjalankan Migration melalui SQL Shell

1. Buka SQL Shell (psql).
2. Hubungkan ke database `magang_verify`.
3. Jalankan file SQL menggunakan `\i`.

Contoh:

```sql
\i 'D:/projects/magang-verify/database/migrations/000_create_schema_migrations.sql'
```

Setelah tabel `schema_migrations` berhasil dibuat, catat migration melalui query `INSERT` yang dijelaskan pada bagian sebelumnya.

Lanjutkan dengan file migration berikutnya secara berurutan.

## 6. Alternatif Menggunakan pgAdmin

1. Buka pgAdmin.
2. Hubungkan ke server PostgreSQL.
3. Pilih database `magang_verify`.
4. Buka Query Tool.
5. Buka file migration dari repository menggunakan editor teks.
6. Salin isi SQL dan tempel ke Query Tool.
7. Jalankan query dan pastikan tidak ada error.
8. Setelah berhasil, jalankan query `INSERT` untuk mencatat migration.
9. Ulangi untuk migration berikutnya.

Jangan menjalankan file migration yang sama melalui PowerShell dan pgAdmin secara bergantian pada database yang sama.

## 7. Memeriksa Riwayat Migration

Jalankan query berikut melalui PowerShell, SQL Shell, atau pgAdmin:

```sql
SELECT migration, executed_at
FROM schema_migrations
ORDER BY id;
```

Pastikan migration yang telah dijalankan muncul pada hasil query.

## 8. Setelah Migration Berhasil

1. Perbarui `database/schema.sql`.
2. Pastikan perubahan migration dan snapshot masuk ke Git.
3. Commit perubahan dengan pesan yang jelas.

Contoh:

```bash
git add database/migrations database/schema.sql
git commit -m "Add users table migration"
```

## 9. Hal yang Harus Diperhatikan

* Gunakan username PostgreSQL milik sendiri; `postgres` hanya contoh.
* Jangan mencatat migration jika SQL gagal.
* Jangan mengubah migration lama yang sudah dibagikan.
* Jangan menjalankan ulang migration pada database yang sama jika sudah diterapkan.
* Jika database lokal masih kosong, jalankan migration dari nomor terkecil secara berurutan.
* Jika tabel `schema_migrations` sudah tersedia, jangan membuatnya ulang.
