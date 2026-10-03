
<?php

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
                p.alamat,
                p.deskripsi,
                p.foto_path,
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
                updated_at = CURRENT_TIMESTAMP
            WHERE user_id = :user_id
        ');

        return $stmt->execute([
            'email' => $data['email'] !== ''
                ? $data['email']
                : null,
            'no_telepon' => $data['no_telepon'] !== ''
                ? $data['no_telepon']
                : null,
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
}