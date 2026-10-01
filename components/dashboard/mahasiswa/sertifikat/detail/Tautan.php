<?php

$tautan = trim(
    $sertifikat['tautan']['sertifikat'] ?? ''
);

?>

<section class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm sm:p-6">

    <div class="mb-5">

        <h2 class="text-lg font-bold text-gray-900">
            Tautan Sertifikat
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Akses sertifikat melalui tautan yang tersedia.
        </p>

    </div>


    <?php if ($tautan !== ''): ?>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

            <div class="min-w-0">

                <p class="text-sm text-gray-600 break-all">
                    <?= e($tautan) ?>
                </p>

            </div>


            <a
                href="<?= e($tautan) ?>"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-white transition bg-gray-900 rounded-lg shrink-0 hover:bg-gray-800"
            >

                <svg
                    class="w-4 h-4"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M13.5 6H18v4.5M18 6l-7.5 7.5M16.5 13.5v4.5h-11V7h4.5"
                    />
                </svg>

                Buka Sertifikat

            </a>

        </div>

    <?php else: ?>

        <p class="text-sm italic text-gray-400">
            Belum ada tautan sertifikat.
        </p>

    <?php endif; ?>

</section>