<?php
$monthTimestamp = strtotime('first day of this month');
$monthTitle = date('F Y', $monthTimestamp);
$firstDay = (int) date('N', $monthTimestamp);
$daysInMonth = (int) date('t', $monthTimestamp);
$loggedDays = [];
foreach (($logbooks ?? []) as $item) {
    $start = strtotime((string) $item['tanggal_mulai']);
    $end = strtotime((string) $item['tanggal_selesai']);
    if ($start && $end) {
        for ($d = $start; $d <= $end; $d += 86400) {
            $loggedDays[] = date('Y-m-d', $d);
        }
    }
}
$loggedDays = array_flip($loggedDays);
?>
<div class="p-4 sm:p-5 bg-white border border-gray-100 shadow-sm rounded-xl">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm font-semibold text-gray-700"><?= e($monthTitle) ?></h2>
        <span class="text-xs text-gray-400">Bulan berjalan</span>
    </div>
    <div class="grid grid-cols-7 mb-3">
        <?php foreach (['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $day): ?>
            <div class="py-2 text-center text-[10px] font-medium text-gray-400"><?= e($day) ?></div>
        <?php endforeach; ?>
    </div>
    <div class="grid grid-cols-7 gap-y-2">
        <?php for ($i = 1; $i < $firstDay; $i++): ?><div class="h-8"></div><?php endfor; ?>
        <?php for ($day = 1; $day <= $daysInMonth; $day++): ?>
            <?php $dateKey = date('Y-m-d', strtotime(date('Y-m-01', $monthTimestamp) . ' +' . ($day - 1) . ' days')); ?>
            <div class="flex items-center justify-center h-8">
                <span class="flex items-center justify-center w-7 h-7 text-[10px] font-medium <?= isset($loggedDays[$dateKey]) ? 'text-blue-700 bg-blue-50' : 'text-gray-500' ?> rounded-full"><?= $day ?></span>
            </div>
        <?php endfor; ?>
    </div>
</div>
