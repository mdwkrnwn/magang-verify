<section
    class="rounded-2xl bg-gradient-to-r from-blue-100/70 via-blue-50 to-white border border-blue-100 p-5 sm:p-8"
>

    <div class="flex flex-col md:flex-row items-center md:items-start gap-6 md:gap-8">

        <?php if (!empty($m['logo'])): ?>

            <img
                src="<?= url('/assets/images/mitra/' . e($m['logo'])) ?>"
                alt="Logo <?= e($m['nama']) ?>"
                class="w-28 h-28 sm:w-32 sm:h-32 rounded-2xl bg-white border border-blue-100 object-contain p-3 shrink-0"
            >

        <?php else: ?>

            <span
                class="w-28 h-28 sm:w-32 sm:h-32 rounded-2xl bg-white border border-blue-100 text-blue-600 text-4xl font-bold grid place-items-center shrink-0"
            >
                <?= e($inisial) ?>
            </span>

        <?php endif; ?>


        <div class="min-w-0 text-center md:text-left flex-1">

            <div
                class="inline-flex items-center gap-2 text-xs font-medium text-blue-700 bg-white/80 border border-blue-100 rounded-full pl-1.5 pr-3 py-1"
            >
                <span class="w-5 h-5 rounded-full bg-blue-600 text-white grid place-items-center">
                    <?= icon('shield', 'w-3 h-3') ?>
                </span>

                <?= e($m['status']) ?>
            </div>


            <h1 class="mt-3 text-2xl md:text-3xl font-bold text-slate-900 leading-tight">
                <?= e($m['nama']) ?>
            </h1>


            <div class="flex flex-wrap justify-center md:justify-start gap-2 mt-3">

                <?php foreach (
                    [
                        $m['kategori'] ?? '',
                        'Skala ' . ($m['skala'] ?? '-'),
                        $m['lokasi']
                    ] as $chip
                ): ?>

                    <?php if ($chip !== ''): ?>

                        <span
                            class="px-3 py-1 rounded-full bg-white/80 border border-blue-100 text-xs font-medium text-slate-700"
                        >
                            <?= e($chip) ?>
                        </span>

                    <?php endif; ?>

                <?php endforeach; ?>


                <?php foreach ($m['bidang'] as $b): ?>

                    <span
                        class="px-3 py-1 rounded-full bg-blue-600/10 text-xs font-medium text-blue-700"
                    >
                        <?= e($b) ?>
                    </span>

                <?php endforeach; ?>

            </div>

        </div>


        <dl class="grid grid-cols-3 gap-3 w-full md:w-auto md:min-w-[270px] text-center">

            <?php foreach (
                [
                    [(int) $m['posisi'], 'Kuota magang'],
                    [count($m['formasi']), 'Formasi'],
                    [(int) $m['magang'], 'Mahasiswa magang']
                ] as [$n, $l]
            ): ?>

                <div class="rounded-xl bg-white/80 border border-blue-100 px-2 py-3">

                    <dd class="text-xl font-semibold text-slate-900 leading-none">
                        <?= $n ?>
                    </dd>

                    <dt class="text-[11px] text-slate-500 mt-1.5 leading-4">
                        <?= e($l) ?>
                    </dt>

                </div>

            <?php endforeach; ?>

        </dl>

    </div>

</section>