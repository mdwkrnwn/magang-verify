<?php

$sertifikat = $sertifikat ?? [];

?>

<main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">

    <div class="px-4 py-6 mx-auto sm:px-6 sm:py-8 lg:px-8 max-w-[1600px]">

        <?php include __DIR__ . '/Header.php'; ?>

        <div class="mt-6 space-y-6">

            <?php include __DIR__ . '/Preview.php'; ?>

            <?php include __DIR__ . '/Informasi.php'; ?>

            <?php include __DIR__ . '/Deskripsi.php'; ?>

            <?php include __DIR__ . '/Tautan.php'; ?>

        </div>

        <?php include __DIR__ . '/../../Footer.php'; ?>

    </div>

</main>