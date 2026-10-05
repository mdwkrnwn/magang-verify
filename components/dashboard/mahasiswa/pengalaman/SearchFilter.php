<?php
/**
 * @var string $q
 * @var string $jenis
 * @var string $sumber
 * @var string $tahun
 * @var array $jenisList
 * @var array $tahunList
 */
?>

<div class="mb-6">
    <form
        method="GET"
        action="<?= e(url('/dashboard/mahasiswa/pengalaman')) ?>"
        class="rounded-xl border border-slate-200 bg-white p-4 sm:p-5"
    >
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">

            <div class="sm:col-span-2 xl:col-span-1">
                <label for="pengalaman-search" class="mb-1.5 block text-xs font-semibold text-gray-600">
                    Cari Pengalaman
                </label>
                <input
                    type="text"
                    id="pengalaman-search"
                    name="q"
                    value="<?= e($q) ?>"
                    placeholder="Cari posisi, instansi, lokasi..."
                    autocomplete="off"
                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >
            </div>

            <div>
                <label for="pengalaman-jenis" class="mb-1.5 block text-xs font-semibold text-gray-600">
                    Jenis
                </label>
                <select
                    id="pengalaman-jenis"
                    name="jenis"
                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >
                    <option value="">Semua Jenis</option>
                    <?php foreach ($jenisList as $itemJenis): ?>
                        <option value="<?= e($itemJenis) ?>" <?= $jenis === $itemJenis ? 'selected' : '' ?>>
                            <?= e(ucfirst($itemJenis)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="pengalaman-sumber" class="mb-1.5 block text-xs font-semibold text-gray-600">
                    Sumber
                </label>
                <select
                    id="pengalaman-sumber"
                    name="sumber"
                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >
                    <option value="">Semua Sumber</option>
                    <option value="manual" <?= $sumber === 'manual' ? 'selected' : '' ?>>Ditambahkan sendiri</option>
                    <option value="otomatis" <?= $sumber === 'otomatis' ? 'selected' : '' ?>>Dari sistem / magang</option>
                </select>
            </div>

            <div>
                <label for="pengalaman-tahun" class="mb-1.5 block text-xs font-semibold text-gray-600">
                    Tahun
                </label>
                <select
                    id="pengalaman-tahun"
                    name="tahun"
                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >
                    <option value="">Semua Tahun</option>
                    <?php foreach ($tahunList as $itemTahun): ?>
                        <option value="<?= e($itemTahun) ?>" <?= $tahun === $itemTahun ? 'selected' : '' ?>>
                            <?= e($itemTahun) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

        </div>

        <?php if ($q !== '' || $jenis !== '' || $sumber !== '' || $tahun !== ''): ?>
            <div class="mt-4">
                <a
                    href="<?= e(url('/dashboard/mahasiswa/pengalaman')) ?>"
                    class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm font-medium text-gray-600 transition hover:bg-slate-50"
                >
                    Reset Filter
                </a>
            </div>
        <?php endif; ?>
    </form>
</div>
