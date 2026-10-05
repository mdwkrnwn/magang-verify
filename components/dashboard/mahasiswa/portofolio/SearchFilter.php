<div class="mb-6">

    <form
        id="portfolio-filter-form"
        method="GET"
        action="<?= e(url('/dashboard/mahasiswa/portofolio')) ?>"
        class="bg-white border border-slate-200 rounded-xl p-4 sm:p-5">

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3">

            <!-- Search -->

            <div class="sm:col-span-2 xl:col-span-1">

                <label
                    for="portfolio-search"
                    class="block mb-1.5 text-xs font-semibold text-gray-600">
                    Cari Portofolio
                </label>

                <input
                    type="text"
                    id="portfolio-search"
                    name="q"
                    value="<?= e($q) ?>"
                    placeholder="Cari judul, deskripsi, teknologi..."
                    autocomplete="off"
                    class="w-full px-3 py-2.5
                           text-sm text-gray-900
                           bg-white
                           border border-slate-200
                           rounded-lg
                           outline-none
                           focus:border-blue-500
                           focus:ring-2 focus:ring-blue-100">

            </div>

            <!-- Tahun -->

            <div>

                <label
                    for="portfolio-tahun"
                    class="block mb-1.5 text-xs font-semibold text-gray-600">
                    Tahun
                </label>

                <select
                    id="portfolio-tahun"
                    name="tahun"
                    class="w-full px-3 py-2.5
                           text-sm text-gray-900
                           bg-white
                           border border-slate-200
                           rounded-lg
                           outline-none
                           focus:border-blue-500
                           focus:ring-2 focus:ring-blue-100">

                    <option value="">
                        Semua Tahun
                    </option>

                    <?php foreach ($tahunList as $itemTahun): ?>

                        <option
                            value="<?= e($itemTahun) ?>"
                            <?= $tahun === $itemTahun ? 'selected' : '' ?>>
                            <?= e($itemTahun) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <!-- Status -->

            <div>

                <label
                    for="portfolio-status"
                    class="block mb-1.5 text-xs font-semibold text-gray-600">
                    Status
                </label>

                <select
                    id="portfolio-status"
                    name="status"
                    class="w-full px-3 py-2.5
                           text-sm text-gray-900
                           bg-white
                           border border-slate-200
                           rounded-lg
                           outline-none
                           focus:border-blue-500
                           focus:ring-2 focus:ring-blue-100">

                    <option value="">
                        Semua Status
                    </option>

                    <option
                        value="terverifikasi"
                        <?= $status === 'terverifikasi' ? 'selected' : '' ?>>
                        Terverifikasi
                    </option>

                    <option
                        value="belum_terverifikasi"
                        <?= $status === 'belum_terverifikasi' ? 'selected' : '' ?>>
                        Belum Terverifikasi
                    </option>

                </select>

            </div>

            <!-- Teknologi -->

            <div>

                <label
                    for="portfolio-teknologi"
                    class="block mb-1.5 text-xs font-semibold text-gray-600">
                    Teknologi
                </label>

                <select
                    id="portfolio-teknologi"
                    name="teknologi"
                    class="w-full px-3 py-2.5
                           text-sm text-gray-900
                           bg-white
                           border border-slate-200
                           rounded-lg
                           outline-none
                           focus:border-blue-500
                           focus:ring-2 focus:ring-blue-100">

                    <option value="">
                        Semua Teknologi
                    </option>

                    <?php foreach ($teknologiList as $itemTeknologi): ?>

                        <option
                            value="<?= e($itemTeknologi) ?>"
                            <?= $teknologi === $itemTeknologi ? 'selected' : '' ?>>
                            <?= e($itemTeknologi) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

        </div>

        <?php if (
            $q !== '' ||
            $tahun !== '' ||
            $status !== '' ||
            $teknologi !== ''
        ): ?>

            <div class="mt-4">

                <a
                    href="<?= e(url('/dashboard/mahasiswa/portofolio')) ?>"
                    class="inline-flex items-center justify-center
                           px-3.5 py-2
                           text-sm font-medium
                           text-gray-600
                           bg-white
                           border border-slate-200
                           rounded-lg
                           hover:bg-slate-50
                           transition">
                    Reset Filter
                </a>

            </div>

        <?php endif; ?>

    </form>

</div>