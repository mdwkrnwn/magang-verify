<div
    class="p-4 sm:p-5 lg:p-6
           bg-white border border-slate-200
           rounded-xl"
>

    <!-- Section Header -->
    <div class="flex items-start gap-3 mb-4">

        <div
            class="flex items-center justify-center
                   w-9 h-9 shrink-0
                   rounded-lg
                   bg-blue-50 text-blue-600"
        >
            <?= icon('briefcase', 'w-4 h-4') ?>
        </div>


        <div class="min-w-0">

            <h2
                class="text-base sm:text-lg
                       font-semibold
                       text-slate-900"
            >
                Tentang Posisi
            </h2>

            <p
                class="mt-0.5
                       text-xs sm:text-sm
                       text-slate-500"
            >
                Informasi pekerjaan dan ruang lingkup magang
            </p>

        </div>

    </div>


    <!-- Description -->
    <div
        class="text-sm
               leading-7
               text-slate-600
               break-words"
    >

        <?= nl2br(
            e(
                $formasi['deskripsi']
                ?? 'Deskripsi posisi belum tersedia.'
            )
        ) ?>

    </div>

</div>