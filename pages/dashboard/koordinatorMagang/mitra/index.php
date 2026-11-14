<?php

$active = 'mitra';
$successMessages = [
    'created' => 'Data mitra berhasil ditambahkan.',
    'updated' => 'Data mitra berhasil diperbarui.',
    'deactivated' => 'Mitra berhasil dinonaktifkan. Data dan riwayatnya tetap tersimpan.',
];
$successMessage = $successMessages[$success] ?? null;
?>
<!DOCTYPE html>
<html lang="id">
<?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Head.php'; ?>

<body class="overflow-x-hidden bg-slate-50 font-[Poppins] text-slate-900 antialiased">
    <?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Sidebar.php'; ?>
    <?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Header.php'; ?>
    <main class="min-h-screen bg-slate-50 pt-20 lg:ml-64">
        <div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
            <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="mb-2 text-xs font-semibold uppercase tracking-[0.16em] text-blue-600">Manajemen Mitra</p>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Data Mitra</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">Kelola perusahaan dan instansi yang menjadi mitra penyelenggaraan program magang.</p>
                </div>
                <a href="<?= e(url('/dashboard/koordinator-magang/mitra/tambah')) ?>" class="inline-flex min-h-[44px] items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm shadow-blue-200 transition hover:bg-blue-700">
                    <span aria-hidden="true">+</span> Tambah Mitra
                </a>
            </div>

            <?php if ($successMessage): ?>
                <div role="status" class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm text-emerald-800"><?= e($successMessage) ?></div>
            <?php elseif ($success === '0'): ?>
                <div role="alert" class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm text-red-800">Aksi belum berhasil dilakukan. Silakan coba kembali.</div>
            <?php endif; ?>

            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-col gap-4 border-b border-slate-100 px-5 py-5 sm:px-6 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="font-semibold text-slate-900">Daftar Perusahaan / Instansi</h2>
                            <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">Aktif</span>
                        </div>
                        <p class="mt-1 text-sm text-slate-500"><?= count($mitraList) ?> mitra aktif</p>
                    </div>
                    <input id="searchMitra" type="search" placeholder="Cari nama, kode, kota..." class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 lg:max-w-xs">
                </div>

                <?php if (!empty($mitraList)): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[950px] text-left text-sm">
                            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-6 py-4 font-semibold">Mitra</th>
                                    <th class="px-6 py-4 font-semibold">Kategori</th>
                                    <th class="px-6 py-4 font-semibold">Lokasi</th>
                                    <th class="px-6 py-4 font-semibold">Kontak</th>
                                    <th class="px-6 py-4 text-center font-semibold">Status</th>
                                    <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php foreach ($mitraList as $mitra): ?>
                                    <?php $searchText = strtolower((string) $mitra['nama_perusahaan'] . ' ' . (string) $mitra['kode_perusahaan'] . ' ' . (string) ($mitra['kota'] ?? '') . ' ' . (string) ($mitra['kategori'] ?? '')); ?>
                                    <tr data-mitra data-search="<?= e($searchText) ?>" class="transition hover:bg-slate-50/70">
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-slate-800"><?= e((string) $mitra['nama_perusahaan']) ?></div>
                                            <div class="mt-1 text-xs text-slate-400"><?= e((string) $mitra['kode_perusahaan']) ?></div>
                                        </td>
                                        <td class="px-6 py-4 text-slate-600"><?= e((string) $mitra['kategori']) ?></td>
                                        <td class="px-6 py-4 text-slate-600"><?= e(implode(', ', array_filter([(string) ($mitra['kota'] ?? ''), (string) ($mitra['provinsi'] ?? '')]))) ?: '—' ?></td>
                                        <td class="px-6 py-4 text-slate-600">
                                            <div><?= e((string) ($mitra['email'] ?? '—')) ?></div>
                                            <div class="mt-1 text-xs text-slate-400"><?= e((string) ($mitra['no_telepon'] ?? '')) ?></div>
                                        </td>
                                        <td class="px-6 py-4 text-center"><span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">Aktif</span></td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="<?= e(url('/dashboard/koordinator-magang/mitra/edit/' . (int) $mitra['id'])) ?>" class="inline-flex items-center gap-1.5 rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 transition hover:bg-blue-100">Edit</a>
                                                <form method="POST" action="<?= e(url('/dashboard/koordinator-magang/mitra/nonaktifkan')) ?>" onsubmit="return confirm('Nonaktifkan mitra ini? Data dan riwayatnya tidak dihapus, tetapi mitra tidak lagi muncul pada daftar aktif.');">
                                                    <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
                                                    <input type="hidden" name="mitra_id" value="<?= (int) $mitra['id'] ?>">
                                                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-xs font-semibold text-amber-700 transition hover:bg-amber-100">Nonaktifkan</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div id="emptySearch" class="hidden px-6 py-12 text-center text-sm text-slate-500">Tidak ada mitra aktif yang cocok dengan pencarian.</div>
                <?php else: ?>
                    <div class="px-5 py-16 text-center">
                        <h3 class="font-semibold text-slate-800">Belum Ada Mitra Aktif</h3>
                        <p class="mx-auto mt-1 max-w-md text-sm leading-6 text-slate-500">Tambahkan mitra baru untuk mulai mengelola perusahaan dan instansi magang.</p>
                        <a href="<?= e(url('/dashboard/koordinator-magang/mitra/tambah')) ?>" class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700">Tambah mitra pertama →</a>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </main>
    <script>
        (() => {
            const input = document.getElementById('searchMitra');
            const rows = Array.from(document.querySelectorAll('[data-mitra]'));
            const empty = document.getElementById('emptySearch');
            if (!input) return;
            input.addEventListener('input', () => {
                const keyword = input.value.trim().toLocaleLowerCase('id');
                let visible = 0;
                rows.forEach((row) => {
                    const show = (row.dataset.search || '').includes(keyword);
                    row.classList.toggle('hidden', !show);
                    if (show) visible++;
                });
                empty?.classList.toggle('hidden', visible > 0);
            });
        })();
    </script>
</body>

</html>