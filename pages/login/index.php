<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . "/../../components/login/Head.php"; ?>

<body class="min-h-screen w-full overflow-x-hidden bg-gradient-to-br from-white via-blue-50/40 to-blue-100/60 font-[Poppins] text-slate-900">

    <!-- Dekorasi latar -->
    <div class="pointer-events-none fixed -left-24 -top-24 h-64 w-64 rounded-full bg-blue-100/70 sm:h-72 sm:w-72"></div>

    <div class="pointer-events-none fixed -bottom-24 -right-24 h-72 w-72 rounded-full bg-blue-200/50 sm:h-96 sm:w-96"></div>

    <!-- Container -->
    <div class="relative mx-auto flex min-h-screen w-full max-w-7xl flex-col px-4 py-5 sm:px-6 sm:py-8 lg:px-8">

        <?php include __DIR__ . "/../../components/login/Header.php"; ?>

        <!-- Main -->
        <main class="grid w-full min-w-0 flex-1 grid-cols-1 items-start gap-8 py-8 sm:gap-10 sm:py-10 lg:grid-cols-2 lg:items-center lg:gap-12">

            <?php include __DIR__ . "/../../components/login/Konten.php"; ?>

            <?php include __DIR__ . "/../../components/login/Card.php"; ?>

        </main>

    </div>

    <?php include __DIR__ . "/../../components/login/Script.php"; ?>

</body>
</html>