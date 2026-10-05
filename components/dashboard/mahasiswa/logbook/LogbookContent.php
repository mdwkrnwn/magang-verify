<?php
/** @var array|null $placement */
/** @var array $placements */
/** @var array $weeks */
/** @var array|null $nextWeek */
/** @var array $summary */
$placementStatus = $placement['status'] ?? null;
$statusClass = static function (string $status): string {
    return match ($status) {
        'disetujui' => 'text-emerald-700 bg-emerald-50',
        'menunggu_mitra', 'menunggu_dosen' => 'text-amber-700 bg-amber-50',
        'perlu_revisi' => 'text-rose-700 bg-rose-50',
        default => 'text-gray-600 bg-gray-100',
    };
};
?>

<?php if (!$placement): ?>
    <section class="flex flex-col items-center justify-center px-6 py-12 text-center bg-white border border-gray-100 shadow-sm rounded-2xl sm:py-16">
        <div class="flex items-center justify-center w-16 h-16 mb-5 text-blue-600 bg-blue-50 rounded-2xl"><span class="text-2xl">L</span></div>
        <h2 class="text-lg font-bold text-gray-900 sm:text-xl">Anda Belum Memiliki Penempatan Magang</h2>
        <p class="max-w-lg mt-2 text-sm leading-6 text-gray-500">Logbook akan tersedia setelah Anda mendapatkan penempatan magang.</p>
        <a href="<?= e(url('/dashboard/mahasiswa/formasi-magang')) ?>" class="inline-flex items-center justify-center px-4 py-2.5 mt-6 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700">Cari Formasi Magang</a>
    </section>
<?php else: ?>

    <section class="p-5 bg-white border border-gray-100 shadow-sm rounded-2xl">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-semibold tracking-wide text-gray-400 uppercase">Penempatan yang dipilih</p>
                <h2 class="mt-1 text-lg font-bold text-gray-900"><?= e($placement['nama_perusahaan']) ?></h2>
                <p class="mt-1 text-sm text-gray-500"><?= e($placement['judul']) ?> · <?= e(date('d M Y', strtotime($placement['tanggal_mulai']))) ?> – <?= e(date('d M Y', strtotime($placement['tanggal_selesai']))) ?></p>
            </div>
            <?php if ($placements): ?>
                <form method="GET" class="flex flex-col gap-2 sm:flex-row sm:items-center">
                    <label for="penempatan" class="text-xs font-medium text-gray-500">Riwayat magang</label>
                    <select id="penempatan" name="penempatan" onchange="this.form.submit()" class="px-3 py-2.5 text-sm bg-white border border-gray-200 rounded-xl">
                        <?php foreach ($placements as $p): ?>
                            <option value="<?= e((string) $p['id']) ?>" <?= (int) $p['id'] === (int) $placement['id'] ? 'selected' : '' ?>><?= e($p['nama_perusahaan']) ?> · <?= e(date('d M Y', strtotime($p['tanggal_mulai']))) ?></option>
                        <?php endforeach; ?>
                    </select>
                </form>
            <?php endif; ?>
        </div>
    </section>

    <div class="grid grid-cols-2 gap-3 mt-4 lg:grid-cols-4">
        <?php foreach ([['Total Minggu',$summary['total'],'blue'],['Selesai',$summary['disetujui'],'emerald'],['Menunggu',$summary['menunggu'],'amber'],['Perlu Diperbaiki',$summary['revisi'],'rose']] as [$label,$value,$tone]): ?>
            <div class="p-4 bg-white border border-gray-100 shadow-sm rounded-xl"><p class="text-xs text-gray-400"><?= e($label) ?></p><p class="mt-1 text-2xl font-bold <?= $tone==='emerald'?'text-emerald-600':($tone==='amber'?'text-amber-600':($tone==='rose'?'text-rose-600':'text-blue-600')) ?>"><?= e((string)$value) ?></p></div>
        <?php endforeach; ?>
    </div>

    <?php if ($nextWeek && !empty($nextWeek['sudah_bisa_dibuat'])): ?>
        <div class="p-4 mt-4 text-sm text-blue-800 bg-blue-50 border border-blue-200 rounded-xl">
            <strong>Minggu berikutnya tersedia:</strong> Minggu <?= e((string)$nextWeek['minggu_ke']) ?> · <?= e(date('d M Y', strtotime($nextWeek['tanggal_mulai']))) ?> – <?= e(date('d M Y', strtotime($nextWeek['tanggal_selesai']))) ?>.
            <?php if (!empty($nextWeek['terlambat'])): ?> Minggu ini dibuat terlambat dan tetap diperbolehkan karena periodenya sudah lewat.<?php endif; ?>
        </div>
    <?php elseif ($placementStatus === 'persiapan'): ?>
        <div class="p-4 mt-4 text-sm text-amber-800 bg-amber-50 border border-amber-200 rounded-xl">Penempatan sudah ada, tetapi periode magang belum dimulai.</div>
    <?php elseif (!$weeks): ?>
        <div class="p-4 mt-4 text-sm text-gray-600 bg-gray-50 border border-gray-200 rounded-xl">Belum ada minggu logbook. Minggu pertama akan mengikuti tanggal mulai penempatan, bukan dipaksa dimulai hari Senin.</div>
    <?php endif; ?>

    <section class="mt-4 overflow-hidden bg-white border border-gray-100 shadow-sm rounded-2xl">
        <div class="flex flex-col gap-3 px-5 py-4 border-b border-gray-100 sm:flex-row sm:items-center sm:justify-between">
            <div><h2 class="text-base font-semibold text-gray-900">Minggu Logbook</h2><p class="mt-1 text-xs text-gray-400">Setiap minggu berisi satu aktivitas untuk setiap tanggal dalam periodenya.</p></div>
            <?php if ($weeks): ?><a href="<?= e(url('/dashboard/mahasiswa/logbook/download-semua/' . (int)$placement['id'])) ?>" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Download semua minggu</a><?php endif; ?>
        </div>
        <?php if ($weeks): ?>
            <div class="divide-y divide-gray-100">
                <?php foreach ($weeks as $week): ?>
                    <?php $locked = in_array($week['status'], ['menunggu_dosen','disetujui'], true); ?>
                    <div class="p-5">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2"><h3 class="text-sm font-bold text-gray-900">Minggu <?= e((string)$week['minggu_ke']) ?></h3><span class="inline-flex px-2.5 py-1 text-[10px] font-medium rounded-full <?= e($statusClass((string)$week['status'])) ?>"><?= e(match($week['status']) { 'draft' => 'Belum ditandatangani mahasiswa', 'menunggu_mitra' => 'Menunggu tanda tangan mitra', 'menunggu_dosen' => 'Menunggu tanda tangan dosen', 'disetujui' => 'Selesai ditandatangani', default => ucfirst(str_replace('_',' ',$week['status'])) }) ?></span></div>
                                <p class="mt-1 text-xs text-gray-500"><?= e(date('d M Y',strtotime($week['tanggal_mulai']))) ?> – <?= e(date('d M Y',strtotime($week['tanggal_selesai']))) ?></p>
                                <p class="mt-2 text-xs text-gray-400">Hari terisi: <?= e((string)$week['hari_terisi']) ?> · Validasi menunggu: <?= e((string)$week['validasi_menunggu']) ?></p>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <a href="<?= e(url('/dashboard/mahasiswa/logbook/detail/'.(int)$week['id'])) ?>" class="inline-flex items-center justify-center px-3 py-2 text-xs font-semibold text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100">Lihat detail</a>
                                <?php if (!$locked): ?><a href="<?= e(url('/dashboard/mahasiswa/logbook/hari/tambah/'.(int)$week['id'])) ?>" class="inline-flex items-center justify-center px-3 py-2 text-xs font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">Tambah hari</a><?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="p-10 text-center"><p class="text-sm font-medium text-gray-700">Belum ada minggu logbook.</p><p class="mt-1 text-sm text-gray-400">Buat minggu pertama setelah periode penempatan dimulai.</p></div>
        <?php endif; ?>
    </section>
<?php endif; ?>
