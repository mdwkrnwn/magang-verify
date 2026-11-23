<?php

class MitraSessionManager
{
    /**
     * Membuat sesi permanen Mitra.
     *
     * accessTokenId:
     * - ID token akses jika login melalui link
     * - NULL jika login manual
     */
    public static function createSession(
        PDO $pdo,
        int $mitraId,
        string $namaPerusahaan,
        ?int $accessTokenId = null
    ): string {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        self::revokeCurrentSession($pdo);
        unset($_SESSION['user']);

        // Generate token sesi baru
        $sessionToken = bin2hex(random_bytes(32));
        $sessionHash = hash('sha256', $sessionToken);

        try {
            // Simpan sesi permanen ke database
            $stmt = $pdo->prepare("
                INSERT INTO mitra_sessions (
                    mitra_id,
                access_token_id,
                    session_hash,
                    expires_at
                ) VALUES (
                    :mitra_id,
                    :access_token_id,
                    :session_hash,
                    NULL
                )
            ");

            $stmt->execute([
                ':mitra_id'       => $mitraId,
                ':access_token_id' => $accessTokenId,
                ':session_hash'   => $sessionHash,
            ]);

            // Regenerasi ID PHP Session
            session_regenerate_id(true);

            // Simpan identitas Mitra
            $_SESSION['mitra'] = [
                'id' => $mitraId,
                'nama_perusahaan' => $namaPerusahaan,
            ];

            // Simpan token sesi asli di PHP Session
            $_SESSION['mitra_session_token'] = $sessionToken;

            return $sessionToken;
        } catch (Throwable $e) {
            error_log('MitraSessionManager: ' . $e->getMessage());
            throw $e;
        }
    }

    public static function revokeCurrentSession(PDO $pdo): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $sessionToken = $_SESSION['mitra_session_token'] ?? null;
        if (is_string($sessionToken) && $sessionToken !== '') {
            $stmt = $pdo->prepare("
                UPDATE mitra_sessions
                SET revoked_at = CURRENT_TIMESTAMP
                WHERE session_hash = :session_hash
                  AND revoked_at IS NULL
            ");
            $stmt->execute([
                ':session_hash' => hash('sha256', $sessionToken),
            ]);
        }

        unset(
            $_SESSION['mitra'],
            $_SESSION['mitra_session_token'],
            $_SESSION['mitra_last_usage_updated_at']
        );
        unset($GLOBALS['mitra_auth_context']);
    }
}
