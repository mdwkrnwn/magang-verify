<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/AktivitasPengguna.php';

class Pengalaman
{
    public function syncAutomaticMagang(int $userId): void
    {
        global $pdo;
        $sql = "
            INSERT INTO pengalaman (mahasiswa_id, pendaftaran_id, jenis, posisi, instansi, lokasi, deskripsi, tanggal_mulai, tanggal_selesai, status_publikasi, is_otomatis)
            SELECT pm.id, pd.id, 'magang', fm.judul, m.nama_perusahaan, fm.lokasi_magang, fm.deskripsi, pen.tanggal_mulai, pen.tanggal_selesai, 'publik', TRUE
            FROM penempatan_magang pen
            JOIN pendaftaran_magang pd ON pd.id = pen.pendaftaran_id
            JOIN profil_mahasiswa pm ON pm.id = pd.mahasiswa_id
            JOIN formasi_magang fm ON fm.id = pd.formasi_id
            JOIN mitra m ON m.id = fm.mitra_id
            LEFT JOIN verifikasi_penyelesaian_magang v ON v.penempatan_id = pen.id
            WHERE pm.user_id = :user_id
              AND (
                    pen.status = 'selesai'
                    OR (pen.tanggal_selesai < CURRENT_DATE AND v.status = 'terverifikasi')
                  )
              AND NOT EXISTS (SELECT 1 FROM pengalaman e WHERE e.pendaftaran_id = pd.id)
        ";
        $stmt = $pdo->prepare($sql . ' RETURNING id');
        $stmt->execute(['user_id' => $userId]);
        $createdId = $stmt->fetchColumn();

        if ($createdId !== false) {
            (new AktivitasPengguna())->log(
                $userId,
                'Pengalaman magang dibuat otomatis',
                'Pengalaman magang dari proses magang yang telah selesai dan terverifikasi berhasil ditambahkan ke profil.',
                'pengalaman',
                'pengalaman',
                (int) $createdId
            );
        }
    }

    public function getAll(
        int $userId,
        array $filters = [],
        int $page = 1,
        int $perPage = 5
    ): array {
        $this->syncAutomaticMagang($userId);
        global $pdo;

        $page = max(1, $page);
        $perPage = max(1, $perPage);

        $where = [
            "(status_publikasi = 'publik' OR is_otomatis = TRUE)"
        ];
        $params = [];

        $keyword = trim((string) ($filters['q'] ?? ''));
        if ($keyword !== '') {
            $where[] = "(
                posisi ILIKE :keyword
                OR instansi ILIKE :keyword
                OR COALESCE(lokasi, '') ILIKE :keyword
                OR COALESCE(deskripsi, '') ILIKE :keyword
            )";
            $params['keyword'] = '%' . $keyword . '%';
        }

        $jenis = trim((string) ($filters['jenis'] ?? ''));
        $allowedJenis = [
            'magang',
            'pekerjaan',
            'organisasi',
            'freelance',
            'proyek',
            'lainnya'
        ];
        if (in_array($jenis, $allowedJenis, true)) {
            $where[] = 'jenis = :jenis';
            $params['jenis'] = $jenis;
        }

        $sumber = trim((string) ($filters['sumber'] ?? ''));
        if ($sumber === 'otomatis') {
            $where[] = 'is_otomatis = TRUE';
        } elseif ($sumber === 'manual') {
            $where[] = 'is_otomatis = FALSE';
        }

        $tahun = trim((string) ($filters['tahun'] ?? ''));
        if ($tahun !== '' && ctype_digit($tahun)) {
            $where[] = "EXTRACT(YEAR FROM COALESCE(tanggal_selesai, tanggal_mulai, created_at::date)) = :tahun";
            $params['tahun'] = (int) $tahun;
        }

        $whereSql = implode(' AND ', $where);

        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM (" . $this->getCombinedQuery() . ") pengalaman_gabungan WHERE {$whereSql}");
        $countParams = $params;
        $countParams['user_id'] = $userId;
        $countParams['user_id_auto'] = $userId;
        $countStmt->execute($countParams);
        $totalData = (int) $countStmt->fetchColumn();

        $totalPage = max(1, (int) ceil($totalData / $perPage));
        $page = min($page, $totalPage);
        $offset = ($page - 1) * $perPage;

        $stmt = $pdo->prepare(
            "SELECT * FROM (" . $this->getCombinedQuery() . ") pengalaman_gabungan
             WHERE {$whereSql}
             ORDER BY tanggal_selesai DESC NULLS LAST, tanggal_mulai DESC NULLS LAST, created_at DESC
             LIMIT :limit OFFSET :offset"
        );

        $queryParams = $params;
        $queryParams['user_id'] = $userId;
        $queryParams['user_id_auto'] = $userId;

        foreach ($queryParams as $key => $value) {
            $stmt->bindValue(
                ':' . $key,
                $value,
                is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR
            );
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'data' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total_data' => $totalData,
                'total_page' => $totalPage,
            ],
        ];
    }

    private function getCombinedQuery(): string
    {
        return "
            SELECT
                p.id,
                p.jenis,
                p.posisi,
                p.instansi,
                p.lokasi,
                p.deskripsi,
                p.tanggal_mulai,
                p.tanggal_selesai,
                p.status_publikasi,
                p.is_otomatis,
                p.pendaftaran_id,
                p.created_at,
                p.link_pendukung,
                p.bukti_path
            FROM pengalaman p
            JOIN profil_mahasiswa pm ON pm.id = p.mahasiswa_id
            WHERE pm.user_id = :user_id

            UNION ALL

            SELECT
                -pmg.id AS id,
                'magang' AS jenis,
                fm.judul AS posisi,
                m.nama_perusahaan AS instansi,
                fm.lokasi_magang AS lokasi,
                fm.deskripsi AS deskripsi,
                pmg.tanggal_mulai,
                pmg.tanggal_selesai,
                'publik' AS status_publikasi,
                TRUE AS is_otomatis,
                pendaftaran.id AS pendaftaran_id,
                pendaftaran.created_at,
                NULL::VARCHAR(500) AS link_pendukung,
                NULL::VARCHAR(255) AS bukti_path
            FROM penempatan_magang pmg
            JOIN pendaftaran_magang pendaftaran ON pendaftaran.id = pmg.pendaftaran_id
            JOIN profil_mahasiswa pm ON pm.id = pendaftaran.mahasiswa_id
            JOIN formasi_magang fm ON fm.id = pendaftaran.formasi_id
            JOIN mitra m ON m.id = fm.mitra_id
            LEFT JOIN verifikasi_penyelesaian_magang vpm ON vpm.penempatan_id = pmg.id
            WHERE pm.user_id = :user_id_auto
              AND pmg.status = 'selesai'
              AND (vpm.status = 'terverifikasi' OR vpm.id IS NULL)
              AND NOT EXISTS (
                  SELECT 1
                  FROM pengalaman px
                  WHERE px.pendaftaran_id = pendaftaran.id
              )
        ";
    }

    public function getJenisPengalaman(int $userId): array
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT DISTINCT jenis FROM (" . $this->getCombinedQuery() . ") data WHERE status_publikasi = 'publik' OR is_otomatis = TRUE ORDER BY jenis");
        $stmt->execute(['user_id' => $userId, 'user_id_auto' => $userId]);
        return array_values(array_filter($stmt->fetchAll(PDO::FETCH_COLUMN), static fn($value) => $value !== null && $value !== ''));
    }

    public function getTahunPengalaman(int $userId): array
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT DISTINCT EXTRACT(YEAR FROM COALESCE(tanggal_selesai, tanggal_mulai, created_at::date))::INT AS tahun FROM (" . $this->getCombinedQuery() . ") data WHERE (status_publikasi = 'publik' OR is_otomatis = TRUE) ORDER BY tahun DESC");
        $stmt->execute(['user_id' => $userId, 'user_id_auto' => $userId]);
        return array_map('strval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }


    /**
     * Mengambil detail pengalaman berdasarkan slug.
     * Mendukung pengalaman manual dan otomatis.
     *
     * Format slug:
     * nama-pengalaman-manual-12
     * nama-pengalaman-magang-12
     */
    public function getBySlug(string $slug, int $userId): ?array
    {
        global $pdo;

        // Ambil jenis sumber dan ID dari bagian akhir slug.
        if (!preg_match('/^(.*)-(manual|magang)-(\d+)$/', $slug, $matches)) {
            return null;
        }

        $source = $matches[2];
        $id = (int) $matches[3];

        if ($id <= 0) {
            return null;
        }

        // ID otomatis menggunakan angka negatif.
        $targetId = $source === 'magang' ? -$id : $id;

        $sql = "
        SELECT *
        FROM (" . $this->getCombinedQuery() . ") pengalaman_gabungan
        WHERE id = :target_id
        LIMIT 1
    ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'user_id' => $userId,
            'user_id_auto' => $userId,
            'target_id' => $targetId,
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        // Validasi agar slug sesuai dengan nama pengalaman.
        $namaSlug = $this->slugify($row['posisi'] ?? '');

        $canonicalSlug = $namaSlug . '-' . $source . '-' . $id;

        if ($slug !== $canonicalSlug) {
            return null;
        }

        return $row;
    }

    /**
     * Mengubah teks menjadi slug URL.
     */
    private function slugify(string $text): string
    {
        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);

        return trim($text, '-');
    }

    public function getById(int $id, int $userId): ?array
    {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT p.* FROM pengalaman p
            JOIN profil_mahasiswa pm ON pm.id = p.mahasiswa_id
            WHERE p.id = :id AND pm.user_id = :user_id AND p.is_otomatis = FALSE
            LIMIT 1
        ");
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function create(int $userId, array $data): int
    {
        global $pdo;
        $stmt = $pdo->prepare("
    INSERT INTO pengalaman (
        mahasiswa_id,
        jenis,
        posisi,
        instansi,
        lokasi,
        deskripsi,
        tanggal_mulai,
        tanggal_selesai,
        link_pendukung,
        bukti_path,
        status_publikasi,
        is_otomatis
    )
    SELECT
        id,
        :jenis,
        :posisi,
        :instansi,
        :lokasi,
        :deskripsi,
        :tanggal_mulai,
        :tanggal_selesai,
        :link_pendukung,
        :bukti_path,
        'publik',
        FALSE
    FROM profil_mahasiswa
    WHERE user_id = :user_id
    RETURNING id
");
        $stmt->execute([
            'jenis' => $data['jenis'],
            'posisi' => $data['posisi'],
            'instansi' => $data['instansi'],
            'lokasi' => $data['lokasi'] !== '' ? $data['lokasi'] : null,
            'deskripsi' => $data['deskripsi'] !== '' ? $data['deskripsi'] : null,
            'tanggal_mulai' => $data['tanggal_mulai'] !== '' ? $data['tanggal_mulai'] : null,
            'tanggal_selesai' => $data['tanggal_selesai'] !== '' ? $data['tanggal_selesai'] : null,
            'link_pendukung' => $data['link_pendukung'] !== ''
                ? $data['link_pendukung']
                : null,

            'bukti_path' => $data['bukti_path'] ?? null,
            'user_id' => $userId
        ]);
        return (int) $stmt->fetchColumn();
    }

    public function update(int $id, int $userId, array $data): bool
    {
        global $pdo;
        $stmt = $pdo->prepare("
            UPDATE pengalaman p SET
                jenis=:jenis, posisi=:posisi, instansi=:instansi, lokasi=:lokasi, deskripsi=:deskripsi,
                tanggal_mulai=:tanggal_mulai, tanggal_selesai=:tanggal_selesai, updated_at=CURRENT_TIMESTAMP,
                link_pendukung = :link_pendukung, bukti_path = :bukti_path
            FROM profil_mahasiswa pm
            WHERE p.id=:id AND p.mahasiswa_id=pm.id AND pm.user_id=:user_id AND p.is_otomatis=FALSE
        ");
        return $stmt->execute([
            'id' => $id,
            'user_id' => $userId,
            'jenis' => $data['jenis'],
            'posisi' => $data['posisi'],
            'instansi' => $data['instansi'],
            'lokasi' => $data['lokasi'] !== '' ? $data['lokasi'] : null,
            'deskripsi' => $data['deskripsi'] !== '' ? $data['deskripsi'] : null,
            'tanggal_mulai' => $data['tanggal_mulai'] !== '' ? $data['tanggal_mulai'] : null,
            'tanggal_selesai' => $data['tanggal_selesai'] !== '' ? $data['tanggal_selesai'] : null,
            'link_pendukung' => $data['link_pendukung'] !== ''
                ? $data['link_pendukung']
                : null,

            'bukti_path' => $data['bukti_path'] ?? null,
        ]);
    }

    public function delete(int $id, int $userId): bool
    {
        global $pdo;
        $stmt = $pdo->prepare("
            DELETE FROM pengalaman p USING profil_mahasiswa pm
            WHERE p.id=:id AND p.mahasiswa_id=pm.id AND pm.user_id=:user_id AND p.is_otomatis=FALSE
        ");
        return $stmt->execute(['id' => $id, 'user_id' => $userId]);
    }
}
