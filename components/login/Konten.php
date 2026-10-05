<!-- Konten informasi -->
<section class="relative z-10 order-2 w-full min-w-0 max-w-full lg:order-1">

    <!-- Judul -->
    <h1 class="max-w-2xl text-3xl font-bold leading-tight tracking-tight sm:text-4xl lg:text-5xl">
        Satu Langkah Menuju Karier yang
        <span class="text-blue-600">Lebih Jelas</span>
    </h1>

    <!-- Deskripsi -->
    <p class="mt-4 max-w-md text-sm leading-7 text-slate-500 sm:mt-5 sm:text-base">
        Masuk untuk mengakses semua fitur MagangVerify sesuai dengan peran Anda di kampus.
    </p>

    <?php
    $fitur = [
        [
            'Kelola Portofolio',
            'Tampilkan pencapaian dan pengalaman Anda.',
            '<path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.1a7.5 7.5 0 0115 0"/>'
        ],
        [
            'Proses Magang',
            'Ajukan dan pantau status magang dengan mudah.',
            '<path d="M20.25 14.15v4.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25v-4.25m16.5 0a2.25 2.25 0 00.75-1.7V8.9a2.25 2.25 0 00-2.25-2.25h-3.5m5 7.5H3.75m0 0a2.25 2.25 0 01-.75-1.7V8.9a2.25 2.25 0 012.25-2.25h3.5m0 0V5.25A2.25 2.25 0 0110 3h4a2.25 2.25 0 012.25 2.25v1.4m-8.5 0h8.5"/>'
        ],
        [
            'Terhubung dengan Mitra',
            'Temukan kesempatan dari berbagai perusahaan.',
            '<path d="M18 18.7a9.1 9.1 0 00-3.7-7.4M18 18.7H6m12 0h3v-.4a4.5 4.5 0 00-4.5-4.5M6 18.7H3v-.4a4.5 4.5 0 014.5-4.5M6 18.7a9.1 9.1 0 013.7-7.4m4.3-3.55a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>'
        ],
        [
            'Data Terverifikasi',
            'Informasi lebih terpercaya dan diakui kampus.',
            '<path d="M9 12.75L11.25 15 15 9.75m-3-7.04A11.96 11.96 0 013.6 6 12 12 0 003 9.75c0 5.6 3.82 10.3 9 11.63 5.18-1.33 9-6.03 9-11.63 0-1.3-.2-2.55-.6-3.75-.1 0-.2 0-.3 0A12 12 0 0112 2.7z"/>'
        ],
    ];
    ?>

    <!-- Fitur -->
    <ul class="mt-6 w-full space-y-4 sm:mt-8">

        <?php foreach ($fitur as [$judul, $deskripsi, $icon]) : ?>

            <li class="flex w-full min-w-0 items-start gap-3 sm:gap-4">

                <!-- Icon -->
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600 sm:h-12 sm:w-12">

                    <svg
                        class="h-5 w-5 sm:h-6 sm:w-6"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >
                        <?= $icon ?>
                    </svg>

                </span>

                <!-- Teks -->
                <div class="min-w-0 flex-1">

                    <p class="text-sm font-semibold text-slate-900">
                        <?= $judul ?>
                    </p>

                    <p class="text-xs leading-5 text-slate-500 sm:text-sm">
                        <?= $deskripsi ?>
                    </p>

                </div>

            </li>

        <?php endforeach; ?>

    </ul>

    <!-- Dekorasi desktop -->
    <div class="relative mt-8 hidden h-56 w-full overflow-hidden lg:block">

        <img
            src="<?= url('/assets/images/gedung-polinema.jpeg') ?>"
            alt="Gedung Polinema"
            class="absolute bottom-0 left-0 h-full max-h-full max-w-[70%] rounded-xl object-cover object-left-bottom opacity-25"
        >

        <p class="absolute right-4 top-0 text-right font-[Caveat] text-2xl leading-7 text-blue-600 xl:right-20">
            Dari Kampus<br>
            Untuk Masa Depan<br>
            yang Lebih Baik
        </p>

    </div>

</section>