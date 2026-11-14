
<?php

$active = 'pengaturan';

?>

<!DOCTYPE html>
<html lang="id">

<?php
include __DIR__
    . '/../../../../components/dashboard/tendik/Head.php';
?>

<body class="min-h-screen overflow-x-hidden
             bg-slate-50 font-[Poppins] text-gray-900">

    <?php
    include __DIR__
        . '/../../../../components/dashboard/tendik/Sidebar.php';
    ?>

    <?php
    include __DIR__
        . '/../../../../components/dashboard/tendik/Header.php';
    ?>

    <main class="min-h-screen pt-20 lg:ml-64">

        <?php
        include __DIR__
            . '/../../../../components/dashboard/tendik/pengaturan/MainContent.php';
        ?>

    </main>

    <?php
    include __DIR__
        . '/../../../../components/dashboard/tendik/Script.php';
    ?>

</body>
</html>
