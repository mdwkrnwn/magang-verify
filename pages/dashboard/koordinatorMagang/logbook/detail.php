<?php $base = url('/');
$active = 'logbook'; ?>
<!DOCTYPE html>
<html lang="id">
    
<?php 

include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Head.php'; 
 
?>

<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden"><?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Sidebar.php'; ?>
<?php 

include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Header.php'; 

?>

<main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">
        <div class="px-4 py-6 mx-auto sm:px-6 sm:py-8 lg:px-8 max-w-6xl"><a href="<?= e(url('/dashboard/koordinator-magang/logbook')) ?>" class="text-sm text-blue-600">← Kembali</a>
            <h1 class="mt-3 text-2xl font-bold text-gray-900">Validasi Minggu <?= e($week['minggu_ke']) ?> · <?= e($week['mahasiswa_nama']) ?></h1>
            <p class="mt-1 text-sm text-gray-500"><?= e($week['nim']) ?> · <?= e($week['nama_perusahaan']) ?></p>
            <div class="mt-5 overflow-hidden bg-white border border-gray-100 shadow-sm rounded-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[820px] text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left">Tanggal</th>
                                <th class="px-4 py-3 text-left">Kehadiran</th>
                                <th class="px-4 py-3 text-left">Kegiatan / Alasan</th>
                                <th class="px-4 py-3 text-left">Bukti</th>
                                <th class="px-4 py-3 text-left">Validasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100"><?php foreach ($week['daily'] as $day): ?><tr>
                                    <td class="px-4 py-4"><?= e(date('D, d M Y', strtotime($day['tanggal']))) ?></td>
                                    <td class="px-4 py-4"><?= e($day['status_kehadiran'] === 'hadir' ? 'Hadir' : 'Tidak hadir') ?></td>
                                    <td class="px-4 py-4 whitespace-pre-line"><?= e($day['kegiatan']) ?><?php if ($day['status_kehadiran'] === 'tidak_hadir'): ?><div class="mt-1 text-xs text-amber-700">Alasan: <?= e($day['alasan_ketidakhadiran']) ?></div><?php endif; ?></td>
                                    <td class="px-4 py-4"><?php if (!empty($day['bukti_path'])): ?><a target="_blank" class="text-xs font-semibold text-blue-600 hover:underline" href="<?= e(url('/dashboard/logbook/file/evidence/' . (int)$day['id'])) ?>">Lihat bukti</a><?php else: ?><span class="text-xs text-gray-400">Tidak ada bukti</span><?php endif; ?></td>
                                    <td class="px-4 py-4"><?php if ($day['status_kehadiran'] === 'tidak_hadir' && $day['status_validasi'] === 'menunggu'): ?><form method="POST" action="<?= e(url('/dashboard/koordinator-magang/logbook/validasi/' . (int)$day['id'])) ?>" class="space-y-2"><input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>"><input type="hidden" name="week_id" value="<?= e((string)$week['id']) ?>"><textarea name="catatan" rows="2" class="w-full text-xs border border-gray-200 rounded-lg" placeholder="Catatan validasi (opsional)"></textarea>
                                                <div class="flex gap-2"><button name="decision" value="disetujui" class="px-3 py-1.5 text-xs font-semibold text-white bg-emerald-600 rounded-lg">Valid</button><button name="decision" value="ditolak" class="px-3 py-1.5 text-xs font-semibold text-white bg-red-600 rounded-lg">Tolak</button></div>
                                            </form><?php else: ?><span class="text-xs text-gray-500"><?= e($day['status_validasi']) ?></span><?php endif; ?></td>
                                </tr><?php endforeach; ?></tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>

</html>