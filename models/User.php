<?php

class User
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findByLoginId(string $loginId): ?array
    {
        $sql = "
            SELECT id, login_id, name, password, role, is_active
            FROM users
            WHERE login_id = :login_id
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'login_id' => $loginId
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    /**
     * Resolve a Mitra account using the official company code on its profile.
     */
    public function findActiveMitraByCode(string $companyCode): ?array
    {
        $sql = "
            SELECT u.id, u.login_id, u.name, u.role, u.is_active,
                   m.is_active AS mitra_is_active
            FROM mitra m
            INNER JOIN users u ON u.id = m.user_id
            WHERE m.kode_perusahaan = :kode_perusahaan
              AND u.role = 'mitra'
              AND u.is_active = TRUE
              AND m.is_active = TRUE
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['kode_perusahaan' => $companyCode]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function findById(int $id): ?array
    {
        $sql = "
        SELECT
            id,
            login_id,
            name,
            password,
            role,
            is_active
        FROM users
        WHERE id = :id
        LIMIT 1
    ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'id' => $id,
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }


    public function updatePassword(
        int $id,
        string $passwordHash
    ): bool {
        $sql = "
        UPDATE users
        SET
            password = :password,
            updated_at = CURRENT_TIMESTAMP
        WHERE id = :id
    ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'password' => $passwordHash,
            'id' => $id,
        ]);
    }
}
