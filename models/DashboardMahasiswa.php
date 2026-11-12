<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class DashboardMahasiswa
{
    public function getData(int $userId): array
    {
        global $pdo;

        $stmt = $pdo->prepare($this->dashboardQuery());
        $stmt->execute(['user_id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            throw new RuntimeException('Profil mahasiswa tidak ditemukan.');
        }

        $profil = [
            'user_id' => (int) $row['user_id'],
            'nama_lengkap' => $row['nama_lengkap'],
            'mahasiswa_id' => (int) $row['mahasiswa_id'],
            'nim' => $row['nim'],
            'program_studi' => $row['program_studi'],
            'angkatan' => $row['angkatan'],
            'email' => $row['email'],
            'no_telepon' => $row['no_telepon'],
            'alamat' => $row['alamat'],
            'foto_path' => $row['foto_path'],
            'cv_path' => $row['cv_path'],
        ];

        return [
            'profil' => $profil,
            'statistik' => [
                'portofolio' => (int) ($row['stat_portofolio'] ?? 0),
                'sertifikat' => (int) ($row['stat_sertifikat'] ?? 0),
                'pengalaman' => (int) ($row['stat_pengalaman'] ?? 0),
                'pengajuan' => (int) ($row['stat_pengajuan'] ?? 0),
                'logbook' => (int) ($row['stat_logbook'] ?? 0),
            ],
            'pengajuan' => $this->buildPengajuan($row),
            'penempatan' => $this->buildPenempatan($row),
            'progress' => $this->buildProgress($row),
            'aktivitas' => $this->buildAktivitas($row['aktivitas_json'] ?? '[]'),
            'notifikasi_belum_dibaca' => $this->getUnreadNotificationCount($userId),
            'profil_progress' => $this->getProfilProgress($profil),
        ];
    }

    /**
     * Satu query untuk seluruh data dashboard: profil, statistik, pengajuan
     * terbaru, penempatan terbaru, progress, dan aktivitas terakhir.
     */
    private function dashboardQuery(): string
    {
        return <<<'SQL'
            WITH profil AS (
                SELECT u.id AS user_id,
                       u.name AS nama_lengkap,
                       p.id AS mahasiswa_id,
                       p.nim,
                       p.program_studi,
                       p.angkatan,
                       p.email,
                       p.no_telepon,
                       p.alamat,
                       p.foto_path,
                       p.cv_path
                FROM users u
                INNER JOIN profil_mahasiswa p ON p.user_id = u.id
                WHERE u.id = :user_id
                  AND u.is_active = TRUE
                LIMIT 1
            ),
            statistik AS (
                SELECT
                    (SELECT COUNT(*) FROM portofolios po
                     WHERE po.mahasiswa_id = profil.mahasiswa_id) AS portofolio,
                    (SELECT COUNT(*) FROM sertifikat s
                     WHERE s.user_id = profil.user_id) AS sertifikat,
                    (SELECT COUNT(*) FROM pengalaman pe
                     WHERE pe.mahasiswa_id = profil.mahasiswa_id) AS pengalaman,
                    (SELECT COUNT(*) FROM pendaftaran_magang pd
                     WHERE pd.mahasiswa_id = profil.mahasiswa_id) AS pengajuan,
                    (SELECT COUNT(*)
                     FROM logbook_mingguan lm
                     INNER JOIN penempatan_magang pm ON pm.id = lm.penempatan_id
                     INNER JOIN pendaftaran_magang pd ON pd.id = pm.pendaftaran_id
                     WHERE pd.mahasiswa_id = profil.mahasiswa_id) AS logbook
                FROM profil
            ),
            pengajuan_terbaru AS (
                SELECT pd.id, pd.status_pendaftaran, pd.tanggal_pengajuan,
                       f.judul, f.lokasi_magang, f.sistem_kerja,
                       m.nama_perusahaan
                FROM pendaftaran_magang pd
                INNER JOIN formasi_magang f ON f.id = pd.formasi_id
                INNER JOIN mitra m ON m.id = f.mitra_id
                CROSS JOIN profil
                WHERE pd.mahasiswa_id = profil.mahasiswa_id
                ORDER BY pd.tanggal_pengajuan DESC, pd.id DESC
                LIMIT 1
            ),
            penempatan_terbaru AS (
                SELECT pm.id, pm.status, pm.tanggal_mulai, pm.tanggal_selesai,
                       f.judul, m.nama_perusahaan
                FROM penempatan_magang pm
                INNER JOIN pendaftaran_magang pd ON pd.id = pm.pendaftaran_id
                INNER JOIN formasi_magang f ON f.id = pd.formasi_id
                INNER JOIN mitra m ON m.id = f.mitra_id
                CROSS JOIN profil
                WHERE pd.mahasiswa_id = profil.mahasiswa_id
                ORDER BY pm.created_at DESC, pm.id DESC
                LIMIT 1
            ),
            progress AS (
                SELECT pd.status_pendaftaran,
                       pd.status_persetujuan_dosen,
                       pd.status_respons_mitra,
                       pm.status AS status_penempatan
                FROM pendaftaran_magang pd
                LEFT JOIN penempatan_magang pm ON pm.pendaftaran_id = pd.id
                CROSS JOIN profil
                WHERE pd.mahasiswa_id = profil.mahasiswa_id
                ORDER BY pd.tanggal_pengajuan DESC, pd.id DESC
                LIMIT 1
            ),
            aktivitas AS (
                SELECT COALESCE(
                           json_agg(
                               json_build_object(
                                   'id', a.id,
                                   'aksi', a.aksi,
                                   'deskripsi', a.deskripsi,
                                   'modul', a.modul,
                                   'created_at', a.created_at
                               )
                               ORDER BY a.created_at DESC, a.id DESC
                           ),
                           '[]'::json
                       ) AS items
                FROM (
                    SELECT a.id, a.aksi, a.deskripsi, a.modul, a.created_at
                    FROM aktivitas_pengguna a
                    CROSS JOIN profil
                    WHERE a.user_id = profil.user_id
                      AND (a.modul IS NULL OR a.modul <> 'auth')
                    ORDER BY a.created_at DESC, a.id DESC
                    LIMIT 5
                ) a
            )
            SELECT
                profil.user_id,
                profil.nama_lengkap,
                profil.mahasiswa_id,
                profil.nim,
                profil.program_studi,
                profil.angkatan,
                profil.email,
                profil.no_telepon,
                profil.alamat,
                profil.foto_path,
                profil.cv_path,
                statistik.portofolio AS stat_portofolio,
                statistik.sertifikat AS stat_sertifikat,
                statistik.pengalaman AS stat_pengalaman,
                statistik.pengajuan AS stat_pengajuan,
                statistik.logbook AS stat_logbook,
                pengajuan_terbaru.id AS pengajuan_id,
                pengajuan_terbaru.status_pendaftaran AS pengajuan_status,
                pengajuan_terbaru.tanggal_pengajuan AS pengajuan_tanggal,
                pengajuan_terbaru.judul AS pengajuan_judul,
                pengajuan_terbaru.lokasi_magang AS pengajuan_lokasi,
                pengajuan_terbaru.sistem_kerja AS pengajuan_sistem_kerja,
                pengajuan_terbaru.nama_perusahaan AS pengajuan_perusahaan,
                penempatan_terbaru.id AS penempatan_id,
                penempatan_terbaru.status AS penempatan_status,
                penempatan_terbaru.tanggal_mulai AS penempatan_tanggal_mulai,
                penempatan_terbaru.tanggal_selesai AS penempatan_tanggal_selesai,
                penempatan_terbaru.judul AS penempatan_judul,
                penempatan_terbaru.nama_perusahaan AS penempatan_perusahaan,
                progress.status_pendaftaran AS progress_status_pendaftaran,
                progress.status_persetujuan_dosen AS progress_status_dosen,
                progress.status_respons_mitra AS progress_status_mitra,
                progress.status_penempatan AS progress_status_penempatan,
                aktivitas.items AS aktivitas_json
            FROM profil
            CROSS JOIN statistik
            CROSS JOIN aktivitas
            LEFT JOIN pengajuan_terbaru ON TRUE
            LEFT JOIN penempatan_terbaru ON TRUE
            LEFT JOIN progress ON TRUE
            SQL;
    }

    private function buildPengajuan(array $row): ?array
    {
        if (empty($row['pengajuan_id'])) {
            return null;
        }

        $status = (string) ($row['pengajuan_status'] ?? '');

        return [
            'id' => (int) $row['pengajuan_id'],
            'status_pendaftaran' => $status,
            'tanggal_pengajuan' => $row['pengajuan_tanggal'],
            'judul' => $row['pengajuan_judul'],
            'lokasi_magang' => $row['pengajuan_lokasi'],
            'sistem_kerja' => $row['pengajuan_sistem_kerja'],
            'nama_perusahaan' => $row['pengajuan_perusahaan'],
            'status_label' => $this->statusLabel($status),
            'status_class' => $this->statusClass($status),
            'tanggal_label' => $this->formatDate($row['pengajuan_tanggal']),
            'slug' => (string) $row['pengajuan_id'],
        ];
    }

    private function buildPenempatan(array $row): ?array
    {
        if (empty($row['penempatan_id'])) {
            return null;
        }

        $status = (string) ($row['penempatan_status'] ?? '');

        return [
            'id' => (int) $row['penempatan_id'],
            'status' => $status,
            'tanggal_mulai' => $row['penempatan_tanggal_mulai'],
            'tanggal_selesai' => $row['penempatan_tanggal_selesai'],
            'judul' => $row['penempatan_judul'],
            'nama_perusahaan' => $row['penempatan_perusahaan'],
            'status_label' => $this->placementStatusLabel($status),
            'tanggal_mulai_label' => $this->formatDate($row['penempatan_tanggal_mulai']),
            'tanggal_selesai_label' => $this->formatDate($row['penempatan_tanggal_selesai']),
        ];
    }

    private function buildProgress(array $row): array
    {
        $steps = [
            ['key' => 'pengajuan', 'label' => 'Pengajuan'],
            ['key' => 'dosen', 'label' => 'Persetujuan Dosen'],
            ['key' => 'mitra', 'label' => 'Respons Mitra'],
            ['key' => 'penempatan', 'label' => 'Penempatan'],
            ['key' => 'magang', 'label' => 'Pelaksanaan'],
            ['key' => 'selesai', 'label' => 'Selesai'],
        ];

        if (($row['progress_status_pendaftaran'] ?? null) === null) {
            foreach ($steps as &$step) {
                $step['state'] = 'pending';
            }
            unset($step);

            return [
                'has_application' => false,
                'current_label' => 'Belum ada pengajuan aktif',
                'steps' => $steps,
            ];
        }

        $status = (string) $row['progress_status_pendaftaran'];
        $dosen = (string) ($row['progress_status_dosen'] ?? '');
        $mitra = (string) ($row['progress_status_mitra'] ?? '');
        $penempatan = (string) ($row['progress_status_penempatan'] ?? '');

        $completed = match (true) {
            $status === 'selesai' => 6,
            $penempatan === 'selesai' => 6,
            $penempatan === 'berlangsung' || $penempatan === 'menunggu_penilaian' => 5,
            $penempatan === 'persiapan' || $status === 'diterima' => 4,
            $mitra === 'diterima' || $mitra === 'seleksi' || $status === 'menunggu_respons_mitra' || $status === 'seleksi' => 3,
            $dosen === 'disetujui' || $status === 'menunggu_respons_mitra' => 2,
            default => 1,
        };

        foreach ($steps as $index => &$step) {
            $position = $index + 1;
            $step['state'] = $position < $completed
                ? 'completed'
                : ($position === $completed ? 'current' : 'pending');
        }
        unset($step);

        $currentStep = $steps[max(0, $completed - 1)];

        return [
            'has_application' => true,
            'current_label' => $currentStep['label'],
            'steps' => $steps,
        ];
    }

    private function buildAktivitas(string $json): array
    {
        $items = json_decode($json, true);
        if (!is_array($items)) {
            return [];
        }

        return array_map(function (array $row): array {
            $row['waktu_label'] = $this->relativeTime($row['created_at'] ?? null);
            $row['icon'] = $this->activityIcon((string) ($row['modul'] ?? ''));
            return $row;
        }, $items);
    }

    private function getUnreadNotificationCount(int $userId): int
    {
        if (
            isset($GLOBALS['auth_context'])
            && array_key_exists('unread_notifications', $GLOBALS['auth_context'])
        ) {
            return max(0, (int) $GLOBALS['auth_context']['unread_notifications']);
        }

        global $pdo;

        $stmt = $pdo->prepare('SELECT COUNT(*) FROM notifikasi WHERE user_id = :user_id AND sudah_dibaca = FALSE');
        $stmt->execute(['user_id' => $userId]);

        return (int) $stmt->fetchColumn();
    }

    private function getProfilProgress(array $profil): int
    {
        $fields = [
            'nim', 'program_studi', 'angkatan', 'email',
            'no_telepon', 'alamat', 'foto_path', 'cv_path',
        ];

        $filled = 0;
        foreach ($fields as $field) {
            if (isset($profil[$field]) && trim((string) $profil[$field]) !== '') {
                $filled++;
            }
        }

        return (int) round(($filled / count($fields)) * 100);
    }

    private function statusLabel(string $status): string
    {
        return match ($status) {
            'diajukan' => 'Diajukan',
            'menunggu_persetujuan_dosen' => 'Menunggu Persetujuan Dosen',
            'menunggu_respons_mitra' => 'Menunggu Respons Mitra',
            'seleksi' => 'Tahap Seleksi',
            'diterima' => 'Diterima',
            'ditolak_dosen' => 'Ditolak Dosen',
            'ditolak_mitra' => 'Ditolak Mitra',
            'perlu_revisi' => 'Perlu Revisi',
            'dibatalkan' => 'Dibatalkan',
            'selesai' => 'Selesai',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }

    private function statusClass(string $status): string
    {
        return match ($status) {
            'diterima', 'selesai' => 'text-emerald-700 bg-emerald-50',
            'ditolak_dosen', 'ditolak_mitra', 'dibatalkan' => 'text-red-700 bg-red-50',
            'perlu_revisi' => 'text-amber-700 bg-amber-50',
            default => 'text-blue-700 bg-blue-50',
        };
    }

    private function placementStatusLabel(string $status): string
    {
        return match ($status) {
            'persiapan' => 'Persiapan',
            'berlangsung' => 'Sedang Berlangsung',
            'menunggu_penilaian' => 'Menunggu Penilaian',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }

    private function formatDate(?string $date): string
    {
        if (!$date) {
            return '-';
        }

        $timestamp = strtotime($date);
        return $timestamp ? date('d M Y', $timestamp) : '-';
    }

    private function relativeTime(?string $date): string
    {
        if (!$date) {
            return '-';
        }

        $timestamp = strtotime($date);
        if (!$timestamp) {
            return '-';
        }

        $diff = max(0, time() - $timestamp);
        if ($diff < 60) return 'Baru saja';
        if ($diff < 3600) return floor($diff / 60) . ' menit lalu';
        if ($diff < 86400) return floor($diff / 3600) . ' jam lalu';
        if ($diff < 604800) return floor($diff / 86400) . ' hari lalu';

        return date('d M Y', $timestamp);
    }

    private function activityIcon(string $module): string
    {
        return match ($module) {
            'portofolio' => 'briefcase',
            'sertifikat' => 'award',
            'pengajuan', 'pendaftaran' => 'document',
            'logbook' => 'calendar',
            'profil' => 'user',
            default => 'activity',
        };
    }
}
