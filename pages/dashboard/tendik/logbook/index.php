<?php
$base = url('/');
$active = 'logbook';
?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . '/../../../../components/dashboard/tendik/Head.php'; ?>

<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">

    <?php include __DIR__ . '/../../../../components/dashboard/tendik/Sidebar.php'; ?>
    <?php include __DIR__ . '/../../../../components/dashboard/tendik/Header.php'; ?>

    <main class="min-h-screen bg-slate-50 pt-20 lg:ml-64">
        <div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

            <!-- Page Header -->
            <div class="mb-7">
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <span>Dashboard</span>
                    <span>/</span>
                    <span class="font-medium text-blue-600">Validasi Logbook</span>
                </div>

                <div class="mt-3">
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">
                        Validasi Logbook
                    </h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-gray-500">
                        Periksa pengajuan ketidakhadiran mahasiswa, tinjau alasan,
                        dan validasi bukti yang telah dikirimkan.
                    </p>
                </div>
            </div>

            <!-- Validation Queue -->
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <!-- Section Header -->
                <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 8h6m-6 4h6" />
                            </svg>
                        </div>

                        <div>
                            <h2 class="font-semibold text-gray-900">
                                Antrean Validasi
                            </h2>
                            <p class="mt-0.5 text-xs text-gray-500">
                                Daftar pengajuan yang membutuhkan pemeriksaan.
                            </p>
                        </div>
                    </div>

                    <span class="inline-flex w-fit items-center rounded-full bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700">
                        <?= count($items ?? []) ?> pengajuan
                    </span>
                </div>

                <?php if (!empty($items)): ?>

                    <!-- List -->
                    <div class="divide-y divide-slate-100">

                        <?php foreach ($items as $item): ?>

                            <a
                                href="<?= e(url('/dashboard/tendik/logbook/detail/' . (int) $item['id'])) ?>"
                                class="group block px-5 py-5 transition-colors hover:bg-slate-50 sm:px-6">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                                    <!-- Student Information -->
                                    <div class="flex min-w-0 items-start gap-4">

                                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-sm font-bold text-blue-700">
                                            <?= e(strtoupper(substr($item['mahasiswa_nama'], 0, 1))) ?>
                                        </div>

                                        <div class="min-w-0">
                                            <h3 class="truncate text-sm font-semibold text-gray-900 transition-colors group-hover:text-blue-700">
                                                <?= e($item['mahasiswa_nama']) ?>
                                            </h3>

                                            <p class="mt-1 text-xs text-gray-500">
                                                NIM: <?= e($item['nim']) ?>
                                            </p>

                                            <div class="mt-3 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-gray-500">
                                                <span class="inline-flex items-center gap-1.5">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-3.5 w-3.5 text-gray-400"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                        stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    Minggu <?= e($item['minggu_ke']) ?>
                                                </span>

                                                <span class="hidden text-slate-300 sm:inline">•</span>

                                                <span>
                                                    <?= e(date('d M Y', strtotime($item['tanggal_mulai']))) ?>
                                                    –
                                                    <?= e(date('d M Y', strtotime($item['tanggal_selesai']))) ?>
                                                </span>
                                            </div>

                                            <p class="mt-2 truncate text-xs text-gray-400">
                                                <?= e($item['nama_perusahaan']) ?>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Status and Action -->
                                    <div class="flex items-center justify-between gap-4 border-t border-slate-100 pt-3 sm:justify-end sm:border-0 sm:pt-0">

                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1.5 text-xs font-medium text-amber-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                            <?= e($item['validasi_menunggu']) ?> hari perlu validasi
                                        </span>

                                        <span class="inline-flex shrink-0 items-center gap-1 text-xs font-semibold text-blue-600 transition-colors group-hover:text-blue-800">
                                            Periksa
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 transition-transform group-hover:translate-x-0.5"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                                stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </span>

                                    </div>
                                </div>
                            </a>

                        <?php endforeach; ?>

                    </div>

                <?php else: ?>

                    <!-- Empty State -->
                    <div class="px-5 py-16 text-center sm:px-6">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-7 w-7"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.003 12.003 0 003 9c0 5.591 3.824 10.29 9 11.622C17.176 19.29 21 14.591 21 9c0-1.042-.133-2.053-.382-3.016z" />
                            </svg>
                        </div>

                        <h3 class="mt-4 text-sm font-semibold text-gray-900">
                            Tidak ada pengajuan menunggu
                        </h3>

                        <p class="mx-auto mt-1 max-w-sm text-sm leading-6 text-gray-500">
                            Semua pengajuan ketidakhadiran sudah diperiksa.
                            Pengajuan baru akan muncul di halaman ini.
                        </p>
                    </div>

                <?php endif; ?>

            </section>

        </div>
    </main>

</body>

</html>