<?php
$base = url('/');
$active = 'permintaan-pembimbing';
?>
<!DOCTYPE html>
<html lang="id">
<?php include __DIR__ . '/../../../../components/dashboard/dosen/Head.php'; ?>
<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">
<?php include __DIR__ . '/../../../../components/dashboard/dosen/Sidebar.php'; ?>
<?php include __DIR__ . '/../../../../components/dashboard/dosen/Header.php'; ?>
<main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">
    <div class="px-4 py-6 mx-auto sm:px-6 sm:py-8 lg:px-8 max-w-6xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Permintaan Dosen Pembimbing</h1>
            <p class="mt-2 text-sm text-gray-500">Tinjau permintaan mahasiswa yang ditujukan kepada Anda. Persetujuan menetapkan Anda sebagai pembimbing penempatan.</p>
        </div>

        <?php if ($success): ?>
            <div class="p-4 mb-5 text-sm text-emerald-800 border border-emerald-200 bg-emerald-50 rounded-xl" role="status">Keputusan permintaan berhasil disimpan.</div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="p-4 mb-5 text-sm text-red-800 border border-red-200 bg-red-50 rounded-xl" role="alert"><?= e($error) ?></div>
        <?php endif; ?>

        <section class="overflow-hidden bg-white border border-gray-100 shadow-sm rounded-2xl">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-900">Menunggu Keputusan</h2>
                <p class="mt-1 text-xs text-gray-500"><?= e((string) count($requests)) ?> permintaan menunggu respons</p>
            </div>
            <?php if ($requests): ?>
                <div class="divide-y divide-gray-100">
                    <?php foreach ($requests as $request): ?>
                        <?php $submitted = strtotime((string) $request['tanggal_pengajuan']); ?>
                        <article class="p-5 sm:p-6">
                            <div class="grid grid-cols-1 gap-5 lg:grid-cols-[1fr_1.2fr]">
                                <div>
                                    <div class="flex items-start gap-3">
                                        <div class="flex items-center justify-center flex-shrink-0 w-11 h-11 font-semibold text-blue-700 bg-blue-50 rounded-xl"><?= e(function_exists('initials') ? initials((string) $request['mahasiswa_nama']) : strtoupper(mb_substr((string) $request['mahasiswa_nama'], 0, 2))) ?></div>
                                        <div class="min-w-0">
                                            <h3 class="font-semibold text-gray-900"><?= e((string) $request['mahasiswa_nama']) ?></h3>
                                            <p class="mt-1 text-xs text-gray-500"><?= e((string) $request['nim']) ?><?= !empty($request['program_studi']) ? ' · ' . e((string) $request['program_studi']) : '' ?></p>
                                            <p class="mt-2 text-sm text-gray-600"><?= e((string) $request['nama_perusahaan']) ?> · <?= e((string) $request['judul']) ?></p>
                                            <p class="mt-1 text-xs text-gray-400">Diajukan <?= $submitted ? e(date('d M Y H:i', $submitted)) : '-' ?></p>
                                        </div>
                                    </div>
                                    <div class="p-4 mt-4 text-sm text-gray-700 whitespace-pre-line bg-gray-50 rounded-xl"><?= e((string) $request['alasan_pengajuan']) ?></div>
                                </div>

                                <form method="POST" action="<?= e(url('/dashboard/dosen/permintaan-pembimbing')) ?>" class="space-y-3">
                                    <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
                                    <input type="hidden" name="request_id" value="<?= e((string) $request['id']) ?>">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Catatan keputusan <span class="font-normal text-gray-400">(opsional)</span>
                                        <textarea name="catatan_dosen" rows="3" maxlength="5000" class="block w-full mt-2 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Tuliskan arahan atau alasan keputusan."></textarea>
                                    </label>
                                    <div class="flex flex-wrap gap-3">
                                        <button type="submit" name="decision" value="disetujui" class="inline-flex items-center justify-center px-5 py-3 text-sm font-semibold text-white bg-emerald-600 rounded-xl hover:bg-emerald-700">Setujui &amp; Tetapkan</button>
                                        <button type="submit" name="decision" value="ditolak" class="inline-flex items-center justify-center px-5 py-3 text-sm font-semibold text-red-700 border border-red-200 rounded-xl hover:bg-red-50">Tolak Permintaan</button>
                                    </div>
                                </form>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="px-5 py-12 text-center">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 text-slate-400 bg-slate-100 rounded-full">✓</div>
                    <h3 class="font-semibold text-gray-700">Tidak ada permintaan yang menunggu</h3>
                    <p class="mt-1 text-sm text-gray-400">Permintaan yang ditujukan kepada Anda akan muncul di sini.</p>
                </div>
            <?php endif; ?>
        </section>
    </div>
</main>
</body>
</html>
