<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/AktivitasPengguna.php';

class PenempatanMagangCoordinator
{
    public function getAcceptedWithoutPlacement(): array
    {
        global $pdo;
        $stmt = $pdo->query(
            "SELECT p.id AS pendaftaran_id, p.tanggal_pengajuan,
                    f.judul, f.tanggal_mulai AS formasi_mulai,
                    f.tanggal_selesai AS formasi_selesai,
                    m.nama_perusahaan, prof.nim,
                    u.name AS mahasiswa_nama
             FROM pendaftaran_magang p
             INNER JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id
             INNER JOIN users u ON u.id = prof.user_id
             INNER JOIN formasi_magang f ON f.id = p.formasi_id
             INNER JOIN mitra m ON m.id = f.mitra_id
             LEFT JOIN penempatan_magang pm ON pm.pendaftaran_id = p.id
             WHERE p.status_pendaftaran = 'diterima' AND pm.id IS NULL
             ORDER BY p.tanggal_pengajuan ASC, p.id ASC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(int $applicationId, int $actorId, string $startDate, string $endDate): int
    {
        global $pdo;
        $start = $this->parseDate($startDate);
        $end = $this->parseDate($endDate);
        if ($end < $start) {
            throw new InvalidArgumentException('Tanggal selesai tidak boleh lebih awal dari tanggal mulai.');
        }

        $pdo->beginTransaction();
        try {
            $application = $pdo->prepare(
                "SELECT p.id, p.status_pendaftaran, prof.user_id AS mahasiswa_user_id
                 FROM pendaftaran_magang p
                 INNER JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id
                 WHERE p.id = :id
                 FOR UPDATE OF p"
            );
            $application->execute(['id' => $applicationId]);
            $row = $application->fetch(PDO::FETCH_ASSOC);
            if (!$row || $row['status_pendaftaran'] !== 'diterima') {
                throw new RuntimeException('Hanya pengajuan yang diterima mitra dapat dibuatkan penempatan.');
            }

            $existing = $pdo->prepare('SELECT id FROM penempatan_magang WHERE pendaftaran_id = :id LIMIT 1');
            $existing->execute(['id' => $applicationId]);
            if ($existing->fetchColumn() !== false) {
                throw new RuntimeException('Penempatan untuk pengajuan ini sudah dibuat.');
            }

            $insert = $pdo->prepare(
                "INSERT INTO penempatan_magang
                    (pendaftaran_id, dosen_pembimbing_id, tanggal_mulai, tanggal_selesai,
                     status, ditetapkan_oleh, tanggal_penetapan)
                 VALUES (:application_id, NULL, :start_date, :end_date,
                         'persiapan', :actor_id, CURRENT_TIMESTAMP)
                 RETURNING id"
            );
            $insert->execute([
                'application_id' => $applicationId,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'actor_id' => $actorId,
            ]);
            $placementId = (int) $insert->fetchColumn();

            $notification = $pdo->prepare(
                "INSERT INTO notifikasi (user_id, judul, pesan, jenis, referensi_tabel, referensi_id, tautan)
                 VALUES (:user_id, :judul, :pesan, 'pendaftaran', 'penempatan_magang', :reference_id, :tautan)"
            );
            $notification->execute([
                'user_id' => (int) $row['mahasiswa_user_id'],
                'judul' => 'Penempatan magang telah dibuat',
                'pesan' => 'Penempatan magang Anda siap dilengkapi dengan permintaan dosen pembimbing.',
                'reference_id' => $placementId,
                'tautan' => '/dashboard/mahasiswa/pembimbing',
            ]);
            $pdo->commit();

            (new AktivitasPengguna())->log(
                $actorId,
                'Penempatan magang dibuat',
                'Penempatan untuk pengajuan #' . $applicationId . ' berhasil dibuat.',
                'penempatan',
                'penempatan_magang',
                $placementId
            );

            return $placementId;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    private function parseDate(string $value): string
    {
        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);
        if (!$date || $date->format('Y-m-d') !== $value) {
            throw new InvalidArgumentException('Tanggal penempatan tidak valid.');
        }

        return $value;
    }
}
