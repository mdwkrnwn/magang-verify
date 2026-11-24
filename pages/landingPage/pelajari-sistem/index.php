<?php

$base = url('/');
$active = "pelajari-sistem";

?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . "/../../../components/landingPage/Head.php"; ?>

<body class="bg-white font-[Poppins] pt-20 overflow-x-hidden text-gray-900">

    <?php include __DIR__ . "/../../../components/landingPage/Navbar.php"; ?>

    <?php include __DIR__ . "/../../../components/landingPage/pelajari-sistem/Hero.php"; ?>

    <?php include __DIR__ . "/../../../components/landingPage/pelajari-sistem/SystemOverview.php"; ?>

    <?php include __DIR__ . "/../../../components/landingPage/pelajari-sistem/Workflow.php"; ?>

    <?php include __DIR__ . "/../../../components/landingPage/pelajari-sistem/Roles.php"; ?>

    <?php include __DIR__ . "/../../../components/landingPage/pelajari-sistem/Features.php"; ?>

    <?php include __DIR__ . "/../../../components/landingPage/pelajari-sistem/FAQ.php"; ?>

    <?php include __DIR__ . "/../../../components/landingPage/pelajari-sistem/CTA.php"; ?>

    <?php include __DIR__ . "/../../../components/landingPage/Footer.php"; ?>

    <?php include __DIR__ . "/../../../components/landingPage/Script.php"; ?>

</body>
</html>