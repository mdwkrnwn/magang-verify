# Database Logbook

## Relasi
`penempatan_magang -> logbook_mingguan -> logbook_harian`

Signature merupakan workflow pada level minggu.

## logbook_mingguan
Konsep field:
- id
- penempatan_id
- minggu_ke
- tanggal_mulai
- tanggal_selesai
- status/workflow
- signature mahasiswa
- signature mitra
- signature dosen
- audit timestamps

Rule:
- minggu unik dalam satu penempatan;
- tidak overlap;
- minggu pertama mengikuti tanggal mulai penempatan sampai Minggu;
- minggu berikutnya Senin-Minggu.

## logbook_harian
Konsep field:
- id
- minggu_id
- tanggal
- jam_masuk
- jam_pulang
- kegiatan
- data/bukti ketidakhadiran bila diperlukan
- timestamps

Constraint:
- satu tanggal satu record dalam satu minggu;
- tanggal harus berada di range minggu;
- tanggal masa depan tidak boleh dibuat.

## Signature
Simpan minimal:
- minggu_id
- signer user id
- role
- signature payload
- signed_at
- referensi versi/data yang ditandatangani bila digunakan.

## Lock
Sebelum Mitra sign: mahasiswa boleh edit.

Setelah Mitra sign: mutation ditolak backend.

## Histori
Penempatan lama dan logbook-nya tetap tersimpan ketika mahasiswa memiliki penempatan baru.

> Struktur final wajib disesuaikan dengan migration existing; jangan menghapus migration yang sudah tercatat.
