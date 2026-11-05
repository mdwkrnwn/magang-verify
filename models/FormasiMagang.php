
<?php

require_once __DIR__ . '/../config/database.php';

class FormasiMagang
{
    /**
     * Mengambil data formasi dengan pagination.
     */
    public function getPaginated(
        array $filter = [],
        int $page = 1,
        int $perPage = 5
    ): array {
        global $pdo;

        $page = max(1, $page);
        $perPage = max(1, $perPage);
        $offset = ($page - 1) * $perPage;

        [$where, $params] = $this->buildFilter($filter);

        // Hitung total data tanpa mengambil seluruh baris.
        $countSql = "
            SELECT COUNT(*)
            FROM formasi_magang f
            JOIN mitra m ON m.id = f.mitra_id
            {$where}
        ";

        $countStmt = $pdo->prepare($countSql);
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();

        // Ambil hanya data pada halaman yang diminta.
        $sql = $this->baseSelect() . "
            {$where}
            ORDER BY f.created_at DESC, f.id DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $pdo->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        $data = array_map(
            fn($row) => $this->formatFormasi($row),
            $stmt->fetchAll()
        );

        return [
            'data' => $data,
            'total' => $total
        ];
    }

    /**
     * Mengambil opsi filter.
     */
    public function getFilterOptions(array $filter = []): array
    {
        global $pdo;

        [$where, $params] = $this->buildFilter($filter, false);

        $sql = "
            SELECT DISTINCT
                COALESCE(
                    NULLIF(f.lokasi_magang, ''),
                    NULLIF(m.kota, ''),
                    NULLIF(m.provinsi, '')
                ) AS lokasi,
                f.tahun_akademik
            FROM formasi_magang f
            JOIN mitra m ON m.id = f.mitra_id
            {$where}
            ORDER BY lokasi
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();

        $lokasi = [];
        $tahun = [];

        foreach ($rows as $row) {
            if (!empty($row['lokasi'])) {
                $lokasi[] = $row['lokasi'];
            }

            if (!empty($row['tahun_akademik'])) {
                $tahun[] = $row['tahun_akademik'];
            }
        }

        $lokasi = array_values(array_unique($lokasi));
        $tahun = array_values(array_unique($tahun));

        rsort($tahun);

        return [
            'lokasi' => $lokasi,
            'tahun_akademik' => $tahun
        ];
    }

    /**
     * Mengambil pilihan durasi berdasarkan filter pencarian.
     */
    public function getDurasiOptions(array $filter = []): array
    {
        global $pdo;

        [$where, $params] = $this->buildFilter($filter, false);

        $sql = "
            SELECT DISTINCT
                GREATEST(
                    1,
                    (
                        EXTRACT(YEAR FROM age(
                            f.tanggal_selesai::date,
                            f.tanggal_mulai::date
                        )) * 12
                        +
                        EXTRACT(MONTH FROM age(
                            f.tanggal_selesai::date,
                            f.tanggal_mulai::date
                        ))
                        +
                        CASE
                            WHEN EXTRACT(DAY FROM age(
                                f.tanggal_selesai::date,
                                f.tanggal_mulai::date
                            )) > 0 THEN 1
                            ELSE 0
                        END
                    )::int
                ) || ' Bulan' AS durasi
            FROM formasi_magang f
            JOIN mitra m ON m.id = f.mitra_id
            {$where}
            ORDER BY durasi
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return array_column($stmt->fetchAll(), 'durasi');
    }

    /**
     * Mengambil satu formasi berdasarkan ID.
     */
    public function getById(int $id, bool $onlyOpen = true): ?array
    {
        global $pdo;

        $sql = $this->baseSelect() . "
            WHERE f.id = :id
            AND m.status_verifikasi = 'terverifikasi'
            AND m.is_active = TRUE
        ";

        if ($onlyOpen) {
            $sql .= "
                AND f.status = 'dibuka'
                AND f.jumlah_diterima < f.jumlah_kuota
            ";
        }

        $stmt = $pdo->prepare($sql . ' LIMIT 1');
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch();

        return $row ? $this->formatFormasi($row) : null;
    }

    public function getBySlug(string $slug): ?array
    {
        if (!ctype_digit($slug) || (int) $slug < 1) {
            return null;
        }

        $formasi = $this->getById((int) $slug, false);

        if (!$formasi || $formasi['status_formasi'] === 'draft') {
            return null;
        }

        return $formasi;
    }

    /**
     * Membuat kondisi WHERE dan parameter query.
     */
    private function buildFilter(
        array $filter = [],
        bool $includeDuration = true
    ): array {
        $conditions = [
            "f.status = 'dibuka'",
            "m.status_verifikasi = 'terverifikasi'",
            "m.is_active = TRUE",
            "f.jumlah_diterima < f.jumlah_kuota"
        ];

        $params = [];

        if (!empty($filter['q'])) {
            $conditions[] = "(
                f.judul ILIKE :q1
                OR f.deskripsi ILIKE :q2
                OR f.bidang ILIKE :q3
                OR m.nama_perusahaan ILIKE :q4
                OR f.lokasi_magang ILIKE :q5
            )";

            $keyword = '%' . trim($filter['q']) . '%';

            for ($i = 1; $i <= 5; $i++) {
                $params['q' . $i] = $keyword;
            }
        }

        if (!empty($filter['lokasi'])) {
            $conditions[] = "(
                COALESCE(f.lokasi_magang, '') ILIKE :lokasi1
                OR COALESCE(m.kota, '') ILIKE :lokasi2
                OR COALESCE(m.provinsi, '') ILIKE :lokasi3
            )";

            $keyword = '%' . trim($filter['lokasi']) . '%';

            $params['lokasi1'] = $keyword;
            $params['lokasi2'] = $keyword;
            $params['lokasi3'] = $keyword;
        }

        if (!empty($filter['sistem_kerja'])) {
            $conditions[] = 'f.sistem_kerja = :sistem_kerja';
            $params['sistem_kerja'] = $filter['sistem_kerja'];
        }

        if (!empty($filter['tahun_akademik'])) {
            $conditions[] = 'f.tahun_akademik = :tahun_akademik';
            $params['tahun_akademik'] = $filter['tahun_akademik'];
        }

        if ($includeDuration && !empty($filter['durasi'])) {
            $conditions[] = "
                (
                    GREATEST(
                        1,
                        (
                            EXTRACT(YEAR FROM age(
                                f.tanggal_selesai::date,
                                f.tanggal_mulai::date
                            )) * 12
                            +
                            EXTRACT(MONTH FROM age(
                                f.tanggal_selesai::date,
                                f.tanggal_mulai::date
                            ))
                            +
                            CASE
                                WHEN EXTRACT(DAY FROM age(
                                    f.tanggal_selesai::date,
                                    f.tanggal_mulai::date
                                )) > 0 THEN 1
                                ELSE 0
                            END
                        )::int
                    ) || ' Bulan'
                ) = :durasi
            ";

            $params['durasi'] = $filter['durasi'];
        }

        if ($includeDuration && !empty($filter['status'])) {
            $conditions[] = "
                CASE
                    WHEN f.status = 'dibuka'
                    AND f.jumlah_diterima < f.jumlah_kuota
                    THEN 'tersedia'
                    ELSE f.status
                END = :status
            ";

            $params['status'] = $filter['status'];
        }

        return [
            'WHERE ' . implode(' AND ', $conditions),
            $params
        ];
    }

    private function baseSelect(): string
    {
        return "
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
                f.created_at,
                m.nama_perusahaan,
                m.kategori,
                m.kota,
                m.provinsi,
                m.alamat,
                m.website,
                m.deskripsi AS deskripsi_mitra
            FROM formasi_magang f
            JOIN mitra m ON m.id = f.mitra_id
        ";
    }

    private function formatFormasi(array $row): array
    {
        $available = max(
            0,
            (int) $row['jumlah_kuota'] - (int) $row['jumlah_diterima']
        );

        $statusFormasi = (string) ($row['status'] ?? '');

        $statusAvailability = $statusFormasi === 'dibuka'
            ? ($available > 0 ? 'tersedia' : 'penuh')
            : $statusFormasi;

        $start = new DateTime($row['tanggal_mulai']);
        $end = new DateTime($row['tanggal_selesai']);

        $diff = $start->diff($end);

        $months = ($diff->y * 12)
            + $diff->m
            + ($diff->d > 0 ? 1 : 0);

        $location = trim((string) ($row['lokasi_magang'] ?? ''));

        if ($location === '') {
            $location = trim(implode(', ', array_filter([
                $row['kota'] ?? null,
                $row['provinsi'] ?? null
            ])));
        }

        return [
            'id' => (int) $row['id'],
            'slug' => (string) $row['id'],
            'mitra_id' => (int) $row['mitra_id'],
            'perusahaan' => $row['nama_perusahaan'],
            'posisi' => $row['judul'],
            'lokasi' => $location !== '' ? $location : 'Lokasi belum ditentukan',
            'kota' => $row['kota'] ?? '',
            'durasi' => max(1, $months) . ' Bulan',
            'status' => $statusAvailability,
            'status_formasi' => $statusFormasi,
            'kuota' => $available,
            'kuota_total' => (int) $row['jumlah_kuota'],
            'jumlah_diterima' => (int) $row['jumlah_diterima'],
            'periode' => date('d M Y', strtotime($row['tanggal_mulai']))
                . ' - '
                . date('d M Y', strtotime($row['tanggal_selesai'])),
            'tanggal_mulai' => $row['tanggal_mulai'],
            'tanggal_selesai' => $row['tanggal_selesai'],
            'prodi' => [],
            'keahlian' => [],
            'deskripsi' => $row['deskripsi'],
            'persyaratan' => array_values(array_filter(
                array_map(
                    'trim',
                    preg_split('/\r\n|\r|\n/', $row['persyaratan'] ?? '')
                )
            )),
            'bidang' => $row['bidang'] ?? '',
            'sistem_kerja' => $row['sistem_kerja'],
            'tahun_akademik' => $row['tahun_akademik'],
            'kategori_mitra' => $row['kategori'],
            'alamat_mitra' => $row['alamat'] ?? '',
            'website_mitra' => $row['website'] ?? '',
            'deskripsi_mitra' => $row['deskripsi_mitra'] ?? '',
            'batas_daftar' => null
        ];
    }
}
