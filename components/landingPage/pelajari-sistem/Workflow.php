<section class="py-20 bg-white" id="alur-sistem">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">

        <div class="max-w-3xl mb-14">
            <span class="text-blue-600 font-semibold text-sm tracking-wider uppercase">
                Alur Sistem
            </span>

            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mt-3">
                Memahami Perjalanan
                <span class="text-blue-600">Kegiatan Magang</span>
            </h2>

            <p class="mt-5 text-slate-600 leading-relaxed">
                Kegiatan magang terdiri dari beberapa tahapan yang saling
                berkaitan. VerifyMagang membantu mendukung pengelolaan proses
                tersebut melalui sistem yang terstruktur.
            </p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <?php
            $steps = [
                [
                    'number' => '01',
                    'title' => 'Persiapan',
                    'description' => 'Mahasiswa mempersiapkan kebutuhan dan administrasi sebelum memulai kegiatan magang.',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M8 13h8M8 17h8"/></svg>',
                    'color' => 'blue'
                ],
                [
                    'number' => '02',
                    'title' => 'Pelaksanaan',
                    'description' => 'Mahasiswa menjalankan kegiatan magang sesuai aktivitas dan tanggung jawabnya.',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="7" width="18" height="14" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M3 12h18M10 12v2h4v-2"/></svg>',
                    'color' => 'blue'
                ],
                [
                    'number' => '03',
                    'title' => 'Monitoring',
                    'description' => 'Perkembangan kegiatan magang dapat dipantau oleh pihak terkait.',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 3v18h18"/><path d="m7 14 4-4 4 3 6-7"/><path d="M16 6h5v5"/></svg>',
                    'color' => 'amber'
                ],
                [
                    'number' => '04',
                    'title' => 'Evaluasi',
                    'description' => 'Proses kegiatan magang dievaluasi sebagai bagian dari penyelesaian kegiatan.',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="m8 12 3 3 5-6"/></svg>',
                    'color' => 'purple'
                ]
            ];
            ?>

            <?php foreach ($steps as $index => $step): ?>

                <div class="relative bg-slate-50 border border-slate-200 rounded-3xl p-7 hover:shadow-lg hover:-translate-y-1 transition duration-300">

                    <div class="flex items-center justify-between mb-6">
                        <span class="text-4xl font-bold text-slate-200">
                            <?= $step['number'] ?>
                        </span>

                        <div class="w-14 h-14 rounded-2xl bg-white shadow-sm text-blue-600 flex items-center justify-center">
                            <?= $step['icon'] ?>
                        </div>
                    </div>

                    <h3 class="text-lg font-bold text-slate-900">
                        <?= $step['title'] ?>
                    </h3>

                    <p class="text-sm text-slate-600 mt-3 leading-relaxed">
                        <?= $step['description'] ?>
                    </p>

                    <?php if ($index < count($steps) - 1): ?>
                        <div class="hidden lg:flex absolute -right-4 top-1/2 -translate-y-1/2 z-10 w-8 h-8 rounded-full bg-white border border-slate-200 items-center justify-center text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4"
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
                        </div>
                    <?php endif; ?>

                </div>

            <?php endforeach; ?>

        </div>
    </div>
</section>