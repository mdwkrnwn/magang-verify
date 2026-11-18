<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../function/Helpers.php';

/**
 * Sumber data direktori mitra publik (landing page /mitra dan /mitra/detail).
 *
 * Menggantikan data dummy di data/landingPage/Mitra.php yang dibaca manual
 * oleh controller. Bentuk array yang dikembalikan sengaja dibuat sama dengan
 * bentuk data dummy agar view, filter, dan komponen kartu tidak perlu diubah.
 */
final class MitraPublik
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

    /**
     * Semua mitra aktif, sudah dibentuk sesuai kebutuhan view.
     */
    public function getAllForLanding(): array
    {
        $rows = $this->db->query(
            "SELECT id, nama_perusahaan, kategori, bidang_usaha, deskripsi,
                    email, website, provinsi, kota, alamat,
                    status_verifikasi, diverifikasi_pada
             FROM mitra
             WHERE is_active = TRUE
             ORDER BY nama_perusahaan"
        )->fetchAll(PDO::FETCH_ASSOC);

        if (!$rows) {
            return [];
        }

        $formasi = $this->getFormasiByMitra();

        return array_map(
            fn (array $row): array => $this->buildMitra(
                $row,
                $formasi[(int) $row['id']] ?? []
            ),
            $rows
        );
    }

    /**
     * Satu mitra berdasarkan slug (dipakai halaman detail).
     */
    public function findBySlug(string $slug): ?array
    {
        $slug = trim($slug);

        if ($slug === '') {
            return null;
        }

        foreach ($this->getAllForLanding() as $mitra) {
            if ($mitra['slug'] === $slug) {
                return $mitra;
            }
        }

        return null;
    }

    /**
     * Formasi magang per mitra: [mitra_id => [baris, ...]]
     */
    private function getFormasiByMitra(): array
    {
        $rows = $this->db->query(
            "SELECT f.id, f.mitra_id, f.judul, f.bidang, f.jumlah_kuota,
                    f.jumlah_diterima, f.persyaratan, f.deskripsi,
                    f.lokasi_magang, f.sistem_kerja, f.tanggal_mulai,
                    f.tanggal_selesai, f.tahun_akademik, f.status
             FROM formasi_magang f
             JOIN mitra m ON m.id = f.mitra_id
             WHERE m.is_active = TRUE
             ORDER BY f.id"
        )->fetchAll(PDO::FETCH_ASSOC);

        $grouped = [];

        foreach ($rows as $row) {
            $grouped[(int) $row['mitra_id']][] = $row;
        }

        return $grouped;
    }

    private function buildMitra(array $row, array $formasiRows): array
    {
        $nama   = (string) $row['nama_perusahaan'];
        $kota   = trim((string) ($row['kota'] ?? ''));
        $lokasi = $kota !== '' ? $kota : trim((string) ($row['provinsi'] ?? ''));
        $deskripsi = trim((string) ($row['deskripsi'] ?? ''));

        $bidang = $this->uniqueValues(array_column($formasiRows, 'bidang'));

        if (!$bidang) {
            $bidang = $this->uniqueValues(
                explode(',', (string) ($row['bidang_usaha'] ?? ''))
            );
        }

        $skema = $this->uniqueValues(array_column($formasiRows, 'sistem_kerja'));

        $posisi  = 0;
        $magang  = 0;
        $tahun   = '';
        $formasi = [];

        foreach ($formasiRows as $f) {
            $kuota    = (int) $f['jumlah_kuota'];
            $diterima = (int) $f['jumlah_diterima'];

            $magang += $diterima;

            if (strtolower((string) $f['status']) === 'dibuka') {
                $posisi += $kuota;
            }

            if ($tahun === '' && !empty($f['tahun_akademik'])) {
                $tahun = (string) $f['tahun_akademik'];
            }

            $formasi[] = [
                'posisi'  => (string) $f['judul'],
                'prodi'   => $this->uniqueValues([(string) ($f['bidang'] ?? '')]),
                'kuota'   => $kuota,
                'periode' => $this->periode($f['tanggal_mulai'], $f['tanggal_selesai']),
                'batas'   => '-',
                'jobdesc' => trim((string) ($f['deskripsi'] ?? '')),
                'syarat'  => $this->lines((string) ($f['persyaratan'] ?? '')),
                'seleksi' => 'Seleksi berkas dan wawancara',
            ];
        }

        return [
            'id'             => (int) $row['id'],
            'nama'           => $nama,
            'slug'           => slugify($nama),
            'status'         => $this->statusLabel((string) ($row['status_verifikasi'] ?? '')),
            'kategori'       => (string) ($row['kategori'] ?? ''),
            'skala'          => '',
            'bidang'         => $bidang,
            'skema'          => $skema,
            'lokasi'         => $lokasi,
            'wilayah'        => $lokasi,
            'alamat'         => (string) ($row['alamat'] ?? ''),
            'email'          => (string) ($row['email'] ?? ''),
            'website'        => (string) ($row['website'] ?? ''),
            'pic'            => [],
            'logo'           => '',
            'posisi'         => $posisi,
            'magang'         => $magang,
            'deskripsi'      => $deskripsi,
            'tentang'        => $deskripsi,
            'tahun_akademik' => $tahun !== '' ? $tahun : '-',
            'verifikasi'     => $this->verifikasi($row['diverifikasi_pada'] ?? null),
            'formasi'        => $formasi,
        ];
    }

    private function statusLabel(string $status): string
    {
        return strtolower(trim($status)) === 'terverifikasi'
            ? 'Mitra Terverifikasi'
            : 'Menunggu Verifikasi';
    }

    private function verifikasi($tanggal): string
    {
        if (empty($tanggal)) {
            return '-';
        }

        $waktu = strtotime((string) $tanggal);

        return $waktu ? date('M Y', $waktu) : '-';
    }

    private function periode($mulai, $selesai): string
    {
        if (empty($mulai) && empty($selesai)) {
            return '-';
        }

        $awal   = empty($mulai) ? '-' : date('M Y', (int) strtotime((string) $mulai));
        $akhir  = empty($selesai) ? '-' : date('M Y', (int) strtotime((string) $selesai));

        return $awal === $akhir ? $awal : $awal . ' – ' . $akhir;
    }

    /**
     * Pisahkan teks bebas (satu syarat per baris atau per titik koma).
     *
     * @return string[]
     */
    private function lines(string $text): array
    {
        $text = trim($text);

        if ($text === '') {
            return [];
        }

        return $this->uniqueValues(preg_split('/\r\n|\r|\n|;/', $text) ?: []);
    }

    /**
     * @param array<int, mixed> $values
     * @return string[]
     */
    private function uniqueValues(array $values): array
    {
        $hasil = [];

        foreach ($values as $value) {
            $value = trim((string) $value);

            if ($value !== '') {
                $hasil[$value] = true;
            }
        }

        return array_keys($hasil);
    }
}
