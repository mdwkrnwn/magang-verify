
<?php
$base = url('/');
$active = 'logbook';
?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Head.php'; ?>

<body class="overflow-x-hidden bg-slate-50 font-[Poppins] text-gray-900">

    <?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Sidebar.php'; ?>
    <?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Header.php'; ?>

    <main class="min-h-screen bg-slate-50 pt-20 lg:ml-64">
        <div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

            <!-- Page Header -->
            <div class="mb-7">
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                    Validasi Logbook
                </h1>

                <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500 sm:text-base">
                    Tinjau pengajuan ketidakhadiran mahasiswa selama pelaksanaan magang
                    dan lakukan validasi berdasarkan alasan serta bukti yang diberikan.
                </p>
            </div>

            <!-- Validation Queue -->
            <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

                <!-- Section Header -->
                <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">

                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14H4V6a1 1 0 011-1Z"/>
                            </svg>
                        </div>

                        <div>
                            <h2 class="font-semibold text-slate-900">
                                Antrean Validasi
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Daftar logbook mahasiswa yang memiliki pengajuan ketidakhadiran.
                            </p>
                        </div>
                    </div>

                    <?php if ($items): ?>
                        <span class="inline-flex w-fit items-center rounded-full bg-amber-50 px-3 py-1.5 text-xs font-medium text-amber-700">
                            <?= count($items) ?> mahasiswa
                        </span>
                    <?php endif; ?>

                </div>

                <?php if ($items): ?>

                    <!-- Queue List -->
                    <div class="divide-y divide-slate-100">

                        <?php foreach ($items as $item): ?>

                            <a
                                href="<?= e(url('/dashboard/koordinator-magang/logbook/detail/' . (int) $item['id'])) ?>"
                                class="group block p-5 transition-colors hover:bg-slate-50/70 sm:px-6"
                            >

                                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                                    <!-- Student Information -->
                                    <div class="flex min-w-0 items-start gap-3">

                                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-50 text-sm font-semibold text-blue-700">
                                            <?= e(strtoupper(substr((string) $item['mahasiswa_nama'], 0, 1))) ?>
                                        </div>

                                        <div class="min-w-0">
                                            <h3 class="font-semibold text-slate-900 transition group-hover:text-blue-600">
                                                <?= e((string) $item['mahasiswa_nama']) ?>
                                            </h3>

                                            <p class="mt-1 text-sm text-slate-500">
                                                NIM: <?= e((string) $item['nim']) ?>
                                            </p>

                                            <div class="mt-2 flex flex-wrap items-center gap-x-2 gap-y-1 text-xs text-slate-500">

                                                <span class="inline-flex items-center gap-1.5">
                                                    <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14H4V6a1 1 0 011-1Z"/>
                                                    </svg>

                                                    Minggu <?= e((string) $item['minggu_ke']) ?>
                                                </span>

                                                <span class="text-slate-300">•</span>

                                                <span>
                                                    <?= e(date('d M Y', strtotime((string) $item['tanggal_mulai']))) ?>
                                                    –
                                                    <?= e(date('d M Y', strtotime((string) $item['tanggal_selesai']))) ?>
                                                </span>

                                            </div>
                                        </div>

                                    </div>

                                    <!-- Validation Status and Action -->
                                    <div class="flex flex-wrap items-center justify-between gap-3 pl-14 lg:justify-end lg:pl-0">

                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1.5 text-xs font-medium text-amber-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                            <?= e((string) $item['validasi_menunggu']) ?> hari menunggu
                                        </span>

                                        <span class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 transition group-hover:text-blue-700">
                                            Periksa Pengajuan

                                            <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6"/>
                                            </svg>
                                        </span>

                                    </div>

                                </div>

                            </a>

                        <?php endforeach; ?>

                    </div>

                    <!-- List Footer -->
                    <div class="border-t border-slate-100 px-5 py-3 sm:px-6">
                        <p class="text-xs text-slate-500">
                            Menampilkan <?= count($items) ?> antrean validasi logbook.
                        </p>
                    </div>

                <?php else: ?>

                    <!-- Empty State -->
                    <div class="px-5 py-16 text-center">

                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="m5 12 4 4L19 6"/>
                            </svg>
                        </div>

                        <h3 class="mt-4 text-sm font-semibold text-slate-800">
                            Tidak Ada Pengajuan Menunggu Validasi
                        </h3>

                        <p class="mx-auto mt-1 max-w-md text-sm text-slate-500">
                            Saat ini tidak ada ketidakhadiran mahasiswa yang perlu ditinjau.
                            Pengajuan baru akan muncul di sini setelah mahasiswa mengirimkannya.
                        </p>

                    </div>

                <?php endif; ?>

            </section>

            <?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Footer.php'; ?>

        </div>
    </main>

    <?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Script.php'; ?>

</body>
</html>
