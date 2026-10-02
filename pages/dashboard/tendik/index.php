<?php

$base = url('/');

$active = 'dashboard';

?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . "/../../../components/dashboard/tendik/Head.php"; ?>

<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">

    <?php include __DIR__ . "/../../../components/dashboard/tendik/Sidebar.php"; ?>

    <?php include __DIR__ . "/../../../components/dashboard/tendik/Header.php"; ?>

    <?php include __DIR__ . "/../../../components/dashboard/tendik/dashboard/MainContent.php"; ?>

</body>

</html>