<?php
declare(strict_types=1);
require_once __DIR__ . '/_SeedHelper.php';

$pdo = seedDb();
$pdo->beginTransaction();

try {
    $mitraUserId = seedUserId('mitra_seed');

    $check = $pdo->prepare('SELECT id FROM mitra WHERE kode_perusahaan = :kode LIMIT 1');
    $check->execute(['kode' => 'MITRA-SEED-001']);
    $mitraId = $check->fetchColumn();

    if ($mitraId === false) {
        $mitraId = seedInsert('mitra', [
            'user_id' => $mitraUserId,
            'nama_perusahaan' => 'PT Teknologi Nusantara',
            'kode_perusahaan' => 'MITRA-SEED-001',
            'kategori' => 'Perusahaan Swasta',
            'bidang_usaha' => 'Software Development',
            'deskripsi' => 'Mitra seed untuk pengujian proses magang.',
            'email' => 'mitra@example.test',
            'no_telepon' => '081299990001',
            'website' => 'https://example.test',
            'provinsi' => 'Jawa Timur',
            'kota' => 'Malang',
            'alamat' => 'Jl. Teknologi No. 1, Malang',
            'status_verifikasi' => 'terverifikasi',
            'is_active' => true,
        ]);
        seedLog("Mitra dibuat (#{$mitraId}).");
    } else {
        $mitraId = (int) $mitraId;
        seedLog("Mitra sudah ada (#{$mitraId}).");
    }

    $forms = [
        ['MAG-SEED-ANDI', 'Software Engineer Intern', 'Backend Development', 'Malang', 'onsite', '2026-01-05', '2026-04-30'],
        ['MAG-SEED-BUDI', 'Web Developer Intern', 'Web Development', 'Malang', 'hybrid', '2026-10-15', '2027-01-15'],
    ];

    foreach ($forms as [$kode, $judul, $bidang, $lokasi, $sistem, $mulai, $selesai]) {
        $check = $pdo->prepare('SELECT id FROM formasi_magang WHERE judul = :judul AND mitra_id = :mitra_id LIMIT 1');
        $check->execute(['judul' => $judul, 'mitra_id' => $mitraId]);
        if ($check->fetchColumn() !== false) {
            seedLog("Formasi {$judul} sudah ada.");
            continue;
        }

        seedInsert('formasi_magang', [
            'mitra_id' => $mitraId,
            'judul' => $judul,
            'deskripsi' => 'Formasi magang seed untuk pengujian alur pendaftaran.',
            'bidang' => $bidang,
            'jumlah_kuota' => 3,
            'jumlah_diterima' => 1,
            'persyaratan' => 'Mahasiswa aktif dan memiliki kemampuan dasar pemrograman.',
            'lokasi_magang' => $lokasi,
            'sistem_kerja' => $sistem,
            'tanggal_mulai' => $mulai,
            'tanggal_selesai' => $selesai,
            'tahun_akademik' => '2026/2027',
            'status' => 'dibuka',
            'dibuat_oleh' => seedUserId('mitra_seed'),
        ]);

        seedLog("Formasi {$judul} dibuat.");
    }

    $pdo->commit();
    echo "Seeder mitra dan formasi selesai." . PHP_EOL;
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    throw $e;
}
