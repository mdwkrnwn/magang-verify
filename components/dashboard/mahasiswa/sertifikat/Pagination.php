<?php

$currentPage = (int) (
    $pagination['current_page'] ?? 1
);

$totalPage = (int) (
    $pagination['total_page'] ?? 1
);

$totalData = (int) (
    $pagination['total_data'] ?? 0
);

if ($totalPage <= 1) {
    return;
}

$queryParams = $_GET;

?>

<div class="mt-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    <!-- Total Data -->

    <p class="text-sm text-gray-500">

        Menampilkan
        <span class="font-medium text-gray-700">
            <?= $totalData ?>
        </span>
        sertifikat

    </p>


    <!-- Pagination -->

    <nav
        class="flex items-center gap-1"
        aria-label="Pagination">


        <!-- Previous -->

        <?php if ($currentPage > 1): ?>

            <?php
            $queryParams['page'] = $currentPage - 1;

            $previousUrl = url(
                '/dashboard/mahasiswa/sertifikat'
            ) . '?' . http_build_query($queryParams);
            ?>

            <a
                href="<?= e($previousUrl) ?>"
                class="inline-flex items-center justify-center
                       w-9 h-9
                       text-sm font-medium
                       text-gray-600
                       bg-white
                       border border-slate-200
                       rounded-lg
                       hover:bg-slate-50
                       hover:text-gray-900
                       transition"
                aria-label="Halaman sebelumnya">

                &lt;

            </a>

        <?php else: ?>

            <span
                class="inline-flex items-center justify-center
                       w-9 h-9
                       text-sm font-medium
                       text-gray-300
                       bg-slate-50
                       border border-slate-200
                       rounded-lg">

                &lt;

            </span>

        <?php endif; ?>


        <!-- Page Numbers -->

        <?php

        $startPage = max(
            1,
            $currentPage - 2
        );

        $endPage = min(
            $totalPage,
            $currentPage + 2
        );

        ?>

        <?php for ($page = $startPage; $page <= $endPage; $page++): ?>

            <?php

            $queryParams['page'] = $page;

            $pageUrl = url(
                '/dashboard/mahasiswa/sertifikat'
            ) . '?' . http_build_query($queryParams);

            ?>

            <?php if ($page === $currentPage): ?>

                <span
                    class="inline-flex items-center justify-center
                           w-9 h-9
                           text-sm font-semibold
                           text-white
                           bg-blue-600
                           border border-blue-600
                           rounded-lg">

                    <?= $page ?>

                </span>

            <?php else: ?>

                <a
                    href="<?= e($pageUrl) ?>"
                    class="inline-flex items-center justify-center
                           w-9 h-9
                           text-sm font-medium
                           text-gray-600
                           bg-white
                           border border-slate-200
                           rounded-lg
                           hover:bg-slate-50
                           hover:text-gray-900
                           transition">

                    <?= $page ?>

                </a>

            <?php endif; ?>

        <?php endfor; ?>


        <!-- Next -->

        <?php if ($currentPage < $totalPage): ?>

            <?php

            $queryParams['page'] = $currentPage + 1;

            $nextUrl = url(
                '/dashboard/mahasiswa/sertifikat'
            ) . '?' . http_build_query($queryParams);

            ?>

            <a
                href="<?= e($nextUrl) ?>"
                class="inline-flex items-center justify-center
                       w-9 h-9
                       text-sm font-medium
                       text-gray-600
                       bg-white
                       border border-slate-200
                       rounded-lg
                       hover:bg-slate-50
                       hover:text-gray-900
                       transition"
                aria-label="Halaman berikutnya">

                &gt;

            </a>

        <?php else: ?>

            <span
                class="inline-flex items-center justify-center
                       w-9 h-9
                       text-sm font-medium
                       text-gray-300
                       bg-slate-50
                       border border-slate-200
                       rounded-lg">

                &gt;

            </span>

        <?php endif; ?>

    </nav>

</div>