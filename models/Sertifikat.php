<?php

require_once __DIR__ . '/../config/database.php';

class Sertifikat
{
    /** Ambil sertifikat milik mahasiswa dengan filter dan pagination. */
    public function getAllSertifikat(
        int $userId,
        array $filters = [],
        int $page = 1,
        int $perPage = 6
    ): array {
        global $pdo;

        $page = max(1, $page);
        $perPage = max(1, $perPage);
        $where = ['user_id = :user_id'];
        $params = ['user_id' => $userId];

        $keyword = trim((string) ($filters['q'] ?? ''));
        if ($keyword !== '') {
            $where[] = "(
                nama ILIKE :keyword OR
                penerbit ILIKE :keyword OR
                COALESCE(nomor_sertifikat, '') ILIKE :keyword OR
                COALESCE(deskripsi, '') ILIKE :keyword
            )";
            $params['keyword'] = '%' . $keyword . '%';
        }

        $tahun = trim((string) ($filters['tahun'] ?? ''));
        if ($tahun !== '' && ctype_digit($tahun)) {
            $where[] = 'EXTRACT(YEAR FROM tanggal_terbit) = :tahun';
            $params['tahun'] = (int) $tahun;
        }

        $status = trim((string) ($filters['status'] ?? ''));
        $allowedStatus = [
            'belum_terverifikasi', 'dalam_peninjauan',
            'terverifikasi', 'ditolak',
        ];
        if (in_array($status, $allowedStatus, true)) {
            $where[] = 'status_verifikasi = :status';
            $params['status'] = $status;
        }

        $whereSql = implode(' AND ', $where);

        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM sertifikat WHERE {$whereSql}");
        $countStmt->execute($params);
        $totalData = (int) $countStmt->fetchColumn();
        $totalPage = max(1, (int) ceil($totalData / $perPage));
        $page = min($page, $totalPage);
        $offset = ($page - 1) * $perPage;

        $stmt = $pdo->prepare("
            SELECT *
            FROM sertifikat
            WHERE {$whereSql}
            ORDER BY created_at DESC, id DESC
            LIMIT :limit OFFSET :offset
        ");
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $data = array_map([$this, 'formatSertifikat'], $stmt->fetchAll(PDO::FETCH_ASSOC));

        return [
            'data' => $data,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total_data' => $totalData,
                'total_page' => $totalPage,
            ],
        ];
    }

    /** Ambil sertifikat berdasarkan slug dan pemiliknya. */
    public function getSertifikatBySlug(string $slug, int $userId): ?array
    {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM sertifikat WHERE slug = :slug AND user_id = :user_id LIMIT 1');
        $stmt->execute(['slug' => $slug, 'user_id' => $userId]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        return $item ? $this->formatSertifikat($item) : null;
    }

    /** Daftar tahun sertifikat milik mahasiswa untuk pilihan filter. */
    public function getTahunSertifikat(int $userId): array
    {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT DISTINCT EXTRACT(YEAR FROM tanggal_terbit)::INT AS tahun
            FROM sertifikat
            WHERE user_id = :user_id AND tanggal_terbit IS NOT NULL
            ORDER BY tahun DESC
        ");
        $stmt->execute(['user_id' => $userId]);

        return array_map('strval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }

    /** Simpan sertifikat baru; mengembalikan slug yang dibuat. */
    public function create(int $userId, array $data): string
    {
        global $pdo;
        $slug = $this->generateUniqueSlug((string) $data['nama']);

        $stmt = $pdo->prepare("
            INSERT INTO sertifikat (
                user_id, nama, slug, penerbit, tanggal_terbit,
                nomor_sertifikat, deskripsi, tautan, file_path
            ) VALUES (
                :user_id, :nama, :slug, :penerbit, :tanggal_terbit,
                :nomor_sertifikat, :deskripsi, :tautan, :file_path
            )
        ");
        $stmt->execute([
            'user_id' => $userId,
            'nama' => trim((string) $data['nama']),
            'slug' => $slug,
            'penerbit' => trim((string) $data['penerbit']),
            'tanggal_terbit' => $this->nullableValue($data['tanggal_terbit'] ?? null),
            'nomor_sertifikat' => $this->nullableValue($data['nomor_sertifikat'] ?? null),
            'deskripsi' => $this->nullableValue($data['deskripsi'] ?? null),
            'tautan' => $this->nullableValue($data['tautan'] ?? null),
            'file_path' => $this->nullableValue($data['file_path'] ?? null),
        ]);

        return $slug;
    }

    /** Perbarui sertifikat milik mahasiswa, hanya jika belum diverifikasi. */
    public function update(int $id, int $userId, array $data): string
    {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT slug FROM sertifikat
            WHERE id = :id AND user_id = :user_id
              AND status_verifikasi = 'belum_terverifikasi'
            LIMIT 1
        ");
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$existing) {
            throw new RuntimeException('Sertifikat tidak ditemukan atau tidak dapat diedit.');
        }

        $slug = $this->generateUniqueSlug((string) $data['nama'], (string) $existing['slug']);
        $sql = "
            UPDATE sertifikat SET
                nama = :nama,
                slug = :slug,
                penerbit = :penerbit,
                tanggal_terbit = :tanggal_terbit,
                nomor_sertifikat = :nomor_sertifikat,
                deskripsi = :deskripsi,
                tautan = :tautan,
                updated_at = CURRENT_TIMESTAMP
        ";
        $params = [
            'id' => $id,
            'user_id' => $userId,
            'nama' => trim((string) $data['nama']),
            'slug' => $slug,
            'penerbit' => trim((string) $data['penerbit']),
            'tanggal_terbit' => $this->nullableValue($data['tanggal_terbit'] ?? null),
            'nomor_sertifikat' => $this->nullableValue($data['nomor_sertifikat'] ?? null),
            'deskripsi' => $this->nullableValue($data['deskripsi'] ?? null),
            'tautan' => $this->nullableValue($data['tautan'] ?? null),
        ];

        if (array_key_exists('file_path', $data)) {
            $sql .= ', file_path = :file_path';
            $params['file_path'] = $this->nullableValue($data['file_path']);
        }

        $sql .= " WHERE id = :id AND user_id = :user_id AND status_verifikasi = 'belum_terverifikasi'";
        $update = $pdo->prepare($sql);
        $update->execute($params);

        if ($update->rowCount() < 1) {
            // rowCount bisa 0 jika nilainya sama; data tetap dianggap berhasil jika record masih ada.
            $check = $pdo->prepare('SELECT 1 FROM sertifikat WHERE id = :id AND user_id = :user_id');
            $check->execute(['id' => $id, 'user_id' => $userId]);
            if (!$check->fetchColumn()) {
                throw new RuntimeException('Sertifikat gagal diperbarui.');
            }
        }

        return $slug;
    }

    /** Hapus sertifikat yang belum diverifikasi berdasarkan slug dan pemilik. */
    public function deleteBySlug(string $slug, int $userId): bool
    {
        global $pdo;
        $stmt = $pdo->prepare("
            DELETE FROM sertifikat
            WHERE slug = :slug AND user_id = :user_id
              AND status_verifikasi = 'belum_terverifikasi'
        ");
        $stmt->execute(['slug' => $slug, 'user_id' => $userId]);

        return $stmt->rowCount() > 0;
    }

    /** Ambil path file untuk proses unduh, dengan validasi kepemilikan. */
    public function getFilePathBySlug(string $slug, int $userId): ?string
    {
        global $pdo;
        $stmt = $pdo->prepare('SELECT file_path FROM sertifikat WHERE slug = :slug AND user_id = :user_id LIMIT 1');
        $stmt->execute(['slug' => $slug, 'user_id' => $userId]);
        $path = $stmt->fetchColumn();

        return $path === false || $path === null || $path === '' ? null : (string) $path;
    }

    private function formatSertifikat(array $item): array
    {
        $status = (string) ($item['status_verifikasi'] ?? 'belum_terverifikasi');
        $labels = [
            'belum_terverifikasi' => 'Belum Terverifikasi',
            'dalam_peninjauan' => 'Dalam Peninjauan',
            'terverifikasi' => 'Terverifikasi',
            'ditolak' => 'Ditolak',
        ];
        $filePath = (string) ($item['file_path'] ?? '');
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        return [
            'id' => (int) $item['id'],
            'user_id' => (int) $item['user_id'],
            'nama' => (string) $item['nama'],
            'slug' => (string) $item['slug'],
            'penerbit' => (string) $item['penerbit'],
            'tanggal_terbit' => $item['tanggal_terbit'] ?? '',
            'nomor_sertifikat' => $item['nomor_sertifikat'] ?? '',
            'deskripsi' => $item['deskripsi'] ?? '',
            'gambar' => $filePath,
            'file_path' => $filePath,
            'tipe_file' => in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true)
                ? 'gambar'
                : ($extension === 'pdf' ? 'pdf' : ($extension === '' ? '' : 'lainnya')),
            'tautan' => ['sertifikat' => (string) ($item['tautan'] ?? '')],
            'verifikasi' => [
                'status' => $status,
                'label' => $labels[$status] ?? 'Belum Terverifikasi',
                'catatan' => $item['catatan_verifikasi'] ?? '',
                'diverifikasi_oleh' => $item['diverifikasi_oleh'] ?? null,
            ],
            'created_at' => $item['created_at'] ?? null,
            'updated_at' => $item['updated_at'] ?? null,
        ];
    }

    private function generateUniqueSlug(string $nama, ?string $excludeSlug = null): string
    {
        global $pdo;
        $base = function_exists('slugify') ? slugify($nama) : strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $nama), '-'));
        if ($base === '') {
            $base = 'sertifikat';
        }

        $slug = $base;
        $counter = 2;
        while (true) {
            $sql = 'SELECT 1 FROM sertifikat WHERE slug = :slug';
            $params = ['slug' => $slug];
            if ($excludeSlug !== null) {
                $sql .= ' AND slug <> :exclude_slug';
                $params['exclude_slug'] = $excludeSlug;
            }
            $sql .= ' LIMIT 1';
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            if (!$stmt->fetchColumn()) {
                return $slug;
            }
            $slug = $base . '-' . $counter++;
        }
    }

    private function nullableValue($value): ?string
    {
        if ($value === null) {
            return null;
        }
        $value = trim((string) $value);
        return $value === '' ? null : $value;
    }
}
