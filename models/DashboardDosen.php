<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class DashboardDosen
{
    public function getBimbingan(int $userId): array
    {
        global $pdo;

        $stmt = $pdo->prepare(
            "SELECT pm.id AS penempatan_id, pm.status AS status_penempatan,
                    pm.tanggal_mulai, pm.tanggal_selesai,
                    p.id AS pendaftaran_id, prof.id AS mahasiswa_id,
                    prof.nim, prof.program_studi, u.name AS mahasiswa_nama,
                    f.judul, m.nama_perusahaan
             FROM penempatan_magang pm
             INNER JOIN profil_dosen pd ON pd.id = pm.dosen_pembimbing_id
             INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id
             INNER JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id
             INNER JOIN users u ON u.id = prof.user_id
             INNER JOIN formasi_magang f ON f.id = p.formasi_id
             INNER JOIN mitra m ON m.id = f.mitra_id
             WHERE pd.user_id = :user_id
             ORDER BY CASE pm.status WHEN 'berlangsung' THEN 0 WHEN 'persiapan' THEN 1 ELSE 2 END,
                      pm.tanggal_mulai DESC, pm.id DESC"
        );
        $stmt->execute(['user_id' => $userId]);
        $placements = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($placements as &$placement) {
            $placement['status_label'] = match ($placement['status_penempatan']) {
                'berlangsung' => 'Sedang Magang',
                'persiapan' => 'Persiapan',
                'menunggu_penilaian' => 'Menunggu Penilaian',
                'selesai' => 'Selesai',
                'dibatalkan' => 'Dibatalkan',
                default => ucfirst(str_replace('_', ' ', (string) $placement['status_penempatan'])),
            };
        }
        unset($placement);

        return $placements;
    }

    public function getPlacementForLecturer(int $userId, int $placementId): ?array
    {
        global $pdo;

        $stmt = $pdo->prepare(
            "SELECT pm.id AS penempatan_id, pm.status AS status_penempatan,
                    pm.tanggal_mulai, pm.tanggal_selesai,
                    prof.id AS mahasiswa_id, prof.nim, prof.program_studi,
                    u.name AS mahasiswa_nama, f.judul, m.nama_perusahaan,
                    m.alamat AS lokasi_perusahaan
             FROM penempatan_magang pm
             INNER JOIN profil_dosen pd ON pd.id = pm.dosen_pembimbing_id
             INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id
             INNER JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id
             INNER JOIN users u ON u.id = prof.user_id
             INNER JOIN formasi_magang f ON f.id = p.formasi_id
             INNER JOIN mitra m ON m.id = f.mitra_id
             WHERE pd.user_id = :user_id AND pm.id = :placement_id
             LIMIT 1"
        );
        $stmt->execute(['user_id' => $userId, 'placement_id' => $placementId]);
        $placement = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$placement) {
            return null;
        }

        $placement['status_label'] = match ($placement['status_penempatan']) {
            'berlangsung' => 'Sedang Magang',
            'persiapan' => 'Persiapan',
            'menunggu_penilaian' => 'Menunggu Penilaian',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default => ucfirst(str_replace('_', ' ', (string) $placement['status_penempatan'])),
        };

        return $placement;
    }

    public function getBimbinganStatistics(array $placements): array
    {
        $students = [];
        $counts = ['berlangsung' => 0, 'persiapan' => 0, 'menunggu_penilaian' => 0, 'selesai' => 0];

        foreach ($placements as $placement) {
            $students[(int) $placement['mahasiswa_id']] = true;
            $status = (string) $placement['status_penempatan'];
            if ($status === 'berlangsung') {
                $counts['berlangsung']++;
            } elseif ($status === 'persiapan') {
                $counts['persiapan']++;
            } elseif ($status === 'menunggu_penilaian') {
                $counts['menunggu_penilaian']++;
            } elseif ($status === 'selesai') {
                $counts['selesai']++;
            }
        }

        return [
            'mahasiswa' => count($students),
            'berlangsung' => $counts['berlangsung'],
            'persiapan' => $counts['persiapan'],
            'menunggu_penilaian' => $counts['menunggu_penilaian'],
            'selesai' => $counts['selesai'],
        ];
    }
}
