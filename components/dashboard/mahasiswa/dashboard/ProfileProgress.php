<?php

/** @var array $dashboard */
$percent = (int) ($dashboard['profil_progress'] ?? 0);
?>

<section class="h-full p-5 bg-white border border-gray-100 shadow-sm sm:p-7 rounded-2xl">
    <div class="flex items-start justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Kelengkapan Profil</h2>
            <p class="mt-1 text-sm text-gray-500">Lengkapi profil agar data Anda siap digunakan.</p>
        </div>
        <span class="text-sm font-bold text-blue-600"><?= e((string) $percent) ?>%</span>
    </div>

    <div class="w-full h-2 mt-6 overflow-hidden bg-gray-100 rounded-full">
        <div class="h-full bg-blue-600 rounded-full transition-all" style="width: <?= e((string) $percent) ?>%"></div>
    </div>

    <p class="mt-3 text-sm text-gray-500">
        <?= $percent >= 100 ? 'Profil Anda sudah lengkap.' : 'Masih ada informasi yang perlu dilengkapi.' ?>
    </p>

    <?php if ($percent < 100): ?>
        <a
            href="<?= e(url('/dashboard/mahasiswa/profil')) ?>"
            class="inline-flex items-center gap-2 mt-5 text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors">

            Lengkapi Profil

            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 shrink-0"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true">
                <path d="M5 12h14" />
                <path d="m12 5 7 7-7 7" />
            </svg>
        </a>
    <?php endif; ?>
</section>