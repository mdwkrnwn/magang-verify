<section id="alur" class="<?= $card ?>">

    <?php $judul('bolt', 'Alur Pengajuan Magang'); ?>


    <ol class="space-y-4">

        <?php foreach ($alur as $i => [$title, $description]): ?>

            <li class="flex gap-4">

                <span class="w-7 h-7 rounded-full bg-blue-600 text-white text-xs font-semibold grid place-items-center shrink-0">
                    <?= $i + 1 ?>
                </span>


                <div class="min-w-0">

                    <h3 class="text-sm font-semibold text-slate-900">
                        <?= e($title) ?>
                    </h3>

                    <p class="text-sm text-slate-600 mt-0.5 leading-relaxed">
                        <?= e($description) ?>
                    </p>

                </div>

            </li>

        <?php endforeach; ?>

    </ol>

</section>