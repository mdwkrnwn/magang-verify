<?php

$base = url('/');
$active = 'pengalaman';

/** @var array<int, array<string, mixed>> $tampil */
/** @var array<string, mixed> $pagination */
/** @var array<int, string> $jenisList */
/** @var array<int, string> $tahunList */
/** @var string $q */
/** @var string $jenis */
/** @var string $sumber */
/** @var string $tahun */

?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . '/../../../../components/dashboard/mahasiswa/Head.php'; ?>

<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">

    <?php include __DIR__ . '/../../../../components/dashboard/mahasiswa/Sidebar.php'; ?>

    <?php include __DIR__ . '/../../../../components/dashboard/mahasiswa/Header.php'; ?>

    <?php include __DIR__ . '/../../../../components/dashboard/mahasiswa/pengalaman/MainContent.php'; ?>

</body>

</html>