<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class DashboardMahasiswa
{
    public function getData(int $userId): array
    {
        global $pdo;

        $profil = $this->getProfil($userId);
        if (!$profil) {
            throw new RuntimeException('Profil mahasiswa tidak ditemukan.');
        }

        $mahasiswaId = (int) $profil['mahasiswa_id'];

        return [
            'profil' => $profil,
            'statistik' => $this->getStatistik($userId, $mahasiswaId),
            'pengajuan' => $this->getPengajuanTerbaru($mahasiswaId),
            'penempatan' => $this->getPenempatanTerbaru($mahasiswaId),
            'progress' => $this->getProgress($mahasiswaId),
            'aktivitas' => $this->getAktivitas($userId),
            'notifikasi_belum_dibaca' => $this->getUnreadNotificationCount($userId),
            'profil_progress' => $this->getProfilProgress($profil),
        ];
    }

    private function getProfil(int $userId): ?array
    {
        global $pdo;

        $stmt = $pdo->prepare(''
            . 'SELECT '
            . 'u.id AS user_id, u.name AS nama_lengkap, '
            . 'p.id AS mahasiswa_id, p.nim, p.program_studi, p.angkatan, '
            . 'p.email, p.no_telepon, p.alamat, p.foto_path, p.cv_path '
            . 'FROM users u '
            . 'INNER JOIN profil_mahasiswa p ON p.user_id = u.id '
            . 'WHERE u.id = :user_id AND u.role = \'mahasiswa\' AND u.is_active = TRUE '
            . 'LIMIT 1'
        );
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    private function getStatistik(int $userId, int $mahasiswaId): array
    {
        global $pdo;

        $stmt = $pdo->prepare(''
            . 'SELECT '
            . '(SELECT COUNT(*) FROM portofolios WHERE mahasiswa_id = :mahasiswa_id_1) AS portofolio, '
            . '(SELECT COUNT(*) FROM sertifikat WHERE user_id = :user_id) AS sertifikat, '
            . '(SELECT COUNT(*) FROM pengalaman WHERE mahasiswa_id = :mahasiswa_id_2) AS pengalaman, '
            . '(SELECT COUNT(*) FROM pendaftaran_magang WHERE mahasiswa_id = :mahasiswa_id_3) AS pengajuan, '
            . '(SELECT COUNT(*) '
            . ' FROM logbook_mingguan lm '
            . ' INNER JOIN penempatan_magang pm ON pm.id = lm.penempatan_id '
            . ' INNER JOIN pendaftaran_magang pg ON pg.id = pm.pendaftaran_id '
            . ' WHERE pg.mahasiswa_id = :mahasiswa_id_4) AS logbook'
        );
        $stmt->execute([
            'user_id' => $userId,
            'mahasiswa_id_1' => $mahasiswaId,
            'mahasiswa_id_2' => $mahasiswaId,
            'mahasiswa_id_3' => $mahasiswaId,
            'mahasiswa_id_4' => $mahasiswaId,
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

        return [
            'portofolio' => (int) ($row['portofolio'] ?? 0),
            'sertifikat' => (int) ($row['sertifikat'] ?? 0),
            'pengalaman' => (int) ($row['pengalaman'] ?? 0),
            'pengajuan' => (int) ($row['pengajuan'] ?? 0),
            'logbook' => (int) ($row['logbook'] ?? 0),
        ];
    }

    private function getPengajuanTerbaru(int $mahasiswaId): ?array
    {
        global $pdo;

        $stmt = $pdo->prepare(''
            . 'SELECT '
            . 'p.id, p.status_pendaftaran, p.tanggal_pengajuan, '
            . 'f.judul, f.lokasi_magang, f.sistem_kerja, '
            . 'm.nama_perusahaan '
            . 'FROM pendaftaran_magang p '
            . 'INNER JOIN formasi_magang f ON f.id = p.formasi_id '
            . 'INNER JOIN mitra m ON m.id = f.mitra_id '
            . 'WHERE p.mahasiswa_id = :mahasiswa_id '
            . 'ORDER BY p.tanggal_pengajuan DESC, p.id DESC '
            . 'LIMIT 1'
        );
        $stmt->execute(['mahasiswa_id' => $mahasiswaId]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        $row['status_label'] = $this->statusLabel((string) $row['status_pendaftaran']);
        $row['status_class'] = $this->statusClass((string) $row['status_pendaftaran']);
        $row['tanggal_label'] = $this->formatDate($row['tanggal_pengajuan']);
        $row['slug'] = (string) $row['id'];

        return $row;
    }

    private function getPenempatanTerbaru(int $mahasiswaId): ?array
    {
        global $pdo;

        $stmt = $pdo->prepare(''
            . 'SELECT '
            . 'pm.id, pm.status, pm.tanggal_mulai, pm.tanggal_selesai, '
            . 'f.judul, m.nama_perusahaan '
            . 'FROM penempatan_magang pm '
            . 'INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id '
            . 'INNER JOIN formasi_magang f ON f.id = p.formasi_id '
            . 'INNER JOIN mitra m ON m.id = f.mitra_id '
            . 'WHERE p.mahasiswa_id = :mahasiswa_id '
            . 'ORDER BY pm.created_at DESC, pm.id DESC '
            . 'LIMIT 1'
        );
        $stmt->execute(['mahasiswa_id' => $mahasiswaId]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        $row['status_label'] = $this->placementStatusLabel((string) $row['status']);
        $row['tanggal_mulai_label'] = $this->formatDate($row['tanggal_mulai']);
        $row['tanggal_selesai_label'] = $this->formatDate($row['tanggal_selesai']);

        return $row;
    }

    private function getProgress(int $mahasiswaId): array
    {
        global $pdo;

        $stmt = $pdo->prepare(''
            . 'SELECT '
            . 'p.status_pendaftaran, '
            . 'p.status_persetujuan_dosen, '
            . 'p.status_respons_mitra, '
            . 'pm.status AS status_penempatan '
            . 'FROM pendaftaran_magang p '
            . 'LEFT JOIN penempatan_magang pm ON pm.pendaftaran_id = p.id '
            . 'WHERE p.mahasiswa_id = :mahasiswa_id '
            . 'ORDER BY p.tanggal_pengajuan DESC, p.id DESC '
            . 'LIMIT 1'
        );
        $stmt->execute(['mahasiswa_id' => $mahasiswaId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $steps = [
            ['key' => 'pengajuan', 'label' => 'Pengajuan'],
            ['key' => 'dosen', 'label' => 'Persetujuan Dosen'],
            ['key' => 'mitra', 'label' => 'Respons Mitra'],
            ['key' => 'penempatan', 'label' => 'Penempatan'],
            ['key' => 'magang', 'label' => 'Pelaksanaan'],
            ['key' => 'selesai', 'label' => 'Selesai'],
        ];

        if (!$row) {
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

        $status = (string) $row['status_pendaftaran'];
        $dosen = (string) $row['status_persetujuan_dosen'];
        $mitra = (string) $row['status_respons_mitra'];
        $penempatan = (string) ($row['status_penempatan'] ?? '');

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

    private function getAktivitas(int $userId): array
    {
        global $pdo;

        $stmt = $pdo->prepare(''
            . 'SELECT id, aksi, deskripsi, modul, created_at '
            . 'FROM aktivitas_pengguna '
            . 'WHERE user_id = :user_id '
            . 'AND (modul IS NULL OR modul <> \'auth\') '
            . 'ORDER BY created_at DESC, id DESC '
            . 'LIMIT 5'
        );
        $stmt->execute(['user_id' => $userId]);

        return array_map(function (array $row): array {
            $row['waktu_label'] = $this->relativeTime($row['created_at']);
            $row['icon'] = $this->activityIcon((string) ($row['modul'] ?? ''));
            return $row;
        }, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    private function getUnreadNotificationCount(int $userId): int
    {
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
