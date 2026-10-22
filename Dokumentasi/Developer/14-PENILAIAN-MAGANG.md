# Penilaian Magang

## Scope yang diimplementasikan

- Route Dosen: `/dashboard/dosen/monitoring` (menu Monitoring & Penilaian).
- Route Mitra: `/dashboard/mitra/penilaian`.
- Model: `models/PenilaianMagang.php`; tabel `penilaian_magang` existing digunakan, tidak ada perubahan SQL.
- Dosen hanya dapat menilai penempatan yang menunjuk ke `profil_dosen` miliknya; Mitra hanya penempatan yang formasinya dimiliki perusahaan tersebut.
- Penilaian tersedia saat penempatan berstatus `menunggu_penilaian` atau `selesai`.
- Lima aspek disimpan sebagai angka 0–100. `nilai_akhir` boleh diisi manual, namun aplikasi tidak menghitung formula/bobot. Draft dapat disimpan; submit mengubah status ke `diajukan`, memberi waktu submit, aktivitas dan notifikasi mahasiswa.
- Penilaian yang telah diajukan terkunci di model; status `perlu_revisi` dapat diedit bila proses eksternal mengembalikan record pada status tersebut.

## Batasan

Schema tidak menentukan bobot nilai, cara menghitung nilai akhir, reviewer lanjutan, syarat kelulusan, atau kaitan eksplisit dengan `verifikasi_penyelesaian_magang`. Karena itu aplikasi tidak menghitung nilai dan tidak otomatis menyatakan mahasiswa lulus. Workflow pemeriksaan lanjutan dan penyelesaian tetap memerlukan definisi bisnis yang didukung schema.

## Test

Jalankan `php tests/smoke_views.php` untuk pemeriksaan render berbasis fixture. Pengujian query/ownership terhadap database dan browser belum dilakukan. Jangan menilai status lulus dari angka penilaian sebelum formula/ambang disepakati.
