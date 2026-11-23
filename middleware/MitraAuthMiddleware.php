
<?php

require_once __DIR__ . '/../config/database.php';

class MitraAuthMiddleware
{
    public static function check(): void
    {
        global $pdo;

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Ambil data session Mitra.
        $sessionToken = $_SESSION['mitra_session_token'] ?? null;
        $mitraId = $_SESSION['mitra']['id'] ?? null;

        // Session tidak lengkap atau tidak valid.
        if (
            !$sessionToken ||
            !$mitraId ||
            !is_numeric($mitraId)
        ) {
            self::logout();
        }

        $sessionHash = hash('sha256', $sessionToken);

        // Validasi session di database.
        try {
            $stmt = $pdo->prepare("
                SELECT
                    ms.id,
                    ms.mitra_id,
                    ms.expires_at,
                    ms.revoked_at,
                    ms.access_token_id,

                    mat.revoked_at AS access_revoked_at,
                    mat.expires_at AS access_expires_at,

                    m.is_active

                FROM mitra_sessions ms

                INNER JOIN mitra m
                    ON m.id = ms.mitra_id

                LEFT JOIN mitra_access_tokens mat
                    ON mat.id = ms.access_token_id

                WHERE ms.session_hash = :session_hash
                  AND ms.mitra_id = :mitra_id

                  AND ms.revoked_at IS NULL

                  AND (
                      ms.expires_at IS NULL
                      OR ms.expires_at > CURRENT_TIMESTAMP
                  )

                  AND (
                      ms.access_token_id IS NULL

                      OR (
                          mat.id IS NOT NULL
                          AND mat.revoked_at IS NULL
                          AND (
                              mat.expires_at IS NULL
                              OR mat.expires_at > CURRENT_TIMESTAMP
                          )
                      )
                  )

                LIMIT 1
            ");

            $stmt->execute([
                ':session_hash' => $sessionHash,
                ':mitra_id' => (int) $mitraId,
            ]);

            $auth = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log(
                'MitraAuthMiddleware query error: '
                    . $e->getMessage()
            );

            http_response_code(500);

            $errorTitle = 'Autentikasi Mitra gagal';
            $errorMessage = 'Kami mengalami kendala saat memverifikasi akses Mitra kamu. Silakan coba kembali beberapa saat lagi.';
            $errorInfo = 'Terjadi gangguan pada proses autentikasi Mitra';

            require dirname(__DIR__) . '/pages/errors/500.php';
            exit;
        }

        // Session tidak ditemukan, expired, atau dicabut.
        if (!$auth) {
            self::logout();
        }

        // Validasi status aktif Mitra.
        $isMitraActive = filter_var(
            $auth['is_active'] ?? false,
            FILTER_VALIDATE_BOOLEAN
        );

        if (!$isMitraActive) {
            self::logout();
        }

        // Batasi pembaruan last_used_at agar tetap akurat tanpa satu UPDATE
        // database pada setiap navigasi. Validasi sesi tetap dijalankan penuh.
        $usageUpdateInterval = 300;
        $usageUpdatedAt = (int) ($_SESSION['mitra_last_usage_updated_at'] ?? 0);
        $now = time();

        if ($usageUpdatedAt < $now - $usageUpdateInterval) {
            try {
                $update = $pdo->prepare("
                    UPDATE mitra_sessions
                    SET last_used_at = CURRENT_TIMESTAMP
                    WHERE id = :id
                ");

                $update->execute([
                    ':id' => (int) $auth['id'],
                ]);
                $_SESSION['mitra_last_usage_updated_at'] = $now;
            } catch (PDOException $e) {
                // Gagal update last_used tidak membatalkan autentikasi.
                error_log(
                    'MitraAuthMiddleware update error: '
                        . $e->getMessage()
                );
            }
        }

        // Context autentikasi Mitra.
        $GLOBALS['mitra_auth_context'] = [
            'session_id' => (int) $auth['id'],
            'mitra_id' => (int) $auth['mitra_id'],
            'access_token_id' => $auth['access_token_id'] !== null
                ? (int) $auth['access_token_id']
                : null,
        ];
    }

    public static function currentMitraId(): int
    {
        $mitraId = filter_var(
            $GLOBALS['mitra_auth_context']['mitra_id'] ?? null,
            FILTER_VALIDATE_INT
        );
        if ($mitraId === false || $mitraId < 1) {
            http_response_code(401);

            $errorTitle = 'Sesi Mitra tidak valid';
            $errorMessage = 'Sesi akses Mitra kamu sudah tidak valid atau telah berakhir. Silakan login kembali untuk melanjutkan aktivitas.';

            require dirname(__DIR__) . '/pages/errors/session-expired.php';
            exit;
        }

        return $mitraId;
    }

    public static function currentUserId(): ?int
    {
        $mitraId = self::currentMitraId();
        global $pdo;

        try {
            $stmt = $pdo->prepare("
        SELECT user_id
        FROM mitra
        WHERE id = :mitra_id
          AND is_active = TRUE
        LIMIT 1
    ");

            $stmt->execute([':mitra_id' => $mitraId]);

            $userId = filter_var(
                $stmt->fetchColumn(),
                FILTER_VALIDATE_INT
            );

            return $userId !== false && $userId > 0
                ? $userId
                : null;
        } catch (PDOException $e) {
            error_log(
                'MitraAuthMiddleware currentUserId error: '
                    . $e->getMessage()
            );

            http_response_code(500);

            $errorTitle = 'Data pengguna Mitra tidak dapat dimuat';
            $errorMessage = 'Kami mengalami kendala saat mengambil data pengguna Mitra. Silakan coba kembali beberapa saat lagi.';
            $errorInfo = 'Terjadi gangguan saat memuat data pengguna Mitra';

            require dirname(__DIR__) . '/pages/errors/500.php';
            exit;
        }

        return $userId !== false && $userId > 0 ? $userId : null;
    }

    /**
     * Logout Mitra.
     */
    public static function logout(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        global $pdo;

        // Cabut session Mitra dari database.
        $sessionToken = $_SESSION['mitra_session_token'] ?? null;

        if ($sessionToken) {
            $sessionHash = hash('sha256', $sessionToken);

            try {
                $stmt = $pdo->prepare("
                    UPDATE mitra_sessions
                    SET revoked_at = CURRENT_TIMESTAMP
                    WHERE session_hash = :session_hash
                      AND revoked_at IS NULL
                ");

                $stmt->execute([
                    ':session_hash' => $sessionHash,
                ]);
            } catch (PDOException $e) {
                error_log(
                    'MitraAuthMiddleware logout error: '
                        . $e->getMessage()
                );
            }
        }

        // Bersihkan session PHP.
        $_SESSION = [];

        // Hapus cookie session.
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        // Kembali ke login.
        header('Location: ' . APP_URL . '/login');
        exit;
    }
}
