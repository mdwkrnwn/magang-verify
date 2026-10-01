<form
    id="pengajuan-filter-form"
    method="GET"
    action="<?= e(url('/dashboard/mahasiswa/pengajuan')) ?>"
    class="mb-6 p-4 bg-white border border-slate-200 rounded-xl">

    <div class="flex flex-col gap-4 lg:flex-row lg:items-end">

        <!-- Search -->

        <div class="w-full lg:flex-1">

            <label
                for="pengajuan-search"
                class="block mb-2 text-xs font-medium text-slate-600">
                Cari Pengajuan
            </label>

            <input
                type="text"
                id="pengajuan-search"
                name="search"
                value="<?= e($search) ?>"
                placeholder="Cari perusahaan atau posisi..."
                autocomplete="off"
                class="w-full h-10 px-4
                       text-xs text-slate-700
                       bg-white
                       border border-slate-200
                       rounded-lg
                       outline-none
                       focus:border-blue-300
                       focus:ring-2
                       focus:ring-blue-50
                       transition">

        </div>


        <!-- Status -->

        <div class="w-full lg:w-56">

            <label
                for="pengajuan-status"
                class="block mb-2 text-xs font-medium text-slate-600">
                Status
            </label>

            <select
                id="pengajuan-status"
                name="status"
                class="w-full h-10 px-3
                       text-xs text-slate-700
                       bg-white
                       border border-slate-200
                       rounded-lg
                       outline-none
                       focus:border-blue-300
                       focus:ring-2
                       focus:ring-blue-50
                       transition">

                <option value="">
                    Semua Status
                </option>

                <option
                    value="Menunggu Verifikasi"
                    <?= $status === 'Menunggu Verifikasi'
                        ? 'selected'
                        : '' ?>>
                    Menunggu Verifikasi
                </option>

                <option
                    value="Dalam Proses"
                    <?= $status === 'Dalam Proses'
                        ? 'selected'
                        : '' ?>>
                    Dalam Proses
                </option>

                <option
                    value="Diterima"
                    <?= $status === 'Diterima'
                        ? 'selected'
                        : '' ?>>
                    Diterima
                </option>

                <option
                    value="Ditolak"
                    <?= $status === 'Ditolak'
                        ? 'selected'
                        : '' ?>>
                    Ditolak
                </option>

            </select>

        </div>


        <!-- Sorting -->

        <div class="w-full lg:w-48">

            <label
                for="pengajuan-sort"
                class="block mb-2 text-xs font-medium text-slate-600">
                Urutan
            </label>

            <select
                id="pengajuan-sort"
                name="sort"
                class="w-full h-10 px-3
                       text-xs text-slate-700
                       bg-white
                       border border-slate-200
                       rounded-lg
                       outline-none
                       focus:border-blue-300
                       focus:ring-2
                       focus:ring-blue-50
                       transition">

                <option
                    value="terbaru"
                    <?= $sort === 'terbaru'
                        ? 'selected'
                        : '' ?>>
                    Terbaru
                </option>

                <option
                    value="terlama"
                    <?= $sort === 'terlama'
                        ? 'selected'
                        : '' ?>>
                    Terlama
                </option>

            </select>

        </div>


        <!-- Action -->

        <div class="flex items-center gap-2">

            <button
                type="submit"
                class="inline-flex items-center justify-center
                       h-10 px-5
                       text-xs font-medium
                       text-white
                       bg-blue-600
                       border border-blue-600
                       rounded-lg
                       hover:bg-blue-700
                       transition">
                Terapkan
            </button>

            <a
                href="<?= e(url('/dashboard/mahasiswa/pengajuan')) ?>"
                class="inline-flex items-center justify-center
                       h-10 px-5
                       text-xs font-medium
                       text-slate-600
                       border border-slate-200
                       rounded-lg
                       hover:bg-slate-50
                       transition">
                Reset
            </a>

        </div>

    </div>

</form>