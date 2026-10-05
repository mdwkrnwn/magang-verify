<div class="mb-6 sm:mb-8">

    <a
        href="<?= e(url('/dashboard/mahasiswa/portofolio')) ?>"
        class="inline-flex items-center gap-2
               text-sm font-medium text-gray-500
               hover:text-blue-600 transition"
    >

        <svg
            class="w-4 h-4"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15 19l-7-7 7-7"
            />
        </svg>

        Kembali ke Portofolio

    </a>


    <div class="mt-5">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">

            <div class="min-w-0">

                <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl break-words">
                    <?= e($portofolioDetail['judul'] ?? '') ?>
                </h1>

                <p class="mt-2 text-sm text-gray-500 sm:text-base">
                    Detail proyek portofolio mahasiswa.
                </p>

            </div>


            <?php if (($portofolioDetail['verifikasi']['status'] ?? '') === 'terverifikasi'): ?>

                <span
                    class="inline-flex w-fit shrink-0 items-center gap-2
                           px-3 py-1.5
                           text-xs font-semibold
                           rounded-full
                           bg-emerald-50 text-emerald-700"
                >

                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>

                    <?= e($portofolioDetail['verifikasi']['label'] ?? 'Terverifikasi') ?>

                </span>

            <?php else: ?>

                <span
                    class="inline-flex w-fit shrink-0 items-center gap-2
                           px-3 py-1.5
                           text-xs font-semibold
                           rounded-full
                           bg-amber-50 text-amber-700"
                >

                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>

                    <?= e($portofolioDetail['verifikasi']['label'] ?? 'Belum Terverifikasi') ?>

                </span>

            <?php endif; ?>

        </div>

    </div>

</div>