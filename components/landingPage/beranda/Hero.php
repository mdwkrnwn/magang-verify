<section class="relative overflow-hidden bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">

        <div class="min-h-[600px] lg:min-h-[600px] py-10 sm:py-14 lg:py-0 flex flex-col lg:flex-row lg:items-center">

            <!-- LEFT CONTENT -->
            <div class="w-full lg:w-1/2 relative z-10">

                <!-- Badge -->
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-50 text-blue-600 text-xs font-medium mb-5">
                    Sistem Terintegrasi untuk Mahasiswa, Kampus, dan Industri
                </div>

                <!-- Heading -->
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight text-slate-900">
                    Bangun Portofolio,
                    <br>
                    <span class="text-blue-600 whitespace-nowrap">
                        Wujudkan Masa Depan
                    </span>
                </h1>

                <!-- Description -->
                <p class="mt-5 max-w-lg text-sm sm:text-base leading-6 text-slate-500">
                    MagangVerify adalah platform yang menghubungkan
                    mahasiswa, kampus, dan mitra industri dalam satu ekosistem
                    untuk pengelolaan portofolio dan proses magang.
                </p>

                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 mt-7">

                    <a
                        href="<?= url('/mahasiswa') ?>"
                        class="inline-flex items-center justify-center gap-2 px-5 py-4 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition"
                    >
                        Lihat Daftar Mahasiswa
                        <span>→</span>
                    </a>

                    <a
                        href="#"
                        class="inline-flex items-center justify-center px-5 py-4 border border-blue-500 text-blue-600 text-sm font-medium rounded-md hover:bg-blue-50 transition"
                    >
                        Pelajari Sistem
                    </a>

                </div>

            </div>


            <!-- RIGHT IMAGE -->
            <div class="w-full lg:w-1/2 h-auto lg:h-full flex items-end justify-center lg:justify-end relative mt-10 lg:mt-0">

                <!-- Background decoration -->
                <div class="absolute w-56 h-56 sm:w-72 sm:h-72 bg-blue-50 rounded-full top-8 sm:top-20 right-1/2 sm:right-20 translate-x-1/2 sm:translate-x-0"></div>

                <img
                    src="<?= url('/assets/images/hero.png') ?>"
                    alt="Mahasiswa MagangVerify"
                    class="relative z-10 w-auto max-w-full max-h-[400px] sm:max-h-[450px] lg:max-h-[500px] object-contain"
                >

            </div>

        </div>

    </div>
</section>