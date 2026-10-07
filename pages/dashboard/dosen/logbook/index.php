<?php
/*
$base = url('/');
$active = 'logbook';
?>
<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . '/../../../../components/dashboard/dosen/Head.php'; ?>

<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">

<?php include __DIR__ . '/../../../../components/dashboard/dosen/Sidebar.php'; ?>
<?php include __DIR__ . '/../../../../components/dashboard/dosen/Header.php'; ?>

<main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">
    <div class="px-4 py-6 mx-auto sm:px-6 sm:py-8 lg:px-8 max-w-6xl">

        <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">
            Logbook Mahasiswa Bimbingan
        </h1>

        <p class="mt-2 text-sm text-gray-500">
            Minggu yang sudah ditandatangani mahasiswa dan mitra akan muncul untuk tanda tangan dosen pembimbing.
        </p>

        <section class="mt-6 overflow-hidden bg-white border border-gray-100 shadow-sm rounded-2xl">

            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-900">
                    Menunggu Tanda Tangan
                </h2>
            </div>

            <?php if ($items): ?>

                <div class="divide-y divide-gray-100">

                    <?php foreach ($items as $item): ?>

                        <a
                            href="<?= e(url('/dashboard/dosen/logbook/detail/' . (int)$item['id'])) ?>"
                            class="block p-5 hover:bg-gray-50"
                        >

                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                                <div>

                                    <p class="text-sm font-semibold text-gray-900">
                                        <?= e($item['mahasiswa_nama']) ?> · <?= e($item['nim']) ?>
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        Minggu <?= e($item['minggu_ke']) ?> ·
                                        <?= e(date('d M Y', strtotime($item['tanggal_mulai']))) ?> –
                                        <?= e(date('d M Y', strtotime($item['tanggal_selesai']))) ?>
                                    </p>

                                    <p class="mt-1 text-xs text-gray-400">
                                        <?= e($item['nama_perusahaan']) ?>
                                    </p>

                                </div>

                                <span class="inline-flex px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 rounded-full">
                                    Menunggu Anda
                                </span>

                            </div>

                        </a>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <div class="p-10 text-center text-sm text-gray-400">
                    Belum ada logbook yang menunggu tanda tangan Anda.
                </div>

            <?php endif; ?>

        </section>

    </div>
</main>

</body>
</html>
*/

$base = url('/');
$active = 'logbook';
?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . "/../../../../components/dashboard/dosen/Head.php"; ?>

<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">

    <?php include __DIR__ . "/../../../../components/dashboard/dosen/Sidebar.php"; ?>

    <?php include __DIR__ . "/../../../../components/dashboard/dosen/Header.php"; ?>

    <?php include __DIR__ . "/../../../../components/dashboard/dosen/logbook/MainContent.php"; ?>

</body>

</html>




