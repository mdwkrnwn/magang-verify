<?php

/** @var array<int, array<string, mixed>> $mahasiswa */
// Dataset utama berasal dari controller dan sudah diambil dari database.
$dataMahasiswa = $mahasiswa;

$q = trim((string) ($_GET['q'] ?? ''));
$fProdi = trim((string) ($_GET['prodi'] ?? ''));
$fAngkatan = trim((string) ($_GET['angkatan'] ?? ''));
$fSkill = $_GET['keahlian'] ?? [];

if (!is_array($fSkill)) {
    $fSkill = $fSkill !== '' ? [$fSkill] : [];
}

$urut = $_GET['urut'] ?? 'terbaru';
$perPage = 8;

$optProdi = array_values(array_unique(array_filter(array_map(
    static fn($m) => trim((string) ($m['prodi'] ?? '')),
    $dataMahasiswa
))));
sort($optProdi);

$optSkill = [];
foreach ($dataMahasiswa as $m) {
    foreach (($m['skills'] ?? []) as $skill) {
        $skill = trim((string) $skill);
        if ($skill !== '') {
            $optSkill[] = $skill;
        }
    }
}
$optSkill = array_values(array_unique($optSkill));
sort($optSkill, SORT_NATURAL | SORT_FLAG_CASE);

$optAngkatan = array_values(array_unique(array_filter(array_map(
    static fn($m) => $m['angkatan'] ?? null,
    $dataMahasiswa,
    ))));
rsort($optAngkatan);

$hasil = array_values(array_filter(
    $dataMahasiswa,
    static function (array $m) use ($q, $fProdi, $fAngkatan, $fSkill): bool {
        $skills = $m['skills'] ?? [];

        return ($q === '' || stripos((string) $m['nama'], $q) !== false)
            && ($fProdi === '' || (string) ($m['prodi'] ?? '') === $fProdi)
            && ($fAngkatan === '' || (string) ($m['angkatan'] ?? '') === $fAngkatan)
            && (empty($fSkill) || empty(array_diff($fSkill, $skills)));
    }
));

usort(
    $hasil,
    static fn($a, $b) => match ($urut) {
        'nama' => strcasecmp((string) $a['nama'], (string) $b['nama']),
        'proyek' => ((int) $b['proyek']) <=> ((int) $a['proyek']),
        default => ((int) ($b['angkatan'] ?? 0) <=> (int) ($a['angkatan'] ?? 0))
            ?: strcasecmp((string) ($a['nama'] ?? ''), (string) ($b['nama'] ?? '')),
    }
);

$total = count($hasil);
$pages = max(1, (int) ceil($total / $perPage));
$page = min($pages, max(1, (int) ($_GET['page'] ?? 1)));
$tampil = array_slice($hasil, ($page - 1) * $perPage, $perPage);

$url = static fn(int $n): string => '?' . http_build_query(array_merge($_GET, ['page' => $n]));
