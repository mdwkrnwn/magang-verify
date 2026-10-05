<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../config/app.php';
require_once __DIR__ . '/../../../function/Helpers.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../models/Logbook.php';

class LogbookController
{
    private Logbook $model;

    public function __construct()
    {
        $this->model = new Logbook();
    }

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

    private function redirect(string $path): never
    {
        header('Location: ' . url($path));
        exit;
    }

    private function validatePost(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST' || !verifyCsrfToken()) {
            http_response_code(403);
            exit('Permintaan tidak valid.');
        }
    }

    private function dailyData(): array
    {
        $status = (string) ($_POST['status_kehadiran'] ?? 'hadir');
        return [
            'tanggal' => trim((string) ($_POST['tanggal'] ?? '')),
            'jam_masuk' => trim((string) ($_POST['jam_masuk'] ?? '')),
            'jam_pulang' => trim((string) ($_POST['jam_pulang'] ?? '')),
            'kegiatan' => trim((string) ($_POST['kegiatan'] ?? '')),
            'status_kehadiran' => $status,
            'alasan_ketidakhadiran' => trim((string) ($_POST['alasan_ketidakhadiran'] ?? '')),
        ];
    }

    public function index($params = []): void
    {
        $userId = $this->userId();
        $placementId = isset($_GET['penempatan']) && ctype_digit((string) $_GET['penempatan'])
            ? (int) $_GET['penempatan']
            : null;

        $placements = $this->model->getPlacements($userId);
        $placement = $this->model->getPlacement($userId, $placementId);
        $summary = $placement ? $this->model->getSummary($userId, (int) $placement['id']) : ['total' => 0, 'disetujui' => 0, 'menunggu' => 0, 'revisi' => 0];
        $weeks = $placement ? $this->model->getWeeks((int) $placement['id']) : [];
        $nextWeek = $placement ? $this->model->getNextWeekPlan($placement) : null;

        require __DIR__ . '/../../../pages/dashboard/mahasiswa/logbook/index.php';
    }

    public function create($params = []): void
    {
        $userId = $this->userId();
        $placementId = isset($_GET['penempatan']) && ctype_digit((string) $_GET['penempatan'])
            ? (int) $_GET['penempatan']
            : null;
        $placement = $this->model->getPlacement($userId, $placementId);
        if (!$placement) {
            http_response_code(409);
            exit('Penempatan magang belum tersedia.');
        }

        $nextWeek = $this->model->getNextWeekPlan($placement);
        $error = null;

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $this->validatePost();
            try {
                $id = $this->model->createWeek($userId, (int) $placement['id']);
                $this->redirect('/dashboard/mahasiswa/logbook/detail/' . $id);
            } catch (Throwable $e) {
                $error = $e->getMessage();
            }
        }

        require __DIR__ . '/../../../pages/dashboard/mahasiswa/logbook/form.php';
    }

    public function detail($params = []): void
    {
        $userId = $this->userId();
        $id = (int) ($params['id'] ?? 0);
        $week = $this->model->getWeek($id, $userId);
        if (!$week) {
            http_response_code(404);
            exit('Minggu logbook tidak ditemukan.');
        }

        require __DIR__ . '/../../../pages/dashboard/mahasiswa/logbook/detail.php';
    }

    public function dailyCreate($params = []): void
    {
        $userId = $this->userId();
        $weekId = (int) ($params['id'] ?? 0);
        $week = $this->model->getWeek($weekId, $userId);
        if (!$week) {
            http_response_code(404);
            exit('Minggu logbook tidak ditemukan.');
        }

        $item = [
            'tanggal' => (isset($_GET['tanggal']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $_GET['tanggal'])) ? (string) $_GET['tanggal'] : '',
            'jam_masuk' => '',
            'jam_pulang' => '',
            'kegiatan' => '',
            'status_kehadiran' => 'hadir',
            'alasan_ketidakhadiran' => '',
            'bukti_path' => null,
        ];
        $mode = 'create';
        $error = null;

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $this->validatePost();
            try {
                $this->model->createDaily($weekId, $userId, $this->dailyData(), $_FILES['bukti'] ?? null);
                $this->redirect('/dashboard/mahasiswa/logbook/detail/' . $weekId);
            } catch (Throwable $e) {
                $error = $e->getMessage();
                $item = array_merge($item, $_POST);
            }
        }

        require __DIR__ . '/../../../pages/dashboard/mahasiswa/logbook/daily-form.php';
    }

    public function dailyEdit($params = []): void
    {
        $userId = $this->userId();
        $dailyId = (int) ($params['id'] ?? 0);
        $item = $this->model->getDailyForStudent($dailyId, $userId);
        if (!$item) {
            http_response_code(404);
            exit('Logbook harian tidak ditemukan.');
        }

        $weekId = (int) $item['logbook_id'];
        $week = $this->model->getWeek($weekId, $userId);
        if (!$week) {
            http_response_code(404);
            exit('Minggu logbook tidak ditemukan.');
        }

        $mode = 'edit';
        $error = null;

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $this->validatePost();
            try {
                $this->model->updateDaily($dailyId, $userId, $this->dailyData(), $_FILES['bukti'] ?? null);
                $this->redirect('/dashboard/mahasiswa/logbook/detail/' . $weekId);
            } catch (Throwable $e) {
                $error = $e->getMessage();
                $item = array_merge($item, $_POST);
            }
        }

        require __DIR__ . '/../../../pages/dashboard/mahasiswa/logbook/daily-form.php';
    }

    public function sign($params = []): void
    {
        $userId = $this->userId();
        $this->validatePost();

        try {
            $this->model->signWeek(
                (int) ($params['id'] ?? 0),
                $userId,
                'mahasiswa',
                trim((string) ($_POST['signature_data'] ?? ''))
            );
            $this->redirect('/dashboard/mahasiswa/logbook/detail/' . (int) $params['id']);
        } catch (Throwable $e) {
            http_response_code(422);
            exit(e($e->getMessage()));
        }
    }

    public function downloadWeek($params = []): void
    {
        $userId = $this->userId();
        $week = $this->model->getWeek((int) ($params['id'] ?? 0), $userId);
        if (!$week) {
            http_response_code(404);
            exit('Minggu logbook tidak ditemukan.');
        }
        $this->renderPdf([$week], 'logbook-minggu-' . $week['minggu_ke']);
    }

    public function downloadAll($params = []): void
    {
        $userId = $this->userId();
        $placementId = (int) ($params['id'] ?? 0);
        $placement = $this->model->getPlacement($userId, $placementId);
        if (!$placement) {
            http_response_code(404);
            exit('Penempatan magang tidak ditemukan.');
        }

        $weeks = [];
        foreach ($this->model->getWeeks($placementId) as $row) {
            $week = $this->model->getWeek((int) $row['id'], $userId);
            if ($week) {
                $weeks[] = $week;
            }
        }

        if (!$weeks) {
            http_response_code(404);
            exit('Belum ada logbook yang dapat diunduh.');
        }

        $this->renderPdf($weeks, 'logbook-' . preg_replace('/[^a-z0-9]+/i', '-', (string) $placement['nama_perusahaan']));
    }

    private function renderPdf(array $weeks, string $filename): never
    {
        $autoload = dirname(__DIR__, 3) . '/vendor/autoload.php';
        if (!is_file($autoload)) {
            http_response_code(500);
            exit('Dompdf belum tersedia.');
        }
        require_once $autoload;

        $html = '<!doctype html><html><head><meta charset="utf-8"><style>
            @page{margin:12mm}body{font-family:DejaVu Sans, sans-serif;color:#1f2937;font-size:10px}
            .page{page-break-after:always;page-break-inside:avoid}.page:last-child{page-break-after:auto}
            h1{text-align:center;font-size:15px;margin:0 0 2px}.sub{text-align:center;font-size:11px;margin-bottom:12px}
            table{width:100%;border-collapse:collapse;margin-top:8px}th,td{border:1px solid #9ca3af;padding:6px;vertical-align:top}th{background:#e5e7eb}
            .meta td{border:1px solid #d1d5db}.meta .label{width:24%;font-weight:bold;background:#f9fafb}
            .sign{margin-top:25px;width:100%;border-collapse:collapse}.sign td{border:0;text-align:center;width:33%}.signature{height:55px;max-width:130px;object-fit:contain}
            .small{font-size:8px;color:#6b7280}.badge{font-weight:bold}
        </style></head><body>';

        foreach ($weeks as $week) {
            $html .= '<section class="page">';
            $html .= '<h1>LOG BOOK KEGIATAN</h1><div class="sub">PROGRAM MAGANG INDUSTRI</div>';
            $html .= '<table class="meta"><tr><td class="label">Nama</td><td>' . e($week['mahasiswa_nama']) . '</td></tr>';
            $html .= '<tr><td class="label">NIM</td><td>' . e($week['nim']) . '</td></tr>';
            $html .= '<tr><td class="label">Program Studi</td><td>' . e($week['program_studi'] ?: '-') . '</td></tr>';
            $html .= '<tr><td class="label">Nama Mitra Industri</td><td>' . e($week['nama_perusahaan']) . '</td></tr>';
            $html .= '<tr><td class="label">Minggu</td><td>' . e((string) $week['minggu_ke']) . ' (' . e(date('d M Y', strtotime($week['tanggal_mulai']))) . ' - ' . e(date('d M Y', strtotime($week['tanggal_selesai']))) . ')</td></tr></table>';
            $html .= '<table><thead><tr><th style="width:18%">Hari, Tanggal</th><th style="width:14%">Jam Masuk</th><th style="width:14%">Jam Pulang</th><th>Kegiatan</th></tr></thead><tbody>';
            foreach ($week['daily'] as $day) {
                $activity = e($day['kegiatan']);
                if ($day['status_kehadiran'] === 'tidak_hadir') {
                    $activity .= '<br><span class="small">Ketidakhadiran: ' . e((string) $day['alasan_ketidakhadiran']) . '</span>';
                }
                $html .= '<tr><td>' . e(date('D, d M Y', strtotime($day['tanggal']))) . '</td><td>' . e($day['jam_masuk'] ?: '-') . '</td><td>' . e($day['jam_pulang'] ?: '-') . '</td><td>' . $activity . '</td></tr>';
            }
            $html .= '</tbody></table>';
            $html .= '<table class="sign"><tr><td>Mahasiswa</td><td>Pembimbing Lapangan</td><td>Dosen Pembimbing</td></tr><tr>';
            foreach (['mahasiswa', 'mitra', 'dosen'] as $stage) {
                $signature = $week['signatures'][$stage]['signature_path'] ?? null;
                if ($signature) {
                    $path = $this->model->getSignatureAbsolutePath((string) $signature);
                    if ($path) {
                        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($path);
                        $html .= '<td><img class="signature" src="data:' . e($mime) . ';base64,' . base64_encode((string) file_get_contents($path)) . '"></td>';
                        continue;
                    }
                }
                $html .= '<td><div style="height:55px">-</div></td>';
            }
            $html .= '</tr><tr>';
            foreach (['mahasiswa', 'mitra', 'dosen'] as $stage) {
                $name = $week['signatures'][$stage]['penanda_tangan_nama'] ?? 'Belum ditandatangani';
                $html .= '<td>' . e($name) . '</td>';
            }
            $html .= '</tr></table>';
            $html .= '<p class="small">Versi dokumen: ' . e((string) $week['versi_terkini']) . ' · Status: ' . e($week['status_label']) . '</p>';
            $html .= '</section>';
        }

        $html .= '</body></html>';

        $dompdf = new \Dompdf\Dompdf(['isRemoteEnabled' => false]);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $safe = preg_replace('/[^a-zA-Z0-9_-]+/', '-', $filename) ?: 'logbook';
        $dompdf->stream($safe . '.pdf', ['Attachment' => true]);
        exit;
    }
}
