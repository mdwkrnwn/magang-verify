<?php

$deskripsi = trim(
    $sertifikat['deskripsi'] ?? ''
);

?>

<section class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm sm:p-6">

    <div class="mb-5">

        <h2 class="text-lg font-bold text-gray-900">
            Deskripsi
        </h2>

    </div>

    <?php if ($deskripsi !== ''): ?>

        <p class="text-sm leading-7 text-gray-600 whitespace-pre-line">
            <?= e($deskripsi) ?>
        </p>

    <?php else: ?>

        <p class="text-sm italic text-gray-400">
            Belum ada deskripsi untuk sertifikat ini.
        </p>

    <?php endif; ?>

</section>