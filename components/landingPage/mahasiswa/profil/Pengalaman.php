<?php if (!empty($m['pengalaman'])): ?>

<section
    class="<?= $card ?>"
    id="pengalaman"
>

    <?php judul('briefcase', 'Pengalaman'); ?>

    <?php foreach ($m['pengalaman'] as $p): ?>

        <div class="flex gap-3 sm:gap-4">

            <span class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl border border-slate-100 bg-slate-50 text-blue-600 grid place-items-center shrink-0">
                <?= icon('briefcase', 'w-5 h-5 sm:w-6 sm:h-6') ?>
            </span>


            <div class="min-w-0 flex-1">

                <div class="flex flex-col sm:flex-row sm:justify-between gap-1">

                    <h3 class="font-semibold text-slate-900 text-sm">
                        <?= e($p['posisi']) ?>
                    </h3>

                    <span class="text-xs text-slate-500 shrink-0">
                        <?= e($p['periode']) ?>
                    </span>

                </div>


                <p class="text-xs text-slate-500">
                    <?= e($p['instansi']) ?>
                </p>


                <ul class="mt-2 list-disc pl-4 space-y-1 text-sm text-slate-700">

                    <?php foreach ($p['tugas'] as $t): ?>

                        <li>
                            <?= e($t) ?>
                        </li>

                    <?php endforeach; ?>

                </ul>

            </div>

        </div>

    <?php endforeach; ?>

</section>

<?php endif; ?>