<?php
$base = url('/');
?>

<section class="relative overflow-hidden bg-white">
    <div class="absolute -top-40 -right-40 w-[500px] h-[500px] bg-blue-100/60 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 -left-40 w-[400px] h-[400px] bg-blue-100/50 rounded-full blur-3xl"></div>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-8 py-20 lg:py-28">
        <div class="grid lg:grid-cols-2 gap-14 items-center">

            <!-- Content -->
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 border border-blue-100 text-blue-700 text-sm font-semibold mb-6">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10" />
                        <circle cx="12" cy="12" r="4" />
                        <path d="M12 2v6m0 8v6m10-10h-6M8 12H2" />
                    </svg>
                    Panduan Mengenal VerifyMagang
                </div>

                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight tracking-tight text-slate-900">
                    Kenali Sistem,
                    <span class="text-blue-600">
                        Pahami Proses Magang.
                    </span>
                </h1>

                <p class="mt-6 text-lg text-slate-600 leading-relaxed">
                    Kenali bagaimana VerifyMagang membantu menghubungkan mahasiswa,
                    perguruan tinggi, dan mitra dalam mengelola kegiatan magang
                    secara lebih terstruktur, transparan, dan terintegrasi.
                </p>

                <div class="flex flex-wrap gap-4 mt-8">
                    <a href="#alur-sistem"
                        class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition shadow-lg shadow-blue-600/20">
                        Jelajahi Sistem
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 5v14" />
                            <path d="m19 12-7 7-7-7" />
                        </svg>
                    </a>

                    <a href="#fitur-sistem"
                        class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl border border-slate-200 bg-white text-slate-700 font-semibold hover:bg-slate-50 transition">
                        Lihat Fitur
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </a>
                </div>

                <div class="flex flex-wrap items-center gap-6 mt-10 text-sm text-slate-500">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m5 12 4 4L19 6" />
                        </svg>
                        Sistem terintegrasi
                    </div>

                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m5 12 4 4L19 6" />
                        </svg>
                        Proses lebih terstruktur
                    </div>
                </div>
            </div>

            <!-- Visual -->
            <div class="relative">
                <div class="relative bg-gradient-to-br from-blue-600 to-teal-700 rounded-[2rem] p-8 lg:p-10 shadow-2xl shadow-blue-900/15 overflow-hidden">

                    <div class="absolute -top-20 -right-20 w-64 h-64 bg-white/10 rounded-full"></div>
                    <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-black/5 rounded-full"></div>

                    <div class="relative">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <p class="text-blue-100 text-sm">Workflow Magang</p>
                                <h3 class="text-white text-xl font-bold mt-1">
                                    Perjalanan Magang
                                </h3>
                            </div>

                            <div class="w-12 h-12 rounded-2xl bg-white/15 flex items-center justify-center text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="6" cy="19" r="3" />
                                    <path d="M9 19h3a6 6 0 0 0 0-12h-1" />
                                    <path d="m14 3-3 4 3 4" />
                                    <circle cx="18" cy="5" r="3" />
                                </svg>
                            </div>
                        </div>

                        <div class="space-y-4">

                            <!-- Step 1 -->
                            <div class="flex items-center gap-4 bg-white rounded-2xl p-4 shadow-sm">
                                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                        <path d="M14 2v6h6M8 13h8M8 17h8" />
                                    </svg>
                                </div>

                                <div class="flex-1">
                                    <p class="text-slate-800 font-semibold">Persiapan</p>
                                    <p class="text-xs text-slate-500 mt-1">Memulai proses kegiatan magang</p>
                                </div>

                                <svg class="w-5 h-5 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m5 12 4 4L19 6" />
                                </svg>
                            </div>

                            <div class="flex justify-center">
                                <svg class="w-4 h-4 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12 5v14m-7-7 7 7 7-7" />
                                </svg>
                            </div>

                            <!-- Step 2 -->
                            <div class="flex items-center gap-4 bg-white rounded-2xl p-4 shadow-sm">
                                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <rect x="3" y="7" width="18" height="14" rx="2" />
                                        <path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                        <path d="M3 12h18m-10 0v2h2v-2" />
                                    </svg>
                                </div>

                                <div class="flex-1">
                                    <p class="text-slate-800 font-semibold">Pelaksanaan</p>
                                    <p class="text-xs text-slate-500 mt-1">Menjalankan kegiatan magang</p>
                                </div>

                                <svg class="w-3 h-3 text-slate-300 shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <circle cx="12" cy="12" r="10" />
                                </svg>
                            </div>

                            <div class="flex justify-center">
                                <svg class="w-4 h-4 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12 5v14m-7-7 7 7 7-7" />
                                </svg>
                            </div>

                            <!-- Step 3 -->
                            <div class="flex items-center gap-4 bg-white rounded-2xl p-4 shadow-sm">
                                <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M9 11l3 3L22 4" />
                                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
                                    </svg>
                                </div>

                                <div class="flex-1">
                                    <p class="text-slate-800 font-semibold">Monitoring & Evaluasi</p>
                                    <p class="text-xs text-slate-500 mt-1">Memantau perkembangan kegiatan</p>
                                </div>

                                <svg class="w-3 h-3 text-slate-300 shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <circle cx="12" cy="12" r="10" />
                                </svg>
                            </div>

                        </div>

                        <div class="mt-6 pt-5 border-t border-white/20 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-white/15 flex items-center justify-center text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="10" cy="7" r="4" />
                                    <path d="M20 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg>
                            </div>

                            <p class="text-sm text-blue-50">
                                Kolaborasi mahasiswa, kampus, dan mitra dalam satu sistem.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Floating card -->
                <div class="absolute -bottom-6 -left-6 bg-white rounded-2xl shadow-xl border border-slate-100 p-4 hidden md:block">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11z" />
                                <path d="m9 12 2 2 4-4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Pengelolaan Magang</p>
                            <p class="font-bold text-slate-800 text-sm">Lebih Terorganisir</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>