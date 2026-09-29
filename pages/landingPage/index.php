<?php

$base = url('/');
$active = "beranda";

?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . "/../../components/landingPage/Head.php"; ?>

<body class="bg-white font-[Poppins] pt-20 overflow-x-hidden text-gray-900">

<?php include __DIR__ . "/../../components/landingPage/Navbar.php"; ?>

<?php include __DIR__ . "/../../components/landingPage/beranda/Hero.php"; ?>

<?php include __DIR__ . "/../../components/landingPage/beranda/Features.php"; ?>

<?php include __DIR__ . "/../../components/landingPage/Footer.php"; ?>

<?php include __DIR__ . '/../../components/landingPage/Script.php'; ?>

</body>
</html>