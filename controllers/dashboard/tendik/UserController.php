<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../function/Helpers.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/User.php';
require_once __DIR__ . '/../../../models/AktivitasPengguna.php';

final class TendikUserController
{
    private User $users;

    public function __construct()
    {
        global $pdo;
        $this->users = new User($pdo);
    }

    private function actorId(): int
    {
        AuthMiddleware::handle('tendik');
        $id = (int) ($_SESSION['user']['id'] ?? 0);
        if ($id < 1) {
            http_response_code(401);
            exit('Sesi pengguna tidak valid.');
        }
        return $id;
    }

    public function index(): void
    {
        $this->actorId();
        $filters = [
            'search' => trim((string) ($_GET['search'] ?? '')),
            'role' => trim((string) ($_GET['role'] ?? '')),
            'status' => trim((string) ($_GET['status'] ?? '')),
        ];
        $items = $this->users->getForManagement($filters);
        $roles = User::allowedRoles();
        $error = null;
        require __DIR__ . '/../../../pages/dashboard/tendik/users/index.php';
    }

    public function create(): void
    {
        $actorId = $this->actorId();
        $roles = User::allowedRoles();
        $form = ['login_id' => '', 'name' => '', 'nim' => '', 'roles' => []];
        $error = null;
        $success = null;
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            if (!verifyCsrfToken()) {
                http_response_code(403);
                exit('Permintaan tidak valid.');
            }
            $form = [
                'login_id' => trim((string) ($_POST['login_id'] ?? '')),
                'name' => trim((string) ($_POST['name'] ?? '')),
                'nim' => trim((string) ($_POST['nim'] ?? '')),
                'roles' => is_array($_POST['roles'] ?? null) ? $_POST['roles'] : [],
            ];
            try {
                $id = $this->users->createManaged([
                    'login_id' => $form['login_id'],
                    'name' => $form['name'],
                    'nim' => $form['nim'],
                    'password' => (string) ($_POST['password'] ?? ''),
                    'roles' => $form['roles'],
                ], $actorId);
                (new AktivitasPengguna())->log($actorId, 'User dibuat', 'User #' . $id . ' dibuat oleh Tendik.', 'users', 'users', $id);
                $success = 'User berhasil dibuat.';
                $form = ['login_id' => '', 'name' => '', 'nim' => '', 'roles' => []];
            } catch (PDOException $exception) {
                error_log('Gagal membuat user: ' . $exception->getMessage());
                $error = 'User gagal dibuat. Pastikan ID login belum digunakan.';
            } catch (Throwable $exception) {
                $error = $exception->getMessage();
            }
        }
        require __DIR__ . '/../../../pages/dashboard/tendik/users/create.php';
    }


    public function detail(array $params = []): void
    {
        $actorId = $this->actorId();

        $id = (int) ($params['id'] ?? 0);

        if ($id < 1) {
            http_response_code(404);
            exit('Pengguna tidak ditemukan.');
        }

        $user = $this->users->getManagementDetail($id);

        if (!$user) {
            http_response_code(404);
            exit('Pengguna tidak ditemukan.');
        }

        $error = null;
        $success = null;

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            if (!verifyCsrfToken()) {
                http_response_code(403);
                exit('Permintaan tidak valid.');
            }

            try {
                $this->users->updateManagementProfile($id, [
                    'nim' => $_POST['nim'] ?? '',
                    'program_studi' => $_POST['program_studi'] ?? '',
                    'angkatan' => $_POST['angkatan'] ?? '',
                    'nidn' => $_POST['nidn'] ?? '',
                ]);

                (new AktivitasPengguna())->log(
                    $actorId,
                    'Profil pengguna diperbarui',
                    'Data profil user #' . $id . ' diperbarui oleh Tendik.',
                    'users',
                    'users',
                    $id
                );

                $success = 'Informasi pengguna berhasil diperbarui.';
                $user = $this->users->getManagementDetail($id);
            } catch (Throwable $exception) {
                $error = $exception->getMessage();
            }
        }

        require __DIR__ . '/../../../pages/dashboard/tendik/users/detail.php';
    }
}
