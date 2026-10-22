<?php
$weeklyActivities = is_array($weeklyActivities ?? null) ? $weeklyActivities : [];
$selectedWeek = (int) ($selectedWeek ?? 0);
?>
<div class="mt-4 rounded-2xl border border-gray-200 bg-gray-50 p-5">
    <div>
        <h2 class="text-sm font-semibold text-gray-800">Kegiatan Magang dari Logbook</h2>
        <p class="mt-1 text-xs leading-5 text-gray-500">Kegiatan dan status kehadiran diambil dari Logbook minggu yang dipilih pada penempatan ini.</p>
    </div>
    <?php if (!empty($selectedLogbook) && $weeklyActivities): ?>
        <div class="mt-4 overflow-hidden rounded-xl border border-gray-200 bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-[680px] w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left font-semibold text-gray-600">No.</th>
                            <th scope="col" class="px-4 py-3 text-left font-semibold text-gray-600">Tanggal</th>
                            <th scope="col" class="px-4 py-3 text-left font-semibold text-gray-600">Kegiatan</th>
                            <th scope="col" class="px-4 py-3 text-left font-semibold text-gray-600">Kehadiran / Validasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($weeklyActivities as $index => $activity): ?>
                            <tr>
                                <td class="px-4 py-3 align-top text-gray-500"><?= e((string) ($index + 1)) ?></td>
                                <td class="whitespace-nowrap px-4 py-3 align-top font-medium text-gray-700">
                                    <?php $tanggal = $activity['tanggal'] ?? null; ?>
                                    <?= $tanggal ? e(date('d M Y', strtotime((string) $tanggal))) : '-' ?>
                                </td>
                                <td class="px-4 py-3 align-top text-gray-600"><?= nl2br(e((string) ($activity['kegiatan'] ?? '-'))) ?></td>
                                <td class="px-4 py-3 align-top">
                                    <?php if (($activity['status_kehadiran'] ?? '') === 'hadir'): ?>
                                        <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">Hadir</span>
                                    <?php else: ?>
                                        <?php if (($activity['status_kehadiran'] ?? '') === 'tidak_hadir'): ?>
                                            <?php
                                            $validationStatus = (string) ($activity['status_validasi'] ?? '');
                                            [$validationLabel, $validationClass] = match ($validationStatus) {
                                                'menunggu' => ['Menunggu validasi', 'bg-amber-50 text-amber-800'],
                                                'disetujui' => ['Disetujui', 'bg-emerald-50 text-emerald-800'],
                                                'ditolak' => ['Ditolak', 'bg-red-50 text-red-800'],
                                                'tidak_perlu' => ['Tidak perlu validasi', 'bg-gray-100 text-gray-700'],
                                                default => ['Status validasi belum dikenali', 'bg-gray-100 text-gray-700'],
                                            };
                                            ?>
                                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium <?= e($validationClass) ?>">Tidak hadir · <?= e($validationLabel) ?></span>
                                        <?php else: ?>
                                            <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700">Status kehadiran belum dikenali</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php elseif (!empty($selectedLogbook)): ?>
        <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800" role="status">
            Logbook minggu ke-<?= e((string) $selectedWeek) ?> belum memiliki kegiatan. Tambahkan aktivitas harian pada minggu ini sebelum membuat draft laporan mingguan.
        </div>
    <?php else: ?>
        <div class="mt-4 rounded-xl border border-gray-200 bg-white p-4 text-sm text-gray-600" role="status">
            Logbook minggu ke-<?= e((string) $selectedWeek) ?> belum tersedia pada penempatan ini. Pilih minggu yang memiliki Logbook untuk melihat sumber kegiatan.
        </div>
    <?php endif; ?>
</div>
