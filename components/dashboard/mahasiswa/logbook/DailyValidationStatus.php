<?php
$validationStatus = (string) ($daily['status_validasi'] ?? '');
$attendanceStatus = (string) ($daily['status_kehadiran'] ?? '');
$validationPresentation = match ($validationStatus) {
    'menunggu' => ['Menunggu validasi', 'text-amber-700 bg-amber-50'],
    'disetujui' => ['Ketidakhadiran disetujui', 'text-emerald-700 bg-emerald-50'],
    'ditolak' => ['Ketidakhadiran ditolak', 'text-red-700 bg-red-50'],
    'tidak_perlu' => ['Tidak perlu validasi', 'text-gray-600 bg-gray-100'],
    default => [$validationStatus !== '' ? ucfirst(str_replace('_', ' ', $validationStatus)) : 'Belum tersedia', 'text-gray-600 bg-gray-100'],
};
?>
<div class="space-y-1.5">
    <?php if ($attendanceStatus === 'hadir'): ?>
        <span class="inline-flex px-2.5 py-1 text-[11px] font-medium text-emerald-700 bg-emerald-50 rounded-full">Hadir</span>
    <?php elseif ($attendanceStatus === 'tidak_hadir'): ?>
        <span class="inline-flex px-2.5 py-1 text-[11px] font-medium text-gray-700 bg-gray-100 rounded-full">Tidak hadir</span>
        <span class="inline-flex px-2.5 py-1 text-[11px] font-medium rounded-full <?= e($validationPresentation[1]) ?>"><?= e($validationPresentation[0]) ?></span>
        <?php if (!empty($daily['catatan_validasi'])): ?>
            <p class="max-w-xs text-xs text-gray-600" title="Catatan validator">Catatan: <?= e((string) $daily['catatan_validasi']) ?></p>
        <?php endif; ?>
    <?php else: ?>
        <span class="inline-flex px-2.5 py-1 text-[11px] font-medium text-gray-700 bg-gray-100 rounded-full">Status kehadiran belum dikenali</span>
    <?php endif; ?>
    <?php if (!empty($daily['bukti_path'])): ?>
        <a target="_blank" rel="noopener noreferrer" href="<?= e(url('/dashboard/logbook/file/evidence/' . (int) ($daily['id'] ?? 0))) ?>" class="block text-xs font-medium text-blue-600 hover:underline">Lihat bukti</a>
    <?php endif; ?>
</div>
