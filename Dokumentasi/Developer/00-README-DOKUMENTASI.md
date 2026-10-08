# Dokumentasi Developer MagangVerify

Dokumentasi ini dipisahkan per domain agar developer dapat mengimplementasikan fitur tanpa harus membaca satu PRD besar.

## Dokumen
- `01-ROLE-DAN-SIDEBAR.md` — role, sidebar, hak akses.
- `02-MENU-MAHASISWA.md` — isi menu mahasiswa.
- `03-MENU-MITRA.md` — isi menu mitra.
- `04-MENU-DOSEN.md` — isi menu dosen.
- `05-MENU-TENDIK-DAN-KOORDINATOR.md` — fungsi Tendik/Koordinator.
- `06-DATABASE-LOGBOOK.md` — rancangan database.
- `07-BUSINESS-RULES-LOGBOOK.md` — business rules developer.
- `08-DIGITAL-SIGNATURE.md` — tanda tangan digital.
- `09-PDF-DAN-REKAP-LOGBOOK.md` — PDF dan rekap.
- `10-AUTHORIZATION-SECURITY.md` — security dan authorization.
- `11-ACTIVITY-AUDIT-LOG.md` — activity/audit.
- `12-SEED-TESTING-ACCOUNTS.md` — akun dan skenario testing.

## Prinsip
- Ikuti struktur folder project existing.
- Jangan membuat tabel/route/controller duplikat tanpa audit.
- Business rule wajib divalidasi backend.
- UI bukan security boundary.
- Histori penempatan tidak boleh hilang.
- Signature dilakukan di platform.
- Edit setelah mahasiswa sign menyebabkan signature mahasiswa harus diulang.
- Setelah mitra sign, minggu terkunci.
