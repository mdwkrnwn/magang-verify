<div class="mb-6 sm:mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">
            Sertifikat
        </h1>

        <p class="mt-2 text-sm text-gray-500 sm:text-base">
            Kelola sertifikat yang Anda miliki.
        </p>
    </div>

    <a
        href="<?= url('/dashboard/mahasiswa/sertifikat/tambah') ?>"
        class="inline-flex items-center justify-center gap-2 w-full sm:w-auto
               px-5 py-3 sm:py-4 shrink-0
               bg-blue-600 text-white text-sm font-semibold
               rounded-lg hover:bg-blue-700 transition">

        <svg
            class="w-5 h-5"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24">

            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 4v16m8-8H4" />

        </svg>

        Unggah Sertifikat

    </a>

</div>