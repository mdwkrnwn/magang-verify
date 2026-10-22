<?php

declare(strict_types=1);

require_once __DIR__ . '/user/mahasiswa/_SeedHelper.php';

$pdo = seedDb();
$hasMigration = $pdo->prepare(
    "SELECT EXISTS (
        SELECT 1 FROM public.schema_migrations
        WHERE migration = '029_create_permintaan_pembimbing.sql'
    )"
);
$hasMigration->execute();
if (!(bool) $hasMigration->fetchColumn()) {
    throw new RuntimeException('Migration 029 belum tercatat; seeder permintaan pembimbing tidak dijalankan.');
}

$pdo->beginTransaction();
try {
    $studentUserId = seedUserId('mhs_test');
    $advisorUserId = seedUserId('dosen_test');

    $studentStmt = $pdo->prepare('SELECT id FROM profil_mahasiswa WHERE user_id = :user_id LIMIT 1');
    $studentStmt->execute(['user_id' => $studentUserId]);
    $studentId = $studentStmt->fetchColumn();
    if ($studentId === false) {
        throw new RuntimeException('Profil mhs_test belum ada. Jalankan SeedTestUsers.php terlebih dahulu.');
    }

    $advisorStmt = $pdo->prepare('SELECT id FROM profil_dosen WHERE user_id = :user_id LIMIT 1');
    $advisorStmt->execute(['user_id' => $advisorUserId]);
    $advisorId = $advisorStmt->fetchColumn();
    if ($advisorId === false) {
        throw new RuntimeException('Profil dosen_test belum ada. Jalankan SeedTestUsers.php terlebih dahulu.');
    }

    $placementStmt = $pdo->prepare(
        'SELECT pm.id, pm.status, pm.dosen_pembimbing_id, p.status_pendaftaran
         FROM penempatan_magang pm
         INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id
         WHERE p.mahasiswa_id = :mahasiswa_id
         ORDER BY pm.id DESC LIMIT 1'
    );
    $placementStmt->execute(['mahasiswa_id' => $studentId]);
    $placement = $placementStmt->fetch(PDO::FETCH_ASSOC);

    if (!$placement) {
        $formStmt = $pdo->prepare(
            "SELECT f.id, f.jumlah_kuota, f.jumlah_diterima, f.tanggal_mulai, f.tanggal_selesai
             FROM formasi_magang f
             INNER JOIN mitra m ON m.id = f.mitra_id
             WHERE m.kode_perusahaan = 'MITRA-SEED-001' AND f.status = 'dibuka'
             ORDER BY f.id LIMIT 1 FOR UPDATE OF f"
        );
        $formStmt->execute();
        $form = $formStmt->fetch(PDO::FETCH_ASSOC);
        if (!$form || (int) $form['jumlah_diterima'] >= (int) $form['jumlah_kuota']) {
            throw new RuntimeException('Tidak ada formasi seed terbuka dengan kuota tersedia.');
        }

        $activeStmt = $pdo->prepare(
            "SELECT id FROM pendaftaran_magang
             WHERE mahasiswa_id = :mahasiswa_id
               AND status_pendaftaran IN ('diajukan','menunggu_persetujuan_dosen','menunggu_respons_mitra','seleksi','diterima','perlu_revisi')
             LIMIT 1"
        );
        $activeStmt->execute(['mahasiswa_id' => $studentId]);
        if ($activeStmt->fetchColumn() !== false) {
            throw new RuntimeException('mhs_test sudah memiliki pengajuan aktif; seeder tidak mengubah pengajuan tersebut.');
        }

        $applicationInsert = $pdo->prepare(
            "INSERT INTO pendaftaran_magang
                (mahasiswa_id, formasi_id, status_persetujuan_dosen, dosen_penyetuju_id,
                 waktu_persetujuan_dosen, catatan_dosen, status_respons_mitra,
                 waktu_respons_mitra, status_pendaftaran, tanggal_pengajuan, tanggal_selesai_proses)
             VALUES (:mahasiswa_id, :formasi_id, 'disetujui', :dosen_id,
                     CURRENT_TIMESTAMP, 'Persetujuan seed testing.', 'diterima',
                     CURRENT_TIMESTAMP, 'diterima', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
             RETURNING id"
        );
        $applicationInsert->execute([
            'mahasiswa_id' => (int) $studentId,
            'formasi_id' => (int) $form['id'],
            'dosen_id' => $advisorUserId,
        ]);
        $applicationId = (int) $applicationInsert->fetchColumn();

        $historyInsert = $pdo->prepare(
            'INSERT INTO riwayat_pendaftaran_magang
                (pendaftaran_id, status_sebelumnya, status_baru, catatan, diubah_oleh)
             VALUES (:id, :previous, :next, :note, :actor)'
        );
        foreach ([
            [null, 'menunggu_persetujuan_dosen', 'Pengajuan testing dikirim.'],
            ['menunggu_persetujuan_dosen', 'menunggu_respons_mitra', 'Persetujuan dosen testing.'],
            ['menunggu_respons_mitra', 'diterima', 'Mitra seed menerima mahasiswa testing.'],
        ] as [$previous, $next, $note]) {
            $historyInsert->execute([
                'id' => $applicationId,
                'previous' => $previous,
                'next' => $next,
                'note' => $note,
                'actor' => $advisorUserId,
            ]);
        }

        $increment = $pdo->prepare(
            'UPDATE formasi_magang SET jumlah_diterima = jumlah_diterima + 1, updated_at = CURRENT_TIMESTAMP
             WHERE id = :id AND jumlah_diterima < jumlah_kuota'
        );
        $increment->execute(['id' => (int) $form['id']]);
        if ($increment->rowCount() !== 1) {
            throw new RuntimeException('Kuota formasi berubah saat seeding; seluruh transaksi dibatalkan.');
        }

        $placementInsert = $pdo->prepare(
            "INSERT INTO penempatan_magang
                (pendaftaran_id, dosen_pembimbing_id, tanggal_mulai, tanggal_selesai, status, catatan)
             VALUES (:application_id, NULL, :tanggal_mulai, :tanggal_selesai, 'persiapan', 'Penempatan testing untuk permintaan pembimbing.')
             RETURNING id"
        );
        $placementInsert->execute([
            'application_id' => $applicationId,
            'tanggal_mulai' => $form['tanggal_mulai'],
            'tanggal_selesai' => $form['tanggal_selesai'],
        ]);
        $placement = [
            'id' => (int) $placementInsert->fetchColumn(),
            'status' => 'persiapan',
            'dosen_pembimbing_id' => null,
            'status_pendaftaran' => 'diterima',
        ];
    }

    if ($placement['status_pendaftaran'] !== 'diterima'
        || !in_array($placement['status'], ['persiapan', 'berlangsung'], true)
        || $placement['dosen_pembimbing_id'] !== null) {
        throw new RuntimeException('Penempatan mhs_test yang ditemukan tidak sesuai skenario seed; tidak ada data existing yang diubah.');
    }

    $requestCheck = $pdo->prepare(
        'SELECT id FROM permintaan_pembimbing WHERE penempatan_id = :placement_id LIMIT 1'
    );
    $requestCheck->execute(['placement_id' => (int) $placement['id']]);
    if ($requestCheck->fetchColumn() === false) {
        $insertRequest = $pdo->prepare(
            "INSERT INTO permintaan_pembimbing
                (penempatan_id, dosen_id, alasan_pengajuan, status_permintaan)
             VALUES (:placement_id, :dosen_id, :reason, 'menunggu')"
        );
        $insertRequest->execute([
            'placement_id' => (int) $placement['id'],
            'dosen_id' => (int) $advisorId,
            'reason' => 'Seeder testing: meminta bimbingan untuk pengembangan aplikasi web dan integrasi API.',
        ]);
        seedLog('Permintaan pembimbing menunggu dibuat untuk mhs_test.');
    } else {
        seedLog('Riwayat permintaan pembimbing mhs_test sudah ada; tidak ditimpa.');
    }

    $pdo->commit();
    echo "Seeder permintaan pembimbing selesai." . PHP_EOL;
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    throw $e;
}
