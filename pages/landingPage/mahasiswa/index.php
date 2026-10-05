<?php

$active = 'mahasiswa';

require_once __DIR__ . '/../../../function/landingPage/mahasiswa/ViewHelpers.php';
require_once __DIR__ . '/../../../function/landingPage/mahasiswa/Process.php';
require_once __DIR__ . '/../../../function/landingPage/mahasiswa/Filter.php';

?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . '/../../../components/landingPage/Head.php'; ?>

<body class="bg-white font-[Poppins] pt-20 text-gray-900">

<?php include __DIR__ . '/../../../components/landingPage/Navbar.php'; ?>

<?php include __DIR__ . '/../../../components/landingPage/mahasiswa/Hero.php'; ?>

<?php include __DIR__ . '/../../../components/landingPage/mahasiswa/Filter.php'; ?>

<main class="max-w-7xl mx-auto px-4 sm:px-6 mt-8 mb-8">

    <?php include __DIR__ . '/../../../components/landingPage/mahasiswa/Toolbar.php'; ?>

    <?php include __DIR__ . '/../../../components/landingPage/mahasiswa/Card.php'; ?>

    <?php include __DIR__ . '/../../../components/landingPage/mahasiswa/Pagination.php'; ?>

</main>

<?php include __DIR__ . '/../../../components/landingPage/Footer.php'; ?>

<?php include __DIR__ . '/../../../components/landingPage/Script.php'; ?>

</body>
</html>
