<?php
/**
 * @var array $tampil
 * @var string $q
 * @var string $jenis
 * @var string $sumber
 * @var string $tahun
 */
?>

<div class="space-y-4">

    <?php if (empty($tampil)): ?>
        <div class="rounded-xl border border-slate-200 bg-white px-6 py-12 text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7h-4l-1.5-2h-5L8 7H4a2 2 0 00-2 2v9a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 13h8" />
                </svg>
            </div>

            <p class="mt-4 text-sm font-semibold text-gray-800">
                <?= ($q !== '' || $jenis !== '' || $sumber !== '' || $tahun !== '') ? 'Pengalaman tidak ditemukan.' : 'Belum ada pengalaman.' ?>
            </p>

            <p class="mx-auto mt-1 max-w-md text-sm leading-6 text-gray-500">
                <?= ($q !== '' || $jenis !== '' || $sumber !== '' || $tahun !== '')
                    ? 'Coba ubah kata pencarian atau filter yang digunakan.'
                    : 'Tambahkan pengalaman organisasi, pekerjaan, freelance, atau proyek yang pernah kamu jalani.' ?>
            </p>

            <?php if ($q === '' && $jenis === '' && $sumber === '' && $tahun === ''): ?>
                <a
                    href="<?= e(url('/dashboard/mahasiswa/pengalaman/tambah')) ?>"
                    class="mt-5 inline-flex items-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700"
                >
                    Tambah Pengalaman
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <?php foreach ($tampil as $item): ?>
            <?php $pengalamanItem = $item; ?>
            <?php include __DIR__ . '/PengalamanCard.php'; ?>
        <?php endforeach; ?>
    <?php endif; ?>

</div>
