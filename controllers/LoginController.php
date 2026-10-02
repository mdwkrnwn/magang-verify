<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

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

        // Ambil data dari form.
        $role = $_POST['role'] ?? '';
        $loginId = trim($_POST['login_id'] ?? '');
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
            !is_string($loginId) ||
            $loginId === ''
        ) {
            $this->redirectToLogin();
        }

        // Cari pengguna berdasarkan NIM, NIP, atau kode mitra.
        $user = $this->userModel->findByLoginId($loginId);

        // Pastikan akun ditemukan, role cocok, dan akun aktif.
        if (
            !$user ||
            $user['role'] !== $role ||
            !$this->isAccountActive($user['is_active'])
        ) {
            $this->redirectToLogin();
        }

        // Semua role selain mitra wajib menggunakan password.
        if ($role !== 'mitra') {
            if (
                !is_string($password) ||
                $password === '' ||
                !is_string($user['password']) ||
                $user['password'] === '' ||
                !password_verify($password, $user['password'])
            ) {
                $this->redirectToLogin();
            }
        }

        // Login berhasil: perbarui ID session.
        session_regenerate_id(true);

        // Simpan informasi pengguna yang diperlukan.
        $_SESSION['user'] = [
            'id'       => $user['id'],
            'login_id' => $user['login_id'],
            'name'     => $user['name'],
            'role'     => $user['role'],
        ];

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
     * Memeriksa status aktif akun dari PostgreSQL.
     */
    private function isAccountActive($value): bool
    {
        return in_array(
            $value,
            [true, 1, '1', 't', 'true'],
            true
        );
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