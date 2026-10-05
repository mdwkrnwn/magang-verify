<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../function/Helpers.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/Logbook.php';

class DosenLogbookController
{
    private Logbook $model;

    public function __construct()
    {
        $this->model = new Logbook();
    }

    private function userId(): int
    {
        AuthMiddleware::handle('dosen');
        $id = (int) ($_SESSION['user']['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(401);
            exit('Sesi pengguna tidak valid.');
        }
        return $id;
    }

    private function validatePost(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST' || !verifyCsrfToken()) {
            http_response_code(403);
            exit('Permintaan tidak valid.');
        }
    }

    public function index(): void
    {
        $userId = $this->userId();
        $items = $this->model->getReviewQueue($userId, 'dosen');
        require __DIR__ . '/../../../pages/dashboard/dosen/logbook/index.php';
    }

    public function detail($params = []): void
    {
        $userId = $this->userId();
        $week = $this->model->getWeekForRole((int) ($params['id'] ?? 0), $userId, 'dosen');
        if (!$week) {
            http_response_code(404);
            exit('Logbook tidak ditemukan atau bukan mahasiswa bimbingan Anda.');
        }
        require __DIR__ . '/../../../pages/dashboard/dosen/logbook/detail.php';
    }

    public function sign($params = []): void
    {
        $userId = $this->userId();
        $this->validatePost();
        try {
            $this->model->signWeek((int) ($params['id'] ?? 0), $userId, 'dosen', trim((string) ($_POST['signature_data'] ?? '')));
            header('Location: ' . url('/dashboard/dosen/logbook/detail/' . (int) $params['id']));
            exit;
        } catch (Throwable $e) {
            http_response_code(422);
            exit(e($e->getMessage()));
        }
    }
}
