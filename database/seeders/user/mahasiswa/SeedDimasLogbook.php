<?php

declare(strict_types=1);

require_once __DIR__ . '/_SeedHelper.php';

$pdo = seedDb();
$pdo->beginTransaction();

try {

    // ============================================================
    // 1. AKUN DIMAS
    // ============================================================

    $loginId = 'mhs_dimas';

    $check = $pdo->prepare('
        SELECT id
        FROM users
        WHERE login_id = :login_id
        LIMIT 1
    ');

    $check->execute([
        'login_id' => $loginId,
    ]);

    $dimasUserId = $check->fetchColumn();

    if ($dimasUserId === false) {

        $dimasUserId = seedInsert('users', [
            'login_id' => 'mhs_dimas',
            'name' => 'Dimas Pratama',
            'password' => password_hash(
                'Password123!',
                PASSWORD_DEFAULT
            ),
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);

        seedLog(
            "Akun Dimas dibuat: mhs_dimas (#{$dimasUserId})."
        );
    } else {

        $dimasUserId = (int) $dimasUserId;

        // Pastikan akun tetap aktif dan memiliki role mahasiswa.
        $update = $pdo->prepare('
            UPDATE users
            SET
                name = :name,
                role = :role,
                is_active = :is_active,
                updated_at = NOW()
            WHERE id = :id
        ');

        $update->execute([
            'name' => 'Dimas Pratama',
            'role' => 'mahasiswa',
            'is_active' => true,
            'id' => $dimasUserId,
        ]);

        seedLog(
            "Akun Dimas sudah ada (#{$dimasUserId}), data dipastikan aktif."
        );
    }


    // ============================================================
    // 2. PROFIL MAHASISWA DIMAS
    // ============================================================

    $check = $pdo->prepare('
        SELECT id
        FROM profil_mahasiswa
        WHERE user_id = :user_id
        LIMIT 1
    ');

    $check->execute([
        'user_id' => $dimasUserId,
    ]);

    $dimasProfilId = $check->fetchColumn();

    if ($dimasProfilId === false) {

        $dimasProfilId = seedInsert('profil_mahasiswa', [
            'user_id' => $dimasUserId,
            'nim' => '23410004',
            'program_studi' => 'Teknik Informatika',
            'angkatan' => 2025,
            'email' => 'dimas.pratama@example.com',
            'no_telepon' => '081234567890',
            'alamat' => 'Malang, Jawa Timur',
        ]);

        seedLog(
            "Profil mahasiswa Dimas dibuat (#{$dimasProfilId})."
        );
    } else {

        $dimasProfilId = (int) $dimasProfilId;

        // Pastikan data profil sesuai dengan akun testing.
        $update = $pdo->prepare('
            UPDATE profil_mahasiswa
            SET
                nim = :nim,
                program_studi = :program_studi,
                angkatan = :angkatan,
                email = :email,
                no_telepon = :no_telepon,
                alamat = :alamat
            WHERE id = :id
        ');

        $update->execute([
            'nim' => '23410004',
            'program_studi' => 'Teknik Informatika',
            'angkatan' => 2025,
            'email' => 'dimas.pratama@example.com',
            'no_telepon' => '081234567890',
            'alamat' => 'Malang, Jawa Timur',
            'id' => $dimasProfilId,
        ]);

        seedLog(
            "Profil mahasiswa Dimas sudah ada (#{$dimasProfilId})."
        );
    }


    // ============================================================
    // 3. DOSEN SEED
    // ============================================================

    $dosenId = seedUserId('dosen_seed');

    // Ambil ID profil dosen, bukan ID user dosen.
    $dosenProfilStmt = $pdo->prepare('
    SELECT id
    FROM profil_dosen
    WHERE user_id = :user_id
    LIMIT 1
');

    $dosenProfilStmt->execute([
        'user_id' => $dosenId,
    ]);

    $dosenProfilId = $dosenProfilStmt->fetchColumn();

    if ($dosenProfilId === false) {
        // Seed ini harus dapat dijalankan mandiri. Akun dosen sudah ada,
        // tetapi profil dosen bisa belum dibuat oleh seed sebelumnya.
        $nidn = sprintf('%010d', $dosenId);
        $insertProfilDosen = $pdo->prepare('
            INSERT INTO profil_dosen
                (user_id, nidn, email, no_telepon, created_at, updated_at)
            VALUES
                (:user_id, :nidn, :email, :no_telepon, NOW(), NOW())
            RETURNING id
        ');
        $insertProfilDosen->execute([
            'user_id' => $dosenId,
            'nidn' => $nidn,
            'email' => 'dosen.seed@example.com',
            'no_telepon' => '081234567890',
        ]);
        $dosenProfilId = $insertProfilDosen->fetchColumn();

        seedLog("Profil dosen dosen_seed dibuat (#{$dosenProfilId}).");
    }

    $dosenProfilId = (int) $dosenProfilId;


    // ============================================================
    // 4. MITRA SEED
    // ============================================================

    $mitraStmt = $pdo->query("
        SELECT id
        FROM mitra
        WHERE kode_perusahaan = 'MITRA-SEED-001'
        LIMIT 1
    ");

    $mitraId = $mitraStmt->fetchColumn();

    if ($mitraId === false) {
        throw new RuntimeException(
            'Mitra seed belum ada. Jalankan SeedMitraMagang.php terlebih dahulu.'
        );
    }

    $mitraId = (int) $mitraId;


    // ============================================================
    // 5. FORMASI MAGANG
    // ============================================================

    $formStmt = $pdo->prepare('
        SELECT id, judul
        FROM formasi_magang
        WHERE mitra_id = :mitra_id
        ORDER BY id ASC
        LIMIT 1
    ');

    $formStmt->execute([
        'mitra_id' => $mitraId,
    ]);

    $formDimas = $formStmt->fetch(PDO::FETCH_ASSOC);

    if (!$formDimas) {
        throw new RuntimeException(
            'Formasi magang seed belum ada. Jalankan seed formasi magang terlebih dahulu.'
        );
    }

    $formDimasId = (int) $formDimas['id'];


    // ============================================================
    // 6. PENDAFTARAN MAGANG DIMAS
    // ============================================================

    $check = $pdo->prepare('
        SELECT id
        FROM pendaftaran_magang
        WHERE mahasiswa_id = :mahasiswa_id
          AND formasi_id = :formasi_id
        LIMIT 1
    ');

    $check->execute([
        'mahasiswa_id' => $dimasProfilId,
        'formasi_id' => $formDimasId,
    ]);

    $pendaftaranDimas = $check->fetchColumn();

    if ($pendaftaranDimas === false) {

        $pendaftaranDimas = seedInsert('pendaftaran_magang', [

            'mahasiswa_id' => $dimasProfilId,

            'formasi_id' => $formDimasId,

            // Persetujuan dosen
            'status_persetujuan_dosen' => 'disetujui',
            'dosen_penyetuju_id' => $dosenId,
            'waktu_persetujuan_dosen' => '2026-08-25 09:00:00+07',
            'catatan_dosen' => 'Disetujui untuk mengikuti program magang.',

            // Respons mitra
            'status_respons_mitra' => 'diterima',
            'waktu_respons_mitra' => '2026-08-27 10:00:00+07',
            'catatan_mitra' => 'Mahasiswa diterima untuk mengikuti program magang.',

            // Status pendaftaran
            'status_pendaftaran' => 'diterima',

            'tanggal_pengajuan' => '2026-08-20 09:00:00+07',
        ]);

        seedLog(
            "Pendaftaran magang Dimas dibuat (#{$pendaftaranDimas})."
        );
    } else {

        $pendaftaranDimas = (int) $pendaftaranDimas;

        // Pastikan status tetap siap untuk menjalani magang.
        $update = $pdo->prepare('
            UPDATE pendaftaran_magang
            SET
                status_persetujuan_dosen = :status_persetujuan_dosen,
                dosen_penyetuju_id = :dosen_penyetuju_id,
                waktu_persetujuan_dosen = :waktu_persetujuan_dosen,
                status_respons_mitra = :status_respons_mitra,
                waktu_respons_mitra = :waktu_respons_mitra,
                status_pendaftaran = :status_pendaftaran,
                updated_at = NOW()
            WHERE id = :id
        ');

        $update->execute([
            'status_persetujuan_dosen' => 'disetujui',
            'dosen_penyetuju_id' => $dosenId,
            'waktu_persetujuan_dosen' => '2026-08-25 09:00:00+07',

            'status_respons_mitra' => 'diterima',
            'waktu_respons_mitra' => '2026-08-27 10:00:00+07',

            'status_pendaftaran' => 'diterima',

            'id' => $pendaftaranDimas,
        ]);

        seedLog(
            "Pendaftaran Dimas diperbarui menjadi diterima (#{$pendaftaranDimas})."
        );
    }


    // ============================================================
    // 7. PENEMPATAN MAGANG DIMAS
    // ============================================================

    $check = $pdo->prepare('
        SELECT id
        FROM penempatan_magang
        WHERE pendaftaran_id = :pendaftaran_id
        LIMIT 1
    ');

    $check->execute([
        'pendaftaran_id' => $pendaftaranDimas,
    ]);

    $penempatanDimas = $check->fetchColumn();

    if ($penempatanDimas === false) {

        $penempatanDimas = seedInsert('penempatan_magang', [
            'pendaftaran_id' => $pendaftaranDimas,

            'dosen_pembimbing_id' => $dosenProfilId,

            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-11-30',

            'status' => 'berlangsung',

            'catatan' => 'Mahasiswa sedang menjalani program magang.',

            'ditetapkan_oleh' => $dosenId,
        ]);

        seedLog(
            "Penempatan Dimas dibuat dengan status berlangsung (#{$penempatanDimas})."
        );
    } else {

        $penempatanDimas = (int) $penempatanDimas;

        // Pastikan kondisi testing tetap berlangsung.
        $update = $pdo->prepare('
            UPDATE penempatan_magang
            SET
                dosen_pembimbing_id = :dosen_pembimbing_id,
                tanggal_mulai = :tanggal_mulai,
                tanggal_selesai = :tanggal_selesai,
                status = :status,
                catatan = :catatan,
                ditetapkan_oleh = :ditetapkan_oleh,
                updated_at = NOW()
            WHERE id = :id
        ');

        $update->execute([
            'dosen_pembimbing_id' => $dosenProfilId,
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-11-30',
            'status' => 'berlangsung',
            'catatan' => 'Mahasiswa sedang menjalani program magang.',
            'ditetapkan_oleh' => $dosenId,
            'id' => $penempatanDimas,
        ]);

        seedLog(
            "Penempatan Dimas dipastikan berstatus berlangsung (#{$penempatanDimas})."
        );
    }


    // ============================================================
    // COMMIT
    // ============================================================

    $pdo->commit();

    echo PHP_EOL;
    echo "============================================" . PHP_EOL;
    echo " SEED DIMAS - LOGBOOK TESTING" . PHP_EOL;
    echo "============================================" . PHP_EOL;
    echo "Login ID  : mhs_dimas" . PHP_EOL;
    echo "Password  : Password123!" . PHP_EOL;
    echo "NIM       : 23410004" . PHP_EOL;
    echo "Nama      : Dimas Pratama" . PHP_EOL;
    echo "Role      : mahasiswa" . PHP_EOL;
    echo "Status    : sedang menjalani magang" . PHP_EOL;
    echo "============================================" . PHP_EOL;
} catch (Throwable $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    throw $e;
}
