<?php

$base = url('/');

$active = 'dashboard';

?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . "/../../../components/dashboard/koordinatorMagang/Head.php"; ?>

<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">

    <?php include __DIR__ . "/../../../components/dashboard/koordinatorMagang/Sidebar.php"; ?>

    <?php include __DIR__ . "/../../../components/dashboard/koordinatorMagang/Header.php"; ?>

    <?php include __DIR__ . "/../../../components/dashboard/koordinatorMagang/dashboard/MainContent.php"; ?>

</body>

</html>