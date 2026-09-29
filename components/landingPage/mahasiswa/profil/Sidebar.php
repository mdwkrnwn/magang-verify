<aside
    class="lg:sticky lg:top-24 lg:self-start min-w-0"
>
    <nav
        id="profil-sidebar"
        class="flex lg:flex-col gap-1 overflow-x-auto lg:overflow-visible bg-white rounded-2xl border border-slate-100 p-2"
        aria-label="Bagian profil"
    >

        <?php foreach ($menu as $i => [$id2, $label, $ic]): ?>

            <a
                href="#<?= e($id2) ?>"
                data-section="<?= e($id2) ?>"
                class="profil-menu-item flex items-center gap-3 px-3 sm:px-4 py-2.5 rounded-lg text-sm whitespace-nowrap shrink-0 <?= $i === 0
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