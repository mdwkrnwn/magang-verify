
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
}
