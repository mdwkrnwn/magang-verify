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
                        <svg
                            class="w-4 h-4 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true">
                            <path d="M5 16.5 3 21l5-1.5" />
                            <path d="M12 15a7 7 0 1 0-7-7" />
                            <path d="m12 2 1.5 5L18 9l-4.5 2L12 16l-1.5-5L6 9l4.5-2L12 2z" />
                        </svg>
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

                            <svg
                                class="w-4 h-4 shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true">
                                <path d="M5 12h14" />
                                <path d="m12 5 7 7-7 7" />
                            </svg>
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
                                <svg
                                    class="w-16 h-16"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    aria-hidden="true">
                                    <path d="M2 10 12 4l10 6-10 6L2 10z" />
                                    <path d="M6 12.5V17c3.5 3 8.5 3 12 0v-4.5" />
                                    <path d="M22 10v6" />
                                </svg>
                            </div>
                        </div>

                        <div class="absolute top-5 right-5 w-14 h-14 rounded-2xl bg-white text-amber-500 flex items-center justify-center shadow-lg -rotate-12">
                            <svg
                                class="w-6 h-6"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                                aria-hidden="true">
                                <path d="m12 2.5 2.9 5.9 6.5.9-4.7 4.6 1.1 6.5-5.8-3.1-5.8 3.1 1.1-6.5-4.7-4.6 6.5-.9L12 2.5z" />
                            </svg>
                        </div>

                        <div class="absolute bottom-7 left-4 w-14 h-14 rounded-2xl bg-white text-blue-500 flex items-center justify-center shadow-lg rotate-12">
                            <svg
                                class="w-6 h-6"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                                <path d="M8 7h8M8 11h8" />
                            </svg>
                        </div>

                        <div class="absolute bottom-5 right-5 w-12 h-12 rounded-full bg-blue-300 flex items-center justify-center text-blue-800 shadow-lg">
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                stroke-width="2.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true">
                                <path d="m5 12 4 4L19 6" />
                            </svg>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
</section>