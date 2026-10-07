<header class="flex w-full min-w-0 flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    <!-- Logo -->
    <a
        href="<?= url('/') ?>"
        class="flex min-w-0 max-w-full items-center gap-3">

        <img
            src="<?= url('/assets/images/icon.png') ?>"
            alt="Logo MagangVerify"
            class="h-10 w-10 shrink-0 object-contain sm:h-12 sm:w-12">

        <div class="min-w-0">
            <p class="truncate text-lg font-bold leading-tight text-slate-900 sm:text-xl">
                MagangVerify
            </p>

            <p class="truncate text-[10px] leading-relaxed text-slate-600 sm:text-xs">
                Portofolio Terverifikasi, Masa Depan Lebih Dekat
            </p>
        </div>

    </a>

    <!-- Kembali -->
    <a
        href="<?= url('/') ?>"
        class="inline-flex min-h-10 w-fit max-w-full shrink-0 items-center gap-2 text-sm font-medium text-blue-600 transition hover:text-blue-700">

        <svg
            class="h-4 w-4 shrink-0"
            fill="none"
            stroke="currentColor"
            stroke-width="1.8"
            stroke-linecap="round"
            stroke-linejoin="round"
            viewBox="0 0 24 24"
            aria-hidden="true">

            <path d="M19 12H5" />
            <path d="M11 6l-6 6 6 6" />

        </svg>

        <span>Kembali ke Beranda</span>

    </a>

</header>