
<?php



/*
|--------------------------------------------------------------------------
| ForgotPasswordController
|--------------------------------------------------------------------------
| Mengelola proses pemulihan password mahasiswa dan dosen.
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/PasswordReset.php';

class ForgotPasswordController
{
    /**
     * Halaman lupa password.
     *
     * GET  : Menampilkan form.
     * POST : Memproses permintaan reset password.
     */
    public function index($params = []): void
    {
        // Mulai session.
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Tampilkan halaman jika GET.
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            require __DIR__ . '/../pages/login/forgot-password.php';
            return;
        }

        // Validasi CSRF.
        if (!verifyCsrfToken()) {
            http_response_code(403);
            exit('Permintaan tidak valid. Silakan muat ulang halaman.');
        }

        // Proses permintaan reset password.
        $this->sendResetLink();
    }

    /**
     * Memproses pengiriman tautan reset password.
     */
    private function sendResetLink(): void
    {
        // Implementasi akan dibuat pada tahap backend:
        // 1. Validasi NIM/NIP.
        // 2. Cari akun mahasiswa atau dosen.
        // 3. Ambil email dari tabel profil.
        // 4. Buat token reset yang aman.
        // 5. Simpan hash token ke database.
        // 6. Kirim email pemulihan.
    }

    /**
     * Halaman pengaturan password baru.
     */
    public function reset($params = []): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        require __DIR__ . '/../pages/login/reset-password.php';
    }
}
