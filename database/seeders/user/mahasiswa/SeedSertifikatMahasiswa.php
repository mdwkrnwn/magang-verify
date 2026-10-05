<?php
declare(strict_types=1);
require_once __DIR__ . '/_SeedHelper.php';

$pdo = seedDb();
$pdo->beginTransaction();

try {
    $items = [
        ['mhs_andi', 'AWS Cloud Practitioner', 'Amazon Web Services', 'terverifikasi'],
        ['mhs_budi', 'Belajar Dasar Pemrograman Web', 'Dicoding Indonesia', 'terverifikasi'],
        ['mhs_citra', 'UI/UX Design Fundamentals', 'Dicoding Indonesia', 'belum_terverifikasi'],
        ['mhs_dimas', 'Data Analytics Fundamentals', 'Google', 'dalam_peninjauan'],
        ['mhs_eka', 'Cyber Security Fundamentals', 'Cisco Networking Academy', 'ditolak'],
    ];

    foreach ($items as [$loginId, $nama, $penerbit, $status]) {
        $userId = seedUserId($loginId);
        $slug = seedSlug($loginId . '-' . $nama);

        if (seedExists('SELECT 1 FROM sertifikat WHERE slug = :slug LIMIT 1', ['slug' => $slug])) {
            seedLog("Sertifikat {$nama} sudah ada.");
            continue;
        }

        seedInsert('sertifikat', [
            'user_id' => $userId,
            'nama' => $nama,
            'slug' => $slug,
            'penerbit' => $penerbit,
            'tanggal_terbit' => '2026-02-15',
            'nomor_sertifikat' => 'CERT-SEED-' . strtoupper(substr($loginId, 4)),
            'deskripsi' => 'Data sertifikat seed untuk pengujian profil mahasiswa.',
            'tautan' => 'https://example.test/sertifikat/' . $slug,
            'file_path' => 'uploads/sertifikat/seed/' . $slug . '.pdf',
            'status_verifikasi' => $status,
            'catatan_verifikasi' => $status === 'ditolak' ? 'Dokumen perlu diperbaiki.' : null,
            'diverifikasi_oleh' => in_array($status, ['terverifikasi', 'ditolak'], true) ? seedUserId('dosen_seed') : null,
            'diverifikasi_pada' => in_array($status, ['terverifikasi', 'ditolak'], true) ? '2026-09-20 10:30:00+07' : null,
        ]);

        seedLog("Sertifikat {$loginId}: {$status}.");
    }

    $pdo->commit();
    echo "Seeder sertifikat selesai." . PHP_EOL;
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    throw $e;
}
