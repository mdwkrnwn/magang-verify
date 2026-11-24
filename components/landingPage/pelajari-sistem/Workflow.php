
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
                    'icon' => 'fa-file-alt',
                    'color' => 'blue'
                ],
                [
                    'number' => '02',
                    'title' => 'Pelaksanaan',
                    'description' => 'Mahasiswa menjalankan kegiatan magang sesuai aktivitas dan tanggung jawabnya.',
                    'icon' => 'fa-briefcase',
                    'color' => 'blue'
                ],
                [
                    'number' => '03',
                    'title' => 'Monitoring',
                    'description' => 'Perkembangan kegiatan magang dapat dipantau oleh pihak terkait.',
                    'icon' => 'fa-chart-line',
                    'color' => 'amber'
                ],
                [
                    'number' => '04',
                    'title' => 'Evaluasi',
                    'description' => 'Proses kegiatan magang dievaluasi sebagai bagian dari penyelesaian kegiatan.',
                    'icon' => 'fa-check-circle',
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
                            <i class="fas <?= $step['icon'] ?> text-xl"></i>
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
                            <i class="fas fa-arrow-right text-xs"></i>
                        </div>
                    <?php endif; ?>

                </div>

            <?php endforeach; ?>

        </div>

    </div>
</section>
