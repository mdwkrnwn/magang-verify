<?php

declare(strict_types=1);

require_once __DIR__ . '/../../function/Helpers.php';
require_once __DIR__ . '/../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../models/Notifikasi.php';

class NotifikasiController
{
    private const ROLE_LAYOUTS = [
        'mahasiswa' => 'mahasiswa',
        'dosen' => 'dosen',
        'mitra' => 'mitra',
        'koordinator_magang' => 'koordinatorMagang',
        'tendik' => 'tendik',
    ];

    public function index($params = []): void
    {
        $role = (string) ($_SESSION['user']['role'] ?? '');
        if (!isset(self::ROLE_LAYOUTS[$role])) {
            http_response_code(403);
            exit('Akses ditolak.');
        }
        AuthMiddleware::handle($role);
        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        if ($userId < 1) {
            http_response_code(401);
            exit('Sesi pengguna tidak valid.');
        }

        $model = new Notifikasi();
        $error = null;
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            if (!verifyCsrfToken()) {
                http_response_code(403);
                exit('Permintaan tidak valid. Silakan muat ulang halaman.');
            }

            try {
                $action = (string) ($_POST['action'] ?? '');
                if ($action === 'mark_read') {
                    $notificationId = filter_var($_POST['notification_id'] ?? null, FILTER_VALIDATE_INT);
                    if (!$notificationId || $notificationId < 1) {
                        throw new InvalidArgumentException('Notifikasi tidak valid.');
                    }
                    $model->markRead($userId, $notificationId);
                } elseif ($action === 'mark_all_read') {
                    $model->markAllRead($userId);
                } else {
                    throw new InvalidArgumentException('Aksi notifikasi tidak valid.');
                }

                header('Location: ' . url('/dashboard/notifikasi'));
                exit;
            } catch (PDOException $exception) {
                error_log('Gagal memperbarui status notifikasi: ' . $exception->getMessage());
                $error = 'Status notifikasi belum dapat diperbarui. Silakan coba lagi.';
            } catch (Throwable $exception) {
                $error = $exception->getMessage();
            }
        } elseif (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
            http_response_code(405);
            header('Allow: GET, POST');
            exit('Method Not Allowed');
        }

        try {
            $items = $model->getForUser($userId);
            $unreadCount = $model->countUnread($userId);
        } catch (PDOException $exception) {
            error_log('Gagal mengambil notifikasi: ' . $exception->getMessage());
            http_response_code(500);
            $items = [];
            $unreadCount = 0;
            $error = 'Notifikasi belum dapat dimuat. Silakan coba lagi nanti.';
        }

        $dashboard = ['notifikasi_belum_dibaca' => $unreadCount];
        $layout = self::ROLE_LAYOUTS[$role];
        $active = '';
        require __DIR__ . '/../../pages/dashboard/shared/notifikasi.php';
    }
}
