<section class="overflow-hidden bg-white border border-slate-100 shadow-sm rounded-2xl">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <div>
            <h2 class="font-semibold text-slate-900">Logbook Mahasiswa Bimbingan</h2>
            <p class="mt-1 text-xs text-slate-500">Status berdasarkan tahapan tanda tangan logbook.</p>
        </div>
        <span class="hidden px-3 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded-full sm:inline-flex">
            <?= count($items ?? []) ?> Menunggu
        </span>
    </div>

    <div class="divide-y divide-slate-100">
        <?php foreach (($items ?? []) as $item): ?>
            <?php
                $studentName = (string) ($item['mahasiswa_nama'] ?? 'Mahasiswa');
                $initials = function_exists('initials') ? initials($studentName) : strtoupper(mb_substr($studentName, 0, 2));
                $status = (string) ($item['status'] ?? 'draft');
                $startDate = strtotime((string) ($item['tanggal_mulai'] ?? ''));
                $endDate = strtotime((string) ($item['tanggal_selesai'] ?? ''));
                $dateRange = $startDate && $endDate ? date('d M Y', $startDate) . ' – ' . date('d M Y', $endDate) : '-';
            ?>
            <article
                data-logbook-row
                data-search="<?= e($studentName . ' ' . ($item['nim'] ?? '') . ' ' . ($item['nama_perusahaan'] ?? '') . ' Minggu ' . ($item['minggu_ke'] ?? '')) ?>"
                data-status="<?= e($status) ?>"
                class="p-5 transition hover:bg-slate-50/70"
            >
                <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
                    <div class="flex items-start gap-4 min-w-0">
                        <div class="flex items-center justify-center flex-shrink-0 w-11 h-11 font-semibold text-blue-700 bg-blue-50 rounded-xl">
                            <?= e($initials) ?>
                        </div>
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="font-semibold text-slate-900"><?= e($studentName) ?></h3>
                                <span class="text-xs text-slate-400">Minggu <?= e((string) ($item['minggu_ke'] ?? '')) ?></span>
                            </div>
                            <p class="mt-1 text-sm text-slate-500"><?= e((string) ($item['nama_perusahaan'] ?? '-')) ?></p>
                            <p class="mt-2 text-xs text-slate-400"><?= e($dateRange) ?></p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="inline-flex px-3 py-1.5 text-xs font-medium text-amber-700 bg-amber-50 rounded-full">
                            Menunggu tanda tangan Anda
                        </span>
                        <a href="<?= e(url('/dashboard/dosen/logbook/detail/' . (int) ($item['id'] ?? 0))) ?>" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-white transition bg-blue-600 rounded-xl hover:bg-blue-700">
                            Review &amp; Tanda Tangan <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>

        <div id="emptyLogbookState" class="<?= !empty($items) ? 'hidden' : '' ?> px-5 py-12 text-center">
            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 text-slate-400 bg-slate-100 rounded-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z" />
                </svg>
            </div>
            <h3 class="font-semibold text-slate-700">Belum ada logbook yang menunggu tindakan</h3>
            <p class="mt-1 text-sm text-slate-400">Logbook akan muncul setelah mahasiswa dan mitra menyelesaikan tanda tangan.</p>
        </div>
    </div>
</section>
