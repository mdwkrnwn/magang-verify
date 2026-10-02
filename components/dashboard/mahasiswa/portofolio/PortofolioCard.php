<?php

$judul = trim($portofolioItem['judul'] ?? '');

$parts = preg_split('/\s+/', $judul);

$inisial = '';

foreach (array_slice($parts, 0, 2) as $part) {

    $inisial .= strtoupper(
        mb_substr($part, 0, 1)
    );
}

$slug = $portofolioItem['slug'] ?? '';

$detailUrl = url(
    '/dashboard/mahasiswa/portofolio/detail/'
        . $slug
);

$editUrl = url(
    '/dashboard/mahasiswa/portofolio/edit/'
        . $slug
);

$deleteUrl = url(
    '/dashboard/mahasiswa/portofolio/hapus/'
        . $slug
);

$status = $portofolioItem['verifikasi']['status'] ?? '';

$statusLabel = $portofolioItem['verifikasi']['label'] ?? '';

$modalId = 'hapus-portofolio-' . ($portofolioItem['id'] ?? uniqid());

?>


<div
    class="bg-white border border-slate-200 rounded-xl p-4 sm:p-5
           flex flex-col sm:flex-row gap-4 sm:gap-5
           hover:shadow-sm transition">

    <?php
    $gambar = trim($portofolioItem['gambar'] ?? '');

    $gambarUrl = $gambar !== ''
        ? url('/' . ltrim($gambar, '/'))
        : '';
    ?>

    <div
        class="w-full h-44
           sm:w-40 sm:h-32
           flex-shrink-0
           rounded-lg overflow-hidden
           bg-slate-100
           flex items-center justify-center">

        <?php if ($gambarUrl !== ''): ?>

            <img
                src="<?= e($gambarUrl) ?>"
                alt="<?= e('Gambar portofolio ' . $judul) ?>"
                class="w-full h-full object-cover"
                loading="lazy">

        <?php else: ?>

            <div class="flex flex-col items-center gap-2 text-slate-400">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-10 h-10"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.5">
                    <rect x="3" y="3" width="18" height="18" rx="2" />
                    <circle cx="8.5" cy="8.5" r="1.5" />
                    <path d="m21 15-5-5L5 21" />
                </svg>

                <span class="text-xs">Belum ada gambar</span>
            </div>

        <?php endif; ?>

    </div>

    <div class="flex flex-col flex-1 min-w-0">


        <div class="flex items-start gap-3">


            <div class="flex-1 min-w-0">

                <h2 class="text-lg font-bold text-gray-900 break-words">
                    <?= e($portofolioItem['judul'] ?? '') ?>
                </h2>

            </div>


            <div class="relative shrink-0">


                <button
                    type="button"
                    onclick="togglePortfolioMenu('menu-<?= e($portofolioItem['id']) ?>')"
                    class="w-9 h-9 inline-flex items-center justify-center
                           text-gray-500 rounded-lg
                           hover:bg-slate-100 hover:text-gray-700
                           transition"
                    aria-label="Menu portofolio">
                    <span class="text-xl leading-none">⋮</span>
                </button>


                <div
                    id="menu-<?= e($portofolioItem['id']) ?>"
                    class="hidden absolute right-0 top-10 z-20
                           w-44 bg-white
                           border border-slate-200
                           rounded-lg shadow-lg
                           p-1">

                    <a
                        href="<?= e($editUrl) ?>"
                        class="flex items-center gap-2
                               px-3 py-2
                               text-sm text-gray-700
                               rounded-md
                               hover:bg-slate-50
                               transition">
                        <span>✏</span>
                        Edit
                    </a>


                    <button
                        type="button"
                        onclick="openDeletePortfolioModal('<?= e($modalId) ?>', 'menu-<?= e($portofolioItem['id']) ?>')"
                        class="w-full flex items-center gap-2
                               px-3 py-2
                               text-sm text-red-600
                               rounded-md
                               hover:bg-red-50
                               transition
                               text-left">
                        <span>🗑</span>
                        Hapus
                    </button>

                </div>

            </div>

        </div>


        <div class="mt-2">


            <?php if ($status === 'terverifikasi'): ?>

                <span
                    class="inline-flex w-fit items-center gap-1.5
                           px-2.5 py-1
                           text-xs font-semibold
                           rounded-full
                           bg-emerald-50 text-emerald-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>

                    <?= e($statusLabel ?: 'Terverifikasi') ?>
                </span>

            <?php else: ?>

                <span
                    class="inline-flex w-fit items-center gap-1.5
                           px-2.5 py-1
                           text-xs font-semibold
                           rounded-full
                           bg-amber-50 text-amber-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>

                    <?= e($statusLabel ?: 'Belum Terverifikasi') ?>
                </span>

            <?php endif; ?>


        </div>


        <p class="mt-2 text-sm leading-relaxed text-gray-500">
            <?= e($portofolioItem['deskripsi'] ?? '') ?>
        </p>


        <div class="mt-3 flex flex-wrap gap-2">

            <?php foreach (($portofolioItem['teknologi'] ?? []) as $teknologi): ?>

                <span
                    class="px-2.5 py-1 text-xs font-medium
                           rounded-md bg-blue-50 text-blue-600">
                    <?= e($teknologi) ?>
                </span>

            <?php endforeach; ?>

        </div>


        <div class="mt-auto pt-4 flex justify-end">

            <a
                href="<?= e($detailUrl) ?>"
                class="inline-flex items-center gap-1
                       px-3 py-1.5
                       text-sm font-medium text-blue-600
                       border border-blue-200 rounded-lg
                       hover:bg-blue-50 hover:border-blue-300
                       transition">
                Lihat Detail
            </a>

        </div>


    </div>

</div>


<dialog
    id="<?= e($modalId) ?>"
    class="w-[calc(100%-2rem)] max-w-md
           rounded-xl p-0
           backdrop:bg-black/40">

    <div class="bg-white rounded-xl p-5 sm:p-6">


        <div class="flex items-start gap-3">


            <div
                class="w-10 h-10 shrink-0
                       flex items-center justify-center
                       rounded-full bg-red-50 text-red-600">
                🗑
            </div>


            <div>

                <h3 class="text-base font-semibold text-gray-900">
                    Hapus Portofolio?
                </h3>

                <p class="mt-1 text-sm leading-relaxed text-gray-500">
                    Apakah kamu yakin ingin menghapus
                    <span class="font-medium text-gray-700">
                        <?= e($judul) ?>
                    </span>?
                </p>

            </div>

        </div>


        <p class="mt-4 text-xs text-gray-400">
            Data yang dihapus tidak dapat dikembalikan.
        </p>



        <form
            method="POST"
            action="<?= e($deleteUrl) ?>"
            class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
            <input
                type="hidden"
                name="_csrf_token"
                value="<?= e(csrfToken()) ?>">

            <button
                type="button"
                onclick="closeDeletePortfolioModal('<?= e($modalId) ?>')"
                class="px-4 py-2.5
               text-sm font-medium
               text-gray-600
               border border-slate-200
               rounded-lg
               hover:bg-slate-50
               transition">
                Batal
            </button>

            <button
                type="submit"
                class="px-4 py-2.5
               text-sm font-medium
               text-white
               bg-red-600
               rounded-lg
               hover:bg-red-700
               transition">
                Hapus
            </button>
        </form>

    </div>

</dialog>