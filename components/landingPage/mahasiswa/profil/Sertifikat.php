<?php if (!empty($m['daftar_sertifikat'])): ?>

<section
    class="<?= $card ?>"
    id="sertifikat"
>

    <?php judul('award', 'Sertifikat & Prestasi'); ?>


    <?php foreach ($m['daftar_sertifikat'] as $c): ?>

        <div class="flex items-start gap-3 sm:gap-4">

            <span class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl border border-slate-100 bg-slate-50 text-blue-600 grid place-items-center shrink-0">
                <?= icon('award', 'w-5 h-5 sm:w-6 sm:h-6') ?>
            </span>


            <div class="flex-1 min-w-0">

                <h3 class="font-semibold text-slate-900 text-sm break-words">
                    <?= e($c['nama']) ?>
                </h3>

                <p class="text-xs text-slate-500 break-words">
                    <?= e($c['penerbit']) ?>
                </p>

            </div>


            <div class="flex flex-col sm:flex-row items-end sm:items-center gap-2 text-xs shrink-0">

                <span class="text-slate-500 whitespace-nowrap">
                    <?= e($c['tanggal']) ?>
                </span>

                <?php if ($c['verified']): ?>

                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 font-medium whitespace-nowrap">
                        Terverifikasi
                    </span>

                <?php endif; ?>

            </div>

        </div>

    <?php endforeach; ?>

</section>

<?php endif; ?>