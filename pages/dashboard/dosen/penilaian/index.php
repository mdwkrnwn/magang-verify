<?php
$base = url('/');
$active = 'monitoring';
?>
<!DOCTYPE html>
<html lang="id">
<?php include __DIR__ . '/../../../../components/dashboard/dosen/Head.php'; ?>
<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">
<?php include __DIR__ . '/../../../../components/dashboard/dosen/Sidebar.php'; ?>
<?php include __DIR__ . '/../../../../components/dashboard/dosen/Header.php'; ?>
<main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">
    <div class="px-4 py-6 mx-auto sm:px-6 sm:py-8 lg:px-8 max-w-6xl">
        <div class="mb-6"><h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">Monitoring &amp; Penilaian</h1><p class="mt-2 text-sm text-gray-500">Penilaian hanya untuk mahasiswa pada penempatan bimbingan Anda.</p></div>
        <?php include __DIR__ . '/../../../../components/dashboard/penilaian/MainContent.php'; ?>
    </div>
</main>
</body>
</html>
