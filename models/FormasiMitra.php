<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/AktivitasPengguna.php';

class FormasiMitra
{
    private const SYSTEMS = ['onsite', 'hybrid', 'remote'];
    private const STATUSES = ['draft', 'dibuka', 'ditutup', 'selesai', 'dibatalkan'];

    public function getCompany(int $userId): ?array
    {
        global $pdo;
        $stmt = $pdo->prepare(
            "SELECT id, nama_perusahaan, status_verifikasi, is_active
             FROM mitra WHERE user_id = :user_id LIMIT 1"
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getForCompany(int $userId): array
    {
        global $pdo;
        $stmt = $pdo->prepare(
            "SELECT f.*,
                    COUNT(p.id) AS jumlah_pelamar
             FROM formasi_magang f
             INNER JOIN mitra m ON m.id = f.mitra_id
             LEFT JOIN pendaftaran_magang p ON p.formasi_id = f.id
             WHERE m.user_id = :user_id
             GROUP BY f.id
             ORDER BY f.created_at DESC, f.id DESC"
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOne(int $userId, int $formasiId): ?array
    {
        global $pdo;
        $stmt = $pdo->prepare(
            'SELECT f.* FROM formasi_magang f
             INNER JOIN mitra m ON m.id = f.mitra_id
             WHERE f.id = :id AND m.user_id = :user_id LIMIT 1'
        );
        $stmt->execute(['id' => $formasiId, 'user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(int $userId, array $input): int
    {
        global $pdo;
        $company = $this->assertCompanyCanManage($userId);
        $data = $this->validate($input);
        if (!in_array($data['status'], ['draft', 'dibuka'], true)) {
            throw new InvalidArgumentException('Formasi baru hanya dapat dibuat sebagai draft atau dibuka.');
        }
        $stmt = $pdo->prepare(
            'INSERT INTO formasi_magang
                (mitra_id, judul, deskripsi, bidang, jumlah_kuota, jumlah_diterima,
                 persyaratan, lokasi_magang, sistem_kerja, tanggal_mulai,
                 tanggal_selesai, tahun_akademik, status, dibuat_oleh)
             VALUES (:mitra_id, :judul, :deskripsi, :bidang, :kuota, 0,
                     :persyaratan, :lokasi, :sistem, :mulai, :selesai,
                     :tahun, :status, :user_id)
             RETURNING id'
        );
        $stmt->execute($data + ['mitra_id' => (int) $company['id'], 'user_id' => $userId]);
        $id = (int) $stmt->fetchColumn();
        (new AktivitasPengguna())->log($userId, 'Formasi magang dibuat', 'Formasi ' . $data['judul'] . ' berhasil dibuat.', 'formasi', 'formasi_magang', $id);
        return $id;
    }

    public function update(int $userId, int $formasiId, array $input): bool
    {
        global $pdo;
        $this->assertCompanyCanManage($userId);
        $pdo->beginTransaction();
        try {
            $lock = $pdo->prepare(
                'SELECT f.id, f.jumlah_diterima, f.status
                 FROM formasi_magang f
                 INNER JOIN mitra m ON m.id = f.mitra_id
                 WHERE f.id = :id AND m.user_id = :user_id
                 FOR UPDATE OF f'
            );
            $lock->execute(['id' => $formasiId, 'user_id' => $userId]);
            $formasi = $lock->fetch(PDO::FETCH_ASSOC);
            if (!$formasi) {
                throw new RuntimeException('Formasi tidak ditemukan atau bukan milik perusahaan Anda.');
            }
            $count = $pdo->prepare('SELECT COUNT(*) FROM pendaftaran_magang WHERE formasi_id = :id');
            $count->execute(['id' => $formasiId]);
            if ((int) $count->fetchColumn() > 0) {
                throw new RuntimeException('Formasi yang sudah memiliki pengajuan tidak dapat diubah. Tutup formasi melalui aksi status agar histori pengajuan tetap konsisten.');
            }

            $data = $this->validate($input, (string) $formasi['status']);
            $stmt = $pdo->prepare(
                'UPDATE formasi_magang
                 SET judul = :judul, deskripsi = :deskripsi, bidang = :bidang,
                     jumlah_kuota = :kuota, persyaratan = :persyaratan,
                     lokasi_magang = :lokasi, sistem_kerja = :sistem,
                     tanggal_mulai = :mulai, tanggal_selesai = :selesai,
                     tahun_akademik = :tahun, status = :status,
                     updated_at = CURRENT_TIMESTAMP
                 WHERE id = :id'
            );
            $stmt->execute($data + ['id' => $formasiId]);
            $pdo->commit();
            (new AktivitasPengguna())->log($userId, 'Formasi magang diperbarui', 'Formasi #' . $formasiId . ' berhasil diperbarui.', 'formasi', 'formasi_magang', $formasiId);
            return $stmt->rowCount() === 1;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public function changeStatus(int $userId, int $formasiId, string $status): bool
    {
        global $pdo;
        if (!in_array($status, ['dibuka', 'ditutup'], true)) {
            throw new InvalidArgumentException('Aksi status formasi tidak valid.');
        }
        if ($status === 'dibuka') {
            $this->assertCompanyCanManage($userId);
        }

        $pdo->beginTransaction();
        try {
            $lock = $pdo->prepare(
                'SELECT f.id, f.status, f.jumlah_diterima, f.jumlah_kuota
                 FROM formasi_magang f
                 INNER JOIN mitra m ON m.id = f.mitra_id
                 WHERE f.id = :id AND m.user_id = :user_id
                 FOR UPDATE OF f'
            );
            $lock->execute(['id' => $formasiId, 'user_id' => $userId]);
            $formasi = $lock->fetch(PDO::FETCH_ASSOC);
            if (!$formasi) {
                throw new RuntimeException('Formasi tidak ditemukan atau bukan milik perusahaan Anda.');
            }
            if ($status === 'ditutup' && $formasi['status'] !== 'dibuka') {
                throw new RuntimeException('Hanya formasi yang sedang dibuka yang dapat ditutup.');
            }
            if ($status === 'dibuka' && !in_array($formasi['status'], ['draft', 'ditutup'], true)) {
                throw new RuntimeException('Formasi dengan status saat ini tidak dapat dibuka.');
            }
            if ($status === 'dibuka' && (int) $formasi['jumlah_diterima'] >= (int) $formasi['jumlah_kuota']) {
                throw new RuntimeException('Formasi tidak dapat dibuka karena kuota telah terpenuhi.');
            }

            $update = $pdo->prepare('UPDATE formasi_magang SET status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id');
            $update->execute(['status' => $status, 'id' => $formasiId]);
            $pdo->commit();
            (new AktivitasPengguna())->log($userId, 'Status formasi diperbarui', 'Status formasi #' . $formasiId . ' diubah menjadi ' . $status . '.', 'formasi', 'formasi_magang', $formasiId);
            return $update->rowCount() === 1;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public function delete(int $userId, int $formasiId): bool
    {
        global $pdo;
        $pdo->beginTransaction();
        try {
            $lock = $pdo->prepare(
                'SELECT f.id, f.jumlah_diterima, f.status
                 FROM formasi_magang f
                 INNER JOIN mitra m ON m.id = f.mitra_id
                 WHERE f.id = :id AND m.user_id = :user_id
                 FOR UPDATE OF f'
            );
            $lock->execute(['id' => $formasiId, 'user_id' => $userId]);
            $formasi = $lock->fetch(PDO::FETCH_ASSOC);
            if (!$formasi) {
                throw new RuntimeException('Formasi tidak ditemukan atau bukan milik perusahaan Anda.');
            }
            $count = $pdo->prepare('SELECT COUNT(*) FROM pendaftaran_magang WHERE formasi_id = :id');
            $count->execute(['id' => $formasiId]);
            if ((int) $count->fetchColumn() > 0 || (int) $formasi['jumlah_diterima'] > 0) {
                throw new RuntimeException('Formasi yang memiliki histori pengajuan atau penerimaan tidak dapat dihapus. Tutup formasi untuk mempertahankan histori.');
            }
            $delete = $pdo->prepare('DELETE FROM formasi_magang WHERE id = :id');
            $delete->execute(['id' => $formasiId]);
            if ($delete->rowCount() !== 1) {
                throw new RuntimeException('Formasi tidak dapat dihapus.');
            }
            $pdo->commit();
            (new AktivitasPengguna())->log($userId, 'Formasi magang dihapus', 'Formasi #' . $formasiId . ' tanpa pengajuan dihapus.', 'formasi', 'formasi_magang', $formasiId);
            return true;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    private function assertCompanyCanManage(int $userId): array
    {
        $company = $this->getCompany($userId);
        if (!$company || $company['status_verifikasi'] !== 'terverifikasi'
            || !in_array($company['is_active'], [true, 1, '1', 't', 'true'], true)) {
            throw new RuntimeException('Akun mitra belum terverifikasi atau tidak aktif.');
        }
        return $company;
    }

    private function validate(array $input, ?string $statusOverride = null): array
    {
        $judul = trim((string) ($input['judul'] ?? ''));
        $deskripsi = trim((string) ($input['deskripsi'] ?? ''));
        $bidang = trim((string) ($input['bidang'] ?? ''));
        $kuota = filter_var($input['jumlah_kuota'] ?? null, FILTER_VALIDATE_INT);
        $persyaratan = trim((string) ($input['persyaratan'] ?? ''));
        $lokasi = trim((string) ($input['lokasi_magang'] ?? ''));
        $sistem = (string) ($input['sistem_kerja'] ?? '');
        $mulai = trim((string) ($input['tanggal_mulai'] ?? ''));
        $selesai = trim((string) ($input['tanggal_selesai'] ?? ''));
        $tahun = trim((string) ($input['tahun_akademik'] ?? ''));
        $status = $statusOverride ?? (string) ($input['status'] ?? 'draft');

        if ($judul === '' || mb_strlen($judul) > 200 || $deskripsi === '') {
            throw new InvalidArgumentException('Judul dan deskripsi formasi wajib diisi; judul maksimal 200 karakter.');
        }
        if ($kuota === false || $kuota < 1 || $kuota > 32767) {
            throw new InvalidArgumentException('Kuota harus berupa bilangan bulat positif.');
        }
        if ($bidang !== '' && mb_strlen($bidang) > 150) {
            throw new InvalidArgumentException('Bidang maksimal 150 karakter.');
        }
        if ($lokasi !== '' && mb_strlen($lokasi) > 255) {
            throw new InvalidArgumentException('Lokasi maksimal 255 karakter.');
        }
        if (!in_array($sistem, self::SYSTEMS, true)) {
            throw new InvalidArgumentException('Sistem kerja tidak valid.');
        }
        if (!in_array($status, self::STATUSES, true)) {
            throw new InvalidArgumentException('Status formasi tidak valid.');
        }
        $start = DateTimeImmutable::createFromFormat('!Y-m-d', $mulai);
        $end = DateTimeImmutable::createFromFormat('!Y-m-d', $selesai);
        if (!$start || !$end || $start->format('Y-m-d') !== $mulai || $end->format('Y-m-d') !== $selesai || $end < $start) {
            throw new InvalidArgumentException('Tanggal periode formasi tidak valid.');
        }
        if ($tahun === '' || mb_strlen($tahun) > 20) {
            throw new InvalidArgumentException('Tahun akademik wajib diisi (maksimal 20 karakter).');
        }

        return [
            'judul' => $judul,
            'deskripsi' => $deskripsi,
            'bidang' => $bidang !== '' ? $bidang : null,
            'kuota' => $kuota,
            'persyaratan' => $persyaratan !== '' ? $persyaratan : null,
            'lokasi' => $lokasi !== '' ? $lokasi : null,
            'sistem' => $sistem,
            'mulai' => $mulai,
            'selesai' => $selesai,
            'tahun' => $tahun,
            'status' => $status,
        ];
    }
}
