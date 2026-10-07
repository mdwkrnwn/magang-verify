# Activity dan Audit Log

Gunakan tabel `aktivitas_pengguna` existing. Jangan membuat tabel activity kedua tanpa alasan arsitektural.

## Mahasiswa
- create minggu
- tambah/edit/hapus harian
- sign
- sign ulang

## Mitra
- sign minggu

## Dosen
- sign minggu

## Tendik/Koordinator
- validasi/menolak validasi ketidakhadiran

## Rule
Activity sukses dibuat setelah mutation berhasil. Jika transaction rollback, jangan ada activity sukses.

Jika schema mendukung reference, gunakan table/id logbook yang relevan.

Login/logout boleh diaudit tetapi tidak perlu dimasukkan ke feed `Aktivitas Terbaru` bisnis mahasiswa.
