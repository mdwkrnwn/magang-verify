<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../function/Helpers.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/Logbook.php';

class LogbookController
{
    private Logbook $model;

    public function __construct()
    {
        $this->model = new Logbook();
    }

    private function userId(): int
    {
        AuthMiddleware::handle('mahasiswa');
        $id = $_SESSION['user']['id'] ?? null;
        if (!is_numeric($id) || (int) $id < 1) {
            http_response_code(401);
            exit('Sesi pengguna tidak valid.');
        }
        return (int) $id;
    }

    private function redirect(string $path): never
    {
        header('Location: ' . url($path));
        exit;
    }

    private function validatePost(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST' || !verifyCsrfToken()) {
            http_response_code(403);
            exit('Permintaan tidak valid.');
        }
    }

    private function formData(): array
    {
        $data = [
            'minggu_ke' => filter_var($_POST['minggu_ke'] ?? null, FILTER_VALIDATE_INT),
            'tanggal_mulai' => trim((string) ($_POST['tanggal_mulai'] ?? '')),
            'tanggal_selesai' => trim((string) ($_POST['tanggal_selesai'] ?? '')),
            'aktivitas' => trim((string) ($_POST['aktivitas'] ?? '')),
            'hasil_pekerjaan' => trim((string) ($_POST['hasil_pekerjaan'] ?? '')),
            'kendala' => trim((string) ($_POST['kendala'] ?? '')),
            'rencana_selanjutnya' => trim((string) ($_POST['rencana_selanjutnya'] ?? '')),
        ];

        if (!is_int($data['minggu_ke']) || $data['minggu_ke'] < 1) {
            throw new InvalidArgumentException('Minggu ke harus berupa angka minimal 1.');
        }
        if ($data['aktivitas'] === '') {
            throw new InvalidArgumentException('Aktivitas wajib diisi.');
        }
        if (mb_strlen($data['aktivitas']) > 10000 || mb_strlen($data['hasil_pekerjaan']) > 10000 || mb_strlen($data['kendala']) > 10000 || mb_strlen($data['rencana_selanjutnya']) > 10000) {
            throw new InvalidArgumentException('Isi logbook terlalu panjang.');
        }

        foreach (['tanggal_mulai', 'tanggal_selesai'] as $field) {
            $date = DateTime::createFromFormat('Y-m-d', $data[$field]);
            if (!$date || $date->format('Y-m-d') !== $data[$field]) {
                throw new InvalidArgumentException('Tanggal logbook tidak valid.');
            }
        }
        if ($data['tanggal_selesai'] < $data['tanggal_mulai']) {
            throw new InvalidArgumentException('Tanggal selesai tidak boleh mendahului tanggal mulai.');
        }

        return $data;
    }

    public function index($params = []): void
    {
        $userId = $this->userId();
        $placement = $this->model->getPlacement($userId);
        $summary = $this->model->getSummary($userId);
        $logbooks = $this->model->getRecent($userId, 8);
        $canAdd = $placement && in_array($placement['status'], ['berlangsung', 'menunggu_penilaian'], true);

        require __DIR__ . '/../../../pages/dashboard/mahasiswa/logbook/index.php';
    }

    public function create(): void
    {
        $userId = $this->userId();
        $placement = $this->model->getPlacement($userId);
        if (!$placement || !in_array($placement['status'], ['berlangsung', 'menunggu_penilaian'], true)) {
            http_response_code(409);
            exit('Logbook hanya dapat dibuat saat proses magang berlangsung.');
        }

        $mode = 'create';
        $item = [
            'minggu_ke' => '',
            'tanggal_mulai' => '',
            'tanggal_selesai' => '',
            'aktivitas' => '',
            'hasil_pekerjaan' => '',
            'kendala' => '',
            'rencana_selanjutnya' => '',
        ];
        $error = null;

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $this->validatePost();
            try {
                $data = $this->formData();
                $id = $this->model->create($userId, $data);
                $this->redirect('/dashboard/mahasiswa/logbook?success=created');
            } catch (Throwable $e) {
                $error = $e->getMessage();
                $item = array_merge($item, $_POST);
            }
        }

        require __DIR__ . '/../../../pages/dashboard/mahasiswa/logbook/form.php';
    }

    public function edit($params = []): void
    {
        $userId = $this->userId();
        $id = (int) ($params['id'] ?? 0);
        $item = $this->model->getById($id, $userId);
        if (!$item) {
            http_response_code(404);
            exit('Logbook tidak ditemukan.');
        }
        if (!in_array($item['status'], ['draft', 'perlu_revisi'], true)) {
            http_response_code(403);
            exit('Logbook yang sudah diajukan tidak dapat diedit.');
        }

        $mode = 'edit';
        $error = null;
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $this->validatePost();
            try {
                $data = $this->formData();
                $this->model->update($id, $userId, $data);
                $this->redirect('/dashboard/mahasiswa/logbook?success=updated');
            } catch (Throwable $e) {
                $error = $e->getMessage();
                $item = array_merge($item, $_POST);
            }
        }

        require __DIR__ . '/../../../pages/dashboard/mahasiswa/logbook/form.php';
    }

    public function submit($params = []): void
    {
        $this->validatePost();
        $userId = $this->userId();
        try {
            $this->model->submit((int) ($params['id'] ?? 0), $userId);
            $this->redirect('/dashboard/mahasiswa/logbook?success=submitted');
        } catch (Throwable $e) {
            http_response_code(422);
            exit(e($e->getMessage()));
        }
    }
}
