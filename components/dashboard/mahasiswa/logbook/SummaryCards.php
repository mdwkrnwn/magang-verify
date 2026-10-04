<?php

/** @var array $summary */
$summary = $summary ?? ['total' => 0, 'disetujui' => 0, 'menunggu' => 0, 'revisi' => 0];
$cards = [
    ['label' => 'Total Logbook', 'value' => $summary['total'], 'tone' => 'blue'],
    ['label' => 'Disetujui', 'value' => $summary['disetujui'], 'tone' => 'emerald'],
    ['label' => 'Menunggu', 'value' => $summary['menunggu'], 'tone' => 'amber'],
    ['label' => 'Perlu Revisi', 'value' => $summary['revisi'], 'tone' => 'rose'],
];
$tones = [
    'blue' => 'bg-blue-50 text-blue-600',
    'emerald' => 'bg-emerald-50 text-emerald-600',
    'amber' => 'bg-amber-50 text-amber-600',
    'rose' => 'bg-rose-50 text-rose-600',
];
?>
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <?php foreach ($cards as $card): ?>
        <div class="p-4 bg-white border border-gray-100 shadow-sm rounded-xl">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-12 h-12 rounded-full shrink-0 <?= e($tones[$card['tone']]) ?>">
                    <span class="text-lg font-semibold"><?= e((string) $card['value']) ?></span>
                </div>
                <div>
                    <p class="text-xs text-gray-400"><?= e($card['label']) ?></p>
                    <p class="mt-1 text-sm font-semibold text-gray-800">Minggu tercatat</p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>