<?php
declare(strict_types=1);
require_once __DIR__ . '/_SeedHelper.php';

$pdo = seedDb();
$pdo->beginTransaction();

try {
    $data = [
        ['mhs_andi', '23410001', 'Teknik Informatika', 2024, 'andi@example.test', '081234567801', 'Malang', 'andi-pratama.jpg'],
        ['mhs_budi', '23410002', 'Teknik Informatika', 2024, 'budi@example.test', '081234567802', 'Malang', 'budi-santoso.jpg'],
        ['mhs_citra', '23410003', 'Sistem Informasi Bisnis', 2025, 'citra@example.test', '081234567803', 'Malang', 'citra-lestari.jpg'],
        ['mhs_dimas', '23410004', 'Teknik Informatika', 2025, 'dimas@example.test', '081234567804', 'Batu', 'dimas-saputra.jpg'],
        ['mhs_eka', '23410005', 'Sistem Informasi Bisnis', 2025, 'eka@example.test', '081234567805', 'Malang', 'eka-putri.jpg'],
    ];

    foreach ($data as [$loginId, $nim, $prodi, $angkatan, $email, $phone, $alamat, $foto]) {
        $userId = seedUserId($loginId);

        $check = $pdo->prepare('SELECT id FROM profil_mahasiswa WHERE user_id = :user_id OR nim = :nim LIMIT 1');
        $check->execute(['user_id' => $userId, 'nim' => $nim]);

        if ($check->fetchColumn() !== false) {
            seedLog("Profil {$nim} sudah ada.");
            continue;
        }

        seedInsert('profil_mahasiswa', [
            'user_id' => $userId,
            'nim' => $nim,
            'program_studi' => $prodi,
            'angkatan' => $angkatan,
            'email' => $email,
            'no_telepon' => $phone,
            'alamat' => $alamat,
            'foto_path' => 'uploads/mahasiswa/' . $foto,
            'github_url' => 'https://github.com/' . str_replace('_', '-', $loginId),
            'linkedin_url' => 'https://www.linkedin.com/in/' . str_replace('_', '-', $loginId),
            'portfolio_url' => 'https://' . str_replace('_', '-', $loginId) . '.dev',
        ]);

        seedLog("Profil {$nim} dibuat.");
    }

    $pdo->commit();
    echo "Seeder profil mahasiswa selesai." . PHP_EOL;
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    throw $e;
}
