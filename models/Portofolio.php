
<?php

require_once __DIR__ . '/../config/database.php';

class Portofolio
{
    /**
     * Mengambil seluruh portofolio milik mahasiswa.
     */
    public function getAllPortofolio(int $userId): array
    {
        global $pdo;

        $sql = "
            SELECT
                p.*,
                u.name AS nama_mahasiswa
            FROM portofolios p
            JOIN users u ON u.id = p.user_id
            WHERE p.user_id = :user_id
            ORDER BY p.created_at DESC, p.id DESC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);

        $portofolios = $stmt->fetchAll();

        foreach ($portofolios as &$item) {
            $item = $this->formatPortofolio($item);
        }
        unset($item);

        return $portofolios;
    }

    /**
     * Mengambil satu portofolio berdasarkan slug
     * dan memastikan portofolio milik mahasiswa terkait.
     */
    public function getPortofolioBySlug(
        string $slug,
        int $userId
    ): ?array {
        global $pdo;

        $sql = "
            SELECT *
            FROM portofolios
            WHERE slug = :slug
              AND user_id = :user_id
            LIMIT 1
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'slug' => $slug,
            'user_id' => $userId,
        ]);

        $item = $stmt->fetch();

        if (!$item) {
            return null;
        }

        return $this->formatPortofolio($item);
    }

    /**
     * Mengubah format data database agar kompatibel
     * dengan struktur data yang digunakan oleh view.
     */
    private function formatPortofolio(array $item): array
    {
        global $pdo;

        $stmt = $pdo->prepare("
            SELECT nama_teknologi
            FROM portofolio_teknologi
            WHERE portofolio_id = :portofolio_id
            ORDER BY id ASC
        ");

        $stmt->execute([
            'portofolio_id' => $item['id'],
        ]);

        $teknologi = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $status = $item['status_verifikasi'];

        $labelStatus = [
            'belum_terverifikasi' => 'Belum Terverifikasi',
            'dalam_peninjauan' => 'Dalam Peninjauan',
            'terverifikasi' => 'Terverifikasi',
            'ditolak' => 'Ditolak',
        ];

        return [
            'id' => (int) $item['id'],
            'user_id' => (int) $item['user_id'],
            'slug' => $item['slug'],
            'judul' => $item['judul'],
            'deskripsi' => $item['deskripsi'],
            'gambar' => $item['gambar_path'] ?? '',
            'teknologi' => $teknologi,
            'peran' => $item['peran'] ?? '',
            'tahun' => $item['tahun'] !== null
                ? (string) $item['tahun']
                : '',
            'tautan' => [
                'github' => $item['tautan_github'] ?? '',
                'demo' => $item['tautan_demo'] ?? '',
            ],
            'verifikasi' => [
                'status' => $status,
                'label' => $labelStatus[$status] ?? $status,
            ],
        ];
    }


    /**
     * Membuat slug unik untuk portofolio.
     */
    private function generateUniqueSlug(string $judul, ?int $excludeId = null): string
    {
        global $pdo;

        $slugDasar = function_exists('slugify')
            ? slugify($judul)
            : trim(
                preg_replace('/-+/', '-', preg_replace(
                    '/[^a-z0-9]+/',
                    '-',
                    strtolower(iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $judul) ?: $judul)
                )),
                '-'
            );

        if ($slugDasar === '') {
            $slugDasar = 'portofolio';
        }

        $slug = $slugDasar;
        $counter = 2;

        while (true) {
            $sql = 'SELECT id FROM portofolios WHERE slug = :slug';
            $params = ['slug' => $slug];

            if ($excludeId !== null) {
                $sql .= ' AND id <> :exclude_id';
                $params['exclude_id'] = $excludeId;
            }

            $sql .= ' LIMIT 1';

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            if (!$stmt->fetch()) {
                return $slug;
            }

            $slug = $slugDasar . '-' . $counter;
            $counter++;
        }
    }

    /**
     * Menambahkan portofolio beserta daftar teknologinya.
     * Mengembalikan slug yang tersimpan.
     */
    public function create(int $userId, array $data): string
    {
        global $pdo;

        $slug = $this->generateUniqueSlug($data['judul']);

        $pdo->beginTransaction();

        try {
            $sql = "
                INSERT INTO portofolios (
                    user_id,
                    judul,
                    slug,
                    deskripsi,
                    gambar_path,
                    peran,
                    tahun,
                    tautan_github,
                    tautan_demo,
                    status_verifikasi
                ) VALUES (
                    :user_id,
                    :judul,
                    :slug,
                    :deskripsi,
                    :gambar_path,
                    :peran,
                    :tahun,
                    :github,
                    :demo,
                    'belum_terverifikasi'
                )
                RETURNING id
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'user_id' => $userId,
                'judul' => $data['judul'],
                'slug' => $slug,
                'deskripsi' => $data['deskripsi'],
                'gambar_path' => $data['gambar_path'] ?? null,
                'peran' => $data['peran'],
                'tahun' => $data['tahun'],
                'github' => $data['github'] !== '' ? $data['github'] : null,
                'demo' => $data['demo'] !== '' ? $data['demo'] : null,
            ]);

            $portofolioId = (int) $stmt->fetchColumn();

            $this->saveTechnologies($portofolioId, $data['teknologi'] ?? []);

            $pdo->commit();

            return $slug;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Mengubah portofolio milik mahasiswa yang sedang login.
     * Mengembalikan slug terbaru.
     */
    public function update(int $id, int $userId, array $data): string
    {
        global $pdo;

        $slug = $this->generateUniqueSlug($data['judul'], $id);

        $pdo->beginTransaction();

        try {
            $sql = "
                UPDATE portofolios
                SET
                    judul = :judul,
                    slug = :slug,
                    deskripsi = :deskripsi,
                    gambar_path = COALESCE(:gambar_path, gambar_path),
                    peran = :peran,
                    tahun = :tahun,
                    tautan_github = :github,
                    tautan_demo = :demo,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id
                  AND user_id = :user_id
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'judul' => $data['judul'],
                'slug' => $slug,
                'deskripsi' => $data['deskripsi'],
                'gambar_path' => $data['gambar_path'] ?? null,
                'peran' => $data['peran'],
                'tahun' => $data['tahun'],
                'github' => $data['github'] !== '' ? $data['github'] : null,
                'demo' => $data['demo'] !== '' ? $data['demo'] : null,
                'id' => $id,
                'user_id' => $userId,
            ]);

            if ($stmt->rowCount() === 0) {
                // Bedakan data yang tidak dimiliki user dari update tanpa perubahan.
                $check = $pdo->prepare(
                    'SELECT id FROM portofolios WHERE id = :id AND user_id = :user_id'
                );
                $check->execute([
                    'id' => $id,
                    'user_id' => $userId,
                ]);

                if (!$check->fetch()) {
                    throw new RuntimeException('Portofolio tidak ditemukan.');
                }
            }

            // Ganti daftar teknologi dengan data terbaru dari form.
            $deleteTech = $pdo->prepare(
                'DELETE FROM portofolio_teknologi WHERE portofolio_id = :id'
            );
            $deleteTech->execute(['id' => $id]);

            $this->saveTechnologies($id, $data['teknologi'] ?? []);

            $pdo->commit();

            return $slug;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Menyimpan daftar teknologi untuk sebuah portofolio.
     */
    private function saveTechnologies(int $portofolioId, array $technologies): void
    {
        global $pdo;

        $stmt = $pdo->prepare("
            INSERT INTO portofolio_teknologi (
                portofolio_id,
                nama_teknologi
            ) VALUES (
                :portofolio_id,
                :nama_teknologi
            )
            ON CONFLICT (portofolio_id, nama_teknologi) DO NOTHING
        ");

        $technologies = array_unique(array_filter(
            array_map('trim', $technologies),
            static fn($item) => $item !== ''
        ));

        foreach ($technologies as $technology) {
            $stmt->execute([
                'portofolio_id' => $portofolioId,
                'nama_teknologi' => $technology,
            ]);
        }
    }

    /**
     * Menghapus portofolio milik mahasiswa berdasarkan slug.
     */
    public function deleteBySlug(string $slug, int $userId): bool
    {
        global $pdo;

        $stmt = $pdo->prepare("
            DELETE FROM portofolios
            WHERE slug = :slug
              AND user_id = :user_id
        ");

        $stmt->execute([
            'slug' => $slug,
            'user_id' => $userId,
        ]);

        // Baris teknologi ikut terhapus melalui ON DELETE CASCADE.
        return $stmt->rowCount() > 0;
    }
}
