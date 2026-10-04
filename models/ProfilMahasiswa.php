
<?php

require_once __DIR__ . '/../function/Helpers.php';

class ProfilMahasiswa
{
    private PDO $pdo;

    public function __construct(PDO $connection)
    {
        $this->pdo = $connection;
    }

    /**
     * Mengambil profil mahasiswa beserta data akun dan keterampilan.
     * Profil harus sudah tersedia di database.
     */
    public function getByUserId(int $userId): ?array
    {
        $sql = '
            SELECT
                u.id AS user_id,
                u.name AS nama_lengkap,
                p.id AS profil_id,
                p.nim,
                p.program_studi,
                p.angkatan,
                p.email,
                p.no_telepon,
                p.github_url,
                p.linkedin_url,
                p.portfolio_url,
                p.alamat,
                p.deskripsi,
                p.foto_path,
                p.cv_path,
                p.cv_nama_asli,
                p.cv_mime_type,
                p.cv_ukuran_bytes,
                p.cv_updated_at,
                p.created_at,
                p.updated_at
            FROM users u
            INNER JOIN profil_mahasiswa p
                ON p.user_id = u.id
            WHERE u.id = :user_id
              AND u.role = :role
            LIMIT 1
        ';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'role' => 'mahasiswa',
        ]);

        $profil = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$profil) {
            return null;
        }

        $profil['keahlian'] = $this->getSkills(
            (int) $profil['profil_id']
        );

        return $profil;
    }

    /**
     * Mengambil keterampilan yang dimiliki mahasiswa.
     */
    public function getSkills(int $profilId): array
    {
        $sql = '
            SELECT
                k.id,
                k.nama,
                k.kategori,
                mk.tingkat,
                mk.keterangan
            FROM mahasiswa_keterampilan mk
            INNER JOIN keterampilan k
                ON k.id = mk.keterampilan_id
            WHERE mk.mahasiswa_id = :profil_id
            ORDER BY k.nama ASC
        ';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['profil_id' => $profilId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
/**
 * Mengambil daftar mahasiswa yang memiliki profil dari database untuk halaman publik.
 */
public function getPublicMahasiswa(): array
{
    $sql = "
        SELECT
            u.id AS user_id,
            p.id AS profil_id,
            u.name AS nama,
            p.nim,
            p.program_studi AS prodi,
            p.angkatan,
            p.email,
            p.no_telepon,
            p.github_url,
            p.linkedin_url,
            p.portfolio_url,
            p.alamat,
            p.deskripsi AS bio,
            p.foto_path,
            COALESCE(
                jsonb_agg(DISTINCT k.nama) FILTER (WHERE k.id IS NOT NULL),
                '[]'::jsonb
            ) AS skills_json,
            COUNT(DISTINCT po.id) FILTER (
                WHERE po.status_publikasi = 'publik'
            ) AS proyek,
            COUNT(DISTINCT s.id) FILTER (
                WHERE s.status_verifikasi = 'terverifikasi'
            ) AS sertifikat
        FROM users u
        INNER JOIN profil_mahasiswa p ON p.user_id = u.id
        LEFT JOIN mahasiswa_keterampilan mk ON mk.mahasiswa_id = p.id
        LEFT JOIN keterampilan k ON k.id = mk.keterampilan_id
        LEFT JOIN portofolios po ON po.mahasiswa_id = p.id
        LEFT JOIN sertifikat s ON s.user_id = u.id
        WHERE u.role = 'mahasiswa'
        GROUP BY
            u.id, p.id, u.name, p.nim, p.program_studi, p.angkatan,
            p.email, p.no_telepon, p.alamat, p.deskripsi, p.foto_path
        ORDER BY p.angkatan DESC NULLS LAST, u.name ASC
    ";

    $stmt = $this->pdo->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return array_map(function (array $row): array {
        $skills = json_decode((string) ($row['skills_json'] ?? '[]'), true);
        if (!is_array($skills)) {
            $skills = [];
        }

        $row['id'] = (int) $row['profil_id'];
        $row['user_id'] = (int) $row['user_id'];
        $row['profil_id'] = (int) $row['profil_id'];
        $row['angkatan'] = $row['angkatan'] !== null ? (int) $row['angkatan'] : null;
        $row['proyek'] = (int) $row['proyek'];
        $row['sertifikat'] = (int) $row['sertifikat'];
        $row['skills'] = array_values(array_filter(array_map('strval', $skills)));
        $row['keahlian'] = $row['skills'];
        $row['tentang'] = trim((string) ($row['bio'] ?? ''));
        $row['domisili'] = trim((string) ($row['alamat'] ?? ''));
        $row['slug'] = slugify((string) $row['nama']);

        return $row;
    }, $rows);
}

/**
 * Mengambil beberapa mahasiswa unggulan untuk ditampilkan di halaman beranda.
 * Urutan diprioritaskan berdasarkan jumlah portofolio publik dan sertifikat
 * terverifikasi, lalu angkatan dan nama.
 */
public function getFeaturedMahasiswa(int $limit = 3): array
{
    $limit = max(1, min($limit, 12));

    $sql = "
        SELECT
            u.id AS user_id,
            p.id AS profil_id,
            u.name AS nama,
            p.nim,
            p.program_studi AS prodi,
            p.angkatan,
            p.foto_path,
            COALESCE(
                jsonb_agg(DISTINCT k.nama) FILTER (WHERE k.id IS NOT NULL),
                '[]'::jsonb
            ) AS skills_json,
            COUNT(DISTINCT po.id) FILTER (
                WHERE po.status_publikasi = 'publik'
            ) AS proyek,
            COUNT(DISTINCT s.id) FILTER (
                WHERE s.status_verifikasi = 'terverifikasi'
            ) AS sertifikat
        FROM users u
        INNER JOIN profil_mahasiswa p ON p.user_id = u.id
        LEFT JOIN mahasiswa_keterampilan mk ON mk.mahasiswa_id = p.id
        LEFT JOIN keterampilan k ON k.id = mk.keterampilan_id
        LEFT JOIN portofolios po ON po.mahasiswa_id = p.id
        LEFT JOIN sertifikat s ON s.user_id = u.id
        WHERE u.role = 'mahasiswa'
        GROUP BY
            u.id, p.id, u.name, p.nim, p.program_studi, p.angkatan, p.foto_path
        ORDER BY
            (
                COUNT(DISTINCT po.id) FILTER (WHERE po.status_publikasi = 'publik')
                + COUNT(DISTINCT s.id) FILTER (WHERE s.status_verifikasi = 'terverifikasi')
            ) DESC,
            p.angkatan DESC NULLS LAST,
            u.name ASC
        LIMIT :limit
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return array_map(function (array $row): array {
        $skills = json_decode((string) ($row['skills_json'] ?? '[]'), true);
        if (!is_array($skills)) {
            $skills = [];
        }

        $row['id'] = (int) $row['profil_id'];
        $row['user_id'] = (int) $row['user_id'];
        $row['profil_id'] = (int) $row['profil_id'];
        $row['angkatan'] = $row['angkatan'] !== null ? (int) $row['angkatan'] : null;
        $row['proyek'] = (int) $row['proyek'];
        $row['sertifikat'] = (int) $row['sertifikat'];
        $row['keahlian'] = array_values(array_filter(array_map('strval', $skills)));
        $row['slug'] = slugify((string) $row['nama']);

        return $row;
    }, $stmt->fetchAll(PDO::FETCH_ASSOC));
}

/**
 * Mengambil satu profil mahasiswa publik berdasarkan slug nama.
 * Portofolio publik dan sertifikat terverifikasi ikut dimuat untuk halaman detail.
 */
public function getPublicMahasiswaBySlug(string $slug): ?array
{
    $sql = "
        SELECT
            u.id AS user_id,
            p.id AS profil_id,
            u.name AS nama,
            p.nim,
            p.program_studi AS prodi,
            p.angkatan,
            p.email,
            p.no_telepon,
            p.alamat,
            p.deskripsi AS bio,
            p.foto_path,
            p.cv_path,
            p.cv_nama_asli,
            p.cv_mime_type,
            p.cv_ukuran_bytes,
            p.cv_updated_at
        FROM users u
        INNER JOIN profil_mahasiswa p ON p.user_id = u.id
        WHERE u.role = 'mahasiswa'
          AND LOWER(TRIM(BOTH '-' FROM REGEXP_REPLACE(TRIM(u.name), '[^a-zA-Z0-9]+', '-', 'g'))) = LOWER(:slug)
        LIMIT 1
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['slug' => trim($slug)]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return null;
    }

    $profilId = (int) $row['profil_id'];
    $userId = (int) $row['user_id'];

    $row['id'] = $profilId;
    $row['user_id'] = $userId;
    $row['profil_id'] = $profilId;
    $row['angkatan'] = $row['angkatan'] !== null ? (int) $row['angkatan'] : null;

    $skillStmt = $this->pdo->prepare("\n        SELECT k.nama\n        FROM mahasiswa_keterampilan mk\n        INNER JOIN keterampilan k ON k.id = mk.keterampilan_id\n        WHERE mk.mahasiswa_id = :profil_id\n        ORDER BY k.nama ASC\n    ");
    $skillStmt->execute(['profil_id' => $profilId]);
    $row['skills'] = array_map('strval', $skillStmt->fetchAll(PDO::FETCH_COLUMN));
    $row['keahlian'] = $row['skills'];
    $row['tentang'] = trim((string) ($row['bio'] ?? ''));
    $row['domisili'] = trim((string) ($row['alamat'] ?? ''));
    $row['slug'] = slugify((string) $row['nama']);

    $portfolioStmt = $this->pdo->prepare("\n        SELECT\n            p.id, p.judul, p.deskripsi, p.tautan, p.gambar_sampul,\n            p.tautan_github, p.tautan_demo\n        FROM portofolios p\n        WHERE p.mahasiswa_id = :profil_id\n          AND p.status_publikasi = 'publik'\n        ORDER BY p.created_at DESC, p.id DESC\n    ");
    $portfolioStmt->execute(['profil_id' => $profilId]);

    $row['daftar_proyek'] = [];
    $techStmt = $this->pdo->prepare("\n        SELECT nama_teknologi\n        FROM portofolio_teknologi\n        WHERE portofolio_id = :portofolio_id\n        ORDER BY nama_teknologi ASC\n    ");

    foreach ($portfolioStmt->fetchAll(PDO::FETCH_ASSOC) as $portfolio) {
        $techStmt->execute(['portofolio_id' => (int) $portfolio['id']]);
        $gambar = trim((string) ($portfolio['gambar_sampul'] ?? ''));
        $url = trim((string) ($portfolio['tautan_github'] ?? ''))
            ?: trim((string) ($portfolio['tautan_demo'] ?? ''))
            ?: trim((string) ($portfolio['tautan'] ?? ''));

        $row['daftar_proyek'][] = [
            'nama' => (string) $portfolio['judul'],
            'deskripsi' => (string) $portfolio['deskripsi'],
            'tech' => array_map('strval', $techStmt->fetchAll(PDO::FETCH_COLUMN)),
            'url' => $url !== '' ? $url : '#',
            'gambar' => $gambar !== '' ? url('/' . ltrim($gambar, '/')) : '',
        ];
    }

    $certificateStmt = $this->pdo->prepare("\n        SELECT nama, penerbit, tanggal_terbit\n        FROM sertifikat\n        WHERE user_id = :user_id\n          AND status_verifikasi = 'terverifikasi'\n        ORDER BY tanggal_terbit DESC NULLS LAST, id DESC\n    ");
    $certificateStmt->execute(['user_id' => $userId]);

    $row['daftar_sertifikat'] = [];
    foreach ($certificateStmt->fetchAll(PDO::FETCH_ASSOC) as $certificate) {
        $tanggal = $certificate['tanggal_terbit']
            ? date('M Y', strtotime((string) $certificate['tanggal_terbit']))
            : '-';

        $row['daftar_sertifikat'][] = [
            'nama' => (string) $certificate['nama'],
            'penerbit' => (string) $certificate['penerbit'],
            'tanggal' => $tanggal,
            'verified' => true,
        ];
    }

    $syncExperienceStmt = $this->pdo->prepare("
        INSERT INTO pengalaman (mahasiswa_id, pendaftaran_id, jenis, posisi, instansi, lokasi, deskripsi, tanggal_mulai, tanggal_selesai, status_publikasi, is_otomatis)
        SELECT pm.id, pd.id, 'magang', fm.judul, m.nama_perusahaan, fm.lokasi_magang, fm.deskripsi, pen.tanggal_mulai, pen.tanggal_selesai, 'publik', TRUE
        FROM penempatan_magang pen
        JOIN pendaftaran_magang pd ON pd.id = pen.pendaftaran_id
        JOIN profil_mahasiswa pm ON pm.id = pd.mahasiswa_id
        JOIN formasi_magang fm ON fm.id = pd.formasi_id
        JOIN mitra m ON m.id = fm.mitra_id
        LEFT JOIN verifikasi_penyelesaian_magang v ON v.penempatan_id = pen.id
        WHERE pm.id = :profil_id AND pen.status = 'selesai'
          AND (v.status = 'terverifikasi' OR v.id IS NULL)
          AND NOT EXISTS (SELECT 1 FROM pengalaman e WHERE e.pendaftaran_id = pd.id)
    " );
    $syncExperienceStmt->execute(['profil_id' => $profilId]);

    $experienceStmt = $this->pdo->prepare("
        SELECT jenis, posisi, instansi, lokasi, deskripsi, tanggal_mulai, tanggal_selesai, is_otomatis
        FROM pengalaman
        WHERE mahasiswa_id = :profil_id
          AND status_publikasi = 'publik'
        ORDER BY tanggal_selesai DESC NULLS LAST, tanggal_mulai DESC NULLS LAST, id DESC
    " );
    $experienceStmt->execute(['profil_id' => $profilId]);
    $row['pengalaman'] = [];
    foreach ($experienceStmt->fetchAll(PDO::FETCH_ASSOC) as $experience) {
        $mulai = $experience['tanggal_mulai'] ? date('M Y', strtotime((string)$experience['tanggal_mulai'])) : '';
        $selesai = $experience['tanggal_selesai'] ? date('M Y', strtotime((string)$experience['tanggal_selesai'])) : 'Sekarang';
        $row['pengalaman'][] = [
            'jenis' => (string)$experience['jenis'],
            'posisi' => (string)$experience['posisi'],
            'instansi' => (string)$experience['instansi'],
            'periode' => trim($mulai . ($mulai !== '' ? ' - ' : '') . $selesai),
            'tugas' => $experience['deskripsi'] ? preg_split('/\R+/', trim((string)$experience['deskripsi'])) : [],
        ];
    }

    $row['proyek'] = count($row['daftar_proyek']);
    $row['sertifikat'] = count($row['daftar_sertifikat']);
    $row['kontak'] = [
        'email' => trim((string) ($row['email'] ?? '')),
        'telepon' => trim((string) ($row['no_telepon'] ?? '')),
        'github' => trim((string) ($row['github_url'] ?? '')),
        'linkedin' => trim((string) ($row['linkedin_url'] ?? '')),
        'portfolio' => trim((string) ($row['portfolio_url'] ?? '')),
    ];

    return $row;
}

/**
 * Memperbarui nama, alamat, dan deskripsi profil mahasiswa.
 */
public function updatePersonal(int $userId, array $data): bool
{
    $this->pdo->beginTransaction();

    try {
        $stmt = $this->pdo->prepare('
            UPDATE users
            SET name = :nama
            WHERE id = :user_id
              AND role = :role
        ');

        $stmt->execute([
            'nama' => $data['nama_lengkap'],
            'user_id' => $userId,
            'role' => 'mahasiswa',
        ]);

        if ($stmt->rowCount() === 0) {
            // rowCount() bisa nol jika nama tidak berubah.
            $check = $this->pdo->prepare(
                'SELECT 1 FROM users
                 WHERE id = :user_id AND role = :role'
            );

            $check->execute([
                'user_id' => $userId,
                'role' => 'mahasiswa',
            ]);

            if (!$check->fetchColumn()) {
                throw new RuntimeException(
                    'Akun mahasiswa tidak ditemukan.'
                );
            }
        }

        $stmt = $this->pdo->prepare('
            UPDATE profil_mahasiswa
            SET
                alamat = :alamat,
                deskripsi = :deskripsi,
                updated_at = CURRENT_TIMESTAMP
            WHERE user_id = :user_id
        ');

        $stmt->execute([
            'alamat' => $data['alamat'] !== ''
                ? $data['alamat']
                : null,
            'deskripsi' => trim($data['deskripsi'] ?? '') !== ''
                ? trim($data['deskripsi'])
                : null,
            'user_id' => $userId,
        ]);

        $this->pdo->commit();

        return true;
    } catch (Throwable $e) {
        if ($this->pdo->inTransaction()) {
            $this->pdo->rollBack();
        }

        throw $e;
    }
}

    /**
     * Memperbarui email dan nomor telepon.
     */
    public function updateContact(int $userId, array $data): bool
    {
        $stmt = $this->pdo->prepare('
            UPDATE profil_mahasiswa
            SET
                email = :email,
                no_telepon = :no_telepon,
                github_url = :github_url,
                linkedin_url = :linkedin_url,
                portfolio_url = :portfolio_url,
                updated_at = CURRENT_TIMESTAMP
            WHERE user_id = :user_id
        ');

        return $stmt->execute([
            'email' => $data['email'] !== ''
                ? $data['email']
                : null,
            'no_telepon' => $data['no_telepon'] !== '' ? $data['no_telepon'] : null,
            'github_url' => $data['github_url'] !== '' ? $data['github_url'] : null,
            'linkedin_url' => $data['linkedin_url'] !== '' ? $data['linkedin_url'] : null,
            'portfolio_url' => $data['portfolio_url'] !== '' ? $data['portfolio_url'] : null,
            'user_id' => $userId,
        ]);
    }

    
/**
 * Memperbarui deskripsi profil mahasiswa.
 */
public function updateDescription(int $userId, string $deskripsi): bool
{
    $stmt = $this->pdo->prepare('
        UPDATE profil_mahasiswa
        SET
            deskripsi = :deskripsi,
            updated_at = CURRENT_TIMESTAMP
        WHERE user_id = :user_id
    ');

    $stmt->execute([
        'deskripsi' => $deskripsi !== '' ? $deskripsi : null,
        'user_id' => $userId,
    ]);

    return $stmt->rowCount() > 0;
}

    /**
     * Menyimpan daftar keterampilan.
     *
     * Nama keterampilan dibuat di katalog jika belum tersedia.
     * Keterampilan yang tidak lagi dikirim akan dilepas dari profil,
     * tetapi data katalog tidak dihapus.
     */
    public function updateSkills(int $profilId, array $skills): bool
    {
        $this->pdo->beginTransaction();

        try {
            $stmt = $this->pdo->prepare('
                SELECT id
                FROM keterampilan
                WHERE LOWER(nama) = LOWER(:nama)
                LIMIT 1
            ');

            $insertSkill = $this->pdo->prepare('
                INSERT INTO keterampilan (nama)
                VALUES (:nama)
                ON CONFLICT (nama) DO NOTHING
            ');

            $findSkill = $this->pdo->prepare('
                SELECT id
                FROM keterampilan
                WHERE LOWER(nama) = LOWER(:nama)
                LIMIT 1
            ');

            $linkSkill = $this->pdo->prepare('
                INSERT INTO mahasiswa_keterampilan
                    (mahasiswa_id, keterampilan_id)
                VALUES (:profil_id, :skill_id)
                ON CONFLICT (mahasiswa_id, keterampilan_id)
                DO NOTHING
            ');

            $skillIds = [];

            foreach ($skills as $skill) {
                $skill = trim((string) $skill);

                if ($skill === '') {
                    continue;
                }

                $stmt->execute(['nama' => $skill]);
                $skillId = $stmt->fetchColumn();

                if (!$skillId) {
                    $insertSkill->execute(['nama' => $skill]);

                    $findSkill->execute(['nama' => $skill]);
                    $skillId = $findSkill->fetchColumn();
                }

                if (!$skillId) {
                    throw new RuntimeException(
                        'Gagal menyimpan katalog keterampilan.'
                    );
                }

                $skillIds[] = (int) $skillId;
            }

            $skillIds = array_values(array_unique($skillIds));

            $this->pdo->prepare('
                DELETE FROM mahasiswa_keterampilan
                WHERE mahasiswa_id = :profil_id
            ')->execute(['profil_id' => $profilId]);

            foreach ($skillIds as $skillId) {
                $linkSkill->execute([
                    'profil_id' => $profilId,
                    'skill_id' => $skillId,
                ]);
            }

            $this->pdo->commit();

            return true;
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Memperbarui path foto profil.
     */
    public function updatePhoto(
        int $userId,
        ?string $fotoPath
    ): bool {
        $stmt = $this->pdo->prepare('
            UPDATE profil_mahasiswa
            SET
                foto_path = :foto_path,
                updated_at = CURRENT_TIMESTAMP
            WHERE user_id = :user_id
        ');

        return $stmt->execute([
            'foto_path' => $fotoPath,
            'user_id' => $userId,
        ]);
    }
    /**
     * Menyimpan metadata CV mahasiswa.
     */
    public function updateCv(
        int $userId,
        string $cvPath,
        string $namaAsli,
        string $mimeType,
        int $ukuranBytes
    ): bool {
        $stmt = $this->pdo->prepare('
            UPDATE profil_mahasiswa
            SET
                cv_path = :cv_path,
                cv_nama_asli = :cv_nama_asli,
                cv_mime_type = :cv_mime_type,
                cv_ukuran_bytes = :cv_ukuran_bytes,
                cv_updated_at = CURRENT_TIMESTAMP,
                updated_at = CURRENT_TIMESTAMP
            WHERE user_id = :user_id
        ');

        return $stmt->execute([
            'cv_path' => $cvPath,
            'cv_nama_asli' => $namaAsli,
            'cv_mime_type' => $mimeType,
            'cv_ukuran_bytes' => $ukuranBytes,
            'user_id' => $userId,
        ]);
    }

    /**
     * Mengambil CV publik berdasarkan slug nama mahasiswa.
     */
    public function getCvBySlug(string $slug): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                u.id AS user_id,
                u.name AS nama_lengkap,
                p.cv_path,
                p.cv_nama_asli,
                p.cv_mime_type,
                p.cv_ukuran_bytes,
                p.cv_updated_at
            FROM users u
            INNER JOIN profil_mahasiswa p
                ON p.user_id = u.id
            WHERE u.role = 'mahasiswa'
              AND LOWER(
                    REGEXP_REPLACE(
                        TRIM(u.name),
                        '[^a-zA-Z0-9]+',
                        '-',
                        'g'
                    )
                  ) = LOWER(:slug)
            LIMIT 1
        ");

        $stmt->execute(['slug' => trim($slug)]);
        $cv = $stmt->fetch(PDO::FETCH_ASSOC);

        return $cv ?: null;
    }

}