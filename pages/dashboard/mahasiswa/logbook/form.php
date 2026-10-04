<?php
$base = url('/');
$active = 'logbook';
?>
<!DOCTYPE html>
<html lang="id">
<?php include __DIR__ . '/../../../../components/dashboard/mahasiswa/Head.php'; ?>
<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">
<?php include __DIR__ . '/../../../../components/dashboard/mahasiswa/Sidebar.php'; ?>
<?php include __DIR__ . '/../../../../components/dashboard/mahasiswa/Header.php'; ?>
<main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">
    <div class="px-4 py-6 mx-auto sm:px-6 sm:py-8 lg:px-8 max-w-4xl">
        <div class="mb-6">
            <a href="<?= e(url('/dashboard/mahasiswa/logbook')) ?>" class="text-sm font-medium text-blue-600 hover:text-blue-700">← Kembali ke Logbook</a>
            <h1 class="mt-3 text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl"><?= $mode === 'edit' ? 'Edit Logbook' : 'Tambah Logbook' ?></h1>
            <p class="mt-2 text-sm text-gray-500">Catat aktivitas mingguan sesuai proses magang Anda.</p>
        </div>
        <?php if (!empty($error)): ?>
            <div class="p-4 mb-5 text-sm text-red-700 bg-red-50 border border-red-100 rounded-xl"><?= e($error) ?></div>
        <?php endif; ?>
        <form method="POST" class="p-5 space-y-5 bg-white border border-gray-100 shadow-sm rounded-2xl sm:p-7">
            <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Minggu ke</label>
                    <input type="number" min="1" name="minggu_ke" value="<?= e((string) ($item['minggu_ke'] ?? '')) ?>" class="w-full px-4 py-3 mt-2 text-sm border border-gray-200 rounded-xl focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal mulai</label>
                    <input type="date" name="tanggal_mulai" value="<?= e((string) ($item['tanggal_mulai'] ?? '')) ?>" class="w-full px-4 py-3 mt-2 text-sm border border-gray-200 rounded-xl focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal selesai</label>
                    <input type="date" name="tanggal_selesai" value="<?= e((string) ($item['tanggal_selesai'] ?? '')) ?>" class="w-full px-4 py-3 mt-2 text-sm border border-gray-200 rounded-xl focus:border-blue-500 focus:ring-blue-500" required>
                </div>
            </div>
            <?php
            $fields = [
                ['aktivitas', 'Aktivitas', 'Jelaskan aktivitas atau pekerjaan yang dilakukan minggu ini.', true],
                ['hasil_pekerjaan', 'Hasil pekerjaan', 'Tuliskan hasil atau capaian pekerjaan.', false],
                ['kendala', 'Kendala', 'Tuliskan kendala jika ada.', false],
                ['rencana_selanjutnya', 'Rencana selanjutnya', 'Tuliskan rencana pekerjaan berikutnya.', false],
            ];
            foreach ($fields as [$name, $label, $placeholder, $required]):
            ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700"><?= e($label) ?></label>
                    <textarea name="<?= e($name) ?>" rows="4" placeholder="<?= e($placeholder) ?>" class="w-full px-4 py-3 mt-2 text-sm border border-gray-200 rounded-xl focus:border-blue-500 focus:ring-blue-500" <?= $required ? 'required' : '' ?>><?= e((string) ($item[$name] ?? '')) ?></textarea>
                </div>
            <?php endforeach; ?>
            <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
                <a href="<?= e(url('/dashboard/mahasiswa/logbook')) ?>" class="inline-flex items-center justify-center px-4 py-3 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50">Batal</a>
                <button type="submit" class="inline-flex items-center justify-center px-5 py-3 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700"><?= $mode === 'edit' ? 'Simpan Perubahan' : 'Simpan Logbook' ?></button>
            </div>
        </form>
        <?php include __DIR__ . '/../../../../components/dashboard/mahasiswa/Footer.php'; ?>
    </div>
</main>
</body>
</html>
