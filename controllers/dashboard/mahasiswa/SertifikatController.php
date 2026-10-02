<?php

require_once __DIR__ . '/../../../models/Sertifikat.php';

class SertifikatController
{
    private Sertifikat $sertifikatModel;
    private string $uploadDirectory;
    private string $uploadRelativePath = 'uploads/sertifikat';

    public function __construct()
    {
        $this->sertifikatModel = new Sertifikat();
        $this->uploadDirectory = dirname(__DIR__, 3) . '/' . $this->uploadRelativePath;
    }

    private function getMahasiswaId(): int
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $userId = $_SESSION['user']['id'] ?? null;
        if (!is_numeric($userId) || (int) $userId < 1) {
            http_response_code(401);
            exit('Sesi pengguna tidak valid. Silakan login kembali.');
        }

        return (int) $userId;
    }

    private function showNotFound(): void
    {
        http_response_code(404);
        require __DIR__ . '/../../../pages/errors/404.php';
        exit;
    }

    private function redirect(string $path): never
    {
        header('Location: ' . url($path));
        exit;
    }

    private function validatePostRequest(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            http_response_code(405);
            header('Allow: POST');
            exit('Method Not Allowed');
        }

        if (!verifyCsrfToken()) {
            http_response_code(403);
            exit('403 - Token CSRF tidak valid.');
        }
    }

    /** Simpan unggahan gambar atau PDF. Mengembalikan path relatif untuk database. */
    private function storeUploadedFile(array &$errors, bool $required = false): ?string
    {
        if (!isset($_FILES['gambar']) || !is_array($_FILES['gambar']) ||
            ($_FILES['gambar']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            if ($required) {
                $errors['gambar'] = 'Sertifikat wajib diunggah.';
            }
            return null;
        }

        $file = $_FILES['gambar'];
        $error = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($error !== UPLOAD_ERR_OK) {
            $errors['gambar'] = match ($error) {
                UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Ukuran file melebihi batas unggahan server.',
                UPLOAD_ERR_PARTIAL => 'File hanya terunggah sebagian. Silakan coba lagi.',
                default => 'File gagal diunggah. Silakan coba lagi.',
            };
            return null;
        }

        if ((int) ($file['size'] ?? 0) < 1) {
            $errors['gambar'] = 'File kosong atau tidak valid.';
            return null;
        }

        // Maksimum 5 MB.
        if ((int) ($file['size'] ?? 0) > 5 * 1024 * 1024) {
            $errors['gambar'] = 'Ukuran file maksimal 5 MB.';
            return null;
        }

        $tmp = (string) ($file['tmp_name'] ?? '');
        if ($tmp === '' || !is_uploaded_file($tmp)) {
            $errors['gambar'] = 'File unggahan tidak valid.';
            return null;
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($tmp);
        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'application/pdf' => 'pdf',
        ];
        if (!isset($extensions[$mime])) {
            $errors['gambar'] = 'Format file harus JPG, PNG, WEBP, atau PDF.';
            return null;
        }

        if (in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true) && @getimagesize($tmp) === false) {
            $errors['gambar'] = 'File gambar tidak valid.';
            return null;
        }
        if ($mime === 'application/pdf') {
            $signature = file_get_contents($tmp, false, null, 0, 5);
            if ($signature !== '%PDF-') {
                $errors['gambar'] = 'File PDF tidak valid.';
                return null;
            }
        }

        if (!is_dir($this->uploadDirectory) && !mkdir($this->uploadDirectory, 0755, true) && !is_dir($this->uploadDirectory)) {
            $errors['gambar'] = 'Folder penyimpanan file tidak dapat dibuat.';
            return null;
        }

        $filename = bin2hex(random_bytes(16)) . '.' . $extensions[$mime];
        if (!move_uploaded_file($tmp, $this->uploadDirectory . '/' . $filename)) {
            $errors['gambar'] = 'File gagal disimpan.';
            return null;
        }

        return $this->uploadRelativePath . '/' . $filename;
    }

    private function deleteStoredFile(?string $relativePath): void
    {
        if (!$relativePath || !str_starts_with($relativePath, $this->uploadRelativePath . '/')) {
            return;
        }
        $fullPath = dirname(__DIR__, 3) . '/' . $relativePath;
        $base = realpath($this->uploadDirectory);
        $real = realpath($fullPath);
        if ($base !== false && $real !== false && str_starts_with($real, $base . DIRECTORY_SEPARATOR) && is_file($real)) {
            @unlink($real);
        }
    }

    private function oldFormData(): array
    {
        return [
            'nama' => trim((string) ($_POST['nama'] ?? '')),
            'penerbit' => trim((string) ($_POST['penerbit'] ?? '')),
            'tanggal_terbit' => trim((string) ($_POST['tanggal_terbit'] ?? '')),
            'nomor_sertifikat' => trim((string) ($_POST['nomor_sertifikat'] ?? '')),
            'deskripsi' => trim((string) ($_POST['deskripsi'] ?? '')),
            'tautan' => trim((string) ($_POST['tautan'] ?? '')),
        ];
    }

    private function validateForm(array $data, array &$errors): void
    {
        if ($data['nama'] === '') {
            $errors['nama'] = 'Nama sertifikat wajib diisi.';
        } elseif (mb_strlen($data['nama']) > 200) {
            $errors['nama'] = 'Nama sertifikat maksimal 200 karakter.';
        }
        if ($data['penerbit'] === '') {
            $errors['penerbit'] = 'Penerbit sertifikat wajib diisi.';
        } elseif (mb_strlen($data['penerbit']) > 200) {
            $errors['penerbit'] = 'Nama penerbit maksimal 200 karakter.';
        }
        if ($data['tanggal_terbit'] !== '') {
            $date = DateTime::createFromFormat('Y-m-d', $data['tanggal_terbit']);
            if (!$date || $date->format('Y-m-d') !== $data['tanggal_terbit']) {
                $errors['tanggal_terbit'] = 'Tanggal terbit tidak valid.';
            }
        }
        if ($data['tautan'] !== '' && (!filter_var($data['tautan'], FILTER_VALIDATE_URL) || !in_array(strtolower((string) parse_url($data['tautan'], PHP_URL_SCHEME)), ['http', 'https'], true))) {
            $errors['tautan'] = 'Tautan harus berupa URL HTTP atau HTTPS yang valid.';
        }
    }

    public function index($params = []): void
    {
        $userId = $this->getMahasiswaId();
        $filters = [
            'q' => trim((string) ($_GET['q'] ?? '')),
            'tahun' => trim((string) ($_GET['tahun'] ?? '')),
            'status' => trim((string) ($_GET['status'] ?? '')),
        ];
        $page = filter_var($_GET['page'] ?? 1, FILTER_VALIDATE_INT);
        $result = $this->sertifikatModel->getAllSertifikat($userId, $filters, max(1, (int) ($page ?: 1)), 6);
        $tampil = $result['data'];
        $pagination = $result['pagination'];
        $tahunList = $this->sertifikatModel->getTahunSertifikat($userId);

        require __DIR__ . '/../../../pages/dashboard/mahasiswa/sertifikat/index.php';
    }

    public function detail($params = []): void
    {
        $userId = $this->getMahasiswaId();
        $slug = trim((string) ($params['slug'] ?? ''));
        $sertifikat = $this->sertifikatModel->getSertifikatBySlug($slug, $userId);
        if (!$sertifikat) {
            $this->showNotFound();
        }
        require __DIR__ . '/../../../pages/dashboard/mahasiswa/sertifikat/detail.php';
    }

    public function tambah($params = []): void
    {
        $userId = $this->getMahasiswaId();
        $mode = 'tambah';
        $errors = [];
        $old = ['nama' => '', 'penerbit' => '', 'tanggal_terbit' => '', 'nomor_sertifikat' => '', 'deskripsi' => '', 'gambar' => '', 'tautan' => ''];

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $this->validatePostRequest();
            $old = $this->oldFormData();
            $this->validateForm($old, $errors);
            $filePath = $this->storeUploadedFile($errors, true);

            if (!$errors && $filePath !== null) {
                try {
                    $data = $old;
                    $data['file_path'] = $filePath;
                    $slug = $this->sertifikatModel->create($userId, $data);
                    $this->redirect('/dashboard/mahasiswa/sertifikat/detail/' . rawurlencode($slug));
                } catch (Throwable $e) {
                    $this->deleteStoredFile($filePath);
                    error_log('Gagal menambahkan sertifikat: ' . $e->getMessage());
                    $errors['umum'] = 'Sertifikat gagal disimpan. Silakan coba kembali.';
                }
            } elseif ($filePath !== null) {
                $this->deleteStoredFile($filePath);
            }
        }

        require __DIR__ . '/../../../pages/dashboard/mahasiswa/sertifikat/form.php';
    }

    public function edit($params = []): void
    {
        $userId = $this->getMahasiswaId();
        $slug = trim((string) ($params['slug'] ?? ''));
        $sertifikat = $this->sertifikatModel->getSertifikatBySlug($slug, $userId);
        if (!$sertifikat) {
            $this->showNotFound();
        }
        if (($sertifikat['verifikasi']['status'] ?? '') !== 'belum_terverifikasi') {
            http_response_code(403);
            exit('Sertifikat yang sedang atau sudah diverifikasi tidak dapat diubah.');
        }

        $mode = 'edit';

        $errors = [];
        $old = [
            'nama' => $sertifikat['nama'] ?? '',
            'penerbit' => $sertifikat['penerbit'] ?? '',
            'tanggal_terbit' => $sertifikat['tanggal_terbit'] ?? '',
            'nomor_sertifikat' => $sertifikat['nomor_sertifikat'] ?? '',
            'deskripsi' => $sertifikat['deskripsi'] ?? '',
            'tautan' => $sertifikat['tautan']['sertifikat'] ?? '',
            'gambar' => $sertifikat['gambar'] ?? '',
        ];

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $this->validatePostRequest();
            $old = array_merge($old, $this->oldFormData());
            $this->validateForm($old, $errors);
            $newFilePath = $this->storeUploadedFile($errors, false);

            if (!$errors) {
                try {
                    $data = $old;
                    if ($newFilePath !== null) {
                        $data['file_path'] = $newFilePath;
                    }
                    $newSlug = $this->sertifikatModel->update((int) $sertifikat['id'], $userId, $data);
                    if ($newFilePath !== null) {
                        $this->deleteStoredFile($sertifikat['file_path'] ?? $sertifikat['gambar'] ?? null);
                    }
                    $this->redirect('/dashboard/mahasiswa/sertifikat/detail/' . rawurlencode($newSlug));
                } catch (Throwable $e) {
                    if ($newFilePath !== null) {
                        $this->deleteStoredFile($newFilePath);
                    }
                    error_log('Gagal memperbarui sertifikat: ' . $e->getMessage());
                    $errors['umum'] = 'Sertifikat gagal diperbarui. Silakan coba kembali.';
                }
            } elseif ($newFilePath !== null) {
                $this->deleteStoredFile($newFilePath);
            }
        }

        require __DIR__ . '/../../../pages/dashboard/mahasiswa/sertifikat/form.php';
    }

    public function hapus($params = []): void
    {
        $this->validatePostRequest();
        $userId = $this->getMahasiswaId();
        $slug = trim((string) ($params['slug'] ?? ''));
        $sertifikat = $this->sertifikatModel->getSertifikatBySlug($slug, $userId);
        if (!$sertifikat) {
            $this->showNotFound();
        }
        if (($sertifikat['verifikasi']['status'] ?? '') !== 'belum_terverifikasi') {
            http_response_code(403);
            exit('Sertifikat yang sedang atau sudah diverifikasi tidak dapat dihapus.');
        }

        if ($this->sertifikatModel->deleteBySlug($slug, $userId)) {
            $this->deleteStoredFile($sertifikat['file_path'] ?? $sertifikat['gambar'] ?? null);
        }
        $this->redirect('/dashboard/mahasiswa/sertifikat');
    }

    public function download($params = []): void
    {
        $userId = $this->getMahasiswaId();
        $slug = trim((string) ($params['slug'] ?? ''));
        $sertifikat = $this->sertifikatModel->getSertifikatBySlug($slug, $userId);
        if (!$sertifikat) {
            $this->showNotFound();
        }

        $relativePath = (string) ($sertifikat['file_path'] ?? $sertifikat['gambar'] ?? '');
        if ($relativePath === '' || !str_starts_with($relativePath, $this->uploadRelativePath . '/')) {
            $this->showNotFound();
        }
        $base = realpath($this->uploadDirectory);
        $file = realpath(dirname(__DIR__, 3) . '/' . $relativePath);
        if ($base === false || $file === false || !str_starts_with($file, $base . DIRECTORY_SEPARATOR) || !is_file($file)) {
            $this->showNotFound();
        }

        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file);
        $downloadName = preg_replace('/[^a-zA-Z0-9_-]+/', '-', $slug) ?: 'sertifikat';
        if ($mime === 'application/pdf') {
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $downloadName . '.pdf"');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        }

        if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true) || @getimagesize($file) === false) {
            http_response_code(415);
            exit('Format file sertifikat tidak didukung.');
        }

        // Gambar dikonversi menjadi PDF menggunakan Dompdf jika tersedia.
        $autoload = dirname(__DIR__, 3) . '/vendor/autoload.php';
        if (!is_file($autoload)) {
            http_response_code(500);
            exit('Dompdf belum tersedia. Jalankan instalasi dependensi Dompdf terlebih dahulu.');
        }
        require_once $autoload;
        if (!class_exists(\Dompdf\Dompdf::class)) {
            http_response_code(500);
            exit('Dompdf belum tersedia.');
        }

        $dataUri = 'data:' . $mime . ';base64,' . base64_encode((string) file_get_contents($file));
        $html = '<!doctype html><html><head><meta charset="utf-8"><style>@page{margin:10mm}body{margin:0;text-align:center}img{max-width:100%;max-height:270mm;object-fit:contain}</style></head><body><img src="' . $dataUri . '"></body></html>';
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream($downloadName . '.pdf', ['Attachment' => true]);
        exit;
    }
}
