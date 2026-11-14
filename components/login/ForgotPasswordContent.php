
<section class="relative z-10 order-2 w-full min-w-0 max-w-full lg:order-1">

    <!-- Judul -->
    <h1 class="max-w-2xl text-3xl font-bold leading-tight tracking-tight sm:text-4xl lg:text-5xl">
        Kembali Mengakses Akun
        <span class="text-blue-600">Anda</span>
    </h1>

    <!-- Deskripsi -->
    <p class="mt-4 max-w-md text-sm leading-7 text-slate-500 sm:mt-5 sm:text-base">
        Jangan khawatir jika Anda lupa password.
        Kami membantu Anda mendapatkan kembali akses
        ke akun MagangVerify dengan mudah dan aman.
    </p>

    <?php
    $langkah = [
        [
            'Masukkan Identitas Akun',
            'Gunakan NIM untuk mahasiswa atau NIP untuk dosen.',
            '<path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.1a7.5 7.5 0 0115 0"/>'
        ],
        [
            'Verifikasi Melalui Email',
            'Tautan pemulihan akan dikirimkan ke email yang terdaftar.',
            '<path d="M3 7.5l8.1 5.4a1.5 1.5 0 001.8 0L21 7.5M4.5 5.25h15A2.25 2.25 0 0121.75 7.5v9a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25v-9A2.25 2.25 0 014.5 5.25z"/>'
        ],
        [
            'Buat Password Baru',
            'Atur password baru untuk kembali menggunakan akun Anda.',
            '<path d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>'
        ],
    ];
    ?>

    <!-- Langkah pemulihan -->
    <ul class="mt-6 w-full space-y-5 sm:mt-8">

        <?php foreach ($langkah as [$judul, $deskripsi, $icon]) : ?>

            <li class="flex w-full min-w-0 items-start gap-3 sm:gap-4">

                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600 sm:h-12 sm:w-12">

                    <svg
                        class="h-5 w-5 sm:h-6 sm:w-6"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        viewBox="0 0 24 24"
                        aria-hidden="true">
                        <?= $icon ?>
                    </svg>

                </span>

                <div class="min-w-0 flex-1">

                    <p class="text-sm font-semibold text-slate-900">
                        <?= htmlspecialchars($judul, ENT_QUOTES, 'UTF-8') ?>
                    </p>

                    <p class="text-xs leading-5 text-slate-500 sm:text-sm">
                        <?= htmlspecialchars($deskripsi, ENT_QUOTES, 'UTF-8') ?>
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
            class="absolute bottom-0 left-0 h-full max-h-full max-w-[70%] rounded-xl object-cover object-left-bottom opacity-25">

        <p class="absolute right-4 top-0 text-right font-[Caveat] text-2xl leading-7 text-blue-600 xl:right-20">
            Setiap Masalah<br>
            Pasti Ada Solusi<br>
            Mari Mulai Kembali
        </p>

    </div>

</section>
