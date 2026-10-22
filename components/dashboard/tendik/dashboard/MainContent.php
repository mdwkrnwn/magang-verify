<main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">
    <div class="px-4 py-6 mx-auto sm:px-6 sm:py-8 lg:px-8 max-w-[1600px]">
        <div class="mb-6 sm:mb-8">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Dashboard Tendik / Manajemen</h1>
            <p class="mt-2 text-sm text-gray-500 sm:text-base">Validasi alasan ketidakhadiran logbook sesuai kewenangan administrasi.</p>
        </div>

        <?php
            $pendingDays = array_sum(array_map(static fn(array $item): int => (int) ($item['validasi_menunggu'] ?? 0), $items ?? []));
            $pendingWeeks = count($items ?? []);
        ?>
        <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 sm:gap-6 xl:grid-cols-4 sm:mb-8">
            <?php foreach ([
                ['Minggu Menunggu Validasi', $pendingWeeks, 'Logbook mingguan dengan pengajuan bukti'],
                ['Catatan Harian Menunggu', $pendingDays, 'Alasan ketidakhadiran untuk diperiksa'],
            ] as [$label, $value, $caption]): ?>
                <div class="p-5 bg-white border border-gray-100 shadow-sm sm:p-6 rounded-2xl">
                    <p class="text-sm font-medium text-gray-500"><?= e($label) ?></p>
                    <p class="mt-3 text-3xl font-bold text-gray-900"><?= e((string) $value) ?></p>
                    <p class="mt-2 text-sm text-gray-400"><?= e($caption) ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <section class="overflow-hidden bg-white border border-gray-100 shadow-sm rounded-2xl">
            <div class="px-5 py-4 border-b border-gray-100 sm:px-7">
                <h2 class="text-lg font-semibold text-gray-900">Antrean Validasi Logbook</h2>
                <p class="mt-1 text-sm text-gray-500">Keputusan validasi dilakukan pada setiap catatan harian melalui detail minggu.</p>
            </div>
            <?php if (!empty($items)): ?>
                <div class="divide-y divide-gray-100">
                    <?php foreach ($items as $item): ?>
                        <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between">
                            <div class="min-w-0">
                                <p class="font-semibold text-gray-900"><?= e((string) ($item['mahasiswa_nama'] ?? 'Mahasiswa')) ?> <span class="font-normal text-gray-400">· <?= e((string) ($item['nim'] ?? '-')) ?></span></p>
                                <p class="mt-1 text-sm text-gray-500"><?= e((string) ($item['nama_perusahaan'] ?? '-')) ?> · Minggu <?= e((string) ($item['minggu_ke'] ?? '')) ?></p>
                                <p class="mt-1 text-xs text-amber-700"><?= e((string) ($item['validasi_menunggu'] ?? 0)) ?> catatan menunggu validasi</p>
                            </div>
                            <a href="<?= e(url('/dashboard/tendik/logbook/detail/' . (int) $item['id'])) ?>" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-blue-600 border border-blue-100 rounded-xl hover:bg-blue-50">Periksa Bukti</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="px-5 py-12 text-center">
                    <p class="text-sm font-medium text-gray-700">Tidak ada catatan yang menunggu validasi.</p>
                    <p class="mt-1 text-xs text-gray-500">Antrean akan muncul saat mahasiswa mengirim bukti ketidakhadiran.</p>
                </div>
            <?php endif; ?>
        </section>

        <?php include __DIR__ . '/../Footer.php'; ?>
    </div>
</main>
