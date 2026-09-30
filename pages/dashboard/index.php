<?php

$base = url('/');

$active = $active ?? '';


?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . "/../../components/dashboard/Head.php"; ?>

<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">

    <?php include __DIR__ . "/../../components/dashboard/Sidebar.php"; ?>

    <?php include __DIR__ . "/../../components/dashboard/Header.php"; ?>

    <?php include __DIR__ . "/../../components/dashboard/MainContent.php"; ?>

</body>

</html>