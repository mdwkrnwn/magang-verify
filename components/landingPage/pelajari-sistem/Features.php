
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
                        'icon' => 'fa-chart-pie',
                        'title' => 'Dashboard',
                        'description' => 'Ringkasan informasi dan perkembangan kegiatan magang.'
                    ],
                    [
                        'icon' => 'fa-file-alt',
                        'title' => 'Pengelolaan Laporan',
                        'description' => 'Pengelolaan data dan informasi laporan kegiatan magang.'
                    ],
                    [
                        'icon' => 'fa-clipboard-check',
                        'title' => 'Verifikasi',
                        'description' => 'Mendukung proses pemeriksaan dan verifikasi data maupun dokumen.'
                    ],
                    [
                        'icon' => 'fa-tasks',
                        'title' => 'Penugasan',
                        'description' => 'Mendukung pengelolaan penugasan dalam proses magang.'
                    ],
                    [
                        'icon' => 'fa-chart-line',
                        'title' => 'Monitoring',
                        'description' => 'Membantu memantau progres dan aktivitas kegiatan magang.'
                    ],
                    [
                        'icon' => 'fa-users',
                        'title' => 'Pengelolaan Pengguna',
                        'description' => 'Mendukung pengelolaan pengguna berdasarkan peran dalam sistem.'
                    ]
                ];
                ?>

                <div class="mt-8 space-y-5">

                    <?php foreach ($features as $feature): ?>

                        <div class="flex gap-4 items-start">

                            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                                <i class="fas <?= $feature['icon'] ?>"></i>
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
                                <i class="fas fa-user"></i>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">

                            <div class="bg-blue-50 rounded-xl p-4">
                                <i class="fas fa-file-alt text-blue-500"></i>
                                <p class="text-xs text-slate-500 mt-3">Laporan</p>
                                <p class="text-xl font-bold text-slate-800">Data</p>
                            </div>

                            <div class="bg-blue-50 rounded-xl p-4">
                                <i class="fas fa-check-circle text-blue-500"></i>
                                <p class="text-xs text-slate-500 mt-3">Verifikasi</p>
                                <p class="text-xl font-bold text-slate-800">Status</p>
                            </div>

                            <div class="bg-purple-50 rounded-xl p-4">
                                <i class="fas fa-tasks text-purple-500"></i>
                                <p class="text-xs text-slate-500 mt-3">Penugasan</p>
                                <p class="text-xl font-bold text-slate-800">Aktivitas</p>
                            </div>

                            <div class="bg-amber-50 rounded-xl p-4">
                                <i class="fas fa-chart-line text-amber-500"></i>
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
                                        <i class="fas fa-file-alt text-xs"></i>
                                    </div>

                                    <div class="flex-1">
                                        <div class="h-2.5 bg-slate-200 rounded-full w-3/4"></div>
                                        <div class="h-2 bg-slate-100 rounded-full w-1/2 mt-2"></div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                                        <i class="fas fa-check text-xs"></i>
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
                        <i class="fas fa-bolt"></i>
                        <span class="text-sm font-semibold">Satu Sistem Terintegrasi</span>
                    </div>
                </div>

            </div>

        </div>

    </div>
</section>
