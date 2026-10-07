# Business Rules Logbook

## BR-01 Penempatan
Logbook hanya dapat dibuat untuk penempatan mahasiswa yang valid.

## BR-02 Minggu pertama
Jika mulai Rabu 7 Oktober:
- Minggu 1 = 7–11 Oktober
- Minggu 2 = 12–18 Oktober

## BR-03 Masa depan
Minggu dan tanggal aktivitas yang masih masa depan tidak boleh dibuat.

## BR-04 Late entry
Minggu/tanggal yang sudah lewat tetapi belum dibuat tetap boleh dibuat.

## BR-05 Harian
Satu record mewakili satu tanggal. Tidak boleh membuat satu record untuk satu minggu sekaligus.

## BR-06 Weekend
Sabtu/Minggu tetap dapat diisi karena hari libur perusahaan tidak boleh diasumsikan.

## BR-07 Kelengkapan
Sebelum mahasiswa sign, semua tanggal yang diwajibkan harus memiliki record. Jika tidak hadir, mahasiswa mengisi alasan/aktivitas dan bukti bila dibutuhkan.

## BR-08 Signature mahasiswa
Minggu harus memenuhi kelengkapan dan validasi sebelum sign.

## BR-09 Signature mitra
Mitra hanya dapat sign setelah mahasiswa sign.

## BR-10 Signature dosen
Dosen hanya dapat sign setelah mahasiswa dan Mitra sign.

## BR-11 Edit
Sebelum Mitra sign: boleh edit.

Setelah Mitra sign: tidak boleh edit.

## BR-12 Re-sign
Jika mahasiswa sudah sign lalu mengedit:
- signature mahasiswa lama dibatalkan;
- muncul keterangan wajib tanda tangan ulang;
- Mitra belum dapat sign sampai mahasiswa sign ulang.

## BR-13 Lock
Setelah Mitra sign, backend menolak mutation.

## BR-14 No skip
Tidak boleh langsung Mahasiswa -> Dosen. Mitra wajib lebih dahulu.

## BR-15 Histori
Penempatan lama tidak boleh dihapus saat penempatan baru dibuat.

## BR-16 Authorization
Mahasiswa hanya dapat mengubah miliknya. Mitra hanya milik penempatan perusahaannya. Dosen hanya mahasiswa bimbingannya. Tendik/Koordinator sesuai kewenangan.

## BR-17 Transaction
Signature, invalidation, status, dan perubahan terkait harus transactional.

## BR-18 Activity
Activity dicatat setelah operasi bisnis berhasil.
