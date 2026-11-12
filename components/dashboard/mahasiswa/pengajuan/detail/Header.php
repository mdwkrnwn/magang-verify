<div class="mb-6">

    <a
        href="<?= e(url('/dashboard/mahasiswa/pengajuan')) ?>"
        class="inline-flex items-center gap-2
           text-sm text-slate-500
           hover:text-blue-600 transition">
        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="16"
            height="16"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            class="shrink-0"
            aria-hidden="true">
            <path d="m12 19-7-7 7-7" />
            <path d="M19 12H5" />
        </svg>

        <span>Kembali ke Pengajuan Saya</span>
    </a>
</div>


<div class="mb-6 sm:mb-8">

    <div
        class="flex flex-col gap-4
               lg:flex-row lg:items-start lg:justify-between">

        <div>

            <p class="text-sm text-slate-500">
                Detail Pengajuan Magang
            </p>

            <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">
                <?= e($pengajuanDetail['perusahaan']) ?>
            </h1>

            <p class="mt-2 text-sm text-slate-500 sm:text-base">
                <?= e($pengajuanDetail['posisi']) ?>
            </p>

        </div>


        <span
            class="inline-flex w-fit px-4 py-2
                   text-xs font-medium rounded-lg
                   <?= e($pengajuanDetail['statusClass']) ?>">
            <?= e($pengajuanDetail['status']) ?>
        </span>

    </div>

</div>