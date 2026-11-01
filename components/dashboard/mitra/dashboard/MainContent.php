
<main class="min-h-screen bg-slate-50 pt-20 lg:ml-64">
    <div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

        <?php $stats = $dashboard['statistics'] ?? []; ?>

        <!-- Page Header -->
        <div class="mb-7">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                Dashboard Mitra
            </h1>

            <p class="mt-2 text-sm text-slate-500 sm:text-base">
                <?= e((string) ($dashboard['company']['nama_perusahaan'] ?? 'Data perusahaan belum terhubung ke akun ini')) ?>
            </p>
        </div>

        <!-- Statistics -->
        <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            <?php foreach ([
                [
                    'label' => 'Formasi Magang',
                    'key' => 'formasi',
                    'caption' => 'Formasi milik perusahaan',
                    'icon' => 'M3 7h4l2 3h12M5 7v12a1 1 0 001 1h12a1 1 0 001-1V7M9 11h6',
                ],
                [
                    'label' => 'Pelamar',
                    'key' => 'pelamar',
                    'caption' => 'Menunggu respons atau seleksi',
                    'icon' => 'M17 20h5v-2a4 4 0 00-5.5-3.7M9 20H2v-2a4 4 0 017.5-1.7M16 3.5a4 4 0 010 7.8M8 3.5a4 4 0 000 7.8',
                ],
                [
                    'label' => 'Penempatan Aktif',
                    'key' => 'penempatan',
                    'caption' => 'Persiapan hingga penilaian',
                    'icon' => 'M9 12l2 2 4-4m5 0a8 8 0 11-16 0 8 8 0 0116 0z',
                ],
                [
                    'label' => 'Logbook Menunggu',
                    'key' => 'menunggu_logbook',
                    'caption' => 'Menunggu tanda tangan mitra',
                    'icon' => 'M7 3h8l4 4v14H7a2 2 0 01-2-2V5a2 2 0 012-2Zm8 0v5h4M9 13h6M9 17h6',
                ],
            ] as $stat): ?>

                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md sm:p-6">

                    <div class="flex items-start justify-between gap-3">

                        <div>
                            <p class="text-sm font-medium text-slate-500">
                                <?= e($stat['label']) ?>
                            </p>

                            <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">
                                <?= e((string) ($stats[$stat['key']] ?? 0)) ?>
                            </p>

                            <p class="mt-2 text-xs text-slate-400">
                                <?= e($stat['caption']) ?>
                            </p>
                        </div>

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="<?= e($stat['icon']) ?>"
                                />
                            </svg>
                        </div>

                    </div>
                </div>

            <?php endforeach; ?>

        </div>

        <!-- Dashboard Content -->
        <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">

            <!-- Logbook Waiting for Signature -->
            <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

                <!-- Section Header -->
                <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">

                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 3h8l4 4v14H7a2 2 0 01-2-2V5a2 2 0 012-2Zm8 0v5h4M9 13h6M9 17h6"/>
                            </svg>
                        </div>

                        <div>
                            <h2 class="font-semibold text-slate-900">
                                Logbook Menunggu Tanda Tangan
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Logbook mahasiswa pada penempatan perusahaan Anda.
                            </p>
                        </div>
                    </div>

                    <a
                        href="<?= e(url('/dashboard/mitra/logbook')) ?>"
                        class="inline-flex w-fit shrink-0 items-center gap-2 text-sm font-medium text-blue-600 transition hover:text-blue-700"
                    >
                        Lihat Semua

                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6"/>
                        </svg>
                    </a>
                </div>

                <?php if (!empty($items)): ?>

                    <div class="divide-y divide-slate-100">

                        <?php foreach (array_slice($items, 0, 5) as $item): ?>

                            <a
                                href="<?= e(url('/dashboard/mitra/logbook/detail/' . (int) $item['id'])) ?>"
                                class="group block p-5 transition-colors hover:bg-slate-50/70 sm:px-6"
                            >

                                <div class="flex items-start gap-3">

                                    <!-- Avatar -->
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-50 text-sm font-semibold text-blue-700">
                                        <?= e(strtoupper(substr((string) ($item['mahasiswa_nama'] ?? 'M'), 0, 1))) ?>
                                    </div>

                                    <!-- Student Information -->
                                    <div class="min-w-0 flex-1">

                                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                                            <p class="font-semibold text-slate-900 transition group-hover:text-blue-600">
                                                <?= e((string) ($item['mahasiswa_nama'] ?? 'Mahasiswa')) ?>
                                            </p>

                                            <span class="inline-flex w-fit items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700">
                                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                                Menunggu tanda tangan
                                            </span>

                                        </div>

                                        <p class="mt-1 text-xs text-slate-500">
                                            NIM: <?= e((string) ($item['nim'] ?? '-')) ?>
                                        </p>

                                        <div class="mt-2 flex flex-wrap items-center gap-x-2 gap-y-1 text-xs text-slate-500">

                                            <span>
                                                <?= e((string) ($item['nama_perusahaan'] ?? '-')) ?>
                                            </span>

                                            <span class="text-slate-300">•</span>

                                            <span>
                                                Minggu <?= e((string) ($item['minggu_ke'] ?? '-')) ?>
                                            </span>

                                        </div>

                                        <p class="mt-2 text-xs text-slate-400">
                                            Sudah ditandatangani mahasiswa, menunggu tanda tangan mitra.
                                        </p>

                                    </div>

                                    <svg class="mt-3 h-4 w-4 shrink-0 text-slate-400 transition group-hover:translate-x-1 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>

                                </div>

                            </a>

                        <?php endforeach; ?>

                    </div>

                    <div class="border-t border-slate-100 px-5 py-3 sm:px-6">
                        <p class="text-xs text-slate-500">
                            Menampilkan maksimal 5 logbook terbaru.
                        </p>
                    </div>

                <?php else: ?>

                    <!-- Empty State -->
                    <div class="px-5 py-14 text-center">

                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="m5 12 4 4L19 6"/>
                            </svg>
                        </div>

                        <h3 class="mt-4 text-sm font-semibold text-slate-800">
                            Tidak Ada Logbook Menunggu
                        </h3>

                        <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">
                            Saat ini tidak ada logbook yang menunggu tanda tangan perusahaan Anda.
                        </p>

                    </div>

                <?php endif; ?>

            </section>

            <!-- Internship Students -->
            <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

                <!-- Section Header -->
                <div class="border-b border-slate-100 px-5 py-5 sm:px-6">

                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a4 4 0 00-5.5-3.7M9 20H2v-2a4 4 0 017.5-1.7M16 3.5a4 4 0 010 7.8M8 3.5a4 4 0 000 7.8"/>
                            </svg>
                        </div>

                        <div>
                            <h2 class="font-semibold text-slate-900">
                                Mahasiswa Magang
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Daftar mahasiswa yang sedang menjalani penempatan di perusahaan Anda.
                            </p>
                        </div>
                    </div>

                </div>

                <?php $students = $dashboard['students'] ?? []; ?>

                <?php if ($students): ?>

                    <div class="divide-y divide-slate-100">

                        <?php foreach ($students as $student): ?>

                            <div class="p-5 transition-colors hover:bg-slate-50/70 sm:px-6">

                                <div class="flex items-start gap-3">

                                    <!-- Avatar -->
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-50 text-sm font-semibold text-blue-700">
                                        <?= e(strtoupper(substr((string) $student['mahasiswa_nama'], 0, 1))) ?>
                                    </div>

                                    <!-- Student Information -->
                                    <div class="min-w-0 flex-1">

                                        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">

                                            <p class="font-semibold text-slate-900">
                                                <?= e((string) $student['mahasiswa_nama']) ?>
                                            </p>

                                            <span class="inline-flex w-fit shrink-0 rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700">
                                                <?= e(ucfirst(str_replace('_', ' ', (string) $student['status']))) ?>
                                            </span>

                                        </div>

                                        <p class="mt-1 text-xs text-slate-500">
                                            NIM: <?= e((string) $student['nim']) ?>
                                        </p>

                                        <p class="mt-2 text-sm leading-5 text-slate-600">
                                            <?= e((string) $student['judul']) ?>
                                        </p>

                                    </div>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                    <div class="border-t border-slate-100 px-5 py-3 sm:px-6">
                        <p class="text-xs text-slate-500">
                            Total <?= count($students) ?> mahasiswa pada penempatan perusahaan.
                        </p>
                    </div>

                <?php else: ?>

                    <!-- Empty State -->
                    <div class="px-5 py-14 text-center">

                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M17 20h5v-2a4 4 0 00-5.5-3.7M9 20H2v-2a4 4 0 017.5-1.7M16 3.5a4 4 0 010 7.8M8 3.5a4 4 0 000 7.8"/>
                            </svg>
                        </div>

                        <h3 class="mt-4 text-sm font-semibold text-slate-800">
                            Belum Ada Mahasiswa Magang
                        </h3>

                        <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">
                            Belum ada mahasiswa yang terhubung dengan penempatan perusahaan ini.
                        </p>

                    </div>

                <?php endif; ?>

            </section>

        </div>

        <?php include __DIR__ . '/../Footer.php'; ?>

    </div>
</main>
