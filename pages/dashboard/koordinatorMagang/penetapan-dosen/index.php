<?php
$base = url('/');
$active = 'penetapan-dosen';
?>
<!DOCTYPE html>
<html lang="id">
<?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Head.php'; ?>
<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">
<?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Sidebar.php'; ?>
<?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Header.php'; ?>
<main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">
    <div class="px-4 py-6 mx-auto sm:px-6 sm:py-8 lg:px-8 max-w-6xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Penetapan Dosen Pembimbing</h1>
            <p class="mt-2 text-sm text-gray-500">Penugasan berlaku per proses magang. Tendik dan Koordinator Magang dapat menetapkan atau mengganti pembimbing dengan alasan yang tercatat.</p>
        </div>
        <?php if ($success): ?>
            <div class="p-4 mb-5 text-sm text-emerald-800 border border-emerald-200 bg-emerald-50 rounded-xl" role="status">Penugasan dosen berhasil disimpan.</div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="p-4 mb-5 text-sm text-red-800 border border-red-200 bg-red-50 rounded-xl" role="alert"><?= e($error) ?></div>
        <?php endif; ?>
        <section class="mb-6 overflow-hidden bg-white border border-gray-100 shadow-sm rounded-2xl">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-900">Pilih Mahasiswa untuk Proses Magang</h2>
                <p class="mt-1 text-xs text-gray-500">Mahasiswa yang sudah memiliki proses aktif tidak dapat dipilih lagi.</p>
            </div>
            <form method="POST" action="<?= e(url('/dashboard/koordinator-magang/penetapan-dosen')) ?>" class="flex flex-col gap-3 p-5 sm:flex-row sm:items-end">
                <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
                <input type="hidden" name="mode" value="create_process">
                <label class="flex-1 text-sm font-medium text-gray-700">Mahasiswa
                    <select name="mahasiswa_id" required class="block w-full mt-2 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Pilih mahasiswa</option>
                        <?php foreach ($eligibleStudents as $student): ?><option value="<?= e((string) $student['mahasiswa_id']) ?>"><?= e((string) $student['mahasiswa_nama']) ?> · <?= e((string) $student['nim']) ?></option><?php endforeach; ?>
                    </select>
                </label>
                <button type="submit" class="inline-flex items-center justify-center px-5 py-3 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700">Buat Proses Magang</button>
            </form>
        </section>
        <section class="overflow-hidden bg-white border border-gray-100 shadow-sm rounded-2xl">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-900">Peserta Proses Magang</h2>
                <p class="mt-1 text-xs text-gray-500">Satu mahasiswa hanya dapat memiliki satu proses magang aktif.</p>
            </div>
            <?php if ($assignments): ?>
                <div class="divide-y divide-gray-100">
                    <?php foreach ($assignments as $item): ?>
                        <article class="p-5 sm:p-6">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                <div>
                                    <h3 class="font-semibold text-gray-900"><?= e((string) $item['mahasiswa_nama']) ?> · <?= e((string) $item['nim']) ?></h3>
                                    <p class="mt-1 text-xs text-gray-400">Proses #<?= e((string) $item['proses_magang_id']) ?></p>
                                    <?php if (!empty($item['penugasan_id'])): ?>
                                        <p class="mt-2 text-sm text-emerald-700">Dosen aktif: <?= e((string) $item['dosen_nama']) ?><?= !empty($item['nidn']) ? ' · ' . e((string) $item['nidn']) : '' ?></p>
                                    <?php else: ?>
                                        <p class="mt-2 text-sm text-amber-700">Belum memiliki dosen pembimbing.</p>
                                    <?php endif; ?>
                                </div>
                                <form method="POST" action="<?= e(url('/dashboard/koordinator-magang/penetapan-dosen')) ?>" class="w-full max-w-xl space-y-3">
                                    <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
                                    <input type="hidden" name="proses_magang_id" value="<?= e((string) $item['proses_magang_id']) ?>">
                                    <input type="hidden" name="mode" value="<?= !empty($item['penugasan_id']) ? 'replace' : 'assign' ?>">
                                    <label class="block text-sm font-medium text-gray-700">Dosen pembimbing
                                        <select name="dosen_id" required class="block w-full mt-2 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                            <option value="">Pilih dosen</option>
                                            <?php foreach ($dosenOptions as $dosen): ?>
                                                <option value="<?= e((string) $dosen['id']) ?>"><?= e((string) $dosen['name']) ?><?= !empty($dosen['nidn']) ? ' · ' . e((string) $dosen['nidn']) : '' ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </label>
                                    <label class="block text-sm font-medium text-gray-700">Alasan <?= !empty($item['penugasan_id']) ? 'penggantian' : 'penetapan' ?>
                                        <textarea name="alasan" required maxlength="5000" rows="2" class="block w-full mt-2 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"></textarea>
                                    </label>
                                    <button type="submit" class="inline-flex items-center justify-center px-5 py-3 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700"><?= !empty($item['penugasan_id']) ? 'Ganti Pembimbing' : 'Tetapkan Pembimbing' ?></button>
                                </form>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="px-5 py-12 text-center text-sm text-gray-500">Belum ada proses magang aktif.</div>
            <?php endif; ?>
        </section>
    </div>
</main>
</body>
</html>
