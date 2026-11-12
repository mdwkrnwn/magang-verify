<?php

require_once __DIR__ . '/../config/database.php';

class AuthMiddleware
{
    /**
     * Cache konteks auth per request agar pemanggilan middleware berulang
     * (index.php + controller) tidak menambah round-trip ke database.
     * Disimpan per user agar aman bila proses yang sama melayani user lain.
     *
     * @var array<int, array{0: ?array, 1: array<int, string>, 2: int}>
     */
    private static array $contextCache = [];

    public static function handle(string $requiredRole): void
    {
        self::handleAny([$requiredRole]);
    }

    public static function handleAny(array $requiredRoles): void
    {
        $requiredRoles = array_values(array_filter($requiredRoles, 'is_string'));
        if ($requiredRoles === []) {
            http_response_code(403);
            exit('403 - Role yang dibutuhkan tidak valid.');
        }
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Pengguna wajib login
        if (empty($_SESSION['user']['id'])) {
            header('Location: ' . APP_URL . '/login');
            exit;
        }

        $userId = (int) $_SESSION['user']['id'];

        [$user, $roles, $unreadNotifications] = self::loadAuthContext($userId);

        // Jika pengguna sudah tidak ditemukan atau akun tidak aktif
        if (
            !$user ||
            !in_array(
                $user['is_active'],
                [true, 1, '1', 't', 'true'],
                true
            )
        ) {
            $_SESSION = [];

            // Hapus cookie session dari browser
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

            // Hancurkan session
            session_destroy();

            header('Location: ' . APP_URL . '/login?error=1');
            exit;
        }

        // Perbarui data pengguna di session
        $_SESSION['user']['name'] = $user['name'];
        $_SESSION['user']['roles'] = $roles;
        $matchedRole = null;
        foreach ($requiredRoles as $role) {
            if (in_array($role, $roles, true)) {
                $matchedRole = $role;
                break;
            }
        }
        if ($matchedRole === null) {
            http_response_code(403);
            exit('403 - Anda tidak memiliki akses ke halaman ini.');
        }
        if (!is_string($_SESSION['user']['role'] ?? null) || !in_array($_SESSION['user']['role'], $roles, true)) {
            $_SESSION['user']['role'] = $user['role'];
        }
        if (!in_array($_SESSION['user']['role'], $requiredRoles, true)) {
            $_SESSION['user']['role'] = $matchedRole;
        }

        // Bagikan konteks auth ke komponen (header/notifikasi) tanpa query ulang.
        $GLOBALS['auth_context'] = [
            'user_id' => $userId,
            'name' => (string) ($user['name'] ?? ''),
            'roles' => $roles,
            'unread_notifications' => $unreadNotifications,
        ];
    }

    /**
     * Memuat user, seluruh role, dan jumlah notifikasi belum dibaca dalam
     * satu query. Fallback ke query user saja bila tabel user_roles belum ada.
     */
    private static function loadAuthContext(int $userId): array
    {
        if (isset(self::$contextCache[$userId])) {
            return self::$contextCache[$userId];
        }

        global $pdo;

        try {
            $stmt = $pdo->prepare(
                'SELECT u.name, u.role, u.is_active,
                        COALESCE(string_agg(DISTINCT ur.role, \',\'), \'\') AS roles,
                        (SELECT COUNT(*) FROM notifikasi n
                         WHERE n.user_id = u.id AND n.sudah_dibaca = FALSE) AS unread_notifications
                 FROM users u
                 LEFT JOIN user_roles ur ON ur.user_id = u.id
                 WHERE u.id = :id
                 GROUP BY u.id, u.name, u.role, u.is_active
                 LIMIT 1'
            );
            $stmt->execute(['id' => $userId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            if (!self::isMissingUserRolesTable($exception)) {
                throw $exception;
            }

            // Legacy: tabel user_roles belum tersedia.
            $stmt = $pdo->prepare(
                'SELECT name, role, is_active
                 FROM users
                 WHERE id = :id
                 LIMIT 1'
            );
            $stmt->execute(['id' => $userId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $row['roles'] = '';
                $row['unread_notifications'] = 0;
            }
        }

        if (!$row) {
            return self::$contextCache[$userId] = [null, [], 0];
        }

        $roles = [];
        $primaryRole = (string) ($row['role'] ?? '');
        if ($primaryRole !== '') {
            $roles[] = $primaryRole;
        }
        foreach (explode(',', (string) ($row['roles'] ?? '')) as $role) {
            $role = trim($role);
            if ($role !== '' && !in_array($role, $roles, true)) {
                $roles[] = $role;
            }
        }
        if ($roles === [] && $primaryRole !== '') {
            $roles = [$primaryRole];
        }

        return self::$contextCache[$userId] = [
            $row,
            $roles,
            max(0, (int) ($row['unread_notifications'] ?? 0)),
        ];
    }

    private static function isMissingUserRolesTable(PDOException $exception): bool
    {
        // Preserve legacy single-role login when migration 030 has not been installed.
        // SQLSTATE 42P01 is PostgreSQL undefined_table.
        $code = (string) $exception->getCode();
        $message = strtolower($exception->getMessage());
        if (in_array($code, ['42P01', 'HY000'], true)) {
            return true;
        }
        return str_contains($message, 'user_roles')
            || str_contains($message, 'does not exist')
            || str_contains($message, 'no such table');
    }
}
