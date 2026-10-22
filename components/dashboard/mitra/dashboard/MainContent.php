<main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">
    <div class="px-4 py-6 mx-auto sm:px-6 sm:py-8 lg:px-8 max-w-[1600px]">
        <div class="mb-6 sm:mb-8">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Dashboard Mitra</h1>
            <p class="mt-2 text-sm text-gray-500 sm:text-base"><?= e((string) ($dashboard['company']['nama_perusahaan'] ?? 'Data perusahaan belum terhubung ke akun ini')) ?></p>
        </div>

        <?php $stats = $dashboard['statistics'] ?? []; ?>
        <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 sm:gap-6 xl:grid-cols-4 sm:mb-8">
            <?php foreach ([
                ['Formasi Magang', 'formasi', 'Formasi milik perusahaan'],
                ['Pelamar', 'pelamar', 'Menunggu respons atau seleksi'],
                ['Penempatan Aktif', 'penempatan', 'Persiapan hingga penilaian'],
                ['Logbook Menunggu', 'menunggu_logbook', 'Menunggu tanda tangan mitra'],
            ] as [$label, $key, $caption]): ?>
                <div class="p-5 bg-white border border-gray-100 shadow-sm sm:p-6 rounded-2xl">
                    <p class="text-sm font-medium text-gray-500"><?= e($label) ?></p>
                    <p class="mt-3 text-3xl font-bold text-gray-900"><?= e((string) ($stats[$key] ?? 0)) ?></p>
                    <p class="mt-2 text-sm text-gray-400"><?= e($caption) ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:gap-6 xl:grid-cols-2">
            <section class="overflow-hidden bg-white border border-gray-100 shadow-sm rounded-2xl">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <div><h2 class="font-semibold text-gray-900">Logbook Menunggu Tanda Tangan</h2><p class="mt-1 text-xs text-gray-500">Hanya mahasiswa pada penempatan perusahaan Anda.</p></div>
                    <a href="<?= e(url('/dashboard/mitra/logbook')) ?>" class="text-sm font-medium text-blue-600 hover:text-blue-700">Lihat Semua →</a>
                </div>
                <?php if (!empty($items)): ?>
                    <div class="divide-y divide-gray-100">
                        <?php foreach (array_slice($items, 0, 5) as $item): ?>
                            <a href="<?= e(url('/dashboard/mitra/logbook/detail/' . (int) $item['id'])) ?>" class="block p-5 hover:bg-gray-50">
                                <p class="font-medium text-gray-900"><?= e((string) ($item['mahasiswa_nama'] ?? 'Mahasiswa')) ?> · <?= e((string) ($item['nim'] ?? '-')) ?></p>
                                <p class="mt-1 text-sm text-gray-500"><?= e((string) ($item['nama_perusahaan'] ?? '-')) ?> · Minggu <?= e((string) ($item['minggu_ke'] ?? '')) ?></p>
                                <p class="mt-1 text-xs text-amber-700">Sudah ditandatangani mahasiswa · menunggu mitra</p>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="p-8 text-sm text-center text-gray-400">Tidak ada logbook yang menunggu tanda tangan Anda.</p>
                <?php endif; ?>
            </section>

            <section class="overflow-hidden bg-white border border-gray-100 shadow-sm rounded-2xl">
                <div class="px-5 py-4 border-b border-gray-100"><h2 class="font-semibold text-gray-900">Mahasiswa Magang</h2><p class="mt-1 text-xs text-gray-500">Penempatan yang terhubung dengan perusahaan Anda.</p></div>
                <?php $students = $dashboard['students'] ?? []; ?>
                <?php if ($students): ?>
                    <div class="divide-y divide-gray-100">
                        <?php foreach ($students as $student): ?>
                            <div class="p-5">
                                <div class="flex flex-wrap items-start justify-between gap-3">
                                    <div><p class="font-medium text-gray-900"><?= e((string) $student['mahasiswa_nama']) ?></p><p class="mt-1 text-xs text-gray-500"><?= e((string) $student['nim']) ?> · <?= e((string) $student['judul']) ?></p></div>
                                    <span class="px-3 py-1 text-xs font-medium text-blue-700 bg-blue-50 rounded-full"><?= e(ucfirst(str_replace('_', ' ', (string) $student['status']))) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="p-8 text-sm text-center text-gray-400">Belum ada mahasiswa pada penempatan perusahaan ini.</p>
                <?php endif; ?>
            </section>
        </div>

        <?php include __DIR__ . '/../Footer.php'; ?>
    </div>
</main>
