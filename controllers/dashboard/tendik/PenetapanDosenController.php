<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../function/Helpers.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/PenugasanPembimbing.php';

final class TendikPenetapanDosenController
{
    public function index($params = []): void
    {
        AuthMiddleware::handleAny(['tendik', 'koordinator_magang']);
        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        if ($userId < 1) {
            http_response_code(401);
            exit('Sesi pengguna tidak valid.');
        }

        $model = new PenugasanPembimbing();
        $eligibleStudents = $model->getEligibleStudents();
        $candidates = $model->getCandidates();
        $dosenOptions = $model->getDosenOptions();
        $error = null;
        $success = ($_GET['success'] ?? '') === '1';

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            if (!verifyCsrfToken()) {
                http_response_code(403);
                exit('Permintaan tidak valid. Silakan muat ulang halaman.');
            }
            $processId = filter_var($_POST['proses_magang_id'] ?? null, FILTER_VALIDATE_INT);
            $studentId = filter_var($_POST['mahasiswa_id'] ?? null, FILTER_VALIDATE_INT);
            $dosenId = filter_var($_POST['dosen_id'] ?? null, FILTER_VALIDATE_INT);
            $mode = (string) ($_POST['mode'] ?? 'assign');
            $reason = trim((string) ($_POST['alasan'] ?? ''));
            if ($mode === 'create_process') {
                if (!$studentId) $error = 'Mahasiswa wajib dipilih.';
                else {
                    try { $model->createProcess((int) $studentId, $userId); header('Location: ' . url('/dashboard/tendik/penetapan-dosen?success=1')); exit; }
                    catch (PDOException $e) { error_log('Gagal membuat proses magang: ' . $e->getMessage()); $error = 'Proses magang belum dapat dibuat.'; }
                    catch (Throwable $e) { $error = $e->getMessage(); }
                }
            } elseif (!$processId || !$dosenId || !in_array($mode, ['assign', 'replace'], true)) {
                $error = 'Proses, dosen pembimbing, dan aksi penetapan wajib dipilih.';
            } elseif ($reason === '' || mb_strlen($reason) > 5000) {
                $error = 'Alasan wajib diisi dan maksimal 5.000 karakter.';
            } else {
                try {
                    if ($mode === 'replace') $model->replace((int) $processId, (int) $dosenId, $userId, $reason);
                    else $model->assign((int) $processId, (int) $dosenId, $userId, $reason);
                    header('Location: ' . url('/dashboard/tendik/penetapan-dosen?success=1'));
                    exit;
                } catch (PDOException $e) {
                    error_log('Gagal menyimpan penugasan pembimbing: ' . $e->getMessage());
                    $error = 'Penugasan belum dapat disimpan. Silakan coba lagi.';
                } catch (Throwable $e) {
                    $error = $e->getMessage();
                }
            }
            $candidates = $model->getCandidates();
            $eligibleStudents = $model->getEligibleStudents();
            $dosenOptions = $model->getDosenOptions();
        }

        require __DIR__ . '/../../../pages/dashboard/tendik/penetapan-dosen/index.php';
    }
}
