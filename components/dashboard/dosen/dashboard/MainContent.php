<main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">

    <div class="px-4 py-6 mx-auto sm:px-6 sm:py-8 lg:px-8 max-w-[1600px]">

        <?php include __DIR__ . '/PageHeader.php'; ?>

        <?php include __DIR__ . '/Statistics.php'; ?>

        <?php include __DIR__ . '/ActionRequired.php'; ?>

        <div class="grid grid-cols-1 gap-4 mt-4 sm:gap-6 sm:mt-6 xl:grid-cols-2">

            <?php include __DIR__ . '/GuidedStudents.php'; ?>

            <?php include __DIR__ . '/PlacementMonitoring.php'; ?>

        </div>

        <?php include __DIR__ . '/QuickActions.php'; ?>

        <?php include __DIR__ . '/../Footer.php'; ?>

    </div>

</main>