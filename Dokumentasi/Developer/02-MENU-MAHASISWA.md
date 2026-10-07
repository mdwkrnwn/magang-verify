# Menu Mahasiswa

## Dashboard
Menampilkan profil, portofolio, sertifikat, pengajuan, progress magang, aktivitas terbaru, quick action, dan status penempatan aktif. Data berasal dari database.

## Profil
- data profil
- foto
- CV
- informasi akademik

## Portofolio
- create/edit/delete sesuai status
- status verifikasi
- publikasi

## Sertifikat
- create/edit
- upload
- status verifikasi
- catatan verifikasi

## Pengalaman
Jenis: magang, pekerjaan, organisasi, freelance, proyek, lainnya. Pengalaman magang dapat dibuat otomatis dari penempatan selesai sesuai business rule.

## Formasi Magang
- daftar/filter
- detail
- kuota
- periode
- persyaratan
- lokasi
- sistem kerja
- lamaran

## Pengajuan Saya
Menampilkan formasi, status dosen, status mitra, status pendaftaran, dokumen, dan riwayat.

## Logbook
Struktur:
`Penempatan -> Minggu -> Hari -> Sign Mahasiswa -> Sign Mitra -> Sign Dosen`.

Mahasiswa dapat edit sebelum Mitra sign. Jika edit setelah mahasiswa sign, signature mahasiswa dibatalkan dan wajib sign ulang. Setelah Mitra sign, minggu terkunci.

## Pengaturan
Keamanan/password, tampilan/theme, dan preferensi yang tersedia.
