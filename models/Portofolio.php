
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
            pm.user_id AS user_id,
            u.name AS nama_mahasiswa
        FROM portofolios p
        JOIN profil_mahasiswa pm ON pm.id = p.mahasiswa_id
        JOIN users u ON u.id = pm.user_id
        WHERE pm.user_id = :user_id
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
        SELECT
            p.*,
            pm.user_id AS user_id,
            u.name AS nama_mahasiswa
        FROM portofolios p
        JOIN profil_mahasiswa pm ON pm.id = p.mahasiswa_id
        JOIN users u ON u.id = pm.user_id
        WHERE p.slug = :slug
          AND pm.user_id = :user_id
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
            ORDER BY nama_teknologi ASC
        ");

        $stmt->execute([
            'portofolio_id' => $item['id'],
        ]);

        $teknologi = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $status = $item['status_verifikasi'];

        $labelStatus = [
            'belum_diverifikasi' => 'Belum Terverifikasi',
            'dalam_peninjauan' => 'Dalam Peninjauan',
            'terverifikasi' => 'Terverifikasi',
            'ditolak' => 'Ditolak',
            'perlu_perbaikan' => 'Perlu Perbaikan',
        ];

        return [
            'id' => (int) $item['id'],
            'user_id' => (int) $item['user_id'],
            'slug' => $item['slug'],
            'judul' => $item['judul'],
            'deskripsi' => $item['deskripsi'],
            'gambar' => $item['gambar_sampul'] ?? '',
            'teknologi' => $teknologi,
            'peran' => $item['peran'] ?? '',
            'tahun' => !empty($item['tanggal_perolehan'])
            ? date('Y', strtotime($item['tanggal_perolehan']))
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

    // Cari profil mahasiswa berdasarkan user yang sedang login.
    $stmtMahasiswa = $pdo->prepare("
        SELECT id
        FROM profil_mahasiswa
        WHERE user_id = :user_id
        LIMIT 1
    ");

    $stmtMahasiswa->execute(['user_id' => $userId]);
    $mahasiswaId = $stmtMahasiswa->fetchColumn();

    if ($mahasiswaId === false) {
        throw new RuntimeException(
            'Profil mahasiswa tidak ditemukan.'
        );
    }

    $slug = $this->generateUniqueSlug($data['judul']);

    $pdo->beginTransaction();

    try {
        

        $sql = "
        INSERT INTO portofolios (
            mahasiswa_id,
            judul,
            slug,
            jenis,
            deskripsi,
            tanggal_perolehan,
            tautan,
            tautan_github,
            tautan_demo,
            gambar_sampul,
            peran,
            status_verifikasi,
            status_publikasi
        ) VALUES (
            :mahasiswa_id,
            :judul,
            :slug,
            'proyek',
            :deskripsi,
            make_date(:tahun, 1, 1),
            :tautan,
            :github,
            :demo,
            :gambar_sampul,
            :peran,
            'belum_diverifikasi',
            'draft'
        )
        RETURNING id
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'mahasiswa_id' => (int) $mahasiswaId,
        'judul' => $data['judul'],
        'slug' => $slug,
        'deskripsi' => $data['deskripsi'],
        'tahun' => (int) $data['tahun'],
        'tautan' => ($data['github'] !== '')
            ? $data['github']
            : (($data['demo'] !== '') ? $data['demo'] : null),
        'github' => $data['github'] !== ''
            ? $data['github']
            : null,
        'demo' => $data['demo'] !== ''
            ? $data['demo']
            : null,
        'gambar_sampul' => $data['gambar_path'] ?? null,
        'peran' => $data['peran'],
    ]);

        $portofolioId = (int) $stmt->fetchColumn();

        $this->saveTechnologies(
            $portofolioId,
            $data['teknologi'] ?? []
        );

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
    gambar_sampul = COALESCE(:gambar_sampul, gambar_sampul),
    peran = :peran,
    tanggal_perolehan = make_date(:tahun, 1, 1),
    tautan = :tautan,
    tautan_github = :github,
    tautan_demo = :demo,
    updated_at = CURRENT_TIMESTAMP
WHERE id = :id
  AND mahasiswa_id = (
      SELECT id
      FROM profil_mahasiswa
      WHERE user_id = :user_id
  )
  AND status_verifikasi = 'belum_diverifikasi'
";
            
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'judul' => $data['judul'],
    'slug' => $slug,
    'deskripsi' => $data['deskripsi'],
    'gambar_sampul' => $data['gambar_path'] ?? null,
    'peran' => $data['peran'],
    'tahun' => $data['tahun'],
    'tautan' => ($data['github'] !== '' ? $data['github'] : null)
        ?? ($data['demo'] !== '' ? $data['demo'] : null),
    'github' => $data['github'] !== '' ? $data['github'] : null,
    'demo' => $data['demo'] !== '' ? $data['demo'] : null,
    'id' => $id,
    'user_id' => $userId,
]);

            
if ($stmt->rowCount() === 0) {
    $check = $pdo->prepare("
SELECT p.status_verifikasi
FROM portofolios p
JOIN profil_mahasiswa pm ON pm.id = p.mahasiswa_id
WHERE p.id = :id
  AND pm.user_id = :user_id
");

    $check->execute([
        'id' => $id,
        'user_id' => $userId,
    ]);

    $portofolio = $check->fetch();

    if (!$portofolio) {
        throw new RuntimeException(
            'Portofolio tidak ditemukan.'
        );
    }

    if (
        $portofolio['status_verifikasi']
        !== 'belum_diverifikasi'
    ) {
        throw new RuntimeException(
            'Portofolio tidak dapat diedit karena status verifikasi sudah berubah.'
        );
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
DELETE FROM portofolios p
WHERE p.slug = :slug
  AND p.mahasiswa_id = (
      SELECT id
      FROM profil_mahasiswa
      WHERE user_id = :user_id
  )
  AND p.status_verifikasi = 'belum_diverifikasi'
");

        $stmt->execute([
            'slug' => $slug,
            'user_id' => $userId,
        ]);

        // Baris teknologi ikut terhapus melalui ON DELETE CASCADE.
        return $stmt->rowCount() > 0;
    }
}
