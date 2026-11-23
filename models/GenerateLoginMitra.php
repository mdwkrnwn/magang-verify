<?php

class GenerateLoginMitra
{
    private PDO $pdo;
    private string $encryptionKey;

    public function __construct()
    {
        global $pdo;

        $this->pdo = $pdo;

        $key = getenv('MITRA_LINK_ENCRYPTION_KEY');

        if (!$key || strlen($key) !== 64 || !ctype_xdigit($key)) {
            throw new RuntimeException(
                'MITRA_LINK_ENCRYPTION_KEY belum dikonfigurasi dengan benar.'
            );
        }

        $this->encryptionKey = hex2bin($key);
    }

    /**
     * Enkripsi token link mitra menggunakan AES-256-GCM.
     */
    private function encryptToken(string $token): string
    {
        $iv = random_bytes(12);
        $tag = '';

        $ciphertext = openssl_encrypt(
            $token,
            'aes-256-gcm',
            $this->encryptionKey,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );

        if ($ciphertext === false) {
            throw new RuntimeException('Gagal mengenkripsi token mitra.');
        }

        return base64_encode($iv . $tag . $ciphertext);
    }

    /**
     * Dekripsi token untuk kebutuhan salin link.
     */
    private function decryptToken(string $encrypted): string
    {
        $data = base64_decode($encrypted, true);

        if ($data === false || strlen($data) < 28) {
            throw new RuntimeException('Data token tidak valid.');
        }

        $iv = substr($data, 0, 12);
        $tag = substr($data, 12, 16);
        $ciphertext = substr($data, 28);

        $token = openssl_decrypt(
            $ciphertext,
            'aes-256-gcm',
            $this->encryptionKey,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );

        if ($token === false) {
            throw new RuntimeException('Gagal mendekripsi token mitra.');
        }

        return $token;
    }

    /**
     * Semua mitra aktif beserta status link terbarunya.
     */
    public function getAllActiveMitra(): array
    {
        $sql = "
            SELECT
                m.id,
                m.nama_perusahaan,
                m.kode_perusahaan,
                m.kategori,
                m.email,
                m.is_active,

                t.id AS access_id,
                t.expires_at,
                t.used_at,
                t.revoked_at,
                t.created_at AS access_created_at,

                CASE
                    WHEN t.id IS NULL THEN 'belum_dibuat'
                    WHEN t.revoked_at IS NOT NULL THEN 'dicabut'
                    ELSE 'aktif'
                END AS access_status

            FROM mitra m

            LEFT JOIN LATERAL (
                SELECT *
                FROM mitra_access_tokens
                WHERE mitra_id = m.id
                ORDER BY created_at DESC, id DESC
                LIMIT 1
            ) t ON TRUE

            WHERE m.is_active = TRUE

            ORDER BY m.nama_perusahaan ASC
        ";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Membuat link permanen.
     * Link sebelumnya otomatis dicabut.
     */
    public function generateToken(
        int $mitraId,
        int $createdBy
    ): string {
        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);
        $tokenEncrypted = $this->encryptToken($token);

        try {
            $this->pdo->beginTransaction();

            // Lock mitra
            $stmt = $this->pdo->prepare("
                SELECT id
                FROM mitra
                WHERE id = :id
                  AND is_active = TRUE
                FOR UPDATE
            ");

            $stmt->execute([
                ':id' => $mitraId
            ]);

            if (!$stmt->fetch()) {
                throw new RuntimeException('Mitra tidak ditemukan atau tidak aktif.');
            }

            // Cabut semua link sebelumnya yang masih aktif
            $stmt = $this->pdo->prepare("
                UPDATE mitra_access_tokens
                SET revoked_at = CURRENT_TIMESTAMP
                WHERE mitra_id = :mitra_id
                  AND revoked_at IS NULL
            ");

            $stmt->execute([
                ':mitra_id' => $mitraId
            ]);

            // Buat link permanen baru
            $stmt = $this->pdo->prepare("
                INSERT INTO mitra_access_tokens (
                    mitra_id,
                    token_hash,
                    token_encrypted,
                    expires_at,
                    created_by
                )
                VALUES (
                    :mitra_id,
                    :token_hash,
                    :token_encrypted,
                    NULL,
                    :created_by
                )
            ");

            $stmt->execute([
                ':mitra_id' => $mitraId,
                ':token_hash' => $tokenHash,
                ':token_encrypted' => $tokenEncrypted,
                ':created_by' => $createdBy
            ]);

            $this->pdo->commit();

            return $token;
        } catch (Throwable $e) {

            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Mengambil token aktif untuk membuat link yang dapat disalin.
     */
    public function getExistingToken(int $mitraId): ?string
    {
        $stmt = $this->pdo->prepare("
            SELECT token_encrypted
            FROM mitra_access_tokens
            WHERE mitra_id = :mitra_id
              AND revoked_at IS NULL
            ORDER BY created_at DESC, id DESC
            LIMIT 1
        ");

        $stmt->execute([
            ':mitra_id' => $mitraId
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row || empty($row['token_encrypted'])) {
            return null;
        }

        return $this->decryptToken($row['token_encrypted']);
    }

    /**
     * Cabut link permanen.
     */
    public function revokeAccess(int $mitraId): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE mitra_access_tokens
            SET revoked_at = CURRENT_TIMESTAMP
            WHERE mitra_id = :mitra_id
              AND revoked_at IS NULL
        ");

        return $stmt->execute([
            ':mitra_id' => $mitraId
        ]);
    }

    /**
     * Validasi status mitra.
     */
    public function isMitraActive(int $mitraId): bool
    {
        $stmt = $this->pdo->prepare("
            SELECT EXISTS (
                SELECT 1
                FROM mitra
                WHERE id = :id
                  AND is_active = TRUE
            )
        ");

        $stmt->execute([
            ':id' => $mitraId
        ]);

        return (bool) $stmt->fetchColumn();
    }

    /**
     * Validasi token login mitra.
     * Token bisa dipakai berulang kali.
     */
    public function validateToken(string $token): ?array
    {
        $tokenHash = hash('sha256', $token);

        $stmt = $this->pdo->prepare("
        SELECT
            t.id AS access_id,
            t.mitra_id,
            t.revoked_at,
            m.nama_perusahaan,
            m.is_active
        FROM mitra_access_tokens t
        INNER JOIN mitra m ON m.id = t.mitra_id
        WHERE t.token_hash = :token_hash
        AND t.revoked_at IS NULL
        AND (t.expires_at IS NULL OR t.expires_at > CURRENT_TIMESTAMP)
        AND m.is_active = TRUE
        LIMIT 1
        ");

        $stmt->execute([
            ':token_hash' => $tokenHash
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        // used_at hanya menjadi catatan bahwa link pernah digunakan
        $stmt = $this->pdo->prepare("
            UPDATE mitra_access_tokens
            SET used_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ");

        $stmt->execute([
            ':id' => $row['access_id']
        ]);

        return $row;
    }
}
