<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../function/Helpers.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/GenerateLoginMitra.php';

final class GenerateLoginMitraController
{
    private function authorize(): void
    {
        AuthMiddleware::handleAny(['tendik', 'koordinator_magang']);
    }


    public function index($params = []): void
    {
        $this->authorize();

        $model = new GenerateLoginMitra();

        $mitraList = $model->getAllActiveMitra();

        // Ambil link yang sudah ada agar dapat disalin kembali
        foreach ($mitraList as &$mitra) {
            $token = $model->getExistingToken((int) $mitra['id']);

            $mitra['access_link'] = $token
                ? url('/mitra/akses/' . $token)
                : null;
        }

        unset($mitra);

        $error = null;
        $success = null;
        $generatedLink = null;
        $generatedMitraName = null;

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {

            if (!verifyCsrfToken()) {
                http_response_code(403);
                exit('Permintaan tidak valid. Silakan muat ulang halaman.');
            }

            $action = (string) ($_POST['action'] ?? '');

            $mitraId = filter_var(
                $_POST['mitra_id'] ?? null,
                FILTER_VALIDATE_INT
            );

            if (!$mitraId || $mitraId < 1) {
                $error = 'Mitra tidak valid.';
            } else {
                try {

                    if ($action === 'generate') {

                        $userId = (int) ($_SESSION['user']['id'] ?? 0);

                        if ($userId < 1) {
                            throw new RuntimeException(
                                'Sesi pengguna tidak valid.'
                            );
                        }

                        // Generate link permanen baru
                        // Link sebelumnya otomatis dicabut oleh model
                        $token = $model->generateToken(
                            (int) $mitraId,
                            $userId
                        );

                        $generatedLink = url(
                            '/mitra/akses/' . $token
                        );

                        foreach ($mitraList as $mitra) {
                            if ((int) $mitra['id'] === (int) $mitraId) {
                                $generatedMitraName =
                                    $mitra['nama_perusahaan'];
                                break;
                            }
                        }

                        $success = 'Link akses permanen berhasil dibuat.';
                    } elseif ($action === 'revoke') {

                        $changed = $model->revokeAccess(
                            (int) $mitraId
                        );

                        $success = $changed
                            ? 'Link akses berhasil dicabut.'
                            : 'Tidak ada link aktif yang perlu dicabut.';
                    } else {

                        $error = 'Aksi tidak valid.';
                    }
                } catch (Throwable $e) {

                    error_log(
                        'Generate Login Mitra Error: ' .
                            $e->getMessage()
                    );

                    $error = 'Permintaan belum dapat diproses. Silakan coba kembali.';
                }
            }

            // Refresh daftar mitra dan link setelah proses POST
            $mitraList = $model->getAllActiveMitra();

            foreach ($mitraList as &$mitra) {
                $token = $model->getExistingToken((int) $mitra['id']);

                $mitra['access_link'] = $token
                    ? url('/mitra/akses/' . $token)
                    : null;
            }

            unset($mitra);
        }

        require __DIR__ .
            '/../../../pages/dashboard/koordinatorMagang/generate-login/index.php';
    }
}
