<div class="p-4 sm:p-5 bg-white border border-slate-200 rounded-xl">

    <!-- Calendar Header -->
    <div class="flex items-center justify-between mb-4">

        <h2 class="text-sm font-semibold text-slate-700">
            Juni 2025
        </h2>

        <button
            type="button"
            class="flex items-center justify-center
                   w-8 h-8 text-slate-400
                   hover:text-blue-600 transition">

            <svg
                class="w-4 h-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="m9 18 6-6-6-6" />

            </svg>

        </button>

    </div>


    <!-- Days -->
    <div class="grid grid-cols-7 mb-3">

        <?php foreach (['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $day): ?>

            <div class="py-2 text-center text-[10px] font-medium text-slate-400">
                <?= $day ?>
            </div>

        <?php endforeach; ?>

    </div>


    <!-- Dates -->
    <div class="grid grid-cols-7 gap-y-2">

        <?php

        $calendar = [
            '', '', '', '', '', '', '1',
            '2', '3', '4', '5', '6', '7', '8',
            '9', '10', '11', '12', '13', '14', '15',
            '16', '17', '18', '19', '20', '21', '22',
            '23', '24', '25', '26', '27', '28', '29',
            '30'
        ];

        foreach ($calendar as $date):

        ?>

            <div class="flex items-center justify-center h-8">

                <?php if ($date === '12'): ?>

                    <span
                        class="flex items-center justify-center
                               w-7 h-7
                               text-[10px] font-semibold text-white
                               bg-blue-600 rounded-full">

                        <?= $date ?>

                    </span>

                <?php elseif ($date !== ''): ?>

                    <span
                        class="text-[10px] text-slate-500
                               hover:text-blue-600 cursor-pointer">

                        <?= $date ?>

                    </span>

                <?php endif; ?>

            </div>

        <?php endforeach; ?>

    </div>

</div>