
<?php

// Nilai filter dari URL
$arr = fn($k) => array_values(
    array_filter((array)($_GET[$k] ?? []), 'is_string')
);

$fQ      = trim((string)($_GET['q'] ?? ''));
$fBidang = $arr('bidang');
$fLokasi = $arr('lokasi');
$fSkema  = $arr('skema');

// Pilihan checkbox dari data mitra
$opt = function ($key) use ($mitra) {
    $values = [];

    foreach ((array)$mitra as $m) {
        if (!is_array($m)) {
            continue;
        }

        $items = $m[$key] ?? [];

        // Mendukung data array maupun string
        foreach ((array)$items as $item) {
            if (is_string($item) && trim($item) !== '') {
                $values[] = trim($item);
            }
        }
    }

    $list = array_values(array_unique($values));
    sort($list);

    return $list;
};

$opts = [
    'bidang' => $opt('bidang'),
    'lokasi' => $opt('lokasi'),
    'skema'  => $opt('skema'),
];
