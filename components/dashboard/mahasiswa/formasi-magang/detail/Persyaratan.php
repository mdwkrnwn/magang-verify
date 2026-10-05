<div
    class="p-4 sm:p-5 lg:p-6
           bg-white border border-slate-200
           rounded-xl"
>

    <!-- Header -->
    <div class="flex items-start gap-3 mb-5">

        <div
            class="flex items-center justify-center
                   w-9 h-9 shrink-0
                   rounded-lg
                   bg-amber-50 text-amber-600"
        >
            <?= icon('briefcase', 'w-4 h-4') ?>
        </div>


        <div class="min-w-0">

            <h2
                class="text-base sm:text-lg
                       font-semibold
                       text-slate-900"
            >
                Persyaratan
            </h2>

            <p
                class="mt-0.5
                       text-xs sm:text-sm
                       text-slate-500"
            >
                Persyaratan yang perlu dipenuhi mahasiswa
            </p>

        </div>

    </div>


    <?php if (!empty($formasi['persyaratan'])): ?>

        <div class="space-y-3">

            <?php foreach (
                $formasi['persyaratan']
                as $index => $persyaratan
            ): ?>

                <div
                    class="flex items-start gap-3"
                >

                    <!-- Number -->
                    <div
                        class="flex items-center justify-center
                               w-7 h-7 shrink-0
                               mt-0.5
                               rounded-full
                               bg-slate-100
                               text-xs font-semibold
                               text-slate-600"
                    >
                        <?= $index + 1 ?>
                    </div>


                    <!-- Text -->
                    <p
                        class="min-w-0
                               text-sm
                               leading-6
                               text-slate-600
                               break-words"
                    >
                        <?= e($persyaratan) ?>
                    </p>

                </div>

            <?php endforeach; ?>

        </div>

    <?php else: ?>

        <p class="text-sm text-slate-500">
            Persyaratan belum tersedia.
        </p>

    <?php endif; ?>

</div>