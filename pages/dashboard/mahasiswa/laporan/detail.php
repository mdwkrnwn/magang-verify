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
                <div class="mt-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700"><?= e($error) ?></div>
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

            <?php if ($canUpload): ?>
                <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="font-bold"><?= $laporan['status'] === LaporanMagang::NEEDS_REVISION ? 'Unggah Revisi' : 'Unggah Laporan' ?></h2>
                    <p class="mt-1 text-sm text-gray-500">File harus berupa PDF maksimal 10 MB.</p>
                    <form
    action="<?= e($formAction) ?>"
    method="POST"
    enctype="multipart/form-data"
>
    <input
        type="hidden"
        name="_csrf_token"
        value="<?= e(csrfToken()) ?>"
    >
                        <input type="file" name="file_laporan" accept="application/pdf,.pdf" required class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm">
                        <textarea name="ringkasan" rows="3" maxlength="5000" placeholder="Ringkasan laporan / perubahan (opsional)" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"></textarea>
                        <?php if ($laporan['status'] === LaporanMagang::NEEDS_REVISION): ?>
                            <textarea name="catatan_revisi" rows="3" maxlength="5000" placeholder="Catatan terkait revisi (opsional)" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"></textarea>
                        <?php endif; ?>
                        <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Unggah dan Ajukan</button>
                    </form>
                </div>
            <?php endif; ?>

            <div class="mt-6 grid gap-6 lg:grid-cols-2">
                <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="font-bold">Riwayat Versi</h2>
                    <div class="mt-4 space-y-3">
                        <?php if (!$revisi): ?>
                            <p class="text-sm text-gray-500">Belum ada file yang diunggah.</p>
                        <?php else: foreach ($revisi as $row): ?>
                            <div class="rounded-xl bg-gray-50 p-4">
                                <div class="flex justify-between gap-3"><span class="font-semibold">Versi <?= (int) $row['nomor_versi'] ?></span><span class="text-xs text-gray-500"><?= e($row['diunggah_pada']) ?></span></div>
                                <?php if (!empty($row['ringkasan'])): ?><p class="mt-2 text-sm text-gray-600"><?= e($row['ringkasan']) ?></p><?php endif; ?>
                            </div>
                        <?php endforeach; endif; ?>
                    </div>
                </section>

                <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="font-bold">Pemeriksaan</h2>
                    <div class="mt-4 space-y-3">
                        <?php if (!$pemeriksaan): ?>
                            <p class="text-sm text-gray-500">Belum ada pemeriksaan.</p>
                        <?php else: foreach ($pemeriksaan as $row): ?>
                            <div class="rounded-xl bg-gray-50 p-4">
                                <div class="flex justify-between gap-3"><span class="font-semibold capitalize"><?= e($row['tahap_pemeriksaan']) ?></span><span class="text-xs text-gray-500"><?= e($row['status']) ?></span></div>
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
