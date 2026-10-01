<div class="p-4 sm:px-5 sm:py-4 border-b border-slate-200 last:border-b-0">

    <div
        class="flex flex-col gap-4
               lg:grid lg:grid-cols-[minmax(300px,1fr)_170px_170px_150px]
               lg:items-center">


        <!-- Perusahaan & Posisi -->
        <div class="flex items-center gap-4 min-w-0">

            <!-- Logo -->
            <div
                class="flex items-center justify-center
                       w-16 h-16 flex-shrink-0
                       bg-white border border-slate-100
                       rounded-lg shadow-sm">

                <span class="<?= $logoClass ?>">
                    <?= e($logo) ?>
                </span>

            </div>


            <!-- Informasi -->
            <div class="min-w-0">

                <h2 class="text-sm font-semibold text-slate-800">
                    <?= e($perusahaan) ?>
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    <?= e($posisi) ?>
                </p>

            </div>

        </div>


        <!-- Tanggal -->
        <div class="flex flex-col lg:block">

            <span class="text-[11px] text-slate-400 lg:hidden">
                Tanggal Pengajuan
            </span>

            <span class="mt-1 text-xs text-slate-500 lg:mt-0">
                <?= e($tanggal) ?>
            </span>

        </div>


        <!-- Status -->
        <div class="flex items-center gap-2">

            <span class="text-[11px] text-slate-400 lg:hidden">
                Status:
            </span>

            <span
                class="inline-flex px-3 py-1.5
                       text-[11px] font-medium
                       rounded-lg
                       <?= $statusClass ?>">

                <?= e($status) ?>

            </span>

        </div>


        <!-- Aksi -->
        <div>

            <a
                href="<?= url($detailUrl) ?>"
                class="inline-flex items-center justify-center
                       h-9 px-5
                       text-xs font-medium text-blue-500
                       border border-blue-200 rounded-lg
                       hover:bg-blue-50
                       hover:border-blue-300
                       transition">

                Lihat Detail

            </a>

        </div>

    </div>

</div>