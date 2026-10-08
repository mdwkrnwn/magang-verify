# Authorization dan Security

Frontend bukan security boundary.

## Mahasiswa
Edit harus memverifikasi:
1. session;
2. role mahasiswa;
3. ownership logbook;
4. relasi ke penempatan;
5. Mitra belum sign.

## Mitra
Sign harus memverifikasi:
1. role mitra;
2. mitra sesuai penempatan;
3. mahasiswa sudah sign;
4. mitra belum sign.

## Dosen
Sign harus memverifikasi:
1. role dosen;
2. dosen adalah pembimbing;
3. mahasiswa sign;
4. mitra sign;
5. dosen belum sign.

## Tendik/Koordinator
Validasi hanya untuk record yang berada dalam kewenangan actor.

## CSRF
Semua mutation menggunakan helper CSRF existing.

## IDOR
Jangan update berdasarkan ID saja. Verifikasi rantai:
`logbook -> minggu -> penempatan -> mahasiswa/mitra/dosen -> session`.

## Transaction
Gunakan transaction untuk signature, invalidation, status, validasi, dan perubahan multi-record.

## Upload
Bukti ketidakhadiran:
- validasi MIME;
- ukuran;
- nama random;
- jangan percaya extension;
- storage sesuai project;
- akses melalui endpoint authorized bila private.
