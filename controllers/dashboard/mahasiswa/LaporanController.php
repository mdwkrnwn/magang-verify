<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../function/Helpers.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';

require_once __DIR__ . '/../../../models/LaporanMagang.php';
require_once __DIR__ . '/../../../models/TemplateLaporan.php';
require_once __DIR__ . '/../../../models/LaporanRevisi.php';
require_once __DIR__ . '/../../../models/PemeriksaanLaporan.php';
require_once __DIR__ . '/../../../models/AktivitasPengguna.php';
require_once __DIR__ . '/../../../models/Logbook.php';
require_once __DIR__ . '/../../../helpers/LaporanMingguanDocxGenerator.php';

class LaporanController
{
    private LaporanMagang $laporanModel;
    private TemplateLaporan $templateModel;
    private LaporanRevisi $revisiModel;
    private PemeriksaanLaporan $pemeriksaanModel;
    private AktivitasPengguna $aktivitasModel;
    private Logbook $logbookModel;

    private string $uploadRelativePath = 'storage/laporan';
    private string $uploadDirectory;


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct()
    {
        $this->laporanModel = new LaporanMagang();
        $this->templateModel = new TemplateLaporan();
        $this->revisiModel = new LaporanRevisi();
        $this->pemeriksaanModel = new PemeriksaanLaporan();
        $this->aktivitasModel = new AktivitasPengguna();
        $this->logbookModel = new Logbook();

        $this->uploadDirectory =
            dirname(__DIR__, 3) . '/' . $this->uploadRelativePath;
    }


    /*
    |--------------------------------------------------------------------------
    | User ID
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | Redirect
    |--------------------------------------------------------------------------
    */

    private function redirect(string $path): never
    {
        header('Location: ' . url($path));
        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | Validate POST
    |--------------------------------------------------------------------------
    */

    private function validatePost(): void
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


    /*
    |--------------------------------------------------------------------------
    | 404
    |--------------------------------------------------------------------------
    */

    private function notFound(): never
    {
        http_response_code(404);

        require __DIR__ . '/../../../pages/errors/404.php';

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index($params = []): void
    {
        $userId = $this->userId();

        $placementId =
            isset($_GET['penempatan']) &&
            ctype_digit((string) $_GET['penempatan'])
                ? (int) $_GET['penempatan']
                : null;

        $placements = $this->laporanModel->getPlacements($userId);

        $placement = $this->laporanModel->getPlacement(
            $userId,
            $placementId
        );

        $reports = $placement
            ? $this->laporanModel->getByPlacement(
                (int) $placement['id']
            )
            : [];

        require __DIR__ . '/../../../pages/dashboard/mahasiswa/laporan/index.php';
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |
    | Membuat draft laporan.
    |
    | Untuk laporan mingguan:
    | laporan mengambil kegiatan dari Logbook berdasarkan minggu.
    |--------------------------------------------------------------------------
    */

    public function create($params = []): void
    {
        $userId = $this->userId();


        /*
        |--------------------------------------------------------------------------
        | Ambil Penempatan
        |--------------------------------------------------------------------------
        */

        $placementId =
            isset($_GET['penempatan']) &&
            ctype_digit((string) $_GET['penempatan'])
                ? (int) $_GET['penempatan']
                : null;

        $placement = $this->laporanModel->getPlacement(
            $userId,
            $placementId
        );

        if (!$placement) {
            http_response_code(409);
            exit('Penempatan magang belum tersedia.');
        }


        /*
        |--------------------------------------------------------------------------
        | Data Laporan
        |--------------------------------------------------------------------------
        */

        $reports = $this->laporanModel->getByPlacement(
            (int) $placement['id']
        );

        $nextWeekly = $this->nextWeeklyNumber($reports);

        $finalOpen = $this->laporanModel->isFinalReportOpen(
            $placement
        );

        $templates = $this->templateModel->getAllActive();


        /*
        |--------------------------------------------------------------------------
        | Ambil Logbook Mingguan
        |--------------------------------------------------------------------------
        |
        | Hanya mengambil minggu Logbook dari penempatan yang
        | sedang digunakan.
        |
        */

        $logbookWeeks = $this->logbookModel->getWeeks(
            (int) $placement['id']
        );


        /*
        |--------------------------------------------------------------------------
        | Tentukan Minggu yang Dipilih
        |--------------------------------------------------------------------------
        |
        | Prioritas:
        |
        | 1. POST minggu_ke
        | 2. GET minggu
        | 3. minggu laporan berikutnya
        |
        */

        if (
            isset($_POST['minggu_ke']) &&
            ctype_digit((string) $_POST['minggu_ke'])
        ) {
            $selectedWeek = (int) $_POST['minggu_ke'];

        } elseif (
            isset($_GET['minggu']) &&
            ctype_digit((string) $_GET['minggu'])
        ) {
            $selectedWeek = (int) $_GET['minggu'];

        } else {
            $selectedWeek = $nextWeekly;
        }


        /*
        |--------------------------------------------------------------------------
        | Validasi minggu minimal
        |--------------------------------------------------------------------------
        */

        if ($selectedWeek < 1) {
            $selectedWeek = 1;
        }


        /*
        |--------------------------------------------------------------------------
        | Cari Logbook sesuai minggu
        |--------------------------------------------------------------------------
        */

        $selectedLogbook = null;
        $weeklyActivities = [];

        foreach ($logbookWeeks as $week) {

            if ((int) ($week['minggu_ke'] ?? 0) !== $selectedWeek) {
                continue;
            }

            $selectedLogbook = $week;

            $weeklyActivities =
                $this->logbookModel->getDailyEntries(
                    (int) $week['id']
                );

            break;
        }


        /*
        |--------------------------------------------------------------------------
        | Form
        |--------------------------------------------------------------------------
        */

        $error = null;

        $form = [
            'jenis_laporan' => (string) (
                $_POST['jenis_laporan']
                ?? LaporanMagang::WEEKLY
            ),

            'minggu_ke' => (string) $selectedWeek,

            'judul' => trim(
                (string) (
                    $_POST['judul']
                    ?? ''
                )
            ),
        ];


        /*
        |--------------------------------------------------------------------------
        | POST
        |--------------------------------------------------------------------------
        */

        if (
            ($_SERVER['REQUEST_METHOD'] ?? 'GET')
            === 'POST'
        ) {

            $this->validatePost();

            try {

                $jenis = $form['jenis_laporan'];


                /*
                |--------------------------------------------------------------------------
                | Validasi jenis laporan
                |--------------------------------------------------------------------------
                */

                if (
                    !in_array(
                        $jenis,
                        [
                            LaporanMagang::WEEKLY,
                            LaporanMagang::FINAL,
                        ],
                        true
                    )
                ) {
                    throw new InvalidArgumentException(
                        'Jenis laporan tidak valid.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Minggu Ke
                |--------------------------------------------------------------------------
                */

                $mingguKe =
                    $jenis === LaporanMagang::WEEKLY
                        ? (int) $form['minggu_ke']
                        : null;


                /*
                |--------------------------------------------------------------------------
                | Laporan Akhir
                |--------------------------------------------------------------------------
                */

                if (
                    $jenis === LaporanMagang::FINAL
                    && !$finalOpen
                ) {
                    throw new RuntimeException(
                        'Laporan akhir baru dapat dibuat pada akhir periode magang.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Template
                |--------------------------------------------------------------------------
                */

                $template =
                    $this->templateModel->getActiveByType(
                        $jenis
                    );

                if (!$template) {
                    throw new RuntimeException(
                        'Template laporan untuk jenis ini belum tersedia.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Validasi Laporan Mingguan
                |--------------------------------------------------------------------------
                */

                if (
                    $jenis === LaporanMagang::WEEKLY
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | Pastikan minggu dipilih
                    |--------------------------------------------------------------------------
                    */

                    if ($mingguKe < 1) {
                        throw new InvalidArgumentException(
                            'Minggu laporan tidak valid.'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Pastikan Logbook minggu tersebut ada
                    |--------------------------------------------------------------------------
                    */

                    if (!$selectedLogbook) {
                        throw new RuntimeException(
                            'Logbook untuk minggu ke-'
                            . $mingguKe
                            . ' belum tersedia.'
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Pastikan Logbook memiliki kegiatan
                    |--------------------------------------------------------------------------
                    */

                    if (empty($weeklyActivities)) {
                        throw new RuntimeException(
                            'Logbook minggu ke-'
                            . $mingguKe
                            . ' belum memiliki kegiatan.'
                        );
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | Judul
                |--------------------------------------------------------------------------
                */

                if ($form['judul'] === '') {

                    $form['judul'] =
                        $jenis === LaporanMagang::WEEKLY
                            ? 'Laporan Magang Minggu Ke-' . $mingguKe
                            : 'Laporan Akhir Magang';
                }


                /*
                |--------------------------------------------------------------------------
                | Buat Draft
                |--------------------------------------------------------------------------
                */

                $id = $this->laporanModel->createDraft(
                    (int) $placement['id'],
                    $jenis,
                    $mingguKe,
                    $form['judul'],
                    (int) $template['id']
                );


                /*
                |--------------------------------------------------------------------------
                | Log Aktivitas
                |--------------------------------------------------------------------------
                */

                $this->aktivitasModel->log(
                    $userId,
                    'Membuat draft laporan',
                    'Draft '
                    . (
                        $jenis === LaporanMagang::WEEKLY
                            ? 'laporan mingguan ke-' . $mingguKe
                            : 'laporan akhir'
                    )
                    . ' berhasil dibuat.',
                    'laporan',
                    'laporan_magang',
                    $id
                );


                /*
                |--------------------------------------------------------------------------
                | Redirect Detail
                |--------------------------------------------------------------------------
                */

                $this->redirect(
                    '/dashboard/mahasiswa/laporan/detail/'
                    . $id
                );

            } catch (Throwable $e) {

                $error = $e->getMessage();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        require __DIR__ . '/../../../pages/dashboard/mahasiswa/laporan/form.php';
    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL
    |--------------------------------------------------------------------------
    */

    public function detail($params = []): void
    {
        $userId = $this->userId();

        $id = (int) ($params['id'] ?? 0);

        $laporan = $this->laporanModel->getById(
            $id,
            $userId
        );

        if (!$laporan) {
            $this->notFound();
        }


        $revisi = $this->revisiModel->getByLaporan($id);

        $pemeriksaan =
            $this->pemeriksaanModel->getByLaporan($id);

        $template =
            $laporan['template_id'] !== null
                ? $this->templateModel->getById(
                    (int) $laporan['template_id']
                )
                : $this->templateModel->getActiveByType(
                    (string) $laporan['jenis_laporan']
                );


        $canUpload = in_array(
            (string) $laporan['status'],
            [
                LaporanMagang::DRAFT,
                LaporanMagang::NEEDS_REVISION,
            ],
            true
        );

        $error = null;

        $formAction = url(
    '/dashboard/mahasiswa/laporan/detail/' . $id
);

        /*
        |--------------------------------------------------------------------------
        | Upload Laporan
        |--------------------------------------------------------------------------
        */

        if (
            ($_SERVER['REQUEST_METHOD'] ?? 'GET')
            === 'POST'
        ) {

            $this->validatePost();

            try {

                if (!$canUpload) {
                    throw new RuntimeException(
                        'Laporan belum dapat diunggah pada status saat ini.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Validasi PDF
                |--------------------------------------------------------------------------
                */

                $file = $this->validateUploadedPdf(true);

                $storedPath =
                    $this->storeUploadedPdf($file);


                global $pdo;

                $pdo->beginTransaction();

                try {

                    $version =
                        $this->revisiModel->nextVersion($id);

                    $ringkasan =
                        trim(
                            (string) (
                                $_POST['ringkasan']
                                ?? ''
                            )
                        ) ?: null;

                    $catatanRevisi =
                        trim(
                            (string) (
                                $_POST['catatan_revisi']
                                ?? ''
                            )
                        ) ?: null;


                    /*
                    |--------------------------------------------------------------------------
                    | Simpan revisi
                    |--------------------------------------------------------------------------
                    */

                    $this->revisiModel->create(
                        $id,
                        $version,
                        $storedPath,
                        $ringkasan,
                        $catatanRevisi,
                        $userId
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Ketepatan waktu
                    |--------------------------------------------------------------------------
                    |
                    | Belum dihitung karena deadline laporan
                    | belum ditentukan di aturan bisnis.
                    |
                    */

                    $timing = null;


                    /*
                    |--------------------------------------------------------------------------
                    | Submit
                    |--------------------------------------------------------------------------
                    */

                    if (
                        !$this->laporanModel->submit(
                            $id,
                            $userId,
                            $storedPath,
                            $version,
                            $timing
                        )
                    ) {
                        throw new RuntimeException(
                            'Data laporan tidak dapat diperbarui.'
                        );
                    }


                    $pdo->commit();

                } catch (Throwable $e) {

                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }

                    $this->deleteStoredFile(
                        $storedPath
                    );

                    throw $e;
                }


                /*
                |--------------------------------------------------------------------------
                | Activity Log
                |--------------------------------------------------------------------------
                */

                $this->aktivitasModel->log(
                    $userId,
                    'Laporan magang diunggah',
                    'Laporan "'
                    . (
                        $laporan['judul']
                        ?? 'Laporan'
                    )
                    . '" berhasil diunggah sebagai versi '
                    . $version
                    . '.',
                    'laporan',
                    'laporan_magang',
                    $id
                );


                $this->redirect(
                    '/dashboard/mahasiswa/laporan/detail/'
                    . $id
                );

            } catch (Throwable $e) {

                $error = $e->getMessage();
            }
        }


        require __DIR__ . '/../../../pages/dashboard/mahasiswa/laporan/detail.php';
    }


    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD TEMPLATE
    |--------------------------------------------------------------------------
    */

    public function downloadTemplate($params = []): void
{
    $userId = $this->userId();

    $id = (int) ($params['id'] ?? 0);

    $laporan = $this->laporanModel->getById(
        $id,
        $userId
    );

    if (!$laporan) {
        $this->notFound();
    }

    /*
     * ------------------------------------------------------------
     * Laporan akhir
     * ------------------------------------------------------------
     *
     * Untuk sementara laporan akhir tetap menggunakan
     * template master karena integrasi otomatis Logbook
     * hanya berlaku untuk laporan mingguan.
     */
    if (
        ($laporan['jenis_laporan'] ?? '')
        !== LaporanMagang::WEEKLY
    ) {
        $templatePath = (string) (
            $laporan['file_template'] ?? ''
        );

        $this->downloadPrivateFile(
            $templatePath,
            'template-' .
            $this->slugFileName(
                (string) $laporan['jenis_laporan']
            )
        );
    }

    /*
     * ------------------------------------------------------------
     * Laporan mingguan
     * ------------------------------------------------------------
     */

    $penempatanId = (int) (
        $laporan['penempatan_id'] ?? 0
    );

    $mingguKe = (int) (
        $laporan['minggu_ke'] ?? 0
    );

    if ($penempatanId < 1 || $mingguKe < 1) {
        throw new RuntimeException(
            'Data penempatan atau minggu laporan tidak valid.'
        );
    }

    /*
     * Cari Logbook minggu yang sesuai.
     */
    $logbookWeeks = $this->logbookModel->getWeeks(
        $penempatanId
    );

    $selectedLogbook = null;

    foreach ($logbookWeeks as $week) {
        if (
            (int) ($week['minggu_ke'] ?? 0)
            === $mingguKe
        ) {
            $selectedLogbook = $week;
            break;
        }
    }

    if (!$selectedLogbook) {
        throw new RuntimeException(
            'Logbook untuk minggu ke-' .
            $mingguKe .
            ' belum tersedia.'
        );
    }

    /*
     * Ambil seluruh kegiatan harian.
     */
    $activities = $this->logbookModel->getDailyEntries(
        (int) $selectedLogbook['id']
    );

    if (!$activities) {
        throw new RuntimeException(
            'Logbook minggu ke-' .
            $mingguKe .
            ' belum memiliki kegiatan.'
        );
    }

    /*
     * Template master.
     */
    $templateRelativePath = (string) (
        $laporan['file_template'] ?? ''
    );

    $templatePath = dirname(
        __DIR__,
        3
    ) . '/' . ltrim(
        $templateRelativePath,
        '/'
    );

    if (!is_file($templatePath)) {
        throw new RuntimeException(
            'File template laporan mingguan tidak ditemukan.'
        );
    }

    /*
     * Folder hasil generate.
     */
    $generatedDirectory = dirname(
        __DIR__,
        3
    ) . '/storage/laporan/generated';

    if (!is_dir($generatedDirectory)) {
        if (
            !mkdir(
                $generatedDirectory,
                0775,
                true
            )
            &&
            !is_dir($generatedDirectory)
        ) {
            throw new RuntimeException(
                'Folder laporan hasil generate tidak dapat dibuat.'
            );
        }
    }

    /*
     * Nama file hasil.
     */
    $filename =
        'Laporan-Mingguan-Minggu-' .
        $mingguKe .
        '-' .
        date('YmdHis') .
        '.docx';

    $outputPath =
        $generatedDirectory .
        '/' .
        $filename;

    /*
     * Generate DOCX.
     */
    LaporanMingguanDocxGenerator::generate(
        $templatePath,
        $outputPath,
        $activities,
        [
            'minggu_ke' => $mingguKe,

            /*
             * Untuk tahap pertama, data yang sudah pasti
             * berasal dari Logbook kita isi otomatis.
             */
            'periode_minggu' =>
                ($selectedLogbook['tanggal_mulai'] ?? '') .
                ' s/d ' .
                ($selectedLogbook['tanggal_selesai'] ?? ''),
        ]
    );

    /*
     * Download file hasil generate.
     */
    $this->downloadPrivateFile(
        'storage/laporan/generated/' . $filename,
        'laporan-mingguan-minggu-' . $mingguKe
    );
}


    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD LAPORAN
    |--------------------------------------------------------------------------
    */

    public function download($params = []): void
    {
        $userId = $this->userId();

        $id = (int) ($params['id'] ?? 0);

        $laporan = $this->laporanModel->getById(
            $id,
            $userId
        );

        if (!$laporan) {
            $this->notFound();
        }


        $path =
            (string) (
                $laporan['file_path']
                ?? ''
            );


        if ($path === '') {

            $latest =
                $this->revisiModel->getLatest($id);

            $path =
                (string) (
                    $latest['file_path']
                    ?? ''
                );
        }


        $this->downloadPrivateFile(
            $path,
            'laporan-'
            . (
                $laporan['minggu_ke'] !== null
                    ? 'minggu-' . (int) $laporan['minggu_ke']
                    : 'akhir'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | NEXT WEEKLY NUMBER
    |--------------------------------------------------------------------------
    */

    private function nextWeeklyNumber(
        array $reports
    ): int {

        $max = 0;

        foreach ($reports as $report) {

            if (
                ($report['jenis_laporan'] ?? '')
                !== LaporanMagang::WEEKLY
            ) {
                continue;
            }

            $max = max(
                $max,
                (int) (
                    $report['minggu_ke']
                    ?? 0
                )
            );
        }

        return $max + 1;
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE UPLOADED PDF
    |--------------------------------------------------------------------------
    */

    private function validateUploadedPdf(
        bool $required
    ): array {

        $file =
            $_FILES['file_laporan']
            ?? [];

        $error =
            (int) (
                $file['error']
                ?? UPLOAD_ERR_NO_FILE
            );


        if (
            $error === UPLOAD_ERR_NO_FILE
        ) {

            if ($required) {
                throw new InvalidArgumentException(
                    'File laporan wajib diunggah.'
                );
            }

            return [];
        }


        if ($error !== UPLOAD_ERR_OK) {
            throw new InvalidArgumentException(
                'File laporan gagal diunggah.'
            );
        }


        if (
            (int) (
                $file['size']
                ?? 0
            ) < 1
        ) {
            throw new InvalidArgumentException(
                'File laporan kosong atau tidak valid.'
            );
        }


        if (
            (int) $file['size']
            > 10 * 1024 * 1024
        ) {
            throw new InvalidArgumentException(
                'Ukuran file laporan maksimal 10 MB.'
            );
        }


        $tmp =
            (string) (
                $file['tmp_name']
                ?? ''
            );


        if (
            $tmp === ''
            || !is_uploaded_file($tmp)
        ) {
            throw new InvalidArgumentException(
                'File unggahan tidak valid.'
            );
        }


        $mime =
            (new finfo(FILEINFO_MIME_TYPE))
            ->file($tmp);

        $extension =
            strtolower(
                pathinfo(
                    (string) (
                        $file['name']
                        ?? ''
                    ),
                    PATHINFO_EXTENSION
                )
            );

        $signature =
            file_get_contents(
                $tmp,
                false,
                null,
                0,
                5
            );


        if (
            $mime !== 'application/pdf'
            || $extension !== 'pdf'
            || $signature !== '%PDF-'
        ) {
            throw new InvalidArgumentException(
                'File laporan harus berupa PDF yang valid.'
            );
        }


        return $file;
    }


    /*
    |--------------------------------------------------------------------------
    | STORE UPLOADED PDF
    |--------------------------------------------------------------------------
    */

    private function storeUploadedPdf(
        array $file
    ): string {

        if (
            !is_dir($this->uploadDirectory)
            && !mkdir(
                $this->uploadDirectory,
                0750,
                true
            )
            && !is_dir($this->uploadDirectory)
        ) {
            throw new RuntimeException(
                'Folder penyimpanan laporan tidak dapat dibuat.'
            );
        }


        $filename =
            bin2hex(
                random_bytes(24)
            )
            . '.pdf';


        $destination =
            $this->uploadDirectory
            . '/'
            . $filename;


        if (
            !move_uploaded_file(
                (string) $file['tmp_name'],
                $destination
            )
        ) {
            throw new RuntimeException(
                'File laporan gagal disimpan.'
            );
        }


        return
            $this->uploadRelativePath
            . '/'
            . $filename;
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE STORED FILE
    |--------------------------------------------------------------------------
    */

    private function deleteStoredFile(
        ?string $relativePath
    ): void {

        if (
            !$relativePath
            || !str_starts_with(
                $relativePath,
                $this->uploadRelativePath . '/'
            )
        ) {
            return;
        }


        $base =
            realpath(
                $this->uploadDirectory
            );

        $file =
            realpath(
                dirname(__DIR__, 3)
                . '/'
                . $relativePath
            );


        if (
            $base !== false
            && $file !== false
            && str_starts_with(
                $file,
                $base . DIRECTORY_SEPARATOR
            )
            && is_file($file)
        ) {
            @unlink($file);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD PRIVATE FILE
    |--------------------------------------------------------------------------
    */

    private function downloadPrivateFile(
        string $relativePath,
        string $downloadName
    ): never {

        if ($relativePath === '') {
            $this->notFound();
        }


        $root =
            dirname(__DIR__, 3);

        $file =
            realpath(
                $root
                . '/'
                . ltrim(
                    $relativePath,
                    '/'
                )
            );


        if (
            $file === false
            || !is_file($file)
        ) {
            $this->notFound();
        }


        $mime =
            (new finfo(FILEINFO_MIME_TYPE))
            ->file($file);


        /*
        |--------------------------------------------------------------------------
        | Template DOCX juga perlu bisa didownload
        |--------------------------------------------------------------------------
        |
        | Sebelumnya method ini hanya menerima PDF.
        | Karena template kita berupa DOCX, kita izinkan PDF dan DOCX.
        |
        */

        $extension =
            strtolower(
                pathinfo(
                    $file,
                    PATHINFO_EXTENSION
                )
            );


        $allowed = [
            'pdf' => 'application/pdf',

            'docx' =>
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];


        if (
            !isset($allowed[$extension])
            || $mime !== $allowed[$extension]
        ) {
            http_response_code(415);
            exit('File tidak valid.');
        }


        $downloadFilename =
            $this->slugFileName(
                $downloadName
            )
            . '.'
            . $extension;


        header(
            'Content-Type: '
            . $allowed[$extension]
        );

        header(
            'Content-Disposition: attachment; filename="'
            . $downloadFilename
            . '"'
        );

        header(
            'Content-Length: '
            . filesize($file)
        );

        header(
            'X-Content-Type-Options: nosniff'
        );


        readfile($file);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | SLUG FILE NAME
    |--------------------------------------------------------------------------
    */

    private function slugFileName(
        string $value
    ): string {

        $value =
            preg_replace(
                '/[^a-zA-Z0-9\_-]+/',
                '-',
                $value
            )
            ?: 'laporan';


        return trim(
            $value,
            '-_'
        ) ?: 'laporan';
    }
}