<?php

$monitoringData = [
    [
        'id' => 1,
        'nama' => 'Ahmad Rizki Pratama',
        'nim' => '23410001',
        'prodi' => 'Teknik Informatika',
        'mitra' => 'PT Semarsoft Technology Indonesia',
        'periode' => '7 Okt – 30 Des 2026',
        'status' => 'berlangsung',
        'status_label' => 'Berlangsung',
        'initial' => 'AR',
    ],
    [
        'id' => 2,
        'nama' => 'Siti Aisyah',
        'nim' => '23410002',
        'prodi' => 'Manajemen',
        'mitra' => 'PT Digital Nusantara',
        'periode' => '1 Jul – 30 Sep 2026',
        'status' => 'menunggu_penilaian',
        'status_label' => 'Menunggu Penilaian',
        'initial' => 'SA',
    ],
    [
        'id' => 3,
        'nama' => 'Budi Santoso',
        'nim' => '23410003',
        'prodi' => 'Sistem Informasi',
        'mitra' => 'CV Kreasi Digital',
        'periode' => '1 Apr – 30 Jun 2026',
        'status' => 'selesai',
        'status_label' => 'Selesai',
        'initial' => 'BS',
    ],
    [
        'id' => 4,
        'nama' => 'Dewi Lestari',
        'nim' => '23410004',
        'prodi' => 'Teknik Informatika',
        'mitra' => 'PT Inovasi Teknologi',
        'periode' => '10 Okt – 31 Des 2026',
        'status' => 'persiapan',
        'status_label' => 'Persiapan',
        'initial' => 'DL',
    ],
];
?>

<section class="overflow-hidden bg-white border border-slate-100 shadow-sm rounded-2xl">

    <!-- Header -->
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">

        <div>
            <h2 class="font-semibold text-slate-900">
                Monitoring Penempatan
            </h2>

            <p class="mt-1 text-xs text-slate-500">
                Daftar mahasiswa beserta status penempatan magangnya.
            </p>
        </div>

        <span class="hidden px-3 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded-full sm:inline-flex">
            <?= count($monitoringData) ?> Mahasiswa
        </span>

    </div>


    <!-- List -->
    <div class="divide-y divide-slate-100">

        <?php foreach ($monitoringData as $mahasiswa): ?>

            <?php

            $statusClass = match ($mahasiswa['status']) {

                'persiapan'
                    => 'text-blue-700 bg-blue-50',

                'berlangsung'
                    => 'text-emerald-700 bg-emerald-50',

                'menunggu_penilaian'
                    => 'text-amber-700 bg-amber-50',

                'selesai'
                    => 'text-slate-600 bg-slate-100',

                'dibatalkan'
                    => 'text-red-700 bg-red-50',

                default
                    => 'text-slate-600 bg-slate-100',
            };

            $dotClass = match ($mahasiswa['status']) {

                'persiapan'
                    => 'bg-blue-500',

                'berlangsung'
                    => 'bg-emerald-500',

                'menunggu_penilaian'
                    => 'bg-amber-500',

                'selesai'
                    => 'bg-slate-400',

                'dibatalkan'
                    => 'bg-red-500',

                default
                    => 'bg-slate-400',
            };

            ?>

            <div
                data-monitoring-row
                data-search="<?= htmlspecialchars(
                    $mahasiswa['nama'] . ' ' .
                    $mahasiswa['nim'] . ' ' .
                    $mahasiswa['prodi'] . ' ' .
                    $mahasiswa['mitra']
                ) ?>"
                data-status="<?= htmlspecialchars($mahasiswa['status']) ?>"
                class="p-5 transition hover:bg-slate-50/70"
            >

                <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">

                    <!-- Mahasiswa -->
                    <div class="flex items-start flex-1 min-w-0 gap-4">

                        <div class="flex items-center justify-center flex-shrink-0 w-11 h-11 font-semibold text-blue-700 bg-blue-50 rounded-xl">
                            <?= htmlspecialchars($mahasiswa['initial']) ?>
                        </div>

                        <div class="min-w-0">

                            <h3 class="font-semibold text-slate-900">
                                <?= htmlspecialchars($mahasiswa['nama']) ?>
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                <?= htmlspecialchars($mahasiswa['nim']) ?>
                                ·
                                <?= htmlspecialchars($mahasiswa['prodi']) ?>
                            </p>

                            <p class="mt-1 text-xs text-slate-400">
                                <?= htmlspecialchars($mahasiswa['mitra']) ?>
                            </p>

                        </div>

                    </div>


                    <!-- Periode -->
                    <div class="min-w-[180px]">

                        <p class="text-xs font-medium text-slate-400">
                            Periode Magang
                        </p>

                        <p class="mt-1 text-sm font-medium text-slate-700">
                            <?= htmlspecialchars($mahasiswa['periode']) ?>
                        </p>

                    </div>


                    <!-- Status -->
                    <div>

                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-full <?= $statusClass ?>">

                            <span class="w-1.5 h-1.5 rounded-full <?= $dotClass ?>"></span>

                            <?= htmlspecialchars($mahasiswa['status_label']) ?>

                        </span>

                    </div>


                    <!-- Action -->
                    <div class="flex items-center gap-2">

                        <a
                            href="<?= url('/dashboard/dosen/monitoring/detail/' . $mahasiswa['id']) ?>"
                            class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-blue-600 transition border border-blue-100 rounded-xl hover:bg-blue-50"
                        >
                            Lihat Monitoring

                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5l7 7-7 7"
                                />
                            </svg>

                        </a>


                        <?php if ($mahasiswa['status'] === 'menunggu_penilaian'): ?>

                            <a
                                href="<?= url('/dashboard/dosen/monitoring/penilaian/' . $mahasiswa['id']) ?>"
                                class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-white transition bg-blue-600 rounded-xl hover:bg-blue-700"
                            >
                                Penilaian
                            </a>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>


        <!-- Empty State -->

        <div
            id="emptyMonitoringState"
            class="hidden px-5 py-12 text-center"
        >

            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 text-slate-400 bg-slate-100 rounded-full">

                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"
                    />
                </svg>

            </div>

            <h3 class="font-semibold text-slate-700">
                Mahasiswa tidak ditemukan
            </h3>

            <p class="mt-1 text-sm text-slate-400">
                Coba gunakan nama mahasiswa atau status yang berbeda.
            </p>

        </div>

    </div>

</section>