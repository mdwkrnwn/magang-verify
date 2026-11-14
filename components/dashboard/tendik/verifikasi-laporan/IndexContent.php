
<?php
$totalLaporan = count($items ?? []);
?>

<main class="min-h-screen bg-slate-50 pt-20 lg:ml-64">

    <div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">
                        Verifikasi Laporan Magang
                    </h1>

                    <p class="mt-2 text-sm text-gray-500 sm:text-base">
                        Periksa laporan magang mahasiswa yang telah disetujui dosen pembimbing.
                    </p>
                </div>

                <div class="inline-flex w-fit items-center gap-2 rounded-lg border border-amber-200 bg-amber-50 px-3 py-2">
                    <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                    <span class="text-xs font-medium text-amber-700">
                        Menunggu Verifikasi
                    </span>
                </div>

            </div>
        </div>

        <!-- Statistics -->
        <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2">

            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            Menunggu Verifikasi
                        </p>

                        <p class="mt-3 text-3xl font-bold tracking-tight text-gray-900">
                            <?= e((string) $totalLaporan) ?>
                        </p>

                        <p class="mt-2 text-xs text-gray-400">
                            Laporan yang telah diperiksa dosen
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>

                </div>
            </div>

            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-sm font-medium text-gray-500">
                            Total Antrean
                        </p>

                        <p class="mt-3 text-3xl font-bold tracking-tight text-gray-900">
                            <?= e((string) $totalLaporan) ?>
                        </p>

                        <p class="mt-2 text-xs text-gray-400">
                            Laporan menunggu pemeriksaan Tendik
                        </p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2Z"/>
                        </svg>
                    </div>

                </div>
            </div>

        </div>

        <!-- Verification Queue -->
        <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

            <!-- Section Header -->
            <div class="flex flex-col gap-2 border-b border-gray-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-7">

                <div>
                    <h2 class="text-lg font-semibold text-gray-900">
                        Antrean Laporan
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Daftar laporan yang telah diperiksa dosen.
                    </p>
                </div>

                <span class="w-fit rounded-lg bg-gray-100 px-3 py-1.5 text-xs font-medium text-gray-600">
                    <?= e((string) $totalLaporan) ?> Laporan
                </span>

            </div>

            <?php if (!empty($items)): ?>

                <div class="overflow-x-auto">

                    <table class="w-full text-left text-sm">

                        <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
                            <tr>
                                <th class="px-6 py-4">Mahasiswa</th>
                                <th class="px-6 py-4">Laporan</th>
                                <th class="px-6 py-4">Perusahaan</th>
                                <th class="px-6 py-4">Tanggal Pengajuan</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            <?php foreach ($items as $item): ?>

                                <tr class="transition-colors hover:bg-gray-50/70">

                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-gray-900">
                                            <?= e($item['mahasiswa_nama'] ?? '-') ?>
                                        </p>

                                        <p class="mt-1 text-xs text-gray-500">
                                            <?= e($item['nim'] ?? '-') ?>
                                        </p>
                                    </td>

                                    <td class="px-6 py-4">
                                        <p class="font-medium text-gray-900">
                                            <?= e($item['judul_laporan'] ?? '-') ?>
                                        </p>

                                        <p class="mt-1 text-xs text-gray-500">
                                            <?= ($item['jenis_laporan'] ?? '') === 'laporan_mingguan'
                                                ? 'Laporan Mingguan - Minggu ' . e((string) ($item['minggu_ke'] ?? '-'))
                                                : 'Laporan Akhir' ?>
                                        </p>
                                    </td>

                                    <td class="px-6 py-4 text-gray-600">
                                        <?= e($item['nama_perusahaan'] ?? '-') ?>
                                    </td>

                                    <td class="px-6 py-4 text-gray-600">
                                        <?= !empty($item['diajukan_pada'])
                                            ? e(date('d M Y', strtotime($item['diajukan_pada'])))
                                            : '-' ?>
                                    </td>

                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 rounded-lg bg-amber-50 px-3 py-1.5 text-xs font-medium text-amber-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                            Menunggu Verifikasi
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <a
                                            href="<?= e(url('/dashboard/tendik/verifikasi/laporan/detail/' . (int) $item['laporan_id'])) ?>"
                                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-blue-700">

                                            Periksa

                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7"/>
                                            </svg>

                                        </a>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php else: ?>

                <!-- Empty State -->
                <div class="px-5 py-16 text-center">

                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 text-gray-400">

                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M9 12l2 2 4-4m5.5-3.5A9 9 0 1112 3"/>
                        </svg>

                    </div>

                    <h3 class="mt-4 text-sm font-semibold text-gray-900">
                        Belum Ada Laporan
                    </h3>

                    <p class="mx-auto mt-2 max-w-md text-sm text-gray-500">
                        Belum ada laporan yang menunggu verifikasi Tendik.
                        Laporan yang telah disetujui dosen akan muncul di sini.
                    </p>

                </div>

            <?php endif; ?>

        </section>

        <?php include __DIR__ . '/../Footer.php'; ?>

    </div>

</main>
