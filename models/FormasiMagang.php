<?php

require_once __DIR__ . '/../config/database.php';

class FormasiMagang
{
    public function getAll(array $filter = []): array
    {
        global $pdo;
        $sql = $this->baseSelect() . " WHERE f.status = 'dibuka'
            AND m.status_verifikasi = 'terverifikasi' AND m.is_active = TRUE
            AND f.jumlah_diterima < f.jumlah_kuota";
        $params = [];
        if (($filter['q'] ?? '') !== '') {
            $sql .= " AND (f.judul ILIKE :q OR f.deskripsi ILIKE :q OR f.bidang ILIKE :q OR m.nama_perusahaan ILIKE :q OR f.lokasi_magang ILIKE :q)";
            $params['q'] = '%' . trim($filter['q']) . '%';
        }
        if (($filter['lokasi'] ?? '') !== '') {
            $sql .= " AND (COALESCE(f.lokasi_magang, '') ILIKE :lokasi OR COALESCE(m.kota, '') ILIKE :lokasi OR COALESCE(m.provinsi, '') ILIKE :lokasi)";
            $params['lokasi'] = '%' . trim($filter['lokasi']) . '%';
        }
        if (($filter['sistem_kerja'] ?? '') !== '') {
            $sql .= ' AND f.sistem_kerja = :sistem_kerja';
            $params['sistem_kerja'] = $filter['sistem_kerja'];
        }
        if (($filter['tahun_akademik'] ?? '') !== '') {
            $sql .= ' AND f.tahun_akademik = :tahun_akademik';
            $params['tahun_akademik'] = $filter['tahun_akademik'];
        }
        $sql .= ' ORDER BY f.created_at DESC, f.id DESC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return array_map(fn($row) => $this->formatFormasi($row), $stmt->fetchAll());
    }

    public function getById(int $id, bool $onlyOpen = true): ?array
    {
        global $pdo;
        $sql = $this->baseSelect() . ' WHERE f.id = :id AND m.status_verifikasi = \'terverifikasi\' AND m.is_active = TRUE';
        if ($onlyOpen) {
            $sql .= " AND f.status = 'dibuka' AND f.jumlah_diterima < f.jumlah_kuota";
        }
        $stmt = $pdo->prepare($sql . ' LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? $this->formatFormasi($row) : null;
    }

    public function getBySlug(string $slug): ?array
    {
        if (!ctype_digit($slug) || (int) $slug < 1) return null;
        return $this->getById((int) $slug, false);
    }

    public function getFilterOptions(): array
    {
        global $pdo;
        $stmt = $pdo->query("SELECT DISTINCT COALESCE(NULLIF(f.lokasi_magang, ''), NULLIF(m.kota, ''), NULLIF(m.provinsi, '')) AS lokasi FROM formasi_magang f JOIN mitra m ON m.id=f.mitra_id WHERE f.status='dibuka' AND m.status_verifikasi='terverifikasi' AND m.is_active=TRUE AND f.jumlah_diterima < f.jumlah_kuota ORDER BY lokasi");
        $lokasi = array_values(array_filter(array_column($stmt->fetchAll(), 'lokasi')));
        $stmt = $pdo->query("SELECT DISTINCT tahun_akademik FROM formasi_magang f JOIN mitra m ON m.id=f.mitra_id WHERE f.status='dibuka' AND m.status_verifikasi='terverifikasi' AND m.is_active=TRUE AND f.jumlah_diterima < f.jumlah_kuota ORDER BY tahun_akademik DESC");
        return ['lokasi' => $lokasi, 'tahun_akademik' => array_column($stmt->fetchAll(), 'tahun_akademik')];
    }

    private function baseSelect(): string
    {
        return "SELECT f.id, f.mitra_id, f.judul, f.deskripsi, f.bidang, f.jumlah_kuota,
            f.jumlah_diterima, f.persyaratan, f.lokasi_magang, f.sistem_kerja,
            f.tanggal_mulai, f.tanggal_selesai, f.tahun_akademik, f.status, f.created_at,
            m.nama_perusahaan, m.kategori, m.kota, m.provinsi, m.alamat,
            m.website, m.deskripsi AS deskripsi_mitra
            FROM formasi_magang f JOIN mitra m ON m.id = f.mitra_id";
    }

    private function formatFormasi(array $row): array
    {
        $available = max(0, (int)$row['jumlah_kuota'] - (int)$row['jumlah_diterima']);
        $start = new DateTime($row['tanggal_mulai']);
        $end = new DateTime($row['tanggal_selesai']);
        $diff = $start->diff($end);
        $months = ($diff->y * 12) + $diff->m + ($diff->d > 0 ? 1 : 0);
        $location = trim((string)($row['lokasi_magang'] ?? ''));
        if ($location === '') $location = trim(implode(', ', array_filter([$row['kota'] ?? null, $row['provinsi'] ?? null])));
        return [
            'id' => (int)$row['id'], 'slug' => (string)$row['id'], 'mitra_id' => (int)$row['mitra_id'],
            'perusahaan' => $row['nama_perusahaan'], 'posisi' => $row['judul'],
            'lokasi' => $location !== '' ? $location : 'Lokasi belum ditentukan',
            'kota' => $row['kota'] ?? '', 'durasi' => max(1, $months) . ' Bulan',
            'status' => $available > 0 ? 'tersedia' : 'penuh',
            'status_formasi' => $row['status'], 'kuota' => $available,
            'kuota_total' => (int)$row['jumlah_kuota'], 'jumlah_diterima' => (int)$row['jumlah_diterima'],
            'periode' => date('d M Y', strtotime($row['tanggal_mulai'])) . ' - ' . date('d M Y', strtotime($row['tanggal_selesai'])),
            'tanggal_mulai' => $row['tanggal_mulai'], 'tanggal_selesai' => $row['tanggal_selesai'],
            'prodi' => [], 'keahlian' => [], 'deskripsi' => $row['deskripsi'],
            'persyaratan' => array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $row['persyaratan'] ?? '')))),
            'bidang' => $row['bidang'] ?? '', 'sistem_kerja' => $row['sistem_kerja'],
            'tahun_akademik' => $row['tahun_akademik'], 'kategori_mitra' => $row['kategori'],
            'alamat_mitra' => $row['alamat'] ?? '', 'website_mitra' => $row['website'] ?? '',
            'deskripsi_mitra' => $row['deskripsi_mitra'] ?? '', 'batas_daftar' => null,
        ];
    }
}
