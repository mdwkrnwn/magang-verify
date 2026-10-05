
<?php

require_once __DIR__ . '/../../config/database.php';

$pdo->beginTransaction();

try {
    $users = [
        [
            'login_id' => 'mhs_test',
            'name' => 'Mahasiswa Pengujian',
            'password' => password_hash('MhsTest123!', PASSWORD_DEFAULT),
            'role' => 'mahasiswa',
        ],
        [
            'login_id' => 'dosen_test',
            'name' => 'Dosen Pengujian',
            'password' => password_hash('DosenTest123!', PASSWORD_DEFAULT),
            'role' => 'dosen',
        ],
        [
            'login_id' => 'koord_test',
            'name' => 'Koordinator Pengujian',
            'password' => password_hash('KoordTest123!', PASSWORD_DEFAULT),
            'role' => 'koordinator_magang',
        ],
        [
            'login_id' => 'tendik_test',
            'name' => 'Tendik Pengujian',
            'password' => password_hash('TendikTest123!', PASSWORD_DEFAULT),
            'role' => 'tendik',
        ],
        [
            'login_id' => 'mitra_test',
            'name' => 'Mitra Pengujian',
            'password' => null,
            'role' => 'mitra',
        ],
    ];

    $insertUser = $pdo->prepare(
        'INSERT INTO users (login_id, name, password, role)
         VALUES (:login_id, :name, :password, :role)
         RETURNING id'
    );

    $insertMahasiswa = $pdo->prepare(
        'INSERT INTO profil_mahasiswa
            (user_id, nim, program_studi, angkatan, email)
         VALUES
            (:user_id, :nim, :program_studi, :angkatan, :email)'
    );

    $insertDosen = $pdo->prepare(
        'INSERT INTO profil_dosen (user_id, nidn, email)
         VALUES (:user_id, :nidn, :email)'
    );

    $insertMitra = $pdo->prepare(
        'INSERT INTO mitra
            (user_id, nama_perusahaan, kode_perusahaan, kategori,
             bidang_usaha, email, kota, status_verifikasi)
         VALUES
            (:user_id, :nama_perusahaan, :kode_perusahaan, :kategori,
             :bidang_usaha, :email, :kota, :status_verifikasi)'
    );

    foreach ($users as $user) {
        $stmt = $pdo->prepare(
            'SELECT id FROM users WHERE login_id = :login_id'
        );
        $stmt->execute(['login_id' => $user['login_id']]);

        if ($stmt->fetch()) {
            throw new RuntimeException(
                'Login ID sudah ada: ' . $user['login_id']
            );
        }

        $insertUser->execute($user);
        $userId = $insertUser->fetchColumn();

        if ($user['role'] === 'mahasiswa') {
            $insertMahasiswa->execute([
                'user_id' => $userId,
                'nim' => 'TEST2026001',
                'program_studi' => 'D-IV Teknik Informatika',
                'angkatan' => 2026,
                'email' => 'mhs.test@example.com',
            ]);
        } elseif ($user['role'] === 'dosen') {
            $insertDosen->execute([
                'user_id' => $userId,
                'nidn' => 'TEST000001',
                'email' => 'dosen.test@example.com',
            ]);
        } elseif ($user['role'] === 'mitra') {
            $insertMitra->execute([
                'user_id' => $userId,
                'nama_perusahaan' => 'Perusahaan Pengujian',
                'kode_perusahaan' => 'MITRA-TEST-001',
                'kategori' => 'Perusahaan Swasta',
                'bidang_usaha' => 'Teknologi Informasi',
                'email' => 'mitra.test@example.com',
                'kota' => 'Malang',
                'status_verifikasi' => 'terverifikasi',
            ]);
        }

        echo 'Berhasil menyiapkan role: '
            . $user['role'] . PHP_EOL;
    }

    $pdo->commit();

    echo PHP_EOL . "Semua akun pengujian berhasil dibuat." . PHP_EOL;
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo 'Gagal: ' . $e->getMessage() . PHP_EOL;
    exit(1);
}