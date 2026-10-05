
<?php

require_once __DIR__ . '/../../config/database.php';

$users = [
    [
        'login_id' => '123',
        'name' => 'Mahasiswa',
        'password' => 'mahasiswa123',
        'role' => 'mahasiswa',
    ],
    [
        'login_id' => '1234',
        'name' => 'Dosen',
        'password' => 'dosen123',
        'role' => 'dosen',
    ],
    [
        'login_id' => '12345',
        'name' => 'Koordinator Magang',
        'password' => 'koordinator123',
        'role' => 'koordinator_magang',
    ],
    [
        'login_id' => '123456',
        'name' => 'Tendik',
        'password' => 'tendik123',
        'role' => 'tendik',
    ],
    [
        'login_id' => 'MITRA-001',
        'name' => 'Mitra',
        'password' => null,
        'role' => 'mitra',
    ],
    [
        'login_id' => '321',
        'name' => 'nonaktif',
        'password' => 'nonaktif123',
        'role' => 'mahasiswa',
        'is_active' => false,
    ],
];

$sql = "
    INSERT INTO users (
        login_id,
        name,
        password,
        role,
        is_active
    ) VALUES (
        :login_id,
        :name,
        :password,
        :role,
        :is_active
    )
    ON CONFLICT (login_id) DO NOTHING
";

$stmt = $pdo->prepare($sql);

$pdo->beginTransaction();

try {
    foreach ($users as $user) {
        $passwordHash = $user['password'] !== null
            ? password_hash($user['password'], PASSWORD_DEFAULT)
            : null;

        $stmt->execute([
            'login_id' => $user['login_id'],
            'name' => $user['name'],
            'password' => $passwordHash,
            'role' => $user['role'],
            'is_active' => ($user['is_active'] ?? true) ? 'true' : 'false',
        ]);
    }

    $pdo->commit();

    echo "Seeder akun testing berhasil dijalankan.\n";
} catch (Throwable $e) {
    $pdo->rollBack();

    echo "Seeder gagal: " . $e->getMessage() . "\n";
    exit(1);
}
