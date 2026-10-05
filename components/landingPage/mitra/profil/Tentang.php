<section id="tentang" class="<?= $card ?>">

    <?php $judul('briefcase', 'Tentang Mitra'); ?>


    <p class="text-sm text-slate-700 leading-relaxed">
        <?= e($m['tentang']) ?>
    </p>


    <dl class="grid sm:grid-cols-2 gap-4 mt-5 pt-5 border-t border-slate-100 text-sm">

        <?php foreach (
            [
                ['Kategori perusahaan', $m['kategori'] ?? '-'],
                ['Skala perusahaan', $m['skala'] ?? '-'],
                ['Wilayah', $m['wilayah']],
                ['Tahun akademik', $m['tahun_akademik']],
            ] as [$label, $value]
        ): ?>

            <div>

                <dt class="text-xs text-slate-500">
                    <?= e($label) ?>
                </dt>

                <dd class="text-slate-800 mt-0.5">
                    <?= e($value) ?>
                </dd>

            </div>

        <?php endforeach; ?>

    </dl>


    <div class="mt-5 pt-5 border-t border-slate-100">

        <p class="text-xs text-slate-500 mb-2">
            Skema magang yang diterima
        </p>

        <div class="flex flex-wrap gap-2">

            <?php foreach ($m['skema'] as $s): ?>

                <span class="px-3 py-1 rounded-full bg-slate-100 text-xs text-slate-700">
                    <?= e($s) ?>
                </span>

            <?php endforeach; ?>

        </div>

    </div>

</section>