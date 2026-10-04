<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/AktivitasPengguna.php';

class Logbook
{
    public function getPlacement(int $userId): ?array
    {
        global $pdo;
        $stmt = $pdo->prepare(
            'SELECT pm.id, pm.status, pm.tanggal_mulai, pm.tanggal_selesai, '
            . 'f.judul, m.nama_perusahaan '
            . 'FROM penempatan_magang pm '
            . 'JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id '
            . 'JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id '
            . 'JOIN formasi_magang f ON f.id = p.formasi_id '
            . 'JOIN mitra m ON m.id = f.mitra_id '
            . 'WHERE prof.user_id = :user_id '
            . 'ORDER BY pm.created_at DESC, pm.id DESC LIMIT 1'
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getSummary(int $userId): array
    {
        global $pdo;
        $stmt = $pdo->prepare(
            'SELECT '
            . 'COUNT(*) AS total, '
            . "COUNT(*) FILTER (WHERE lm.status = 'disetujui') AS disetujui, "
            . "COUNT(*) FILTER (WHERE lm.status IN ('diajukan','menunggu_mitra','menunggu_dosen','menunggu_verifikasi')) AS menunggu, "
            . "COUNT(*) FILTER (WHERE lm.status = 'perlu_revisi') AS revisi "
            . 'FROM logbook_mingguan lm '
            . 'JOIN penempatan_magang pen ON pen.id = lm.penempatan_id '
            . 'JOIN pendaftaran_magang p ON p.id = pen.pendaftaran_id '
            . 'JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id '
            . 'WHERE prof.user_id = :user_id'
        );
        $stmt->execute(['user_id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

        return [
            'total' => (int) ($row['total'] ?? 0),
            'disetujui' => (int) ($row['disetujui'] ?? 0),
            'menunggu' => (int) ($row['menunggu'] ?? 0),
            'revisi' => (int) ($row['revisi'] ?? 0),
        ];
    }

    public function getRecent(int $userId, int $limit = 6): array
    {
        global $pdo;
        $limit = max(1, min($limit, 20));
        $stmt = $pdo->prepare(
            'SELECT lm.id, lm.minggu_ke, lm.tanggal_mulai, lm.tanggal_selesai, lm.status, '
            . 'lr.aktivitas, lr.hasil_pekerjaan, lr.kendala, lr.rencana_selanjutnya '
            . 'FROM logbook_mingguan lm '
            . 'JOIN penempatan_magang pen ON pen.id = lm.penempatan_id '
            . 'JOIN pendaftaran_magang p ON p.id = pen.pendaftaran_id '
            . 'JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id '
            . 'LEFT JOIN logbook_revisi lr ON lr.logbook_id = lm.id AND lr.nomor_versi = lm.versi_terkini '
            . 'WHERE prof.user_id = :user_id '
            . 'ORDER BY lm.tanggal_mulai DESC, lm.minggu_ke DESC '
            . 'LIMIT ' . $limit
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id, int $userId): ?array
    {
        global $pdo;
        $stmt = $pdo->prepare(
            'SELECT lm.*, lr.aktivitas, lr.hasil_pekerjaan, lr.kendala, lr.rencana_selanjutnya, lr.catatan_revisi '
            . 'FROM logbook_mingguan lm '
            . 'JOIN penempatan_magang pen ON pen.id = lm.penempatan_id '
            . 'JOIN pendaftaran_magang p ON p.id = pen.pendaftaran_id '
            . 'JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id '
            . 'LEFT JOIN logbook_revisi lr ON lr.logbook_id = lm.id AND lr.nomor_versi = lm.versi_terkini '
            . 'WHERE lm.id = :id AND prof.user_id = :user_id LIMIT 1'
        );
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(int $userId, array $data): int
    {
        global $pdo;
        $placement = $this->getPlacement($userId);
        if (!$placement) {
            throw new RuntimeException('Penempatan magang belum tersedia.');
        }
        if (!in_array($placement['status'], ['berlangsung', 'menunggu_penilaian'], true)) {
            throw new RuntimeException('Logbook hanya dapat dibuat saat proses magang berlangsung.');
        }

        $pdo->beginTransaction();
        try {
            $check = $pdo->prepare('SELECT 1 FROM logbook_mingguan WHERE penempatan_id = :placement AND minggu_ke = :week LIMIT 1');
            $check->execute(['placement' => (int) $placement['id'], 'week' => $data['minggu_ke']]);
            if ($check->fetchColumn()) {
                throw new RuntimeException('Logbook untuk minggu tersebut sudah ada. Gunakan fitur edit.');
            }

            $stmt = $pdo->prepare(
                'INSERT INTO logbook_mingguan (penempatan_id, minggu_ke, tanggal_mulai, tanggal_selesai, versi_terkini, status) '
                . "VALUES (:placement, :week, :start, :end, 1, 'draft') RETURNING id"
            );
            $stmt->execute([
                'placement' => (int) $placement['id'],
                'week' => $data['minggu_ke'],
                'start' => $data['tanggal_mulai'],
                'end' => $data['tanggal_selesai'],
            ]);
            $id = (int) $stmt->fetchColumn();

            $revision = $pdo->prepare(
                'INSERT INTO logbook_revisi (logbook_id, nomor_versi, aktivitas, hasil_pekerjaan, kendala, rencana_selanjutnya, dibuat_oleh) '
                . 'VALUES (:id, 1, :aktivitas, :hasil, :kendala, :rencana, :user_id)'
            );
            $revision->execute([
                'id' => $id,
                'aktivitas' => $data['aktivitas'],
                'hasil' => $data['hasil_pekerjaan'] !== '' ? $data['hasil_pekerjaan'] : null,
                'kendala' => $data['kendala'] !== '' ? $data['kendala'] : null,
                'rencana' => $data['rencana_selanjutnya'] !== '' ? $data['rencana_selanjutnya'] : null,
                'user_id' => $userId,
            ]);

            $pdo->commit();
            (new AktivitasPengguna())->log($userId, 'Logbook ditambahkan', 'Logbook minggu ke-' . $data['minggu_ke'] . ' berhasil dibuat.', 'logbook', 'logbook_mingguan', $id);
            return $id;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            throw $e;
        }
    }

    public function update(int $id, int $userId, array $data): bool
    {
        global $pdo;
        $current = $this->getById($id, $userId);
        if (!$current) {
            throw new RuntimeException('Logbook tidak ditemukan.');
        }
        if (!in_array($current['status'], ['draft', 'perlu_revisi'], true)) {
            throw new RuntimeException('Logbook yang sudah diajukan tidak dapat diedit.');
        }

        $pdo->beginTransaction();
        try {
            $nextVersion = (int) $current['versi_terkini'] + 1;
            $stmt = $pdo->prepare(
                "UPDATE logbook_mingguan SET minggu_ke = :week, tanggal_mulai = :start, tanggal_selesai = :end, versi_terkini = :version, status = 'draft', updated_at = CURRENT_TIMESTAMP WHERE id = :id"
            );
            $stmt->execute([
                'week' => $data['minggu_ke'],
                'start' => $data['tanggal_mulai'],
                'end' => $data['tanggal_selesai'],
                'version' => $nextVersion,
                'id' => $id,
            ]);

            $revision = $pdo->prepare(
                'INSERT INTO logbook_revisi (logbook_id, nomor_versi, aktivitas, hasil_pekerjaan, kendala, rencana_selanjutnya, catatan_revisi, dibuat_oleh) '
                . 'VALUES (:id, :version, :aktivitas, :hasil, :kendala, :rencana, :catatan, :user_id)'
            );
            $revision->execute([
                'id' => $id,
                'version' => $nextVersion,
                'aktivitas' => $data['aktivitas'],
                'hasil' => $data['hasil_pekerjaan'] !== '' ? $data['hasil_pekerjaan'] : null,
                'kendala' => $data['kendala'] !== '' ? $data['kendala'] : null,
                'rencana' => $data['rencana_selanjutnya'] !== '' ? $data['rencana_selanjutnya'] : null,
                'catatan' => $current['status'] === 'perlu_revisi' ? ($current['catatan_revisi'] ?? null) : null,
                'user_id' => $userId,
            ]);

            $pdo->commit();
            (new AktivitasPengguna())->log($userId, 'Logbook diperbarui', 'Logbook minggu ke-' . $data['minggu_ke'] . ' berhasil diperbarui.', 'logbook', 'logbook_mingguan', $id);
            return true;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            throw $e;
        }
    }

    public function submit(int $id, int $userId): bool
    {
        global $pdo;
        $current = $this->getById($id, $userId);
        if (!$current) {
            throw new RuntimeException('Logbook tidak ditemukan.');
        }
        if (!in_array($current['status'], ['draft', 'perlu_revisi'], true)) {
            throw new RuntimeException('Logbook tidak dapat diajukan pada status saat ini.');
        }

        $stmt = $pdo->prepare("UPDATE logbook_mingguan SET status = 'diajukan', updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        $stmt->execute(['id' => $id]);
        (new AktivitasPengguna())->log($userId, 'Logbook diajukan', 'Logbook minggu ke-' . $current['minggu_ke'] . ' diajukan untuk proses verifikasi.', 'logbook', 'logbook_mingguan', $id);
        return $stmt->rowCount() > 0;
    }
}
