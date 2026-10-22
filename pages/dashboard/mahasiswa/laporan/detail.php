<?php
$base = url('/');
$active = 'laporan';
$statusLabels = [
    'draft' => 'Draft',
    'diajukan' => 'Diajukan',
    'diperiksa_dosen' => 'Diperiksa Dosen',
    'perlu_revisi' => 'Perlu Revisi',
    'menunggu_verifikasi' => 'Menunggu Verifikasi',
    'terverifikasi' => 'Terverifikasi',
    'ditolak' => 'Ditolak',
];
$statusLabel = $statusLabels[$laporan['status']] ?? $laporan['status'];
$reviewStatusLabels = [
    'menunggu' => 'Menunggu',
    'disetujui' => 'Disetujui',
    'perlu_revisi' => 'Perlu Revisi',
    'ditolak' => 'Ditolak',
];
$reviewStageLabels = [
    'dosen' => 'Dosen',
    'koordinator' => 'Koordinator Magang',
    'tendik' => 'Tendik',
];
?>
<!DOCTYPE html>
<html lang="id">
<?php include __DIR__ . '/../../../../components/dashboard/mahasiswa/Head.php'; ?>
<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">
    <?php include __DIR__ . '/../../../../components/dashboard/mahasiswa/Sidebar.php'; ?>
    <?php include __DIR__ . '/../../../../components/dashboard/mahasiswa/Header.php'; ?>

    <main class="lg:ml-64 pt-20 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <a href="<?= url('/dashboard/mahasiswa/laporan?penempatan=' . (int) $laporan['penempatan_id']) ?>" class="text-sm text-blue-600 hover:underline">← Kembali ke laporan</a>

            <?php if ($error): ?>
                <div class="mt-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700" role="alert"><?= e($error) ?></div>
            <?php endif; ?>

            <div class="mt-5 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                    <div>
                        <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">
                            <?= $laporan['jenis_laporan'] === LaporanMagang::WEEKLY ? 'Laporan Mingguan' : 'Laporan Akhir' ?>
                        </span>
                        <h1 class="mt-3 text-2xl font-bold"><?= e($laporan['judul']) ?></h1>
                        <?php if ($laporan['minggu_ke'] !== null): ?>
                            <p class="mt-1 text-sm text-gray-500">Minggu ke-<?= (int) $laporan['minggu_ke'] ?></p>
                        <?php endif; ?>
                    </div>
                    <span class="inline-flex w-fit rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700"><?= e($statusLabel) ?></span>
                </div>

                <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-xl bg-gray-50 p-4"><p class="text-xs text-gray-500">Perusahaan</p><p class="mt-1 font-semibold"><?= e($laporan['nama_perusahaan'] ?? '-') ?></p></div>
                    <div class="rounded-xl bg-gray-50 p-4"><p class="text-xs text-gray-500">Periode</p><p class="mt-1 font-semibold"><?= e($laporan['tanggal_mulai']) ?> — <?= e($laporan['tanggal_selesai']) ?></p></div>
                    <div class="rounded-xl bg-gray-50 p-4"><p class="text-xs text-gray-500">Versi</p><p class="mt-1 font-semibold"><?= (int) $laporan['versi_terkini'] ?></p></div>
                    <div class="rounded-xl bg-gray-50 p-4"><p class="text-xs text-gray-500">Ketepatan waktu</p><p class="mt-1 font-semibold"><?= e($laporan['status_ketepatan_waktu'] ?? 'Belum ditentukan') ?></p></div>
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    <?php if ($template && !empty($template['file_template'])): ?>
                        <a href="<?= url('/dashboard/mahasiswa/laporan/template/' . (int) $laporan['id']) ?>" class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-semibold hover:bg-gray-50">Download Template</a>
                    <?php endif; ?>
                    <?php if (!empty($laporan['file_path'])): ?>
                        <a href="<?= url('/dashboard/mahasiswa/laporan/download/' . (int) $laporan['id']) ?>" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Download Laporan</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mt-5 rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-900">
                <?php if ($canUpload): ?>
                    <strong><?= $laporan['status'] === LaporanMagang::NEEDS_REVISION ? 'Tindakan berikutnya: unggah revisi.' : 'Tindakan berikutnya: unggah PDF dan ajukan laporan.' ?></strong>
                    <p class="mt-1 text-xs leading-5 text-blue-800">File PDF maksimal 10 MB. Riwayat versi sebelumnya tetap tersedia di bawah.</p>
                <?php else: ?>
                    <strong>Pengunggahan tidak tersedia pada status ini.</strong>
                    <p class="mt-1 text-xs leading-5 text-blue-800">Lihat status pemeriksaan dan catatan yang tercatat. Tombol unggah hanya tersedia untuk draft dan status yang memang mengizinkan revisi.</p>
                <?php endif; ?>
            </div>

            <?php if ($canUpload): ?>
                <?php
                $uploadHeading = $laporan['status'] === LaporanMagang::NEEDS_REVISION ? 'Unggah Revisi' : 'Unggah Laporan';
                $hasRevisionContext = $laporan['status'] === LaporanMagang::NEEDS_REVISION;
                include __DIR__ . '/../../../../components/dashboard/mahasiswa/laporan/UploadPanel.php';
                ?>
            <?php endif; ?>

            <div class="mt-6 grid gap-6 lg:grid-cols-2">
                <?php include __DIR__ . '/../../../../components/dashboard/mahasiswa/laporan/RevisionHistory.php'; ?>

                <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="font-bold">Catatan Pemeriksaan</h2>
                    <p class="mt-1 text-xs leading-5 text-gray-500">Menampilkan catatan pemeriksaan yang tersimpan. Urutan dan keputusan berikutnya mengikuti proses yang ditetapkan pengelola.</p>
                    <div class="mt-4 space-y-3">
                        <?php if (!$pemeriksaan): ?>
                            <p class="text-sm text-gray-500">Belum ada catatan pemeriksaan untuk laporan ini.</p>
                        <?php else: foreach ($pemeriksaan as $row): ?>
                            <div class="rounded-xl bg-gray-50 p-4">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <span class="font-semibold text-gray-800"><?= e($reviewStageLabels[$row['tahap_pemeriksaan']] ?? ucfirst(str_replace('_', ' ', (string) $row['tahap_pemeriksaan']))) ?></span>
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold <?= ($row['status'] ?? '') === 'disetujui' ? 'bg-emerald-50 text-emerald-700' : ((($row['status'] ?? '') === 'ditolak') ? 'bg-red-50 text-red-700' : ((($row['status'] ?? '') === 'perlu_revisi') ? 'bg-amber-50 text-amber-800' : 'bg-gray-100 text-gray-700')) ?>"><?= e($reviewStatusLabels[$row['status']] ?? ucfirst(str_replace('_', ' ', (string) $row['status']))) ?></span>
                                </div>
                                <?php if (!empty($row['nama_pemeriksa'])): ?><p class="mt-2 text-xs text-gray-500">Pemeriksa: <?= e((string) $row['nama_pemeriksa']) ?></p><?php endif; ?>
                                <?php if (!empty($row['catatan'])): ?><p class="mt-2 text-sm text-gray-600"><?= e($row['catatan']) ?></p><?php endif; ?>
                            </div>
                        <?php endforeach; endif; ?>
                    </div>
                </section>
            </div>
        </div>
    </main>
</body>
</html>
