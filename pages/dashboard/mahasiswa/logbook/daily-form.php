<?php
$base = url('/');
$active = 'logbook';
$item = $item ?? [];
$week = $week ?? [];
$mode = $mode ?? 'create';
?>
<!DOCTYPE html>
<html lang="id">
<?php include __DIR__ . '/../../../../components/dashboard/mahasiswa/Head.php'; ?>
<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">
<?php include __DIR__ . '/../../../../components/dashboard/mahasiswa/Sidebar.php'; ?>
<?php include __DIR__ . '/../../../../components/dashboard/mahasiswa/Header.php'; ?>
<main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">
    <div class="px-4 py-6 mx-auto sm:px-6 sm:py-8 lg:px-8 max-w-3xl">
        <div class="mb-6">
            <a href="<?= e(url('/dashboard/mahasiswa/logbook/detail/' . (int) $week['id'])) ?>" class="text-sm font-medium text-blue-600 hover:text-blue-700">← Kembali ke Minggu <?= e((string) $week['minggu_ke']) ?></a>
            <h1 class="mt-3 text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl"><?= $mode === 'edit' ? 'Edit Aktivitas Harian' : 'Tambah Aktivitas Harian' ?></h1>
            <p class="mt-2 text-sm leading-6 text-gray-500">Periode minggu: <?= e(date('d M Y', strtotime($week['tanggal_mulai']))) ?> – <?= e(date('d M Y', strtotime($week['tanggal_selesai']))) ?>. Tanggal masa depan tidak dapat diisi.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="p-4 mb-5 text-sm text-red-700 bg-red-50 border border-red-200 rounded-xl"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="p-5 space-y-5 bg-white border border-gray-100 shadow-sm rounded-2xl sm:p-7">
            <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">

            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                <input type="date" name="tanggal" min="<?= e($week['tanggal_mulai']) ?>" max="<?= e(min($week['tanggal_selesai'], date('Y-m-d'))) ?>" value="<?= e((string) ($item['tanggal'] ?? '')) ?>" class="w-full px-4 py-3 mt-2 text-sm border border-gray-200 rounded-xl focus:border-blue-500 focus:ring-blue-500" required>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jam masuk <span class="text-gray-400">(opsional)</span></label>
                    <input type="time" name="jam_masuk" value="<?= e((string) ($item['jam_masuk'] ?? '')) ?>" class="w-full px-4 py-3 mt-2 text-sm border border-gray-200 rounded-xl focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jam pulang <span class="text-gray-400">(opsional)</span></label>
                    <input type="time" name="jam_pulang" value="<?= e((string) ($item['jam_pulang'] ?? '')) ?>" class="w-full px-4 py-3 mt-2 text-sm border border-gray-200 rounded-xl focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Kehadiran</label>
                <select name="status_kehadiran" id="status-kehadiran" class="w-full px-4 py-3 mt-2 text-sm border border-gray-200 rounded-xl focus:border-blue-500 focus:ring-blue-500">
                    <option value="hadir" <?= (($item['status_kehadiran'] ?? 'hadir') === 'hadir') ? 'selected' : '' ?>>Hadir</option>
                    <option value="tidak_hadir" <?= (($item['status_kehadiran'] ?? '') === 'tidak_hadir') ? 'selected' : '' ?>>Tidak hadir</option>
                </select>
                <p class="mt-2 text-xs text-gray-400">Jika tidak hadir, tuliskan alasannya dan unggah bukti jika ada/diperlukan. Data ketidakhadiran akan divalidasi Tendik atau Koordinator.</p>
            </div>

            <div id="absence-box" class="<?= (($item['status_kehadiran'] ?? 'hadir') === 'tidak_hadir') ? '' : 'hidden' ?>">
                <label class="block text-sm font-medium text-gray-700">Alasan ketidakhadiran</label>
                <textarea name="alasan_ketidakhadiran" rows="3" class="w-full px-4 py-3 mt-2 text-sm border border-gray-200 rounded-xl focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: sakit, izin, atau alasan lainnya."><?= e((string) ($item['alasan_ketidakhadiran'] ?? '')) ?></textarea>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Bukti / surat <span class="text-gray-400">(opsional, PDF/JPG/PNG, maks. 5 MB)</span></label>
                    <input type="file" name="bukti" accept="application/pdf,image/jpeg,image/png" class="block w-full mt-2 text-sm text-gray-600 file:mr-4 file:px-4 file:py-2.5 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700">
                    <?php if (!empty($item['bukti_path'])): ?>
                        <p class="mt-2 text-xs text-gray-500">Bukti sebelumnya tersimpan. Unggah file baru jika ingin menggantinya.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Kegiatan</label>
                <textarea name="kegiatan" rows="7" maxlength="10000" class="w-full px-4 py-3 mt-2 text-sm border border-gray-200 rounded-xl focus:border-blue-500 focus:ring-blue-500" placeholder="Tuliskan aktivitas pada tanggal ini. Contoh: Implementasi endpoint API, testing fitur, koordinasi dengan pembimbing, atau tuliskan Sakit/Izin jika tidak hadir." required><?= e((string) ($item['kegiatan'] ?? '')) ?></textarea>
            </div>

            <div class="p-4 text-xs leading-5 text-amber-800 bg-amber-50 border border-amber-200 rounded-xl">
                <strong>Catatan tanda tangan:</strong> jika minggu ini sebelumnya sudah ditandatangani mahasiswa tetapi belum ditandatangani mitra, perubahan data akan membuat tanda tangan mahasiswa sebelumnya tidak berlaku dan mahasiswa harus menandatangani ulang.
            </div>

            <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
                <a href="<?= e(url('/dashboard/mahasiswa/logbook/detail/' . (int) $week['id'])) ?>" class="inline-flex items-center justify-center px-4 py-3 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50">Batal</a>
                <button type="submit" class="inline-flex items-center justify-center px-5 py-3 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700"><?= $mode === 'edit' ? 'Simpan Perubahan' : 'Simpan Aktivitas' ?></button>
            </div>
        </form>
    </div>
</main>
<script>
(() => {
    const select = document.getElementById('status-kehadiran');
    const box = document.getElementById('absence-box');
    if (!select || !box) return;
    const sync = () => box.classList.toggle('hidden', select.value !== 'tidak_hadir');
    select.addEventListener('change', sync);
    sync();
})();
</script>
</body>
</html>
