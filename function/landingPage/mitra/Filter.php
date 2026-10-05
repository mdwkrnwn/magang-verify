<?php

// Nilai filter dari URL: /mitra?q=cloud&bidang[]=Data+%26+AI&lokasi[]=Malang
$arr = fn($k) => array_values(array_filter((array)($_GET[$k] ?? []), 'is_string'));

$fQ      = trim($_GET['q'] ?? '');
$fBidang = $arr('bidang');
$fLokasi = $arr('lokasi');
$fSkema  = $arr('skema');

// Pilihan checkbox diambil dari data
$opt = function ($key) use ($mitra) {
    $list = array_unique(array_merge(...array_map(fn($m) => (array)$m[$key], $mitra)));
    sort($list);
    return $list;
};

$opts = [
    'bidang' => $opt('bidang'),
    'lokasi' => $opt('lokasi'),
    'skema'  => $opt('skema'),
];