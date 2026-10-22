<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/AktivitasPengguna.php';

class PermintaanPembimbing
{
    public function getStudentPlacements(int $userId): array
    {
        global $pdo;
        $stmt = $pdo->prepare(
            "SELECT pm.id AS penempatan_id, pm.status AS status_penempatan,
                    pm.tanggal_mulai, pm.tanggal_selesai, pm.dosen_pembimbing_id,
                    p.status_pendaftaran,
                    f.judul, m.nama_perusahaan,
                    latest.id AS permintaan_id,
                    latest.status_permintaan,
                    latest.alasan_pengajuan,
                    latest.catatan_dosen,
                    latest.tanggal_pengajuan,
                    latest.tanggal_respons,
                    du.name AS dosen_nama
             FROM penempatan_magang pm
             INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id
             INNER JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id
             INNER JOIN formasi_magang f ON f.id = p.formasi_id
             INNER JOIN mitra m ON m.id = f.mitra_id
             LEFT JOIN LATERAL (
                 SELECT pp.*
                 FROM permintaan_pembimbing pp
                 WHERE pp.penempatan_id = pm.id
                 ORDER BY pp.tanggal_pengajuan DESC, pp.id DESC
                 LIMIT 1
             ) latest ON TRUE
             LEFT JOIN profil_dosen pd ON pd.id = latest.dosen_id
             LEFT JOIN users du ON du.id = pd.user_id
             WHERE prof.user_id = :user_id
             ORDER BY pm.tanggal_mulai DESC, pm.id DESC"
        );
        $stmt->execute(['user_id' => $userId]);

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
             ORDER BY u.name"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStudentHistory(int $userId): array
    {
        global $pdo;
        $stmt = $pdo->prepare(
            "SELECT pp.id, pp.penempatan_id, pp.dosen_id, pp.alasan_pengajuan,
                    pp.status_permintaan, pp.catatan_dosen, pp.tanggal_pengajuan,
                    pp.tanggal_respons, u.name AS dosen_nama,
                    f.judul, m.nama_perusahaan
             FROM permintaan_pembimbing pp
             INNER JOIN penempatan_magang pm ON pm.id = pp.penempatan_id
             INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id
             INNER JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id
             INNER JOIN profil_dosen pd ON pd.id = pp.dosen_id
             INNER JOIN users u ON u.id = pd.user_id
             INNER JOIN formasi_magang f ON f.id = p.formasi_id
             INNER JOIN mitra m ON m.id = f.mitra_id
             WHERE prof.user_id = :user_id
             ORDER BY pp.tanggal_pengajuan DESC, pp.id DESC"
        );
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPendingForDosen(int $userId): array
    {
        global $pdo;
        $stmt = $pdo->prepare(
            "SELECT pp.id, pp.penempatan_id, pp.alasan_pengajuan, pp.tanggal_pengajuan,
                    pd.id AS dosen_id,
                    pm.status AS status_penempatan,
                    pm.dosen_pembimbing_id,
                    prof.nim, prof.program_studi,
                    mahasiswa.name AS mahasiswa_nama,
                    f.judul, m.nama_perusahaan
             FROM permintaan_pembimbing pp
             INNER JOIN profil_dosen pd ON pd.id = pp.dosen_id
             INNER JOIN users dosen ON dosen.id = pd.user_id
             INNER JOIN penempatan_magang pm ON pm.id = pp.penempatan_id
             INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id
             INNER JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id
             INNER JOIN users mahasiswa ON mahasiswa.id = prof.user_id
             INNER JOIN formasi_magang f ON f.id = p.formasi_id
             INNER JOIN mitra m ON m.id = f.mitra_id
             WHERE dosen.id = :user_id AND pp.status_permintaan = 'menunggu'
             ORDER BY pp.tanggal_pengajuan ASC, pp.id ASC"
        );
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(int $userId, int $placementId, int $dosenId, string $reason): int
    {
        global $pdo;
        $reason = trim($reason);
        if ($reason === '' || mb_strlen($reason) > 5000) {
            throw new InvalidArgumentException('Alasan pengajuan wajib diisi (maksimal 5.000 karakter).');
        }

        $pdo->beginTransaction();
        try {
            $placementStmt = $pdo->prepare(
                "SELECT pm.id, pm.status, pm.dosen_pembimbing_id, p.status_pendaftaran
                 FROM penempatan_magang pm
                 INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id
                 INNER JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id
                 WHERE pm.id = :placement_id AND prof.user_id = :user_id
                 FOR UPDATE OF pm, p"
            );
            $placementStmt->execute(['placement_id' => $placementId, 'user_id' => $userId]);
            $placement = $placementStmt->fetch(PDO::FETCH_ASSOC);
            if (!$placement) {
                throw new RuntimeException('Penempatan magang tidak ditemukan atau bukan milik Anda.');
            }
            if ($placement['status_pendaftaran'] !== 'diterima'
                || !in_array($placement['status'], ['persiapan', 'berlangsung'], true)) {
                throw new RuntimeException('Permintaan pembimbing hanya dapat diajukan untuk penempatan magang yang telah diterima dan belum selesai.');
            }
            if ($placement['dosen_pembimbing_id'] !== null) {
                throw new RuntimeException('Penempatan ini sudah memiliki dosen pembimbing.');
            }

            $dosenStmt = $pdo->prepare(
                "SELECT pd.id, pd.user_id
                 FROM profil_dosen pd
                 INNER JOIN users u ON u.id = pd.user_id
                 WHERE pd.id = :dosen_id AND u.role = 'dosen' AND u.is_active = TRUE"
            );
            $dosenStmt->execute(['dosen_id' => $dosenId]);
            $dosen = $dosenStmt->fetch(PDO::FETCH_ASSOC);
            if (!$dosen) {
                throw new InvalidArgumentException('Dosen yang dipilih tidak tersedia.');
            }

            $pendingStmt = $pdo->prepare(
                "SELECT id FROM permintaan_pembimbing
                 WHERE penempatan_id = :placement_id AND status_permintaan = 'menunggu'
                 LIMIT 1"
            );
            $pendingStmt->execute(['placement_id' => $placementId]);
            if ($pendingStmt->fetchColumn() !== false) {
                throw new RuntimeException('Penempatan ini sudah memiliki permintaan pembimbing yang menunggu keputusan.');
            }

            $insert = $pdo->prepare(
                "INSERT INTO permintaan_pembimbing
                    (penempatan_id, dosen_id, alasan_pengajuan, status_permintaan)
                 VALUES (:placement_id, :dosen_id, :reason, 'menunggu')
                 RETURNING id"
            );
            $insert->execute([
                'placement_id' => $placementId,
                'dosen_id' => $dosenId,
                'reason' => $reason,
            ]);
            $requestId = (int) $insert->fetchColumn();

            $notification = $pdo->prepare(
                "INSERT INTO notifikasi (user_id, judul, pesan, jenis, referensi_tabel, referensi_id, tautan)
                 VALUES (:user_id, :judul, :pesan, 'persetujuan', 'permintaan_pembimbing', :reference_id, :tautan)"
            );
            $notification->execute([
                'user_id' => (int) $dosen['user_id'],
                'judul' => 'Permintaan dosen pembimbing baru',
                'pesan' => 'Mahasiswa mengajukan Anda sebagai dosen pembimbing magang.',
                'reference_id' => $requestId,
                'tautan' => '/dashboard/dosen/permintaan-pembimbing',
            ]);
            $pdo->commit();

            (new AktivitasPengguna())->log(
                $userId,
                'Permintaan dosen pembimbing diajukan',
                'Permintaan pembimbing untuk penempatan #' . $placementId . ' berhasil dikirim.',
                'permintaan_pembimbing',
                'permintaan_pembimbing',
                $requestId
            );

            return $requestId;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public function respond(int $userId, int $requestId, string $decision, ?string $note): void
    {
        global $pdo;
        if (!in_array($decision, ['disetujui', 'ditolak'], true)) {
            throw new InvalidArgumentException('Keputusan permintaan tidak valid.');
        }
        $note = $note !== null ? trim($note) : null;
        if ($note !== null && mb_strlen($note) > 5000) {
            throw new InvalidArgumentException('Catatan keputusan maksimal 5.000 karakter.');
        }

        $pdo->beginTransaction();
        try {
            $requestStmt = $pdo->prepare(
                "SELECT pp.id, pp.penempatan_id, pp.status_permintaan,
                        pp.dosen_id, pd.user_id AS dosen_user_id,
                        mahasiswa.user_id AS mahasiswa_user_id
                 FROM permintaan_pembimbing pp
                 INNER JOIN profil_dosen pd ON pd.id = pp.dosen_id
                 INNER JOIN penempatan_magang pm ON pm.id = pp.penempatan_id
                 INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id
                 INNER JOIN profil_mahasiswa mahasiswa ON mahasiswa.id = p.mahasiswa_id
                 WHERE pp.id = :request_id
                 FOR UPDATE OF pp"
            );
            $requestStmt->execute(['request_id' => $requestId]);
            $request = $requestStmt->fetch(PDO::FETCH_ASSOC);
            if (!$request || (int) $request['dosen_user_id'] !== $userId) {
                throw new RuntimeException('Permintaan tidak ditemukan atau bukan ditujukan kepada Anda.');
            }
            if ($request['status_permintaan'] !== 'menunggu') {
                throw new RuntimeException('Permintaan ini sudah diproses.');
            }

            $placementStmt = $pdo->prepare(
                "SELECT pm.dosen_pembimbing_id, pm.status, p.status_pendaftaran
                 FROM penempatan_magang pm
                 INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id
                 WHERE pm.id = :id
                 FOR UPDATE OF pm, p"
            );
            $placementStmt->execute(['id' => (int) $request['penempatan_id']]);
            $currentAdvisor = $placementStmt->fetch(PDO::FETCH_ASSOC);
            if ($currentAdvisor === false) {
                throw new RuntimeException('Penempatan magang tidak ditemukan.');
            }
            if ($decision === 'disetujui') {
                if ($currentAdvisor['dosen_pembimbing_id'] !== null) {
                    throw new RuntimeException('Penempatan sudah memiliki dosen pembimbing dan tidak dapat menerima penetapan lain.');
                }
                if ($currentAdvisor['status_pendaftaran'] !== 'diterima'
                    || !in_array($currentAdvisor['status'], ['persiapan', 'berlangsung'], true)) {
                    throw new RuntimeException('Penempatan tidak lagi memenuhi syarat untuk menerima pembimbing.');
                }
            }

            if ($decision === 'disetujui') {
                $assign = $pdo->prepare(
                    'UPDATE penempatan_magang SET dosen_pembimbing_id = :dosen_id, updated_at = CURRENT_TIMESTAMP WHERE id = :placement_id'
                );
                $assign->execute([
                    'dosen_id' => (int) $request['dosen_id'],
                    'placement_id' => (int) $request['penempatan_id'],
                ]);
            }

            $update = $pdo->prepare(
                'UPDATE permintaan_pembimbing
                 SET status_permintaan = :decision,
                     catatan_dosen = :note,
                     tanggal_respons = CURRENT_TIMESTAMP,
                     updated_at = CURRENT_TIMESTAMP
                 WHERE id = :id AND status_permintaan = \'menunggu\''
            );
            $update->execute(['decision' => $decision, 'note' => $note, 'id' => $requestId]);
            if ($update->rowCount() !== 1) {
                throw new RuntimeException('Permintaan sudah diproses oleh pengguna lain.');
            }

            $notification = $pdo->prepare(
                "INSERT INTO notifikasi (user_id, judul, pesan, jenis, referensi_tabel, referensi_id, tautan)
                 VALUES (:user_id, :judul, :pesan, 'persetujuan', 'permintaan_pembimbing', :reference_id, :tautan)"
            );
            $notification->execute([
                'user_id' => (int) $request['mahasiswa_user_id'],
                'judul' => 'Permintaan dosen pembimbing diproses',
                'pesan' => 'Dosen telah memberikan keputusan ' . $decision . ' atas permintaan pembimbing Anda.',
                'reference_id' => $requestId,
                'tautan' => '/dashboard/mahasiswa/pembimbing',
            ]);
            $pdo->commit();

            (new AktivitasPengguna())->log(
                $userId,
                'Permintaan dosen pembimbing diproses',
                'Permintaan #' . $requestId . ' diputuskan ' . $decision . '.',
                'permintaan_pembimbing',
                'permintaan_pembimbing',
                $requestId
            );
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }
}
