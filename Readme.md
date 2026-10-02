# MagangVerify

Aplikasi web MagangVerify menggunakan PHP Native.

## Menjalankan di Windows dengan Laragon

### 1. Letakkan project

Copy folder:

```text
magang-verify
```

ke:

```text
C:\laragon\www\
```

Sehingga menjadi:

```text
C:\laragon\www\magang-verify
```

### 2. Jalankan Laragon

Buka **Laragon**, kemudian klik:

```text
Start All
```

Pastikan Apache berjalan.

### 3. Buka project

Akses melalui browser:

```text
http://localhost/magang-verify/
```

Jika menggunakan **Auto Virtual Hosts** Laragon, project juga dapat diakses melalui:

```text
http://magang-verify.test
```

---

## Menjalankan di WSL

### 1. Masuk ke folder project

```bash
cd /home/valencza/projects/magang-verify
```

### 2. Jalankan PHP Built-in Server

```bash
php -S localhost:8000 router.php
```

### 3. Buka di browser

```text
http://localhost:8000/magang-verify/
```

Untuk menghentikan server:

```text
Ctrl + C
```

### 4. Cek PHP

Pastikan PHP sudah terinstall:

```bash
php -v
```

---

## Konfigurasi

Konfigurasi URL aplikasi berada di:

```text
config/app.php
```

Saat ini:

```php
define('APP_URL', '/magang-verify');
```

Gunakan konfigurasi tersebut baik saat menjalankan project melalui **Laragon** maupun **WSL**.

## Dokumentasi Migration

* [Panduan Migration](Dokumentas/migration.md)
