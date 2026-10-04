<?php
$currentPage = (int) ($pagination['current_page'] ?? 1);
$totalPage = (int) ($pagination['total_page'] ?? 1);
$totalData = (int) ($pagination['total_data'] ?? 0);

if ($totalPage <= 1) {
    return;
}

$queryParams = $_GET;
?>

<div class="mt-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <p class="text-sm text-gray-500">
        Menampilkan
        <span class="font-medium text-gray-700"><?= e($totalData) ?></span>
        pengalaman
    </p>

    <nav class="flex items-center gap-1" aria-label="Pagination">
        <?php if ($currentPage > 1): ?>
            <?php
            $queryParams['page'] = $currentPage - 1;
            $previousUrl = url('/dashboard/mahasiswa/pengalaman') . '?' . http_build_query($queryParams);
            ?>
            <a
                href="<?= e($previousUrl) ?>"
                class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-sm font-medium text-gray-600 transition hover:bg-slate-50"
                aria-label="Halaman sebelumnya"
            >&lt;</a>
        <?php else: ?>
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-sm font-medium text-gray-300">&lt;</span>
        <?php endif; ?>

        <?php
        $startPage = max(1, $currentPage - 2);
        $endPage = min($totalPage, $currentPage + 2);
        ?>

        <?php for ($page = $startPage; $page <= $endPage; $page++): ?>
            <?php
            $queryParams['page'] = $page;
            $pageUrl = url('/dashboard/mahasiswa/pengalaman') . '?' . http_build_query($queryParams);
            ?>

            <?php if ($page === $currentPage): ?>
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-blue-600 bg-blue-600 text-sm font-semibold text-white">
                    <?= e($page) ?>
                </span>
            <?php else: ?>
                <a
                    href="<?= e($pageUrl) ?>"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-sm font-medium text-gray-600 transition hover:bg-slate-50"
                >
                    <?= e($page) ?>
                </a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($currentPage < $totalPage): ?>
            <?php
            $queryParams['page'] = $currentPage + 1;
            $nextUrl = url('/dashboard/mahasiswa/pengalaman') . '?' . http_build_query($queryParams);
            ?>
            <a
                href="<?= e($nextUrl) ?>"
                class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-sm font-medium text-gray-600 transition hover:bg-slate-50"
                aria-label="Halaman berikutnya"
            >&gt;</a>
        <?php else: ?>
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-sm font-medium text-gray-300">&gt;</span>
        <?php endif; ?>
    </nav>
</div>
