<?php
declare(strict_types=1);
require_once __DIR__ . '/_SeedHelper.php';

$pdo = seedDb();
$pdo->beginTransaction();

try {
    $users = [
        ['mhs_andi', 'Andi Pratama', 'mahasiswa'],
        ['mhs_budi', 'Budi Santoso', 'mahasiswa'],
        ['mhs_citra', 'Citra Lestari', 'mahasiswa'],
        ['mhs_dimas', 'Dimas Saputra', 'mahasiswa'],
        ['mhs_eka', 'Eka Putri', 'mahasiswa'],
        ['dosen_seed', 'Dr. Rina Kartika', 'dosen'],
        ['mitra_seed', 'PT Teknologi Nusantara', 'mitra'],
    ];

    foreach ($users as [$loginId, $name, $role]) {
        $exists = seedDb()->prepare('SELECT id FROM users WHERE login_id = :login_id LIMIT 1');
        $exists->execute(['login_id' => $loginId]);
        $id = $exists->fetchColumn();

        if ($id !== false) {
            seedLog("User {$loginId} sudah ada (#{$id}).");
            continue;
        }

        $id = seedInsert('users', [
            'login_id' => $loginId,
            'name' => $name,
            'password' => password_hash('Password123!', PASSWORD_DEFAULT),
            'role' => $role,
            'is_active' => true,
        ]);

        seedLog("User {$loginId} dibuat (#{$id}).");
    }

    $pdo->commit();
    echo "Seeder users selesai." . PHP_EOL;
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    throw $e;
}
