<?php if ($pages > 1): ?>

<div class="flex items-center justify-between mt-6">

    <!-- Info -->
    <p class="text-xs text-slate-500">

        Menampilkan
        <?= e((($page - 1) * $perPage) + 1) ?>
        -
        <?= e(min($page * $perPage, $total)) ?>
        dari
        <?= e($total) ?>
        formasi

    </p>


    <!-- Pagination -->
    <div class="flex items-center gap-1">

        <?php if ($page > 1): ?>

            <a
                href="<?= e($url($page - 1)) ?>"
                class="inline-flex items-center justify-center
                       w-9 h-9 text-sm text-slate-600
                       bg-white border border-slate-200
                       rounded-lg hover:bg-slate-50 transition"
                aria-label="Halaman sebelumnya"
            >
                ‹
            </a>

        <?php endif; ?>


        <?php for ($i = 1; $i <= $pages; $i++): ?>

            <a
                href="<?= e($url($i)) ?>"
                class="inline-flex items-center justify-center
                       w-9 h-9 text-sm rounded-lg transition
                       <?= $i === $page
                           ? 'text-white bg-blue-600'
                           : 'text-slate-600 bg-white border border-slate-200 hover:bg-slate-50'
                       ?>"
            >
                <?= e($i) ?>
            </a>

        <?php endfor; ?>


        <?php if ($page < $pages): ?>

            <a
                href="<?= e($url($page + 1)) ?>"
                class="inline-flex items-center justify-center
                       w-9 h-9 text-sm text-slate-600
                       bg-white border border-slate-200
                       rounded-lg hover:bg-slate-50 transition"
                aria-label="Halaman berikutnya"
            >
                ›
            </a>

        <?php endif; ?>

    </div>

</div>

<?php endif; ?>