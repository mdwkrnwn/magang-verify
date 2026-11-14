<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';

final class KoordinatorMitra
{
    private PDO $db;

    public function __construct()
    {
        global $pdo;
        if (!$pdo instanceof PDO) {
            throw new RuntimeException('Koneksi database tidak tersedia.');
        }
        $this->db = $pdo;
    }

    public function getAllActive(): array
    {
        $sql = "SELECT id, user_id, nama_perusahaan, kode_perusahaan, kategori,
                       bidang_usaha, deskripsi, email, no_telepon, website,
                       provinsi, kota, alamat, is_active, created_at, updated_at
                FROM mitra
                WHERE is_active = TRUE
                ORDER BY created_at DESC, id DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT id, nama_perusahaan, kode_perusahaan,
                kategori, bidang_usaha, deskripsi, email, no_telepon, website,
                provinsi, kota, alamat, is_active
            FROM mitra WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO mitra (
            nama_perusahaan, kode_perusahaan, kategori, bidang_usaha, deskripsi,
            email, no_telepon, website, provinsi, kota, alamat
        ) VALUES (
            :nama_perusahaan, :kode_perusahaan, :kategori, :bidang_usaha,
            :deskripsi, :email, :no_telepon, :website, :provinsi, :kota, :alamat
        ) RETURNING id");
        $stmt->execute($this->mapData($data));
        return (int) $stmt->fetchColumn();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE mitra SET
            nama_perusahaan = :nama_perusahaan,
            kode_perusahaan = :kode_perusahaan,
            kategori = :kategori,
            bidang_usaha = :bidang_usaha,
            deskripsi = :deskripsi,
            email = :email,
            no_telepon = :no_telepon,
            website = :website,
            provinsi = :provinsi,
            kota = :kota,
            alamat = :alamat,
            updated_at = CURRENT_TIMESTAMP
            WHERE id = :id AND is_active = TRUE");
        $params = $this->mapData($data);
        $params[':id'] = $id;
        return $stmt->execute($params);
    }

    public function deactivate(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE mitra
            SET is_active = FALSE, updated_at = CURRENT_TIMESTAMP
            WHERE id = :id AND is_active = TRUE");
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public function activate(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE mitra
            SET is_active = TRUE, updated_at = CURRENT_TIMESTAMP
            WHERE id = :id AND is_active = FALSE");
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }

    private function mapData(array $data): array
    {
        return [
            ':nama_perusahaan' => $data['nama_perusahaan'],
            ':kode_perusahaan' => $data['kode_perusahaan'],
            ':kategori' => $data['kategori'],
            ':bidang_usaha' => $data['bidang_usaha'] !== '' ? $data['bidang_usaha'] : null,
            ':deskripsi' => $data['deskripsi'] !== '' ? $data['deskripsi'] : null,
            ':email' => $data['email'] !== '' ? $data['email'] : null,
            ':no_telepon' => $data['no_telepon'] !== '' ? $data['no_telepon'] : null,
            ':website' => $data['website'] !== '' ? $data['website'] : null,
            ':provinsi' => $data['provinsi'] !== '' ? $data['provinsi'] : null,
            ':kota' => $data['kota'] !== '' ? $data['kota'] : null,
            ':alamat' => $data['alamat'] !== '' ? $data['alamat'] : null,
        ];
    }
}
