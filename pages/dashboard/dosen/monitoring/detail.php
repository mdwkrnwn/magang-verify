<?php
$base = url('/');
$active = 'monitoring';
?>
<!DOCTYPE html>
<html lang="id">
<?php include __DIR__ . '/../../../../components/dashboard/dosen/Head.php'; ?>
<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">
<?php include __DIR__ . '/../../../../components/dashboard/dosen/Sidebar.php'; ?>
<?php include __DIR__ . '/../../../../components/dashboard/dosen/Header.php'; ?>
<main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">
    <div class="px-4 py-6 mx-auto sm:px-6 sm:py-8 lg:px-8 max-w-5xl">
        <a href="<?= e(url('/dashboard/dosen/monitoring')) ?>" class="inline-flex mb-5 text-sm font-medium text-blue-700 hover:underline">← Kembali ke monitoring</a>
        <section class="overflow-hidden bg-white border border-gray-100 shadow-sm rounded-2xl">
            <header class="p-5 border-b border-gray-100 sm:p-6">
                <p class="text-sm font-medium text-blue-700">Monitoring Penempatan</p>
                <h1 class="mt-1 text-2xl font-bold text-gray-900"><?= e((string) ($placement['mahasiswa_nama'] ?? 'Mahasiswa')) ?></h1>
                <p class="mt-1 text-sm text-gray-500"><?= e((string) ($placement['nim'] ?? '-')) ?> · <?= e((string) ($placement['program_studi'] ?? '-')) ?></p>
            </header>
            <dl class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 sm:p-6">
                <div><dt class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Status Penempatan</dt><dd class="mt-1 text-sm font-medium text-gray-900"><?= e((string) ($placement['status_label'] ?? '-')) ?></dd></div>
                <div><dt class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Posisi</dt><dd class="mt-1 text-sm font-medium text-gray-900"><?= e((string) ($placement['judul'] ?? '-')) ?></dd></div>
                <div><dt class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Perusahaan</dt><dd class="mt-1 text-sm font-medium text-gray-900"><?= e((string) ($placement['nama_perusahaan'] ?? '-')) ?></dd></div>
                <div><dt class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Lokasi Perusahaan</dt><dd class="mt-1 text-sm font-medium text-gray-900"><?= e((string) ($placement['lokasi_perusahaan'] ?? '-')) ?></dd></div>
                <div><dt class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Tanggal Mulai</dt><dd class="mt-1 text-sm font-medium text-gray-900"><?= !empty($placement['tanggal_mulai']) ? e(date('d M Y', strtotime((string) $placement['tanggal_mulai']))) : '-' ?></dd></div>
                <div><dt class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Tanggal Selesai</dt><dd class="mt-1 text-sm font-medium text-gray-900"><?= !empty($placement['tanggal_selesai']) ? e(date('d M Y', strtotime((string) $placement['tanggal_selesai']))) : '-' ?></dd></div>
            </dl>
            <?php if (($placement['status_penempatan'] ?? '') === 'menunggu_penilaian'): ?>
                <div class="px-5 pb-5 sm:px-6 sm:pb-6">
                    <a href="<?= e(url('/dashboard/dosen/penilaian')) ?>" class="inline-flex px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700">Buka penilaian</a>
                </div>
            <?php endif; ?>
        </section>
    </div>
</main>
</body>
</html>
