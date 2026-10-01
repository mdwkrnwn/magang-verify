<?php

$currentPage = $pagination['current_page'];
$totalPage = $pagination['total_page'];

if ($totalPage <= 1) {
    return;
}

$queryParams = $_GET;

?>

<div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

    <p class="text-sm text-gray-500">
        Halaman
        <span class="font-semibold text-gray-700">
            <?= e($currentPage) ?>
        </span>
        dari
        <span class="font-semibold text-gray-700">
            <?= e($totalPage) ?>
        </span>
    </p>

    <div class="flex items-center gap-1.5">

        <?php

        /*
        |--------------------------------------------------------------------------
        | Previous
        |--------------------------------------------------------------------------
        */

        if ($currentPage > 1):

            $queryParams['page'] = $currentPage - 1;

            $previousUrl = url(
                '/dashboard/mahasiswa/portofolio'
                    . '?'
                    . http_build_query($queryParams)
            );

        ?>

            <a
                href="<?= e($previousUrl) ?>"
                class="inline-flex items-center justify-center
                       min-w-9 h-9 px-3
                       text-sm font-medium
                       text-gray-600
                       bg-white
                       border border-slate-200
                       rounded-lg
                       hover:bg-slate-50
                       transition">
                &lt;
            </a>

        <?php else: ?>

            <span
                class="inline-flex items-center justify-center
                       min-w-9 h-9 px-3
                       text-sm font-medium
                       text-gray-300
                       bg-slate-50
                       border border-slate-200
                       rounded-lg">
                &lt;
            </span>

        <?php endif; ?>


        <?php

        /*
        |--------------------------------------------------------------------------
        | Page Numbers
        |--------------------------------------------------------------------------
        */

        $startPage = max(
            1,
            $currentPage - 2
        );

        $endPage = min(
            $totalPage,
            $currentPage + 2
        );

        for ($i = $startPage; $i <= $endPage; $i++):

            $queryParams['page'] = $i;

            $pageUrl = url(
                '/dashboard/mahasiswa/portofolio'
                    . '?'
                    . http_build_query($queryParams)
            );

        ?>

            <a
                href="<?= e($pageUrl) ?>"
                class="inline-flex items-center justify-center
                       w-9 h-9
                       text-sm font-medium
                       rounded-lg
                       transition
                       <?= $i === $currentPage
                            ? 'bg-blue-600 text-white'
                            : 'text-gray-600 bg-white border border-slate-200 hover:bg-slate-50'
                        ?>">
                <?= e($i) ?>
            </a>

        <?php endfor; ?>


        <?php

        /*
        |--------------------------------------------------------------------------
        | Next
        |--------------------------------------------------------------------------
        */

        if ($currentPage < $totalPage):

            $queryParams['page'] = $currentPage + 1;

            $nextUrl = url(
                '/dashboard/mahasiswa/portofolio'
                    . '?'
                    . http_build_query($queryParams)
            );

        ?>

            <a
                href="<?= e($nextUrl) ?>"
                class="inline-flex items-center justify-center
                       min-w-9 h-9 px-3
                       text-sm font-medium
                       text-gray-600
                       bg-white
                       border border-slate-200
                       rounded-lg
                       hover:bg-slate-50
                       transition">
                &gt;
            </a>

        <?php else: ?>

            <span
                class="inline-flex items-center justify-center
                       min-w-9 h-9 px-3
                       text-sm font-medium
                       text-gray-300
                       bg-slate-50
                       border border-slate-200
                       rounded-lg">
                &gt;
            </span>

        <?php endif; ?>

    </div>

</div>