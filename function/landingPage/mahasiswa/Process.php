<?php

// ---- Dataset utama ----
// Simpan dataset asli.
// Dataset ini TIDAK boleh berubah karena filter, search, atau pagination.
$dataMahasiswa = $mahasiswa;


// ---- Filter, urutkan, paginasi ----

$q = trim($_GET['q'] ?? '');

$fJurusan = $_GET['jurusan'] ?? '';
$fProdi = $_GET['prodi'] ?? '';
$fAngkatan = $_GET['angkatan'] ?? '';
$fSkill = $_GET['keahlian'] ?? [];

if (!is_array($fSkill)) {
    $fSkill = $fSkill !== '' ? [$fSkill] : [];
}

$urut = $_GET['urut'] ?? 'terbaru';

$perPage = 8;


// ---- Opsi filter ----
// Selalu dibuat dari seluruh data mahasiswa,
// bukan dari hasil filter atau data yang sedang tampil.

$opsi = fn($k) => array_values(
    array_unique(
        array_merge(
            ...array_map(
                fn($m) => (array)($m[$k] ?? []),
                $dataMahasiswa
            )
        )
    )
);

$optJurusan = $opsi('jurusan');
$optProdi = $opsi('prodi');
$optSkill = $opsi('skills');

$optAngkatan = $opsi('angkatan');
rsort($optAngkatan);


// ---- Filter data ----

$hasil = array_values(
    array_filter(
        $dataMahasiswa,
        fn($m) =>
            ($q === '' || stripos($m['nama'], $q) !== false) &&
            ($fJurusan === '' || $m['jurusan'] === $fJurusan) &&
            ($fProdi === '' || $m['prodi'] === $fProdi) &&
            ($fAngkatan === '' || (string)$m['angkatan'] === $fAngkatan) &&
            (
                empty($fSkill) ||
                empty(array_diff($fSkill, $m['skills']))
            )
    )
);


// ---- Urutkan data ----

usort(
    $hasil,
    fn($a, $b) => match ($urut) {
        'nama' => strcmp($a['nama'], $b['nama']),
        'proyek' => $b['proyek'] <=> $a['proyek'],
        default => [$b['angkatan'], $b['id']] <=> [$a['angkatan'], $a['id']],
    }
);


// ---- Paginasi ----

$total = count($hasil);

$pages = max(
    1,
    (int) ceil($total / $perPage)
);

$page = min(
    $pages,
    max(1, (int) ($_GET['page'] ?? 1))
);

$tampil = array_slice(
    $hasil,
    ($page - 1) * $perPage,
    $perPage
);


// ---- URL pagination ----

$url = fn($n) =>
    '?' . http_build_query(
        array_merge(
            $_GET,
            ['page' => $n]
        )
    );