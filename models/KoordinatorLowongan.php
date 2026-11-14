
<?php


require_once __DIR__ . '/../config/database.php';

final class KoordinatorLowongan
{
    private PDO $db;

    private const STATUSES = [
        'draft',
        'dibuka',
        'ditutup',
        'selesai',
        'dibatalkan',
    ];

    private const WORK_SYSTEMS = [
        'onsite',
        'hybrid',
        'remote',
    ];

    public function __construct()
    {
        global $pdo;

        if (!$pdo instanceof PDO) {
            throw new RuntimeException('Koneksi database tidak tersedia.');
        }

        $this->db = $pdo;
    }

    /**
     * Mengambil seluruh lowongan beserta informasi mitra.
     */
    public function getAll(): array
    {
        $sql = "
            SELECT
                f.id,
                f.mitra_id,
                f.judul,
                f.deskripsi,
                f.bidang,
                f.jumlah_kuota,
                f.jumlah_diterima,
                f.persyaratan,
                f.lokasi_magang,
                f.sistem_kerja,
                f.tanggal_mulai,
                f.tanggal_selesai,
                f.tahun_akademik,
                f.status,
                f.dibuat_oleh,
                f.created_at,
                f.updated_at,
                m.nama_perusahaan,
                m.kode_perusahaan,
                m.is_active AS mitra_is_active
            FROM formasi_magang f
            INNER JOIN mitra m ON m.id = f.mitra_id
            ORDER BY f.created_at DESC, f.id DESC
        ";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Dropdown hanya mengambil mitra aktif.
     */
    public function getActiveMitraOptions(): array
    {
        $sql = "
            SELECT
                id,
                nama_perusahaan,
                kode_perusahaan
            FROM mitra
            WHERE is_active = TRUE
            ORDER BY nama_perusahaan ASC
        ";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil detail satu lowongan.
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT
                f.*,
                m.nama_perusahaan,
                m.kode_perusahaan,
                m.is_active AS mitra_is_active
            FROM formasi_magang f
            INNER JOIN mitra m ON m.id = f.mitra_id
            WHERE f.id = :id
            LIMIT 1
        ");

        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    /**
     * Membuat lowongan baru.
     */
    public function create(array $data, int $userId): int
    {
        $sql = "
            INSERT INTO formasi_magang (
                mitra_id,
                judul,
                deskripsi,
                bidang,
                jumlah_kuota,
                persyaratan,
                lokasi_magang,
                sistem_kerja,
                tanggal_mulai,
                tanggal_selesai,
                tahun_akademik,
                status,
                dibuat_oleh
            )
            SELECT
                m.id,
                :judul,
                :deskripsi,
                :bidang,
                :jumlah_kuota,
                :persyaratan,
                :lokasi_magang,
                :sistem_kerja,
                :tanggal_mulai,
                :tanggal_selesai,
                :tahun_akademik,
                :status,
                :dibuat_oleh
            FROM mitra m
            WHERE m.id = :mitra_id
              AND m.is_active = TRUE
            RETURNING id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':judul' => $data['judul'],
            ':deskripsi' => $data['deskripsi'],
            ':bidang' => $this->nullable($data['bidang']),
            ':jumlah_kuota' => $data['jumlah_kuota'],
            ':persyaratan' => $this->nullable($data['persyaratan']),
            ':lokasi_magang' => $this->nullable($data['lokasi_magang']),
            ':sistem_kerja' => $data['sistem_kerja'],
            ':tanggal_mulai' => $data['tanggal_mulai'],
            ':tanggal_selesai' => $data['tanggal_selesai'],
            ':tahun_akademik' => $data['tahun_akademik'],
            ':status' => $data['status'],
            ':dibuat_oleh' => $userId,
            ':mitra_id' => $data['mitra_id'],
        ]);

        $id = $stmt->fetchColumn();

        if ($id === false) {
            throw new RuntimeException(
                'Mitra tidak ditemukan atau sudah dinonaktifkan.'
            );
        }

        return (int) $id;
    }

    /**
     * Memperbarui informasi lowongan.
     * Jumlah diterima tidak diubah melalui form koordinator.
     */

    public function update(int $id, array $data): bool
    {
        $sql = "
        UPDATE formasi_magang f
        SET
            mitra_id = m.id,
            judul = :judul,
            deskripsi = :deskripsi,
            bidang = :bidang,
            jumlah_kuota = :jumlah_kuota,
            persyaratan = :persyaratan,
            lokasi_magang = :lokasi_magang,
            sistem_kerja = :sistem_kerja,
            tanggal_mulai = :tanggal_mulai,
            tanggal_selesai = :tanggal_selesai,
            tahun_akademik = :tahun_akademik,
            status = :status,
            updated_at = CURRENT_TIMESTAMP
        FROM mitra m
        WHERE f.id = :id
          AND m.id = :mitra_id
          AND m.is_active = TRUE
          AND :jumlah_kuota_check >= f.jumlah_diterima
    ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':judul' => $data['judul'],
            ':deskripsi' => $data['deskripsi'],
            ':bidang' => $this->nullable($data['bidang']),
            ':jumlah_kuota' => $data['jumlah_kuota'],
            ':jumlah_kuota_check' => $data['jumlah_kuota'],
            ':persyaratan' => $this->nullable($data['persyaratan']),
            ':lokasi_magang' => $this->nullable($data['lokasi_magang']),
            ':sistem_kerja' => $data['sistem_kerja'],
            ':tanggal_mulai' => $data['tanggal_mulai'],
            ':tanggal_selesai' => $data['tanggal_selesai'],
            ':tahun_akademik' => $data['tahun_akademik'],
            ':status' => $data['status'],
            ':id' => $id,
            ':mitra_id' => $data['mitra_id'],
        ]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Mengubah status lowongan.
     */
    public function updateStatus(int $id, string $status): bool
    {
        if (!in_array($status, self::STATUSES, true)) {
            throw new InvalidArgumentException('Status lowongan tidak valid.');
        }

        $stmt = $this->db->prepare("
            UPDATE formasi_magang
            SET
                status = :status,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ");

        $stmt->execute([
            ':status' => $status,
            ':id' => $id,
        ]);

        return $stmt->rowCount() > 0;
    }

    private function nullable(string $value): ?string
    {
        return $value !== '' ? $value : null;
    }
}
