<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../function/Helpers.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/User.php';
require_once __DIR__ . '/../../../models/AktivitasPengguna.php';

final class TendikUserController
{
    private User $users;

    public function __construct()
    {
        global $pdo;
        $this->users = new User($pdo);
    }

    private function actorId(): int
    {
        AuthMiddleware::handle('tendik');

        $id = (int) ($_SESSION['user']['id'] ?? 0);
        if ($id < 1) {
            http_response_code(401);
            exit('Sesi pengguna tidak valid.');
        }

        return $id;
    }

    public function index(): void
    {
        $this->actorId();

        $roles = User::managedCreationRoles();
        $roleLabels = [
            'mahasiswa' => 'Mahasiswa',
            'dosen' => 'Dosen',
            'koordinator_magang' => 'Koordinator Magang',
            'tendik' => 'Tenaga Kependidikan',
        ];
        $roleIcons = [
            'mahasiswa' => 'graduation-cap',
            'dosen' => 'user-round-check',
            'koordinator_magang' => 'clipboard-check',
            'tendik' => 'briefcase-business',
        ];
        $roleStats = $this->users->getManagementRoleStats();

        $selectedRole = trim((string) ($_GET['role'] ?? ''));
        $isRoleDetail = in_array($selectedRole, $roles, true);

        $success = $_SESSION['flash_success'] ?? null;
        $error = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        $filters = [
            'search' => trim((string) ($_GET['search'] ?? '')),
            'role' => $isRoleDetail ? $selectedRole : '',
            'status' => trim((string) ($_GET['status'] ?? '')),
        ];

        $items = [];
        $totalItems = 0;
        $perPage = 10;
        $currentPage = max(1, (int) ($_GET['page'] ?? 1));
        $totalPages = 1;

        if ($isRoleDetail) {
            if (!in_array($filters['status'], ['active', 'inactive'], true)) {
                $filters['status'] = '';
            }

            $totalItems = $this->users->countForManagement($filters);
            $totalPages = max(1, (int) ceil($totalItems / $perPage));
            $currentPage = min($currentPage, $totalPages);
            $offset = ($currentPage - 1) * $perPage;
            $items = $this->users->getForManagement($filters, $perPage, $offset);
        }

        require __DIR__ . '/../../../pages/dashboard/tendik/users/index.php';
    }

    public function create(): void
    {
        $actorId = $this->actorId();
        $roles = User::managedCreationRoles();

        $form = [
            'name' => '',
            'email' => '',
            'role' => in_array((string) ($_GET['role'] ?? ''), $roles, true)
                ? (string) $_GET['role']
                : '',
            'nim' => '',
            'nidn' => '',
            'nip' => '',
            'program_studi' => '',
            'angkatan' => '',
            'no_telepon' => '',
        ];

        $error = null;
        $success = null;

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {

            if (!verifyCsrfToken()) {
                http_response_code(403);
                exit('Permintaan tidak valid.');
            }

            $role = trim((string) ($_POST['role'] ?? ''));

            $form = [
                'name' => trim((string) ($_POST['name'] ?? '')),
                'email' => trim((string) ($_POST['email'] ?? '')),
                'role' => $role,
                'nim' => trim((string) ($_POST['nim'] ?? '')),
                'nidn' => trim((string) ($_POST['nidn'] ?? '')),
                'nip' => trim((string) ($_POST['nip'] ?? '')),

                'program_studi' => ($role === 'mahasiswa')
                    ? trim((string) ($_POST['program_studi'] ?? ''))
                    : '',

                'angkatan' => ($role === 'mahasiswa')
                    ? trim((string) ($_POST['angkatan'] ?? ''))
                    : '',

                'no_telepon' => trim((string) ($_POST['no_telepon'] ?? '')),
            ];

            try {

                // ==========================================
                // VALIDASI UMUM
                // ==========================================

                if ($form['name'] === '') {
                    throw new InvalidArgumentException(
                        'Nama lengkap wajib diisi.'
                    );
                }


                if ($form['email'] === '') {
                    throw new InvalidArgumentException(
                        'Email wajib diisi.'
                    );
                }

                if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
                    throw new InvalidArgumentException(
                        'Format email tidak valid.'
                    );
                }

                if (!in_array($role, $roles, true)) {
                    throw new InvalidArgumentException(
                        'Role yang dipilih tidak valid.'
                    );
                }

                // ==========================================
                // VALIDASI MAHASISWA
                // ==========================================

                if ($role === 'mahasiswa') {

                    if ($form['nim'] === '') {
                        throw new InvalidArgumentException(
                            'NIM mahasiswa wajib diisi.'
                        );
                    }

                    if ($form['program_studi'] === '') {
                        throw new InvalidArgumentException(
                            'Program studi wajib diisi.'
                        );
                    }

                    if ($form['angkatan'] === '') {
                        throw new InvalidArgumentException(
                            'Angkatan wajib diisi.'
                        );
                    }

                    if (!preg_match('/^\d{4}$/', $form['angkatan'])) {
                        throw new InvalidArgumentException(
                            'Angkatan harus terdiri dari tepat 4 digit angka.'
                        );
                    }
                }

                // ==========================================
                // VALIDASI DOSEN
                // ==========================================

                if ($role === 'dosen') {

                    if ($form['nidn'] === '') {
                        throw new InvalidArgumentException(
                            'NIDN dosen wajib diisi.'
                        );
                    }

                    if ($form['no_telepon'] === '') {
                        throw new InvalidArgumentException(
                            'Nomor telepon dosen wajib diisi.'
                        );
                    }
                }

                // ==========================================
                // VALIDASI KOORDINATOR MAGANG
                // ==========================================

                if ($role === 'koordinator_magang') {

                    if ($form['nidn'] === '') {
                        throw new InvalidArgumentException(
                            'NIDN koordinator magang wajib diisi.'
                        );
                    }

                    if ($form['no_telepon'] === '') {
                        throw new InvalidArgumentException(
                            'Nomor telepon koordinator magang wajib diisi.'
                        );
                    }
                }

                // ==========================================
                // VALIDASI TENDIK
                // ==========================================

                if ($role === 'tendik') {

                    if ($form['nip'] === '') {
                        throw new InvalidArgumentException(
                            'NIP tenaga kependidikan wajib diisi.'
                        );
                    }

                    if ($form['no_telepon'] === '') {
                        throw new InvalidArgumentException(
                            'Nomor telepon tendik wajib diisi.'
                        );
                    }
                }

                // ==========================================
                // VALIDASI FORMAT NOMOR TELEPON
                // Berlaku untuk Dosen, Koordinator, dan Tendik
                // ==========================================

                if ($form['no_telepon'] !== '') {

                    // Hanya izinkan angka, +, spasi, tanda hubung,
                    // dan tanda kurung.
                    if (!preg_match('/^[0-9+\-\s()]+$/', $form['no_telepon'])) {
                        throw new InvalidArgumentException(
                            'Nomor telepon hanya boleh berisi angka dan tanda pemisah.'
                        );
                    }

                    // Hitung jumlah digit saja.
                    $jumlahDigit = strlen(
                        preg_replace('/\D/', '', $form['no_telepon'])
                    );

                    if ($jumlahDigit < 10 || $jumlahDigit > 15) {
                        throw new InvalidArgumentException(
                            'Nomor telepon harus terdiri dari 10 sampai 15 digit.'
                        );
                    }

                    // Pastikan tanda + hanya berada di awal.
                    if (
                        strpos($form['no_telepon'], '+') !== false
                        && strpos($form['no_telepon'], '+') !== 0
                    ) {
                        throw new InvalidArgumentException(
                            'Tanda + hanya boleh berada di awal nomor telepon.'
                        );
                    }

                    // Simpan nomor tanpa spasi atau tanda pemisah.
                    $form['no_telepon'] = preg_replace(
                        '/[\s\-()]/',
                        '',
                        $form['no_telepon']
                    );
                }

                // ==========================================
                // SIMPAN USER
                // ==========================================

                $id = $this->users->createManaged([
                    'name' => $form['name'],
                    'email' => $form['email'],
                    'role' => $form['role'],
                    'nim' => $form['nim'],
                    'nidn' => $form['nidn'],
                    'nip' => $form['nip'],
                    'program_studi' => $form['program_studi'],
                    'angkatan' => $form['angkatan'],
                    'no_telepon' => $form['no_telepon'],
                ], $actorId);

                // ==========================================
                // CATAT AKTIVITAS
                // ==========================================

                (new AktivitasPengguna())->log(
                    $actorId,
                    'User dibuat',
                    'User #' . $id . ' dibuat oleh Tendik dengan role ' . $form['role'] . '.',
                    'users',
                    'users',
                    $id
                );

                // ==========================================
                // REDIRECT DENGAN FLASH MESSAGE
                // ==========================================

                $_SESSION['flash_success'] =
                    'User berhasil ditambahkan. Password awal menggunakan NIM/NIDN/NIP sesuai role.';

                header('Location: ' . url('/dashboard/tendik/users'));
                exit;
            } catch (PDOException $exception) {

                error_log(
                    'Gagal membuat user: ' . $exception->getMessage()
                );

                $sqlState = (string) (
                    $exception->errorInfo[0] ?? $exception->getCode()
                );

                if ($sqlState === '23505') {
                    $error = 'Identitas login atau email tersebut sudah terdaftar. Periksa kembali NIM/NIDN/NIP dan email.';
                } else {
                    $error = 'User gagal dibuat karena terjadi kesalahan penyimpanan.';
                }
            } catch (InvalidArgumentException $exception) {

                $error = $exception->getMessage();
            } catch (Throwable $exception) {

                error_log(
                    'Kesalahan pembuatan user: ' . $exception->getMessage()
                );

                $error = 'Terjadi kesalahan saat memproses data user.';
            }
        }

        require __DIR__ . '/../../../pages/dashboard/tendik/users/create.php';
    }

    /** Download template CSV untuk import akun pengguna. */
    public function downloadImportTemplate(): void
    {
        $this->actorId();

        $filename = 'template_import_user.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-store, no-cache, must-revalidate');

        // BOM membantu Excel mengenali UTF-8.
        echo "\xEF\xBB\xBF";
        $output = fopen('php://output', 'wb');
        fputcsv($output, [
            'nama',
            'email',
            'role',
            'nim',
            'nidn',
            'nip',
            'no_telepon',
            'program_studi',
            'angkatan',
        ]);
        fclose($output);
        exit;
    }

    /** Menampilkan halaman import dan memproses upload untuk preview. */
    public function import(): void
    {
        $actorId = $this->actorId();
        $error = null;
        $success = null;
        $previewRows = $_SESSION['tendik_user_import_preview'] ?? [];

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            if (!verifyCsrfToken()) {
                http_response_code(403);
                exit('Permintaan tidak valid.');
            }

            $action = (string) ($_POST['action'] ?? 'preview');

            if ($action === 'confirm') {
                $rowsToImport = $_SESSION['tendik_user_import_preview'] ?? [];
                if (!is_array($rowsToImport) || $rowsToImport === []) {
                    $error = 'Preview import tidak ditemukan atau sudah kedaluwarsa. Silakan upload ulang CSV.';
                } elseif (count(array_filter($rowsToImport, static fn($row) => empty($row['valid']))) > 0) {
                    $error = 'Import dibatalkan karena masih ada baris yang tidak valid. Perbaiki CSV lalu upload kembali.';
                    $previewRows = $rowsToImport;
                } else {
                    $importResult = ['success' => 0, 'failed' => 0, 'details' => []];
                    foreach ($rowsToImport as $preview) {
                        $row = $preview['data'];
                        try {
                            $userId = $this->users->createManaged([
                                'name' => $row['nama'],
                                'email' => $row['email'],
                                'role' => $row['role'],
                                'nim' => $row['nim'],
                                'nidn' => $row['nidn'],
                                'nip' => $row['nip'],
                                'no_telepon' => $row['no_telepon'],
                                'program_studi' => $row['program_studi'],
                                'angkatan' => $row['angkatan'],
                            ], $actorId);

                            try {
                                (new AktivitasPengguna())->log(
                                    $actorId,
                                    'User diimpor melalui CSV',
                                    'User #' . $userId . ' dibuat melalui import CSV dengan role ' . $row['role'] . '.',
                                    'users',
                                    'users',
                                    $userId
                                );
                            } catch (Throwable $logException) {
                                error_log('Gagal mencatat aktivitas import user #' . $userId . ': ' . $logException->getMessage());
                            }

                            $importResult['success']++;
                            $importResult['details'][] = [
                                'line' => $preview['line'],
                                'name' => $row['nama'],
                                'status' => 'success',
                                'message' => 'Akun berhasil dibuat (ID: ' . $userId . ').',
                            ];
                        } catch (PDOException $exception) {
                            $sqlState = (string) ($exception->errorInfo[0] ?? $exception->getCode());
                            $message = $sqlState === '23505'
                                ? 'Identitas login atau email sudah terdaftar.'
                                : 'Terjadi kesalahan saat menyimpan data.';
                            error_log('Import CSV gagal pada baris ' . $preview['line'] . ': ' . $exception->getMessage());
                            $importResult['failed']++;
                            $importResult['details'][] = [
                                'line' => $preview['line'],
                                'name' => $row['nama'],
                                'status' => 'failed',
                                'message' => $message,
                            ];
                        } catch (Throwable $exception) {
                            $importResult['failed']++;
                            $importResult['details'][] = [
                                'line' => $preview['line'],
                                'name' => $row['nama'],
                                'status' => 'failed',
                                'message' => $exception->getMessage(),
                            ];
                        }
                    }

                    unset($_SESSION['tendik_user_import_preview']);
                    $previewRows = [];
                    $success = 'Proses import selesai. ' . $importResult['success'] . ' akun berhasil dibuat dan ' . $importResult['failed'] . ' akun gagal.';
                }
            } elseif ($action !== 'preview') {
                $error = 'Aksi import tidak dikenali.';
            } elseif (!isset($_FILES['csv_file']) || !is_array($_FILES['csv_file'])) {
                $error = 'Silakan pilih file CSV terlebih dahulu.';
            } else {
                $file = $_FILES['csv_file'];
                if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                    $error = 'File gagal diunggah. Silakan coba kembali.';
                } elseif ((int) ($file['size'] ?? 0) > 2 * 1024 * 1024) {
                    $error = 'Ukuran file maksimal 2 MB.';
                } elseif (strtolower(pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION)) !== 'csv') {
                    $error = 'File harus menggunakan format .csv.';
                } else {
                    $handle = fopen((string) $file['tmp_name'], 'rb');
                    if ($handle === false) {
                        $error = 'File CSV tidak dapat dibaca.';
                    } else {
                        $headers = fgetcsv($handle, 0, ',');
                        if (is_array($headers) && isset($headers[0])) {
                            $headers[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) $headers[0]);
                        }

                        $expectedHeaders = [
                            'nama',
                            'email',
                            'role',
                            'nim',
                            'nidn',
                            'nip',
                            'no_telepon',
                            'program_studi',
                            'angkatan',
                        ];
                        $normalizedHeaders = is_array($headers)
                            ? array_map(static fn($value) => strtolower(trim((string) $value)), $headers)
                            : [];

                        if ($normalizedHeaders !== $expectedHeaders) {
                            $error = 'Header CSV tidak sesuai template. Unduh template terbaru dan jangan mengubah nama atau urutan kolom.';
                        } else {
                            $previewRows = [];
                            $line = 1;
                            while (($values = fgetcsv($handle, 0, ',')) !== false) {
                                $line++;
                                if ($values === [null] || (count($values) === 1 && trim((string) $values[0]) === '')) {
                                    continue;
                                }
                                if (count($previewRows) >= 500) {
                                    $error = 'Maksimal 500 baris data dalam satu kali import.';
                                    break;
                                }

                                $values = array_pad(array_slice($values, 0, count($expectedHeaders)), count($expectedHeaders), '');
                                $row = array_combine($expectedHeaders, array_map(static fn($value) => trim((string) $value), $values));
                                $rowErrors = [];
                                $role = $row['role'];
                                if (!filter_var($row['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($row['email']) > 255) {
                                    $rowErrors[] = 'Email tidak valid.';
                                }
                                if (!in_array($role, User::managedCreationRoles(), true)) {
                                    $rowErrors[] = 'Role tidak valid.';
                                } elseif ($role === 'mahasiswa') {
                                    if (!preg_match('/^[A-Za-z0-9.-]{3,30}$/', $row['nim'])) {
                                        $rowErrors[] = 'NIM wajib diisi dan harus valid.';
                                    }
                                    if ($row['program_studi'] === '') {
                                        $rowErrors[] = 'Program Studi wajib diisi.';
                                    }
                                    if ($row['angkatan'] === '' || !ctype_digit($row['angkatan']) || (int) $row['angkatan'] < 2000 || (int) $row['angkatan'] > 2100) {
                                        $rowErrors[] = 'Angkatan tidak valid.';
                                    }
                                    if ($row['nidn'] !== '' || $row['nip'] !== '' || $row['no_telepon'] !== '') {
                                        $rowErrors[] = 'Kolom NIDN, NIP, dan nomor telepon harus kosong untuk Mahasiswa.';
                                    }
                                } elseif (in_array($role, ['dosen', 'koordinator_magang'], true)) {
                                    if (!preg_match('/^[A-Za-z0-9.-]{3,30}$/', $row['nidn'])) {
                                        $rowErrors[] = 'NIDN wajib diisi dan harus valid.';
                                    }
                                    $digits = preg_replace('/\D+/', '', $row['no_telepon']);
                                    if ($row['no_telepon'] === '' || $digits === null || strlen($digits) < 8 || strlen($digits) > 15) {
                                        $rowErrors[] = 'Nomor telepon wajib diisi dan harus valid.';
                                    }
                                    if ($row['nim'] !== '' || $row['nip'] !== '' || $row['program_studi'] !== '' || $row['angkatan'] !== '') {
                                        $rowErrors[] = 'Kolom NIM, NIP, Program Studi, dan Angkatan harus kosong untuk role ini.';
                                    }
                                } elseif ($role === 'tendik') {
                                    if (!preg_match('/^[A-Za-z0-9.-]{3,30}$/', $row['nip'])) {
                                        $rowErrors[] = 'NIP wajib diisi dan harus valid.';
                                    }
                                    $digits = preg_replace('/\D+/', '', $row['no_telepon']);
                                    if ($row['no_telepon'] === '' || $digits === null || strlen($digits) < 8 || strlen($digits) > 15) {
                                        $rowErrors[] = 'Nomor telepon wajib diisi dan harus valid.';
                                    }
                                    if ($row['nim'] !== '' || $row['nidn'] !== '' || $row['program_studi'] !== '' || $row['angkatan'] !== '') {
                                        $rowErrors[] = 'Kolom NIM, NIDN, Program Studi, dan Angkatan harus kosong untuk Tendik.';
                                    }
                                }

                                $previewRows[] = [
                                    'line' => $line,
                                    'data' => $row,
                                    'errors' => $rowErrors,
                                    'valid' => count($rowErrors) === 0,
                                ];
                            }
                        }
                        fclose($handle);

                        if ($error === null && count($previewRows) === 0) {
                            $error = 'File CSV tidak memiliki data untuk diproses.';
                        }
                        if ($error === null) {
                            $_SESSION['tendik_user_import_preview'] = $previewRows;
                            $success = 'Preview berhasil dibuat. Periksa seluruh baris sebelum melanjutkan.';
                        } else {
                            unset($_SESSION['tendik_user_import_preview']);
                            $previewRows = [];
                        }
                    }
                }
            }
        }

        require __DIR__ . '/../../../pages/dashboard/tendik/users/import.php';
    }
    public function detail(array $params = []): void
    {
        $actorId = $this->actorId();
        $id = (int) ($params['id'] ?? 0);

        if ($id < 1) {
            http_response_code(404);
            exit('Pengguna tidak ditemukan.');
        }

        $user = $this->users->getManagementDetail($id);

        if (!$user) {
            http_response_code(404);
            exit('Pengguna tidak ditemukan.');
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            if (!verifyCsrfToken()) {
                http_response_code(403);
                exit('Permintaan tidak valid.');
            }

            try {
                $this->users->updateManagementProfile($id, [
                    'name' => $_POST['name'] ?? '',
                    'email' => $_POST['email'] ?? '',
                    'no_telepon' => $_POST['no_telepon'] ?? '',
                    'nim' => $_POST['nim'] ?? '',
                    'nidn' => $_POST['nidn'] ?? '',
                    'nip' => $_POST['nip'] ?? '',
                    'program_studi' => $_POST['program_studi'] ?? '',
                    'angkatan' => $_POST['angkatan'] ?? '',
                ]);

                (new AktivitasPengguna())->log(
                    $actorId,
                    'Profil pengguna diperbarui',
                    'Data profil user #' . $id . ' diperbarui oleh Tendik.',
                    'users',
                    'users',
                    $id
                );

                $_SESSION['flash_success'] = 'Informasi pengguna berhasil diperbarui.';

                header('Location: ' . url('/dashboard/tendik/users'));
                exit;
            } catch (Throwable $exception) {
                $_SESSION['flash_error'] = $exception->getMessage();

                header('Location: ' . url('/dashboard/tendik/users/' . $id));
                exit;
            }
        }

        require __DIR__ . '/../../../pages/dashboard/tendik/users/detail.php';
    }


    public function updateStatus(array $params = []): void
    {
        $actorId = $this->actorId();
        $id = (int) ($params['id'] ?? 0);

        if ($id < 1 || ($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            http_response_code(404);
            exit('Permintaan tidak valid.');
        }

        if (!verifyCsrfToken()) {
            http_response_code(403);
            exit('Permintaan tidak valid.');
        }

        try {
            $status = $_POST['status'] ?? '';

            if (!in_array($status, ['active', 'inactive'], true)) {
                throw new InvalidArgumentException('Status akun tidak valid.');
            }

            $active = $status === 'active';

            $this->users->setActive($id, $active, $actorId);

            (new AktivitasPengguna())->log(
                $actorId,
                $active ? 'Akun diaktifkan' : 'Akun dinonaktifkan',
                'Status akun pengguna #' . $id . ' diubah oleh Tendik.',
                'users',
                'users',
                $id
            );

            $_SESSION['flash_success'] = $active
                ? 'Akun berhasil diaktifkan.'
                : 'Akun berhasil dinonaktifkan.';
        } catch (Throwable $exception) {
            $_SESSION['flash_error'] = $exception->getMessage();
        }

        $returnRole = (string) ($_POST['return_role'] ?? '');
        $redirect = url('/dashboard/tendik/users');
        if (in_array($returnRole, User::managedCreationRoles(), true)) {
            $redirect .= '?role=' . rawurlencode($returnRole);
        }
        header('Location: ' . $redirect);
        exit;
    }
}
