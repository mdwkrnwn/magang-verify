<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/LoginAuthenticator.php';
require_once __DIR__ . '/../models/AktivitasPengguna.php';

class LoginController
{
    private User $userModel;

    public function __construct()
    {
        global $pdo;

        $this->userModel = new User($pdo);
    }

    public function index($params = [])
    {
        // Mulai session sebelum menghasilkan output.
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Tampilkan halaman login jika request bukan POST.
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require __DIR__ . '/../pages/login/index.php';
            return;
        }

        if (!verifyCsrfToken()) {
            http_response_code(403);
            exit('Permintaan tidak valid. Silakan muat ulang halaman.');
        }

        // Ambil data dari form.
        $role = $_POST['role'] ?? '';
        $submittedLoginId = $_POST['login_id'] ?? '';
        $loginId = is_string($submittedLoginId) ? trim($submittedLoginId) : '';
        $password = $_POST['password'] ?? '';

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
            $loginId === ''
        ) {
            $this->redirectToLogin();
        }

        // Mitra memakai kode pada profil perusahaan; role lain tetap memakai users.login_id.
        $user = $role === 'mitra'
            ? $this->userModel->findActiveMitraByCode($loginId)
            : $this->userModel->findByLoginId($loginId);

        if (
            !$user ||
            !is_string($password) ||
            !in_array($role, LoginAuthenticator::normalizeRoles($user), true) ||
            !LoginAuthenticator::accepts($role, $user, $password)
        ) {
            $this->redirectToLogin();
        }

        // Login berhasil: perbarui ID session.
        $aktivitasModel = new AktivitasPengguna();
        session_regenerate_id(true);

        // Simpan informasi pengguna yang diperlukan.
        $_SESSION['user'] = [
            'id'       => $user['id'],
            'login_id' => $user['login_id'],
            'name'     => $user['name'],
            'role'     => $role,
            'roles'    => LoginAuthenticator::normalizeRoles($user),
        ];

        $aktivitasModel->log(
            (int) $user['id'],
            'Login berhasil',
            'Pengguna berhasil masuk ke sistem sebagai ' . $user['role'] . '.',
            'auth',
            'users',
            (int) $user['id']
        );

        // Tujuan redirect berdasarkan role.
        $dashboardRoutes = [
            'mahasiswa'          => '/dashboard/mahasiswa',
            'dosen'              => '/dashboard/dosen',
            'koordinator_magang' => '/dashboard/koordinator-magang',
            'tendik'             => '/dashboard/tendik',
            'mitra'              => '/dashboard/mitra',
        ];

        // Arahkan ke dashboard sesuai role.
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