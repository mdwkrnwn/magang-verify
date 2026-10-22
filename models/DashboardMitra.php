<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class DashboardMitra
{
    public function getData(int $userId): array
    {
        global $pdo;
        $stmt = $pdo->prepare(
            "SELECT m.id, m.nama_perusahaan
             FROM mitra m
             INNER JOIN users u ON u.id = m.user_id
             WHERE u.id = :user_id AND u.role = 'mitra' AND u.is_active = TRUE
             LIMIT 1"
        );
        $stmt->execute(['user_id' => $userId]);
        $company = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$company) {
            return ['company' => null, 'statistics' => ['formasi' => 0, 'pelamar' => 0, 'penempatan' => 0, 'menunggu_logbook' => 0], 'students' => []];
        }

        $stats = $pdo->prepare(
            "SELECT
                (SELECT COUNT(*) FROM formasi_magang f WHERE f.mitra_id = :mitra_id_1) AS formasi,
                (SELECT COUNT(*) FROM pendaftaran_magang p
                 INNER JOIN formasi_magang f ON f.id = p.formasi_id
                 WHERE f.mitra_id = :mitra_id_2
                   AND p.status_pendaftaran IN ('menunggu_respons_mitra', 'seleksi')) AS pelamar,
                (SELECT COUNT(*) FROM penempatan_magang pm
                 INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id
                 INNER JOIN formasi_magang f ON f.id = p.formasi_id
                 WHERE f.mitra_id = :mitra_id_3
                   AND pm.status IN ('persiapan', 'berlangsung', 'menunggu_penilaian')) AS penempatan,
                (SELECT COUNT(*) FROM logbook_mingguan lm
                 INNER JOIN penempatan_magang pm ON pm.id = lm.penempatan_id
                 INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id
                 INNER JOIN formasi_magang f ON f.id = p.formasi_id
                 WHERE f.mitra_id = :mitra_id_4 AND lm.status = 'menunggu_mitra') AS menunggu_logbook"
        );
        $stats->execute([
            'mitra_id_1' => (int) $company['id'],
            'mitra_id_2' => (int) $company['id'],
            'mitra_id_3' => (int) $company['id'],
            'mitra_id_4' => (int) $company['id'],
        ]);

        $students = $pdo->prepare(
            "SELECT pm.id AS penempatan_id, pm.status, pm.tanggal_mulai, pm.tanggal_selesai,
                    u.name AS mahasiswa_nama, prof.nim, f.judul
             FROM penempatan_magang pm
             INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id
             INNER JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id
             INNER JOIN users u ON u.id = prof.user_id
             INNER JOIN formasi_magang f ON f.id = p.formasi_id
             WHERE f.mitra_id = :mitra_id
             ORDER BY CASE pm.status WHEN 'berlangsung' THEN 0 WHEN 'persiapan' THEN 1 ELSE 2 END,
                      pm.tanggal_mulai DESC, pm.id DESC
             LIMIT 8"
        );
        $students->execute(['mitra_id' => (int) $company['id']]);

        return [
            'company' => $company,
            'statistics' => array_map('intval', $stats->fetch(PDO::FETCH_ASSOC) ?: []),
            'students' => $students->fetchAll(PDO::FETCH_ASSOC),
        ];
    }
}
