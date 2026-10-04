<?php

/** @var array $logbooks */
$logbooks = $logbooks ?? [];
$statusClass = static function (string $status): string {
    return match ($status) {
        'disetujui' => 'text-emerald-600 bg-emerald-50',
        'perlu_revisi', 'ditolak' => 'text-rose-600 bg-rose-50',
        'draft' => 'text-gray-600 bg-gray-100',
        default => 'text-amber-600 bg-amber-50',
    };
};
$statusLabel = static function (string $status): string {
    return match ($status) {
        'diajukan' => 'Diajukan',
        'menunggu_mitra' => 'Menunggu Mitra',
        'menunggu_dosen' => 'Menunggu Dosen',
        'menunggu_verifikasi' => 'Menunggu Verifikasi',
        'perlu_revisi' => 'Perlu Revisi',
        'disetujui' => 'Disetujui',
        'ditolak' => 'Ditolak',
        default => 'Draft',
    };
};
?>
<div class="overflow-hidden bg-white border border-gray-100 shadow-sm rounded-xl">
    <div class="px-4 py-4 border-b border-gray-100">
        <h2 class="text-sm font-semibold text-gray-700">Logbook Terbaru</h2>
    </div>

    <?php if ($logbooks): ?>
        <div>
            <?php foreach ($logbooks as $item): ?>
                <div class="grid grid-cols-[68px_minmax(0,1fr)] gap-3 px-4 py-3 border-b border-gray-100 last:border-b-0 sm:grid-cols-[70px_minmax(0,1fr)_110px] sm:items-center">
                    <div class="text-xs font-medium text-gray-500">Minggu <?= e((string) $item['minggu_ke']) ?></div>
                    <div class="min-w-0">
                        <h3 class="text-xs font-semibold text-gray-700">Minggu <?= e((string) $item['minggu_ke']) ?> · <?= e(date('d M', strtotime($item['tanggal_mulai']))) ?> - <?= e(date('d M Y', strtotime($item['tanggal_selesai']))) ?></h3>
                        <p class="mt-1 text-[11px] text-gray-400 truncate"><?= e($item['aktivitas'] ?? '-') ?></p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            <?php if (in_array($item['status'], ['draft', 'perlu_revisi'], true)): ?>
                                <a href="<?= e(url('/dashboard/mahasiswa/logbook/edit/' . (int) $item['id'])) ?>" class="text-[11px] font-semibold text-blue-600 hover:text-blue-700">Edit</a>
                            <?php endif; ?>
                            <?php if (in_array($item['status'], ['draft', 'perlu_revisi'], true)): ?>
                                <form method="POST" action="<?= e(url('/dashboard/mahasiswa/logbook/kirim/' . (int) $item['id'])) ?>" class="inline">
                                    <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
                                    <button type="submit" class="text-[11px] font-semibold text-emerald-600 hover:text-emerald-700">Ajukan</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="flex sm:justify-end">
                        <span class="inline-flex px-2.5 py-1 text-[10px] font-medium rounded-full <?= e($statusClass((string) $item['status'])) ?>">
                            <?= e($statusLabel((string) $item['status'])) ?>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="p-8 text-center">
            <p class="text-sm font-medium text-gray-700">Belum ada logbook.</p>
            <p class="mt-1 text-sm text-gray-400">Logbook mingguan yang Anda buat akan muncul di sini.</p>
        </div>
    <?php endif; ?>
</div>