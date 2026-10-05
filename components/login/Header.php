<header class="flex w-full min-w-0 flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    <!-- Logo -->
    <a
        href="<?= url('/') ?>"
        class="flex min-w-0 max-w-full items-center gap-3"
    >

        <img
            src="<?= url('/assets/images/icon.png') ?>"
            alt="Logo MagangVerify"
            class="h-10 w-10 shrink-0 object-contain sm:h-12 sm:w-12"
        >

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
        class="inline-flex min-h-10 w-fit max-w-full shrink-0 items-center gap-2 text-sm font-medium text-blue-600 transition hover:text-blue-700"
    >
        <span aria-hidden="true">←</span>
        <span>Kembali ke Beranda</span>
    </a>

</header>