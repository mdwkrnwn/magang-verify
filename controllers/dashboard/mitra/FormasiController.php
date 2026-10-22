<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../function/Helpers.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/FormasiMitra.php';

class MitraFormasiController
{
    public function index($params = []): void
    {
        $userId = $this->userId();
        $items = (new FormasiMitra())->getForCompany($userId);
        require __DIR__ . '/../../../pages/dashboard/mitra/formasi/index.php';
    }

    public function create($params = []): void
    {
        $this->form(null);
    }

    public function edit(array $params = []): void
    {
        $this->form((int) ($params['id'] ?? 0));
    }

    public function changeStatus(array $params = []): never
    {
        $userId = $this->userId();
        $this->validatePost();
        $id = (int) ($params['id'] ?? 0);
        try {
            (new FormasiMitra())->changeStatus($userId, $id, (string) ($_POST['status'] ?? ''));
            header('Location: ' . url('/dashboard/mitra/formasi?success=1'));
            exit;
        } catch (PDOException $e) {
            error_log('Gagal mengubah status formasi mitra: ' . $e->getMessage());
            http_response_code(422);
            exit('Status formasi belum dapat diubah.');
        } catch (Throwable $e) {
            http_response_code(422);
            exit(e($e->getMessage()));
        }
    }

    public function delete(array $params = []): never
    {
        $userId = $this->userId();
        $this->validatePost();
        try {
            (new FormasiMitra())->delete($userId, (int) ($params['id'] ?? 0));
            header('Location: ' . url('/dashboard/mitra/formasi?success=1'));
            exit;
        } catch (PDOException $e) {
            error_log('Gagal menghapus formasi mitra: ' . $e->getMessage());
            http_response_code(422);
            exit('Formasi belum dapat dihapus.');
        } catch (Throwable $e) {
            http_response_code(422);
            exit(e($e->getMessage()));
        }
    }

    private function form(?int $formasiId): void
    {
        $userId = $this->userId();
        $model = new FormasiMitra();
        $formasi = $formasiId ? $model->getOne($userId, $formasiId) : null;
        if ($formasiId && !$formasi) {
            http_response_code(404);
            exit('Formasi tidak ditemukan.');
        }

        $mode = $formasi ? 'edit' : 'create';
        $error = null;
        $values = $formasi ?? [
            'judul' => '', 'deskripsi' => '', 'bidang' => '', 'jumlah_kuota' => 1,
            'persyaratan' => '', 'lokasi_magang' => '', 'sistem_kerja' => 'onsite',
            'tanggal_mulai' => '', 'tanggal_selesai' => '', 'tahun_akademik' => '', 'status' => 'draft',
        ];

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $this->validatePost();
            $values = array_merge($values, $_POST);
            try {
                if ($mode === 'create') {
                    $model->create($userId, $_POST);
                } else {
                    $model->update($userId, (int) $formasi['id'], $_POST);
                }
                header('Location: ' . url('/dashboard/mitra/formasi?success=1'));
                exit;
            } catch (PDOException $e) {
                error_log('Gagal menyimpan formasi mitra: ' . $e->getMessage());
                $error = 'Formasi belum dapat disimpan. Silakan coba lagi.';
            } catch (Throwable $e) {
                $error = $e->getMessage();
            }
        }

        require __DIR__ . '/../../../pages/dashboard/mitra/formasi/form.php';
    }

    private function userId(): int
    {
        AuthMiddleware::handle('mitra');
        $id = (int) ($_SESSION['user']['id'] ?? 0);
        if ($id < 1) {
            http_response_code(401);
            exit('Sesi pengguna tidak valid.');
        }

        return $id;
    }

    private function validatePost(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST' || !verifyCsrfToken()) {
            http_response_code(403);
            exit('Permintaan tidak valid. Silakan muat ulang halaman.');
        }
    }
}
