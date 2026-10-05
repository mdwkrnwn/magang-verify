<?php

$tampil = $tampil ?? [];
$tahunList = $tahunList ?? [];

$pagination = $pagination ?? [
    'current_page' => 1,
    'per_page' => 6,
    'total_data' => count($tampil),
    'total_page' => 1,
];

?>

<main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">

    <div class="px-4 py-6 mx-auto sm:px-6 sm:py-8 lg:px-8 max-w-[1600px]">

        <?php include __DIR__ . '/PageHeader.php'; ?>

        <?php include __DIR__ . '/SearchFilter.php'; ?>

        <?php include __DIR__ . '/SertifikatList.php'; ?>

        <?php include __DIR__ . '/Pagination.php'; ?>

        <?php include __DIR__ . '/../Footer.php'; ?>

    </div>

</main>