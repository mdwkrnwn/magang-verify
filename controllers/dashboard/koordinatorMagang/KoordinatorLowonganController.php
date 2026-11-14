
<?php

require_once __DIR__ . '/../../../function/Helpers.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/KoordinatorLowongan.php';

final class KoordinatorLowonganController
{
    private const WORK_SYSTEMS = [
        'onsite',
        'hybrid',
        'remote',
    ];

    private const STATUSES = [
        'draft',
        'dibuka',
        'ditutup',
        'selesai',
        'dibatalkan',
    ];

    private function authorize(): void
    {
        AuthMiddleware::handleAny([
            'tendik',
            'koordinator_magang',
        ]);
    }

    private function emptyForm(): array
    {
        return [
            'mitra_id' => '',
            'judul' => '',
            'deskripsi' => '',
            'bidang' => '',
            'jumlah_kuota' => '1',
            'persyaratan' => '',
            'lokasi_magang' => '',
            'sistem_kerja' => 'onsite',
            'tanggal_mulai' => '',
            'tanggal_selesai' => '',
            'tahun_akademik' => '',
            'status' => 'draft',
        ];
    }

    private function readForm(): array
    {
        $form = $this->emptyForm();

        foreach ($form as $key => $_) {
            $form[$key] = trim(
                (string) ($_POST[$key] ?? '')
            );
        }

        return $form;
    }

    private function validDate(string $date): bool
    {
        $parsed = DateTimeImmutable::createFromFormat(
            '!Y-m-d',
            $date
        );

        return $parsed !== false
            && $parsed->format('Y-m-d') === $date;
    }

    private function validate(array $form): ?string
    {
        $mitraId = filter_var(
            $form['mitra_id'],
            FILTER_VALIDATE_INT
        );

        if (!$mitraId || $mitraId < 1) {
            return 'Mitra wajib dipilih.';
        }

        if (
            $form['judul'] === ''
            || mb_strlen($form['judul']) > 200
        ) {
            return 'Judul lowongan wajib diisi dan maksimal 200 karakter.';
        }

        if ($form['deskripsi'] === '') {
            return 'Deskripsi lowongan wajib diisi.';
        }

        $quota = filter_var(
            $form['jumlah_kuota'],
            FILTER_VALIDATE_INT
        );

        if (!$quota || $quota < 1 || $quota > 32767) {
            return 'Jumlah kuota harus berupa bilangan bulat antara 1 dan 32767.';
        }

        if (
            !in_array(
                $form['sistem_kerja'],
                self::WORK_SYSTEMS,
                true
            )
        ) {
            return 'Sistem kerja tidak valid.';
        }

        if (
            !$this->validDate($form['tanggal_mulai'])
            || !$this->validDate($form['tanggal_selesai'])
        ) {
            return 'Tanggal mulai dan selesai harus valid.';
        }

        if (
            $form['tanggal_selesai']
            < $form['tanggal_mulai']
        ) {
            return 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.';
        }

        if (
            $form['tahun_akademik'] === ''
            || mb_strlen($form['tahun_akademik']) > 20
        ) {
            return 'Tahun akademik wajib diisi dan maksimal 20 karakter.';
        }

        if (
            !in_array(
                $form['status'],
                self::STATUSES,
                true
            )
        ) {
            return 'Status lowongan tidak valid.';
        }

        return null;
    }

    private function dbError(PDOException $e): string
    {
        error_log(
            'Kesalahan database modul lowongan: '
            . $e->getMessage()
        );

        $sqlState = $e->errorInfo[0] ?? $e->getCode();

        if ($sqlState === '23503') {
            return 'Mitra tidak ditemukan atau sudah tidak aktif.';
        }

        if ($sqlState === '23514') {
            return 'Data tidak memenuhi aturan database. Periksa kuota, tanggal, dan status.';
        }

        return 'Lowongan belum dapat diproses. Silakan coba kembali.';
    }

    /**
     * Daftar lowongan.
     */
    public function index($params = []): void
    {
        $this->authorize();

        $model = new KoordinatorLowongan();

        $lowonganList = $model->getAll();

        $success = (string) (
            $_GET['success'] ?? ''
        );

        require __DIR__
            . '/../../../pages/dashboard/koordinatorMagang/lowongan/index.php';
    }

    /**
     * Form tambah lowongan.
     */
    public function create($params = []): void
    {
        $this->authorize();

        $model = new KoordinatorLowongan();

        $mitraList = $model->getActiveMitraOptions();

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
                $userId = (int) (
                    $_SESSION['user']['id'] ?? 0
                );

                if ($userId < 1) {
                    http_response_code(401);
                    exit('Sesi pengguna tidak valid.');
                }

                try {
                    $model->create($form, $userId);

                    header(
                        'Location: '
                        . url('/dashboard/koordinator-magang/lowongan?success=created')
                    );

                    exit;

                } catch (PDOException $e) {

                    $error = $this->dbError($e);

                } catch (Throwable $e) {

                    error_log(
                        'Gagal membuat lowongan: '
                        . $e->getMessage()
                    );

                    $error = 'Lowongan belum dapat dibuat. Silakan coba kembali.';
                }
            }
        }

        require __DIR__
            . '/../../../pages/dashboard/koordinatorMagang/lowongan/create.php';
    }

    /**
     * Detail lowongan.
     */
    public function detail($params = []): void
    {
        $this->authorize();

        $id = filter_var(
            $params['id'] ?? $_GET['id'] ?? null,
            FILTER_VALIDATE_INT
        );

        if (!$id || $id < 1) {
            http_response_code(400);
            exit('ID lowongan tidak valid.');
        }

        $model = new KoordinatorLowongan();

        $lowongan = $model->findById((int) $id);

        if (!$lowongan) {
            http_response_code(404);
            exit('Data lowongan tidak ditemukan.');
        }

        require __DIR__
            . '/../../../pages/dashboard/koordinatorMagang/lowongan/detail.php';
    }

    /**
     * Edit lowongan.
     */
    public function edit($params = []): void
    {
        $this->authorize();

        $id = filter_var(
            $params['id'] ?? $_GET['id'] ?? null,
            FILTER_VALIDATE_INT
        );

        if (!$id || $id < 1) {
            http_response_code(400);
            exit('ID lowongan tidak valid.');
        }

        $model = new KoordinatorLowongan();

        $lowongan = $model->findById((int) $id);

        if (!$lowongan) {
            http_response_code(404);
            exit('Data lowongan tidak ditemukan.');
        }

        $mitraList = $model->getActiveMitraOptions();

        $form = array_merge(
            $this->emptyForm(),
            $lowongan
        );

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
                    $updated = $model->update(
                        (int) $id,
                        $form
                    );

                    if (!$updated) {
                        throw new RuntimeException(
                            'Lowongan tidak diperbarui. Periksa kembali mitra dan kuota.'
                        );
                    }

                    header(
                        'Location: '
                        . url('/dashboard/koordinator-magang/lowongan?success=updated')
                    );

                    exit;

                } catch (PDOException $e) {

                    $error = $this->dbError($e);

                } catch (Throwable $e) {

                    error_log(
                        'Gagal memperbarui lowongan: '
                        . $e->getMessage()
                    );

                    $error = $e->getMessage();
                }
            }
        }

        require __DIR__
            . '/../../../pages/dashboard/koordinatorMagang/lowongan/edit.php';
    }

    /**
     * Mengubah status lowongan.
     */
    public function updateStatus($params = []): void
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

        $id = filter_var(
            $_POST['lowongan_id'] ?? null,
            FILTER_VALIDATE_INT
        );

        $status = trim(
            (string) ($_POST['status'] ?? '')
        );

        if (
            !$id
            || $id < 1
            || !in_array($status, self::STATUSES, true)
        ) {
            header(
                'Location: '
                . url('/dashboard/koordinator-magang/lowongan?success=0')
            );
            exit;
        }

        try {
            $changed = (new KoordinatorLowongan())
                ->updateStatus((int) $id, $status);

            header(
                'Location: '
                . url('/dashboard/koordinator-magang/lowongan?success='
                    . ($changed ? 'status_updated' : '0'))
            );

            exit;

        } catch (PDOException $e) {

            error_log(
                'Gagal mengubah status lowongan: '
                . $e->getMessage()
            );

            header(
                'Location: '
                . url('/dashboard/koordinator-magang/lowongan?success=0')
            );

            exit;
        }
    }
}
