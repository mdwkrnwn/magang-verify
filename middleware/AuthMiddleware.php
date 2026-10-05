<?php

require_once __DIR__ . '/../config/database.php';

class AuthMiddleware
{
    public static function handle(string $requiredRole): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Pengguna wajib login
        if (empty($_SESSION['user']['id'])) {
            header('Location: ' . APP_URL . '/login');
            exit;
        }

        global $pdo;

        // Ambil data pengguna terbaru dari database
        $stmt = $pdo->prepare(
            'SELECT name, role, is_active
             FROM users
             WHERE id = :id
             LIMIT 1'
        );

        $stmt->execute([
            'id' => $_SESSION['user']['id'],
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

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
        $_SESSION['user']['role'] = $user['role'];

        // Pastikan role pengguna sesuai dengan route
        if ($user['role'] !== $requiredRole) {
            http_response_code(403);
            exit('403 - Anda tidak memiliki akses ke halaman ini.');
        }
    }
}