<div
    class="p-4 sm:p-5 lg:p-6
           bg-white
           border border-slate-200
           rounded-xl"
>

    <div class="flex items-start gap-3">

        <div
            class="flex items-center justify-center
                   w-11 h-11 shrink-0
                   rounded-xl
                   bg-blue-50
                   text-blue-600
                   text-sm font-bold"
        >
            <?= e(
                initials(
                    $formasi['perusahaan'] ?? ''
                )
            ) ?>
        </div>


        <div class="min-w-0 flex-1">

            <div
                class="flex flex-wrap
                       items-center gap-2"
            >

                <h2
                    class="text-base sm:text-lg
                           font-semibold
                           text-slate-900
                           break-words"
                >
                    <?= e(
                        $formasi['posisi']
                        ?? '-'
                    ) ?>
                </h2>


                <span
                    class="inline-flex
                           items-center
                           px-2.5 py-1
                           text-xs
                           font-medium
                           text-blue-700
                           bg-blue-50
                           rounded-full"
                >
                    Formasi Dipilih
                </span>

            </div>


            <p
                class="mt-1
                       text-sm
                       font-medium
                       text-slate-600
                       break-words"
            >
                <?= e(
                    $formasi['perusahaan']
                    ?? '-'
                ) ?>
            </p>


            <div
                class="flex flex-wrap
                       gap-x-4 gap-y-2
                       mt-3
                       text-xs sm:text-sm
                       text-slate-500"
            >

                <span class="inline-flex items-center gap-1.5">

                    <?= icon('location', 'w-4 h-4 shrink-0') ?>

                    <?= e(
                        $formasi['lokasi']
                        ?? '-'
                    ) ?>

                </span>


                <span class="inline-flex items-center gap-1.5">

                    <?= icon('calendar', 'w-4 h-4 shrink-0') ?>

                    <?= e(
                        $formasi['durasi']
                        ?? '-'
                    ) ?>

                </span>

            </div>

        </div>

    </div>


    <div
        class="mt-5
               p-3 sm:p-4
               rounded-lg
               bg-slate-50
               border border-slate-100"
    >

        <p
            class="text-xs sm:text-sm
                   leading-6
                   text-slate-500"
        >
            Formasi ini menjadi tujuan pengajuan
            magang Anda. Pastikan posisi dan
            periode magang sudah sesuai sebelum
            melanjutkan pengajuan.
        </p>

    </div>

</div>