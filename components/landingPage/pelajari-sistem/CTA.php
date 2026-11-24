
<?php
$base = url('/');
?>

<section class="py-20 bg-white" id="cta-pelajari-sistem">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">

        <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-blue-600 via-blue-600 to-teal-700 px-8 py-14 md:px-14 md:py-16">

            <!-- Decorative -->
            <div class="absolute -top-32 -right-32 w-96 h-96 bg-white/10 rounded-full"></div>
            <div class="absolute -bottom-40 -left-32 w-96 h-96 bg-black/5 rounded-full"></div>

            <div class="relative grid lg:grid-cols-2 gap-10 items-center">

                <div>
                    <div class="inline-flex items-center gap-2 bg-white/15 border border-white/20 rounded-full px-4 py-2 text-blue-50 text-sm font-medium">
                        <i class="fas fa-rocket"></i>
                        Mulai Perjalananmu
                    </div>

                    <h2 class="text-3xl md:text-4xl font-bold text-white mt-6 leading-tight">
                        Siap Mengelola
                        Kegiatan Magang
                        dengan Lebih Terstruktur?
                    </h2>

                    <p class="mt-5 text-blue-50 leading-relaxed max-w-xl">
                        Mulai gunakan VerifyMagang dan manfaatkan sistem
                        terintegrasi untuk mendukung pengelolaan kegiatan magang.
                    </p>

                    <div class="flex flex-wrap gap-4 mt-8">

                        <a href="<?= url('/login') ?>"
                           class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl bg-white text-blue-700 font-semibold hover:bg-blue-50 transition shadow-lg">
                            Masuk ke Sistem
                            <i class="fas fa-arrow-right"></i>
                        </a>

                        <a href="<?= url('/') ?>"
                           class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl border border-white/30 text-white font-semibold hover:bg-white/10 transition">
                            Kembali ke Beranda
                        </a>

                    </div>
                </div>

                <!-- Illustration -->
                <div class="hidden lg:flex justify-center">
                    <div class="relative w-72 h-72">

                        <div class="absolute inset-0 bg-white/10 rounded-full"></div>
                        <div class="absolute inset-8 bg-white/10 rounded-full"></div>

                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-36 h-36 bg-white rounded-[2rem] shadow-2xl flex items-center justify-center text-blue-600 rotate-6">
                                <i class="fas fa-graduation-cap text-6xl"></i>
                            </div>
                        </div>

                        <div class="absolute top-5 right-5 w-14 h-14 rounded-2xl bg-white text-amber-500 flex items-center justify-center shadow-lg -rotate-12">
                            <i class="fas fa-star text-xl"></i>
                        </div>

                        <div class="absolute bottom-7 left-4 w-14 h-14 rounded-2xl bg-white text-blue-500 flex items-center justify-center shadow-lg rotate-12">
                            <i class="fas fa-book text-xl"></i>
                        </div>

                        <div class="absolute bottom-5 right-5 w-12 h-12 rounded-full bg-blue-300 flex items-center justify-center text-blue-800 shadow-lg">
                            <i class="fas fa-check"></i>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
</section>
