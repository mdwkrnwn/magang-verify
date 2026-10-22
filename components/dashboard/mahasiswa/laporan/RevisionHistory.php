<?php

$revisi = is_array($revisi ?? null) ? $revisi : [];
?>
<section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
    <h2 class="font-bold text-gray-900">Riwayat Versi</h2>
    <div class="mt-4 space-y-3">
        <?php if (!$revisi): ?>
            <p class="text-sm text-gray-500">Belum ada file yang diunggah.</p>
        <?php else: ?>
            <?php foreach ($revisi as $row): ?>
                <div class="rounded-xl bg-gray-50 p-4">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="font-semibold text-gray-800">Versi <?= (int) ($row['nomor_versi'] ?? 0) ?></p>
                            <time class="text-xs text-gray-500" datetime="<?= e($row['diunggah_pada'] ?? '') ?>"><?= e($row['diunggah_pada'] ?? '') ?></time>
                        </div>
                        <a href="<?= e(url('/dashboard/mahasiswa/laporan/revisi/' . (int) ($row['id'] ?? 0) . '/download')) ?>" class="inline-flex min-h-10 items-center justify-center rounded-lg border border-gray-200 bg-white px-3 text-sm font-semibold text-blue-700 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Unduh versi ini
                        </a>
                    </div>
                    <?php if (!empty($row['ringkasan'])): ?>
                        <p class="mt-2 whitespace-pre-line break-words text-sm text-gray-600"><?= e($row['ringkasan']) ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
