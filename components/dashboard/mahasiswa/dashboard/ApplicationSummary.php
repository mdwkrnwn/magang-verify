<?php
/** @var array $dashboard */
$pengajuan = $dashboard['pengajuan'] ?? null;
?>

<section class="h-full p-5 bg-white border border-gray-100 shadow-sm sm:p-7 rounded-2xl">
    <div class="flex flex-wrap items-start justify-between gap-3 mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Pengajuan Magang Terbaru</h2>
            <p class="mt-1 text-sm text-gray-500">Pantau proses pengajuan magang Anda.</p>
        </div>
        <?php if ($pengajuan): ?>
            <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full <?= e($pengajuan['status_class']) ?>">
                <?= e($pengajuan['status_label']) ?>
            </span>
        <?php endif; ?>
    </div>

    <?php if ($pengajuan): ?>
        <div class="p-4 border border-gray-100 sm:p-5 rounded-xl">
            <div class="flex items-start gap-4">
                <div class="flex items-center justify-center w-11 h-11 font-bold text-blue-600 bg-blue-50 rounded-xl shrink-0">
                    <?= e(mb_strtoupper(mb_substr((string) $pengajuan['nama_perusahaan'], 0, 2))) ?>
                </div>
                <div class="min-w-0">
                    <h3 class="text-base font-semibold text-gray-900 break-words"><?= e($pengajuan['nama_perusahaan']) ?></h3>
                    <p class="mt-1 text-sm text-gray-600"><?= e($pengajuan['judul']) ?></p>
                    <p class="mt-3 text-xs text-gray-400">Diajukan <?= e($pengajuan['tanggal_label']) ?></p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 mt-4 text-xs text-gray-500">
                <span class="px-2.5 py-1 bg-gray-50 rounded-lg"><?= e($pengajuan['lokasi_magang'] ?: 'Lokasi fleksibel') ?></span>
                <span class="px-2.5 py-1 bg-gray-50 rounded-lg"><?= e(ucfirst((string) $pengajuan['sistem_kerja'])) ?></span>
            </div>
        </div>

        <a href="<?= e(url('/dashboard/mahasiswa/pengajuan/detail/' . $pengajuan['slug'])) ?>" class="inline-flex items-center justify-center w-full gap-2 px-4 py-3 mt-5 text-sm font-semibold text-blue-600 border border-blue-100 rounded-xl hover:bg-blue-50">
            Lihat Detail Pengajuan <span aria-hidden="true">→</span>
        </a>
    <?php else: ?>
        <div class="p-6 text-center border border-dashed border-gray-200 rounded-xl">
            <p class="text-sm font-medium text-gray-700">Belum ada pengajuan magang.</p>
            <p class="mt-1 text-sm text-gray-400">Cari formasi yang sesuai dengan minat Anda.</p>
            <a href="<?= e(url('/dashboard/mahasiswa/formasi-magang')) ?>" class="inline-flex items-center gap-2 px-4 py-2.5 mt-4 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700">
                Lihat Formasi Magang <span aria-hidden="true">→</span>
            </a>
        </div>
    <?php endif; ?>
</section>
