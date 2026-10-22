<?php
$base = url('/');
$active = 'formasi';
$isEdit = $mode === 'edit';
?>
<!DOCTYPE html>
<html lang="id">
<?php include __DIR__ . '/../../../../components/dashboard/mitra/Head.php'; ?>
<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">
<?php include __DIR__ . '/../../../../components/dashboard/mitra/Sidebar.php'; ?>
<?php include __DIR__ . '/../../../../components/dashboard/mitra/Header.php'; ?>
<main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">
    <div class="px-4 py-6 mx-auto sm:px-6 sm:py-8 lg:px-8 max-w-4xl">
        <a href="<?= e(url('/dashboard/mitra/formasi')) ?>" class="text-sm text-blue-600 hover:text-blue-700">← Kembali ke Formasi</a>
        <div class="mt-3 mb-6"><h1 class="text-2xl font-bold text-gray-900"><?= $isEdit ? 'Edit Formasi Magang' : 'Tambah Formasi Magang' ?></h1><p class="mt-1 text-sm text-gray-500">Informasi formasi disimpan pada perusahaan Anda.</p></div>
        <?php if ($error): ?><div class="p-4 mb-5 text-sm text-red-800 border border-red-200 bg-red-50 rounded-xl" role="alert"><?= e($error) ?></div><?php endif; ?>

        <form method="POST" action="<?= e(url($isEdit ? '/dashboard/mitra/formasi/edit/' . (int) $values['id'] : '/dashboard/mitra/formasi/tambah')) ?>" class="p-5 space-y-5 bg-white border border-gray-100 shadow-sm sm:p-7 rounded-2xl">
            <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
            <label class="block text-sm font-medium text-gray-700">Judul formasi
                <input type="text" name="judul" maxlength="200" required value="<?= e((string) ($values['judul'] ?? '')) ?>" class="block w-full mt-2 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </label>
            <label class="block text-sm font-medium text-gray-700">Deskripsi pekerjaan
                <textarea name="deskripsi" rows="4" required class="block w-full mt-2 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"><?= e((string) ($values['deskripsi'] ?? '')) ?></textarea>
            </label>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <label class="block text-sm font-medium text-gray-700">Bidang
                    <input type="text" name="bidang" maxlength="150" value="<?= e((string) ($values['bidang'] ?? '')) ?>" class="block w-full mt-2 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </label>
                <label class="block text-sm font-medium text-gray-700">Kuota
                    <input type="number" name="jumlah_kuota" min="1" max="32767" required value="<?= e((string) ($values['jumlah_kuota'] ?? 1)) ?>" class="block w-full mt-2 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </label>
                <label class="block text-sm font-medium text-gray-700">Lokasi
                    <input type="text" name="lokasi_magang" maxlength="255" value="<?= e((string) ($values['lokasi_magang'] ?? '')) ?>" class="block w-full mt-2 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </label>
                <label class="block text-sm font-medium text-gray-700">Sistem kerja
                    <select name="sistem_kerja" required class="block w-full mt-2 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <?php foreach (['onsite' => 'On-site', 'hybrid' => 'Hybrid', 'remote' => 'Remote'] as $value => $label): ?>
                            <option value="<?= e($value) ?>" <?= ($values['sistem_kerja'] ?? 'onsite') === $value ? 'selected' : '' ?>><?= e($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label class="block text-sm font-medium text-gray-700">Tanggal mulai
                    <input type="date" name="tanggal_mulai" required value="<?= e((string) ($values['tanggal_mulai'] ?? '')) ?>" class="block w-full mt-2 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </label>
                <label class="block text-sm font-medium text-gray-700">Tanggal selesai
                    <input type="date" name="tanggal_selesai" required value="<?= e((string) ($values['tanggal_selesai'] ?? '')) ?>" class="block w-full mt-2 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </label>
            </div>
            <label class="block text-sm font-medium text-gray-700">Tahun akademik
                <input type="text" name="tahun_akademik" maxlength="20" required placeholder="2026/2027" value="<?= e((string) ($values['tahun_akademik'] ?? '')) ?>" class="block w-full mt-2 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </label>
            <label class="block text-sm font-medium text-gray-700">Persyaratan
                <textarea name="persyaratan" rows="4" class="block w-full mt-2 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"><?= e((string) ($values['persyaratan'] ?? '')) ?></textarea>
            </label>
            <div class="flex flex-wrap gap-3 pt-2">
                <button type="submit" class="px-5 py-3 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700"><?= $isEdit ? 'Simpan Perubahan' : 'Simpan Formasi' ?></button>
                <a href="<?= e(url('/dashboard/mitra/formasi')) ?>" class="px-5 py-3 text-sm font-medium text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50">Batal</a>
            </div>
        </form>
    </div>
</main>
</body>
</html>
