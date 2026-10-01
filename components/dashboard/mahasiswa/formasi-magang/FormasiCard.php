<div class="p-4 bg-white border border-slate-200 rounded-xl hover:shadow-sm transition">

    <div class="flex flex-col gap-4 lg:flex-row lg:items-center">

        <!-- Logo -->
        <div
            class="flex items-center justify-center w-full h-16
                   bg-white border border-slate-100 rounded-lg
                   lg:w-20 lg:flex-shrink-0">

            <span class="<?= $logoClass ?>">
                <?= e($logo) ?>
            </span>

        </div>


        <!-- Informasi -->
        <div class="flex-1 min-w-0">

            <h2 class="text-sm font-semibold text-slate-800">
                <?= e($perusahaan) ?>
            </h2>

            <p class="mt-1 text-xs text-slate-500">
                <?= e($posisi) ?>
            </p>

        </div>


        <!-- Lokasi -->
        <div class="flex items-center gap-2 text-xs text-slate-500 lg:w-32">

            <svg
                class="w-4 h-4 text-slate-400 flex-shrink-0"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 21s7-6.2 7-12a7 7 0 1 0-14 0c0 5.8 7 12 7 12Z" />

                <circle cx="12" cy="9" r="2.5" />

            </svg>

            <span>
                <?= e($lokasi) ?>
            </span>

        </div>


        <!-- Durasi -->
        <div class="flex items-center gap-2 text-xs text-slate-500 lg:w-24">

            <svg
                class="w-4 h-4 text-slate-400 flex-shrink-0"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">

                <circle
                    cx="12"
                    cy="12"
                    r="9"
                    stroke-width="2" />

                <path
                    stroke-linecap="round"
                    stroke-width="2"
                    d="M12 7v5l3 2" />

            </svg>

            <span>
                <?= e($durasi) ?>
            </span>

        </div>


        <!-- Status -->
        <div class="lg:w-20">

            <span
                class="inline-flex px-2.5 py-1 text-[11px]
                       font-medium text-emerald-600
                       bg-emerald-50 rounded-full">

                Tersedia

            </span>

        </div>


        <!-- Action -->
        <div class="flex items-center gap-2">

            <a
                href="<?= url($detailUrl) ?>"
                class="inline-flex items-center justify-center
                       h-9 px-4 text-xs font-medium text-blue-500
                       border border-blue-200 rounded-lg
                       hover:bg-blue-50 transition">

                Lihat Detail

            </a>

            <a
                href="<?= url($lamarUrl) ?>"
                class="inline-flex items-center justify-center
                       h-9 px-4 text-xs font-medium text-white
                       bg-blue-600 rounded-lg
                       hover:bg-blue-700 transition">

                Lamar

            </a>

        </div>

    </div>

</div>