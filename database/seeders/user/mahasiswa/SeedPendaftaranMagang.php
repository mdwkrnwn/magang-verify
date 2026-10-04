<?php

declare(strict_types=1);
require_once __DIR__ . '/_SeedHelper.php';

$pdo = seedDb();
$pdo->beginTransaction();

try {
    $dosenId = seedUserId('dosen_seed');
    $mitraIdStmt = $pdo->query("SELECT id FROM mitra WHERE kode_perusahaan = 'MITRA-SEED-001' LIMIT 1");
    $mitraId = $mitraIdStmt->fetchColumn();
    if ($mitraId === false) throw new RuntimeException('Mitra seed belum ada. Jalankan 05_SeedMitraMagang.php.');

    $formStmt = $pdo->prepare('SELECT id, judul FROM formasi_magang WHERE mitra_id = :mitra_id ORDER BY id ASC');
    $formStmt->execute(['mitra_id' => (int) $mitraId]);
    $forms = $formStmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($forms) < 2) {
        throw new RuntimeException('Minimal dua formasi seed diperlukan.');
    }

    // ANDI: sudah diterima dan penempatan selesai.
    $andiId = seedMahasiswaId('23410001');
    $formAndi = (int) $forms[0]['id'];

    $check = $pdo->prepare('SELECT id FROM pendaftaran_magang WHERE mahasiswa_id = :mahasiswa_id AND formasi_id = :formasi_id LIMIT 1');
    $check->execute(['mahasiswa_id' => $andiId, 'formasi_id' => $formAndi]);
    $pendaftaranAndi = $check->fetchColumn();

    if ($pendaftaranAndi === false) {
        $pendaftaranAndi = seedInsert('pendaftaran_magang', [
            'mahasiswa_id' => $andiId,
            'formasi_id' => $formAndi,
            'status_persetujuan_dosen' => 'disetujui',
            'dosen_penyetuju_id' => $dosenId,
            'waktu_persetujuan_dosen' => '2025-12-20 09:00:00+07',
            'catatan_dosen' => 'Disetujui untuk mengikuti program magang.',
            'status_respons_mitra' => 'diterima',
            'waktu_respons_mitra' => '2025-12-25 10:00:00+07',
            'status_pendaftaran' => 'selesai',
            'tanggal_pengajuan' => '2025-12-15 09:00:00+07',
            'tanggal_selesai_proses' => '2026-05-05 10:00:00+07',
        ]);
        seedLog("Pendaftaran Andi dibuat (#{$pendaftaranAndi}).");
    } else {
        $pendaftaranAndi = (int) $pendaftaranAndi;
    }

    // Penempatan Andi.
    $check = $pdo->prepare('SELECT id FROM penempatan_magang WHERE pendaftaran_id = :pendaftaran_id LIMIT 1');
    $check->execute(['pendaftaran_id' => $pendaftaranAndi]);
    $penempatanAndi = $check->fetchColumn();

    if ($penempatanAndi === false) {
        $penempatanAndi = seedInsert('penempatan_magang', [
            'pendaftaran_id' => $pendaftaranAndi,
            'dosen_pembimbing_id' => null,
            'tanggal_mulai' => '2026-01-05',
            'tanggal_selesai' => '2026-04-30',
            'status' => 'selesai',
            'catatan' => 'Magang telah selesai.',
            'ditetapkan_oleh' => $dosenId,
        ]);
        seedLog("Penempatan Andi selesai (#{$penempatanAndi}).");
    } else {
        $penempatanAndi = (int) $penempatanAndi;
    }

    // Verifikasi penyelesaian Andi.
    $check = $pdo->prepare('SELECT id FROM verifikasi_penyelesaian_magang WHERE penempatan_id = :penempatan_id LIMIT 1');
    $check->execute(['penempatan_id' => $penempatanAndi]);

    if ($check->fetchColumn() === false) {
        seedInsert('verifikasi_penyelesaian_magang', [
            'penempatan_id' => $penempatanAndi,
            'diverifikasi_oleh' => $dosenId,
            'status' => 'terverifikasi',
            'catatan' => 'Penyelesaian magang terverifikasi.',
            'diverifikasi_pada' => '2026-05-05 11:00:00+07',
        ]);
        seedLog("Verifikasi penyelesaian Andi dibuat.");
    }

    // BUDI: masih menunggu persetujuan dosen.
$budiId = seedMahasiswaId('23410002');
$formBudi = (int) $forms[1]['id'];

$check = $pdo->prepare('
    SELECT id
    FROM pendaftaran_magang
    WHERE mahasiswa_id = :mahasiswa_id
      AND formasi_id = :formasi_id
    LIMIT 1
');

$check->execute([
    'mahasiswa_id' => $budiId,
    'formasi_id'   => $formBudi,
]);

$pendaftaranBudi = $check->fetchColumn();

if ($pendaftaranBudi === false) {
    $pendaftaranBudi = seedInsert('pendaftaran_magang', [
        'mahasiswa_id'             => $budiId,
        'formasi_id'               => $formBudi,
        'status_persetujuan_dosen' => 'menunggu',
        'dosen_penyetuju_id'       => null,
        'status_respons_mitra'     => 'menunggu',
        'status_pendaftaran'       => 'menunggu_persetujuan_dosen',
        'tanggal_pengajuan'        => '2026-09-25 09:00:00+07',
    ]);

    seedLog("Pendaftaran Budi dibuat: menunggu persetujuan dosen.");
} else {
    $pendaftaranBudi = (int) $pendaftaranBudi;
}

    $pdo->commit();
    echo "Seeder pendaftaran/penempatan magang selesai." . PHP_EOL;
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    throw $e;
}
