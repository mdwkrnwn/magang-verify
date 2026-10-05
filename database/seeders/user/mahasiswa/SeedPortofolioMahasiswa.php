<?php
declare(strict_types=1);
require_once __DIR__ . '/_SeedHelper.php';

$pdo = seedDb();
$pdo->beginTransaction();

try {
    $items = [
        ['23410001', 'Sistem Informasi Akademik Modern', 'terverifikasi', 'publik', 'Membangun sistem informasi akademik berbasis web untuk pengelolaan data mahasiswa.'],
        ['23410002', 'Aplikasi POS UMKM', 'terverifikasi', 'publik', 'Aplikasi kasir dan laporan penjualan untuk UMKM.'],
        ['23410003', 'Platform Edukasi Digital', 'belum_diverifikasi', 'draft', 'Platform pembelajaran digital untuk mahasiswa.'],
        ['23410004', 'Dashboard Analitik Kampus', 'perlu_perbaikan', 'draft', 'Dashboard visualisasi data akademik dan aktivitas mahasiswa.'],
        ['23410005', 'Aplikasi Mobile Event', 'ditolak', 'draft', 'Aplikasi mobile untuk pengelolaan acara kampus.'],
    ];

    foreach ($items as [$nim, $judul, $verifikasi, $publikasi, $deskripsi]) {
        $mahasiswaId = seedMahasiswaId($nim);
        $slug = seedSlug($nim . '-' . $judul);

        if (seedExists('SELECT 1 FROM portofolios WHERE slug = :slug LIMIT 1', ['slug' => $slug])) {
            seedLog("Portofolio {$judul} sudah ada.");
            continue;
        }

        seedInsert('portofolios', [
            'mahasiswa_id' => $mahasiswaId,
            'judul' => $judul,
            'slug' => $slug,
            'jenis' => 'proyek',
            'deskripsi' => $deskripsi,
            'tanggal_perolehan' => '2026-01-15',
            'penyelenggara' => 'Politeknik Negeri Malang',
            'tautan' => 'https://github.com/example/' . $slug,
            'gambar_sampul' => null,
            'status_verifikasi' => $verifikasi,
            'status_publikasi' => $publikasi,
            'diverifikasi_oleh' => in_array($verifikasi, ['terverifikasi', 'ditolak'], true) ? seedUserId('dosen_seed') : null,
            'diverifikasi_pada' => in_array($verifikasi, ['terverifikasi', 'ditolak'], true) ? '2026-09-20 10:00:00+07' : null,
        ]);

        seedLog("Portofolio {$nim}: {$verifikasi} / {$publikasi}.");
    }

    $pdo->commit();
    echo "Seeder portofolio selesai." . PHP_EOL;
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    throw $e;
}
