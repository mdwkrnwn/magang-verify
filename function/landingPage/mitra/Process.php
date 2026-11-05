
<?php

// Pastikan data tersedia
if (!isset($mitra) || !is_array($mitra)) {
    throw new RuntimeException(
        'Data $mitra tidak tersedia. Periksa Mitra.php dan MitraController.'
    );
}

// Aturan:
// - Pilihan dalam satu kelompok = ATAU
// - Antar kelompok = DAN

$hasil = array_values(array_filter(
    $mitra,
    function ($m) use ($fQ, $fBidang, $fLokasi, $fSkema) {

        if (!is_array($m)) {
            return false;
        }

        $nama      = (string)($m['nama'] ?? '');
        $deskripsi = (string)($m['deskripsi'] ?? '');

        $bidang = (array)($m['bidang'] ?? []);
        $lokasi = (string)($m['lokasi'] ?? '');
        $skema  = (array)($m['skema'] ?? []);

        return
            // Pencarian nama dan deskripsi
            ($fQ === '' ||
                stripos($nama . ' ' . $deskripsi, $fQ) !== false)

            // Filter bidang
            && (empty($fBidang) ||
                !empty(array_intersect($fBidang, $bidang)))

            // Filter lokasi
            && (empty($fLokasi) ||
                in_array($lokasi, $fLokasi, true))

            // Filter skema
            && (empty($fSkema) ||
                !empty(array_intersect($fSkema, $skema)));
    }
));

// ========================================
// PAGINATION
// ========================================

$perPage = 6;
$total   = count($hasil);

$pages = max(1, (int)ceil($total / $perPage));

$page = min(
    $pages,
    max(1, (int)($_GET['page'] ?? 1))
);

$offset = ($page - 1) * $perPage;

$tampil = array_slice($hasil, $offset, $perPage);

$dari   = $total > 0 ? $offset + 1 : 0;
$sampai = $offset + count($tampil);
