<section id="formasi" class="<?= $card ?>">

    <?php $judul('layers', 'Formasi Magang'); ?>


    <?php if (!$m['formasi']): ?>

        <p class="text-sm text-slate-500">
            Belum ada formasi yang dibuka oleh mitra ini.
        </p>

    <?php endif; ?>


    <div class="space-y-4">

        <?php foreach ($m['formasi'] as $f): ?>

            <article class="rounded-xl border border-slate-100 p-4 sm:p-5">

                <div class="flex flex-wrap items-start justify-between gap-3">

                    <div class="min-w-0">

                        <h3 class="font-semibold text-slate-900">
                            <?= e($f['posisi']) ?>
                        </h3>


                        <div class="flex flex-wrap gap-2 mt-2">

                            <?php foreach ($f['prodi'] as $pr): ?>

                                <span class="px-2.5 py-1 rounded-full bg-blue-50 text-xs text-slate-700">
                                    <?= e($pr) ?>
                                </span>

                            <?php endforeach; ?>

                        </div>

                    </div>


                    <a
                        href="<?= url('/login') ?>"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-blue-500 text-blue-600 text-sm font-medium hover:bg-blue-50 transition whitespace-nowrap"
                    >
                        Masuk untuk mengajukan
                        <?= icon('arrow', 'w-4 h-4') ?>
                    </a>

                </div>


                <dl class="grid grid-cols-3 gap-3 mt-4 text-sm">

                    <?php foreach (
                        [
                            ['Kuota', $f['kuota'] . ' orang'],
                            ['Periode', $f['periode']],
                            ['Batas daftar', $f['batas']]
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


                <div class="mt-4 pt-4 border-t border-slate-100 text-sm">

                    <p class="text-xs font-semibold text-slate-800 mb-1">
                        Deskripsi pekerjaan
                    </p>

                    <p class="text-slate-600 leading-relaxed">
                        <?= e($f['jobdesc']) ?>
                    </p>


                    <p class="text-xs font-semibold text-slate-800 mt-4 mb-1">
                        Persyaratan mahasiswa
                    </p>

                    <ul class="list-disc pl-4 space-y-1 text-slate-600">

                        <?php foreach ($f['syarat'] as $sy): ?>

                            <li>
                                <?= e($sy) ?>
                            </li>

                        <?php endforeach; ?>

                    </ul>


                    <p class="text-xs font-semibold text-slate-800 mt-4 mb-1">
                        Tahapan seleksi
                    </p>

                    <p class="text-slate-600">
                        <?= e($f['seleksi']) ?>
                    </p>

                </div>

            </article>

        <?php endforeach; ?>

    </div>

</section>