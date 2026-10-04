<?php
$base = url('/');
$active = 'logbook';
$week = $week ?? [];
$completeness = $week['completeness'] ?? [];
$signatures = $week['signatures'] ?? [];
$locked = in_array($week['status'] ?? '', ['menunggu_dosen', 'disetujui'], true);
?>
<!DOCTYPE html>
<html lang="id">
<?php include __DIR__ . '/../../../../components/dashboard/mahasiswa/Head.php'; ?>
<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">
<?php include __DIR__ . '/../../../../components/dashboard/mahasiswa/Sidebar.php'; ?>
<?php include __DIR__ . '/../../../../components/dashboard/mahasiswa/Header.php'; ?>
<main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">
    <div class="px-4 py-6 mx-auto sm:px-6 sm:py-8 lg:px-8 max-w-6xl">
        <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <a href="<?= e(url('/dashboard/mahasiswa/logbook')) ?>" class="text-sm font-medium text-blue-600 hover:text-blue-700">← Kembali ke Logbook</a>
                <h1 class="mt-3 text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Minggu <?= e((string) $week['minggu_ke']) ?></h1>
                <p class="mt-1 text-sm text-gray-500"><?= e(date('d M Y', strtotime($week['tanggal_mulai']))) ?> – <?= e(date('d M Y', strtotime($week['tanggal_selesai']))) ?> · <?= e($week['nama_perusahaan']) ?></p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="<?= e(url('/dashboard/mahasiswa/logbook/download/' . (int) $week['id'])) ?>" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50">Download Minggu</a>
                <?php if (!$locked): ?>
                    <a href="<?= e(url('/dashboard/mahasiswa/logbook/hari/tambah/' . (int) $week['id'])) ?>" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700">Tambah Hari</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 mb-5 sm:grid-cols-3">
            <div class="p-4 bg-white border border-gray-100 shadow-sm rounded-2xl"><p class="text-xs text-gray-400">Hari terisi</p><p class="mt-1 text-xl font-bold text-gray-900"><?= e((string) $completeness['filled']) ?> / <?= e((string) $completeness['expected']) ?></p></div>
            <div class="p-4 bg-white border border-gray-100 shadow-sm rounded-2xl"><p class="text-xs text-gray-400">Validasi menunggu</p><p class="mt-1 text-xl font-bold text-amber-600"><?= e((string) $completeness['pending_validation']) ?></p></div>
            <div class="p-4 bg-white border border-gray-100 shadow-sm rounded-2xl"><p class="text-xs text-gray-400">Status</p><p class="mt-1 text-sm font-semibold text-gray-900"><?= e($week['status_label']) ?></p></div>
        </div>

        <?php if ($locked): ?>
            <div class="p-4 mb-5 text-sm text-blue-800 bg-blue-50 border border-blue-200 rounded-xl">Mitra sudah menandatangani minggu ini. Data dikunci untuk menjaga keutuhan dokumen yang telah disahkan.</div>
        <?php elseif (!empty($week['had_previous_signature']) && ($week['status'] ?? '') === 'draft'): ?>
            <div class="p-4 mb-5 text-sm text-amber-800 bg-amber-50 border border-amber-200 rounded-xl">Isi minggu berubah setelah tanda tangan mahasiswa sebelumnya. Tanda tangan tersebut tidak lagi berlaku untuk versi terbaru. Silakan tanda tangan ulang.</div>
        <?php endif; ?>

        <?php if (!empty($completeness['missing'])): ?>
            <div class="p-4 mb-5 text-sm text-amber-800 bg-amber-50 border border-amber-200 rounded-xl">
                <strong>Belum lengkap.</strong> Tanggal yang belum memiliki logbook: <?= e(implode(', ', array_map(fn($d) => date('d M Y', strtotime($d)), $completeness['missing']))) ?>.
            </div>
        <?php endif; ?>

        <section class="overflow-hidden bg-white border border-gray-100 shadow-sm rounded-2xl">
            <div class="px-5 py-4 border-b border-gray-100"><h2 class="text-base font-semibold text-gray-900">Aktivitas Harian</h2></div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-sm">
                    <thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Hari, Tanggal</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Jam Masuk</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Jam Pulang</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Kegiatan</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Status</th><th class="px-4 py-3"></th></tr></thead>
                    <tbody class="divide-y divide-gray-100">
                    <?php foreach (($week['daily'] ?? []) as $day): ?>
                        <tr>
                            <td class="px-4 py-4 font-medium text-gray-800"><?= e(date('D, d M Y', strtotime($day['tanggal']))) ?></td>
                            <td class="px-4 py-4 text-gray-600"><?= e($day['jam_masuk'] ?: '-') ?></td>
                            <td class="px-4 py-4 text-gray-600"><?= e($day['jam_pulang'] ?: '-') ?></td>
                            <td class="px-4 py-4 text-gray-700"><div class="max-w-md whitespace-pre-line"><?= e($day['kegiatan']) ?></div><?php if ($day['status_kehadiran'] === 'tidak_hadir'): ?><div class="mt-1 text-xs text-amber-700">Alasan: <?= e($day['alasan_ketidakhadiran']) ?></div><?php endif; ?></td>
                            <td class="px-4 py-4"><span class="inline-flex px-2.5 py-1 text-[11px] font-medium rounded-full <?= $day['status_kehadiran'] === 'hadir' ? 'text-emerald-700 bg-emerald-50' : ($day['status_validasi'] === 'disetujui' ? 'text-emerald-700 bg-emerald-50' : ($day['status_validasi'] === 'ditolak' ? 'text-red-700 bg-red-50' : 'text-amber-700 bg-amber-50')) ?>"><?= e($day['status_kehadiran'] === 'hadir' ? 'Hadir' : ucfirst(str_replace('_', ' ', $day['status_validasi']))) ?></span><?php if (!empty($day['bukti_path'])): ?><a target="_blank" href="<?= e(url('/dashboard/logbook/file/evidence/' . (int) $day['id'])) ?>" class="block mt-1 text-xs text-blue-600 hover:underline">Lihat bukti</a><?php endif; ?></td>
                            <td class="px-4 py-4 text-right"><?php if (!$locked): ?><a href="<?= e(url('/dashboard/mahasiswa/logbook/hari/edit/' . (int) $day['id'])) ?>" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Edit</a><?php endif; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$week['daily']): ?><tr><td colspan="6" class="px-4 py-10 text-center text-sm text-gray-400">Belum ada aktivitas harian.</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="mt-5 p-5 bg-white border border-gray-100 shadow-sm rounded-2xl">
            <h2 class="text-base font-semibold text-gray-900">Tanda Tangan</h2>
            <div class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-3">
                <?php foreach ([['mahasiswa','Mahasiswa'],['mitra','Mitra'],['dosen','Dosen Pembimbing']] as [$stage,$label]): ?>
                    <div class="p-4 border border-gray-100 rounded-xl bg-gray-50">
                        <p class="text-xs font-semibold text-gray-500"><?= e($label) ?></p>
                        <?php if (!empty($signatures[$stage])): ?>
                            <img src="<?= e(url('/dashboard/logbook/file/signature/' . (int) $signatures[$stage]['id'])) ?>" alt="Tanda tangan <?= e($label) ?>" class="object-contain w-full h-20 mt-3 bg-white rounded-lg">
                            <p class="mt-2 text-xs font-medium text-gray-700"><?= e($signatures[$stage]['penanda_tangan_nama']) ?></p>
                            <p class="mt-1 text-[10px] text-gray-400"><?= e(date('d M Y H:i', strtotime($signatures[$stage]['ditandatangani_pada']))) ?></p>
                        <?php else: ?>
                            <div class="flex items-center justify-center h-20 mt-3 text-xs text-gray-400 bg-white border border-dashed border-gray-200 rounded-lg">Belum ditandatangani</div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <?php if (($week['status'] ?? '') === 'draft' && ($completeness['complete'] ?? false) && ($completeness['period_ended'] ?? false)): ?>
            <div class="mt-5">
                <?php
                $signatureAction = url('/dashboard/mahasiswa/logbook/tanda-tangan/' . (int) $week['id']);
                $signatureButtonLabel = 'Tanda Tangani Minggu';
                $signatureStageLabel = 'Tanda Tangan Mahasiswa';
                include __DIR__ . '/../../../../components/dashboard/logbook/SignaturePad.php';
                ?>
            </div>
        <?php elseif (($week['status'] ?? '') === 'draft'): ?>
            <div class="p-4 mt-5 text-sm text-gray-600 bg-gray-50 border border-gray-200 rounded-xl">Tanda tangan mahasiswa akan tersedia setelah periode minggu berakhir dan seluruh tanggal sudah terisi serta tidak ada validasi ketidakhadiran yang masih menunggu.</div>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
