<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . "/../../components/login/Head.php"; ?>

<body class="relative min-h-screen bg-gradient-to-br from-white via-blue-50/40 to-blue-100/60 font-[Poppins] text-slate-900 overflow-x-hidden">

    <!-- Dekorasi latar -->
    <div class="pointer-events-none absolute -top-24 left-1/3 w-72 h-72 bg-blue-100/70 rounded-full"></div>

    <div class="pointer-events-none absolute -bottom-32 -right-24 w-96 h-96 bg-blue-200/50 rounded-full"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8 min-h-screen flex flex-col">

        <?php include __DIR__ . "/../../components/login/Header.php"; ?>

        <main class="flex-1 grid lg:grid-cols-2 gap-8 lg:gap-10 items-center py-6 sm:py-10">

            <?php include __DIR__ . "/../../components/login/Konten.php"; ?>

            <?php include __DIR__ . "/../../components/login/Card.php"; ?>

        </main>

    </div>

    <?php include __DIR__ . "/../../components/login/Script.php"; ?>

</body>
</html>