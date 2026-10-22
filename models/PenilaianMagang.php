<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/AktivitasPengguna.php';
require_once __DIR__ . '/PdoParameterBinder.php';

class PenilaianMagang
{
    private const FIELDS = [
        'nilai_kedisiplinan',
        'nilai_komunikasi',
        'nilai_kerja_sama',
        'nilai_tanggung_jawab',
        'nilai_keterampilan',
    ];

    public function getAssignments(int $userId, string $role): array
    {
        global $pdo;
        $this->assertRole($role);
        $ownership = $role === 'dosen' ? 'pd.user_id = :user_id' : 'm.user_id = :user_id';
        $stmt = $pdo->prepare(
            "SELECT pm.id AS penempatan_id, pm.status AS status_penempatan,
                    pm.tanggal_mulai, pm.tanggal_selesai,
                    u.name AS mahasiswa_nama, prof.nim,
                    f.judul, m.nama_perusahaan,
                    n.id AS penilaian_id, n.nilai_kedisiplinan, n.nilai_komunikasi,
                    n.nilai_kerja_sama, n.nilai_tanggung_jawab, n.nilai_keterampilan,
                    n.nilai_akhir, n.catatan, n.status AS status_penilaian,
                    n.dinilai_pada
             FROM penempatan_magang pm
             INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id
             INNER JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id
             INNER JOIN users u ON u.id = prof.user_id
             INNER JOIN formasi_magang f ON f.id = p.formasi_id
             INNER JOIN mitra m ON m.id = f.mitra_id
             LEFT JOIN profil_dosen pd ON pd.id = pm.dosen_pembimbing_id
             LEFT JOIN penilaian_magang n
               ON n.penempatan_id = pm.id AND n.penilai_id = :assessment_user_id AND n.peran_penilai = :role
             WHERE {$ownership}
               AND pm.status IN ('menunggu_penilaian', 'selesai')
             ORDER BY pm.tanggal_selesai DESC, pm.id DESC"
        );
        $stmt->execute(['user_id' => $userId, 'assessment_user_id' => $userId, 'role' => $role]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save(int $userId, string $role, int $placementId, array $input, bool $submit): void
    {
        global $pdo;
        $this->assertRole($role);
        $scores = [];
        foreach (self::FIELDS as $field) {
            $raw = trim((string) ($input[$field] ?? ''));
            if ($raw === '') {
                $scores[$field] = null;
                continue;
            }
            if (!is_numeric($raw) || (float) $raw < 0 || (float) $raw > 100) {
                throw new InvalidArgumentException('Nilai harus berupa angka antara 0 dan 100.');
            }
            $scores[$field] = number_format((float) $raw, 2, '.', '');
        }
        $finalRaw = trim((string) ($input['nilai_akhir'] ?? ''));
        if ($finalRaw !== '' && (!is_numeric($finalRaw) || (float) $finalRaw < 0 || (float) $finalRaw > 100)) {
            throw new InvalidArgumentException('Nilai akhir manual harus berupa angka antara 0 dan 100.');
        }
        $finalScore = $finalRaw === '' ? null : number_format((float) $finalRaw, 2, '.', '');
        $note = trim((string) ($input['catatan'] ?? ''));
        if (mb_strlen($note) > 5000) {
            throw new InvalidArgumentException('Catatan maksimal 5.000 karakter.');
        }

        $ownership = $role === 'dosen' ? 'pd.user_id = :user_id' : 'm.user_id = :user_id';
        $pdo->beginTransaction();
        try {
            $placementStmt = $pdo->prepare(
                "SELECT pm.id, pm.status
                 FROM penempatan_magang pm
                 INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id
                 INNER JOIN formasi_magang f ON f.id = p.formasi_id
                 INNER JOIN mitra m ON m.id = f.mitra_id
                 LEFT JOIN profil_dosen pd ON pd.id = pm.dosen_pembimbing_id
                 WHERE pm.id = :placement_id AND {$ownership}
                 FOR UPDATE OF pm"
            );
            $placementStmt->execute(['placement_id' => $placementId, 'user_id' => $userId]);
            $placement = $placementStmt->fetch(PDO::FETCH_ASSOC);
            if (!$placement) {
                throw new RuntimeException('Penempatan tidak ditemukan atau bukan dalam kewenangan Anda.');
            }
            if (!in_array($placement['status'], ['menunggu_penilaian', 'selesai'], true)) {
                throw new RuntimeException('Penilaian hanya tersedia setelah penempatan memasuki tahap menunggu penilaian atau selesai.');
            }

            $existingStmt = $pdo->prepare(
                'SELECT id, status FROM penilaian_magang
                 WHERE penempatan_id = :placement_id AND penilai_id = :user_id AND peran_penilai = :role
                 FOR UPDATE'
            );
            $existingStmt->execute(['placement_id' => $placementId, 'user_id' => $userId, 'role' => $role]);
            $existing = $existingStmt->fetch(PDO::FETCH_ASSOC);
            if ($existing && !in_array($existing['status'], ['draft', 'perlu_revisi'], true)) {
                throw new RuntimeException('Penilaian yang telah diajukan tidak dapat diubah.');
            }

            $status = $submit ? 'diajukan' : 'draft';
            if ($existing) {
                $stmt = $pdo->prepare(
                    'UPDATE penilaian_magang SET
                        nilai_kedisiplinan = :kedisiplinan,
                        nilai_komunikasi = :komunikasi,
                        nilai_kerja_sama = :kerja_sama,
                        nilai_tanggung_jawab = :tanggung_jawab,
                        nilai_keterampilan = :keterampilan,
                        nilai_akhir = :nilai_akhir,
                        catatan = :catatan,
                        status = :status,
                        dinilai_pada = CASE WHEN :is_submitted THEN CURRENT_TIMESTAMP ELSE NULL END,
                        updated_at = CURRENT_TIMESTAMP
                     WHERE id = :id'
                );
                $params = $this->scoreParams($scores, $finalScore, $note, $status, $submit);
                $params['id'] = (int) $existing['id'];
                PdoParameterBinder::execute($stmt, $params);
                $assessmentId = (int) $existing['id'];
            } else {
                $stmt = $pdo->prepare(
                    'INSERT INTO penilaian_magang
                        (penempatan_id, penilai_id, peran_penilai, nilai_kedisiplinan,
                         nilai_komunikasi, nilai_kerja_sama, nilai_tanggung_jawab,
                         nilai_keterampilan, nilai_akhir, catatan, status, dinilai_pada)
                     VALUES (:placement_id, :user_id, :role, :kedisiplinan,
                             :komunikasi, :kerja_sama, :tanggung_jawab,
                             :keterampilan, :nilai_akhir, :catatan, :status,
                             CASE WHEN :is_submitted THEN CURRENT_TIMESTAMP ELSE NULL END)
                     RETURNING id'
                );
                $params = $this->scoreParams($scores, $finalScore, $note, $status, $submit);
                $params += ['placement_id' => $placementId, 'user_id' => $userId, 'role' => $role];
                PdoParameterBinder::execute($stmt, $params);
                $assessmentId = (int) $stmt->fetchColumn();
            }

            if ($submit) {
                $studentStmt = $pdo->prepare(
                    'SELECT prof.user_id FROM penempatan_magang pm
                     INNER JOIN pendaftaran_magang p ON p.id = pm.pendaftaran_id
                     INNER JOIN profil_mahasiswa prof ON prof.id = p.mahasiswa_id
                     WHERE pm.id = :id'
                );
                $studentStmt->execute(['id' => $placementId]);
                $studentUserId = $studentStmt->fetchColumn();
                if ($studentUserId !== false) {
                    $notification = $pdo->prepare(
                        "INSERT INTO notifikasi (user_id, judul, pesan, jenis, referensi_tabel, referensi_id, tautan)
                         VALUES (:user_id, :judul, :pesan, 'penilaian', 'penilaian_magang', :reference_id, :tautan)"
                    );
                    $notification->execute([
                        'user_id' => (int) $studentUserId,
                        'judul' => 'Penilaian magang dikirim',
                        'pesan' => 'Penilai ' . $role . ' telah mengirim penilaian magang.',
                        'reference_id' => $assessmentId,
                        'tautan' => '/dashboard/mahasiswa',
                    ]);
                }
            }
            $pdo->commit();

            (new AktivitasPengguna())->log(
                $userId,
                $submit ? 'Penilaian magang diajukan' : 'Draft penilaian magang disimpan',
                'Penilaian ' . $role . ' untuk penempatan #' . $placementId . ' berhasil disimpan.',
                'penilaian',
                'penilaian_magang',
                $assessmentId
            );
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    private function scoreParams(array $scores, ?string $finalScore, string $note, string $status, bool $submit): array
    {
        return [
            'kedisiplinan' => $scores['nilai_kedisiplinan'],
            'komunikasi' => $scores['nilai_komunikasi'],
            'kerja_sama' => $scores['nilai_kerja_sama'],
            'tanggung_jawab' => $scores['nilai_tanggung_jawab'],
            'keterampilan' => $scores['nilai_keterampilan'],
            'nilai_akhir' => $finalScore,
            'catatan' => $note !== '' ? $note : null,
            'status' => $status,
            'is_submitted' => $submit,
        ];
    }

    private function assertRole(string $role): void
    {
        if (!in_array($role, ['dosen', 'mitra'], true)) {
            throw new InvalidArgumentException('Role tidak dapat memberikan penilaian magang.');
        }
    }
}
