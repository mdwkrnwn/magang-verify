<?php
$base = url('/');
?>

<section class="py-20 bg-white" id="fitur-sistem">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">

        <div class="grid lg:grid-cols-2 gap-12 items-center">

            <!-- Left content -->
            <div>
                <span class="text-blue-600 font-semibold text-sm tracking-wider uppercase">
                    Fitur Platform
                </span>

                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mt-3 leading-tight">
                    Semua Proses Magang
                    <span class="text-blue-600">dalam Satu Platform</span>
                </h2>

                <p class="mt-5 text-slate-600 leading-relaxed">
                    VerifyMagang menyediakan berbagai fitur yang mendukung
                    pengelolaan kegiatan magang, mulai dari administrasi,
                    pemantauan aktivitas, hingga pengelolaan proses yang
                    melibatkan berbagai pengguna.
                </p>

                <?php
                $features = [
                    [
                        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>',
                        'title' => 'Dashboard',
                        'description' => 'Ringkasan informasi dan perkembangan kegiatan magang.'
                    ],
                    [
                        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M8 13h8M8 17h8"/></svg>',
                        'title' => 'Pengelolaan Laporan',
                        'description' => 'Pengelolaan data dan informasi laporan kegiatan magang.'
                    ],
                    [
                        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 11 3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>',
                        'title' => 'Verifikasi',
                        'description' => 'Mendukung proses pemeriksaan dan verifikasi data maupun dokumen.'
                    ],
                    [
                        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 6h11M9 12h11M9 18h11"/><path d="M4 6h.01M4 12h.01M4 18h.01"/></svg>',
                        'title' => 'Penugasan',
                        'description' => 'Mendukung pengelolaan penugasan dalam proses magang.'
                    ],
                    [
                        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>',
                        'title' => 'Monitoring',
                        'description' => 'Membantu memantau progres dan aktivitas kegiatan magang.'
                    ],
                    [
                        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M20 8v6M17 11h6"/></svg>',
                        'title' => 'Pengelolaan Pengguna',
                        'description' => 'Mendukung pengelolaan pengguna berdasarkan peran dalam sistem.'
                    ]
                ];
                ?>

                <div class="mt-8 space-y-5">

                    <?php foreach ($features as $feature): ?>

                        <div class="flex gap-4 items-start">

                            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                <?= $feature['icon'] ?>
                            </div>

                            <div>
                                <h3 class="font-semibold text-slate-900">
                                    <?= $feature['title'] ?>
                                </h3>

                                <p class="text-sm text-slate-600 mt-1 leading-relaxed">
                                    <?= $feature['description'] ?>
                                </p>
                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>
            </div>

            <!-- Right visual -->
            <div class="relative">

                <div class="bg-slate-900 rounded-3xl p-5 md:p-7 shadow-2xl overflow-hidden">

                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-slate-400 text-xs">VERIFYMAGANG</p>
                            <h3 class="text-white font-bold text-lg mt-1">
                                Dashboard Overview
                            </h3>
                        </div>

                        <div class="flex gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-yellow-400"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-green-400"></span>
                        </div>
                    </div>

                    <!-- Mock dashboard -->
                    <div class="bg-white rounded-2xl p-5">

                        <div class="flex justify-between items-center mb-5">
                            <div>
                                <p class="text-xs text-slate-500">Selamat datang</p>
                                <p class="text-slate-800 font-bold mt-1">Dashboard Magang</p>
                            </div>

                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="12" cy="8" r="4" />
                                    <path d="M5 21v-2a7 7 0 0 1 14 0v2" />
                                </svg>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">

                            <div class="bg-blue-50 rounded-xl p-4">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                    <path d="M14 2v6h6M8 13h8M8 17h8" />
                                </svg>
                                <p class="text-xs text-slate-500 mt-3">Laporan</p>
                                <p class="text-xl font-bold text-slate-800">Data</p>
                            </div>

                            <div class="bg-blue-50 rounded-xl p-4">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m5 12 4 4L19 6" />
                                    <circle cx="12" cy="12" r="10" />
                                </svg>
                                <p class="text-xs text-slate-500 mt-3">Verifikasi</p>
                                <p class="text-xl font-bold text-slate-800">Status</p>
                            </div>

                            <div class="bg-purple-50 rounded-xl p-4">
                                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M9 6h11M9 12h11M9 18h11" />
                                    <path d="M4 6h.01M4 12h.01M4 18h.01" />
                                </svg>
                                <p class="text-xs text-slate-500 mt-3">Penugasan</p>
                                <p class="text-xl font-bold text-slate-800">Aktivitas</p>
                            </div>

                            <div class="bg-amber-50 rounded-xl p-4">
                                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M3 3v18h18" />
                                    <path d="m19 9-5 5-4-4-3 3" />
                                </svg>
                                <p class="text-xs text-slate-500 mt-3">Monitoring</p>
                                <p class="text-xl font-bold text-slate-800">Progres</p>
                            </div>

                        </div>

                        <div class="mt-4 bg-slate-50 rounded-xl p-4">
                            <p class="text-xs text-slate-500 mb-3">
                                Aktivitas Sistem
                            </p>

                            <div class="space-y-3">

                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                            <path d="M14 2v6h6" />
                                        </svg>
                                    </div>

                                    <div class="flex-1">
                                        <div class="h-2.5 bg-slate-200 rounded-full w-3/4"></div>
                                        <div class="h-2 bg-slate-100 rounded-full w-1/2 mt-2"></div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="m5 12 4 4L19 6" />
                                        </svg>
                                    </div>

                                    <div class="flex-1">
                                        <div class="h-2.5 bg-slate-200 rounded-full w-2/3"></div>
                                        <div class="h-2 bg-slate-100 rounded-full w-1/3 mt-2"></div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                </div>

                <div class="absolute -top-5 -right-5 bg-blue-600 text-white rounded-2xl px-5 py-3 shadow-xl hidden md:block">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m13 2-3 8h7l-6 12 1-9H5l8-11z" />
                        </svg>
                        <span class="text-sm font-semibold">Satu Sistem Terintegrasi</span>
                    </div>
                </div>

            </div>

        </div>

    </div>
</section>