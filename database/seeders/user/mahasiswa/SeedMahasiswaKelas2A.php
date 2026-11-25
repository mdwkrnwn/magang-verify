<?php

declare(strict_types=1);

/**
 * Seeder Mahasiswa Kelas 2A - VerifyMagang
 *
 * Jalankan dari root project:
 * php database/seeders/user/mahasiswa/SeedMahasiswaKelas2A.php
 *
 * Isi NIM secara manual pada array $students.
 * Login ID = NIM
 * Password = NIM
 */

require_once __DIR__ . '/_SeedHelper.php';

$pdo = seedDb();

$students = [
    // Format: [NIM, NAMA, JENIS_KELAMIN]

    ['254107020174', 'ADITYA RAGIL AKBAR SEPTIAWAN', 'L'],
    ['254107020138', 'ADRIAN ALEXANDER SANDA', 'L'],
    ['254107020152', 'ALYA MUKHBITA LARASSATI', 'P'],
    ['254107020002', 'ARIEF RAHMAN HAKIM CANDRA', 'L'],
    ['254107020129', 'ARIEL ARDANI ARIS PUTRA', 'L'],
    ['254107020029', 'ATHIARAHIMA MUTHMAINNAH', 'P'],
    ['254107020055', 'CAESAR VIOR BYRMAND A', 'L'],
    ['254107020202', 'CINDY CALLISTA NATHANIA SUWANDI', 'P'],
    ['254107020140', 'DAFFA DWI NUGROHO', 'L'],
    ['254107020199', 'ELSHAF ATAR PRASETYA', 'L'],
    ['254107020179', 'FADHIL FIRNAS DERMAWAN', 'L'],
    ['254107020232', 'FARID FEBRIANTO', 'L'],
    ['254107020193', 'FIRMANSYAH TRI WIDAYA', 'L'],
    ['254107020175', 'GARCIA FERNANDA VALENCA ARCHADEA', 'L'],
    ['254107020203', 'GHULAM EL FIRDAUSY', 'L'],
    ['254107020194', 'IRSYAD HAYBATULLAH', 'L'],
    ['254107020001', 'M. DWI KURNIAWAN', 'L'],
    ['254107020176', 'M. SAMSUL ARIFIN BAIHAQI', 'L'],
    ['254107020167', 'MOCHAMAD HAZBILLUH ZAMAN', 'L'],
    ['254107020010', 'MUHAMMAD DAVIQ NAUFAL HAQOUL A', 'L'],
    ['254107020013', 'MUHAMMAD NASHRULLOH', 'L'],
    ['254107020098', 'NOBBEL KAISAR BHUMI', 'L'],
    ['254107020168', 'RAHMA AULIA NURRIZKY', 'P'],
    ['254107020211', 'RAVY SANDA ADITYA', 'L'],
    ['254107020049', 'RHENZA RENDRAGRAHA', 'L'],
    ['254107020154', 'SAKTIAWAN CAKRA DARMAWANSYAH', 'L'],
    ['254107020096', 'SULTAN ALFAYYAD SALDI', 'L'],
];

// ============================================
// VALIDASI DATA SEBELUM INSERT
// ============================================

$usedNims = [];

foreach ($students as $index => $student) {
    [$nim, $name, $gender] = $student;

    if (trim($nim) === '') {
        throw new RuntimeException(
            "NIM mahasiswa nomor " . ($index + 1) . " belum diisi."
        );
    }

    if (isset($usedNims[$nim])) {
        throw new RuntimeException("NIM duplikat: {$nim}");
    }

    $usedNims[$nim] = true;
}

// ============================================
// PERSIAPAN QUERY
// ============================================

$checkUser = $pdo->prepare(
    'SELECT id FROM public.users WHERE login_id = :nim'
);

$checkNim = $pdo->prepare(
    'SELECT id FROM public.profil_mahasiswa WHERE nim = :nim'
);

$insertUser = $pdo->prepare(
    'INSERT INTO public.users
        (login_id, name, password, role, is_active)
     VALUES
        (:login_id, :name, :password, \'mahasiswa\', TRUE)
     RETURNING id'
);

$insertProfile = $pdo->prepare(
    'INSERT INTO public.profil_mahasiswa
        (user_id, nim, program_studi, angkatan)
     VALUES
        (:user_id, :nim, :program_studi, :angkatan)'
);

// ============================================
// INSERT DATA
// ============================================

$pdo->beginTransaction();

try {
    foreach ($students as $index => [$nim, $name, $gender]) {

        // Pastikan NIM belum digunakan
        $checkUser->execute(['nim' => $nim]);

        if ($checkUser->fetchColumn() !== false) {
            throw new RuntimeException(
                "NIM {$nim} sudah digunakan pada tabel users."
            );
        }

        $checkNim->execute(['nim' => $nim]);

        if ($checkNim->fetchColumn() !== false) {
            throw new RuntimeException(
                "NIM {$nim} sudah digunakan pada profil mahasiswa."
            );
        }

        // Password sama dengan NIM
        $passwordHash = password_hash($nim, PASSWORD_DEFAULT);

        // Buat akun mahasiswa
        $insertUser->execute([
            'login_id' => $nim,
            'name' => $name,
            'password' => $passwordHash,
        ]);

        $userId = (int) $insertUser->fetchColumn();

        // Buat profil mahasiswa
        $insertProfile->execute([
            'user_id' => $userId,
            'nim' => $nim,
            'program_studi' => 'D-IV Teknik Informatika',
            'angkatan' => 2025,
        ]);

        printf(
            "[SEED] %s | %s | %s | user_id=%d | berhasil\n",
            $nim,
            $name,
            $gender,
            $userId
        );
    }

    $pdo->commit();

    echo PHP_EOL;
    echo "====================================" . PHP_EOL;
    echo "SEEDER BERHASIL" . PHP_EOL;
    echo "Total mahasiswa: " . count($students) . PHP_EOL;
    echo "Angkatan: 2025" . PHP_EOL;
    echo "Program Studi: D-IV Teknik Informatika" . PHP_EOL;
    echo "====================================" . PHP_EOL;

} catch (Throwable $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    throw $e;
}
