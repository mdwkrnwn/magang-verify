<div class="p-5 bg-white border border-gray-100 shadow-sm sm:p-7 rounded-2xl">
    <div class="flex flex-wrap items-start justify-between gap-3 mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Mahasiswa Bimbingan</h2>
            <p class="mt-1 text-sm text-gray-500">Ringkasan penempatan mahasiswa yang menjadi bimbingan Anda.</p>
        </div>
        <a href="<?= e(url('/dashboard/dosen/bimbingan')) ?>" class="text-sm font-medium text-blue-600 hover:text-blue-700">Lihat Semua →</a>
    </div>

    <div class="space-y-4">
        <?php foreach (array_slice($students ?? [], 0, 3) as $student): ?>
            <?php
                $status = (string) ($student['status_penempatan'] ?? 'persiapan');
                $badge = match ($status) {
                    'berlangsung' => 'text-green-700 bg-green-50',
                    'selesai' => 'text-violet-700 bg-violet-50',
                    'dibatalkan' => 'text-red-700 bg-red-50',
                    default => 'text-blue-700 bg-blue-50',
                };
            ?>
            <div class="p-4 border border-gray-100 rounded-xl">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-gray-900"><?= e((string) ($student['mahasiswa_nama'] ?? 'Mahasiswa')) ?></p>
                        <p class="mt-1 text-xs text-gray-500"><?= e((string) ($student['program_studi'] ?? '-')) ?> · <?= e((string) ($student['nim'] ?? '-')) ?></p>
                        <p class="mt-2 text-xs text-gray-400"><?= e((string) ($student['nama_perusahaan'] ?? '-')) ?></p>
                    </div>
                    <span class="px-2.5 py-1 text-xs font-medium <?= e($badge) ?> rounded-full"><?= e((string) ($student['status_label'] ?? $status)) ?></span>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($students)): ?>
            <div class="px-4 py-8 text-center border border-dashed border-gray-200 rounded-xl">
                <p class="text-sm font-medium text-gray-700">Belum ada mahasiswa bimbingan.</p>
                <p class="mt-1 text-xs text-gray-500">Penempatan yang ditugaskan kepada Anda akan muncul di sini.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
