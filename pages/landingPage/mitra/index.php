<?php

$active = "mitra";

require_once __DIR__ . "/../../../data/landingPage/Mitra.php";
require_once __DIR__ . "/../../../function/landingPage/mitra/Filter.php";
require_once __DIR__ . "/../../../function/landingPage/mitra/Process.php";

?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . "/../../../components/landingPage/Head.php"; ?>

<body class="bg-slate-50/60 font-[Poppins] pt-20 text-gray-900">

<?php include __DIR__ . "/../../../components/landingPage/Navbar.php"; ?>

<!-- Satu form: pencarian di Hero + checkbox di Filter terkirim bersamaan -->
<form method="get" action="<?= url('/mitra') ?>">

    <?php include __DIR__ . "/../../../components/landingPage/mitra/Hero.php"; ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 grid gap-6 lg:gap-10 lg:grid-cols-[220px_1fr] items-start">

        <?php include __DIR__ . "/../../../components/landingPage/mitra/Filter.php"; ?>

        <main class="min-w-0">

            <p class="text-sm text-slate-700 mb-4">
                Menampilkan <?= $dari ?>–<?= $sampai ?> dari <?= $total ?> mitra
            </p>

            <?php include __DIR__ . "/../../../components/landingPage/mitra/Card.php"; ?>

            <?php include __DIR__ . "/../../../components/landingPage/mitra/Pagination.php"; ?>

        </main>

    </div>

</form>

<?php include __DIR__ . "/../../../components/landingPage/Footer.php"; ?>

<?php include __DIR__ . '/../../../components/landingPage/Script.php'; ?>

</body>
</html>