<?php
$base = url('/');
$active = 'pembimbing';
?>
<!DOCTYPE html>
<html lang="id">
<?php include __DIR__ . '/../../../../components/dashboard/mahasiswa/Head.php'; ?>
<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">
<?php include __DIR__ . '/../../../../components/dashboard/mahasiswa/Sidebar.php'; ?>
<?php include __DIR__ . '/../../../../components/dashboard/mahasiswa/Header.php'; ?>
<main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">
    <div class="px-4 py-6 mx-auto sm:px-6 sm:py-8 lg:px-8 max-w-6xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Permintaan Dosen Pembimbing</h1>
            <p class="mt-2 text-sm text-gray-500">Dosen pembimbing ditetapkan oleh Koordinator Magang.</p>
        </div>

        <?php if ($success): ?>
            <div class="p-4 mb-5 text-sm text-emerald-800 border border-emerald-200 bg-emerald-50 rounded-xl" role="status">Permintaan pembimbing berhasil dikirim.</div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="p-4 mb-5 text-sm text-red-800 border border-red-200 bg-red-50 rounded-xl" role="alert"><?= e($error) ?></div>
        <?php endif; ?>

        <section class="p-5 mb-6 bg-white border border-gray-100 shadow-sm sm:p-7 rounded-2xl">
            <h2 class="text-lg font-semibold text-gray-900">Penempatan Magang</h2>
            <div class="mt-4 space-y-4">
                <?php foreach ($placements as $placement): ?>
                    <?php
                        $eligible = $placement['status_pendaftaran'] === 'diterima'
                            && in_array($placement['status_penempatan'], ['persiapan', 'berlangsung'], true)
                            && $placement['dosen_pembimbing_id'] !== null
                            && ($placement['status_permintaan'] ?? null) !== 'menunggu';
                        $start = strtotime((string) $placement['tanggal_mulai']);
                        $end = strtotime((string) $placement['tanggal_selesai']);
                    ?>
                    <article class="p-4 border border-gray-100 rounded-xl">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0">
                                <h3 class="font-semibold text-gray-900"><?= e((string) $placement['judul']) ?></h3>
                                <p class="mt-1 text-sm text-gray-500"><?= e((string) $placement['nama_perusahaan']) ?></p>
                                <p class="mt-1 text-xs text-gray-400">
                                    <?= $start && $end ? e(date('d M Y', $start) . ' – ' . date('d M Y', $end)) : '-' ?>
                                </p>
                            </div>
                            <?php if ($placement['dosen_pembimbing_id'] !== null): ?>
                                <span class="px-3 py-1 text-xs font-medium text-emerald-700 bg-emerald-50 rounded-full">Pembimbing: <?= e((string) ($placement['dosen_nama'] ?? 'Sudah ditetapkan')) ?></span>
                            <?php elseif (($placement['status_permintaan'] ?? '') === 'menunggu'): ?>
                                <span class="px-3 py-1 text-xs font-medium text-amber-700 bg-amber-50 rounded-full">Menunggu respons dosen</span>
                            <?php else: ?>
                                <span class="px-3 py-1 text-xs font-medium text-blue-700 bg-blue-50 rounded-full"><?= e(ucfirst(str_replace('_', ' ', (string) $placement['status_penempatan']))) ?></span>
                            <?php endif; ?>
                        </div>

                        <?php if ($placement['dosen_pembimbing_id'] === null): ?>
                            <p class="mt-4 text-xs text-amber-700">Belum ada dosen pembimbing yang ditetapkan oleh pengelola.</p>
                        <?php elseif ($eligible): ?>
                            <form method="POST" action="<?= e(url('/dashboard/mahasiswa/pembimbing')) ?>" class="mt-5 space-y-4">
                                <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
                                <input type="hidden" name="penempatan_id" value="<?= e((string) $placement['penempatan_id']) ?>">
                                <label class="block text-sm font-medium text-gray-700">
                                    Topik dan deskripsi bimbingan
                                    <textarea name="alasan_pengajuan" required maxlength="5000" rows="3" class="block w-full mt-2 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: konsultasi rencana kegiatan minggu pertama dan target luaran magang."></textarea>
                                </label>
                                <div>
                                    <button type="submit" class="inline-flex items-center justify-center px-5 py-3 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700">Kirim Permintaan Bimbingan</button>
                                </div>
                            </form>
                        <?php elseif (($placement['status_permintaan'] ?? '') === 'menunggu'): ?>
                            <p class="mt-4 text-xs text-gray-500">Permintaan bimbingan Anda sedang menunggu respons dosen pembimbing.</p>
                        <?php elseif ($placement['status_pendaftaran'] !== 'diterima' || !in_array($placement['status_penempatan'], ['persiapan', 'berlangsung'], true)): ?>
                            <p class="mt-4 text-xs text-gray-500">Permintaan bimbingan tersedia setelah pengajuan diterima dan penempatan berada pada tahap persiapan atau berlangsung.</p>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
                <?php if (!$placements): ?>
                    <div class="px-4 py-10 text-center border border-dashed border-gray-200 rounded-xl">
                        <p class="text-sm font-medium text-gray-700">Belum ada penempatan magang.</p>
                        <p class="mt-1 text-xs text-gray-500">Permintaan pembimbing dapat diajukan setelah pengajuan magang diterima dan penempatan dibuat.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <section class="overflow-hidden bg-white border border-gray-100 shadow-sm rounded-2xl">
            <div class="px-5 py-4 border-b border-gray-100"><h2 class="font-semibold text-gray-900">Riwayat Permintaan</h2></div>
            <?php if ($history): ?>
                <div class="divide-y divide-gray-100">
                    <?php foreach ($history as $request): ?>
                        <?php $statusClass = match ($request['status_permintaan']) { 'disetujui' => 'text-emerald-700 bg-emerald-50', 'ditolak' => 'text-red-700 bg-red-50', default => 'text-amber-700 bg-amber-50' }; ?>
                        <article class="p-5">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <h3 class="font-semibold text-gray-900"><?= e((string) $request['dosen_nama']) ?></h3>
                                    <p class="mt-1 text-sm text-gray-500"><?= e((string) $request['nama_perusahaan']) ?> · <?= e((string) $request['judul']) ?></p>
                                    <p class="mt-2 text-sm text-gray-700"><?= e((string) $request['alasan_pengajuan']) ?></p>
                                    <?php if (!empty($request['catatan_dosen'])): ?><p class="mt-2 text-xs text-gray-500">Catatan dosen: <?= e((string) $request['catatan_dosen']) ?></p><?php endif; ?>
                                </div>
                                <span class="px-3 py-1 text-xs font-medium <?= e($statusClass) ?> rounded-full"><?= e(ucfirst((string) $request['status_permintaan'])) ?></span>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="p-8 text-sm text-center text-gray-400">Belum ada riwayat permintaan.</p>
            <?php endif; ?>
        </section>
    </div>
</main>
</body>
</html>
