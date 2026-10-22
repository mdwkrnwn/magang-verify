<main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">

    <div class="px-4 py-6 mx-auto sm:px-6 sm:py-8 lg:px-8 max-w-[1600px]">

        <?php include __DIR__ . '/PageHeader.php'; ?>

        <?php if (!empty($success)): ?><div class="p-4 mb-5 text-sm text-emerald-800 border border-emerald-200 bg-emerald-50 rounded-xl" role="status">Keputusan pengajuan berhasil disimpan.</div><?php endif; ?>
        <?php if (!empty($error)): ?><div class="p-4 mb-5 text-sm text-red-800 border border-red-200 bg-red-50 rounded-xl" role="alert"><?= e($error) ?></div><?php endif; ?>

        <?php include __DIR__ . '/Statistics.php'; ?>

        <?php include __DIR__ . '/SearchFilter.php'; ?>

        <?php include __DIR__ . '/SubmissionList.php'; ?>

        <?php include __DIR__ . '/../Footer.php'; ?>

    </div>

</main>