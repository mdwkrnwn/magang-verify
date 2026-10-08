# Digital Signature

## Mekanisme

Tanda tangan dibuat langsung di platform menggunakan HTML Canvas. Browser mengirim PNG data URL melalui POST ke endpoint tanda tangan sesuai role.

File tanda tangan disimpan privat di:

```text
storage/logbook/signatures/
```

File tidak boleh diakses langsung melalui `/storage/logbook/...`. Akses dilakukan melalui endpoint terotorisasi:

```text
/dashboard/logbook/file/signature/{id}
```

## Penyimpanan database

Signature dicatat pada tabel `logbook_tanda_tangan` dan terikat pada `logbook_revisi` versi yang sedang aktif. Data penting yang digunakan antara lain:

- `revisi_id` — versi logbook yang ditandatangani
- `tahap` — `mahasiswa`, `mitra`, atau `dosen`
- `penanda_tangan_id` — user yang menandatangani
- `signature_path` — path privat file PNG
- `signature_hash` — SHA-256 file signature
- `snapshot_hash` — hash isi logbook yang ditandatangani
- `metadata` — metadata audit signature

## Urutan

```text
Mahasiswa
   ↓
Mitra
   ↓
Dosen Pembimbing
```

Backend memvalidasi urutan tersebut. UI hanya merupakan representasi dari status backend.

## Edit setelah mahasiswa menandatangani

Mahasiswa masih dapat mengedit selama mitra belum menandatangani. Ketika isi logbook berubah, sistem membuat versi/revisi baru dan signature versi sebelumnya tidak menjadi signature aktif. Mahasiswa wajib menandatangani ulang sebelum mitra dapat menandatangani.

Setelah mitra menandatangani, logbook dikunci untuk perubahan mahasiswa.

## Tampilan signature

Signature ditampilkan melalui endpoint file privat setelah authorization. Ini menjaga agar file signature tidak menjadi aset publik. Endpoint mengirim PNG dengan `Content-Type: image/png` dan `Content-Disposition: inline`.
