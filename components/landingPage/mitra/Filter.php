<?php

// Butuh: $opts, $fBidang, $fLokasi, $fSkema
$groups = [
    'bidang' => ['Bidang',       $opts['bidang'], $fBidang],
    'lokasi' => ['Lokasi',       $opts['lokasi'], $fLokasi],
    'skema'  => ['Skema Magang', $opts['skema'],  $fSkema],
];

$aktif = count($fBidang) + count($fLokasi) + count($fSkema) > 0;

?>

<aside>

    <!-- Tombol buka/tutup (hanya mobile). Tanpa name, jadi tidak ikut terkirim. -->
    <input type="checkbox" id="filter-toggle" class="peer sr-only">

    <label
        for="filter-toggle"
        class="lg:hidden inline-flex items-center gap-2 px-4 h-10 rounded-lg border border-slate-200
        bg-white text-sm font-medium text-slate-700 cursor-pointer
        peer-focus-visible:ring-2 peer-focus-visible:ring-blue-500"
    >
        <?= icon('filter', 'w-4 h-4') ?>
        Filter
        <?php if ($aktif): ?>
            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
        <?php endif; ?>
    </label>

    <div class="hidden peer-checked:block lg:block mt-3 lg:mt-0 bg-white lg:bg-transparent
        rounded-2xl border border-slate-100 lg:border-0 p-4 lg:p-0 lg:sticky lg:top-24">

        <div class="flex items-center justify-between">
            <h2 class="text-sm font-semibold text-slate-900">Filter</h2>

            <?php if ($aktif || $fQ !== ''): ?>
                <a href="<?= url('/mitra') ?>" class="text-xs text-blue-600 hover:underline">
                    Reset
                </a>
            <?php endif; ?>
        </div>

        <?php foreach ($groups as $name => [$label, $list, $selected]): ?>

            <fieldset class="mt-5">

                <legend class="text-sm text-slate-500 mb-2">
                    <?= e($label) ?>
                </legend>

                <div class="space-y-2">
                    <?php foreach ($list as $o): ?>
                        <label class="flex items-center gap-2.5 text-sm text-slate-700 cursor-pointer">
                            <input
                                type="checkbox"
                                name="<?= $name ?>[]"
                                value="<?= e($o) ?>"
                                <?= in_array($o, $selected, true) ? 'checked' : '' ?>
                                onchange="this.form.submit()"
                                class="w-4 h-4 rounded border-slate-300 accent-blue-600"
                            >
                            <?= e($o) ?>
                        </label>
                    <?php endforeach; ?>
                </div>

            </fieldset>

        <?php endforeach; ?>

    </div>

</aside>