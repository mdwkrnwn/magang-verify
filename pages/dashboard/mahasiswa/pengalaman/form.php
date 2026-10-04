<?php $base=url('/'); $active='pengalaman'; $mode=$mode??'create'; $item=$item??null; ?>
<!DOCTYPE html><html lang="id">
<?php include __DIR__ . '/../../../../components/dashboard/mahasiswa/Head.php'; ?>
<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">
<?php include __DIR__ . '/../../../../components/dashboard/mahasiswa/Sidebar.php'; ?>
<?php include __DIR__ . '/../../../../components/dashboard/mahasiswa/Header.php'; ?>
<?php include __DIR__ . '/../../../../components/dashboard/mahasiswa/pengalaman/Form.php'; ?>
</body></html>