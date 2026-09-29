<div class="max-w-7xl mx-auto px-4 sm:px-6 -mt-14 sm:-mt-16 md:-mt-20 relative z-10">
    <form
        method="get"
        class="bg-white rounded-2xl shadow-lg shadow-blue-900/5 border border-slate-100 p-4 sm:p-5 md:p-7
        grid gap-4 sm:grid-cols-2 lg:grid-cols-[1.6fr_repeat(4,1fr)_auto] lg:items-end"
    >

        <!-- Search -->
        <div class="sm:col-span-2 lg:col-span-1">

            <label for="q" class="sr-only">
                Cari nama mahasiswa
            </label>

            <div class="relative">

                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">
                    <?= icon('search') ?>
                </span>

                <input
                    id="q"
                    name="q"
                    value="<?= e($q) ?>"
                    placeholder="Cari nama mahasiswa..."
                    class="w-full h-11 pl-10 pr-3 rounded-lg border border-slate-200 text-sm
                    focus:outline-none focus:ring-2 focus:ring-blue-500"
                >

            </div>

        </div>

        <!-- Filter -->
        <?php
        select(
            'jurusan',
            'Jurusan',
            'Semua Jurusan',
            $optJurusan,
            $fJurusan
        );

        select(
            'prodi',
            'Program Studi',
            'Semua Prodi',
            $optProdi,
            $fProdi
        );

        select(
            'angkatan',
            'Angkatan',
            'Semua Angkatan',
            $optAngkatan,
            $fAngkatan
        );

        select(
            'keahlian',
            'Keahlian',
            'Semua Keahlian',
            $optSkill,
            $fSkill
        );
        ?>

        <!-- Button -->
        <button
            type="submit"
            class="sm:col-span-2 lg:col-span-1 h-11 px-6
            inline-flex items-center justify-center gap-2
            rounded-lg bg-blue-600 text-white text-sm font-medium
            hover:bg-blue-700 transition"
        >
            <?= icon('filter', 'w-4 h-4') ?>
            Terapkan Filter
        </button>

        <input
            type="hidden"
            name="urut"
            value="<?= e($urut) ?>"
        >
    </form>
</div>