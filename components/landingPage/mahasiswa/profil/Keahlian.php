<?php if (!empty($m['keahlian'])): ?>

<section
    class="<?= $card ?>"
    id="portofolio"
>

    <?php judul('bolt', 'Keahlian'); ?>

    <div class="flex flex-wrap gap-2">

        <?php foreach ($m['keahlian'] as $s): ?>

            <span class="px-3 py-1.5 rounded-full bg-slate-100 text-xs text-slate-700 whitespace-nowrap">
                <?= e($s) ?>
            </span>

        <?php endforeach; ?>

    </div>

</section>

<?php endif; ?>