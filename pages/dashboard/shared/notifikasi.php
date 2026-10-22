<?php

$projectRoot = dirname(__DIR__, 3);
$layout = is_string($layout ?? null) ? $layout : '';
$supportedLayouts = ['mahasiswa', 'dosen', 'mitra', 'koordinatorMagang', 'tendik'];
if (!in_array($layout, $supportedLayouts, true)) {
    http_response_code(500);
    exit('Layout notifikasi tidak tersedia.');
}
$componentRoot = $projectRoot . '/components/dashboard/' . $layout;
foreach (['Head.php', 'Sidebar.php', 'Header.php'] as $layoutComponent) {
    if (!is_file($componentRoot . '/' . $layoutComponent)) {
        error_log('Komponen layout halaman notifikasi tidak ditemukan: ' . $layoutComponent);
        http_response_code(500);
        exit('Halaman notifikasi belum dapat dimuat.');
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<?php include $componentRoot . '/Head.php'; ?>
<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">
    <?php include $componentRoot . '/Sidebar.php'; ?>
    <?php include $componentRoot . '/Header.php'; ?>
    <?php include $projectRoot . '/components/dashboard/shared/Notifications.php'; ?>
</body>
</html>
