<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../function/Helpers.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/KoordinatorMitra.php';

final class KoordinatorMitraController
{
    private const CATEGORIES = [
        'BUMN', 'BUMD', 'Perusahaan Swasta', 'Instansi Pemerintah',
        'Institusi Pendidikan', 'UMKM', 'Organisasi', 'Lainnya',
    ];

    private function authorize(): void
    {
        AuthMiddleware::handleAny(['tendik', 'koordinator_magang']);
    }

    private function emptyForm(): array
    {
        return [
            'nama_perusahaan' => '', 'kode_perusahaan' => '', 'kategori' => '',
            'bidang_usaha' => '', 'deskripsi' => '', 'email' => '',
            'no_telepon' => '', 'website' => '', 'provinsi' => '',
            'kota' => '', 'alamat' => '',
        ];
    }

    private function readForm(): array
    {
        $form = $this->emptyForm();
        foreach ($form as $key => $_) {
            $form[$key] = trim((string) ($_POST[$key] ?? ''));
        }
        $form['kode_perusahaan'] = strtoupper($form['kode_perusahaan']);
        return $form;
    }

    private function validate(array $form): ?string
    {
        if ($form['nama_perusahaan'] === '' || mb_strlen($form['nama_perusahaan']) > 200) {
            return 'Nama perusahaan wajib diisi dan maksimal 200 karakter.';
        }
        if ($form['kode_perusahaan'] === '' ||
            !preg_match('/^[A-Z0-9][A-Z0-9_-]{1,49}$/', $form['kode_perusahaan'])) {
            return 'Kode perusahaan wajib diisi (2–50 karakter; gunakan huruf, angka, - atau _).';
        }
        if (!in_array($form['kategori'], self::CATEGORIES, true)) {
            return 'Kategori mitra tidak valid.';
        }
        if ($form['email'] !== '' && !filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
            return 'Format email tidak valid.';
        }
        if ($form['website'] !== '' && !filter_var($form['website'], FILTER_VALIDATE_URL)) {
            return 'Format website harus berupa URL lengkap.';
        }
        return null;
    }

    private function dbError(PDOException $e): string
    {
        error_log('Kesalahan database modul mitra: ' . $e->getMessage());
        return (($e->errorInfo[0] ?? $e->getCode()) === '23505')
            ? 'Kode perusahaan sudah digunakan.'
            : 'Data belum dapat diproses. Silakan coba kembali.';
    }

    public function index($params = []): void
    {
        $this->authorize();
        $mitraList = (new KoordinatorMitra())->getAllActive();
        $success = (string) ($_GET['success'] ?? '');
        require __DIR__ . '/../../../pages/dashboard/koordinatorMagang/mitra/index.php';
    }

    public function create($params = []): void
    {
        $this->authorize();
        $categories = self::CATEGORIES;
        $error = null;
        $form = $this->emptyForm();

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            if (!verifyCsrfToken()) {
                http_response_code(403);
                exit('Permintaan tidak valid. Silakan muat ulang halaman.');
            }
            $form = $this->readForm();
            $error = $this->validate($form);
            if ($error === null) {
                try {
                    (new KoordinatorMitra())->create($form);
                    header('Location: ' . url('/dashboard/koordinator-magang/mitra?success=created'));
                    exit;
                } catch (PDOException $e) {
                    $error = $this->dbError($e);
                }
            }
        }
        require __DIR__ . '/../../../pages/dashboard/koordinatorMagang/mitra/create.php';
    }

    public function edit($params = []): void
    {
        $this->authorize();
        $id = filter_var($params['id'] ?? $_GET['id'] ?? null, FILTER_VALIDATE_INT);
        if (!$id || $id < 1) {
            http_response_code(400);
            exit('ID mitra tidak valid.');
        }

        $model = new KoordinatorMitra();
        $mitra = $model->findById((int) $id);
        if (!$mitra || !$mitra['is_active']) {
            http_response_code(404);
            exit('Mitra aktif tidak ditemukan.');
        }

        $categories = self::CATEGORIES;
        $form = $mitra;
        $error = null;

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            if (!verifyCsrfToken()) {
                http_response_code(403);
                exit('Permintaan tidak valid. Silakan muat ulang halaman.');
            }
            $form = $this->readForm();
            $error = $this->validate($form);
            if ($error === null) {
                try {
                    $model->update((int) $id, $form);
                    header('Location: ' . url('/dashboard/koordinator-magang/mitra?success=updated'));
                    exit;
                } catch (PDOException $e) {
                    $error = $this->dbError($e);
                }
            }
        }
        require __DIR__ . '/../../../pages/dashboard/koordinatorMagang/mitra/edit.php';
    }

    public function deactivate($params = []): void
    {
        $this->authorize();
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            http_response_code(405);
            exit('Metode tidak diizinkan.');
        }
        if (!verifyCsrfToken()) {
            http_response_code(403);
            exit('Permintaan tidak valid. Silakan muat ulang halaman.');
        }

        $id = filter_var($_POST['mitra_id'] ?? null, FILTER_VALIDATE_INT);
        if (!$id || $id < 1) {
            header('Location: ' . url('/dashboard/koordinator-magang/mitra?success=0'));
            exit;
        }

        try {
            $changed = (new KoordinatorMitra())->deactivate((int) $id);
            header('Location: ' . url('/dashboard/koordinator-magang/mitra?success=' . ($changed ? 'deactivated' : '0')));
            exit;
        } catch (PDOException $e) {
            error_log('Gagal menonaktifkan mitra: ' . $e->getMessage());
            header('Location: ' . url('/dashboard/koordinator-magang/mitra?success=0'));
            exit;
        }
    }
}
