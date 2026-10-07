# Database Logbook

Migration utama: `database/migrations/020_restructure_logbook_workflow.sql`.

Migration ini tidak menghapus tabel logbook lama. `logbook_mingguan` tetap dipakai sebagai parent dan `logbook_revisi`/`logbook_tanda_tangan` lama dipertahankan untuk kompatibilitas. Data harian baru menggunakan `logbook_harian`.

Relasi baru:

```text
penempatan_magang.id
    ↓
logbook_mingguan.penempatan_id
    ↓
logbook_harian.logbook_id
```

Signature mingguan berada pada `logbook_mingguan` agar satu minggu memiliki satu signature mahasiswa, satu signature mitra, dan satu signature dosen untuk snapshot data yang sama.
