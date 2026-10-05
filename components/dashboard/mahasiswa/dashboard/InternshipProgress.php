<?php
/** @var array $dashboard */
$progress = $dashboard['progress'] ?? ['has_application' => false, 'steps' => [], 'current_label' => ''];
$steps = $progress['steps'] ?? [];
?>

<section class="p-5 bg-white border border-gray-100 shadow-sm sm:p-7 rounded-2xl">
    <div class="flex items-start justify-between gap-3 mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Perjalanan Magang</h2>
            <p class="mt-1 text-sm text-gray-500">Status proses magang Anda saat ini.</p>
        </div>
        <?php if ($progress['has_application'] ?? false): ?>
            <span class="px-3 py-1 text-xs font-medium text-blue-700 bg-blue-50 rounded-full whitespace-nowrap">
                <?= e($progress['current_label']) ?>
            </span>
        <?php endif; ?>
    </div>

    <?php if (!($progress['has_application'] ?? false)): ?>
        <div class="p-5 text-center border border-dashed border-gray-200 rounded-xl">
            <p class="text-sm font-medium text-gray-700">Belum ada perjalanan magang.</p>
            <p class="mt-1 text-sm text-gray-400">Setelah mengajukan formasi, progres akan muncul di sini.</p>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($steps as $index => $step): ?>
                <?php
                $state = $step['state'] ?? 'pending';
                $dotClass = $state === 'completed'
                    ? 'bg-emerald-500 text-white'
                    : ($state === 'current' ? 'bg-blue-600 text-white ring-4 ring-blue-50' : 'bg-gray-100 text-gray-400');
                $lineClass = $state === 'completed' ? 'bg-emerald-200' : 'bg-gray-100';
                ?>
                <div class="flex gap-3">
                    <div class="flex flex-col items-center shrink-0">
                        <span class="flex items-center justify-center w-8 h-8 text-xs font-bold rounded-full <?= e($dotClass) ?>">
                            <?= $state === 'completed' ? '✓' : e((string) ($index + 1)) ?>
                        </span>
                        <?php if ($index < count($steps) - 1): ?>
                            <span class="w-px h-5 mt-1 <?= e($lineClass) ?>"></span>
                        <?php endif; ?>
                    </div>
                    <div class="pt-1">
                        <p class="text-sm font-medium <?= $state === 'pending' ? 'text-gray-400' : 'text-gray-800' ?>">
                            <?= e($step['label']) ?>
                        </p>
                        <?php if ($state === 'current'): ?>
                            <p class="mt-0.5 text-xs text-blue-600">Tahap saat ini</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
