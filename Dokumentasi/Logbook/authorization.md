# Authorization Logbook

| Role | Akses |
|---|---|
| Mahasiswa | Membuat minggu, membuat/mengedit aktivitas harian sebelum mitra tanda tangan, tanda tangan minggu, download histori penempatan |
| Mitra | Melihat minggu mahasiswa pada mitranya setelah mahasiswa tanda tangan, tanda tangan mitra |
| Dosen | Melihat minggu mahasiswa yang penempatannya menggunakan profil dosen tersebut setelah mitra tanda tangan, tanda tangan dosen |
| Tendik | Melihat bukti ketidakhadiran yang menunggu validasi dan memberi keputusan |
| Koordinator Magang | Melihat bukti ketidakhadiran yang menunggu validasi dan memberi keputusan |

Seluruh operasi POST menggunakan CSRF. Kepemilikan relasi diverifikasi di server.
