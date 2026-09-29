<section class="relative overflow-hidden bg-gradient-to-r from-white via-blue-50/40 to-blue-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 pt-6 sm:pt-8 pb-20 sm:pb-24 md:pb-32">
        <nav
            class="text-xs text-slate-600 flex items-center gap-2"
            aria-label="Breadcrumb"
        >
            <a
                href="<?= url('/') ?>"
                class="hover:text-blue-600 transition"
            >
                Beranda
            </a>

            <span>›</span>

            <span>Daftar Mahasiswa</span>
        </nav>

        <div class="relative z-10 mt-5 sm:mt-6 max-w-2xl">

            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold leading-tight text-slate-900">
                Temukan
                <span class="text-blue-600">Talenta Terbaik</span>
            </h1>

            <p class="mt-4 text-sm sm:text-base leading-relaxed text-slate-500">
                Jelajahi profil mahasiswa POLINEMA yang siap berkontribusi di dunia industri.
                Gunakan filter untuk menemukan kandidat yang sesuai dengan kebutuhan Anda.
            </p>

        </div>

        <img
            src="<?= url('/assets/images/hero-mahasiswa.png') ?>"
            alt=""
            class="hidden lg:block absolute right-6 bottom-0 h-64 w-auto object-contain"
        >
    </div>
</section>