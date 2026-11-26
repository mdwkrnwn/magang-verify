<?php

require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../function/Helpers.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/Pengalaman.php';
require_once __DIR__ . '/../../../models/AktivitasPengguna.php';

class PengalamanController
{
    private Pengalaman $model;
    private AktivitasPengguna $aktivitasModel;

    public function __construct()
    {
        $this->model = new Pengalaman();
        $this->aktivitasModel = new AktivitasPengguna();
    }

    private function userId(): int
    {
        AuthMiddleware::handle('mahasiswa');
        $id = $_SESSION['user']['id'] ?? null;
        if (!is_numeric($id) || (int)$id < 1) {
            http_response_code(401);
            exit('Sesi pengguna tidak valid.');
        }
        return (int)$id;
    }

    private function redirect(string $path): never
    {
        header('Location: ' . url($path));
        exit;
    }

    private function dataFromPost(): array
    {
        $data = [
            'jenis' => trim($_POST['jenis'] ?? ''),
            'posisi' => trim($_POST['posisi'] ?? ''),
            'instansi' => trim($_POST['instansi'] ?? ''),
            'lokasi' => trim($_POST['lokasi'] ?? ''),
            'deskripsi' => trim($_POST['deskripsi'] ?? ''),
            'tanggal_mulai' => trim($_POST['tanggal_mulai'] ?? ''),
            'tanggal_selesai' => trim($_POST['tanggal_selesai'] ?? ''),
            'link_pendukung' => trim($_POST['link_pendukung'] ?? ''),
        ];
        if (!in_array($data['jenis'], ['pekerjaan', 'organisasi', 'freelance', 'proyek', 'lainnya'], true)) {
            $data['jenis'] = 'lainnya';
        }
        if ($data['posisi'] === '' || mb_strlen($data['posisi']) > 200) throw new InvalidArgumentException('Posisi wajib diisi dan maksimal 200 karakter.');
        if ($data['instansi'] === '' || mb_strlen($data['instansi']) > 200) throw new InvalidArgumentException('Instansi wajib diisi dan maksimal 200 karakter.');
        foreach (['tanggal_mulai', 'tanggal_selesai'] as $f) {
            if ($data[$f] !== '') {
                $d = DateTime::createFromFormat('Y-m-d', $data[$f]);
                if (!$d || $d->format('Y-m-d') !== $data[$f]) throw new InvalidArgumentException('Tanggal tidak valid.');
            }
        }
        if ($data['tanggal_mulai'] !== '' && $data['tanggal_selesai'] !== '' && $data['tanggal_selesai'] < $data['tanggal_mulai']) throw new InvalidArgumentException('Tanggal selesai tidak boleh mendahului tanggal mulai.');
        if ($data['link_pendukung'] !== '') {
            if (
                mb_strlen($data['link_pendukung']) > 500 ||
                !filter_var($data['link_pendukung'], FILTER_VALIDATE_URL) ||
                !in_array(
                    strtolower((string) parse_url($data['link_pendukung'], PHP_URL_SCHEME)),
                    ['http', 'https'],
                    true
                )
            ) {
                throw new InvalidArgumentException('Link pendukung harus berupa URL HTTP/HTTPS yang valid dan maksimal 500 karakter.');
            }
        }
        return $data;
    }

    private function uploadBukti(): ?string
    {
        if (
            !isset($_FILES['bukti_file']) ||
            $_FILES['bukti_file']['error'] === UPLOAD_ERR_NO_FILE
        ) {
            return null;
        }

        $file = $_FILES['bukti_file'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Gagal mengunggah bukti pendukung.');
        }

        if ($file['size'] > 2 * 1024 * 1024) {
            throw new InvalidArgumentException('Ukuran file maksimal 2 MB.');
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        $allowed = [
            'application/pdf' => 'pdf',
            'image/jpeg'      => 'jpg',
            'image/png'       => 'png',
        ];

        if (!isset($allowed[$mime])) {
            throw new InvalidArgumentException('Format bukti harus PDF, JPG, atau PNG.');
        }

        $directory = __DIR__ . '/../../../uploads/pengalaman';

        if (!is_dir($directory) && !mkdir($directory, 0755, true)) {
            throw new RuntimeException('Folder upload bukti tidak dapat dibuat.');
        }

        $filename = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
        $destination = $directory . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new RuntimeException('Gagal menyimpan file bukti.');
        }

        return 'uploads/pengalaman/' . $filename;
    }

    private function deleteBuktiFile(?string $path): void
    {
        if (!$path || !str_starts_with($path, 'uploads/pengalaman/')) {
            return;
        }

        $fullPath = __DIR__ . '/../../../' . $path;

        if (is_file($fullPath)) {
            unlink($fullPath);
        }
    }

    public function index(): void
    {
        $id = $this->userId();

        $filters = [
            'q' => trim((string) ($_GET['q'] ?? '')),
            'jenis' => trim((string) ($_GET['jenis'] ?? '')),
            'sumber' => trim((string) ($_GET['sumber'] ?? '')),
            'tahun' => trim((string) ($_GET['tahun'] ?? '')),
        ];

        $page = filter_var($_GET['page'] ?? 1, FILTER_VALIDATE_INT);

        $result = $this->model->getAll(
            $id,
            $filters,
            max(1, (int) ($page ?: 1)),
            6
        );

        $tampil = $result['data'];
        $pagination = $result['pagination'];
        $jenisList = $this->model->getJenisPengalaman($id);
        $tahunList = $this->model->getTahunPengalaman($id);

        $q = $filters['q'];
        $jenis = $filters['jenis'];
        $sumber = $filters['sumber'];
        $tahun = $filters['tahun'];

        require __DIR__ . '/../../../pages/dashboard/mahasiswa/pengalaman/index.php';
    }


    public function detail($params = []): void
    {
        $id = $this->userId();

        $slug = trim((string) ($params['slug'] ?? ''));

        if ($slug === '') {
            http_response_code(404);
            exit('Pengalaman tidak ditemukan.');
        }

        $item = $this->model->getBySlug($slug, $id);

        if (!$item) {
            http_response_code(404);
            exit('Pengalaman tidak ditemukan.');
        }

        require __DIR__ . '/../../../pages/dashboard/mahasiswa/pengalaman/detail.php';
    }


    public function create(): void
    {
        $this->userId();
        $pengalaman = [];
        $mode = 'create';
        $item = null;
        require __DIR__ . '/../../../pages/dashboard/mahasiswa/pengalaman/form.php';
    }

    public function store(): void
    {
        $id = $this->userId();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verifyCsrfToken()) {
            http_response_code(403);
            exit('Permintaan tidak valid.');
        }
        try {
            $data = $this->dataFromPost();
            $data['bukti_path'] = $this->uploadBukti();
            $newId = $this->model->create($id, $data);
            $this->aktivitasModel->log(
                $id,
                'Pengalaman ditambahkan',
                'Pengalaman "' . $data['posisi'] . '" di "' . $data['instansi'] . '" berhasil ditambahkan.',
                'pengalaman',
                'pengalaman',
                $newId
            );
            $this->redirect('/dashboard/mahasiswa/pengalaman');
        } catch (Throwable $e) {
            http_response_code(422);
            exit(e($e->getMessage()));
        }
    }

    public function edit($params = []): void
    {
        $id = $this->userId();
        $item = $this->model->getById((int)($params['id'] ?? 0), $id);
        if (!$item) {
            http_response_code(404);
            exit('Pengalaman tidak ditemukan.');
        }
        $mode = 'edit';
        require __DIR__ . '/../../../pages/dashboard/mahasiswa/pengalaman/form.php';
    }

    public function update($params = []): void
    {
        $id = $this->userId();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verifyCsrfToken()) {
            http_response_code(403);
            exit('Permintaan tidak valid.');
        }
        try {
            $data = $this->dataFromPost();

            $itemId = (int) ($params['id'] ?? 0);
            $item = $this->model->getById($itemId, $id);

            if (!$item) {
                http_response_code(404);
                exit('Pengalaman tidak ditemukan.');
            }

            $newBukti = $this->uploadBukti();

            $data['bukti_path'] = $newBukti ?? $item['bukti_path'];

            // Jika mahasiswa mencentang hapus bukti lama.
            if (isset($_POST['hapus_bukti']) && $_POST['hapus_bukti'] === '1') {
                $data['bukti_path'] = $newBukti;
            }

            $this->model->update($itemId, $id, $data);

            // Hapus file lama hanya setelah database berhasil diperbarui.
            if (
                $item['bukti_path'] &&
                $item['bukti_path'] !== $data['bukti_path']
            ) {
                $this->deleteBuktiFile($item['bukti_path']);
            }
            $this->redirect('/dashboard/mahasiswa/pengalaman');
        } catch (Throwable $e) {
            http_response_code(422);
            exit(e($e->getMessage()));
        }
    }

    public function delete($params = []): void
    {
        $id = $this->userId();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verifyCsrfToken()) {
            http_response_code(403);
            exit('Permintaan tidak valid.');
        }
        $itemId = (int) ($params['id'] ?? 0);
        $item = $this->model->getById($itemId, $id);
        if ($this->model->delete($itemId, $id)) {
            $this->aktivitasModel->log(
                $id,
                'Pengalaman dihapus',
                'Pengalaman "' . ($item['posisi'] ?? 'Pengalaman') . '" berhasil dihapus.',
                'pengalaman',
                'pengalaman',
                $itemId
            );
        }
        $this->deleteBuktiFile($item['bukti_path'] ?? null);
        $this->redirect('/dashboard/mahasiswa/pengalaman');
    }
}
