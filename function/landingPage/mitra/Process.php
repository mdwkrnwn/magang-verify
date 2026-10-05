<?php

// Butuh: $mitra, $fQ, $fBidang, $fLokasi, $fSkema (dari Filter.php)
// Aturan: pilihan dalam satu kelompok = ATAU, antar kelompok = DAN.
$hasil = array_values(array_filter($mitra, function ($m) use ($fQ, $fBidang, $fLokasi, $fSkema) {
    return ($fQ === '' || stripos($m['nama'] . ' ' . $m['deskripsi'], $fQ) !== false)
        && (!$fBidang || array_intersect($fBidang, $m['bidang']))
        && (!$fLokasi || in_array($m['lokasi'], $fLokasi, true))
        && (!$fSkema  || array_intersect($fSkema, $m['skema']));
}));


/*
|--------------------------------------------------------------------------
| Pagination (6 mitra per halaman)
|--------------------------------------------------------------------------
*/

$perPage = 6;
$total   = count($hasil);
$pages   = max(1, (int) ceil($total / $perPage));
$page    = min($pages, max(1, (int) ($_GET['page'] ?? 1)));
$offset  = ($page - 1) * $perPage;

$tampil  = array_slice($hasil, $offset, $perPage);

$dari    = $total ? $offset + 1 : 0;
$sampai  = $offset + count($tampil);