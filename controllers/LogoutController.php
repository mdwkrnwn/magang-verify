
<?php

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../function/Helpers.php';
require_once __DIR__ . '/../models/AktivitasPengguna.php';

class LogoutController
{
    public function index($params = []): void
    {
        // Logout hanya boleh dilakukan melalui POST.
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            header('Allow: POST');
            exit('Metode request tidak diizinkan.');
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Validasi token CSRF.
        if (!verifyCsrfToken()) {
            http_response_code(403);
            exit('Permintaan tidak valid. Silakan muat ulang halaman.');
        }

        // Catat logout sebelum session dihancurkan.
        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        if ($userId > 0) {
            (new AktivitasPengguna())->log(
                $userId,
                'Logout',
                'Pengguna keluar dari sistem.',
                'auth',
                'users',
                $userId
            );
        }

        // Hapus seluruh data session.
        $_SESSION = [];

        // Hapus cookie session dari browser.
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

        // Hancurkan session di server.
        session_destroy();

        // Cegah halaman login dari cache setelah logout.
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Pragma: no-cache');

        // Kembali ke halaman login.
        header(
            'Location: ' . APP_URL . '/login?logout=success',
            true,
            303
        );
        exit;
    }
}