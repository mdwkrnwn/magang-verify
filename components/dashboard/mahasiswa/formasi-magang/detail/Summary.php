<div
    class="p-4 sm:p-5
           bg-white border border-slate-200
           rounded-xl"
>

    <div class="flex items-center gap-3 mb-5">

        <div
            class="flex items-center justify-center
                   w-9 h-9 shrink-0
                   rounded-lg
                   bg-blue-50 text-blue-600"
        >
            <?= icon('briefcase', 'w-4 h-4') ?>
        </div>

        <div>

            <h2
                class="text-base
                       font-semibold
                       text-slate-900"
            >
                Ringkasan Formasi
            </h2>

            <p
                class="mt-0.5
                       text-xs
                       text-slate-500"
            >
                Informasi utama formasi
            </p>

        </div>

    </div>


    <div class="divide-y divide-slate-100">

        <!-- Lokasi -->
        <div
            class="flex items-start
                   justify-between gap-4
                   py-3 first:pt-0"
        >

            <span class="text-xs sm:text-sm text-slate-500">
                Lokasi
            </span>

            <span
                class="max-w-[60%]
                       text-right
                       text-xs sm:text-sm
                       font-medium
                       text-slate-700
                       break-words"
            >
                <?= e($formasi['lokasi'] ?? '-') ?>
            </span>

        </div>


        <!-- Durasi -->
        <div
            class="flex items-start
                   justify-between gap-4
                   py-3"
        >

            <span class="text-xs sm:text-sm text-slate-500">
                Durasi
            </span>

            <span
                class="max-w-[60%]
                       text-right
                       text-xs sm:text-sm
                       font-medium
                       text-slate-700
                       break-words"
            >
                <?= e($formasi['durasi'] ?? '-') ?>
            </span>

        </div>


        <!-- Periode -->
        <div
            class="flex items-start
                   justify-between gap-4
                   py-3"
        >

            <span class="text-xs sm:text-sm text-slate-500">
                Periode
            </span>

            <span
                class="max-w-[60%]
                       text-right
                       text-xs sm:text-sm
                       font-medium
                       text-slate-700
                       break-words"
            >
                <?= e($formasi['periode'] ?? '-') ?>
            </span>

        </div>


        <!-- Deadline -->
        <div
            class="flex items-start
                   justify-between gap-4
                   py-3"
        >

            <span class="text-xs sm:text-sm text-slate-500">
                Batas Pendaftaran
            </span>

            <span
                class="max-w-[60%]
                       text-right
                       text-xs sm:text-sm
                       font-medium
                       text-slate-700
                       break-words"
            >
                <?= e($formasi['batas_daftar'] ?? '-') ?>
            </span>

        </div>


        <!-- Kuota -->
        <div
            class="flex items-start
                   justify-between gap-4
                   py-3 last:pb-0"
        >

            <span class="text-xs sm:text-sm text-slate-500">
                Kuota
            </span>

            <span
                class="max-w-[60%]
                       text-right
                       text-xs sm:text-sm
                       font-semibold
                       <?= ($formasi['status'] ?? '') === 'tersedia'
                            ? 'text-emerald-600'
                            : 'text-slate-600'
                       ?>"
            >
                <?= e($formasi['kuota'] ?? '-') ?>
            </span>

        </div>

    </div>

</div>