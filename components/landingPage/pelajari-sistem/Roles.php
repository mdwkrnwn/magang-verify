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
                'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 10 12 4 2 10l10 6 10-6z"/><path d="M6 12.5V17c3.5 3 8.5 3 12 0v-4.5M22 10v6"/></svg>',
                'color' => 'blue'
            ],
            [
                'title' => 'Dosen',
                'description' => 'Mendukung proses pembimbingan, monitoring, dan evaluasi kegiatan magang.',
                'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3 2 8l10 5 10-5-10-5z"/><path d="M6 10v5c3.5 3 8.5 3 12 0v-5M22 8v7"/></svg>',
                'color' => 'purple'
            ],
            [
                'title' => 'Tenaga Kependidikan',
                'description' => 'Mendukung administrasi, pengelolaan data, dan proses verifikasi kegiatan magang.',
                'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M5 21v-2a7 7 0 0 1 14 0v2M19 8h3M20.5 6.5v3"/></svg>',
                'color' => 'amber'
            ],
            [
                'title' => 'Koordinator Magang',
                'description' => 'Mengelola koordinasi dan memantau proses kegiatan magang secara keseluruhan.',
                'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M20 8v6M17 11h6"/></svg>',
                'color' => 'blue'
            ],
            [
                'title' => 'Mitra',
                'description' => 'Berpartisipasi dalam pelaksanaan kegiatan magang dan pengelolaan mahasiswa di lingkungan mitra.',
                'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="7" width="18" height="14" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M3 12h18M10 12v2h4v-2"/></svg>',
                'color' => 'rose'
            ]
        ];
        ?>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">

            <?php foreach ($roles as $role): ?>

                <div class="bg-white border border-slate-200 rounded-3xl p-7 hover:shadow-xl hover:border-blue-200 transition duration-300 group">

                    <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition">
                        <?= $role['icon'] ?>
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