<div class="p-5 bg-white border border-gray-100 shadow-sm sm:p-7 rounded-2xl">
    <div class="flex flex-wrap items-start justify-between gap-3 mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Perlu Tindakan</h2>
            <p class="mt-1 text-sm text-gray-500">Logbook yang membutuhkan tanda tangan dosen pembimbing.</p>
        </div>
        <span class="px-3 py-1 text-xs font-medium text-orange-700 bg-orange-50 rounded-full">
            <?= e((string) count($pendingLogbooks ?? [])) ?> Logbook
        </span>
    </div>

    <?php if (!empty($pendingLogbooks)): ?>
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <?php foreach (array_slice($pendingLogbooks, 0, 4) as $item): ?>
                <div class="p-4 border border-gray-100 rounded-xl">
                    <p class="text-xs font-medium tracking-wide text-blue-600 uppercase">Logbook</p>
                    <h3 class="mt-1 text-sm font-semibold text-gray-900"><?= e((string) ($item['mahasiswa_nama'] ?? 'Mahasiswa')) ?></h3>
                    <p class="mt-1 text-sm text-gray-500"><?= e((string) ($item['nama_perusahaan'] ?? '-')) ?></p>
                    <p class="mt-2 text-xs text-gray-400">Minggu ke-<?= e((string) ($item['minggu_ke'] ?? '')) ?></p>
                    <a href="<?= e(url('/dashboard/dosen/logbook/detail/' . (int) $item['id'])) ?>" class="inline-flex items-center mt-4 text-sm font-medium text-blue-600 hover:text-blue-700">
                        Review &amp; tanda tangan <span class="ml-1" aria-hidden="true">→</span>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="px-4 py-8 text-center border border-dashed border-gray-200 rounded-xl">
            <p class="text-sm font-medium text-gray-700">Tidak ada logbook yang menunggu tindakan.</p>
            <p class="mt-1 text-xs text-gray-500">Logbook akan tampil setelah mahasiswa dan mitra menandatangani versi yang sama.</p>
        </div>
    <?php endif; ?>

    <div class="mt-5 text-center">
        <a href="<?= e(url('/dashboard/dosen/logbook')) ?>" class="inline-flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-blue-600 border border-blue-100 rounded-xl hover:bg-blue-50">
            Lihat Antrean Logbook
        </a>
    </div>
</div>
