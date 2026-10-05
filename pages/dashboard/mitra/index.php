<?php

$base = url('/');

$active = 'dashboard';

?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . "/../../../components/dashboard/mitra/Head.php"; ?>

<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">

    <?php include __DIR__ . "/../../../components/dashboard/mitra/Sidebar.php"; ?>

    <?php include __DIR__ . "/../../../components/dashboard/mitra/Header.php"; ?>

    <?php include __DIR__ . "/../../../components/dashboard/mitra/dashboard/MainContent.php"; ?>

</body>

</html>