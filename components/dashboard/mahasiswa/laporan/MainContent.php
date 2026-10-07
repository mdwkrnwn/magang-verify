<main class="lg:ml-64 pt-20 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Laporan Magang</h1>
                <p class="mt-1 text-sm text-gray-500">Kelola laporan mingguan dan laporan akhir magang.</p>
            </div>
            <?php if ($placement): ?>
                <a href="<?= url('/dashboard/mahasiswa/laporan/tambah?penempatan=' . (int) $placement['id']) ?>"
                   class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                    + Buat Laporan
                </a>
            <?php endif; ?>
        </div>

        <?php if (!$placement): ?>
            <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-8 text-center shadow-sm">
                <h2 class="font-semibold text-gray-900">Belum ada penempatan magang</h2>
                <p class="mt-2 text-sm text-gray-500">Laporan magang akan tersedia setelah kamu memiliki penempatan magang.</p>
            </div>
        <?php else: ?>
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm mb-6">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-400">Penempatan</p>
                        <h2 class="mt-1 font-semibold text-gray-900"><?= e($placement['judul'] ?? '-') ?></h2>
                        <p class="text-sm text-gray-500"><?= e($placement['nama_perusahaan'] ?? '-') ?></p>
                    </div>
                    <div class="text-sm text-gray-500">
                        <?= e($placement['tanggal_mulai'] ?? '-') ?> — <?= e($placement['tanggal_selesai'] ?? '-') ?>
                    </div>
                </div>
            </div>

            <?php if (!$reports): ?>
                <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-8 text-center shadow-sm">
                    <h2 class="font-semibold text-gray-900">Belum ada laporan</h2>
                    <p class="mt-2 text-sm text-gray-500">Buat laporan mingguan sesuai periode magang. Laporan akhir tersedia pada akhir periode.</p>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($reports as $report): ?>
                        <?php
                        $isWeekly = ($report['jenis_laporan'] ?? '') === 'laporan_mingguan';
                        $status = (string) ($report['status'] ?? 'draft');
                        $statusLabel = [
                            'draft' => 'Draft',
                            'diajukan' => 'Diajukan',
                            'diperiksa_dosen' => 'Diperiksa Dosen',
                            'perlu_revisi' => 'Perlu Revisi',
                            'menunggu_verifikasi' => 'Menunggu Verifikasi',
                            'terverifikasi' => 'Terverifikasi',
                            'ditolak' => 'Ditolak',
                        ][$status] ?? $status;
                        ?>
                        <a href="<?= url('/dashboard/mahasiswa/laporan/detail/' . (int) $report['id']) ?>"
                           class="block rounded-2xl border border-gray-200 bg-white p-5 shadow-sm hover:border-blue-300 hover:shadow-md transition">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-600">
                                            <?= $isWeekly ? 'Laporan Mingguan' : 'Laporan Akhir' ?>
                                        </span>
                                        <?php if ($isWeekly): ?>
                                            <span class="text-xs text-gray-500">Minggu ke-<?= (int) $report['minggu_ke'] ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <h3 class="mt-2 font-semibold text-gray-900"><?= e($report['judul']) ?></h3>
                                    <p class="mt-1 text-xs text-gray-500">Versi <?= (int) $report['versi_terkini'] ?></p>
                                </div>
                                <span class="inline-flex w-fit rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700">
                                    <?= e($statusLabel) ?>
                                </span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</main>
