<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

    <!-- Keahlian -->
    <div
        class="p-4 sm:p-5
               bg-white border border-slate-200
               rounded-xl"
    >

        <div class="flex items-center gap-3 mb-4">

            <div
                class="flex items-center justify-center
                       w-9 h-9 shrink-0
                       rounded-lg
                       bg-blue-50 text-blue-600"
            >
                <?= icon('layers', 'w-4 h-4') ?>
            </div>

            <div class="min-w-0">

                <h2
                    class="text-sm sm:text-base
                           font-semibold
                           text-slate-900"
                >
                    Keahlian yang Dibutuhkan
                </h2>

                <p
                    class="mt-0.5
                           text-xs
                           text-slate-500"
                >
                    Kompetensi untuk posisi ini
                </p>

            </div>

        </div>


        <?php if (!empty($formasi['keahlian'])): ?>

            <div class="flex flex-wrap gap-2">

                <?php foreach ($formasi['keahlian'] as $keahlian): ?>

                    <span
                        class="inline-flex items-center
                               max-w-full
                               px-2.5 py-1.5
                               text-xs
                               text-blue-700
                               bg-blue-50
                               rounded-lg
                               break-words"
                    >
                        <?= e($keahlian) ?>
                    </span>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <p class="text-sm text-slate-500">
                Informasi keahlian belum tersedia.
            </p>

        <?php endif; ?>

    </div>


    <!-- Program Studi -->
    <div
        class="p-4 sm:p-5
               bg-white border border-slate-200
               rounded-xl"
    >

        <div class="flex items-center gap-3 mb-4">

            <div
                class="flex items-center justify-center
                       w-9 h-9 shrink-0
                       rounded-lg
                       bg-emerald-50 text-emerald-600"
            >
                <?= icon('layers', 'w-4 h-4') ?>
            </div>

            <div class="min-w-0">

                <h2
                    class="text-sm sm:text-base
                           font-semibold
                           text-slate-900"
                >
                    Program Studi
                </h2>

                <p
                    class="mt-0.5
                           text-xs
                           text-slate-500"
                >
                    Program studi yang relevan
                </p>

            </div>

        </div>


        <?php if (!empty($formasi['prodi'])): ?>

            <div class="flex flex-wrap gap-2">

                <?php foreach ($formasi['prodi'] as $prodi): ?>

                    <span
                        class="inline-flex items-center
                               max-w-full
                               px-2.5 py-1.5
                               text-xs
                               text-slate-700
                               bg-slate-100
                               rounded-lg
                               break-words"
                    >
                        <?= e($prodi) ?>
                    </span>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <p class="text-sm text-slate-500">
                Informasi program studi belum tersedia.
            </p>

        <?php endif; ?>

    </div>

</div>