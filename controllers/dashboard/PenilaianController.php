
<?php


require_once __DIR__ . '/../../function/Helpers.php';
require_once __DIR__ . '/../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../middleware/MitraAuthMiddleware.php';
require_once __DIR__ . '/../../models/PenilaianMagang.php';

class PenilaianController
{
    public function index($params = []): void
    {
        global $pdo;

        /*
         * =========================================================
         * IDENTIFIKASI ROLE DAN USER
         * =========================================================
         */

        $role = '';
        $identityId = 0;
        $actorUserId = null;

        // =========================================================
        // DOSEN: Tetap menggunakan autentikasi normal
        // =========================================================
        if (($_SESSION['user']['role'] ?? '') === 'dosen') {

            $role = 'dosen';

            AuthMiddleware::handle('dosen');

            $identityId = (int) ($_SESSION['user']['id'] ?? 0);
            $actorUserId = $identityId;

            // =========================================================
            // MITRA: Menggunakan session login melalui access link
            // =========================================================
        } elseif (isset($_SESSION['mitra']['id'])) {

            $role = 'mitra';
            $identityId = MitraAuthMiddleware::currentMitraId();
            $actorUserId = MitraAuthMiddleware::currentUserId();
        } else {

            http_response_code(403);
            exit('Role tidak memiliki akses ke penilaian magang.');
        }

        // =========================================================
        // VALIDASI USER ID
        // =========================================================
        if ($identityId < 1) {
            http_response_code(401);
            exit('Sesi pengguna tidak valid.');
        }

        /*
         * =========================================================
         * LOAD DATA PENILAIAN
         * =========================================================
         */

        $model = new PenilaianMagang();
        $items = $model->getAssignments($identityId, $role, $actorUserId);

        $error = null;
        $success = ($_GET['success'] ?? '') === '1';

        /*
         * =========================================================
         * PROSES POST
         * =========================================================
         */

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {

            if (!verifyCsrfToken()) {
                http_response_code(403);
                exit('Permintaan tidak valid. Silakan muat ulang halaman.');
            }

            $placementId = filter_var(
                $_POST['penempatan_id'] ?? null,
                FILTER_VALIDATE_INT
            );

            $action = (string) ($_POST['action'] ?? '');

            if (
                !$placementId ||
                !in_array($action, ['draft', 'submit'], true)
            ) {
                $error = 'Data penilaian tidak valid.';
            } else {

                try {

                    $model->save(
                        $identityId,
                        $role,
                        $placementId,
                        $_POST,
                        $action === 'submit',
                        $actorUserId
                    );

                    header(
                        'Location: ' . url(
                            (
                                $role === 'dosen'
                                ? '/dashboard/dosen/penilaian'
                                : '/dashboard/mitra/penilaian'
                            ) . '?success=1'
                        )
                    );

                    exit;
                } catch (PDOException $e) {

                    error_log(
                        'Gagal menyimpan penilaian magang: ' .
                            $e->getMessage()
                    );

                    $error = 'Penilaian belum dapat disimpan. Silakan coba lagi.';
                } catch (Throwable $e) {

                    $error = $e->getMessage();
                }

                $items = $model->getAssignments($identityId, $role, $actorUserId);
            }
        }

        /*
         * =========================================================
         * TAMPILAN BERDASARKAN ROLE
         * =========================================================
         */

        if ($role === 'dosen') {

            $active = 'monitoring';

            require __DIR__ . '/../../pages/dashboard/dosen/penilaian/index.php';
        } else {

            $active = 'penilaian';

            require __DIR__ . '/../../pages/dashboard/mitra/penilaian/index.php';
        }
    }
}
