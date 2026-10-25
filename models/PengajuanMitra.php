<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/AktivitasPengguna.php';
require_once __DIR__ . '/PdoParameterBinder.php';

class PengajuanMitra
{
    public function getApplications(int $mitraUserId): array
    {
        global $pdo;
        $stmt = $pdo->prepare(
            "SELECT p.id, p.status_pendaftaran, p.status_persetujuan_dosen,
                    p.status_respons_mitra, p.tanggal_pengajuan, p.catatan_dosen,
                    p.catatan_mitra, f.judul, f.tanggal_mulai, f.tanggal_selesai,
                    prof.nim, prof.program_studi, u.name AS mahasiswa_nama,
                    m.nama_perusahaan, docs.dokumen
             FROM pendaftaran_magang p
             INNER JOIN formasi_magang f ON f.id = p.formasi_id
             INNER JOIN mitra m ON m.id = f.mitra_id
             INNER JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id
             INNER JOIN users u ON u.id = prof.user_id
             LEFT JOIN LATERAL (
                 SELECT json_agg(json_build_object(
                     'id', d.id, 'jenis', d.jenis_dokumen, 'nama', d.nama_asli
                 ) ORDER BY d.id) AS dokumen
                 FROM dokumen_pendaftaran_magang d
                 WHERE d.pendaftaran_id = p.id
             ) docs ON TRUE
             WHERE m.user_id = :mitra_user_id
               AND p.status_persetujuan_dosen = 'disetujui'
               AND p.dosen_pembimbing_snapshot_id IS NOT NULL
               AND p.status_pendaftaran IN ('menunggu_respons_mitra', 'seleksi')
             ORDER BY p.tanggal_pengajuan ASC, p.id ASC"
        );
        $stmt->execute(['mitra_user_id' => $mitraUserId]);

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($items as &$item) {
            $item['dokumen'] = is_string($item['dokumen'] ?? null)
                ? (json_decode($item['dokumen'], true) ?: [])
                : [];
        }
        unset($item);

        return $items;
    }

    public function respond(int $mitraUserId, int $applicationId, string $decision, ?string $note): void
    {
        global $pdo;
        if (!in_array($decision, ['diterima', 'ditolak', 'seleksi'], true)) {
            throw new InvalidArgumentException('Keputusan mitra tidak valid.');
        }
        $note = $note !== null ? trim($note) : null;
        if ($note !== null && mb_strlen($note) > 5000) {
            throw new InvalidArgumentException('Catatan mitra maksimal 5.000 karakter.');
        }

        $pdo->beginTransaction();
        try {
            $applicationStmt = $pdo->prepare(
                "SELECT p.id, p.formasi_id, p.mahasiswa_id, p.status_pendaftaran,
                        p.status_persetujuan_dosen, m.id AS mitra_id
                 FROM pendaftaran_magang p
                 INNER JOIN formasi_magang f ON f.id = p.formasi_id
                 INNER JOIN mitra m ON m.id = f.mitra_id
                 WHERE p.id = :application_id AND m.user_id = :mitra_user_id
                 FOR UPDATE OF p"
            );
            $applicationStmt->execute([
                'application_id' => $applicationId,
                'mitra_user_id' => $mitraUserId,
            ]);
            $application = $applicationStmt->fetch(PDO::FETCH_ASSOC);
            if (!$application) {
                throw new RuntimeException('Pengajuan tidak ditemukan atau bukan milik perusahaan Anda.');
            }
            if ($application['status_persetujuan_dosen'] !== 'disetujui'
                || !in_array($application['status_pendaftaran'], ['menunggu_respons_mitra', 'seleksi'], true)) {
                throw new RuntimeException('Pengajuan belum berada pada tahap respons mitra.');
            }
            if ($decision === 'seleksi' && $application['status_pendaftaran'] === 'seleksi') {
                throw new RuntimeException('Pengajuan sudah berada pada tahap seleksi. Pilih keputusan akhir setelah proses seleksi selesai.');
            }

            if ($decision === 'diterima') {
                $formStmt = $pdo->prepare(
                    'SELECT jumlah_kuota, jumlah_diterima FROM formasi_magang WHERE id = :id FOR UPDATE'
                );
                $formStmt->execute(['id' => (int) $application['formasi_id']]);
                $form = $formStmt->fetch(PDO::FETCH_ASSOC);
                if (!$form || (int) $form['jumlah_diterima'] >= (int) $form['jumlah_kuota']) {
                    throw new RuntimeException('Kuota formasi telah penuh; pengajuan tidak dapat diterima.');
                }
                $increment = $pdo->prepare(
                    'UPDATE formasi_magang SET jumlah_diterima = jumlah_diterima + 1, updated_at = CURRENT_TIMESTAMP
                     WHERE id = :id AND jumlah_diterima < jumlah_kuota'
                );
                $increment->execute(['id' => (int) $application['formasi_id']]);
                if ($increment->rowCount() !== 1) {
                    throw new RuntimeException('Kuota formasi berubah saat keputusan disimpan.');
                }
            }

            $nextStatus = match ($decision) {
                'diterima' => 'diterima',
                'ditolak' => 'ditolak_mitra',
                'seleksi' => 'seleksi',
            };
            $update = $pdo->prepare(
                "UPDATE pendaftaran_magang
                 SET status_respons_mitra = :decision,
                     catatan_mitra = :note,
                     waktu_respons_mitra = CURRENT_TIMESTAMP,
                     status_pendaftaran = :next_status,
                     tanggal_selesai_proses = CASE WHEN :terminal THEN CURRENT_TIMESTAMP ELSE NULL END,
                     updated_at = CURRENT_TIMESTAMP
                 WHERE id = :id
                   AND status_persetujuan_dosen = 'disetujui'
                   AND status_pendaftaran IN ('menunggu_respons_mitra', 'seleksi')"
            );
            PdoParameterBinder::execute($update, [
                'decision' => $decision,
                'note' => $note,
                'next_status' => $nextStatus,
                'terminal' => $decision !== 'seleksi',
                'id' => $applicationId,
            ]);
            if ($update->rowCount() !== 1) {
                throw new RuntimeException('Pengajuan telah diproses atau tidak lagi berada pada tahap respons Mitra.');
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
                'actor' => $mitraUserId,
            ]);

            $studentStmt = $pdo->prepare(
                'SELECT user_id FROM profil_mahasiswa WHERE id = :id'
            );
            $studentStmt->execute(['id' => (int) $application['mahasiswa_id']]);
            $studentUserId = $studentStmt->fetchColumn();
            if ($studentUserId === false) {
                throw new RuntimeException('Profil mahasiswa pengajuan tidak ditemukan.');
            }
            $notification = $pdo->prepare(
                "INSERT INTO notifikasi (user_id, judul, pesan, jenis, referensi_tabel, referensi_id, tautan)
                 VALUES (:user_id, :judul, :pesan, 'pendaftaran', 'pendaftaran_magang', :reference_id, :tautan)"
            );
            $notification->execute([
                'user_id' => (int) $studentUserId,
                'judul' => 'Respons mitra atas pengajuan magang',
                'pesan' => 'Mitra memberikan respons ' . $decision . ' terhadap pengajuan Anda.',
                'reference_id' => $applicationId,
                'tautan' => '/dashboard/mahasiswa/pengajuan/detail/' . $applicationId,
            ]);
            $pdo->commit();

            (new AktivitasPengguna())->log(
                $mitraUserId,
                'Respons pengajuan magang diperbarui',
                'Pengajuan #' . $applicationId . ' direspons mitra dengan keputusan ' . $decision . '.',
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
}
