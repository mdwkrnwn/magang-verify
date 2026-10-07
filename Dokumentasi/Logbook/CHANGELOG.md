# Perubahan Logbook Workflow

## Ditambahkan

- `database/migrations/020_restructure_logbook_workflow.sql`
- `logbook_harian` untuk satu record per tanggal.
- Metadata digital signature mahasiswa/mitra/dosen pada `logbook_mingguan`.
- Trigger database untuk mencegah tanggal harian di luar minggu dan tanggal masa depan.
- `controllers/dashboard/mitra/LogbookController.php`
- `controllers/dashboard/dosen/LogbookController.php`
- `controllers/dashboard/tendik/LogbookController.php`
- `controllers/dashboard/koordinatorMagang/LogbookController.php`
- `pages/dashboard/mahasiswa/logbook/week.php`
- `pages/dashboard/mahasiswa/logbook/day-form.php`
- halaman logbook mitra, dosen, tendik, dan koordinator.
- `assets/js/logbook-signature.js`
- `services/LogbookPdfService.php`
- dokumentasi workflow, database, authorization, signature, PDF, dan testing.

## Diubah

- `models/Logbook.php` direstrukturisasi dari logbook mingguan berbasis revisi menjadi minggu + aktivitas harian + signature berjenjang.
- `controllers/dashboard/mahasiswa/LogbookController.php` menangani pembuatan minggu, harian, edit, signature, upload bukti, dan download PDF.
- `routes/web.php` menambahkan endpoint seluruh role.
- Sidebar dosen, mitra, tendik, dan koordinator diberi akses Logbook/Validasi Logbook.
- `SeedDimasLogbook.php` otomatis membuat `profil_dosen` untuk `dosen_seed` bila belum ada.
- `database/schema.sql` diselaraskan dengan workflow baru.
- `Dokumentasi/migration.md` ditambah panduan migration 020.

## Dihapus dari UI aktif

Component logbook mahasiswa lama (`Calendar.php`, `LogbookContent.php`, `MainContent.php`, `PageHeader.php`, `SummaryCards.php`, `WeeklyLogbook.php`) tidak lagi digunakan karena flow UI baru menggunakan halaman minggu dan aktivitas harian. Tabel legacy `logbook_revisi` dan `logbook_tanda_tangan` tidak dihapus dari database untuk menjaga kompatibilitas.

## Validasi yang sudah dilakukan

- Seluruh file PHP pada project lulus `php -l`.
- Dompdf tersedia melalui `vendor/autoload.php`.
- SQL migration telah ditulis dan diselaraskan dengan `schema.sql`.
- Koneksi PostgreSQL tidak diuji dari environment pengerjaan karena extension PostgreSQL tidak tersedia di container dan `.env` project tidak ikut dalam ZIP. Migration perlu dijalankan di database development sebelum testing browser.
