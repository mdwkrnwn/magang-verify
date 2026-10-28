<?php
$base = url('/');
$active = 'verifikasi';
$portfolioItems = $portfolios['items'] ?? [];
$certificateItems = $certificates['items'] ?? [];
?>
<!DOCTYPE html>
<html lang="id">
<?php include __DIR__ . '/../../../../components/dashboard/tendik/Head.php'; ?>
<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">
<?php include __DIR__ . '/../../../../components/dashboard/tendik/Sidebar.php'; ?>
<?php include __DIR__ . '/../../../../components/dashboard/tendik/Header.php'; ?>
<main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">
<div class="px-4 py-6 mx-auto sm:px-6 sm:py-8 lg:px-8 max-w-7xl">
<h1 class="text-2xl font-bold tracking-tight sm:text-3xl">Verifikasi Dokumen Mahasiswa</h1>
<p class="mt-2 text-sm text-gray-500">Periksa portofolio dan sertifikat tanpa mengubah keputusan akademik atau proses magang.</p>
<?php if (($_GET['success'] ?? '') !== ''): ?><div class="p-4 mt-5 text-sm text-emerald-800 border border-emerald-200 bg-emerald-50 rounded-xl">Keputusan berhasil disimpan.</div><?php endif; ?>
<section class="mt-6 p-5 bg-white border border-gray-100 shadow-sm rounded-2xl">
<h2 class="text-lg font-semibold">Verifikasi Portofolio</h2>
<form method="GET" class="mt-3 flex gap-2"><select name="portfolio_status" class="rounded-lg border-gray-300 text-sm"><option value="belum_diverifikasi" <?= $portfolioStatus === 'belum_diverifikasi' ? 'selected' : '' ?>>Belum diverifikasi</option><option value="perlu_perbaikan" <?= $portfolioStatus === 'perlu_perbaikan' ? 'selected' : '' ?>>Perlu perbaikan</option></select><input type="hidden" name="certificate_status" value="<?= e($certificateStatus) ?>"><button class="px-3 py-2 text-sm border rounded-lg">Filter</button></form>
<?php if ($portfolioItems): ?><div class="mt-4 overflow-x-auto"><table class="w-full text-sm"><thead><tr class="text-left border-b"><th class="px-3 py-3">Mahasiswa</th><th class="px-3 py-3">Judul</th><th class="px-3 py-3">Dibuat</th><th class="px-3 py-3"></th></tr></thead><tbody><?php foreach ($portfolioItems as $item): ?><tr class="border-b last:border-0"><td class="px-3 py-3"><?= e((string) $item['mahasiswa_nama']) ?> · <?= e((string) $item['nim']) ?></td><td class="px-3 py-3 font-medium"><?= e((string) $item['judul']) ?></td><td class="px-3 py-3 text-gray-500"><?= e((string) $item['created_at']) ?></td><td class="px-3 py-3 text-right"><a class="text-blue-600 hover:underline" href="<?= e(url('/dashboard/tendik/verifikasi/portofolio/' . (int) $item['id'])) ?>">Periksa</a></td></tr><?php endforeach; ?></tbody></table></div><?php else: ?><p class="mt-4 text-sm text-gray-500">Tidak ada portofolio yang menunggu pemeriksaan.</p><?php endif; ?>
</section>
<section class="mt-6 p-5 bg-white border border-gray-100 shadow-sm rounded-2xl">
<h2 class="text-lg font-semibold">Verifikasi Sertifikat</h2><form method="GET" class="mt-3 flex gap-2"><select name="certificate_status" class="rounded-lg border-gray-300 text-sm"><option value="belum_terverifikasi" <?= $certificateStatus === 'belum_terverifikasi' ? 'selected' : '' ?>>Belum terverifikasi</option><option value="dalam_peninjauan" <?= $certificateStatus === 'dalam_peninjauan' ? 'selected' : '' ?>>Dalam peninjauan</option></select><input type="hidden" name="portfolio_status" value="<?= e($portfolioStatus) ?>"><button class="px-3 py-2 text-sm border rounded-lg">Filter</button></form>
<?php if ($certificateItems): ?><div class="mt-4 overflow-x-auto"><table class="w-full text-sm"><thead><tr class="text-left border-b"><th class="px-3 py-3">Mahasiswa</th><th class="px-3 py-3">Sertifikat</th><th class="px-3 py-3">Penerbit</th><th class="px-3 py-3"></th></tr></thead><tbody><?php foreach ($certificateItems as $item): ?><tr class="border-b last:border-0"><td class="px-3 py-3"><?= e((string) $item['mahasiswa_nama']) ?> · <?= e((string) ($item['nim'] ?? '-')) ?></td><td class="px-3 py-3 font-medium"><?= e((string) $item['nama']) ?></td><td class="px-3 py-3"><?= e((string) $item['penerbit']) ?></td><td class="px-3 py-3 text-right"><a class="text-blue-600 hover:underline" href="<?= e(url('/dashboard/tendik/verifikasi/sertifikat/' . (int) $item['id'])) ?>">Periksa</a></td></tr><?php endforeach; ?></tbody></table></div><?php else: ?><p class="mt-4 text-sm text-gray-500">Tidak ada sertifikat yang menunggu pemeriksaan.</p><?php endif; ?>
</section>
<?php include __DIR__ . '/../../../../components/dashboard/tendik/Footer.php'; ?>
</div></main></body></html>
