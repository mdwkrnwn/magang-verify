<div class="overflow-hidden bg-white border border-slate-200 rounded-xl">

    <!-- Header -->
    <div class="px-4 py-4 border-b border-slate-200">

        <h2 class="text-sm font-semibold text-slate-700">
            Logbook Minggu Ini (9 - 15 Juni 2025)
        </h2>

    </div>


    <!-- Logbook Items -->
    <div>

        <?php

        $logbooks = [

            [
                'tanggal' => '12 Jun',
                'judul' => 'Mempelajari alur kerja sistem',
                'deskripsi' => 'Membaca dokumentasi dan memahami flow aplikasi.',
                'status' => 'Disetujui',
                'statusClass' => 'text-emerald-600 bg-emerald-50'
            ],

            [
                'tanggal' => '11 Jun',
                'judul' => 'Implementasi fitur login',
                'deskripsi' => 'Mengembangkan halaman login dan autentikasi.',
                'status' => 'Disetujui',
                'statusClass' => 'text-emerald-600 bg-emerald-50'
            ],

            [
                'tanggal' => '10 Jun',
                'judul' => 'Diskusi tim',
                'deskripsi' => 'Rapat koordinasi mingguan dengan tim developer.',
                'status' => 'Menunggu',
                'statusClass' => 'text-amber-600 bg-amber-50'
            ],

            [
                'tanggal' => '9 Jun',
                'judul' => 'Perbaikan bug tampilan',
                'deskripsi' => 'Memperbaiki UI pada halaman dashboard.',
                'status' => 'Disetujui',
                'statusClass' => 'text-emerald-600 bg-emerald-50'
            ]

        ];

        ?>


        <?php foreach ($logbooks as $item): ?>

            <div
                class="grid grid-cols-[68px_minmax(0,1fr)]
                       gap-3 px-4 py-3
                       border-b border-slate-100
                       last:border-b-0
                       sm:grid-cols-[70px_minmax(0,1fr)_90px]
                       sm:items-center">

                <!-- Date -->
                <div class="text-xs font-medium text-slate-500">
                    <?= e($item['tanggal']) ?>
                </div>


                <!-- Activity -->
                <div class="min-w-0">

                    <h3 class="text-xs font-semibold text-slate-700">
                        <?= e($item['judul']) ?>
                    </h3>

                    <p class="mt-1 text-[11px] text-slate-400 truncate">
                        <?= e($item['deskripsi']) ?>
                    </p>

                </div>


                <!-- Status -->
                <div class="flex sm:justify-end">

                    <span
                        class="inline-flex px-2.5 py-1
                               text-[10px] font-medium
                               rounded-full
                               <?= $item['statusClass'] ?>">

                        <?= e($item['status']) ?>

                    </span>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

</div>