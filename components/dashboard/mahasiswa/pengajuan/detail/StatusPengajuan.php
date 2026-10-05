<section class="bg-white border border-slate-200 rounded-xl overflow-hidden">

    <div class="px-5 py-4 border-b border-slate-200">

        <h2 class="text-sm font-semibold text-slate-800">
            Status Pengajuan
        </h2>

        <p class="mt-1 text-xs text-slate-500">
            Perkembangan proses pengajuan Anda.
        </p>

    </div>


    <div class="p-5">

        <div class="space-y-6">

            <?php foreach ($pengajuanDetail['timeline'] as $index => $timeline): ?>

                <?php

                /*
                 * Mapping status dari data
                 * ke tampilan timeline.
                 */

                $timelineStatus =
                    $timeline['status']
                    ?? 'waiting';

                $statusClass = match ($timelineStatus) {

                    'completed' => [
                        'dot' => 'bg-blue-600 border-blue-600',
                        'text' => 'text-slate-800',
                    ],

                    'process' => [
                        'dot' => 'bg-white border-blue-600',
                        'text' => 'text-slate-800',
                    ],

                    'rejected' => [
                        'dot' => 'bg-red-500 border-red-500',
                        'text' => 'text-slate-800',
                    ],

                    default => [
                        'dot' => 'bg-white border-slate-300',
                        'text' => 'text-slate-500',
                    ],

                };

                ?>

                <div class="relative flex gap-3">

                    <?php if (
                        $index <
                        count($pengajuanDetail['timeline']) - 1
                    ): ?>

                        <div
                            class="absolute left-[7px] top-5
                                   w-px h-[calc(100%+8px)]
                                   bg-slate-200"
                        ></div>

                    <?php endif; ?>


                    <div
                        class="relative z-10
                               w-4 h-4 mt-0.5
                               rounded-full border-2
                               flex-shrink-0
                               <?= e($statusClass['dot']) ?>"
                    ></div>


                    <div>

                        <p
                            class="text-sm font-medium
                                   <?= e($statusClass['text']) ?>"
                        >
                            <?= e($timeline['title'] ?? '-') ?>
                        </p>


                        <p class="mt-1 text-xs text-slate-400">

                            <?= !empty($timeline['date'])
                                ? e($timeline['date'])
                                : 'Belum diproses'
                            ?>

                        </p>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

</section>