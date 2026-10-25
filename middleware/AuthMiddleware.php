<?php

require_once __DIR__ . '/../config/database.php';

class AuthMiddleware
{
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

        global $pdo;

        [$user, $roles] = self::loadUserWithRoles((int) $_SESSION['user']['id']);

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
    }

    private static function loadUserWithRoles(int $userId): array
    {
        global $pdo;
        $stmt = $pdo->prepare(
            'SELECT name, role, is_active
             FROM users
             WHERE id = :id
             LIMIT 1'
        );
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            return [null, []];
        }
        $roles = [$user['role'] ?? null];
        try {
            $statement = $pdo->prepare('SELECT role FROM user_roles WHERE user_id = :id');
            $statement->execute(['id' => $userId]);
            foreach ($statement->fetchAll(PDO::FETCH_COLUMN) as $role) {
                if (is_string($role) && $role !== '') {
                    $roles[] = $role;
                }
            }
        } catch (PDOException $exception) {
            if (!User::isMissingUserRolesTable($exception)) {
                throw $exception;
            }
        }
        $primaryRole = (string) ($user['role'] ?? '');
        if ($primaryRole !== '' && !in_array($primaryRole, $roles, true)) {
            array_unshift($roles, $primaryRole);
        }
        return [$user, $roles === [] ? [$primaryRole] : $roles];
    }
}