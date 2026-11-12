<section class="relative overflow-hidden bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">

        <div class="min-h-[600px] py-10 sm:py-14 lg:py-0 flex flex-col lg:flex-row lg:items-center">

            <!-- LEFT CONTENT -->
            <div class="w-full lg:w-1/2 relative z-10 flex flex-col">

                <!-- Badge -->
                <div class="order-1 self-start inline-flex items-center px-4 py-2 rounded-full bg-blue-50 text-blue-600 text-xs font-medium mb-5">
                    Sistem Terintegrasi untuk Mahasiswa, Kampus, dan Industri
                </div>

                <!-- Heading -->
                <h1 class="order-2 text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight text-slate-900">
                    Bangun Portofolio,
                    <br>
                    <span class="text-blue-600 whitespace-nowrap">
                        Wujudkan Masa Depan
                    </span>
                </h1>

                <!-- RIGHT IMAGE (Mobile: setelah heading) -->
                <div class="order-3 lg:hidden w-full flex items-center justify-center relative mt-6 mb-6">

                    <!-- Background decoration -->
                    <div class="absolute w-56 h-56 sm:w-72 sm:h-72 bg-blue-50 rounded-full"></div>

                    <img
                        src="<?= url('/assets/images/hero.png') ?>"
                        alt="Mahasiswa MagangVerify"
                        class="relative z-10 w-auto max-w-full max-h-[280px] sm:max-h-[350px] object-contain">
                </div>

                <!-- Description -->
                <p class="order-4 mt-0 lg:mt-5 max-w-lg text-sm sm:text-base leading-6 text-slate-500">
                    MagangVerify adalah platform yang menghubungkan
                    mahasiswa, kampus, dan mitra industri dalam satu ekosistem
                    untuk pengelolaan portofolio dan proses magang.
                </p>

                <!-- Buttons -->
                <div class="order-5 flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 mt-7">

                    <a
                        href="<?= url('/mahasiswa') ?>"
                        class="inline-flex items-center justify-center gap-2 px-5 py-4 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition">
                        Lihat Daftar Mahasiswa

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="20"
                            height="20"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true">
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </a>

                    <a
                        href="#"
                        class="inline-flex items-center justify-center px-5 py-4 border border-blue-500 text-blue-600 text-sm font-medium rounded-md hover:bg-blue-50 transition">
                        Pelajari Sistem
                    </a>

                </div>

            </div>

            <!-- RIGHT IMAGE (Desktop only) -->
            <div class="hidden lg:flex w-full lg:w-1/2 h-full items-end justify-end relative">

                <!-- Background decoration -->
                <div class="absolute w-72 h-72 bg-blue-50 rounded-full top-20 right-20"></div>

                <img
                    src="<?= url('/assets/images/hero.png') ?>"
                    alt="Mahasiswa MagangVerify"
                    class="relative z-10 w-auto max-w-full max-h-[500px] object-contain">

            </div>

        </div>

    </div>
</section>