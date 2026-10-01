<?php

$base = url('/');

$active = 'pengajuan';

?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . "/../../../../components/dashboard/mahasiswa/Head.php"; ?>

<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">

    <?php include __DIR__ . "/../../../../components/dashboard/mahasiswa/Sidebar.php"; ?>

    <?php include __DIR__ . "/../../../../components/dashboard/mahasiswa/Header.php"; ?>


    <main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">

        <div class="px-4 py-6 mx-auto sm:px-6 sm:py-8 lg:px-8 max-w-[1600px]">

            <?php include __DIR__ . "/../../../../components/dashboard/mahasiswa/pengajuan/Detail.php"; ?>

            <?php include __DIR__ . "/../../../../components/dashboard/mahasiswa/Footer.php"; ?>

        </div>

    </main>

</body>

</html>