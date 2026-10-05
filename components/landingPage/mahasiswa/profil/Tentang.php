<section
    class="<?= $card ?>"
    id="tentang"
>

    <?php judul('user', 'Tentang Saya'); ?>

    <p class="text-sm text-slate-700 leading-relaxed">
        <?= e($m['tentang'] ?? $m['bio'] ?? 'Belum ada deskripsi.') ?>
    </p>


    <dl class="grid gap-4 sm:grid-cols-3 mt-5 pt-5 border-t border-slate-100 text-sm">

        <?php foreach (
            [
                ['layers', 'Program Studi', $m['prodi']],
                ['user', 'Angkatan', $m['angkatan']],
                ['pin', 'Domisili', $m['domisili'] ?? '-']
            ]
            as [$ic, $l, $v]
        ): ?>

            <div class="flex items-center gap-3 min-w-0">

                <span class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 grid place-items-center shrink-0">
                    <?= icon($ic, 'w-4 h-4') ?>
                </span>

                <div class="min-w-0">

                    <dt class="text-xs text-slate-500">
                        <?= $l ?>
                    </dt>

                    <dd class="text-slate-800 break-words">
                        <?= e($v) ?>
                    </dd>

                </div>

            </div>

        <?php endforeach; ?>

    </dl>

</section>