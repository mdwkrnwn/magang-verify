<?php
/**
 * @var string $q
 * @var string $fProdi
 * @var string $fAngkatan
 * @var array<int, string> $fSkill
 * @var array<int, string> $optProdi
 * @var array<int, string> $optAngkatan
 * @var array<int, string> $optSkill
 * @var string $urut
 */
?>
<div
    class="max-w-7xl mx-auto px-4 sm:px-6 -mt-14 sm:-mt-16 md:-mt-20
    relative z-10">
    <form
        id="mahasiswa-filter-form"
        method="get"
        class="bg-white rounded-2xl shadow-lg shadow-blue-900/5
        border border-slate-100 p-4 sm:p-5 md:p-7
        grid gap-4 sm:grid-cols-2
        lg:grid-cols-[1.6fr_repeat(3,1fr)_auto]
        lg:items-end">

        <!-- SEARCH -->
        <div class="sm:col-span-2 lg:col-span-1">
            <label
                for="mahasiswa-search"
                class="sr-only">
                Cari nama mahasiswa
            </label>

            <div class="relative">
                <span
                    class="absolute left-3 top-1/2
                    -translate-y-1/2 text-slate-500">
                    <?= icon('search') ?>
                </span>

                <input
                    id="mahasiswa-search"
                    name="q"
                    value="<?= e($q) ?>"
                    placeholder="Cari nama mahasiswa..."
                    class="w-full h-11 pl-10 pr-3
                    rounded-lg border border-slate-200
                    text-sm
                    focus:outline-none
                    focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <!-- PRODI -->
        <?php
        select(
            'prodi',
            'Program Studi',
            'Semua Prodi',
            $optProdi,
            $fProdi
        );
        ?>

        <!-- ANGKATAN -->
        <?php
        select(
            'angkatan',
            'Angkatan',
            'Semua Angkatan',
            $optAngkatan,
            $fAngkatan
        );
        ?>

        <!-- KEAHLIAN MULTI SELECT -->
        <div
            class="relative"
            id="keahlian-filter">
            <label
                class="block text-xs font-semibold
                text-slate-800 mb-1.5">
                Keahlian
            </label>

            <!-- TOGGLE -->
            <button
                type="button"
                id="keahlian-toggle"
                class="w-full h-11 px-3
                rounded-lg border border-slate-200
                bg-white text-sm text-slate-600
                flex items-center
                justify-between gap-2 text-left
                focus:outline-none
                focus:ring-2 focus:ring-blue-500">
                <span
                    id="keahlian-label"
                    class="truncate">
                    <?= empty($fSkill)
                        ? 'Semua Keahlian'
                        : count($fSkill) . ' keahlian dipilih'
                    ?>
                </span>

                <svg
                    id="keahlian-arrow"
                    class="w-4 h-4 shrink-0
                    transition-transform"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="m6 9 6 6 6-6" />
                </svg>
            </button>

            <!-- DROPDOWN -->
            <div
                id="keahlian-menu"
                class="hidden absolute z-30 mt-2
                w-full min-w-[240px]
                bg-white border border-slate-200
                rounded-xl shadow-lg
                overflow-hidden">

                <!-- SEARCH KEAHLIAN -->
                <div
                    class="p-2 border-b
                    border-slate-100">
                    <div class="relative">

                        <span
                            class="absolute left-3
                            top-1/2
                            -translate-y-1/2
                            text-slate-400">
                            <?= icon('search', 'w-4 h-4') ?>
                        </span>

                        <input
                            type="text"
                            id="keahlian-search"
                            placeholder="Cari keahlian..."
                            autocomplete="off"
                            class="w-full h-10
                            pl-9 pr-3
                            rounded-lg
                            border border-slate-200
                            text-sm text-slate-700
                            placeholder:text-slate-400
                            focus:outline-none
                            focus:ring-2
                            focus:ring-blue-500">

                    </div>
                </div>

                <!-- LIST KEAHLIAN -->
                <div
                    id="keahlian-list"
                    class="p-2 max-h-64
                    overflow-y-auto">

                    <?php foreach ($optSkill as $skill): ?>

                        <?php
                        $selected = in_array(
                            $skill,
                            $fSkill,
                            true
                        );
                        ?>

                        <label
                            class="keahlian-option
                            flex items-center gap-3
                            px-3 py-2.5
                            rounded-lg cursor-pointer
                            hover:bg-slate-50
                            transition"
                            data-skill="<?= e(
                                            strtolower($skill)
                                        ) ?>">
                            <input
                                type="checkbox"
                                name="keahlian[]"
                                value="<?= e($skill) ?>"
                                <?= $selected
                                    ? 'checked'
                                    : ''
                                ?>
                                class="keahlian-checkbox
                                w-4 h-4 rounded
                                border-slate-300
                                text-blue-600
                                focus:ring-blue-500">

                            <span
                                class="text-sm
                                text-slate-700">
                                <?= e($skill) ?>
                            </span>
                        </label>

                    <?php endforeach; ?>

                    <!-- HASIL PENCARIAN KOSONG -->
                    <div
                        id="keahlian-empty"
                        class="hidden px-3 py-6
                        text-center text-sm
                        text-slate-400">
                        Keahlian tidak ditemukan.
                    </div>

                </div>

            </div>
        </div>

        <?php
        $adaFilter =
            $q !== ''
            || $fProdi !== ''
            || $fAngkatan !== ''
            || !empty($fSkill);
        ?>

        <?php if ($adaFilter): ?>

            <div class="flex items-end">

                <a
                    href="<?= url('/mahasiswa') ?>"
                    class="h-11 px-4
            whitespace-nowrap
            inline-flex items-center
            justify-center gap-2
            rounded-lg
            border border-slate-200
            bg-white text-slate-600
            text-sm font-medium
            hover:bg-slate-50 transition">
                    <?= icon('reset', 'w-4 h-4') ?>
                    Reset
                </a>

            </div>

        <?php endif; ?>

        <!-- SORTING -->
        <input
            type="hidden"
            name="urut"
            value="<?= e($urut) ?>">

    </form>
</div>