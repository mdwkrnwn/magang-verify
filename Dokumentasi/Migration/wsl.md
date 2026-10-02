# Panduan Migration — WSL Ubuntu

## 1. Tujuan

Dokumen ini digunakan oleh anggota tim yang menjalankan proyek VerifyMagang melalui WSL Ubuntu.

Panduan ini mengikuti konfigurasi pengembangan yang menggunakan WSL untuk source code, PHP, dan Git, serta mengakses PostgreSQL yang berjalan di Windows.

## 2. Persiapan

Pastikan:

* WSL Ubuntu sudah terpasang.
* PostgreSQL di Windows sedang berjalan.
* Database `magang_verify` sudah tersedia.
* Perintah `psql` tersedia di WSL.
* Repository VerifyMagang sudah tersedia.

## 3. Masuk ke Folder Proyek

```bash
cd ~/projects/magang-verify
```

Pastikan file migration tersedia:

```bash
ls -1 database/migrations/
```

## 4. Konfigurasi Koneksi

Pada konfigurasi pengembangan yang digunakan saat dokumentasi ini dibuat:

| Pengaturan | Nilai           |
| ---------- | --------------- |
| Host       | `172.21.192.1`  |
| Port       | `5432`          |
| Database   | `magang_verify` |
| Username   | `magangverify`  |

Alamat host di atas khusus untuk lingkungan pengembangan saat ini. Alamat host Windows yang diakses dari WSL dapat berubah, sehingga anggota tim harus menggunakan alamat yang benar pada komputernya.

Jangan menaruh password database di repository.

## 5. Menjalankan Migration

### Langkah 1 — Jalankan migration pertama

Jika tabel `schema_migrations` belum tersedia:

```bash
psql -h 172.21.192.1 -p 5432 \
-U magangverify -d magang_verify -W \
-v ON_ERROR_STOP=1 \
-f database/migrations/000_create_schema_migrations.sql
```

Masukkan password ketika diminta.

Jika berhasil, output akan menampilkan:

```text
CREATE TABLE
```

### Langkah 2 — Catat migration pertama

```bash
psql -h 172.21.192.1 -p 5432 \
-U magangverify -d magang_verify -W \
-c "INSERT INTO schema_migrations (migration) VALUES ('000_create_schema_migrations.sql') ON CONFLICT (migration) DO NOTHING;"
```

### Langkah 3 — Jalankan migration users

```bash
psql -h 172.21.192.1 -p 5432 \
-U magangverify -d magang_verify -W \
-v ON_ERROR_STOP=1 \
-f database/migrations/001_create_table_users.sql
```

### Langkah 4 — Catat migration users

```bash
psql -h 172.21.192.1 -p 5432 \
-U magangverify -d magang_verify -W \
-c "INSERT INTO schema_migrations (migration) VALUES ('001_create_table_users.sql') ON CONFLICT (migration) DO NOTHING;"
```

### Langkah 5 — Periksa riwayat

```bash
psql -h 172.21.192.1 -p 5432 \
-U magangverify -d magang_verify -W \
-c "SELECT migration, executed_at FROM schema_migrations ORDER BY id;"
```

Pastikan migration tercatat.

## 6. Menjalankan Migration Berikutnya

Jika tim membuat file baru, misalnya:

```text
002_create_table_mahasiswa.sql
```

Jalankan file tersebut:

```bash
psql -h 172.21.192.1 -p 5432 \
-U magangverify -d magang_verify -W \
-v ON_ERROR_STOP=1 \
-f database/migrations/002_create_table_mahasiswa.sql
```

Jika berhasil, catat:

```bash
psql -h 172.21.192.1 -p 5432 \
-U magangverify -d magang_verify -W \
-c "INSERT INTO schema_migrations (migration) VALUES ('002_create_table_mahasiswa.sql') ON CONFLICT (migration) DO NOTHING;"
```

Ganti nama file sesuai migration yang dijalankan.

## 7. Memeriksa Riwayat Migration

```bash
psql -h 172.21.192.1 -p 5432 \
-U magangverify -d magang_verify -W \
-c "SELECT migration, executed_at FROM schema_migrations ORDER BY id;"
```

Tabel `schema_migrations` hanya mencatat migration yang telah diterapkan. Dalam alur kerja saat ini, file SQL tetap dijalankan secara manual.

## 8. Setelah Migration Berhasil

1. Perbarui `database/schema.sql`.
2. Pastikan migration dan snapshot masuk ke Git.
3. Commit perubahan.

Contoh:

```bash
git add database/migrations database/schema.sql
git commit -m "Add users table migration"
```

## 9. Jika Terjadi Error Koneksi

Periksa:

* PostgreSQL di Windows sedang berjalan.
* Host dan port benar.
* Database dan username benar.
* Pengaturan akses PostgreSQL mengizinkan koneksi dari WSL.
* Firewall Windows tidak memblokir koneksi.

Jangan mencatat migration sebagai berhasil jika SQL gagal dijalankan.
