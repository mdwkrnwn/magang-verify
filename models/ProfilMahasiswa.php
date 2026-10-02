
<?php

require_once __DIR__ . '/../config/database.php';

class ProfilMahasiswa
{
    private PDO $pdo;

    public function __construct(?PDO $connection = null)
    {
        if ($connection !== null) {
            $this->pdo = $connection;
            return;
        }

        global $pdo;

        if (!$pdo instanceof PDO) {
            throw new RuntimeException(
                'Koneksi database tidak tersedia.'
            );
        }

        $this->pdo = $pdo;
    }

    /**
     * Mengambil profil berdasarkan ID akun.
     * Jika profil belum tersedia, buat profil kosong.
     */
    public function getOrCreateByUserId(int $userId): array
    {
        $sql = '
            INSERT INTO profil_mahasiswa (user_id)
            SELECT id
            FROM users
            WHERE id = :user_id
              AND role = :role
            ON CONFLICT (user_id) DO NOTHING
        ';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'user_id' => $userId,
            'role' => 'mahasiswa',
        ]);

        $sql = '
            SELECT
                u.id AS user_id,
                u.name AS nama_lengkap,
                u.login_id AS nim,
                p.jenis_kelamin,
                p.tanggal_lahir,
                p.alamat,
                p.email,
                p.no_hp,
                p.instagram,
                p.program_studi,
                p.semester,
                p.bio,
                p.foto_profil,
                p.keahlian,
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
            throw new RuntimeException(
                'Data akun mahasiswa tidak ditemukan.'
            );
        }

        // PostgreSQL mengembalikan TEXT[] sebagai string.
        $profil['keahlian'] = $this->parsePostgresArray(
            $profil['keahlian'] ?? '{}'
        );

        return $profil;
    }

    /**
     * Memperbarui informasi pribadi.
     */
    public function updatePersonal(int $userId, array $data): bool
    {
        $this->pdo->beginTransaction();

        try {
            $sqlUser = '
                UPDATE users
                SET name = :nama
                WHERE id = :user_id
                  AND role = :role
            ';

            $stmt = $this->pdo->prepare($sqlUser);
            $stmt->execute([
                'nama' => $data['nama_lengkap'],
                'user_id' => $userId,
                'role' => 'mahasiswa',
            ]);

            $sqlProfil = '
                UPDATE profil_mahasiswa
                SET
                    jenis_kelamin = :jenis_kelamin,
                    tanggal_lahir = :tanggal_lahir,
                    alamat = :alamat,
                    updated_at = CURRENT_TIMESTAMP
                WHERE user_id = :user_id
            ';

            $stmt = $this->pdo->prepare($sqlProfil);
            $stmt->execute([
                'jenis_kelamin' => $data['jenis_kelamin'] ?: null,
                'tanggal_lahir' => $data['tanggal_lahir'] ?: null,
                'alamat' => $data['alamat'] ?: null,
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
     * Memperbarui informasi kontak, termasuk email.
     */
    public function updateContact(int $userId, array $data): bool
    {
        $sql = '
            UPDATE profil_mahasiswa
            SET
                email = :email,
                no_hp = :no_hp,
                instagram = :instagram,
                updated_at = CURRENT_TIMESTAMP
            WHERE user_id = :user_id
        ';

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'email' => !empty($data['email'])
                ? trim($data['email'])
                : null,
            'no_hp' => !empty($data['no_hp'])
                ? trim($data['no_hp'])
                : null,
            'instagram' => !empty($data['instagram'])
                ? trim($data['instagram'])
                : null,
            'user_id' => $userId,
        ]);
    }

    /**
     * Memperbarui bio dan daftar keahlian.
     */
    public function updateBio(
        int $userId,
        string $bio,
        array $keahlian
    ): bool {
        $keahlian = array_values(array_unique(array_filter(
            array_map(
                static fn($item) => trim((string) $item),
                $keahlian
            ),
            static fn($item) => $item !== ''
        )));

        // Bentuk literal array PostgreSQL dengan escaping.
        $arrayLiteral = '{' . implode(',', array_map(
            static fn($item) => '"' .
                addcslashes($item, "\\\"") . '"',
            $keahlian
        )) . '}';

        $sql = '
            UPDATE profil_mahasiswa
            SET
                bio = :bio,
                keahlian = CAST(:keahlian AS TEXT[]),
                updated_at = CURRENT_TIMESTAMP
            WHERE user_id = :user_id
        ';

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'bio' => $bio !== '' ? $bio : null,
            'keahlian' => $arrayLiteral,
            'user_id' => $userId,
        ]);
    }

    /**
     * Memperbarui path foto profil.
     */
    public function updatePhoto(int $userId, ?string $fotoProfil): bool
    {
        $sql = '
            UPDATE profil_mahasiswa
            SET
                foto_profil = :foto_profil,
                updated_at = CURRENT_TIMESTAMP
            WHERE user_id = :user_id
        ';

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'foto_profil' => $fotoProfil,
            'user_id' => $userId,
        ]);
    }

    /**
     * Mengubah TEXT[] PostgreSQL menjadi array PHP.
     */
    private function parsePostgresArray(string $value): array
    {
        if ($value === '{}' || $value === '') {
            return [];
        }

        $result = str_getcsv(
            trim($value, '{}'),
            ',',
            '"',
            '\\'
        );

        return array_values(array_filter(
            $result,
            static fn($item) => $item !== null && $item !== ''
        ));
    }
}