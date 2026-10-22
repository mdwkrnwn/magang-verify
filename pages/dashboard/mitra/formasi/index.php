<?php
$base = url('/');
$active = 'formasi';
?>
<!DOCTYPE html>
<html lang="id">
<?php include __DIR__ . '/../../../../components/dashboard/mitra/Head.php'; ?>
<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">
<?php include __DIR__ . '/../../../../components/dashboard/mitra/Sidebar.php'; ?>
<?php include __DIR__ . '/../../../../components/dashboard/mitra/Header.php'; ?>
<main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">
    <div class="px-4 py-6 mx-auto sm:px-6 sm:py-8 lg:px-8 max-w-6xl">
        <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-end sm:justify-between">
            <div><h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Formasi Magang</h1><p class="mt-2 text-sm text-gray-500">Kelola formasi perusahaan. Pengajuan yang sudah masuk akan dipertahankan sebagai histori.</p></div>
            <a href="<?= e(url('/dashboard/mitra/formasi/tambah')) ?>" class="inline-flex items-center justify-center px-5 py-3 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700">Tambah Formasi</a>
        </div>
        <?php if (($_GET['success'] ?? '') === '1'): ?><div class="p-4 mb-5 text-sm text-emerald-800 border border-emerald-200 bg-emerald-50 rounded-xl" role="status">Perubahan formasi berhasil disimpan.</div><?php endif; ?>

        <?php if ($items): ?>
            <div class="space-y-4">
                <?php foreach ($items as $item): ?>
                    <?php $statusClass = match ($item['status']) { 'dibuka' => 'text-emerald-700 bg-emerald-50', 'draft' => 'text-gray-700 bg-gray-100', 'ditutup', 'selesai' => 'text-amber-700 bg-amber-50', default => 'text-red-700 bg-red-50' }; ?>
                    <article class="p-5 bg-white border border-gray-100 shadow-sm sm:p-6 rounded-2xl">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2"><h2 class="text-lg font-semibold text-gray-900"><?= e((string) $item['judul']) ?></h2><span class="px-3 py-1 text-xs font-medium <?= e($statusClass) ?> rounded-full"><?= e(ucfirst((string) $item['status'])) ?></span></div>
                                <p class="mt-1 text-sm text-gray-500"><?= e((string) ($item['bidang'] ?? 'Bidang belum diisi')) ?> · <?= e((string) ($item['lokasi_magang'] ?? 'Lokasi belum diisi')) ?></p>
                                <p class="mt-3 text-sm text-gray-700 whitespace-pre-line"><?= e((string) $item['deskripsi']) ?></p>
                                <div class="flex flex-wrap gap-x-5 gap-y-2 mt-4 text-xs text-gray-500">
                                    <span>Kuota: <?= e((string) $item['jumlah_diterima']) ?>/<?= e((string) $item['jumlah_kuota']) ?> diterima</span>
                                    <span>Pelamar: <?= e((string) $item['jumlah_pelamar']) ?></span>
                                    <span>Periode: <?= e((string) $item['tanggal_mulai']) ?> – <?= e((string) $item['tanggal_selesai']) ?></span>
                                    <span>Tahun akademik: <?= e((string) $item['tahun_akademik']) ?></span>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2 shrink-0">
                                <?php if ((int) $item['jumlah_pelamar'] === 0): ?>
                                    <a href="<?= e(url('/dashboard/mitra/formasi/edit/' . (int) $item['id'])) ?>" class="px-4 py-2 text-sm font-medium text-blue-700 border border-blue-100 rounded-xl hover:bg-blue-50">Edit</a>
                                    <?php if ((int) $item['jumlah_diterima'] === 0): ?>
                                        <form method="POST" action="<?= e(url('/dashboard/mitra/formasi/hapus/' . (int) $item['id'])) ?>" onsubmit="return confirm('Hapus formasi ini? Hanya formasi tanpa histori pengajuan yang dapat dihapus.');">
                                            <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
                                            <button type="submit" class="px-4 py-2 text-sm font-medium text-red-700 border border-red-200 rounded-xl hover:bg-red-50">Hapus</button>
                                        </form>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if (in_array($item['status'], ['draft', 'dibuka', 'ditutup'], true)): ?>
                                    <form method="POST" action="<?= e(url('/dashboard/mitra/formasi/status/' . (int) $item['id'])) ?>">
                                        <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
                                        <input type="hidden" name="status" value="<?= $item['status'] === 'dibuka' ? 'ditutup' : 'dibuka' ?>">
                                        <button type="submit" class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-200 rounded-xl hover:bg-gray-50"><?= $item['status'] === 'dibuka' ? 'Tutup Formasi' : 'Buka Formasi' ?></button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="p-10 text-center bg-white border border-dashed border-gray-200 rounded-2xl"><p class="text-sm font-medium text-gray-700">Belum ada formasi magang.</p><p class="mt-1 text-xs text-gray-500">Tambahkan formasi untuk mulai menerima pelamar.</p></div>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
