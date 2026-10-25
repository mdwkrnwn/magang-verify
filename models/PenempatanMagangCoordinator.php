<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/AktivitasPengguna.php';

class PenempatanMagangCoordinator
{
    public function getPlacementsForAssignment(): array
    {
        global $pdo;
        $stmt = $pdo->query(
            "SELECT pm.id AS penempatan_id, pm.dosen_pembimbing_id,
                    pm.status AS status_penempatan, pm.tanggal_mulai, pm.tanggal_selesai,
                    p.id AS pendaftaran_id, p.status_pendaftaran,
                    prof.nim, mahasiswa.name AS mahasiswa_nama,
                    f.judul, m.nama_perusahaan,
                    pd.nidn, dosen.name AS dosen_nama
             FROM penempatan_magang pm
             INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id
             INNER JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id
             INNER JOIN users mahasiswa ON mahasiswa.id = prof.user_id
             INNER JOIN formasi_magang f ON f.id = p.formasi_id
             INNER JOIN mitra m ON m.id = f.mitra_id
             LEFT JOIN profil_dosen pd ON pd.id = pm.dosen_pembimbing_id
             LEFT JOIN users dosen ON dosen.id = pd.user_id
             WHERE p.status_pendaftaran = 'diterima'
               AND pm.status IN ('persiapan', 'berlangsung')
             ORDER BY (pm.dosen_pembimbing_id IS NOT NULL), pm.tanggal_mulai ASC, pm.id ASC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDosenOptions(): array
    {
        global $pdo;
        $stmt = $pdo->query(
            "SELECT pd.id, u.name, pd.nidn
             FROM profil_dosen pd
             INNER JOIN users u ON u.id = pd.user_id
             WHERE u.role = 'dosen' AND u.is_active = TRUE
             ORDER BY u.name, pd.id"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function assignAdvisor(int $placementId, int $advisorProfileId, int $actorId): void
    {
        throw new RuntimeException('Penetapan pembimbing melalui penempatan sudah tidak tersedia. Gunakan penugasan pada proses magang.');

        global $pdo;
        if ($placementId < 1 || $advisorProfileId < 1 || $actorId < 1) {
            throw new InvalidArgumentException('Data penetapan dosen tidak valid.');
        }

        $pdo->beginTransaction();
        try {
            $advisor = $pdo->prepare(
                "SELECT pd.id FROM profil_dosen pd
                 INNER JOIN users u ON u.id = pd.user_id
                 WHERE pd.id = :advisor_id AND u.role = 'dosen' AND u.is_active = TRUE"
            );
            $advisor->execute(['advisor_id' => $advisorProfileId]);
            if ($advisor->fetchColumn() === false) {
                throw new InvalidArgumentException('Dosen pembimbing tidak tersedia.');
            }

            $placement = $pdo->prepare(
                "SELECT pm.id, prof.user_id AS mahasiswa_user_id
                 FROM penempatan_magang pm
                 INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id
                 INNER JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id
                 WHERE pm.id = :placement_id
                   AND p.status_pendaftaran = 'diterima'
                   AND pm.status IN ('persiapan', 'berlangsung')
                   AND pm.dosen_pembimbing_id IS NULL
                 FOR UPDATE OF pm"
            );
            $placement->execute(['placement_id' => $placementId]);
            $row = $placement->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                throw new RuntimeException('Penempatan tidak ditemukan, tidak aktif, atau sudah memiliki pembimbing.');
            }

            $update = $pdo->prepare(
                "UPDATE penempatan_magang
                 SET dosen_pembimbing_id = :advisor_id,
                     ditetapkan_oleh = :actor_id,
                     tanggal_penetapan = CURRENT_TIMESTAMP,
                     updated_at = CURRENT_TIMESTAMP
                 WHERE id = :placement_id AND dosen_pembimbing_id IS NULL"
            );
            $update->execute([
                'advisor_id' => $advisorProfileId,
                'actor_id' => $actorId,
                'placement_id' => $placementId,
            ]);
            if ($update->rowCount() !== 1) {
                throw new RuntimeException('Penempatan sudah diproses oleh pengguna lain.');
            }

            $notification = $pdo->prepare(
                "INSERT INTO notifikasi (user_id, judul, pesan, jenis, referensi_tabel, referensi_id, tautan)
                 VALUES (:user_id, :judul, :pesan, 'pendaftaran', 'penempatan_magang', :reference_id, :tautan)"
            );
            $notification->execute([
                'user_id' => (int) $row['mahasiswa_user_id'],
                'judul' => 'Dosen pembimbing telah ditetapkan',
                'pesan' => 'Pengelola telah menetapkan dosen pembimbing untuk penempatan magang Anda.',
                'reference_id' => $placementId,
                'tautan' => '/dashboard/mahasiswa/pembimbing',
            ]);
            $pdo->commit();

            (new AktivitasPengguna())->log(
                $actorId,
                'Dosen pembimbing ditetapkan',
                'Dosen pembimbing untuk penempatan #' . $placementId . ' berhasil ditetapkan.',
                'penempatan',
                'penempatan_magang',
                $placementId
            );
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

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
                "SELECT p.id, p.status_pendaftaran, prof.user_id AS mahasiswa_user_id,
                        p.dosen_pembimbing_snapshot_id
                 FROM pendaftaran_magang p
                 INNER JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id
                 WHERE p.id = :id
                 FOR UPDATE OF p"
            );
            $application->execute(['id' => $applicationId]);
            $row = $application->fetch(PDO::FETCH_ASSOC);
            if (!$row || $row['status_pendaftaran'] !== 'diterima'
                || $row['dosen_pembimbing_snapshot_id'] === null) {
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
                 VALUES (:application_id, :advisor_id, :start_date, :end_date,
                         'persiapan', :actor_id, CURRENT_TIMESTAMP)
                 RETURNING id"
            );
            $insert->execute([
                'application_id' => $applicationId,
                'advisor_id' => (int) $row['dosen_pembimbing_snapshot_id'],
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
