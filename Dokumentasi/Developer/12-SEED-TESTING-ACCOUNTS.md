# Seed dan Testing Accounts

Password seed:
`Password123!`

## Mahasiswa aktif
```text
login_id: mhs_dimas
password: Password123!
role: mahasiswa
```

Dimas adalah test utama logbook.

## Dosen pembimbing Dimas
```text
login_id: dosen_seed
password: Password123!
user_id: 18
profil_dosen_id: 2
```

## Mitra
```text
login_id: mitra_seed
password: Password123!
kode: MITRA-SEED-001
nama: PT Teknologi Nusantara
```

## Mahasiswa selesai
```text
mhs_andi / Password123!
```

## Mahasiswa menunggu persetujuan
```text
mhs_budi / Password123!
```

## End-to-end
1. Login Dimas.
2. Buat minggu.
3. Coba tanggal masa depan -> harus ditolak.
4. Isi setiap tanggal.
5. Lengkapi minggu.
6. Sign mahasiswa.
7. Edit sebelum Mitra sign -> boleh.
8. Pastikan signature mahasiswa dibatalkan.
9. Sign ulang.
10. Login Mitra.
11. Sebelum mahasiswa sign -> tidak dapat sign.
12. Setelah mahasiswa sign -> dapat sign.
13. Sign Mitra.
14. Pastikan Dimas tidak dapat edit.
15. Login Dosen.
16. Sebelum Mitra sign -> tidak dapat sign.
17. Setelah Mitra sign -> dapat sign.
18. Sign Dosen.

## Security test
Uji:
- akses ID milik mahasiswa lain;
- edit setelah Mitra sign;
- Mitra sign sebelum mahasiswa;
- Dosen sign sebelum Mitra;
- manipulasi user_id/role dari POST;
- request tanpa CSRF;
- tanggal masa depan;
- duplicate tanggal;
- duplicate minggu.
