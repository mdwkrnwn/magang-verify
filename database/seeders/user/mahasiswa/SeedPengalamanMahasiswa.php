<?php
declare(strict_types=1);
require_once __DIR__ . '/_SeedHelper.php';

$pdo = seedDb();
$pdo->beginTransaction();

try {
    // Pengalaman manual untuk mahasiswa yang belum memiliki pengalaman magang selesai.
    $manual = [
        ['23410002', 'Organisasi', 'Koordinator Divisi Teknologi', 'Himpunan Mahasiswa TI', 'Malang', '2025-01-10', '2025-12-20', 'Mengelola kegiatan dan pengembangan sistem internal organisasi.'],
        ['23410003', 'Proyek', 'Frontend Developer', 'Project Kampus', 'Malang', '2026-02-01', '2026-05-30', 'Mengembangkan antarmuka aplikasi akademik menggunakan HTML, CSS, JavaScript, dan Bootstrap.'],
        ['23410004', 'Freelance', 'Web Developer', 'Klien UMKM', 'Malang', '2026-03-01', '2026-06-30', 'Membangun website katalog produk dan dashboard sederhana.'],
        ['23410005', 'Organisasi', 'Staff IT', 'Komunitas Teknologi Kampus', 'Malang', '2025-08-01', null, 'Membantu pengelolaan website dan kegiatan teknologi kampus.'],
    ];

    foreach ($manual as [$nim, $jenis, $posisi, $instansi, $lokasi, $mulai, $selesai, $deskripsi]) {
        $mahasiswaId = seedMahasiswaId($nim);

        if (seedExists(
            'SELECT 1 FROM pengalaman WHERE mahasiswa_id = :mahasiswa_id AND posisi = :posisi AND instansi = :instansi LIMIT 1',
            ['mahasiswa_id' => $mahasiswaId, 'posisi' => $posisi, 'instansi' => $instansi]
        )) {
            continue;
        }

        seedInsert('pengalaman', [
            'mahasiswa_id' => $mahasiswaId,
            'pendaftaran_id' => null,
            'jenis' => strtolower($jenis),
            'posisi' => $posisi,
            'instansi' => $instansi,
            'lokasi' => $lokasi,
            'deskripsi' => $deskripsi,
            'tanggal_mulai' => $mulai,
            'tanggal_selesai' => $selesai,
            'status_publikasi' => 'publik',
            'is_otomatis' => false,
        ]);

        seedLog("Pengalaman manual {$nim} dibuat.");
    }

    // Andi: pengalaman berasal dari magang yang sudah selesai dan terverifikasi.
    $stmt = $pdo->prepare("
        SELECT
            pm.id AS mahasiswa_id,
            p.id AS pendaftaran_id,
            fm.judul,
            m.nama_perusahaan,
            pm.lokasi_dummy
        FROM profil_mahasiswa pm
        JOIN pendaftaran_magang p ON p.mahasiswa_id = pm.id
        JOIN formasi_magang fm ON fm.id = p.formasi_id
        JOIN mitra m ON m.id = fm.mitra_id
        WHERE pm.nim = '23410001'
          AND p.status_pendaftaran = 'selesai'
        LIMIT 1
    ");

    // Karena lokasi profil tidak menyimpan lokasi khusus magang, gunakan kota mitra pada query berikutnya.
    $stmt = $pdo->prepare("
        SELECT
            pm.id AS mahasiswa_id,
            p.id AS pendaftaran_id,
            fm.judul,
            fm.lokasi_magang,
            fm.tanggal_mulai,
            fm.tanggal_selesai,
            m.nama_perusahaan
        FROM profil_mahasiswa pm
        JOIN pendaftaran_magang p ON p.mahasiswa_id = pm.id
        JOIN formasi_magang fm ON fm.id = p.formasi_id
        JOIN mitra m ON m.id = fm.mitra_id
        JOIN penempatan_magang pen ON pen.pendaftaran_id = p.id
        JOIN verifikasi_penyelesaian_magang vpm ON vpm.penempatan_id = pen.id
        WHERE pm.nim = '23410001'
          AND p.status_pendaftaran = 'selesai'
          AND pen.status = 'selesai'
          AND vpm.status = 'terverifikasi'
        LIMIT 1
    ");
    $stmt->execute();
    $magang = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($magang) {
        $exists = seedDb()->prepare('SELECT 1 FROM pengalaman WHERE pendaftaran_id = :pendaftaran_id LIMIT 1');
        $exists->execute(['pendaftaran_id' => (int) $magang['pendaftaran_id']]);

        if (!$exists->fetchColumn()) {
            seedInsert('pengalaman', [
                'mahasiswa_id' => (int) $magang['mahasiswa_id'],
                'pendaftaran_id' => (int) $magang['pendaftaran_id'],
                'jenis' => 'magang',
                'posisi' => $magang['judul'],
                'instansi' => $magang['nama_perusahaan'],
                'lokasi' => $magang['lokasi_magang'],
                'deskripsi' => 'Pengalaman magang yang otomatis dibuat dari pendaftaran magang yang telah selesai dan terverifikasi.',
                'tanggal_mulai' => $magang['tanggal_mulai'],
                'tanggal_selesai' => $magang['tanggal_selesai'],
                'status_publikasi' => 'publik',
                'is_otomatis' => true,
            ]);

            seedLog('Pengalaman magang otomatis Andi dibuat.');
        } else {
            seedLog('Pengalaman magang otomatis Andi sudah ada.');
        }
    }

    $pdo->commit();
    echo "Seeder pengalaman selesai." . PHP_EOL;
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    throw $e;
}
