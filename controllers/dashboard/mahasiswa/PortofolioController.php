<?php

require_once __DIR__ . '/../../../models/Portofolio.php';

class PortofolioController
{

    private Portofolio $portofolioModel;

    public function __construct()
    {
        $this->portofolioModel = new Portofolio();
    }

    /*
    |--------------------------------------------------------------------------
    | Session
    |--------------------------------------------------------------------------
    */

    private function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Mengambil ID pengguna yang sedang login.
     * Route/controller ini seharusnya hanya dapat diakses oleh role mahasiswa.
     */
    private function getMahasiswaId(): int
    {
        $this->startSession();

        $userId = $_SESSION['user']['id'] ?? null;

        if (!is_numeric($userId) || (int) $userId < 1) {
            http_response_code(401);
            exit('Sesi pengguna tidak valid. Silakan login kembali.');
        }

        return (int) $userId;
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

    private function showNotFound(): void
    {
        http_response_code(404);
        require __DIR__ . '/../../../pages/errors/404.php';
        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | Upload gambar portofolio
    |--------------------------------------------------------------------------
    */

    /**
     * Memvalidasi dan menyimpan gambar ke uploads/portofolio.
     * Path relatif yang disimpan ke database, misalnya:
     * uploads/portofolio/namafile.webp
     */
    private function saveUploadedImage(array &$errors): ?string
    {
        if (
            !isset($_FILES['gambar']) ||
            !is_array($_FILES['gambar']) ||
            ($_FILES['gambar']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE
        ) {
            return null;
        }

        $file = $_FILES['gambar'];
        $uploadError = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);

        if ($uploadError !== UPLOAD_ERR_OK) {
            $errors['gambar'] = match ($uploadError) {
                UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE =>
                    'Ukuran gambar terlalu besar.',
                UPLOAD_ERR_PARTIAL =>
                    'Gambar hanya terunggah sebagian. Silakan coba lagi.',
                default =>
                    'Gambar gagal diunggah. Silakan coba lagi.',
            };
            return null;
        }

        $maxSize = 2 * 1024 * 1024;

        if (!isset($file['size']) || (int) $file['size'] < 1) {
            $errors['gambar'] = 'File gambar kosong atau tidak valid.';
            return null;
        }

        if ((int) $file['size'] > $maxSize) {
            $errors['gambar'] = 'Ukuran gambar maksimal 2 MB.';
            return null;
        }

        $tmpName = $file['tmp_name'] ?? '';

        if (!is_uploaded_file($tmpName)) {
            $errors['gambar'] = 'File yang diunggah tidak valid.';
            return null;
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($tmpName);

        $allowedTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];

        if (!isset($allowedTypes[$mimeType])) {
            $errors['gambar'] = 'Format gambar harus JPG, PNG, atau WEBP.';
            return null;
        }

        $projectRoot = dirname(__DIR__, 3);
        $uploadDirectory = $projectRoot . '/uploads/portofolio';

        if (
            !is_dir($uploadDirectory) &&
            !mkdir($uploadDirectory, 0755, true) &&
            !is_dir($uploadDirectory)
        ) {
            $errors['gambar'] = 'Folder penyimpanan gambar tidak dapat dibuat.';
            return null;
        }

        $fileName = bin2hex(random_bytes(16)) . '.' . $allowedTypes[$mimeType];
        $destination = $uploadDirectory . '/' . $fileName;

        if (!move_uploaded_file($tmpName, $destination)) {
            $errors['gambar'] = 'Gambar gagal disimpan ke server.';
            return null;
        }

        return 'uploads/portofolio/' . $fileName;
    }

    /**
     * Menghapus file gambar hanya jika path berada di folder upload portofolio.
     */
    private function removeUploadedImage(?string $relativePath): void
    {
        if (!$relativePath || !str_starts_with($relativePath, 'uploads/portofolio/')) {
            return;
        }

        $projectRoot = dirname(__DIR__, 3);
        $uploadDirectory = realpath($projectRoot . '/uploads/portofolio');

        if ($uploadDirectory === false) {
            return;
        }

        $filePath = realpath($projectRoot . '/' . $relativePath);

        if (
            $filePath !== false &&
            str_starts_with($filePath, $uploadDirectory . DIRECTORY_SEPARATOR) &&
            is_file($filePath)
        ) {
            unlink($filePath);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Membaca data form
    |--------------------------------------------------------------------------
    */

    private function readFormData(array &$errors): array
    {
        $judul = trim($_POST['judul'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');
        $teknologiInput = trim($_POST['teknologi'] ?? '');
        $peran = trim($_POST['peran'] ?? '');
        $tahunInput = trim((string) ($_POST['tahun'] ?? ''));
        $github = trim($_POST['github'] ?? '');
        $demo = trim($_POST['demo'] ?? '');

        if ($judul === '') {
            $errors['judul'] = 'Judul portofolio wajib diisi.';
        } elseif (mb_strlen($judul) > 200) {
            $errors['judul'] = 'Judul maksimal 200 karakter.';
        }

        if ($deskripsi === '') {
            $errors['deskripsi'] = 'Deskripsi portofolio wajib diisi.';
        }

        if ($teknologiInput === '') {
            $errors['teknologi'] = 'Teknologi wajib diisi.';
        }

        if ($peran === '') {
            $errors['peran'] = 'Peran wajib diisi.';
        } elseif (mb_strlen($peran) > 100) {
            $errors['peran'] = 'Peran maksimal 100 karakter.';
        }

        $tahun = null;

        if ($tahunInput === '') {
            $errors['tahun'] = 'Tahun wajib diisi.';
        } else {
            $tahunValid = filter_var($tahunInput, FILTER_VALIDATE_INT);

            if ($tahunValid === false || $tahunValid < 2000 || $tahunValid > 2100) {
                $errors['tahun'] = 'Tahun harus berupa angka antara 2000 dan 2100.';
            } else {
                $tahun = (int) $tahunValid;
            }
        }

        foreach (['github' => $github, 'demo' => $demo] as $field => $url) {
            if ($url === '') {
                continue;
            }

            $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));

            if (
                !filter_var($url, FILTER_VALIDATE_URL) ||
                !in_array($scheme, ['http', 'https'], true)
            ) {
                $errors[$field] = 'URL harus menggunakan format HTTP atau HTTPS yang valid.';
            }
        }

        $teknologi = array_values(array_unique(array_filter(
            array_map('trim', explode(',', $teknologiInput)),
            static fn($item) => $item !== ''
        )));

        foreach ($teknologi as $item) {
            if (mb_strlen($item) > 100) {
                $errors['teknologi'] = 'Setiap nama teknologi maksimal 100 karakter.';
                break;
            }
        }

        return [
            'judul' => $judul,
            'deskripsi' => $deskripsi,
            'teknologi' => $teknologi,
            'peran' => $peran,
            'tahun' => $tahun,
            'github' => $github,
            'demo' => $demo,
        ];
    }

    /**
     * Menyiapkan nilai form dalam format yang dipakai oleh view tambah/edit.
     */
    private function prepareFormValues(array $data, array $existing = []): array
    {
        return array_merge($existing, [
            'judul' => $data['judul'],
            'deskripsi' => $data['deskripsi'],
            'teknologi' => $data['teknologi'],
            'peran' => $data['peran'],
            'tahun' => (string) ($data['tahun'] ?? ''),
            'gambar' => $existing['gambar'] ?? '',
            'tautan' => [
                'github' => $data['github'],
                'demo' => $data['demo'],
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Ambil seluruh data portofolio
    |--------------------------------------------------------------------------
    */

    private function getAllPortofolio()
    {
        global $portofolio;

        $this->startSession();

        /*
        |----------------------------------------------------------------------
        | Jika session belum memiliki data portofolio,
        | gunakan data awal dari Portofolio.php
        |----------------------------------------------------------------------
        */

        if (!isset($_SESSION['portofolio_data'])) {

            $_SESSION['portofolio_data'] = $portofolio;

            /*
            |------------------------------------------------------------------
            | Ambil data tambahan dari sistem tambah versi sebelumnya
            |------------------------------------------------------------------
            */

            if (!empty($_SESSION['portofolio_tambahan'])) {

                $_SESSION['portofolio_data'] = array_merge(
                    $_SESSION['portofolio_data'],
                    $_SESSION['portofolio_tambahan']
                );

                unset($_SESSION['portofolio_tambahan']);
            }
        }

        return $_SESSION['portofolio_data'];
    }


    /*
    |--------------------------------------------------------------------------
    | Simpan data portofolio ke session
    |--------------------------------------------------------------------------
    */

    private function savePortofolio($data)
    {
        $this->startSession();

        $_SESSION['portofolio_data'] = $data;
    }


    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index($params = [])
    {
        $userId = $this->getMahasiswaId();

        // Data diambil dari PostgreSQL melalui model.
        $portofolio = $this->portofolioModel->getAllPortofolio($userId);

        $q = trim($_GET['q'] ?? '');
        $tahun = trim($_GET['tahun'] ?? '');
        $status = trim($_GET['status'] ?? '');
        $teknologi = trim($_GET['teknologi'] ?? '');

        /*
         * Pilihan filter diambil dari data milik mahasiswa yang login.
         */
        $tahunList = [];
        $teknologiList = [];

        foreach ($portofolio as $item) {
            $itemTahun = trim((string) ($item['tahun'] ?? ''));

            if ($itemTahun !== '') {
                $tahunList[] = $itemTahun;
            }

            foreach (($item['teknologi'] ?? []) as $itemTeknologi) {
                $itemTeknologi = trim((string) $itemTeknologi);

                if ($itemTeknologi !== '') {
                    $teknologiList[] = $itemTeknologi;
                }
            }
        }

        $tahunList = array_values(array_unique($tahunList));
        rsort($tahunList);

        $teknologiList = array_values(array_unique($teknologiList));
        sort($teknologiList);

        /*
         * Pencarian dan filter.
         */
        $hasilFilter = array_filter(
            $portofolio,
            function ($item) use ($q, $tahun, $status, $teknologi) {
                if ($q !== '') {
                    $keyword = mb_strtolower($q);

                    $teksPencarian = mb_strtolower(implode(' ', [
                        $item['judul'] ?? '',
                        $item['deskripsi'] ?? '',
                        $item['peran'] ?? '',
                        implode(' ', $item['teknologi'] ?? []),
                    ]));

                    if (mb_strpos($teksPencarian, $keyword) === false) {
                        return false;
                    }
                }

                if (
                    $tahun !== '' &&
                    (string) ($item['tahun'] ?? '') !== $tahun
                ) {
                    return false;
                }

                if (
                    $status !== '' &&
                    ($item['verifikasi']['status'] ?? '') !== $status
                ) {
                    return false;
                }

                if ($teknologi !== '') {
                    $daftarTeknologi = array_map(
                        'mb_strtolower',
                        $item['teknologi'] ?? []
                    );

                    if (!in_array(mb_strtolower($teknologi), $daftarTeknologi, true)) {
                        return false;
                    }
                }

                return true;
            }
        );

        $hasilFilter = array_values($hasilFilter);

        /*
         * Pagination.
         */
        $perPage = 6;
        $totalData = count($hasilFilter);
        $totalPage = max(1, (int) ceil($totalData / $perPage));

        $page = max(1, (int) ($_GET['page'] ?? 1));
        $page = min($page, $totalPage);

        $offset = ($page - 1) * $perPage;

        // Nama variabel $tampil dipertahankan agar cocok dengan view.
        $tampil = array_slice($hasilFilter, $offset, $perPage);

        $pagination = [
            'current_page' => $page,
            'per_page' => $perPage,
            'total_data' => $totalData,
            'total_page' => $totalPage,
        ];

        require __DIR__ . '/../../../pages/dashboard/mahasiswa/portofolio/index.php';
    }


    /*
    |--------------------------------------------------------------------------
    | Detail
    |--------------------------------------------------------------------------
    */

    public function detail($params = [])
    {
        $userId = $this->getMahasiswaId();
        $slug = trim($params['slug'] ?? '');

        // Model membatasi hasil berdasarkan slug dan user_id.
        $portofolioDetail = $this->portofolioModel
            ->getPortofolioBySlug($slug, $userId);

        if (!$portofolioDetail) {
            $this->showNotFound();
        }

        require __DIR__ . '/../../../pages/dashboard/mahasiswa/portofolio/detail.php';
    }


    /*
    |--------------------------------------------------------------------------
    | Tambah
    |--------------------------------------------------------------------------
    */

    public function tambah($params = [])
    {
        $userId = $this->getMahasiswaId();
        $errors = [];
        $portofolioEdit = null;

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $this->validatePostRequest();

            $data = $this->readFormData($errors);

            if (empty($errors)) {
                $gambarPathBaru = $this->saveUploadedImage($errors);

                if ($gambarPathBaru !== null) {
                    $data['gambar_path'] = $gambarPathBaru;
                }
            }

            if (empty($errors)) {
                try {
                    /*
                     * Model membuat slug unik, menyimpan data portofolio,
                     * path gambar, dan daftar teknologi.
                     */
                    $slug = $this->portofolioModel->create($userId, $data);

                    header(
                        'Location: ' .
                            url('/dashboard/mahasiswa/portofolio/detail/' . $slug)
                    );
                    exit;
                } catch (PDOException | RuntimeException $e) {
                    if (!empty($data['gambar_path'])) {
                        $this->removeUploadedImage($data['gambar_path']);
                    }

                    error_log('Gagal menambah portofolio: ' . $e->getMessage());
                    $errors['database'] =
                        'Portofolio gagal disimpan. Silakan coba kembali.';
                }
            }

            // Jika validasi gagal, isi form tetap ditampilkan.
            $portofolioEdit = $this->prepareFormValues($data);
        }

        $isEdit = false;

        require __DIR__ . '/../../../pages/dashboard/mahasiswa/portofolio/tambah.php';
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit($params = [])
    {
        $userId = $this->getMahasiswaId();
        $slug = trim($params['slug'] ?? '');

        // Data hanya dapat diedit oleh pemiliknya.
        $portofolioEdit = $this->portofolioModel
            ->getPortofolioBySlug($slug, $userId);

        if (!$portofolioEdit) {
            $this->showNotFound();
        }

        $errors = [];

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $this->validatePostRequest();

            $data = $this->readFormData($errors);

            if (empty($errors)) {
                $gambarPathBaru = $this->saveUploadedImage($errors);

                if ($gambarPathBaru !== null) {
                    $data['gambar_path'] = $gambarPathBaru;
                }
            }

            if (empty($errors)) {
                $gambarLama = $portofolioEdit['gambar'] ?? '';

                try {
                    /*
                     * Model memeriksa id dan user_id saat UPDATE.
                     * Jika tidak ada gambar baru, gambar lama dipertahankan.
                     */
                    $slugBaru = $this->portofolioModel->update(
                        (int) $portofolioEdit['id'],
                        $userId,
                        $data
                    );

                    if (
                        !empty($data['gambar_path']) &&
                        $gambarLama !== '' &&
                        $gambarLama !== $data['gambar_path']
                    ) {
                        $this->removeUploadedImage($gambarLama);
                    }

                    header(
                        'Location: ' .
                            url('/dashboard/mahasiswa/portofolio/detail/' . $slugBaru)
                    );
                    exit;
                
} catch (PDOException | RuntimeException $e) {
    if (
        !empty($data['gambar_path']) &&
        $data['gambar_path'] !== ($gambarLama ?? '')
    ) {
        $this->removeUploadedImage($data['gambar_path']);
    }

    error_log('Gagal mengubah portofolio: ' . $e->getMessage());

    $errors['database'] = $e instanceof RuntimeException
        && str_contains($e->getMessage(), 'status verifikasi sudah berubah')
            ? $e->getMessage()
            : 'Perubahan portofolio gagal disimpan. Silakan coba kembali.';
}
            }

            /*
             * Jika validasi gagal, pertahankan data awal yang tidak diedit,
             * termasuk status verifikasi dan gambar.
             */
            $portofolioEdit = $this->prepareFormValues(
                $data,
                $portofolioEdit
            );
        }

        $isEdit = true;

        require __DIR__ . '/../../../pages/dashboard/mahasiswa/portofolio/tambah.php';
    }


    /*
    |--------------------------------------------------------------------------
    | Hapus
    |--------------------------------------------------------------------------
    */

    public function hapus($params = [])
    {
        $this->validatePostRequest();

        $userId = $this->getMahasiswaId();
        $slug = trim($params['slug'] ?? '');

        try {
            // Ambil path gambar sebelum data dihapus dari database.
            $portofolio = $this->portofolioModel->getPortofolioBySlug($slug, $userId);

            if (!$portofolio) {
                $this->showNotFound();
            }

            /*
             * Model menghapus berdasarkan slug dan user_id.
             * Portofolio milik pengguna lain tidak boleh terhapus.
             */
            
$dihapus = $this->portofolioModel->deleteBySlug($slug, $userId);

if (!$dihapus) {
    http_response_code(403);
    exit(
        'Portofolio tidak dapat dihapus karena status verifikasi sudah berubah.'
    );
}
            $this->removeUploadedImage($portofolio['gambar'] ?? '');
        } catch (PDOException $e) {
            error_log('Gagal menghapus portofolio: ' . $e->getMessage());

            http_response_code(500);
            exit('Portofolio gagal dihapus. Silakan coba kembali.');
        } catch (RuntimeException $e) {
            error_log('Gagal menghapus portofolio: ' . $e->getMessage());

            http_response_code(500);
            exit('Portofolio gagal dihapus. Silakan coba kembali.');
        }

        header('Location: ' . url('/dashboard/mahasiswa/portofolio'));
        exit;
    }
}
