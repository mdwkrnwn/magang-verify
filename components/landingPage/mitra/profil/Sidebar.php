<?php

$menu = [
    ['tentang', 'Tentang', 'briefcase'],
    ['formasi', 'Formasi Magang', 'layers'],
    ['alur', 'Alur Pengajuan', 'bolt'],
    ['kontak', 'Kontak', 'mail'],
];

?>

<aside class="hidden lg:block w-[210px] min-w-0">

    <nav
        id="profil-sidebar"
        class="absolute top-0 left-0 w-[210px] flex flex-col gap-1 bg-white rounded-2xl border border-slate-100 p-2 shadow-sm"
        aria-label="Bagian detail mitra"
    >

        <?php foreach ($menu as $i => [$id, $label, $ic]): ?>

            <a
                href="#<?= e($id) ?>"
                data-section="<?= e($id) ?>"
                class="profil-menu-item flex items-center gap-3 px-3 sm:px-4 py-2.5 rounded-lg text-sm whitespace-nowrap
                <?= $i === 0
                    ? 'bg-blue-50 text-blue-600 font-medium border-l-4 border-blue-600'
                    : 'text-slate-700 hover:bg-slate-50'
                ?>"
            >

                <?= icon($ic, 'w-5 h-5 shrink-0') ?>

                <span><?= e($label) ?></span>

            </a>

        <?php endforeach; ?>

    </nav>

</aside>