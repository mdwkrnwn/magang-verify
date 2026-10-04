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
