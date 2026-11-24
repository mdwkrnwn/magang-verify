
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
                    <i class="fas fa-compass"></i>
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
                        <i class="fas fa-arrow-down"></i>
                    </a>

                    <a href="#fitur-sistem"
                        class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl border border-slate-200 bg-white text-slate-700 font-semibold hover:bg-slate-50 transition">
                        Lihat Fitur
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <div class="flex flex-wrap items-center gap-6 mt-10 text-sm text-slate-500">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-blue-500"></i>
                        Sistem terintegrasi
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-blue-500"></i>
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
                                <i class="fas fa-route text-xl"></i>
                            </div>
                        </div>

                        <div class="space-y-4">

                            <!-- Step 1 -->
                            <div class="flex items-center gap-4 bg-white rounded-2xl p-4 shadow-sm">
                                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-file-alt text-lg"></i>
                                </div>

                                <div class="flex-1">
                                    <p class="text-slate-800 font-semibold">Persiapan</p>
                                    <p class="text-xs text-slate-500 mt-1">Memulai proses kegiatan magang</p>
                                </div>

                                <i class="fas fa-check-circle text-blue-500"></i>
                            </div>

                            <div class="flex justify-center">
                                <i class="fas fa-arrow-down text-blue-100"></i>
                            </div>

                            <!-- Step 2 -->
                            <div class="flex items-center gap-4 bg-white rounded-2xl p-4 shadow-sm">
                                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-briefcase text-lg"></i>
                                </div>

                                <div class="flex-1">
                                    <p class="text-slate-800 font-semibold">Pelaksanaan</p>
                                    <p class="text-xs text-slate-500 mt-1">Menjalankan kegiatan magang</p>
                                </div>

                                <i class="fas fa-circle text-slate-300 text-xs"></i>
                            </div>

                            <div class="flex justify-center">
                                <i class="fas fa-arrow-down text-blue-100"></i>
                            </div>

                            <!-- Step 3 -->
                            <div class="flex items-center gap-4 bg-white rounded-2xl p-4 shadow-sm">
                                <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-clipboard-check text-lg"></i>
                                </div>

                                <div class="flex-1">
                                    <p class="text-slate-800 font-semibold">Monitoring & Evaluasi</p>
                                    <p class="text-xs text-slate-500 mt-1">Memantau perkembangan kegiatan</p>
                                </div>

                                <i class="fas fa-circle text-slate-300 text-xs"></i>
                            </div>

                        </div>

                        <div class="mt-6 pt-5 border-t border-white/20 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-white/15 flex items-center justify-center text-white">
                                <i class="fas fa-users"></i>
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
                            <i class="fas fa-shield-alt text-lg"></i>
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
