
<?php

require_once __DIR__ . '/../../config/database.php';

$pdo->beginTransaction();

try {
    /*
     * 1. Siapkan mitra pengujian.
     * Jika sudah ada, gunakan mitra tersebut.
     */
    $kodePerusahaan = 'MITRA-SEED-001';

    $stmt = $pdo->prepare(
        'SELECT id FROM mitra
         WHERE kode_perusahaan = :kode_perusahaan'
    );
    $stmt->execute(['kode_perusahaan' => $kodePerusahaan]);

    $mitraId = $stmt->fetchColumn();

    if (!$mitraId) {
        $insertMitra = $pdo->prepare(
            'INSERT INTO mitra (
                nama_perusahaan,
                kode_perusahaan,
                kategori,
                bidang_usaha,
                deskripsi,
                email,
                no_telepon,
                website,
                provinsi,
                kota,
                alamat,
                status_verifikasi,
                is_active
            ) VALUES (
                :nama,
                :kode,
                :kategori,
                :bidang_usaha,
                :deskripsi,
                :email,
                :telepon,
                :website,
                :provinsi,
                :kota,
                :alamat,
                :status_verifikasi,
                TRUE
            )
            RETURNING id'
        );

        $insertMitra->execute([
            'nama' => 'PT Teknologi Pengujian',
            'kode' => $kodePerusahaan,
            'kategori' => 'Perusahaan Swasta',
            'bidang_usaha' => 'Teknologi Informasi',
            'deskripsi' => 'Perusahaan mitra untuk pengujian fitur magang.',
            'email' => 'mitra.seed@example.com',
            'telepon' => '081234567890',
            'website' => 'https://example.com',
            'provinsi' => 'Jawa Timur',
            'kota' => 'Malang',
            'alamat' => 'Kota Malang, Jawa Timur',
            'status_verifikasi' => 'terverifikasi',
        ]);

        $mitraId = $insertMitra->fetchColumn();

        echo "Berhasil membuat mitra pengujian." . PHP_EOL;
    } else {
        // Pastikan mitra pengujian dapat menampilkan formasi.
        $stmt = $pdo->prepare(
            "UPDATE mitra
             SET status_verifikasi = 'terverifikasi',
                 is_active = TRUE
             WHERE id = :id"
        );
        $stmt->execute(['id' => $mitraId]);

        echo "Menggunakan mitra pengujian yang sudah ada." . PHP_EOL;
    }

    /*
     * 2. Data formasi magang.
     */
    $formasi = [
        [
            'judul' => 'Internship Web Developer',
            'deskripsi' => 'Membantu mengembangkan aplikasi web, '
                . 'membuat antarmuka, mengintegrasikan API, '
                . 'dan memperbaiki bug.',
            'bidang' => 'Web Development',
            'kuota' => 3,
            'persyaratan' => "Memahami HTML, CSS, dan JavaScript\n"
                . "Memahami dasar Git\n"
                . "Mampu bekerja dalam tim\n"
                . "Bersedia belajar teknologi baru",
            'lokasi' => 'Malang, Jawa Timur',
            'sistem_kerja' => 'hybrid',
        ],
        [
            'judul' => 'Internship Backend Developer',
            'deskripsi' => 'Membantu pengembangan REST API, '
                . 'pengolahan data, dan integrasi database.',
            'bidang' => 'Backend Development',
            'kuota' => 2,
            'persyaratan' => "Memahami dasar PHP atau JavaScript\n"
                . "Memahami SQL dan database relasional\n"
                . "Memahami konsep REST API",
            'lokasi' => 'Malang, Jawa Timur',
            'sistem_kerja' => 'onsite',
        ],
        [
            'judul' => 'Internship UI/UX Designer',
            'deskripsi' => 'Membantu riset pengguna, membuat '
                . 'wireframe, dan merancang prototipe aplikasi.',
            'bidang' => 'UI/UX Design',
            'kuota' => 2,
            'persyaratan' => "Memahami prinsip UI/UX\n"
                . "Mampu membuat wireframe dan prototype\n"
                . "Memiliki portofolio menjadi nilai tambah",
            'lokasi' => 'Malang, Jawa Timur',
            'sistem_kerja' => 'hybrid',
        ],
        [
            'judul' => 'Internship Data Analyst',
            'deskripsi' => 'Membantu pengolahan data, '
                . 'pembuatan laporan, dan visualisasi data.',
            'bidang' => 'Data Analyst',
            'kuota' => 2,
            'persyaratan' => "Memahami spreadsheet\n"
                . "Memahami dasar SQL\n"
                . "Teliti dalam mengolah data",
            'lokasi' => 'Surabaya, Jawa Timur',
            'sistem_kerja' => 'onsite',
        ],
        [
            'judul' => 'Internship QA Tester',
            'deskripsi' => 'Membantu pengujian aplikasi, '
                . 'menyusun test case, dan mendokumentasikan bug.',
            'bidang' => 'Quality Assurance',
            'kuota' => 2,
            'persyaratan' => "Memahami dasar pengujian perangkat lunak\n"
                . "Teliti dan komunikatif\n"
                . "Memahami test case menjadi nilai tambah",
            'lokasi' => 'Remote',
            'sistem_kerja' => 'remote',
        ],
        [
            'judul' => 'Internship Fullstack Developer',
            'deskripsi' => 'Membantu pengembangan fitur frontend, '
                . 'backend, dan database.',
            'bidang' => 'Fullstack Development',
            'kuota' => 3,
            'persyaratan' => "Memahami HTML, CSS, dan JavaScript\n"
                . "Memahami dasar backend dan SQL\n"
                . "Memahami Git",
            'lokasi' => 'Malang, Jawa Timur',
            'sistem_kerja' => 'hybrid',
        ],
    ];

    $insertFormasi = $pdo->prepare(
        'INSERT INTO formasi_magang (
            mitra_id,
            judul,
            deskripsi,
            bidang,
            jumlah_kuota,
            jumlah_diterima,
            persyaratan,
            lokasi_magang,
            sistem_kerja,
            tanggal_mulai,
            tanggal_selesai,
            tahun_akademik,
            status
        ) VALUES (
            :mitra_id,
            :judul,
            :deskripsi,
            :bidang,
            :kuota,
            0,
            :persyaratan,
            :lokasi,
            :sistem_kerja,
            CURRENT_DATE + 30,
            CURRENT_DATE + 120,
            :tahun_akademik,
            :status
        )
        RETURNING id'
    );

    /*
     * 3. Hindari duplikasi berdasarkan mitra dan judul.
     */
    $cekFormasi = $pdo->prepare(
        'SELECT id FROM formasi_magang
         WHERE mitra_id = :mitra_id
           AND judul = :judul'
    );

    $jumlahBaru = 0;
    $jumlahDilewati = 0;

    foreach ($formasi as $item) {
        $cekFormasi->execute([
            'mitra_id' => $mitraId,
            'judul' => $item['judul'],
        ]);

        if ($cekFormasi->fetchColumn()) {
            echo "Dilewati: {$item['judul']} (sudah ada)"
                . PHP_EOL;
            $jumlahDilewati++;
            continue;
        }

        $insertFormasi->execute([
            'mitra_id' => $mitraId,
            'judul' => $item['judul'],
            'deskripsi' => $item['deskripsi'],
            'bidang' => $item['bidang'],
            'kuota' => $item['kuota'],
            'persyaratan' => $item['persyaratan'],
            'lokasi' => $item['lokasi'],
            'sistem_kerja' => $item['sistem_kerja'],
            'tahun_akademik' => '2026/2027',
            'status' => 'dibuka',
        ]);

        $id = $insertFormasi->fetchColumn();

        echo "Berhasil membuat formasi ID {$id}: "
            . $item['judul'] . PHP_EOL;

        $jumlahBaru++;
    }

    $pdo->commit();

    echo PHP_EOL;
    echo "Seeder formasi magang selesai." . PHP_EOL;
    echo "Formasi baru: {$jumlahBaru}" . PHP_EOL;
    echo "Formasi dilewati: {$jumlahDilewati}" . PHP_EOL;

} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo 'Gagal menjalankan seeder: '
        . $e->getMessage() . PHP_EOL;

    exit(1);
}