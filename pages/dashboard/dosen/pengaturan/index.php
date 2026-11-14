<?php

$active = 'pengaturan';

?>

<!DOCTYPE html>
<html lang="id">

<?php
include __DIR__
    . '/../../../../components/dashboard/dosen/Head.php';
?>

<body class="min-h-screen overflow-x-hidden
             bg-slate-50 font-[Poppins] text-gray-900">

    <?php
    include __DIR__
        . '/../../../../components/dashboard/dosen/Sidebar.php';
    ?>

    <?php
    include __DIR__
        . '/../../../../components/dashboard/dosen/Header.php';
    ?>

    <main class="min-h-screen pt-20 lg:ml-64">

        <?php
        include __DIR__
            . '/../../../../components/dashboard/dosen/pengaturan/MainContent.php';
        ?>

    </main>

    <?php
    include __DIR__
        . '/../../../../components/dashboard/dosen/Script.php';
    ?>

</body>

</html>