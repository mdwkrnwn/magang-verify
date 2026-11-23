
<?php

require_once __DIR__ . '/../../../models/User.php';
require_once __DIR__ . '/../../../models/AktivitasPengguna.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../middleware/MitraAuthMiddleware.php';

abstract class PengaturanBaseController
{
    protected User $userModel;
    protected AktivitasPengguna $aktivitasModel;

    public function __construct()
    {
        global $pdo;

        $this->userModel = new User($pdo);
        $this->aktivitasModel = new AktivitasPengguna();
    }

    /**
     * Konfigurasi role dan halaman
     */
    abstract protected function getRole(): string;

    abstract protected function getViewPath(): string;

    abstract protected function getRedirectUrl(): string;

    /**
     * Session
     */
    protected function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Mendapatkan ID pengguna yang login
     */
    protected function getUserId(): int
    {
        $this->startSession();

        // =========================================
        // AUTENTIKASI MITRA VIA ACCESS LINK
        // =========================================
        if ($this->getRole() === 'mitra') {
            $userId = MitraAuthMiddleware::currentUserId();

            if ($userId === null) {
                http_response_code(403);
                exit('Pengaturan kata sandi memerlukan akun pengguna Mitra. Silakan hubungi koordinator untuk menghubungkan akun.');
            }

            return $userId;
        }

        // =========================================
        // AUTENTIKASI ROLE LAIN
        // =========================================
        AuthMiddleware::handle($this->getRole());

        $userId = $_SESSION['user']['id'] ?? null;

        if (!is_numeric($userId) || (int) $userId < 1) {
            http_response_code(401);
            exit('Sesi pengguna tidak valid. Silakan login kembali.');
        }

        return (int) $userId;
    }

    /**
     * Validasi request POST dan CSRF
     */
    protected function validatePost(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            http_response_code(405);
            header('Allow: GET, POST');
            exit('Method Not Allowed');
        }

        if (!verifyCsrfToken()) {
            http_response_code(403);
            exit('403 - Token CSRF tidak valid.');
        }
    }

    /**
     * Memproses pergantian password
     */
    protected function processPasswordChange(int $userId): array
    {
        $errors = [];

        $currentPassword = (string) (
            $_POST['current_password'] ?? ''
        );

        $newPassword = (string) (
            $_POST['new_password'] ?? ''
        );

        $newPasswordConfirmation = (string) (
            $_POST['new_password_confirmation'] ?? ''
        );

        // Validasi password saat ini
        if ($currentPassword === '') {
            $errors['current_password'] =
                'Kata sandi saat ini wajib diisi.';
        }

        // Validasi password baru
        if ($newPassword === '') {
            $errors['new_password'] =
                'Kata sandi baru wajib diisi.';
        } elseif (mb_strlen($newPassword) < 8) {
            $errors['new_password'] =
                'Kata sandi baru minimal 8 karakter.';
        }

        // Validasi konfirmasi password
        if ($newPasswordConfirmation === '') {
            $errors['new_password_confirmation'] =
                'Konfirmasi kata sandi wajib diisi.';
        } elseif ($newPassword !== $newPasswordConfirmation) {
            $errors['new_password_confirmation'] =
                'Konfirmasi kata sandi tidak sama.';
        }

        // Hentikan jika validasi awal gagal
        if (!empty($errors)) {
            return $errors;
        }

        // Ambil data pengguna
        $user = $this->userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            exit('Pengguna tidak ditemukan.');
        }

        // Verifikasi password lama
        if (
            empty($user['password']) ||
            !password_verify(
                $currentPassword,
                $user['password']
            )
        ) {
            $errors['current_password'] =
                'Kata sandi saat ini salah.';

            return $errors;
        }

        // Password baru tidak boleh sama dengan password lama
        if (password_verify($newPassword, $user['password'])) {
            $errors['new_password'] =
                'Kata sandi baru harus berbeda dari kata sandi saat ini.';

            return $errors;
        }

        // Generate password hash
        $passwordHash = password_hash(
            $newPassword,
            PASSWORD_DEFAULT
        );

        if ($passwordHash === false) {
            throw new RuntimeException(
                'Gagal memproses kata sandi baru.'
            );
        }

        // Simpan password baru
        $updated = $this->userModel->updatePassword(
            $userId,
            $passwordHash
        );

        if (!$updated) {
            throw new RuntimeException(
                'Kata sandi gagal diperbarui.'
            );
        }

        // Catat aktivitas pengguna
        $this->aktivitasModel->log(
            $userId,
            'Kata sandi diperbarui',
            'Kata sandi akun berhasil diperbarui melalui pengaturan.',
            'pengaturan',
            'users',
            $userId
        );

        return [];
    }

    /**
     * Halaman pengaturan
     */
    public function index(): void
    {
        $userId = $this->getUserId();

        $errors = [];
        $success = null;

        // Ambil pesan sukses
        if (!empty($_SESSION['pengaturan_success'])) {
            $success = $_SESSION['pengaturan_success'];

            unset($_SESSION['pengaturan_success']);
        }

        // Proses POST
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {

            $this->validatePost();

            $errors = $this->processPasswordChange($userId);

            if (empty($errors)) {

                $_SESSION['pengaturan_success'] =
                    'Kata sandi berhasil diperbarui.';

                header(
                    'Location: ' . url($this->getRedirectUrl()),
                    true,
                    303
                );

                exit;
            }
        }

        // Tampilkan halaman sesuai role
        require $this->getViewPath();
    }
}
