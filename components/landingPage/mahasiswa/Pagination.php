<?php

// Butuh: $page, $pages, $url (closure dari Process.php, sudah membawa seluruh filter)
if ($pages <= 1) {
    return;
}

// 1 … 4 5 6 … 12
$nums = array_unique(array_filter(
    array_merge([1], range($page - 1, $page + 1), [$pages]),
    fn($n) => $n >= 1 && $n <= $pages
));
sort($nums);

$btn = 'w-9 h-9 grid place-items-center rounded-lg border text-sm transition ';
$off = 'border-slate-100 text-slate-300 pointer-events-none';
$on  = 'border-slate-200 text-slate-600 hover:bg-blue-50';

?>

<nav class="flex flex-wrap items-center justify-center gap-2 mt-8" aria-label="Paginasi">

    <a
        href="<?= $page > 1 ? $url($page - 1) : '#' ?>"
        class="<?= $btn . ($page > 1 ? $on : $off) ?>"
        aria-label="Sebelumnya"
    >
        <?= icon('left', 'w-4 h-4') ?>
    </a>

    <?php $prev = 0; foreach ($nums as $n): ?>

        <?php if ($n - $prev > 1): ?>
            <span class="text-slate-400 px-1">…</span>
        <?php endif; ?>

        <a
            href="<?= $url($n) ?>"
            <?= $n === $page ? 'aria-current="page"' : '' ?>
            class="<?= $btn . ($n === $page ? 'bg-blue-600 border-blue-600 text-white' : $on) ?>"
        >
            <?= $n ?>
        </a>

        <?php $prev = $n; ?>

    <?php endforeach; ?>

    <a
        href="<?= $page < $pages ? $url($page + 1) : '#' ?>"
        class="<?= $btn . ($page < $pages ? $on : $off) ?>"
        aria-label="Berikutnya"
    >
        <?= icon('right', 'w-4 h-4') ?>
    </a>

</nav>