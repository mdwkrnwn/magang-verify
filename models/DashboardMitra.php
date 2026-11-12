<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class DashboardMitra
{
    public function getData(int $userId): array
    {
        global $pdo;

        $stmt = $pdo->prepare(
            "WITH mitra_ctx AS (
                SELECT m.id, m.nama_perusahaan
                FROM mitra m
                INNER JOIN users u ON u.id = m.user_id
                WHERE u.id = :user_id AND u.role = 'mitra' AND u.is_active = TRUE
                LIMIT 1
             )
             SELECT mc.id, mc.nama_perusahaan,
                    (SELECT COUNT(*) FROM formasi_magang f
                     WHERE f.mitra_id = mc.id) AS formasi,
                    (SELECT COUNT(*) FROM pendaftaran_magang p
                     INNER JOIN formasi_magang f ON f.id = p.formasi_id
                     WHERE f.mitra_id = mc.id
                       AND p.status_pendaftaran IN ('menunggu_respons_mitra', 'seleksi')) AS pelamar,
                    (SELECT COUNT(*) FROM penempatan_magang pm
                     INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id
                     INNER JOIN formasi_magang f ON f.id = p.formasi_id
                     WHERE f.mitra_id = mc.id
                       AND pm.status IN ('persiapan', 'berlangsung', 'menunggu_penilaian')) AS penempatan,
                    (SELECT COUNT(*) FROM logbook_mingguan lm
                     INNER JOIN penempatan_magang pm ON pm.id = lm.penempatan_id
                     INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id
                     INNER JOIN formasi_magang f ON f.id = p.formasi_id
                     WHERE f.mitra_id = mc.id AND lm.status = 'menunggu_mitra') AS menunggu_logbook,
                    (SELECT COALESCE(
                                json_agg(
                                    json_build_object(
                                        'penempatan_id', s.penempatan_id,
                                        'status', s.status,
                                        'tanggal_mulai', s.tanggal_mulai,
                                        'tanggal_selesai', s.tanggal_selesai,
                                        'mahasiswa_nama', s.mahasiswa_nama,
                                        'nim', s.nim,
                                        'judul', s.judul
                                    )
                                    ORDER BY s.status_priority, s.tanggal_mulai DESC, s.penempatan_id DESC
                                ),
                                '[]'::json
                            )
                     FROM (
                         SELECT pm.id AS penempatan_id, pm.status, pm.tanggal_mulai, pm.tanggal_selesai,
                                u.name AS mahasiswa_nama, prof.nim, f.judul,
                                CASE pm.status WHEN 'berlangsung' THEN 0 WHEN 'persiapan' THEN 1 ELSE 2 END AS status_priority
                         FROM penempatan_magang pm
                         INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id
                         INNER JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id
                         INNER JOIN users u ON u.id = prof.user_id
                         INNER JOIN formasi_magang f ON f.id = p.formasi_id
                         WHERE f.mitra_id = mc.id
                         ORDER BY status_priority, pm.tanggal_mulai DESC, pm.id DESC
                         LIMIT 8
                     ) s) AS students_json
             FROM mitra_ctx mc"
        );
        $stmt->execute(['user_id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return [
                'company' => null,
                'statistics' => ['formasi' => 0, 'pelamar' => 0, 'penempatan' => 0, 'menunggu_logbook' => 0],
                'students' => [],
            ];
        }

        $students = json_decode((string) ($row['students_json'] ?? '[]'), true);

        return [
            'company' => [
                'id' => (int) $row['id'],
                'nama_perusahaan' => $row['nama_perusahaan'],
            ],
            'statistics' => [
                'formasi' => (int) ($row['formasi'] ?? 0),
                'pelamar' => (int) ($row['pelamar'] ?? 0),
                'penempatan' => (int) ($row['penempatan'] ?? 0),
                'menunggu_logbook' => (int) ($row['menunggu_logbook'] ?? 0),
            ],
            'students' => is_array($students) ? $students : [],
        ];
    }
}
