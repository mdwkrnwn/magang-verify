<?php
$base = url('/');
$active = 'logbook';
$plan = $nextWeek ?? null;
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
            <a href="<?= e(url('/dashboard/mahasiswa/logbook')) ?>" class="text-sm font-medium text-blue-600 hover:text-blue-700">← Kembali ke Logbook</a>
            <h1 class="mt-3 text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Buat Minggu Logbook</h1>
            <p class="mt-2 text-sm leading-6 text-gray-500">Sistem menentukan periode minggu secara otomatis berdasarkan periode penempatan magang. Tanggal masa depan tidak dapat dibuat.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="p-4 mb-5 text-sm text-red-700 bg-red-50 border border-red-200 rounded-xl"><?= e($error) ?></div>
        <?php endif; ?>

        <?php if ($plan): ?>
            <section class="p-6 bg-white border border-gray-100 shadow-sm rounded-2xl">
                <div class="flex items-start gap-4">
                    <div class="flex items-center justify-center w-12 h-12 text-blue-600 bg-blue-50 rounded-xl shrink-0">
                        <span class="text-sm font-bold">M<?= e((string) $plan['minggu_ke']) ?></span>
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-lg font-semibold text-gray-900">Minggu ke-<?= e((string) $plan['minggu_ke']) ?></h2>
                        <p class="mt-1 text-sm text-gray-500"><?= e(date('d M Y', strtotime($plan['tanggal_mulai']))) ?> – <?= e(date('d M Y', strtotime($plan['tanggal_selesai']))) ?></p>
                    </div>
                </div>

                <?php if (!empty($plan['terlambat'])): ?>
                    <div class="p-4 mt-5 text-sm text-amber-800 bg-amber-50 border border-amber-200 rounded-xl">
                        Minggu ini dibuat terlambat. Sistem tetap mengizinkannya karena periodenya sudah berjalan/terlewat.
                    </div>
                <?php endif; ?>

                <form method="POST" class="mt-6">
                    <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
                    <button type="submit" class="inline-flex items-center justify-center w-full px-5 py-3 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 sm:w-auto">Buat Minggu Ini</button>
                </form>
            </section>
        <?php else: ?>
            <section class="p-6 text-center bg-white border border-gray-100 shadow-sm rounded-2xl">
                <h2 class="text-lg font-semibold text-gray-900">Belum ada minggu yang dapat dibuat</h2>
                <p class="max-w-xl mx-auto mt-2 text-sm leading-6 text-gray-500">Minggu berikutnya baru tersedia setelah periode sebelumnya selesai. Sistem tidak mengizinkan pembuatan minggu yang tanggalnya masih di masa depan.</p>
            </section>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
