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

    // Logbook mingguan untuk Andi karena penempatannya sudah selesai.
    $placementStmt = $pdo->prepare(''
        . 'SELECT pm.id, pm.tanggal_mulai '
        . 'FROM penempatan_magang pm '
        . 'INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id '
        . 'INNER JOIN profil_mahasiswa m ON m.id = p.mahasiswa_id '
        . 'INNER JOIN users u ON u.id = m.user_id '
        . 'WHERE u.login_id = :login_id AND pm.status = \'selesai\' '
        . 'ORDER BY pm.id DESC LIMIT 1'
    );
    $placementStmt->execute(['login_id' => 'mhs_andi']);
    $placement = $placementStmt->fetch(PDO::FETCH_ASSOC);

    if ($placement) {
        for ($week = 1; $week <= 4; $week++) {
            $exists = seedExists(
                'SELECT 1 FROM logbook_mingguan WHERE penempatan_id = :penempatan_id AND minggu_ke = :minggu_ke LIMIT 1',
                ['penempatan_id' => (int) $placement['id'], 'minggu_ke' => $week]
            );

            if ($exists) {
                continue;
            }

            $start = date('Y-m-d', strtotime($placement['tanggal_mulai'] . ' +' . (($week - 1) * 7) . ' days'));
            $end = date('Y-m-d', strtotime($start . ' +6 days'));

            $logbookId = seedInsert('logbook_mingguan', [
                'penempatan_id' => (int) $placement['id'],
                'minggu_ke' => $week,
                'tanggal_mulai' => $start,
                'tanggal_selesai' => $end,
                'versi_terkini' => 1,
                'status' => 'disetujui',
            ]);

            seedInsert('logbook_revisi', [
                'logbook_id' => $logbookId,
                'nomor_versi' => 1,
                'aktivitas' => 'Mengerjakan task pengembangan aplikasi dan mengikuti koordinasi tim.',
                'hasil_pekerjaan' => 'Task mingguan terselesaikan sesuai target.',
                'kendala' => null,
                'rencana_selanjutnya' => 'Melanjutkan pengembangan pada minggu berikutnya.',
                'catatan_revisi' => null,
                'dibuat_oleh' => $andiId,
            ]);
        }
    }

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
