# Panduan Database Migration — VerifyMagang

## 1. Tujuan

Dokumentasi ini menjelaskan standar pengelolaan perubahan struktur database pada proyek VerifyMagang.

Setiap anggota tim wajib mengikuti aturan migration agar struktur database tetap konsisten meskipun menggunakan lingkungan pengembangan yang berbeda, seperti Windows dan WSL Ubuntu.

## 2. Struktur Folder

```text
database/
├── schema.sql
├── migrations/
│   ├── 000_create_schema_migrations.sql
│   ├── 001_create_table_users.sql
│   └── ...
└── seeders/
    └── ...
```

Keterangan:

| Folder/File   | Fungsi                                                  |
| ------------- | ------------------------------------------------------- |
| `schema.sql`  | Snapshot struktur database terbaru.                     |
| `migrations/` | Menyimpan perubahan struktur database secara berurutan. |
| `seeders/`    | Menyimpan data awal atau data pengujian.                |

## 3. Apa Itu Migration?

Migration adalah file SQL yang mencatat satu perubahan struktur database.

Contohnya:

* Membuat tabel baru.
* Menambahkan kolom.
* Menambahkan foreign key.
* Membuat index.
* Mengubah constraint atau struktur kolom.

Setiap perubahan struktur database harus memiliki migration baru. Hindari mengubah struktur database secara manual tanpa membuat file migration yang dapat dibagikan melalui Git.

## 4. Aturan Penamaan

Gunakan format:

`[nomor]_[aksi]_[objek].sql`

Contoh:

```text
000_create_schema_migrations.sql
001_create_table_users.sql
002_create_table_mahasiswa.sql
003_create_table_mitra.sql
004_add_column_status_to_pendaftaran_magang.sql
```

Ketentuan:

1. Gunakan nomor urut tiga digit.
2. Gunakan huruf kecil dan underscore.
3. Nama file harus menjelaskan perubahan yang dilakukan.
4. Jangan menggunakan nomor yang sama untuk dua migration berbeda.
5. Jangan mengubah atau menghapus migration lama yang sudah dibagikan atau diterapkan. Buat migration baru untuk perubahan berikutnya.

Sebelum membuat migration, tarik perubahan terbaru dari Git dan periksa nomor terakhir yang digunakan.

## 5. Alur Kerja Migration

Setiap migration harus mengikuti urutan berikut:

1. Buat file SQL baru di `database/migrations/`.
2. Tulis SQL sesuai perubahan yang dibutuhkan.
3. Jalankan migration pada database pengembangan.
4. Pastikan SQL berhasil dijalankan.
5. Catat nama file ke tabel `schema_migrations`.
6. Periksa riwayat migration.
7. Perbarui `database/schema.sql`.
8. Commit perubahan ke Git.

**Jangan mencatat migration sebagai berhasil sebelum SQL selesai dijalankan tanpa error.**

## 6. Fungsi `schema_migrations`

Tabel `schema_migrations` menyimpan daftar migration yang telah diterapkan pada database.

Untuk memeriksa riwayat:

```sql
SELECT migration, executed_at
FROM schema_migrations
ORDER BY id;
```

Tabel ini hanya menyimpan riwayat. Pada alur kerja saat ini, tabel tersebut tidak otomatis menjalankan file migration.

## 7. Perbedaan Migration dan Seeder

**Migration** digunakan untuk mengubah struktur database, misalnya membuat tabel atau menambah kolom.

**Seeder** digunakan untuk memasukkan data awal atau data pengujian.

Data operasional yang dibuat pengguna melalui aplikasi harus dikelola oleh kode aplikasi, bukan dimasukkan ke migration.

Jangan menyimpan password pengguna dalam bentuk plaintext pada seeder.

## 8. Fungsi `schema.sql`

File `database/schema.sql` berisi gambaran lengkap struktur database terbaru.

Setelah migration berhasil, perbarui snapshot ini agar mencerminkan struktur database yang berlaku.

Migration dan snapshot memiliki fungsi berbeda:

* `migrations/`: riwayat perubahan secara bertahap.
* `schema.sql`: struktur database secara keseluruhan.

Jangan menjalankan ulang `schema.sql` hanya untuk memperbarui dokumentasi.

## 9. Aturan Kerja Tim

* Gunakan nama database pengembangan yang telah disepakati, yaitu `magang_verify`.
* Gunakan nomor dan nama migration yang sama di seluruh tim.
* Setiap anggota menjalankan migration yang belum diterapkan pada database lokalnya.
* Jika menggunakan database yang sama, migration cukup diterapkan satu kali.
* Jangan mencatat migration yang gagal.
* Jangan mengubah migration lama yang sudah dibagikan.
* Jangan memasukkan password database ke repository.
* Setelah selesai, commit file migration dan `schema.sql` ke Git.

## 10. Checklist Sebelum Commit

* [ ] Nama dan nomor migration sesuai aturan.
* [ ] Tidak ada nomor migration yang bertabrakan.
* [ ] SQL berhasil dijalankan pada database pengembangan.
* [ ] Migration sudah tercatat di `schema_migrations`.
* [ ] `schema.sql` sudah diperbarui.
* [ ] Tidak ada password atau kredensial rahasia.
* [ ] Perubahan sudah di-commit ke Git.

## 11. Panduan Berdasarkan Lingkungan

Pilih panduan yang sesuai dengan lingkungan pengembangan:

* [Panduan Migration Windows](Migration/windows.md)
* [Panduan Migration WSL Ubuntu](Migration/wsl.md)
