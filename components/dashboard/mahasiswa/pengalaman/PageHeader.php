<div class="mb-6 flex flex-col gap-4 sm:mb-8 sm:flex-row sm:items-end sm:justify-between">

    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">
            Pengalaman
        </h1>

        <p class="mt-2 text-sm text-gray-500 sm:text-base">
            Tampilkan pengalaman organisasi, pekerjaan, freelance, proyek, dan pengalaman lainnya.
        </p>
    </div>

    <a
        href="<?= e(url('/dashboard/mahasiswa/pengalaman/tambah')) ?>"
        class="inline-flex w-full shrink-0 items-center justify-center gap-2 rounded-lg bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 sm:w-auto"
    >
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Pengalaman
    </a>

</div>
