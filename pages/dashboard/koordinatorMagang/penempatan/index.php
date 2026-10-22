<?php
$base = url('/');
$active = 'penempatan';
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
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Penempatan Magang</h1>
            <p class="mt-2 text-sm text-gray-500">Buat penempatan setelah mitra menerima pengajuan. Dosen pembimbing dapat diminta setelah penempatan tercatat.</p>
        </div>
        <?php if ($success): ?><div class="p-4 mb-5 text-sm text-emerald-800 border border-emerald-200 bg-emerald-50 rounded-xl" role="status">Penempatan magang berhasil dibuat.</div><?php endif; ?>
        <?php if ($error): ?><div class="p-4 mb-5 text-sm text-red-800 border border-red-200 bg-red-50 rounded-xl" role="alert"><?= e($error) ?></div><?php endif; ?>

        <section class="overflow-hidden bg-white border border-gray-100 shadow-sm rounded-2xl">
            <div class="px-5 py-4 border-b border-gray-100"><h2 class="font-semibold text-gray-900">Pengajuan Diterima — Belum Ditempatkan</h2><p class="mt-1 text-xs text-gray-500">Tanggal formasi diisi sebagai saran awal; koordinator dapat menetapkan tanggal pelaksanaan aktual.</p></div>
            <?php if ($items): ?>
                <div class="divide-y divide-gray-100">
                    <?php foreach ($items as $item): ?>
                        <article class="p-5 sm:p-6">
                            <div class="mb-4">
                                <h3 class="font-semibold text-gray-900"><?= e((string) $item['mahasiswa_nama']) ?> · <?= e((string) $item['nim']) ?></h3>
                                <p class="mt-1 text-sm text-gray-500"><?= e((string) $item['nama_perusahaan']) ?> · <?= e((string) $item['judul']) ?></p>
                                <p class="mt-1 text-xs text-gray-400">Periode formasi: <?= e((string) $item['formasi_mulai']) ?> – <?= e((string) $item['formasi_selesai']) ?></p>
                            </div>
                            <form method="POST" action="<?= e(url('/dashboard/koordinator-magang/penempatan')) ?>" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
                                <input type="hidden" name="pendaftaran_id" value="<?= e((string) $item['pendaftaran_id']) ?>">
                                <label class="text-sm font-medium text-gray-700">Tanggal mulai
                                    <input type="date" name="tanggal_mulai" value="<?= e((string) $item['formasi_mulai']) ?>" required class="block w-full mt-2 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                </label>
                                <label class="text-sm font-medium text-gray-700">Tanggal selesai
                                    <input type="date" name="tanggal_selesai" value="<?= e((string) $item['formasi_selesai']) ?>" required class="block w-full mt-2 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                </label>
                                <div class="sm:col-span-2"><button type="submit" class="inline-flex items-center justify-center px-5 py-3 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700">Buat Penempatan</button></div>
                            </form>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="px-5 py-12 text-center"><p class="text-sm font-medium text-gray-700">Tidak ada pengajuan diterima yang menunggu penempatan.</p><p class="mt-1 text-xs text-gray-500">Pengajuan baru akan muncul di sini setelah diterima mitra.</p></div>
            <?php endif; ?>
        </section>
    </div>
</main>
</body>
</html>
