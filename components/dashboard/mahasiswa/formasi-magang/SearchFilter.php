<div class="mb-5 p-4 bg-white border border-slate-200 rounded-xl">

    <form
        id="formasi-filter-form"
        method="GET"
        action="<?= url('/dashboard/mahasiswa/formasi-magang') ?>">

        <div class="flex flex-col gap-3 lg:flex-row lg:items-center">

            <!-- Search -->
            <div class="relative flex-1">

                <svg
                    class="absolute w-4 h-4 text-slate-400 left-3 top-1/2 -translate-y-1/2"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 6.05 6.05a7.5 7.5 0 0 0 10.6 10.6Z" />
                </svg>

                <input
                    type="text"
                    name="q"
                    value="<?= e($q) ?>"
                    placeholder="Cari nama perusahaan, posisi, atau kata kunci..."
                    class="w-full h-10 pl-9 pr-4 text-sm text-slate-700
                           bg-white border border-slate-200 rounded-lg
                           outline-none focus:ring-2 focus:ring-blue-100
                           focus:border-blue-400">

            </div>


            <!-- Lokasi -->
            <select
                name="lokasi"
                class="w-full lg:w-36 h-10 px-3 text-sm text-slate-600
                       bg-white border border-slate-200 rounded-lg
                       outline-none focus:ring-2 focus:ring-blue-100
                       focus:border-blue-400">

                <option value="">
                    Semua Lokasi
                </option>

                <?php foreach ($optLokasi as $lokasi): ?>

                    <option
                        value="<?= e($lokasi) ?>"
                        <?= $fLokasi === $lokasi ? 'selected' : '' ?>>
                        <?= e($lokasi) ?>
                    </option>

                <?php endforeach; ?>

            </select>


            <!-- Durasi -->
            <select
                name="durasi"
                class="w-full lg:w-36 h-10 px-3 text-sm text-slate-600
                       bg-white border border-slate-200 rounded-lg
                       outline-none focus:ring-2 focus:ring-blue-100
                       focus:border-blue-400">

                <option value="">
                    Semua Durasi
                </option>

                <?php foreach ($optDurasi as $durasi): ?>

                    <option
                        value="<?= e($durasi) ?>"
                        <?= $fDurasi === $durasi ? 'selected' : '' ?>>
                        <?= e($durasi) ?>
                    </option>

                <?php endforeach; ?>

            </select>


            <!-- Sistem Kerja -->
            <select
                name="sistem_kerja"
                class="w-full lg:w-36 h-10 px-3 text-sm text-slate-600 bg-white border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400">
                <option value="">Semua Sistem Kerja</option>
                <?php foreach (['onsite' => 'On-site', 'hybrid' => 'Hybrid', 'remote' => 'Remote'] as $value => $label): ?>
                    <option value="<?= e($value) ?>" <?= ($fSistemKerja ?? '') === $value ? 'selected' : '' ?>><?= e($label) ?></option>
                <?php endforeach; ?>
            </select>

            <!-- Tahun Akademik -->
            <select
                name="tahun_akademik"
                class="w-full lg:w-36 h-10 px-3 text-sm text-slate-600 bg-white border border-slate-200 rounded-lg outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400">
                <option value="">Semua Tahun</option>
                <?php foreach ($optTahun as $tahun): ?>
                    <option value="<?= e($tahun) ?>" <?= ($fTahun ?? '') === $tahun ? 'selected' : '' ?>><?= e($tahun) ?></option>
                <?php endforeach; ?>
            </select>

            <!-- Reset -->
            <?php if (
                $q !== ''
                || $fLokasi !== ''
                || $fDurasi !== ''
                || ($fSistemKerja ?? '') !== ''
                || ($fTahun ?? '') !== ''
            ): ?>

                <a
                    href="<?= url('/dashboard/mahasiswa/formasi-magang') ?>"
                    class="h-10 inline-flex items-center px-2
                           text-xs font-medium text-blue-500
                           hover:text-blue-700 transition">
                    Reset
                </a>

            <?php endif; ?>

        </div>

    </form>

</div>