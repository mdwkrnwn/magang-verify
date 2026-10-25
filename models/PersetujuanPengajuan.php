<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/AktivitasPengguna.php';
require_once __DIR__ . '/PengajuanDecision.php';

class PersetujuanPengajuan
{
    public function getStatistics(int $dosenUserId): array
    {
        global $pdo;
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) FILTER (
                        WHERE (assigned_u.id = :pending_user_1
                               AND p.status_pendaftaran = 'menunggu_persetujuan_dosen')
                           OR p.dosen_penyetuju_id = :user_id_1
                    ) AS total,
                    COUNT(*) FILTER (
                        WHERE assigned_u.id = :pending_user_2
                          AND p.status_pendaftaran = 'menunggu_persetujuan_dosen'
                    ) AS menunggu,
                    COUNT(*) FILTER (
                        WHERE p.dosen_penyetuju_id = :user_id_2
                          AND p.status_persetujuan_dosen = 'disetujui'
                    ) AS disetujui,
                    COUNT(*) FILTER (
                        WHERE p.dosen_penyetuju_id = :user_id_3
                          AND p.status_persetujuan_dosen = 'perlu_revisi'
                    ) AS perlu_revisi
             FROM pendaftaran_magang p
             LEFT JOIN profil_dosen assigned_pd ON assigned_pd.id = p.dosen_pembimbing_snapshot_id
             LEFT JOIN users assigned_u ON assigned_u.id = assigned_pd.user_id"
        );
        $stmt->execute([
            'pending_user_1' => $dosenUserId,
            'pending_user_2' => $dosenUserId,
            'user_id_1' => $dosenUserId,
            'user_id_2' => $dosenUserId,
            'user_id_3' => $dosenUserId,
        ]);

        return array_map('intval', $stmt->fetch(PDO::FETCH_ASSOC) ?: []);
    }

    public function getPending(int $dosenUserId): array
    {
        global $pdo;
        $stmt = $pdo->prepare(
            "SELECT p.id, p.tanggal_pengajuan, p.status_pendaftaran,
                    p.status_persetujuan_dosen, p.status_respons_mitra,
                    p.catatan_dosen, prof.nim, prof.program_studi,
                    u.name AS mahasiswa_nama, f.judul, f.tanggal_mulai,
                    f.tanggal_selesai, f.jumlah_kuota, f.jumlah_diterima,
                    m.nama_perusahaan, p.dosen_pembimbing_snapshot_nama,
                    p.dosen_pembimbing_snapshot_nidn,
                    docs.dokumen
             FROM pendaftaran_magang p
             INNER JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id
             INNER JOIN users u ON u.id = prof.user_id
             INNER JOIN formasi_magang f ON f.id = p.formasi_id
             INNER JOIN mitra m ON m.id = f.mitra_id
             LEFT JOIN LATERAL (
                 SELECT json_agg(json_build_object(
                     'id', d.id, 'jenis', d.jenis_dokumen, 'nama', d.nama_asli
                 ) ORDER BY d.id) AS dokumen
                 FROM dokumen_pendaftaran_magang d
                 WHERE d.pendaftaran_id = p.id
             ) docs ON TRUE
             INNER JOIN profil_dosen assigned_pd ON assigned_pd.id = p.dosen_pembimbing_snapshot_id
             INNER JOIN users assigned_u ON assigned_u.id = assigned_pd.user_id
             WHERE assigned_u.id = :dosen_user_id
               AND p.status_pendaftaran = 'menunggu_persetujuan_dosen'
               AND p.status_persetujuan_dosen = 'menunggu'
               AND assigned_u.is_active = TRUE
             ORDER BY p.tanggal_pengajuan ASC, p.id ASC"
        );
        $stmt->execute(['dosen_user_id' => $dosenUserId]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($items as &$item) {
            $item['dokumen'] = is_string($item['dokumen'] ?? null)
                ? (json_decode($item['dokumen'], true) ?: [])
                : [];
        }
        unset($item);

        return $items;
    }

    public function respond(int $dosenUserId, int $applicationId, string $decision, ?string $note): void
    {
        global $pdo;
        $note = PengajuanDecision::normalizeNote($decision, $note);

        $pdo->beginTransaction();
        try {
            $lock = $pdo->prepare(
                "SELECT p.id, p.mahasiswa_id, p.status_pendaftaran,
                        p.status_persetujuan_dosen, prof.user_id AS mahasiswa_user_id
                 FROM pendaftaran_magang p
                 INNER JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id
                 WHERE p.id = :id FOR UPDATE OF p"
            );
            $lock->execute(['id' => $applicationId]);
            $application = $lock->fetch(PDO::FETCH_ASSOC);
            if (!$application) {
                throw new RuntimeException('Pengajuan tidak ditemukan.');
            }
            if ($application['status_pendaftaran'] !== 'menunggu_persetujuan_dosen'
                || $application['status_persetujuan_dosen'] !== 'menunggu') {
                throw new RuntimeException('Pengajuan sudah tidak menunggu persetujuan dosen.');
            }

            $ownership = $pdo->prepare(
                "SELECT 1
                 FROM pendaftaran_magang p
                 INNER JOIN profil_dosen pd ON pd.id = p.dosen_pembimbing_snapshot_id
                 WHERE p.id = :id AND pd.user_id = :dosen_user_id"
            );
            $ownership->execute(['id' => $applicationId, 'dosen_user_id' => $dosenUserId]);
            if ($ownership->fetchColumn() === false) {
                throw new RuntimeException('Pengajuan ini bukan berada pada antrean Dosen Anda.');
            }

            if ($decision === 'disetujui') {
                $documents = $pdo->prepare(
                    "SELECT COUNT(DISTINCT jenis_dokumen)
                     FROM dokumen_pendaftaran_magang
                     WHERE pendaftaran_id = :id
                       AND jenis_dokumen IN (
                           'pakta_integritas', 'daftar_riwayat_hidup', 'khs',
                           'ktp', 'ktm', 'surat_izin_orang_tua'
                       )"
                );
                $documents->execute(['id' => $applicationId]);
                if ((int) $documents->fetchColumn() !== 6) {
                    throw new RuntimeException('Pengajuan belum memiliki seluruh dokumen wajib dan belum dapat disetujui.');
                }
            }

            $nextStatus = $decision === 'disetujui' ? 'menunggu_respons_mitra' : 'ditolak_dosen';
            $update = $pdo->prepare(
                'UPDATE pendaftaran_magang
                 SET status_persetujuan_dosen = :decision,
                     dosen_penyetuju_id = :dosen_id,
                     waktu_persetujuan_dosen = CURRENT_TIMESTAMP,
                     catatan_dosen = :note,
                     status_pendaftaran = :next_status,
                     tanggal_selesai_proses = CASE WHEN :terminal THEN CURRENT_TIMESTAMP ELSE NULL END,
                     updated_at = CURRENT_TIMESTAMP
                 WHERE id = :id AND status_persetujuan_dosen = \'menunggu\''
            );
            PengajuanDecision::bindUpdate(
                $update,
                $decision,
                $dosenUserId,
                $note,
                $nextStatus,
                $applicationId
            );
            $update->execute();
            if ($update->rowCount() !== 1) {
                throw new RuntimeException('Pengajuan telah diproses oleh pengguna lain.');
            }

            $history = $pdo->prepare(
                'INSERT INTO riwayat_pendaftaran_magang
                    (pendaftaran_id, status_sebelumnya, status_baru, catatan, diubah_oleh)
                 VALUES (:id, :previous, :next, :note, :actor)'
            );
            $history->execute([
                'id' => $applicationId,
                'previous' => (string) $application['status_pendaftaran'],
                'next' => $nextStatus,
                'note' => $note,
                'actor' => $dosenUserId,
            ]);

            $notification = $pdo->prepare(
                "INSERT INTO notifikasi (user_id, judul, pesan, jenis, referensi_tabel, referensi_id, tautan)
                 VALUES (:user_id, :judul, :pesan, 'persetujuan', 'pendaftaran_magang', :reference_id, :tautan)"
            );
            $notification->execute([
                'user_id' => (int) $application['mahasiswa_user_id'],
                'judul' => 'Persetujuan dosen diperbarui',
                'pesan' => 'Pengajuan magang Anda telah ' . $decision . ' oleh dosen.',
                'reference_id' => $applicationId,
                'tautan' => '/dashboard/mahasiswa/pengajuan/detail/' . $applicationId,
            ]);
            $pdo->commit();

            (new AktivitasPengguna())->log(
                $dosenUserId,
                'Pengajuan magang diproses dosen',
                'Pengajuan #' . $applicationId . ' diputuskan ' . $decision . '.',
                'pendaftaran',
                'pendaftaran_magang',
                $applicationId
            );
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public function getPendingDocument(int $documentId, int $actorId, string $role): ?array
    {
        global $pdo;
        $stmt = $pdo->prepare(
            "SELECT d.nama_asli, d.path_file, d.mime_type,
                    d.pendaftaran_id
             FROM dokumen_pendaftaran_magang d
             INNER JOIN pendaftaran_magang p ON p.id = d.pendaftaran_id
             INNER JOIN formasi_magang f ON f.id = p.formasi_id
             INNER JOIN mitra m ON m.id = f.mitra_id
             LEFT JOIN profil_dosen assigned_pd ON assigned_pd.id = p.dosen_pembimbing_snapshot_id
             LEFT JOIN users assigned_u ON assigned_u.id = assigned_pd.user_id
             WHERE d.id = :id
               AND (
                    (:role_dosen = 'dosen'
                     AND assigned_u.id = :actor_id
                     AND p.status_pendaftaran = 'menunggu_persetujuan_dosen'
                     AND p.status_persetujuan_dosen = 'menunggu')
                    OR
                    (:role_mitra = 'mitra'
                     AND m.user_id = :actor_id
                     AND p.status_pendaftaran IN ('menunggu_respons_mitra', 'seleksi')
                     AND p.status_persetujuan_dosen = 'disetujui')
               )
             LIMIT 1"
        );
        $stmt->execute([
            'id' => $documentId,
            'role_dosen' => $role,
            'role_mitra' => $role,
            'actor_id' => $actorId,
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
