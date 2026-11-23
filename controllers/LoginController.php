<?php


$loginPerfStart = microtime(true);

require_once __DIR__ . '/../config/database.php';

if (function_exists('perfMark')) {
    perfMark(sprintf(
        'Login dependency: database.php (%.3f s)',
        microtime(true) - $loginPerfStart
    ));
}

$loginPerfStart = microtime(true);

require_once __DIR__ . '/../models/MitraSessionManager.php';

if (function_exists('perfMark')) {
    perfMark(sprintf(
        'Login dependency: MitraSessionManager.php (%.3f s)',
        microtime(true) - $loginPerfStart
    ));
}

$loginPerfStart = microtime(true);

require_once __DIR__ . '/../models/MitraAuthentication.php';

if (function_exists('perfMark')) {
    perfMark(sprintf(
        'Login dependency: MitraAuthentication.php (%.3f s)',
        microtime(true) - $loginPerfStart
    ));
}

class LoginController
{
    private ?User $userModel = null;
    private MitraAuthentication $mitraAuthentication;

    public function __construct()
    {
        $perfStart = microtime(true);

        global $pdo;

        $this->mitraAuthentication = new MitraAuthentication($pdo);

        if (function_exists('perfMark')) {
            perfMark(sprintf(
                'Login: constructor (%.3f s)',
                microtime(true) - $perfStart
            ));
        }
    }

    public function index($params = [])
    {
        global $pdo;

        $perfStart = microtime(true);

        // Mulai session sebelum menghasilkan output.
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (function_exists('perfMark')) {
            perfMark(sprintf(
                'Login: session (%.3f s)',
                microtime(true) - $perfStart
            ));
        }

        // Tampilkan halaman login jika request bukan POST.
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            $perfStart = microtime(true);

            require __DIR__ . '/../pages/login/index.php';

            if (function_exists('perfMark')) {
                perfMark(sprintf(
                    'Login: render page (%.3f s)',
                    microtime(true) - $perfStart
                ));
            }

            return;
        }

        // Validasi CSRF.
        $perfStart = microtime(true);

        if (!verifyCsrfToken()) {
            http_response_code(403);
            exit('Permintaan tidak valid. Silakan muat ulang halaman.');
        }

        if (function_exists('perfMark')) {
            perfMark(sprintf(
                'Login: CSRF validation (%.3f s)',
                microtime(true) - $perfStart
            ));
        }

        // Ambil data dari form.
        $role = $_POST['role'] ?? '';

        $submittedIdentity = $_POST['login_id'] ?? '';

        $identity = is_string($submittedIdentity)
            ? trim($submittedIdentity)
            : '';

        // Daftar role yang diperbolehkan.
        $allowedRoles = [
            'mahasiswa',
            'dosen',
            'koordinator_magang',
            'tendik',
            'mitra',
        ];

        // Validasi input dasar.
        if (
            !is_string($role) ||
            !in_array($role, $allowedRoles, true) ||
            $identity === ''
        ) {
            $this->redirectToLogin();
        }

        if ($role === 'mitra') {
            $mitra = $this->mitraAuthentication->findActiveByCode($identity);
            if (!$mitra) {
                $this->redirectToLogin();
            }

            MitraSessionManager::createSession(
                $pdo,
                (int) $mitra['id'],
                (string) $mitra['nama_perusahaan'],
                null
            );
            header('Location: ' . APP_URL . '/dashboard/mitra', true, 302);
            exit;
        }

        $password = $_POST['password'] ?? '';

        $loginPerfStart = microtime(true);
        require_once __DIR__ . '/../models/User.php';
        require_once __DIR__ . '/../models/LoginAuthenticator.php';
        require_once __DIR__ . '/../models/AktivitasPengguna.php';
        $this->userModel = new User($pdo);
        if (function_exists('perfMark')) {
            perfMark(sprintf(
                'Login dependency: User.php (%.3f s)',
                microtime(true) - $loginPerfStart
            ));
        }

        // Cari pengguna.
        $perfStart = microtime(true);

        $user = $this->userModel->findByIdentityAndRole($identity, $role);

        if (function_exists('perfMark')) {
            perfMark(sprintf(
                'Login: find user query (%.3f s)',
                microtime(true) - $perfStart
            ));
        }
        // Validasi role dan password.
        $perfStart = microtime(true);

        if (
            !$user ||
            !is_string($password) ||
            !in_array(
                $role,
                LoginAuthenticator::normalizeRoles($user),
                true
            ) ||
            !LoginAuthenticator::accepts($role, $user, $password)
        ) {
            if (function_exists('perfMark')) {
                perfMark(sprintf(
                    'Login: credential validation (%.3f s)',
                    microtime(true) - $perfStart
                ));
            }

            $this->redirectToLogin();
        }

        if (function_exists('perfMark')) {
            perfMark(sprintf(
                'Login: credential validation (%.3f s)',
                microtime(true) - $perfStart
            ));
        }

        // Login berhasil.
        $perfStart = microtime(true);

        $aktivitasModel = new AktivitasPengguna();

        if (function_exists('perfMark')) {
            perfMark(sprintf(
                'Login: activity model constructor (%.3f s)',
                microtime(true) - $perfStart
            ));
        }

        // Buat session pengguna internal.
        $perfStart = microtime(true);

        MitraSessionManager::revokeCurrentSession($pdo);
        session_regenerate_id(true);

        $_SESSION['user'] = [
            'id'       => $user['id'],
            'login_id' => $user['login_id'],
            'name'     => $user['name'],
            'role'     => $role,
            'roles'    => LoginAuthenticator::normalizeRoles($user),
        ];

        if (function_exists('perfMark')) {
            perfMark(sprintf(
                'Login: session regeneration and assignment (%.3f s)',
                microtime(true) - $perfStart
            ));
        }

        // Catat aktivitas login.
        $perfStart = microtime(true);

        $aktivitasModel->log(
            (int) $user['id'],
            'Login berhasil',
            'Pengguna berhasil masuk ke sistem sebagai ' . $user['role'] . '.',
            'auth',
            'users',
            (int) $user['id']
        );

        if (function_exists('perfMark')) {
            perfMark(sprintf(
                'Login: activity log (%.3f s)',
                microtime(true) - $perfStart
            ));
        }

        // Tujuan redirect berdasarkan role.
        $dashboardRoutes = [
            'mahasiswa'          => '/dashboard/mahasiswa',
            'dosen'              => '/dashboard/dosen',
            'koordinator_magang' => '/dashboard/koordinator-magang',
            'tendik'             => '/dashboard/tendik',
        ];

        // Redirect ke dashboard.
        if (function_exists('perfMark')) {
            perfMark('Login: before redirect');
        }

        header(
            'Location: ' . APP_URL . $dashboardRoutes[$role],
            true,
            302
        );

        exit;
    }

    /**
     * Mengembalikan pengguna ke halaman login.
     */
    private function redirectToLogin(): void
    {
        header(
            'Location: ' . APP_URL . '/login?error=1',
            true,
            302
        );

        exit;
    }
}
