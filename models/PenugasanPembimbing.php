<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/AktivitasPengguna.php';

final class PenugasanPembimbing
{
    public static function actorCanManage(string $role): bool
    {
        return in_array($role, ['tendik', 'koordinator_magang'], true);
    }

    public function getEligibleStudents(): array
    {
        global $pdo;
        $stmt = $pdo->query(
            "SELECT prof.id AS mahasiswa_id, prof.nim, u.name AS mahasiswa_nama
             FROM pendaftaran_magang p
             INNER JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id
             INNER JOIN users u ON u.id = prof.user_id
             LEFT JOIN proses_magang pr
                ON pr.mahasiswa_id = prof.id AND pr.status = 'aktif'
             LEFT JOIN penugasan_pembimbing pp
                ON pp.proses_magang_id = pr.id AND pp.status = 'aktif'
             WHERE p.status_pendaftaran = 'diterima'
               AND p.dosen_pembimbing_snapshot_id IS NULL
               AND u.is_active = TRUE
               AND (
                    p.proses_magang_id IS NULL
                    OR (p.proses_magang_id = pr.id AND pp.id IS NULL)
               )
             ORDER BY u.name, prof.id"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCandidates(): array
    {
        global $pdo;
        $stmt = $pdo->query(
            "SELECT pr.id AS proses_magang_id, pr.status AS status_proses,
                    prof.id AS mahasiswa_id, prof.nim, u.name AS mahasiswa_nama,
                    assignment.id AS penugasan_id,
                    assignment.dosen_pembimbing_id, assignment.alasan,
                    assignment.ditetapkan_pada, advisor.name AS dosen_nama,
                    advisor_profile.nidn
             FROM proses_magang pr
             INNER JOIN profil_mahasiswa prof ON prof.id = pr.mahasiswa_id
             INNER JOIN users u ON u.id = prof.user_id
             INNER JOIN pendaftaran_magang p
                ON p.proses_magang_id = pr.id AND p.status_pendaftaran = 'diterima'
             LEFT JOIN LATERAL (
                 SELECT pp.* FROM penugasan_pembimbing pp
                 WHERE pp.proses_magang_id = pr.id AND pp.status = 'aktif'
                 ORDER BY pp.ditetapkan_pada DESC, pp.id DESC LIMIT 1
             ) assignment ON TRUE
             LEFT JOIN profil_dosen advisor_profile ON advisor_profile.id = assignment.dosen_pembimbing_id
             LEFT JOIN users advisor ON advisor.id = advisor_profile.user_id
             WHERE pr.status = 'aktif'
             ORDER BY (assignment.id IS NULL), u.name, pr.id"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDosenOptions(): array
    {
        global $pdo;
        $stmt = $pdo->query("SELECT pd.id, u.name, pd.nidn FROM profil_dosen pd INNER JOIN users u ON u.id = pd.user_id WHERE u.role IN ('dosen', 'koordinator_magang') AND u.is_active = TRUE ORDER BY u.name, pd.id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHistory(int $processId): array
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT pp.id, pp.dosen_pembimbing_id, pp.status, pp.alasan, pp.ditetapkan_pada, pp.berakhir_pada, u.name AS dosen_nama, actor.name AS actor_nama FROM penugasan_pembimbing pp INNER JOIN profil_dosen pd ON pd.id = pp.dosen_pembimbing_id INNER JOIN users u ON u.id = pd.user_id INNER JOIN users actor ON actor.id = pp.ditetapkan_oleh WHERE pp.proses_magang_id = :process_id ORDER BY pp.ditetapkan_pada DESC, pp.id DESC");
        $stmt->execute(['process_id' => $processId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createProcess(int $studentId, int $actorId): int
    {
        global $pdo;
        if ($studentId < 1 || $actorId < 1) throw new InvalidArgumentException('Mahasiswa dan actor tidak valid.');
        $pdo->beginTransaction();
        try {
            $application = $pdo->prepare(
                "SELECT id, proses_magang_id
                 FROM pendaftaran_magang
                 WHERE mahasiswa_id = :student_id AND status_pendaftaran = 'diterima'
                 ORDER BY tanggal_pengajuan DESC, id DESC
                 LIMIT 1
                 FOR UPDATE"
            );
            $application->execute(['student_id' => $studentId]);
            $acceptedApplication = $application->fetch(PDO::FETCH_ASSOC);
            if (!$acceptedApplication) {
                throw new RuntimeException('Hanya pengajuan yang telah diterima Mitra dapat diproses untuk penetapan dosen.');
            }

            if ($acceptedApplication['proses_magang_id'] !== null) {
                $processId = (int) $acceptedApplication['proses_magang_id'];
                $process = $pdo->prepare(
                    "SELECT id FROM proses_magang
                     WHERE id = :process_id AND mahasiswa_id = :student_id AND status = 'aktif'
                     FOR UPDATE"
                );
                $process->execute(['process_id' => $processId, 'student_id' => $studentId]);
                if ($process->fetchColumn() === false) {
                    throw new RuntimeException('Proses magang terkait pengajuan tidak aktif.');
                }
            } else {
                $existingProcess = $pdo->prepare(
                    "SELECT id FROM proses_magang
                     WHERE mahasiswa_id = :student_id AND status = 'aktif'
                     FOR UPDATE"
                );
                $existingProcess->execute(['student_id' => $studentId]);
                $processId = (int) $existingProcess->fetchColumn();
                if ($processId < 1) {
                    $insert = $pdo->prepare("INSERT INTO proses_magang (mahasiswa_id, status, dibuat_oleh) VALUES (:student_id, 'aktif', :actor_id) RETURNING id");
                    $insert->execute(['student_id' => $studentId, 'actor_id' => $actorId]);
                    $processId = (int) $insert->fetchColumn();
                }
            }

            $assignment = $pdo->prepare(
                "SELECT pp.id AS assignment_id, pp.dosen_pembimbing_id,
                        u.name AS advisor_name, pd.nidn
                 FROM penugasan_pembimbing pp
                 INNER JOIN profil_dosen pd ON pd.id = pp.dosen_pembimbing_id
                 INNER JOIN users u ON u.id = pd.user_id
                 WHERE pp.proses_magang_id = :process_id AND pp.status = 'aktif'
                 FOR UPDATE OF pp"
            );
            $assignment->execute(['process_id' => $processId]);
            $currentAssignment = $assignment->fetch(PDO::FETCH_ASSOC) ?: null;
            $link = $pdo->prepare(
                "UPDATE pendaftaran_magang
                 SET proses_magang_id = :process_id,
                     penugasan_pembimbing_id = :assignment_id,
                     dosen_pembimbing_snapshot_id = :advisor_id,
                     dosen_pembimbing_snapshot_nama = :advisor_name,
                     dosen_pembimbing_snapshot_nidn = :advisor_nidn,
                     updated_at = CURRENT_TIMESTAMP
                 WHERE id = :application_id AND status_pendaftaran = 'diterima'"
            );
            $link->execute([
                'process_id' => $processId,
                'assignment_id' => $currentAssignment['assignment_id'] ?? null,
                'advisor_id' => $currentAssignment['dosen_pembimbing_id'] ?? null,
                'advisor_name' => $currentAssignment['advisor_name'] ?? null,
                'advisor_nidn' => $currentAssignment['nidn'] ?? null,
                'application_id' => (int) $acceptedApplication['id'],
            ]);
            if ($link->rowCount() !== 1) {
                throw new RuntimeException('Pengajuan berubah saat proses magang disiapkan.');
            }
            $pdo->commit();
            return $processId;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            throw $e;
        }
    }

    public function assign(int $processId, int $advisorId, int $actorId, string $reason): void
    {
        $this->mutate($processId, $advisorId, $actorId, $reason, false);
    }

    public function replace(int $processId, int $advisorId, int $actorId, string $reason): void
    {
        $this->mutate($processId, $advisorId, $actorId, $reason, true);
    }

    private function mutate(int $processId, int $advisorId, int $actorId, string $reason, bool $replacement): void
    {
        global $pdo;
        $reason = trim($reason);
        if ($processId < 1 || $advisorId < 1 || $actorId < 1 || $reason === '' || mb_strlen($reason) > 5000) throw new InvalidArgumentException('Proses, dosen, actor, dan alasan wajib valid.');
        $pdo->beginTransaction();
        try {
            $advisor = $pdo->prepare("SELECT pd.id, pd.nidn, u.name FROM profil_dosen pd INNER JOIN users u ON u.id = pd.user_id WHERE pd.id = :advisor_id AND u.role = 'dosen' AND u.is_active = TRUE");
            $advisor->execute(['advisor_id' => $advisorId]);
            $advisorData = $advisor->fetch(PDO::FETCH_ASSOC);
            if (!$advisorData) throw new InvalidArgumentException('Dosen pembimbing tidak tersedia.');
            $process = $pdo->prepare("SELECT id FROM proses_magang WHERE id = :process_id AND status = 'aktif' FOR UPDATE");
            $process->execute(['process_id' => $processId]);
            if ($process->fetchColumn() === false) throw new RuntimeException('Proses magang tidak aktif.');
            $application = $pdo->prepare(
                "SELECT id FROM pendaftaran_magang
                 WHERE proses_magang_id = :process_id AND status_pendaftaran = 'diterima'
                 FOR UPDATE"
            );
            $application->execute(['process_id' => $processId]);
            $applicationId = $application->fetchColumn();
            if ($applicationId === false) {
                throw new RuntimeException('Dosen hanya dapat ditetapkan setelah pengajuan diterima Mitra.');
            }
            $current = $pdo->prepare("SELECT id FROM penugasan_pembimbing WHERE proses_magang_id = :process_id AND status = 'aktif' FOR UPDATE");
            $current->execute(['process_id' => $processId]);
            $currentId = $current->fetchColumn();
            if ($replacement && $currentId === false) throw new RuntimeException('Belum ada penugasan pembimbing yang dapat diganti.');
            if (!$replacement && $currentId !== false) throw new RuntimeException('Proses ini sudah memiliki dosen pembimbing. Gunakan aksi ganti dengan alasan.');
            if ($currentId !== false) {
                $close = $pdo->prepare("UPDATE penugasan_pembimbing SET status = 'diganti', berakhir_pada = CURRENT_TIMESTAMP WHERE id = :id AND status = 'aktif'");
                $close->execute(['id' => $currentId]);
                if ($close->rowCount() !== 1) throw new RuntimeException('Penugasan berubah saat diproses pengguna lain.');
            }
            $insert = $pdo->prepare("INSERT INTO penugasan_pembimbing (proses_magang_id, dosen_pembimbing_id, ditetapkan_oleh, alasan, status) VALUES (:process_id, :advisor_id, :actor_id, :reason, 'aktif')");
            $insert->execute(['process_id' => $processId, 'advisor_id' => $advisorId, 'actor_id' => $actorId, 'reason' => $reason]);
            $snapshot = $pdo->prepare(
                "UPDATE pendaftaran_magang
                 SET penugasan_pembimbing_id = (
                         SELECT id FROM penugasan_pembimbing
                         WHERE proses_magang_id = :process_id AND status = 'aktif'
                         ORDER BY ditetapkan_pada DESC, id DESC LIMIT 1
                     ),
                     dosen_pembimbing_snapshot_id = :advisor_id,
                     dosen_pembimbing_snapshot_nama = :advisor_name,
                     dosen_pembimbing_snapshot_nidn = :advisor_nidn,
                     updated_at = CURRENT_TIMESTAMP
                 WHERE id = :application_id AND status_pendaftaran = 'diterima'"
            );
            $snapshot->execute([
                'process_id' => $processId,
                'advisor_id' => $advisorId,
                'advisor_name' => (string) $advisorData['name'],
                'advisor_nidn' => $advisorData['nidn'] !== null ? (string) $advisorData['nidn'] : null,
                'application_id' => (int) $applicationId,
            ]);
            if ($snapshot->rowCount() !== 1) {
                throw new RuntimeException('Snapshot dosen pengajuan gagal diperbarui.');
            }
            $pdo->commit();
            (new AktivitasPengguna())->log($actorId, $replacement ? 'Dosen pembimbing diganti' : 'Dosen pembimbing ditetapkan', 'Penugasan pembimbing untuk proses #' . $processId . ' diperbarui.', 'penugasan', 'proses_magang', $processId);
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            throw $e;
        }
    }
}
