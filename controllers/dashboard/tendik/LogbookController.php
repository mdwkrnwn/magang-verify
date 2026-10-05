<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../function/Helpers.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/Logbook.php';

class TendikLogbookController
{
    private Logbook $model;

    public function __construct()
    {
        $this->model = new Logbook();
    }

    private function userId(): int
    {
        AuthMiddleware::handle('tendik');
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
        $items = $this->model->getReviewQueue($userId, 'tendik');
        require __DIR__ . '/../../../pages/dashboard/tendik/logbook/index.php';
    }

    public function detail($params = []): void
    {
        $userId = $this->userId();
        $week = $this->model->getWeekForRole((int) ($params['id'] ?? 0), $userId, 'tendik');
        if (!$week) {
            http_response_code(404);
            exit('Logbook tidak ditemukan.');
        }
        require __DIR__ . '/../../../pages/dashboard/tendik/logbook/detail.php';
    }

    public function validate($params = []): void
    {
        $userId = $this->userId();
        $this->validatePost();
        try {
            $decision = (string) ($_POST['decision'] ?? '');
            $note = trim((string) ($_POST['catatan'] ?? ''));
            $this->model->validateDaily((int) ($params['id'] ?? 0), $userId, 'tendik', $decision, $note !== '' ? $note : null);
            header('Location: ' . url('/dashboard/tendik/logbook/detail/' . (int) ($_POST['week_id'] ?? 0)));
            exit;
        } catch (Throwable $e) {
            http_response_code(422);
            exit(e($e->getMessage()));
        }
    }
}
