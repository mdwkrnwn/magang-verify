
<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/PasswordReset.php';
require_once __DIR__ . '/../helpers/Mailer.php';
class ForgotPasswordController
{
    private PDO $pdo;
    private PasswordReset $passwordResetModel;

    public function __construct()
    {
        global $pdo;

        $this->pdo = $pdo;
        $this->passwordResetModel = new PasswordReset($pdo);
    }

    /**
     * Halaman permintaan reset password.
     */
    public function index($params = []): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            require __DIR__ . '/../pages/login/forgot-password.php';
            return;
        }

        if (!verifyCsrfToken()) {
            http_response_code(403);

            $errorTitle = 'Permintaan tidak dapat diproses';
            $errorMessage = 'Sesi formulir sudah tidak valid atau permintaan tidak sesuai. Silakan kembali ke halaman sebelumnya dan coba kirim formulir lagi.';

            require dirname(__DIR__) . '/pages/errors/403.php';
            exit;
        }

        $identity = $_POST['identity'] ?? '';

        if (!is_string($identity)) {
            $this->redirectForgot('error');
        }

        $identity = trim($identity);

        if ($identity === '' || mb_strlen($identity) > 50) {
            $this->redirectForgot('error');
        }

        try {
            $account = $this->passwordResetModel
                ->findAccountByIdentity($identity);

            if ($account) {
                $this->createResetRequest($account);
            }
        } catch (Throwable $exception) {
            error_log(
                '[FORGOT PASSWORD] ' . $exception->getMessage()
            );
        }

        // Pesan dibuat sama untuk mencegah kebocoran
        // informasi apakah NIM/NIDN terdaftar.
        $this->redirectForgot('sent');
    }

    /**
     * Membuat token reset password.
     */

    private function createResetRequest(array $account): void
    {
        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);

        $this->passwordResetModel->create(
            (int) $account['user_id'],
            $tokenHash,
            30
        );

        $resetUrl = 'http://' . $_SERVER['HTTP_HOST']
            . APP_URL
            . '/reset-password?token=' . urlencode($token);

        $sent = sendResetPasswordEmail(
            $account['email'],
            $account['name'],
            $resetUrl
        );

        if (!$sent) {
            $this->passwordResetModel->deleteByTokenHash($tokenHash);

            error_log(
                '[FORGOT PASSWORD] Email gagal dikirim. User ID: '
                    . $account['user_id']
            );
        }
    }


    /**
     * Halaman dan proses reset password.
     */
    public function reset($params = []): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {

            $status = $_GET['status'] ?? '';

            if ($status === 'invalid') {
                require __DIR__ . '/../pages/login/reset-password.php';
                return;
            }

            $token = $_GET['token'] ?? '';

            if (
                !is_string($token) ||
                !preg_match('/^[a-f0-9]{64}$/i', $token)
            ) {
                $this->redirectReset('invalid');
            }

            try {
                $reset = $this->passwordResetModel->findValidToken(
                    hash('sha256', $token)
                );

                if (!$reset) {
                    $this->redirectReset('invalid');
                }
            } catch (Throwable $exception) {
                error_log('[PASSWORD RESET] ' . $exception->getMessage());

                http_response_code(500);

                $errorTitle = 'Reset password tidak dapat diproses';
                $errorMessage = 'Kami sedang mengalami kendala saat memeriksa link reset password kamu. Silakan coba kembali beberapa saat lagi.';
                $errorInfo = 'Terjadi gangguan pada proses reset password';

                require dirname(__DIR__) . '/pages/errors/500.php';
                exit;
            }

            require __DIR__ . '/../pages/login/reset-password.php';
            return;
        }

        if (!verifyCsrfToken()) {
            http_response_code(403);

            $errorTitle = 'Permintaan reset password tidak valid';
            $errorMessage = 'Sesi formulir reset password kamu sudah tidak valid. Silakan buka kembali link reset password dan coba lakukan proses penggantian password.';

            require dirname(__DIR__) . '/pages/errors/403.php';
            exit;
        }
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $passwordConfirmation = $_POST['password_confirmation'] ?? '';

        if (
            !is_string($token) ||
            !preg_match('/^[a-f0-9]{64}$/i', $token)
        ) {
            $this->redirectReset('invalid');
        }

        if (
            !is_string($password) ||
            !is_string($passwordConfirmation) ||
            mb_strlen($password) < 8 ||
            $password !== $passwordConfirmation
        ) {
            $this->redirectReset('error', $token);
        }

        $tokenHash = hash('sha256', $token);

        try {
            $reset = $this->passwordResetModel
                ->findValidToken($tokenHash);

            if (!$reset) {
                $this->redirectReset('invalid');
            }

            $passwordHash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $success = $this->passwordResetModel->resetPassword(
                (int) $reset['reset_id'],
                (int) $reset['user_id'],
                $passwordHash
            );

            if (!$success) {
                $this->redirectReset('invalid');
            }

            header(
                'Location: ' . APP_URL . '/login?reset=success',
                true,
                302
            );
            exit;
        } catch (Throwable $exception) {
            error_log(
                '[PASSWORD RESET] ' . $exception->getMessage()
            );

            http_response_code(500);

            $errorTitle = 'Password gagal diperbarui';
            $errorMessage = 'Terjadi kendala saat memperbarui password kamu. Silakan coba kembali beberapa saat lagi.';
            $errorInfo = 'Terjadi gangguan pada proses reset password';

            require dirname(__DIR__) . '/pages/errors/500.php';
            exit;
        }
    }

    private function redirectForgot(string $status): void
    {
        header(
            'Location: ' . APP_URL . '/forgot-password?status=' . urlencode($status),
            true,
            302
        );
        exit;
    }

    private function redirectReset(
        string $status,
        string $token = ''
    ): void {
        $url = APP_URL . '/reset-password?status=' . urlencode($status);

        if ($token !== '') {
            $url .= '&token=' . urlencode($token);
        }

        header('Location: ' . $url, true, 302);
        exit;
    }
}
