<?php if (!empty($m['daftar_proyek'])): ?>

<section
    class="<?= $card ?>"
    id="proyek"
>

    <?php judul('layers', 'Proyek'); ?>


    <?php foreach ($m['daftar_proyek'] as $p): ?>

        <div class="flex flex-col sm:flex-row gap-4">

            <div class="w-full sm:w-36 h-40 sm:h-24 rounded-lg bg-gradient-to-br from-blue-100 to-blue-50 border border-slate-100 overflow-hidden shrink-0">

                <?php if (!empty($p['gambar'])): ?>

                    <img
                        src="<?= e($p['gambar']) ?>"
                        alt="<?= e($p['nama']) ?>"
                        class="w-full h-full object-cover"
                    >

                <?php endif; ?>

            </div>


            <div class="flex-1 min-w-0">

                <h3 class="font-semibold text-slate-900 text-sm">
                    <?= e($p['nama']) ?>
                </h3>

                <p class="text-sm text-slate-600 mt-1 break-words">
                    <?= e($p['deskripsi']) ?>
                </p>


                <div class="flex flex-wrap gap-2 mt-3">

                    <?php foreach ($p['tech'] as $t): ?>

                        <span class="px-2.5 py-1 rounded-full bg-blue-50 text-xs text-slate-700 whitespace-nowrap">
                            <?= e($t) ?>
                        </span>

                    <?php endforeach; ?>

                </div>

            </div>


            <a
                href="<?= e($p['url']) ?>"
                class="self-start inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg border border-blue-500 text-blue-600 text-sm font-medium hover:bg-blue-50 whitespace-nowrap w-full sm:w-auto"
            >
                Lihat Proyek
                <?= icon('external', 'w-4 h-4') ?>
            </a>

        </div>


        <?php if ($p !== end($m['daftar_proyek'])): ?>
            <div class="mt-4 border-b border-slate-100"></div>
        <?php endif; ?>

    <?php endforeach; ?>

</section>

<?php endif; ?>