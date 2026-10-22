<section class="bg-white border border-slate-100 shadow-sm rounded-2xl overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <div>
            <h2 class="font-semibold text-slate-900">Mahasiswa Bimbingan</h2>
            <p class="mt-1 text-xs text-slate-500"><?= e((string) ($statistics['mahasiswa'] ?? 0)) ?> mahasiswa dalam penempatan yang menjadi bimbingan Anda</p>
        </div>
        <span class="hidden px-3 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded-full sm:inline-flex">Data Bimbingan</span>
    </div>

    <div class="divide-y divide-slate-100">
        <?php foreach (($students ?? []) as $student): ?>
            <?php
                $studentName = (string) ($student['mahasiswa_nama'] ?? 'Mahasiswa');
                $status = (string) ($student['status_penempatan'] ?? 'persiapan');
                $initials = function_exists('initials') ? initials($studentName) : strtoupper(mb_substr($studentName, 0, 2));
                $start = strtotime((string) ($student['tanggal_mulai'] ?? ''));
                $end = strtotime((string) ($student['tanggal_selesai'] ?? ''));
                $period = $start && $end ? date('d M Y', $start) . ' – ' . date('d M Y', $end) : '-';
                [$badgeClass, $dotClass] = match ($status) {
                    'berlangsung' => ['text-emerald-700 bg-emerald-50', 'bg-emerald-500'],
                    'selesai' => ['text-violet-700 bg-violet-50', 'bg-violet-500'],
                    'dibatalkan' => ['text-red-700 bg-red-50', 'bg-red-500'],
                    default => ['text-amber-700 bg-amber-50', 'bg-amber-500'],
                };
            ?>
            <article
                data-student-row
                data-search="<?= e($studentName . ' ' . ($student['nim'] ?? '') . ' ' . ($student['nama_perusahaan'] ?? '') . ' ' . ($student['judul'] ?? '')) ?>"
                data-status="<?= e($status) ?>"
                class="p-5 transition hover:bg-slate-50/70"
            >
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-start gap-4 min-w-0">
                        <div class="flex items-center justify-center flex-shrink-0 w-11 h-11 font-semibold text-blue-700 bg-blue-50 rounded-xl">
                            <?= e($initials) ?>
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-semibold text-slate-900"><?= e($studentName) ?></h3>
                            <p class="mt-1 text-sm text-slate-500"><?= e((string) ($student['nim'] ?? '-')) ?><?= !empty($student['program_studi']) ? ' · ' . e((string) $student['program_studi']) : '' ?></p>
                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium <?= e($badgeClass) ?> rounded-lg">
                                    <span class="w-1.5 h-1.5 rounded-full <?= e($dotClass) ?>"></span>
                                    <?= e((string) ($student['status_label'] ?? $status)) ?>
                                </span>
                                <span class="text-xs text-slate-400"><?= e((string) ($student['nama_perusahaan'] ?? '-')) ?></span>
                            </div>
                            <p class="mt-2 text-xs text-slate-400"><?= e((string) ($student['judul'] ?? '')) ?> · <?= e($period) ?></p>
                        </div>
                    </div>
                    <div class="text-xs text-slate-500 lg:text-right">
                        Penempatan #<?= e((string) ($student['penempatan_id'] ?? '')) ?>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>

        <div id="emptyStudentState" class="<?= !empty($students) ? 'hidden' : '' ?> px-5 py-12 text-center">
            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 text-slate-400 bg-slate-100 rounded-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l5 5m-5-5a7 7 0 1110-10 7 7 0 01-10 10z" />
                </svg>
            </div>
            <h3 class="font-semibold text-slate-700">Belum ada mahasiswa bimbingan</h3>
            <p class="mt-1 text-sm text-slate-400">Penempatan mahasiswa yang ditugaskan kepada Anda akan muncul di sini.</p>
        </div>
    </div>
</section>
