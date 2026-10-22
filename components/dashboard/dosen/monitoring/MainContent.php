<main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">

    <div class="px-4 py-6 mx-auto sm:px-6 sm:py-8 lg:px-8 max-w-[1600px]">

        <?php include __DIR__ . '/PageHeader.php'; ?>

        <?php if (!empty($error)): ?>
            <div class="p-4 mb-6 text-sm text-red-800 border border-red-200 bg-red-50 rounded-xl" role="alert"><?= e($error) ?></div>
        <?php endif; ?>

        <?php include __DIR__ . '/Statistics.php'; ?>

        <?php include __DIR__ . '/SearchFilter.php'; ?>

        <?php include __DIR__ . '/MonitoringList.php'; ?>

        <?php include __DIR__ . '/../Footer.php'; ?>

    </div>

</main>