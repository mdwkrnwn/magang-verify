<?php

$base = url('/');

$active = 'logbook';

?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . "/../../../../components/dashboard/mahasiswa/Head.php"; ?>

<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">

    <?php include __DIR__ . "/../../../../components/dashboard/mahasiswa/Sidebar.php"; ?>

    <?php include __DIR__ . "/../../../../components/dashboard/mahasiswa/Header.php"; ?>

    <?php include __DIR__ . "/../../../../components/dashboard/mahasiswa/logbook/MainContent.php"; ?>

</body>

</html>