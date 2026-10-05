<?php
/**
 * @var array $dashboard
 */
?>

<main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">
    <div class="px-4 py-6 mx-auto sm:px-6 sm:py-8 lg:px-8 max-w-[1600px]">

        <?php include __DIR__ . '/Welcome.php'; ?>

        <?php include __DIR__ . '/StatsCards.php'; ?>

        <div class="grid grid-cols-1 gap-4 mb-4 sm:gap-6 lg:grid-cols-3 sm:mb-6">
            <div class="lg:col-span-2">
                <?php include __DIR__ . '/ApplicationSummary.php'; ?>
            </div>

            <?php include __DIR__ . '/ProfileProgress.php'; ?>
        </div>

        <div class="grid grid-cols-1 gap-4 mb-4 sm:gap-6 xl:grid-cols-2 sm:mb-6">
            <?php include __DIR__ . '/InternshipProgress.php'; ?>
            <?php include __DIR__ . '/Activity.php'; ?>
        </div>

        <?php include __DIR__ . '/QuickActions.php'; ?>

        <?php include __DIR__ . '/../Footer.php'; ?>
    </div>
</main>
