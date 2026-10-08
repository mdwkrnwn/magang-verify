<?php
$base = url('/');
$active = 'pengajuan';
?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . "/../../../../components/dashboard/dosen/Head.php"; ?>

<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">

    <?php include __DIR__ . "/../../../../components/dashboard/dosen/Sidebar.php"; ?>

    <?php include __DIR__ . "/../../../../components/dashboard/dosen/Header.php"; ?>

    <?php include __DIR__ . "/../../../../components/dashboard/dosen/pengajuanmagang/MainContent.php"; ?>

</body>

</html>