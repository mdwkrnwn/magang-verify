
<?php


class PasswordReset
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Mencari akun mahasiswa atau dosen berdasarkan NIM/NIP.
     */
    public function findAccountByIdentity(string $identity): ?array
    {
        $sql = "
            SELECT
                u.id AS user_id,
                u.login_id,
                u.name,
                u.role,
                p.email,
                p.nim AS identity
            FROM profil_mahasiswa p
            INNER JOIN users u ON u.id = p.user_id
            WHERE p.nim = :nim
                AND u.role = 'mahasiswa'
                AND u.is_active = TRUE
                AND p.email IS NOT NULL
                AND TRIM(p.email) <> ''

            UNION ALL

            SELECT
                u.id AS user_id,
                u.login_id,
                u.name,
                u.role,
                p.email,
                p.nip AS identity
            FROM profil_dosen p
            INNER JOIN users u ON u.id = p.user_id
            WHERE p.nip = :nip
                AND u.role = 'dosen'
                AND u.is_active = TRUE
                AND p.email IS NOT NULL
                AND TRIM(p.email) <> ''
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':nim' => $identity,
            ':nip' => $identity,
        ]);

        $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Jangan memilih akun secara sembarangan
        // apabila identitas ditemukan lebih dari satu kali.
        if (count($accounts) !== 1) {
            return null;
        }

        return $accounts[0];
    }

    /**
     * Membuat token reset password.
     */
    public function create(
        int $userId,
        string $tokenHash,
        int $expiryMinutes = 30
    ): void {
        $this->pdo->beginTransaction();

        try {
            // Nonaktifkan token lama yang belum digunakan.
            $delete = $this->pdo->prepare("
                DELETE FROM password_resets
                WHERE user_id = :user_id
            ");

            $delete->execute([
                ':user_id' => $userId,
            ]);

            // Simpan token baru.
            $sql = "
                INSERT INTO password_resets (
                    user_id,
                    token_hash,
                    expires_at
                )
                VALUES (
                    :user_id,
                    :token_hash,
                    CURRENT_TIMESTAMP
                        + (:expiry_minutes * INTERVAL '1 minute')
                )
            ";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute([
                ':user_id' => $userId,
                ':token_hash' => $tokenHash,
                ':expiry_minutes' => $expiryMinutes,
            ]);

            $this->pdo->commit();
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Mencari token yang masih berlaku.
     */
    public function findValidToken(string $tokenHash): ?array
    {
        $sql = "
            SELECT
                pr.id AS reset_id,
                pr.user_id,
                u.is_active
            FROM password_resets pr
            INNER JOIN users u ON u.id = pr.user_id
            WHERE pr.token_hash = :token_hash
                AND pr.used_at IS NULL
                AND pr.expires_at > CURRENT_TIMESTAMP
                AND u.is_active = TRUE
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':token_hash' => $tokenHash,
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    /**
     * Mengubah password dan menandai token sudah digunakan.
     *
     * Dilakukan dalam satu transaksi.
     */
    public function resetPassword(
        int $resetId,
        int $userId,
        string $passwordHash
    ): bool {
        $this->pdo->beginTransaction();

        try {
            // Kunci token agar tidak digunakan bersamaan.
            $stmt = $this->pdo->prepare("
                SELECT id
                FROM password_resets
                WHERE id = :reset_id
                    AND user_id = :user_id
                    AND used_at IS NULL
                    AND expires_at > CURRENT_TIMESTAMP
                FOR UPDATE
            ");

            $stmt->execute([
                ':reset_id' => $resetId,
                ':user_id' => $userId,
            ]);

            if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                $this->pdo->rollBack();
                return false;
            }

            // Perbarui password pengguna.
            $update = $this->pdo->prepare("
                UPDATE users
                SET
                    password = :password,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :user_id
                    AND is_active = TRUE
            ");

            $update->execute([
                ':password' => $passwordHash,
                ':user_id' => $userId,
            ]);

            if ($update->rowCount() !== 1) {
                $this->pdo->rollBack();
                return false;
            }

            // Tandai token sudah digunakan.
            $used = $this->pdo->prepare("
                UPDATE password_resets
                SET used_at = CURRENT_TIMESTAMP
                WHERE id = :reset_id
            ");

            $used->execute([
                ':reset_id' => $resetId,
            ]);

            // Hapus token lain milik pengguna.
            $delete = $this->pdo->prepare("
                DELETE FROM password_resets
                WHERE user_id = :user_id
                    AND id <> :reset_id
            ");

            $delete->execute([
                ':user_id' => $userId,
                ':reset_id' => $resetId,
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
}
