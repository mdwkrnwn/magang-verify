
<section class="py-20 bg-slate-50" id="pengguna-sistem">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">

        <div class="text-center max-w-3xl mx-auto mb-14">
            <span class="text-blue-600 font-semibold text-sm tracking-wider uppercase">
                Ekosistem Pengguna
            </span>

            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mt-3">
                Setiap Pengguna,
        <span class="text-blue-600">Peran yang Berbeda</span>
            </h2>

            <p class="mt-5 text-slate-600 leading-relaxed">
                Setiap pengguna memiliki tanggung jawab dan akses sesuai
                perannya dalam mendukung proses kegiatan magang.
            </p>
        </div>

        <?php
        $roles = [
            [
                'title' => 'Mahasiswa',
                'description' => 'Menjalankan kegiatan magang, mengelola aktivitas, dan mendokumentasikan proses magang.',
                'icon' => 'fa-user-graduate',
                'color' => 'blue'
            ],
            [
                'title' => 'Dosen',
                'description' => 'Mendukung proses pembimbingan, monitoring, dan evaluasi kegiatan magang.',
                'icon' => 'fa-chalkboard-teacher',
                'color' => 'purple'
            ],
            [
                'title' => 'Tenaga Kependidikan',
                'description' => 'Mendukung administrasi, pengelolaan data, dan proses verifikasi kegiatan magang.',
                'icon' => 'fa-user-tie',
                'color' => 'amber'
            ],
            [
                'title' => 'Koordinator Magang',
                'description' => 'Mengelola koordinasi dan memantau proses kegiatan magang secara keseluruhan.',
                'icon' => 'fa-users-cog',
                'color' => 'blue'
            ],
            [
                'title' => 'Mitra',
                'description' => 'Berpartisipasi dalam pelaksanaan kegiatan magang dan pengelolaan mahasiswa di lingkungan mitra.',
                'icon' => 'fa-building',
                'color' => 'rose'
            ]
        ];
        ?>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">

            <?php foreach ($roles as $role): ?>

                <div class="bg-white border border-slate-200 rounded-3xl p-7 hover:shadow-xl hover:border-blue-200 transition duration-300 group">

                    <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition">
                        <i class="fas <?= $role['icon'] ?> text-xl"></i>
                    </div>

                    <h3 class="mt-5 text-xl font-bold text-slate-900">
                        <?= $role['title'] ?>
                    </h3>

                    <p class="mt-3 text-sm leading-relaxed text-slate-600">
                        <?= $role['description'] ?>
                    </p>

                </div>

            <?php endforeach; ?>

        </div>

    </div>
</section>
