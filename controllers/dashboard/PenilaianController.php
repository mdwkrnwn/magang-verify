
<?php


require_once __DIR__ . '/../../function/Helpers.php';
require_once __DIR__ . '/../../middleware/AuthMiddleware.php';
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
        $userId = 0;

        // =========================================================
        // DOSEN: Tetap menggunakan autentikasi normal
        // =========================================================
        if (($_SESSION['user']['role'] ?? '') === 'dosen') {

            $role = 'dosen';

            AuthMiddleware::handle('dosen');

            $userId = (int) ($_SESSION['user']['id'] ?? 0);

            // =========================================================
            // MITRA: Menggunakan session login melalui access link
            // =========================================================
        } elseif (isset($_SESSION['mitra']['id'])) {

            $role = 'mitra';

            $mitraId = (int) ($_SESSION['mitra']['id'] ?? 0);

            if ($mitraId < 1) {
                http_response_code(401);
                exit('Sesi Mitra tidak valid.');
            }

            // Ambil user_id berdasarkan mitra.id
            $stmt = $pdo->prepare("
                SELECT user_id
                FROM mitra
                WHERE id = :mitra_id
                LIMIT 1
            ");

            $stmt->execute([
                'mitra_id' => $mitraId
            ]);

            $userId = (int) $stmt->fetchColumn();
        } else {

            http_response_code(403);
            exit('Role tidak memiliki akses ke penilaian magang.');
        }

        // =========================================================
        // VALIDASI USER ID
        // =========================================================
        if ($userId < 1) {
            http_response_code(401);
            exit('Sesi pengguna tidak valid.');
        }

        /*
         * =========================================================
         * LOAD DATA PENILAIAN
         * =========================================================
         */

        $model = new PenilaianMagang();
        $items = $model->getAssignments($userId, $role);

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
                        $userId,
                        $role,
                        $placementId,
                        $_POST,
                        $action === 'submit'
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

                $items = $model->getAssignments($userId, $role);
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
