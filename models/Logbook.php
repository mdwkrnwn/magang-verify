<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/AktivitasPengguna.php';

class Logbook
{
    private const STUDENT = 'mahasiswa';
    private const PARTNER = 'mitra';
    private const LECTURER = 'dosen';
    private const VALIDATORS = ['tendik', 'koordinator_magang'];

    public function getPlacements(int $userId): array
    {
        global $pdo;

        $stmt = $pdo->prepare(
            'SELECT pm.id, pm.status, pm.tanggal_mulai, pm.tanggal_selesai,
                    f.judul, m.id AS mitra_id, m.nama_perusahaan
             FROM penempatan_magang pm
             INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id
             INNER JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id
             INNER JOIN formasi_magang f ON f.id = p.formasi_id
             INNER JOIN mitra m ON m.id = f.mitra_id
             WHERE prof.user_id = :user_id
             ORDER BY pm.tanggal_mulai DESC, pm.id DESC'
        );
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPlacement(int $userId, ?int $placementId = null): ?array
    {
        global $pdo;

        $sql =
            'SELECT pm.id, pm.pendaftaran_id, pm.status, pm.tanggal_mulai, pm.tanggal_selesai,
                    pm.dosen_pembimbing_id, f.judul, m.id AS mitra_id,
                    m.nama_perusahaan, m.user_id AS mitra_user_id,
                    pd.user_id AS dosen_user_id
             FROM penempatan_magang pm
             INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id
             INNER JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id
             INNER JOIN formasi_magang f ON f.id = p.formasi_id
             INNER JOIN mitra m ON m.id = f.mitra_id
             LEFT JOIN profil_dosen pd ON pd.id = pm.dosen_pembimbing_id
             WHERE prof.user_id = :user_id';

        $params = ['user_id' => $userId];
        if ($placementId !== null) {
            $sql .= ' AND pm.id = :placement_id';
            $params['placement_id'] = $placementId;
        } else {
            $sql .= " ORDER BY CASE WHEN pm.status = 'berlangsung' THEN 0 ELSE 1 END,
                               pm.tanggal_mulai DESC, pm.id DESC LIMIT 1";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getWeeks(int $placementId): array
    {
        global $pdo;

        $stmt = $pdo->prepare(
            'SELECT lm.id, lm.penempatan_id, lm.minggu_ke, lm.tanggal_mulai,
                    lm.tanggal_selesai, lm.versi_terkini, lm.status,
                    ((lm.tanggal_selesai - lm.tanggal_mulai) + 1) AS total_hari,
                    COALESCE(d.hari_terisi, 0) AS hari_terisi,
                    COALESCE(d.validasi_menunggu, 0) AS validasi_menunggu,
                    COALESCE(s.ttd_mahasiswa, FALSE) AS ttd_mahasiswa,
                    COALESCE(s.ttd_mitra, FALSE) AS ttd_mitra,
                    COALESCE(s.ttd_dosen, FALSE) AS ttd_dosen
             FROM logbook_mingguan lm
             LEFT JOIN (
                 SELECT logbook_id,
                        COUNT(*) AS hari_terisi,
                        COUNT(*) FILTER (WHERE status_validasi = \'menunggu\') AS validasi_menunggu
                 FROM logbook_harian
                 GROUP BY logbook_id
             ) d ON d.logbook_id = lm.id
             LEFT JOIN (
                 SELECT lr.logbook_id,
                        BOOL_OR(ltt.tahap = \'mahasiswa\') AS ttd_mahasiswa,
                        BOOL_OR(ltt.tahap = \'mitra\') AS ttd_mitra,
                        BOOL_OR(ltt.tahap = \'dosen\') AS ttd_dosen
                 FROM logbook_revisi lr
                 INNER JOIN logbook_tanda_tangan ltt ON ltt.revisi_id = lr.id
                 INNER JOIN logbook_mingguan cur ON cur.id = lr.logbook_id AND cur.versi_terkini = lr.nomor_versi
                 GROUP BY lr.logbook_id
             ) s ON s.logbook_id = lm.id
             WHERE lm.penempatan_id = :placement_id
             ORDER BY lm.minggu_ke ASC'
        );
        $stmt->execute(['placement_id' => $placementId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getWeek(int $weekId, int $userId): ?array
    {
        global $pdo;

        $stmt = $pdo->prepare(
            'SELECT lm.*, pm.status AS penempatan_status, pm.tanggal_mulai AS penempatan_mulai,
                    pm.tanggal_selesai AS penempatan_selesai,
                    f.judul, m.id AS mitra_id, m.nama_perusahaan, m.user_id AS mitra_user_id,
                    pd.user_id AS dosen_user_id,
                    u.name AS mahasiswa_nama, prof.nim, prof.program_studi,
                    cur.aktivitas, cur.hasil_pekerjaan, cur.kendala, cur.rencana_selanjutnya,
                    cur.catatan_revisi, cur.snapshot_hash
             FROM logbook_mingguan lm
             INNER JOIN penempatan_magang pm ON pm.id = lm.penempatan_id
             INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id
             INNER JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id
             INNER JOIN users u ON u.id = prof.user_id
             INNER JOIN formasi_magang f ON f.id = p.formasi_id
             INNER JOIN mitra m ON m.id = f.mitra_id
             LEFT JOIN profil_dosen pd ON pd.id = pm.dosen_pembimbing_id
             LEFT JOIN logbook_revisi cur
                ON cur.logbook_id = lm.id AND cur.nomor_versi = lm.versi_terkini
             WHERE lm.id = :week_id AND prof.user_id = :user_id
             LIMIT 1'
        );
        $stmt->execute(['week_id' => $weekId, 'user_id' => $userId]);
        $week = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$week) {
            return null;
        }

        $week['daily'] = $this->getDailyEntries($weekId);
        $week['signatures'] = $this->getCurrentSignatures($weekId);
        $week['had_previous_signature'] = $this->hasPreviousSignature($weekId, (int) $week['versi_terkini']);
        $week['completeness'] = $this->getCompleteness($week);
        $week['status_label'] = $this->statusLabel((string) $week['status']);

        return $week;
    }

    public function getWeekForRole(int $weekId, int $userId, string $role): ?array
    {
        global $pdo;

        $sql =
            'SELECT lm.id, lm.penempatan_id, lm.minggu_ke, lm.tanggal_mulai, lm.tanggal_selesai,
                    lm.versi_terkini, lm.status,
                    pm.status AS penempatan_status, pm.dosen_pembimbing_id,
                    f.judul, m.id AS mitra_id, m.nama_perusahaan, m.user_id AS mitra_user_id,
                    pd.user_id AS dosen_user_id,
                    u.id AS mahasiswa_user_id, u.name AS mahasiswa_nama,
                    prof.id AS mahasiswa_id, prof.nim, prof.program_studi,
                    cur.snapshot_hash
             FROM logbook_mingguan lm
             INNER JOIN penempatan_magang pm ON pm.id = lm.penempatan_id
             INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id
             INNER JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id
             INNER JOIN users u ON u.id = prof.user_id
             INNER JOIN formasi_magang f ON f.id = p.formasi_id
             INNER JOIN mitra m ON m.id = f.mitra_id
             LEFT JOIN profil_dosen pd ON pd.id = pm.dosen_pembimbing_id
             LEFT JOIN logbook_revisi cur ON cur.logbook_id = lm.id AND cur.nomor_versi = lm.versi_terkini
             WHERE lm.id = :week_id';

        $params = ['week_id' => $weekId];

        if ($role === self::PARTNER) {
            $sql .= ' AND m.user_id = :user_id';
            $params['user_id'] = $userId;
        } elseif ($role === self::LECTURER) {
            $sql .= ' AND pd.user_id = :user_id';
            $params['user_id'] = $userId;
        } elseif (in_array($role, self::VALIDATORS, true)) {
            // Tendik/Koordinator dapat memvalidasi ketidakhadiran.
        } else {
            $sql .= ' AND prof.user_id = :user_id';
            $params['user_id'] = $userId;
        }

        $sql .= ' LIMIT 1';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $week = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$week) {
            return null;
        }

        $week['daily'] = $this->getDailyEntries($weekId);
        $week['signatures'] = $this->getCurrentSignatures($weekId);
        $week['had_previous_signature'] = $this->hasPreviousSignature($weekId, (int) $week['versi_terkini']);
        $week['completeness'] = $this->getCompleteness($week);
        $week['status_label'] = $this->statusLabel((string) $week['status']);

        return $week;
    }

    public function getDailyEntries(int $weekId): array
    {
        global $pdo;

        $stmt = $pdo->prepare(
            'SELECT lh.*, vu.name AS validator_name
             FROM logbook_harian lh
             LEFT JOIN users vu ON vu.id = lh.divalidasi_oleh
             WHERE lh.logbook_id = :logbook_id
             ORDER BY lh.tanggal ASC'
        );
        $stmt->execute(['logbook_id' => $weekId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSummary(int $userId, ?int $placementId = null): array
    {
        global $pdo;

        $placement = $this->getPlacement($userId, $placementId);
        if (!$placement) {
            return ['total' => 0, 'disetujui' => 0, 'menunggu' => 0, 'revisi' => 0];
        }

        $stmt = $pdo->prepare(
            'SELECT COUNT(*) AS total,
                    COUNT(*) FILTER (WHERE status = \'disetujui\') AS disetujui,
                    COUNT(*) FILTER (WHERE status IN (\'menunggu_mitra\', \'menunggu_dosen\')) AS menunggu,
                    COUNT(*) FILTER (WHERE status = \'perlu_revisi\') AS revisi
             FROM logbook_mingguan
             WHERE penempatan_id = :placement_id'
        );
        $stmt->execute(['placement_id' => (int) $placement['id']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

        return [
            'total' => (int) ($row['total'] ?? 0),
            'disetujui' => (int) ($row['disetujui'] ?? 0),
            'menunggu' => (int) ($row['menunggu'] ?? 0),
            'revisi' => (int) ($row['revisi'] ?? 0),
        ];
    }

    public function getNextWeekPlan(array $placement): ?array
    {
        global $pdo;

        $today = new DateTimeImmutable('today');
        $placementStart = new DateTimeImmutable((string) $placement['tanggal_mulai']);
        $placementEnd = new DateTimeImmutable((string) $placement['tanggal_selesai']);

        if ($today < $placementStart) {
            return null;
        }

        $stmt = $pdo->prepare(
            'SELECT minggu_ke, tanggal_mulai, tanggal_selesai
             FROM logbook_mingguan
             WHERE penempatan_id = :placement_id
             ORDER BY minggu_ke DESC
             LIMIT 1'
        );
        $stmt->execute(['placement_id' => (int) $placement['id']]);
        $last = $stmt->fetch(PDO::FETCH_ASSOC);

        $isFirstWeek = !$last;
        if ($isFirstWeek) {
            $start = $placementStart;
            $week = 1;
        } else {
            $lastEnd = new DateTimeImmutable((string) $last['tanggal_selesai']);
            if ($today <= $lastEnd) {
                return null;
            }
            $start = $lastEnd->modify('+1 day');
            $week = (int) $last['minggu_ke'] + 1;
        }

        if ($start > $placementEnd) {
            return null;
        }

        // Minggu pertama mengikuti tanggal mulai penempatan lalu berakhir pada Minggu.
        // Minggu berikutnya selalu Senin-Minggu karena start berasal dari hari setelah Minggu sebelumnya.
        $end = $isFirstWeek
            ? $start->modify('sunday this week')
            : $start->modify('+6 days');

        if ($end > $placementEnd) {
            $end = $placementEnd;
        }

        return [
            'minggu_ke' => $week,
            'tanggal_mulai' => $start->format('Y-m-d'),
            'tanggal_selesai' => $end->format('Y-m-d'),
            'sudah_bisa_dibuat' => $today >= $start,
            'terlambat' => $today > $end,
        ];
    }

    public function createWeek(int $userId, ?int $placementId = null): int
    {
        global $pdo;

        $placement = $this->getPlacement($userId, $placementId);
        if (!$placement) {
            throw new RuntimeException('Penempatan magang belum tersedia.');
        }
        if (!in_array($placement['status'], ['berlangsung', 'selesai'], true)) {
            throw new RuntimeException('Logbook belum dapat dibuat karena penempatan belum berlangsung.');
        }

        $plan = $this->getNextWeekPlan($placement);
        if (!$plan) {
            throw new RuntimeException('Belum ada minggu logbook yang dapat dibuat. Pastikan periode sebelumnya sudah selesai.');
        }
        if (!$plan['sudah_bisa_dibuat']) {
            throw new RuntimeException('Minggu tersebut belum dapat dibuat karena tanggalnya masih di masa depan.');
        }

        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO logbook_mingguan
                    (penempatan_id, minggu_ke, tanggal_mulai, tanggal_selesai, versi_terkini, status)
                 VALUES (:placement_id, :minggu_ke, :tanggal_mulai, :tanggal_selesai, 1, \'draft\')
                 RETURNING id'
            );
            $stmt->execute([
                'placement_id' => (int) $placement['id'],
                'minggu_ke' => $plan['minggu_ke'],
                'tanggal_mulai' => $plan['tanggal_mulai'],
                'tanggal_selesai' => $plan['tanggal_selesai'],
            ]);
            $weekId = (int) $stmt->fetchColumn();

            $revisionId = $this->insertRevision($weekId, 1, $userId, null, null);
            $this->refreshRevisionSnapshot($weekId, $revisionId);

            $pdo->commit();
            (new AktivitasPengguna())->log(
                $userId,
                'Minggu logbook dibuat',
                'Minggu ke-' . $plan['minggu_ke'] . ' dibuat untuk periode ' . $plan['tanggal_mulai'] . ' sampai ' . $plan['tanggal_selesai'] . '.',
                'logbook',
                'logbook_mingguan',
                $weekId
            );

            return $weekId;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public function createDaily(int $weekId, int $userId, array $data, ?array $file = null): int
    {
        global $pdo;

        $week = $this->getWeek($weekId, $userId);
        if (!$week) {
            throw new RuntimeException('Minggu logbook tidak ditemukan.');
        }
        $this->assertStudentCanEdit($week);

        $date = $this->validateDailyData($week, $data);
        $storedFile = null;

        if ($file && (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE)) {
            $storedFile = $this->storeEvidence($file, $userId, $weekId, $date);
        }

        $pdo->beginTransaction();
        try {
            $exists = $pdo->prepare('SELECT id FROM logbook_harian WHERE logbook_id = :logbook_id AND tanggal = :tanggal LIMIT 1');
            $exists->execute(['logbook_id' => $weekId, 'tanggal' => $date]);
            if ($exists->fetchColumn() !== false) {
                throw new RuntimeException('Logbook untuk tanggal tersebut sudah ada.');
            }

            $validation = $data['status_kehadiran'] === 'tidak_hadir' ? 'menunggu' : 'tidak_perlu';
            $stmt = $pdo->prepare(
                'INSERT INTO logbook_harian
                    (logbook_id, tanggal, jam_masuk, jam_pulang, kegiatan, status_kehadiran,
                     alasan_ketidakhadiran, bukti_path, status_validasi)
                 VALUES (:logbook_id, :tanggal, :jam_masuk, :jam_pulang, :kegiatan, :status_kehadiran,
                         :alasan, :bukti_path, :status_validasi)
                 RETURNING id'
            );
            $stmt->execute([
                'logbook_id' => $weekId,
                'tanggal' => $date,
                'jam_masuk' => $data['jam_masuk'] !== '' ? $data['jam_masuk'] : null,
                'jam_pulang' => $data['jam_pulang'] !== '' ? $data['jam_pulang'] : null,
                'kegiatan' => $data['kegiatan'],
                'status_kehadiran' => $data['status_kehadiran'],
                'alasan' => $data['status_kehadiran'] === 'tidak_hadir' ? $data['alasan_ketidakhadiran'] : null,
                'bukti_path' => $storedFile,
                'status_validasi' => $validation,
            ]);
            $dailyId = (int) $stmt->fetchColumn();

            $revisionId = $this->createRevisionFromCurrent($weekId, $userId, 'Perubahan logbook harian.');
            $this->refreshRevisionSnapshot($weekId, $revisionId);

            $pdo->commit();
            (new AktivitasPengguna())->log(
                $userId,
                'Logbook harian ditambahkan',
                'Aktivitas tanggal ' . $date . ' ditambahkan pada minggu ke-' . $week['minggu_ke'] . '.',
                'logbook',
                'logbook_harian',
                $dailyId
            );

            return $dailyId;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            if ($storedFile) {
                $this->deletePrivateFile($storedFile);
            }
            throw $e;
        }
    }

    public function updateDaily(int $dailyId, int $userId, array $data, ?array $file = null): bool
    {
        global $pdo;

        $daily = $this->getDailyForStudent($dailyId, $userId);
        if (!$daily) {
            throw new RuntimeException('Logbook harian tidak ditemukan.');
        }

        $week = $this->getWeek($daily['logbook_id'], $userId);
        if (!$week) {
            throw new RuntimeException('Minggu logbook tidak ditemukan.');
        }
        $this->assertStudentCanEdit($week);

        $date = $this->validateDailyData($week, $data);
        if ($date !== $daily['tanggal']) {
            $existing = $this->getDailyByDate($week['id'], $date);
            if ($existing && (int) $existing['id'] !== $dailyId) {
                throw new RuntimeException('Tanggal tersebut sudah memiliki logbook.');
            }
        }

        $storedFile = $daily['bukti_path'] ?? null;
        $newFile = null;
        if ($file && (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE)) {
            $newFile = $this->storeEvidence($file, $userId, (int) $week['id'], $date);
            $storedFile = $newFile;
        }

        $pdo->beginTransaction();
        try {
            $validation = $data['status_kehadiran'] === 'tidak_hadir' ? 'menunggu' : 'tidak_perlu';
            $stmt = $pdo->prepare(
                'UPDATE logbook_harian
                 SET tanggal = :tanggal,
                     jam_masuk = :jam_masuk,
                     jam_pulang = :jam_pulang,
                     kegiatan = :kegiatan,
                     status_kehadiran = :status_kehadiran,
                     alasan_ketidakhadiran = :alasan,
                     bukti_path = :bukti_path,
                     status_validasi = :status_validasi,
                     catatan_validasi = NULL,
                     divalidasi_oleh = NULL,
                     divalidasi_pada = NULL,
                     updated_at = CURRENT_TIMESTAMP
                 WHERE id = :id'
            );
            $stmt->execute([
                'tanggal' => $date,
                'jam_masuk' => $data['jam_masuk'] !== '' ? $data['jam_masuk'] : null,
                'jam_pulang' => $data['jam_pulang'] !== '' ? $data['jam_pulang'] : null,
                'kegiatan' => $data['kegiatan'],
                'status_kehadiran' => $data['status_kehadiran'],
                'alasan' => $data['status_kehadiran'] === 'tidak_hadir' ? $data['alasan_ketidakhadiran'] : null,
                'bukti_path' => $storedFile,
                'status_validasi' => $validation,
                'id' => $dailyId,
            ]);

            $revisionId = $this->createRevisionFromCurrent($week['id'], $userId, 'Perubahan logbook harian.');
            $this->refreshRevisionSnapshot($week['id'], $revisionId);

            $pdo->commit();

            if ($newFile && !empty($daily['bukti_path']) && $daily['bukti_path'] !== $newFile) {
                $this->deletePrivateFile((string) $daily['bukti_path']);
            }

            (new AktivitasPengguna())->log(
                $userId,
                'Logbook harian diperbarui',
                'Aktivitas tanggal ' . $date . ' diperbarui pada minggu ke-' . $week['minggu_ke'] . '.',
                'logbook',
                'logbook_harian',
                $dailyId
            );
            return true;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            if ($newFile) {
                $this->deletePrivateFile($newFile);
            }
            throw $e;
        }
    }

    public function signWeek(int $weekId, int $userId, string $role, string $signatureData): bool
    {
        global $pdo;

        if (!in_array($role, [self::STUDENT, self::PARTNER, self::LECTURER], true)) {
            throw new RuntimeException('Peran tidak dapat menandatangani logbook.');
        }

        $week = $role === self::STUDENT
            ? $this->getWeek($weekId, $userId)
            : $this->getWeekForRole($weekId, $userId, $role);

        if (!$week) {
            throw new RuntimeException('Minggu logbook tidak ditemukan atau Anda tidak berhak mengaksesnya.');
        }

        $this->assertSignatureData($signatureData);
        $this->assertSignatureWorkflow($week, $role);

        $signaturePath = $this->storeSignature($signatureData, $userId, $weekId, $role);
        $signatureHash = hash_file('sha256', $this->privateAbsolutePath($signaturePath));
        $snapshotHash = (string) ($week['snapshot_hash'] ?? '');

        if ($snapshotHash === '') {
            global $pdo;
            $currentRevision = $pdo->prepare('SELECT id FROM logbook_revisi WHERE logbook_id = :id AND nomor_versi = :version LIMIT 1');
            $currentRevision->execute(['id' => $weekId, 'version' => $week['versi_terkini']]);
            $revisionId = $currentRevision->fetchColumn();
            if ($revisionId === false) {
                $this->deletePrivateFile($signaturePath);
                throw new RuntimeException('Versi logbook tidak ditemukan.');
            }
            $this->refreshRevisionSnapshot($weekId, (int) $revisionId);
            $week['snapshot_hash'] = $this->getCurrentSnapshotHash($weekId);
            $snapshotHash = (string) $week['snapshot_hash'];
        }

        $pdo->beginTransaction();
        try {
            $existing = $pdo->prepare(
                'SELECT id FROM logbook_tanda_tangan
                 WHERE revisi_id = (
                    SELECT id FROM logbook_revisi WHERE logbook_id = :logbook_id AND nomor_versi = :versi
                 ) AND tahap = :tahap
                 LIMIT 1'
            );
            $existing->execute([
                'logbook_id' => $weekId,
                'versi' => (int) $week['versi_terkini'],
                'tahap' => $role,
            ]);
            if ($existing->fetchColumn() !== false) {
                throw new RuntimeException('Tahap tanda tangan ini sudah dilakukan untuk versi logbook saat ini.');
            }

            $revisionStmt = $pdo->prepare(
                'SELECT id FROM logbook_revisi WHERE logbook_id = :logbook_id AND nomor_versi = :versi LIMIT 1'
            );
            $revisionStmt->execute(['logbook_id' => $weekId, 'versi' => (int) $week['versi_terkini']]);
            $revisionId = $revisionStmt->fetchColumn();
            if ($revisionId === false) {
                throw new RuntimeException('Versi logbook tidak ditemukan.');
            }

            $stmt = $pdo->prepare(
                'INSERT INTO logbook_tanda_tangan
                    (revisi_id, tahap, penanda_tangan_id, keputusan, signature_path,
                     signature_hash, snapshot_hash, metadata)
                 VALUES (:revisi_id, :tahap, :user_id, \'disetujui\', :signature_path,
                         :signature_hash, :snapshot_hash, :metadata)'
            );
            $stmt->execute([
                'revisi_id' => (int) $revisionId,
                'tahap' => $role,
                'user_id' => $userId,
                'signature_path' => $signaturePath,
                'signature_hash' => $signatureHash,
                'snapshot_hash' => $snapshotHash,
                'metadata' => json_encode([
                    'role' => $role,
                    'signed_at' => (new DateTimeImmutable())->format(DateTimeInterface::ATOM),
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
                    'user_agent' => mb_substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 500),
                ], JSON_UNESCAPED_SLASHES),
            ]);

            $nextStatus = match ($role) {
                self::STUDENT => 'menunggu_mitra',
                self::PARTNER => 'menunggu_dosen',
                self::LECTURER => 'disetujui',
            };

            $update = $pdo->prepare(
                'UPDATE logbook_mingguan SET status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id'
            );
            $update->execute(['status' => $nextStatus, 'id' => $weekId]);

            $pdo->commit();

            $label = match ($role) {
                self::STUDENT => 'mahasiswa',
                self::PARTNER => 'mitra',
                self::LECTURER => 'dosen pembimbing',
            };
            (new AktivitasPengguna())->log(
                $userId,
                'Logbook ditandatangani',
                'Minggu ke-' . $week['minggu_ke'] . ' ditandatangani oleh ' . $label . '.',
                'logbook',
                'logbook_mingguan',
                $weekId
            );

            return true;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $this->deletePrivateFile($signaturePath);
            throw $e;
        }
    }

    public function validateDaily(int $dailyId, int $userId, string $role, string $decision, ?string $note = null): bool
    {
        global $pdo;

        if (!in_array($role, self::VALIDATORS, true)) {
            throw new RuntimeException('Anda tidak memiliki hak validasi ketidakhadiran.');
        }
        if (!in_array($decision, ['disetujui', 'ditolak'], true)) {
            throw new RuntimeException('Keputusan validasi tidak valid.');
        }

        $daily = $this->getDailyForValidator($dailyId);
        if (!$daily) {
            throw new RuntimeException('Logbook harian tidak ditemukan.');
        }
        if ($daily['status_kehadiran'] !== 'tidak_hadir') {
            throw new RuntimeException('Validasi hanya diperlukan untuk logbook ketidakhadiran.');
        }

        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare(
                'UPDATE logbook_harian
                 SET status_validasi = :status,
                     catatan_validasi = :catatan,
                     divalidasi_oleh = :user_id,
                     divalidasi_pada = CURRENT_TIMESTAMP,
                     updated_at = CURRENT_TIMESTAMP
                 WHERE id = :id'
            );
            $stmt->execute([
                'status' => $decision,
                'catatan' => $note !== null && trim($note) !== '' ? trim($note) : null,
                'user_id' => $userId,
                'id' => $dailyId,
            ]);

            $revisionStmt = $pdo->prepare('SELECT versi_terkini FROM logbook_mingguan WHERE id = :id FOR UPDATE');
            $revisionStmt->execute(['id' => (int) $daily['logbook_id']]);
            $currentVersion = $revisionStmt->fetchColumn();
            if ($currentVersion === false) {
                throw new RuntimeException('Minggu logbook tidak ditemukan.');
            }

            $revisionId = $this->insertRevision(
                (int) $daily['logbook_id'],
                (int) $currentVersion + 1,
                $userId,
                'Status validasi ketidakhadiran diperbarui.',
                null
            );
            $this->refreshRevisionSnapshot((int) $daily['logbook_id'], $revisionId);
            $pdo->commit();
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }

        (new AktivitasPengguna())->log(
            $userId,
            'Validasi logbook harian',
            'Ketidakhadiran tanggal ' . $daily['tanggal'] . ' divalidasi dengan keputusan ' . $decision . '.',
            'logbook',
            'logbook_harian',
            $dailyId
        );

        return $stmt->rowCount() > 0;
    }

    public function getReviewQueue(int $userId, string $role): array
    {
        global $pdo;

        if ($role === self::PARTNER) {
            $condition = 'm.user_id = :user_id AND lm.status = \'menunggu_mitra\'';
        } elseif ($role === self::LECTURER) {
            $condition = 'pd.user_id = :user_id AND lm.status = \'menunggu_dosen\'';
        } elseif (in_array($role, self::VALIDATORS, true)) {
            $condition = 'lh.status_validasi = \'menunggu\'';
        } else {
            throw new RuntimeException('Peran review logbook tidak didukung.');
        }

        $sql =
            'SELECT DISTINCT lm.id, lm.penempatan_id, lm.minggu_ke, lm.tanggal_mulai, lm.tanggal_selesai,
                    lm.status, u.name AS mahasiswa_nama, prof.nim,
                    m.nama_perusahaan, f.judul,
                    COUNT(lh.id) FILTER (WHERE lh.status_validasi = \'menunggu\') AS validasi_menunggu
             FROM logbook_mingguan lm
             INNER JOIN penempatan_magang pm ON pm.id = lm.penempatan_id
             INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id
             INNER JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id
             INNER JOIN users u ON u.id = prof.user_id
             INNER JOIN formasi_magang f ON f.id = p.formasi_id
             INNER JOIN mitra m ON m.id = f.mitra_id
             LEFT JOIN profil_dosen pd ON pd.id = pm.dosen_pembimbing_id
             LEFT JOIN logbook_harian lh ON lh.logbook_id = lm.id
             WHERE ' . $condition . '
             GROUP BY lm.id, pm.id, u.id, prof.id, m.id, f.id
             ORDER BY lm.tanggal_mulai ASC, lm.minggu_ke ASC';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDailyForStudent(int $dailyId, int $userId): ?array
    {
        global $pdo;
        $stmt = $pdo->prepare(
            'SELECT lh.*
             FROM logbook_harian lh
             INNER JOIN logbook_mingguan lm ON lm.id = lh.logbook_id
             INNER JOIN penempatan_magang pm ON pm.id = lm.penempatan_id
             INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id
             INNER JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id
             WHERE lh.id = :id AND prof.user_id = :user_id
             LIMIT 1'
        );
        $stmt->execute(['id' => $dailyId, 'user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getDailyForValidator(int $dailyId): ?array
    {
        global $pdo;
        $stmt = $pdo->prepare(
            'SELECT lh.*, lm.minggu_ke, lm.tanggal_mulai AS minggu_mulai, lm.tanggal_selesai AS minggu_selesai,
                    u.name AS mahasiswa_nama, prof.nim, m.nama_perusahaan
             FROM logbook_harian lh
             INNER JOIN logbook_mingguan lm ON lm.id = lh.logbook_id
             INNER JOIN penempatan_magang pm ON pm.id = lm.penempatan_id
             INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id
             INNER JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id
             INNER JOIN users u ON u.id = prof.user_id
             INNER JOIN formasi_magang f ON f.id = p.formasi_id
             INNER JOIN mitra m ON m.id = f.mitra_id
             WHERE lh.id = :id
             LIMIT 1'
        );
        $stmt->execute(['id' => $dailyId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getSignatureForUser(int $signatureId, int $userId, string $role): ?array
    {
        global $pdo;
        $stmt = $pdo->prepare(
            'SELECT ltt.*, lm.id AS logbook_id, lm.penempatan_id,
                    m.user_id AS mitra_user_id, pd.user_id AS dosen_user_id,
                    prof.user_id AS mahasiswa_user_id
             FROM logbook_tanda_tangan ltt
             INNER JOIN logbook_revisi lr ON lr.id = ltt.revisi_id
             INNER JOIN logbook_mingguan lm ON lm.id = lr.logbook_id
             INNER JOIN penempatan_magang pm ON pm.id = lm.penempatan_id
             INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id
             INNER JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id
             INNER JOIN formasi_magang f ON f.id = p.formasi_id
             INNER JOIN mitra m ON m.id = f.mitra_id
             LEFT JOIN profil_dosen pd ON pd.id = pm.dosen_pembimbing_id
             WHERE ltt.id = :id
             LIMIT 1'
        );
        $stmt->execute(['id' => $signatureId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        $allowed = match ($role) {
            'mahasiswa' => (int) $row['mahasiswa_user_id'] === $userId,
            'mitra' => (int) $row['mitra_user_id'] === $userId,
            'dosen' => (int) ($row['dosen_user_id'] ?? 0) === $userId,
            'tendik', 'koordinator_magang' => true,
            default => false,
        };

        return $allowed ? $row : null;
    }

    public function getEvidenceForUser(int $dailyId, int $userId, string $role): ?array
    {
        global $pdo;
        $stmt = $pdo->prepare(
            'SELECT lh.id, lh.bukti_path,
                    m.user_id AS mitra_user_id, pd.user_id AS dosen_user_id,
                    prof.user_id AS mahasiswa_user_id
             FROM logbook_harian lh
             INNER JOIN logbook_mingguan lm ON lm.id = lh.logbook_id
             INNER JOIN penempatan_magang pm ON pm.id = lm.penempatan_id
             INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id
             INNER JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id
             INNER JOIN formasi_magang f ON f.id = p.formasi_id
             INNER JOIN mitra m ON m.id = f.mitra_id
             LEFT JOIN profil_dosen pd ON pd.id = pm.dosen_pembimbing_id
             WHERE lh.id = :id
             LIMIT 1'
        );
        $stmt->execute(['id' => $dailyId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        $allowed = match ($role) {
            'mahasiswa' => (int) $row['mahasiswa_user_id'] === $userId,
            'mitra' => (int) $row['mitra_user_id'] === $userId,
            'dosen' => (int) ($row['dosen_user_id'] ?? 0) === $userId,
            'tendik', 'koordinator_magang' => true,
            default => false,
        };

        return $allowed ? $row : null;
    }

    public function getEvidenceAbsolutePath(string $relativePath): ?string
    {
        $path = $this->privateAbsolutePath($relativePath);
        return is_file($path) ? $path : null;
    }

    public function getSignatureAbsolutePath(string $relativePath): ?string
    {
        $path = $this->privateAbsolutePath($relativePath);
        return is_file($path) ? $path : null;
    }

    private function hasPreviousSignature(int $weekId, int $currentVersion): bool
    {
        global $pdo;
        $stmt = $pdo->prepare(
            'SELECT EXISTS(
                SELECT 1
                FROM logbook_tanda_tangan ltt
                INNER JOIN logbook_revisi lr ON lr.id = ltt.revisi_id
                WHERE lr.logbook_id = :logbook_id AND lr.nomor_versi < :current_version
            )'
        );
        $stmt->execute(['logbook_id' => $weekId, 'current_version' => $currentVersion]);
        return (bool) $stmt->fetchColumn();
    }

    public function getCurrentSignatures(int $weekId): array
    {
        global $pdo;
        $stmt = $pdo->prepare(
            'SELECT ltt.*, u.name AS penanda_tangan_nama, u.role AS penanda_tangan_role
             FROM logbook_tanda_tangan ltt
             INNER JOIN logbook_revisi lr ON lr.id = ltt.revisi_id
             INNER JOIN logbook_mingguan lm ON lm.id = lr.logbook_id AND lm.versi_terkini = lr.nomor_versi
             INNER JOIN users u ON u.id = ltt.penanda_tangan_id
             WHERE ltt.revisi_id = lr.id AND lm.id = :week_id
             ORDER BY CASE ltt.tahap WHEN \'mahasiswa\' THEN 1 WHEN \'mitra\' THEN 2 WHEN \'dosen\' THEN 3 ELSE 4 END'
        );
        $stmt->execute(['week_id' => $weekId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];
        foreach ($rows as $row) {
            $result[$row['tahap']] = $row;
        }
        return $result;
    }

    public function getCompleteness(array $week): array
    {
        $start = new DateTimeImmutable((string) $week['tanggal_mulai']);
        $end = new DateTimeImmutable((string) $week['tanggal_selesai']);
        $expected = [];
        for ($date = $start; $date <= $end; $date = $date->modify('+1 day')) {
            $expected[] = $date->format('Y-m-d');
        }

        $daily = $week['daily'] ?? [];
        $indexed = [];
        foreach ($daily as $item) {
            $indexed[(string) $item['tanggal']] = $item;
        }

        $missing = [];
        foreach ($expected as $date) {
            if (!isset($indexed[$date])) {
                $missing[] = $date;
            }
        }

        $pendingValidation = 0;
        $rejectedValidation = 0;
        foreach ($daily as $item) {
            if (($item['status_validasi'] ?? '') === 'menunggu') {
                $pendingValidation++;
            }
            if (($item['status_validasi'] ?? '') === 'ditolak') {
                $rejectedValidation++;
            }
        }

        return [
            'expected' => count($expected),
            'filled' => count($indexed),
            'missing' => $missing,
            'pending_validation' => $pendingValidation,
            'rejected_validation' => $rejectedValidation,
            'complete' => count($missing) === 0 && $pendingValidation === 0 && $rejectedValidation === 0,
            'period_ended' => new DateTimeImmutable('today') >= $end,
        ];
    }

    public function statusLabel(string $status): string
    {
        return match ($status) {
            'draft' => 'Belum ditandatangani mahasiswa',
            'menunggu_mitra' => 'Menunggu tanda tangan mitra',
            'menunggu_dosen' => 'Menunggu tanda tangan dosen',
            'disetujui' => 'Selesai ditandatangani',
            'perlu_revisi' => 'Perlu diperbaiki',
            'ditolak' => 'Ditolak',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }

    private function assertStudentCanEdit(array $week): void
    {
        if (in_array($week['status'], ['menunggu_dosen', 'disetujui'], true)) {
            throw new RuntimeException('Logbook terkunci karena mitra sudah menandatangani. Data tidak dapat diubah lagi.');
        }
    }

    private function assertSignatureWorkflow(array $week, string $role): void
    {
        $today = new DateTimeImmutable('today');
        $end = new DateTimeImmutable((string) $week['tanggal_selesai']);
        $complete = $week['completeness'];

        if ($role === self::STUDENT) {
            if (!in_array($week['status'], ['draft', 'perlu_revisi'], true)) {
                throw new RuntimeException('Minggu ini belum berada pada tahap tanda tangan mahasiswa.');
            }
            if ($today < $end) {
                throw new RuntimeException('Minggu logbook belum selesai. Tanda tangan mahasiswa tersedia setelah periode minggu berakhir.');
            }
            if (!$complete['complete']) {
                if (!empty($complete['missing'])) {
                    throw new RuntimeException('Lengkapi semua tanggal dalam minggu ini sebelum tanda tangan.');
                }
                if ($complete['pending_validation'] > 0) {
                    throw new RuntimeException('Masih ada ketidakhadiran yang menunggu validasi Tendik/Koordinator.');
                }
                throw new RuntimeException('Masih ada data logbook yang belum memenuhi syarat.');
            }
        } elseif ($role === self::PARTNER) {
            if ($week['status'] !== 'menunggu_mitra') {
                throw new RuntimeException('Minggu ini belum tersedia untuk tanda tangan mitra.');
            }
        } elseif ($role === self::LECTURER) {
            if ($week['status'] !== 'menunggu_dosen') {
                throw new RuntimeException('Minggu ini belum tersedia untuk tanda tangan dosen pembimbing.');
            }
        }
    }

    private function validateDailyData(array $week, array $data): string
    {
        $date = trim((string) ($data['tanggal'] ?? ''));
        $parsed = DateTime::createFromFormat('Y-m-d', $date);
        if (!$parsed || $parsed->format('Y-m-d') !== $date) {
            throw new InvalidArgumentException('Tanggal aktivitas tidak valid.');
        }

        $start = new DateTimeImmutable((string) $week['tanggal_mulai']);
        $end = new DateTimeImmutable((string) $week['tanggal_selesai']);
        $today = new DateTimeImmutable('today');
        $dateValue = new DateTimeImmutable($date);

        if ($dateValue < $start || $dateValue > $end) {
            throw new InvalidArgumentException('Tanggal aktivitas harus berada di dalam periode minggu logbook.');
        }
        if ($dateValue > $today) {
            throw new InvalidArgumentException('Logbook tidak boleh dibuat untuk tanggal yang belum terjadi.');
        }

        $status = (string) ($data['status_kehadiran'] ?? 'hadir');
        if (!in_array($status, ['hadir', 'tidak_hadir'], true)) {
            throw new InvalidArgumentException('Status kehadiran tidak valid.');
        }

        $kegiatan = trim((string) ($data['kegiatan'] ?? ''));
        if ($kegiatan === '') {
            throw new InvalidArgumentException('Kegiatan wajib diisi. Jika tidak masuk, tuliskan alasan seperti sakit atau izin.');
        }
        if (mb_strlen($kegiatan) > 10000) {
            throw new InvalidArgumentException('Kegiatan terlalu panjang.');
        }

        $alasan = trim((string) ($data['alasan_ketidakhadiran'] ?? ''));
        if ($status === 'tidak_hadir' && $alasan === '') {
            throw new InvalidArgumentException('Alasan ketidakhadiran wajib diisi.');
        }

        foreach (['jam_masuk', 'jam_pulang'] as $field) {
            $value = trim((string) ($data[$field] ?? ''));
            if ($value !== '') {
                $time = DateTime::createFromFormat('H:i', $value);
                if (!$time || $time->format('H:i') !== $value) {
                    throw new InvalidArgumentException('Jam masuk/pulang tidak valid.');
                }
            }
        }

        if (($data['jam_masuk'] ?? '') !== '' && ($data['jam_pulang'] ?? '') !== '' && $data['jam_pulang'] < $data['jam_masuk']) {
            throw new InvalidArgumentException('Jam pulang tidak boleh lebih awal dari jam masuk.');
        }

        return $date;
    }

    private function getDailyByDate(int $weekId, string $date): ?array
    {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM logbook_harian WHERE logbook_id = :logbook_id AND tanggal = :tanggal LIMIT 1');
        $stmt->execute(['logbook_id' => $weekId, 'tanggal' => $date]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    private function insertRevision(int $weekId, int $version, int $userId, ?string $note, ?string $snapshotData): int
    {
        global $pdo;
        $stmt = $pdo->prepare(
            'SELECT aktivitas, hasil_pekerjaan, kendala, rencana_selanjutnya
             FROM logbook_revisi
             WHERE logbook_id = :id
             ORDER BY nomor_versi DESC LIMIT 1'
        );
        $stmt->execute(['id' => $weekId]);
        $previous = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

        $insert = $pdo->prepare(
            'INSERT INTO logbook_revisi
                (logbook_id, nomor_versi, aktivitas, hasil_pekerjaan, kendala, rencana_selanjutnya,
                 catatan_revisi, dibuat_oleh, snapshot_data, snapshot_hash)
             VALUES (:logbook_id, :version, :aktivitas, :hasil, :kendala, :rencana,
                     :catatan, :user_id, :snapshot_data, :snapshot_hash)
             RETURNING id'
        );

        $hash = $snapshotData !== null ? hash('sha256', $snapshotData) : null;
        $insert->execute([
            'logbook_id' => $weekId,
            'version' => $version,
            'aktivitas' => $previous['aktivitas'] ?? '',
            'hasil' => $previous['hasil_pekerjaan'] ?? null,
            'kendala' => $previous['kendala'] ?? null,
            'rencana' => $previous['rencana_selanjutnya'] ?? null,
            'catatan' => $note,
            'user_id' => $userId,
            'snapshot_data' => $snapshotData,
            'snapshot_hash' => $hash,
        ]);

        $id = (int) $insert->fetchColumn();
        $update = $pdo->prepare("UPDATE logbook_mingguan SET versi_terkini = :version, status = 'draft', updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        $update->execute(['version' => $version, 'id' => $weekId]);

        return $id;
    }

    private function createRevisionFromCurrent(int $weekId, int $userId, string $note): int
    {
        global $pdo;
        $stmt = $pdo->prepare('SELECT versi_terkini FROM logbook_mingguan WHERE id = :id FOR UPDATE');
        $stmt->execute(['id' => $weekId]);
        $currentVersion = $stmt->fetchColumn();
        if ($currentVersion === false) {
            throw new RuntimeException('Logbook tidak ditemukan.');
        }

        return $this->insertRevision($weekId, (int) $currentVersion + 1, $userId, $note, null);
    }

    private function refreshRevisionSnapshot(int $weekId, int $revisionId): void
    {
        global $pdo;

        $weekStmt = $pdo->prepare(
            'SELECT id, penempatan_id, minggu_ke, tanggal_mulai, tanggal_selesai
             FROM logbook_mingguan WHERE id = :id LIMIT 1'
        );
        $weekStmt->execute(['id' => $weekId]);
        $week = $weekStmt->fetch(PDO::FETCH_ASSOC);
        if (!$week) {
            throw new RuntimeException('Minggu logbook tidak ditemukan.');
        }

        $daily = $this->getDailyEntries($weekId);
        $snapshot = [
            'week' => [
                'id' => (int) $week['id'],
                'penempatan_id' => (int) $week['penempatan_id'],
                'minggu_ke' => (int) $week['minggu_ke'],
                'tanggal_mulai' => $week['tanggal_mulai'],
                'tanggal_selesai' => $week['tanggal_selesai'],
            ],
            'daily' => array_map(static function (array $item): array {
                return [
                    'tanggal' => $item['tanggal'],
                    'jam_masuk' => $item['jam_masuk'],
                    'jam_pulang' => $item['jam_pulang'],
                    'kegiatan' => $item['kegiatan'],
                    'status_kehadiran' => $item['status_kehadiran'],
                    'alasan_ketidakhadiran' => $item['alasan_ketidakhadiran'],
                    'status_validasi' => $item['status_validasi'],
                ];
            }, $daily),
        ];

        $json = json_encode($snapshot, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION | JSON_THROW_ON_ERROR);
        $hash = hash('sha256', $json);

        $stmt = $pdo->prepare('UPDATE logbook_revisi SET snapshot_data = :data, snapshot_hash = :hash WHERE id = :id');
        $stmt->execute(['data' => $json, 'hash' => $hash, 'id' => $revisionId]);
    }

    private function getCurrentSnapshotHash(int $weekId): ?string
    {
        global $pdo;
        $stmt = $pdo->prepare(
            'SELECT lr.snapshot_hash
             FROM logbook_revisi lr
             INNER JOIN logbook_mingguan lm ON lm.id = lr.logbook_id AND lm.versi_terkini = lr.nomor_versi
             WHERE lm.id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $weekId]);
        $value = $stmt->fetchColumn();
        return $value !== false ? (string) $value : null;
    }

    private function getCurrentRevisionId(int $weekId): ?int
    {
        global $pdo;
        $stmt = $pdo->prepare(
            'SELECT lr.id
             FROM logbook_revisi lr
             INNER JOIN logbook_mingguan lm ON lm.id = lr.logbook_id AND lm.versi_terkini = lr.nomor_versi
             WHERE lm.id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $weekId]);
        $value = $stmt->fetchColumn();
        return $value !== false ? (int) $value : null;
    }

    private function storeSignature(string $dataUri, int $userId, int $weekId, string $role): string
    {
        if (!preg_match('#^data:image/png;base64,#', $dataUri)) {
            throw new InvalidArgumentException('Tanda tangan harus berupa PNG dari area tanda tangan.');
        }

        $encoded = substr($dataUri, strpos($dataUri, ',') + 1);
        $binary = base64_decode($encoded, true);
        if ($binary === false || strlen($binary) < 100 || strlen($binary) > 1024 * 1024) {
            throw new InvalidArgumentException('Data tanda tangan tidak valid atau terlalu besar.');
        }

        $imageInfo = @getimagesizefromstring($binary);
        if (!$imageInfo || ($imageInfo['mime'] ?? '') !== 'image/png') {
            throw new InvalidArgumentException('Format tanda tangan tidak valid.');
        }

        $directory = $this->privateRoot() . '/logbook/signatures';
        if (!is_dir($directory) && !mkdir($directory, 0700, true) && !is_dir($directory)) {
            throw new RuntimeException('Folder penyimpanan tanda tangan tidak dapat dibuat.');
        }

        $filename = $weekId . '_' . $userId . '_' . $role . '_' . bin2hex(random_bytes(10)) . '.png';
        $absolute = $directory . '/' . $filename;
        if (file_put_contents($absolute, $binary, LOCK_EX) === false) {
            throw new RuntimeException('Tanda tangan gagal disimpan.');
        }

        return 'private/logbook/signatures/' . $filename;
    }

    private function assertSignatureData(string $signatureData): void
    {
        if (trim($signatureData) === '') {
            throw new InvalidArgumentException('Tanda tangan wajib dibuat.');
        }
    }

    private function storeEvidence(array $file, int $userId, int $weekId, string $date): string
    {
        $error = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($error !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Bukti ketidakhadiran gagal diunggah.');
        }

        $size = (int) ($file['size'] ?? 0);
        if ($size <= 0 || $size > 5 * 1024 * 1024) {
            throw new InvalidArgumentException('Bukti ketidakhadiran maksimal 5 MB.');
        }

        $tmp = (string) ($file['tmp_name'] ?? '');
        if (!is_uploaded_file($tmp)) {
            throw new InvalidArgumentException('File bukti tidak valid.');
        }

        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($tmp);
        $allowed = [
            'application/pdf' => 'pdf',
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
        ];
        if (!isset($allowed[$mime])) {
            throw new InvalidArgumentException('Bukti harus berupa PDF, JPG, atau PNG.');
        }

        $directory = $this->privateRoot() . '/logbook/evidence';
        if (!is_dir($directory) && !mkdir($directory, 0700, true) && !is_dir($directory)) {
            throw new RuntimeException('Folder bukti tidak dapat dibuat.');
        }

        $filename = $weekId . '_' . $userId . '_' . str_replace('-', '', $date) . '_' . bin2hex(random_bytes(8)) . '.' . $allowed[$mime];
        $absolute = $directory . '/' . $filename;
        if (!move_uploaded_file($tmp, $absolute)) {
            throw new RuntimeException('Bukti ketidakhadiran gagal disimpan.');
        }

        return 'private/logbook/evidence/' . $filename;
    }

    private function privateRoot(): string
    {
        return dirname(__DIR__) . '/storage';
    }

    private function privateAbsolutePath(string $relativePath): string
    {
        $relativePath = ltrim(str_replace('\\', '/', $relativePath), '/');
        if (!str_starts_with($relativePath, 'private/logbook/')) {
            throw new RuntimeException('Path file logbook tidak valid.');
        }
        return $this->privateRoot() . '/' . $relativePath;
    }

    private function deletePrivateFile(string $relativePath): void
    {
        try {
            $absolute = $this->privateAbsolutePath($relativePath);
            if (is_file($absolute)) {
                @unlink($absolute);
            }
        } catch (Throwable $e) {
            error_log('Gagal menghapus file privat logbook: ' . $e->getMessage());
        }
    }
}
