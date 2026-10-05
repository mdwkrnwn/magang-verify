<?php

declare(strict_types=1);

require_once __DIR__ . '/_SeedHelper.php';

$pdo = seedDb();
$pdo->beginTransaction();

try {
    $students = [
        'mhs_andi' => [
            ['aksi' => 'Pengajuan magang selesai diproses', 'deskripsi' => 'Pengajuan Software Engineer Intern telah selesai dan penempatan telah diselesaikan.', 'modul' => 'pengajuan', 'jenis' => 'pendaftaran'],
            ['aksi' => 'Logbook diperbarui', 'deskripsi' => 'Empat minggu aktivitas magang telah tercatat pada logbook.', 'modul' => 'logbook', 'jenis' => 'logbook'],
            ['aksi' => 'Sertifikat ditambahkan', 'deskripsi' => 'Sertifikat AWS Cloud Practitioner berhasil ditambahkan.', 'modul' => 'sertifikat', 'jenis' => 'verifikasi'],
            ['aksi' => 'Portofolio dipublikasikan', 'deskripsi' => 'Sistem Informasi Akademik Modern dipublikasikan ke profil.', 'modul' => 'portofolio', 'jenis' => 'portofolio'],
        ],
        'mhs_budi' => [
            ['aksi' => 'Pengajuan magang dikirim', 'deskripsi' => 'Pengajuan Web Developer Intern sedang menunggu persetujuan dosen.', 'modul' => 'pengajuan', 'jenis' => 'pendaftaran'],
            ['aksi' => 'Portofolio dipublikasikan', 'deskripsi' => 'Aplikasi POS UMKM berhasil dipublikasikan.', 'modul' => 'portofolio', 'jenis' => 'portofolio'],
            ['aksi' => 'Sertifikat terverifikasi', 'deskripsi' => 'Sertifikat Belajar Dasar Pemrograman Web telah terverifikasi.', 'modul' => 'sertifikat', 'jenis' => 'verifikasi'],
        ],
        'mhs_citra' => [
            ['aksi' => 'Portofolio disimpan sebagai draft', 'deskripsi' => 'Platform Edukasi Digital masih menunggu verifikasi.', 'modul' => 'portofolio', 'jenis' => 'portofolio'],
            ['aksi' => 'Sertifikat ditambahkan', 'deskripsi' => 'UI/UX Design Fundamentals ditambahkan ke profil.', 'modul' => 'sertifikat', 'jenis' => 'verifikasi'],
        ],
        'mhs_dimas' => [
            ['aksi' => 'Portofolio memerlukan perbaikan', 'deskripsi' => 'Dashboard Analitik Kampus memerlukan perbaikan sebelum dipublikasikan.', 'modul' => 'portofolio', 'jenis' => 'verifikasi'],
            ['aksi' => 'Sertifikat ditambahkan', 'deskripsi' => 'Data Analytics Fundamentals sedang dalam peninjauan.', 'modul' => 'sertifikat', 'jenis' => 'verifikasi'],
        ],
        'mhs_eka' => [
            ['aksi' => 'Sertifikat ditolak', 'deskripsi' => 'Cyber Security Fundamentals perlu diperbaiki dan diajukan kembali.', 'modul' => 'sertifikat', 'jenis' => 'verifikasi'],
            ['aksi' => 'Pengalaman diperbarui', 'deskripsi' => 'Pengalaman organisasi berhasil ditambahkan ke profil.', 'modul' => 'profil', 'jenis' => 'sistem'],
        ],
    ];

    foreach ($students as $loginId => $activities) {
        $userId = seedUserId($loginId);

        foreach ($activities as $item) {
            $exists = seedExists(
                'SELECT 1 FROM aktivitas_pengguna WHERE user_id = :user_id AND aksi = :aksi AND deskripsi = :deskripsi LIMIT 1',
                ['user_id' => $userId, 'aksi' => $item['aksi'], 'deskripsi' => $item['deskripsi']]
            );

            if ($exists) {
                continue;
            }

            seedInsert('aktivitas_pengguna', [
                'user_id' => $userId,
                'aksi' => $item['aksi'],
                'deskripsi' => $item['deskripsi'],
                'modul' => $item['modul'],
                'ip_address' => '127.0.0.1',
            ]);
        }
    }

    // Notifikasi mengikuti data pengajuan/sertifikat yang sudah dibuat oleh seeder domain.
    $budiId = seedUserId('mhs_budi');
    $budiApplication = seedDb()->query("SELECT id FROM pendaftaran_magang WHERE mahasiswa_id = (SELECT id FROM profil_mahasiswa WHERE user_id = {$budiId}) ORDER BY id DESC LIMIT 1")->fetchColumn();
    if ($budiApplication !== false) {
        seedNotification($budiId, 'Pengajuan menunggu persetujuan', 'Pengajuan magang Anda sedang menunggu persetujuan dosen.', 'persetujuan', 'pendaftaran_magang', (int) $budiApplication, '/dashboard/mahasiswa/pengajuan/detail/' . (int) $budiApplication);
    }

    $andiId = seedUserId('mhs_andi');
    $andiCertificate = seedDb()->prepare('SELECT id FROM sertifikat WHERE user_id = :user_id ORDER BY id DESC LIMIT 1');
    $andiCertificate->execute(['user_id' => $andiId]);
    $certificateId = $andiCertificate->fetchColumn();
    if ($certificateId !== false) {
        seedNotification($andiId, 'Sertifikat terverifikasi', 'Sertifikat Anda telah berhasil diverifikasi.', 'verifikasi', 'sertifikat', (int) $certificateId, '/dashboard/mahasiswa/sertifikat');
    }

    // Data logbook tidak lagi dibuat di seeder dashboard.
    // Gunakan SeedDimasLogbook.php untuk membuat penempatan aktif, lalu
    // buat minggu dan aktivitas harian melalui workflow aplikasi agar
    // aturan tanggal, validasi, versi, dan tanda tangan tetap teruji.

    $pdo->commit();
    echo "Seeder dashboard mahasiswa selesai." . PHP_EOL;
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    throw $e;
}

function seedNotification(
    int $userId,
    string $judul,
    string $pesan,
    string $jenis,
    ?string $referensiTabel = null,
    ?int $referensiId = null,
    ?string $tautan = null
): void {
    $exists = seedExists(
        'SELECT 1 FROM notifikasi WHERE user_id = :user_id AND judul = :judul AND pesan = :pesan LIMIT 1',
        ['user_id' => $userId, 'judul' => $judul, 'pesan' => $pesan]
    );

    if ($exists) {
        return;
    }

    seedInsert('notifikasi', [
        'user_id' => $userId,
        'judul' => $judul,
        'pesan' => $pesan,
        'jenis' => $jenis,
        'referensi_tabel' => $referensiTabel,
        'referensi_id' => $referensiId,
        'tautan' => $tautan,
        'sudah_dibaca' => false,
        'dibaca_pada' => null,
    ]);
}
