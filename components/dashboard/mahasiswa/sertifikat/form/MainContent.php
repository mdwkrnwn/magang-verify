<?php

$mode = $mode ?? 'tambah';

$old = $old ?? [];

$errors = $errors ?? [];

$slug = $slug ?? '';

?>

<main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">

    <div class="px-4 py-6 mx-auto sm:px-6 sm:py-8 lg:px-8 max-w-[1600px]">

        <?php include __DIR__ . '/PageHeader.php'; ?>

        <div class="mt-6">

            <?php include __DIR__ . '/SertifikatForm.php'; ?>

        </div>

        <?php include __DIR__ . '/../../Footer.php'; ?>

    </div>

</main>