<?php

require_once __DIR__ . '/../../../models/User.php';
require_once __DIR__ . '/../../../models/AktivitasPengguna.php';

class PengaturanController
{
    private User $userModel;
    private AktivitasPengguna $aktivitasModel;


    public function __construct()
    {
        global $pdo;

        $this->userModel = new User($pdo);
        $this->aktivitasModel = new AktivitasPengguna();
    }


    /*
    |--------------------------------------------------------------------------
    | Session
    |--------------------------------------------------------------------------
    */

    private function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | User yang sedang login
    |--------------------------------------------------------------------------
    */

    private function getUserId(): int
    {
        $this->startSession();

        $userId = $_SESSION['user']['id'] ?? null;

        if (!is_numeric($userId) || (int) $userId < 1) {
            http_response_code(401);

            exit(
                'Sesi pengguna tidak valid. Silakan login kembali.'
            );
        }

        return (int) $userId;
    }


    /*
    |--------------------------------------------------------------------------
    | Validasi POST
    |--------------------------------------------------------------------------
    */

    private function validatePost(): void
    {
        if (
            ($_SERVER['REQUEST_METHOD'] ?? 'GET')
            !== 'POST'
        ) {
            http_response_code(405);

            header('Allow: GET, POST');

            exit('Method Not Allowed');
        }


        if (!verifyCsrfToken()) {
            http_response_code(403);

            exit(
                '403 - Token CSRF tidak valid.'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Halaman Pengaturan
    |--------------------------------------------------------------------------
    */

    public function index(): void
    {
        $userId = $this->getUserId();

        $errors = [];
        $success = null;


        /*
        |--------------------------------------------------------------
        | Pesan berhasil
        |--------------------------------------------------------------
        */

        if (!empty($_SESSION['pengaturan_success'])) {

            $success =
                $_SESSION['pengaturan_success'];

            unset(
                $_SESSION['pengaturan_success']
            );
        }


        /*
        |--------------------------------------------------------------
        | Proses ganti password
        |--------------------------------------------------------------
        */

        if (
            ($_SERVER['REQUEST_METHOD'] ?? 'GET')
            === 'POST'
        ) {

            $this->validatePost();


            $currentPassword =
                (string) (
                    $_POST['current_password']
                    ?? ''
                );

            $newPassword =
                (string) (
                    $_POST['new_password']
                    ?? ''
                );

            $newPasswordConfirmation =
                (string) (
                    $_POST['new_password_confirmation']
                    ?? ''
                );


            /*
            |----------------------------------------------------------
            | Validasi password lama
            |----------------------------------------------------------
            */

            if ($currentPassword === '') {

                $errors['current_password'] =
                    'Kata sandi saat ini wajib diisi.';
            }


            /*
            |----------------------------------------------------------
            | Validasi password baru
            |----------------------------------------------------------
            */

            if ($newPassword === '') {

                $errors['new_password'] =
                    'Kata sandi baru wajib diisi.';

            } elseif (
                strlen($newPassword) < 8
            ) {

                $errors['new_password'] =
                    'Kata sandi baru minimal 8 karakter.';
            }


            /*
            |----------------------------------------------------------
            | Validasi konfirmasi
            |----------------------------------------------------------
            */

            if ($newPasswordConfirmation === '') {

                $errors['new_password_confirmation'] =
                    'Konfirmasi kata sandi wajib diisi.';

            } elseif (
                $newPassword !==
                $newPasswordConfirmation
            ) {

                $errors['new_password_confirmation'] =
                    'Konfirmasi kata sandi tidak sama.';
            }


            /*
            |----------------------------------------------------------
            | Verifikasi password lama
            |----------------------------------------------------------
            */

            if (empty($errors)) {

                $user =
                    $this->userModel
                        ->findById($userId);


                if (!$user) {

                    http_response_code(404);

                    exit(
                        'Pengguna tidak ditemukan.'
                    );
                }


                if (
                    empty($user['password'])
                    ||
                    !password_verify(
                        $currentPassword,
                        $user['password']
                    )
                ) {

                    $errors['current_password'] =
                        'Kata sandi saat ini salah.';
                }
            }


            /*
            |----------------------------------------------------------
            | Simpan password baru
            |----------------------------------------------------------
            */

            if (empty($errors)) {

                $passwordHash =
                    password_hash(
                        $newPassword,
                        PASSWORD_DEFAULT
                    );


                $this->userModel
                    ->updatePassword(
                        $userId,
                        $passwordHash
                    );

                $this->aktivitasModel->log(
                    $userId,
                    'Kata sandi diperbarui',
                    'Kata sandi akun berhasil diperbarui melalui pengaturan.',
                    'pengaturan',
                    'users',
                    $userId
                );


                $_SESSION['pengaturan_success'] =
                    'Kata sandi berhasil diperbarui.';


                header(
                    'Location: '
                    . url(
                        '/dashboard/mahasiswa/pengaturan'
                    )
                );

                exit;
            }
        }


        /*
        |--------------------------------------------------------------
        | View
        |--------------------------------------------------------------
        */

        require __DIR__
            . '/../../../pages/dashboard/mahasiswa/pengaturan/index.php';
    }
}