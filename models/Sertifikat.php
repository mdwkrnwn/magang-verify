<?php

require_once __DIR__ . '/../config/database.php';

class Sertifikat
{
    public function getVerificationQueue(string $status = 'belum_terverifikasi', int $page = 1, int $perPage = 20): array
    {
        global $pdo;
        if (!in_array($status, ['belum_terverifikasi', 'dalam_peninjauan', 'perlu_perbaikan'], true)) {
            throw new InvalidArgumentException('Filter status sertifikat tidak valid.');
        }
        $page = max(1, $page);
        $perPage = max(1, min($perPage, 100));
        $count = $pdo->prepare('SELECT COUNT(*) FROM sertifikat WHERE status_verifikasi = :status');
        $count->execute(['status' => $status]);
        $total = (int) $count->fetchColumn();
        $stmt = $pdo->prepare(
            'SELECT s.id, s.user_id, s.nama, s.penerbit, s.tanggal_terbit,
                    s.tanggal_kedaluwarsa, s.nomor_sertifikat, s.deskripsi,
                    s.file_path, s.tautan, s.status_verifikasi, s.catatan_verifikasi,
                    s.created_at, u.name AS mahasiswa_nama, pm.nim
             FROM sertifikat s
             INNER JOIN users u ON u.id = s.user_id
             LEFT JOIN profil_mahasiswa pm ON pm.user_id = u.id
             WHERE s.status_verifikasi = :status
             ORDER BY s.created_at ASC, s.id ASC
             LIMIT :limit OFFSET :offset'
        );
        $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', ($page - 1) * $perPage, PDO::PARAM_INT);
        $stmt->execute();
        return ['items' => $stmt->fetchAll(PDO::FETCH_ASSOC), 'total' => $total, 'page' => $page, 'per_page' => $perPage];
    }

    public function getForVerification(int $id): ?array
    {
        global $pdo;
        $stmt = $pdo->prepare(
            'SELECT s.*, u.name AS mahasiswa_nama, pm.nim
             FROM sertifikat s
             INNER JOIN users u ON u.id = s.user_id
             LEFT JOIN profil_mahasiswa pm ON pm.user_id = u.id
             WHERE s.id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function verify(int $id, int $actorId, string $decision, string $note): void
    {
        global $pdo;
        if (!in_array($decision, ['terverifikasi', 'perlu_perbaikan', 'ditolak'], true)) {
            throw new InvalidArgumentException('Keputusan verifikasi tidak valid.');
        }
        $note = trim($note);
        if ($decision !== 'terverifikasi' && $note === '') {
            throw new InvalidArgumentException('Catatan wajib diisi saat sertifikat perlu diperbaiki atau ditolak.');
        }
        if (mb_strlen($note) > 5000) {
            throw new InvalidArgumentException('Catatan maksimal 5.000 karakter.');
        }
        $pdo->beginTransaction();
        try {
            $update = $pdo->prepare(
                "UPDATE sertifikat
                 SET status_verifikasi = :decision, catatan_verifikasi = :note,
                     diverifikasi_oleh = :actor_id, diverifikasi_pada = CURRENT_TIMESTAMP,
                     updated_at = CURRENT_TIMESTAMP
                 WHERE id = :id AND status_verifikasi IN ('belum_terverifikasi', 'dalam_peninjauan', 'perlu_perbaikan')"
            );
            $update->execute([
                'decision' => $decision,
                'note' => $note !== '' ? $note : null,
                'actor_id' => $actorId,
                'id' => $id,
            ]);
            if ($update->rowCount() !== 1) {
                throw new RuntimeException('Sertifikat tidak ditemukan atau sudah diputuskan.');
            }
            $owner = $pdo->prepare('SELECT user_id FROM sertifikat WHERE id = :id');
            $owner->execute(['id' => $id]);
            $ownerId = (int) $owner->fetchColumn();
            $notification = $pdo->prepare(
                "INSERT INTO notifikasi (user_id, judul, pesan, jenis, referensi_tabel, referensi_id, tautan)
                 VALUES (:user_id, :judul, :pesan, 'sertifikat', 'sertifikat', :id, :tautan)"
            );
            $notification->execute([
                'user_id' => $ownerId,
                'judul' => $decision === 'terverifikasi' ? 'Sertifikat terverifikasi' : 'Sertifikat perlu diperbaiki',
                'pesan' => $decision === 'terverifikasi' ? 'Sertifikat Anda telah diverifikasi.' : 'Sertifikat Anda ditolak. Catatan: ' . $note,
                'id' => $id,
                'tautan' => '/dashboard/mahasiswa/sertifikat',
            ]);
            $pdo->commit();
        } catch (Throwable $exception) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $exception;
        }
    }

    public function getVerificationFile(int $id): ?array
    {
        global $pdo;
        $stmt = $pdo->prepare('SELECT id, file_path FROM sertifikat WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
    public function resubmit(int $id, int $userId): void
    {
        global $pdo;
        $stmt = $pdo->prepare(
            "UPDATE sertifikat SET status_verifikasi = 'belum_terverifikasi',
             catatan_verifikasi = NULL, diverifikasi_oleh = NULL, diverifikasi_pada = NULL,
             updated_at = CURRENT_TIMESTAMP
             WHERE id = :id AND user_id = :user_id AND status_verifikasi = 'ditolak'"
        );
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
        if ($stmt->rowCount() !== 1) {
            throw new RuntimeException('Sertifikat tidak ditemukan atau tidak dapat diajukan ulang.');
        }
    }

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
                user_id, nama, slug, jenis, penerbit, tanggal_terbit,
                tanggal_kedaluwarsa, nomor_sertifikat, deskripsi, tautan, file_path
            ) VALUES (
                :user_id, :nama, :slug, :jenis, :penerbit, :tanggal_terbit,
                :tanggal_kedaluwarsa, :nomor_sertifikat, :deskripsi, :tautan, :file_path
            )
        ");
        $stmt->execute([
            'user_id' => $userId,
            'nama' => trim((string) $data['nama']),
            'slug' => $slug,
            'jenis' => $this->nullableValue($data['jenis'] ?? null),
            'penerbit' => trim((string) $data['penerbit']),
            'tanggal_terbit' => $this->nullableValue($data['tanggal_terbit'] ?? null),
            'tanggal_kedaluwarsa' => $this->nullableValue($data['tanggal_kedaluwarsa'] ?? null),
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
            SELECT slug, status_verifikasi FROM sertifikat
            WHERE id = :id AND user_id = :user_id
              AND status_verifikasi IN ('belum_terverifikasi', 'ditolak', 'perlu_perbaikan')
            LIMIT 1
        ");
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$existing) {
            throw new RuntimeException('Sertifikat tidak ditemukan atau tidak dapat diedit.');
        }

        $previousStatus = (string) $existing['status_verifikasi'];
        $slug = $this->generateUniqueSlug((string) $data['nama'], (string) $existing['slug']);
        $sql = "
            UPDATE sertifikat SET
                nama = :nama,
                slug = :slug,
                jenis = :jenis,
                penerbit = :penerbit,
                tanggal_terbit = :tanggal_terbit,
                tanggal_kedaluwarsa = :tanggal_kedaluwarsa,
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
            'jenis' => $this->nullableValue($data['jenis'] ?? null),
            'penerbit' => trim((string) $data['penerbit']),
            'tanggal_terbit' => $this->nullableValue($data['tanggal_terbit'] ?? null),
            'tanggal_kedaluwarsa' => $this->nullableValue($data['tanggal_kedaluwarsa'] ?? null),
            'nomor_sertifikat' => $this->nullableValue($data['nomor_sertifikat'] ?? null),
            'deskripsi' => $this->nullableValue($data['deskripsi'] ?? null),
            'tautan' => $this->nullableValue($data['tautan'] ?? null),
        ];

        if (array_key_exists('file_path', $data)) {
            $sql .= ', file_path = :file_path';
            $params['file_path'] = $this->nullableValue($data['file_path']);
        }

        if (in_array($previousStatus, ['ditolak', 'perlu_perbaikan'], true)) {
            $sql .= ", status_verifikasi = 'belum_terverifikasi',
                catatan_verifikasi = NULL, diverifikasi_oleh = NULL, diverifikasi_pada = NULL";
        }
        $sql .= ' WHERE id = :id AND user_id = :user_id AND status_verifikasi = :previous_status';
        $params['previous_status'] = $previousStatus;
        $update = $pdo->prepare($sql);
        $update->execute($params);

        if ($update->rowCount() < 1) {
            // rowCount bisa nol untuk data pending yang tidak berubah; status tetap harus sama.
            $check = $pdo->prepare('SELECT status_verifikasi FROM sertifikat WHERE id = :id AND user_id = :user_id');
            $check->execute(['id' => $id, 'user_id' => $userId]);
            if ($check->fetchColumn() !== $previousStatus) {
                throw new RuntimeException('Sertifikat berubah atau tidak dapat diperbarui.');
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
            'perlu_perbaikan' => 'Perlu Perbaikan',
        ];
        $filePath = (string) ($item['file_path'] ?? '');
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        return [
            'id' => (int) $item['id'],
            'user_id' => (int) $item['user_id'],
            'nama' => (string) $item['nama'],
            'slug' => (string) $item['slug'],
            'jenis' => $item['jenis'] ?? '',
            'penerbit' => (string) $item['penerbit'],
            'tanggal_terbit' => $item['tanggal_terbit'] ?? '',
            'tanggal_kedaluwarsa' => $item['tanggal_kedaluwarsa'] ?? '',
            'nomor_sertifikat' => $item['nomor_sertifikat'] ?? '',
            'deskripsi' => $item['deskripsi'] ?? '',
            'gambar' => basename($filePath),
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
