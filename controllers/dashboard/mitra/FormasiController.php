<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../function/Helpers.php';
require_once __DIR__ . '/../../../middleware/MitraAuthMiddleware.php';
require_once __DIR__ . '/../../../models/FormasiMitra.php';

class MitraFormasiController
{
    public function index($params = []): void
    {
        $mitraId = MitraAuthMiddleware::currentMitraId();
        $items = (new FormasiMitra())->getForCompany($mitraId);
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
        $mitraId = MitraAuthMiddleware::currentMitraId();
        $actorUserId = MitraAuthMiddleware::currentUserId();
        $this->validatePost();
        $id = (int) ($params['id'] ?? 0);
        try {
            (new FormasiMitra())->changeStatus($mitraId, $id, (string) ($_POST['status'] ?? ''), $actorUserId);
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
        $mitraId = MitraAuthMiddleware::currentMitraId();
        $actorUserId = MitraAuthMiddleware::currentUserId();
        $this->validatePost();
        try {
            (new FormasiMitra())->delete($mitraId, (int) ($params['id'] ?? 0), $actorUserId);
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
        $mitraId = MitraAuthMiddleware::currentMitraId();
        $actorUserId = MitraAuthMiddleware::currentUserId();
        $model = new FormasiMitra();
        $formasi = $formasiId ? $model->getOne($mitraId, $formasiId) : null;
        if ($formasiId && !$formasi) {
            http_response_code(404);
            exit('Formasi tidak ditemukan.');
        }

        $mode = $formasi ? 'edit' : 'create';
        $error = null;
        $values = $formasi ?? [
            'judul' => '',
            'deskripsi' => '',
            'bidang' => '',
            'jumlah_kuota' => 1,
            'persyaratan' => '',
            'lokasi_magang' => '',
            'sistem_kerja' => 'onsite',
            'tanggal_mulai' => '',
            'tanggal_selesai' => '',
            'tahun_akademik' => '',
            'status' => 'draft',
        ];

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $this->validatePost();
            $values = array_merge($values, $_POST);
            try {
                if ($mode === 'create') {
                    $model->create($mitraId, $_POST, $actorUserId);
                } else {
                    $model->update($mitraId, (int) $formasi['id'], $_POST, $actorUserId);
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

    private function validatePost(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST' || !verifyCsrfToken()) {
            http_response_code(403);
            exit('Permintaan tidak valid. Silakan muat ulang halaman.');
        }
    }
}
