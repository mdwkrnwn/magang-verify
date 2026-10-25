<?php

require_once __DIR__ . '/../config/database.php';

class PendaftaranMagang
{
    public function getProfilMahasiswa(int $userId): ?array
    {
        global $pdo;
        $stmt = $pdo->prepare('SELECT p.id AS mahasiswa_id, p.nim, p.program_studi, p.angkatan, p.email, p.no_telepon, p.alamat, u.name AS nama FROM users u JOIN profil_mahasiswa p ON p.user_id=u.id WHERE u.id=:id AND u.role=\'mahasiswa\' AND u.is_active=TRUE LIMIT 1');
        $stmt->execute(['id' => $userId]);
        return $stmt->fetch() ?: null;
    }

    public function hasActiveApplication(int $mahasiswaId): bool
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT EXISTS(SELECT 1 FROM pendaftaran_magang WHERE mahasiswa_id=:id AND status_pendaftaran IN ('diajukan','menunggu_persetujuan_dosen','menunggu_respons_mitra','seleksi','diterima','perlu_revisi'))");
        $stmt->execute(['id' => $mahasiswaId]);
        return (bool)$stmt->fetchColumn();
    }

    public function create(int $mahasiswaId, int $formasiId, array $documents): int
    {
        global $pdo;
        $pdo->beginTransaction();
        try {
            $assignmentStmt = $pdo->prepare(
                "SELECT pr.id AS proses_magang_id, pp.id AS penugasan_pembimbing_id,
                        pp.dosen_pembimbing_id, u.name AS dosen_nama, pd.nidn
                 FROM proses_magang pr
                 INNER JOIN penugasan_pembimbing pp
                    ON pp.proses_magang_id = pr.id AND pp.status = 'aktif'
                 INNER JOIN profil_dosen pd ON pd.id = pp.dosen_pembimbing_id
                 INNER JOIN users u ON u.id = pd.user_id
                 WHERE pr.mahasiswa_id = :mahasiswa_id
                   AND pr.status = 'aktif'
                   AND u.role = 'dosen' AND u.is_active = TRUE
                 FOR UPDATE OF pr, pp"
            );
            $assignmentStmt->execute(['mahasiswa_id' => $mahasiswaId]);
            $assignment = $assignmentStmt->fetch(PDO::FETCH_ASSOC);
            if (!$assignment) {
                throw new DomainException('Anda belum memiliki dosen pembimbing yang ditetapkan pengelola untuk proses magang ini.');
            }
            $stmt = $pdo->prepare("SELECT f.id, f.jumlah_kuota, f.jumlah_diterima FROM formasi_magang f JOIN mitra m ON m.id=f.mitra_id WHERE f.id=:id AND f.status='dibuka' AND m.status_verifikasi='terverifikasi' AND m.is_active=TRUE FOR UPDATE OF f");
            $stmt->execute(['id' => $formasiId]);
            $formasi = $stmt->fetch();
            if (!$formasi || (int)$formasi['jumlah_diterima'] >= (int)$formasi['jumlah_kuota']) {
                throw new DomainException('Formasi sudah tidak tersedia atau kuotanya penuh.');
            }
            if ($this->hasActiveApplication($mahasiswaId)) {
                throw new DomainException('Anda masih memiliki pengajuan magang aktif. Selesaikan atau batalkan pengajuan tersebut sebelum mengajukan formasi lain.');
            }
            $stmt = $pdo->prepare("INSERT INTO pendaftaran_magang (mahasiswa_id, formasi_id, proses_magang_id, penugasan_pembimbing_id, dosen_pembimbing_snapshot_id, dosen_pembimbing_snapshot_nama, dosen_pembimbing_snapshot_nidn, status_persetujuan_dosen, status_respons_mitra, status_pendaftaran) VALUES (:mahasiswa_id, :formasi_id, :process_id, :assignment_id, :advisor_id, :advisor_name, :advisor_nidn, 'menunggu', 'menunggu', 'menunggu_persetujuan_dosen') RETURNING id");
            $stmt->execute([
                'mahasiswa_id' => $mahasiswaId,
                'formasi_id' => $formasiId,
                'process_id' => (int) $assignment['proses_magang_id'],
                'assignment_id' => (int) $assignment['penugasan_pembimbing_id'],
                'advisor_id' => (int) $assignment['dosen_pembimbing_id'],
                'advisor_name' => (string) $assignment['dosen_nama'],
                'advisor_nidn' => $assignment['nidn'] !== null ? (string) $assignment['nidn'] : null,
            ]);
            $applicationId = (int)$stmt->fetchColumn();
            $docStmt = $pdo->prepare('INSERT INTO dokumen_pendaftaran_magang (pendaftaran_id, jenis_dokumen, nama_asli, path_file, mime_type, ukuran_bytes) VALUES (:pendaftaran_id, :jenis, :nama, :path, :mime, :size)');
            foreach ($documents as $jenis => $doc) {
                $docStmt->execute(['pendaftaran_id' => $applicationId, 'jenis' => $jenis, 'nama' => $doc['nama_asli'], 'path' => $doc['path_file'], 'mime' => $doc['mime_type'], 'size' => $doc['ukuran_bytes']]);
            }
            $stmt = $pdo->prepare("INSERT INTO riwayat_pendaftaran_magang (pendaftaran_id, status_sebelumnya, status_baru, catatan) VALUES (:id, NULL, 'menunggu_persetujuan_dosen', 'Pengajuan dikirim oleh mahasiswa.')");
            $stmt->execute(['id' => $applicationId]);
            $pdo->commit();
            return $applicationId;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            throw $e;
        }
    }

    public function getByMahasiswa(int $mahasiswaId): array
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT p.id, p.status_pendaftaran, p.tanggal_pengajuan, f.id AS formasi_id, f.judul, f.deskripsi, f.tanggal_mulai, f.tanggal_selesai, m.nama_perusahaan FROM pendaftaran_magang p JOIN formasi_magang f ON f.id=p.formasi_id JOIN mitra m ON m.id=f.mitra_id WHERE p.mahasiswa_id=:id ORDER BY p.tanggal_pengajuan DESC");
        $stmt->execute(['id' => $mahasiswaId]);
        return array_map(fn($r) => $this->formatApplication($r), $stmt->fetchAll());
    }

    public function getDetail(int $applicationId, int $mahasiswaId): ?array
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT p.*, f.id AS formasi_id, f.judul, f.deskripsi, f.tanggal_mulai, f.tanggal_selesai, m.nama_perusahaan FROM pendaftaran_magang p JOIN formasi_magang f ON f.id=p.formasi_id JOIN mitra m ON m.id=f.mitra_id WHERE p.id=:id AND p.mahasiswa_id=:mahasiswa_id LIMIT 1");
        $stmt->execute(['id' => $applicationId, 'mahasiswa_id' => $mahasiswaId]);
        $r = $stmt->fetch();
        if (!$r) return null;
        $r = array_merge($this->formatApplication($r), $r);
        $docs = $pdo->prepare('SELECT jenis_dokumen, nama_asli, path_file, mime_type, ukuran_bytes FROM dokumen_pendaftaran_magang WHERE pendaftaran_id=:id ORDER BY id');
        $docs->execute(['id' => $applicationId]);
        $r['dokumen'] = []; foreach ($docs->fetchAll() as $d) { $r['dokumen'][$d['jenis_dokumen']] = $d['nama_asli']; }
        $history = $pdo->prepare('SELECT status_baru, catatan, waktu_perubahan FROM riwayat_pendaftaran_magang WHERE pendaftaran_id=:id ORDER BY waktu_perubahan');
        $history->execute(['id' => $applicationId]);
        $historyRows = $history->fetchAll();
        $lastIndex = count($historyRows) - 1;
        $r['timeline'] = [];
        foreach ($historyRows as $index => $h) {
            $isRejected = str_starts_with($h['status_baru'], 'ditolak');
            $r['timeline'][] = [
                'title' => $this->statusLabel($h['status_baru']),
                'description' => $h['catatan'] ?: 'Status pengajuan diperbarui.',
                'date' => date('d M Y H:i', strtotime($h['waktu_perubahan'])),
                'status' => $isRejected ? 'rejected' : ($index === $lastIndex ? 'process' : 'completed'),
            ];
        }
        $r['periode'] = date('d M Y', strtotime($r['tanggal_mulai'])) . ' - ' . date('d M Y', strtotime($r['tanggal_selesai']));
        $r['jpl'] = '-';
        return $r;
    }

    private function formatApplication(array $r): array
    {
        $status = $this->statusLabel($r['status_pendaftaran']);
        $class = match ($r['status_pendaftaran']) {
            'diterima', 'selesai' => 'text-emerald-700 bg-emerald-50',
            'ditolak_dosen', 'ditolak_mitra', 'dibatalkan' => 'text-red-700 bg-red-50',
            'perlu_revisi' => 'text-amber-700 bg-amber-50',
            default => 'text-blue-700 bg-blue-50',
        };
        return ['id' => (int)$r['id'], 'slug' => (string)$r['id'], 'perusahaan' => $r['nama_perusahaan'], 'posisi' => $r['judul'], 'tanggal' => date('d M Y', strtotime($r['tanggal_pengajuan'])), 'status' => $status, 'statusClass' => $class, 'periode' => date('d M Y', strtotime($r['tanggal_mulai'])) . ' - ' . date('d M Y', strtotime($r['tanggal_selesai'])), 'deskripsi' => $r['deskripsi'], 'logo' => strtoupper(mb_substr($r['nama_perusahaan'], 0, 2)), 'logoClass' => 'text-sm font-bold text-blue-600'];
    }

    private function statusLabel(string $status): string
    {
        return match ($status) {
            'diajukan' => 'Diajukan', 'menunggu_persetujuan_dosen' => 'Menunggu Persetujuan Dosen',
            'menunggu_respons_mitra' => 'Menunggu Respons Mitra', 'seleksi' => 'Tahap Seleksi',
            'diterima' => 'Diterima', 'ditolak_dosen' => 'Ditolak Dosen', 'ditolak_mitra' => 'Ditolak Mitra',
            'perlu_revisi' => 'Perlu Revisi', 'dibatalkan' => 'Dibatalkan', 'selesai' => 'Selesai', default => ucfirst($status),
        };
    }
}
