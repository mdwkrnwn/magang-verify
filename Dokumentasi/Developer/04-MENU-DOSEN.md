# Menu Dosen

## Dashboard
Mahasiswa bimbingan, penempatan, logbook yang perlu tindakan, dan monitoring.

## Mahasiswa Bimbingan
Dosen hanya dapat mengakses mahasiswa yang menjadi bimbingannya.

## Permintaan Dosen Pembimbing
Dosen dapat melihat dan memproses permintaan yang secara khusus ditujukan kepadanya. Persetujuan menetapkan `penempatan_magang.dosen_pembimbing_id` menggunakan `profil_dosen.id`.

## Pengajuan Magang
Persetujuan mahasiswa sesuai workflow pendaftaran.

Schema pengajuan tidak memiliki dosen reviewer yang ditetapkan sebelum persetujuan (`dosen_penyetuju_id` baru dicatat saat diproses). Karena itu, antrean permohonan menunggu saat ini tersedia bagi role dosen; jika kebijakan kampus mengharuskan pengajuan diarahkan ke dosen tertentu, perlu ada keputusan/rancangan penugasan yang jelas.

## Logbook
Dosen baru dapat review/sign setelah:
1. Mahasiswa sign.
2. Mitra sign.

Dosen melakukan signature langsung di platform. Setelah sign, histori signature tersimpan dan workflow selesai pada tahap signature.

## Monitoring/Penilaian
Monitoring penempatan dan mahasiswa sesuai kewenangan.
