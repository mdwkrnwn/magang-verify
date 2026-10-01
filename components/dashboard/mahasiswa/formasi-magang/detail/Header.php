<?php

$status = $formasi['status'] ?? 'penuh';

$isTersedia = $status === 'tersedia';

$initial = initials(
    $formasi['perusahaan'] ?? ''
);

?>

<div class="mb-5">

    <!-- Back -->
    <a
        href="<?= e(url('/dashboard/mahasiswa/formasi-magang')) ?>"
        class="inline-flex items-center gap-2 mb-4
               text-sm font-medium text-slate-500
               hover:text-blue-600 transition">

        <?= icon('arrow-left', 'w-4 h-4 shrink-0') ?>

        <span>
            ← Kembali ke Formasi Magang
        </span>

    </a>


    <!-- Header Card -->
    <div
        class="p-4 sm:p-5 lg:p-6
               bg-white border border-slate-200
               rounded-xl">

        <div
            class="flex flex-col gap-5
                   lg:flex-row lg:items-center
                   lg:justify-between">

            <!-- Information -->
            <div class="flex items-start gap-3 sm:gap-4 min-w-0">

                <!-- Logo -->
                <div
                    class="flex items-center justify-center
                           w-12 h-12 sm:w-14 sm:h-14
                           shrink-0
                           rounded-xl
                           bg-blue-50 text-blue-600
                           text-base sm:text-lg
                           font-bold">
                    <?= e($initial) ?>
                </div>


                <!-- Text -->
                <div class="min-w-0 flex-1">

                    <!-- Position + Status -->
                    <div class="flex flex-wrap items-center gap-2">

                        <h1
                            class="text-lg sm:text-xl lg:text-2xl
                                   font-bold tracking-tight
                                   text-slate-900
                                   break-words">
                            <?= e($formasi['posisi']) ?>
                        </h1>


                        <?php if ($isTersedia): ?>

                            <span
                                class="inline-flex items-center
                                       px-2.5 py-1
                                       text-xs font-medium
                                       text-emerald-700
                                       bg-emerald-50
                                       rounded-full
                                       shrink-0">
                                Tersedia
                            </span>

                        <?php else: ?>

                            <span
                                class="inline-flex items-center
                                       px-2.5 py-1
                                       text-xs font-medium
                                       text-slate-600
                                       bg-slate-100
                                       rounded-full
                                       shrink-0">
                                Penuh
                            </span>

                        <?php endif; ?>

                    </div>


                    <!-- Company -->
                    <p
                        class="mt-1
                               text-sm sm:text-base
                               font-medium
                               text-slate-600
                               break-words">
                        <?= e($formasi['perusahaan']) ?>
                    </p>


                    <!-- Meta -->
                    <div
                        class="flex flex-wrap
                               gap-x-4 gap-y-2
                               mt-3
                               text-xs sm:text-sm
                               text-slate-500">

                        <span
                            class="inline-flex items-center gap-1.5">

                            <?= icon('location', 'w-4 h-4 shrink-0') ?>

                            <span>
                                <?= e($formasi['lokasi']) ?>
                            </span>

                        </span>


                        <span
                            class="inline-flex items-center gap-1.5">

                            <?= icon('calendar', 'w-4 h-4 shrink-0') ?>

                            <span>
                                <?= e($formasi['durasi']) ?>
                            </span>

                        </span>

                    </div>

                </div>

            </div>


            <!-- Action -->
            <div class="w-full lg:w-auto lg:shrink-0">

                <?php if ($isTersedia): ?>

                    <a
                        href="<?= e(url(
                                    '/dashboard/mahasiswa/lamar/'
                                        . $formasi['slug']
                                )) ?>"
                        class="inline-flex items-center justify-center
           w-full lg:w-auto
           min-w-[120px]
           h-11
           px-5
           text-sm font-semibold
           text-center text-white
           bg-blue-600
           rounded-lg
           hover:bg-blue-700
           transition">
                        <?= icon('send', 'w-4 h-4 shrink-0') ?>

                        <span>
                            Lamar
                        </span>
                    </a>

                <?php else: ?>

                    <span
                        class="inline-flex items-center justify-center
                               w-full lg:w-auto
                               min-w-[120px]
                               h-11
                               px-5
                               text-sm font-semibold
                               text-center
                               text-slate-400
                               bg-slate-100
                               rounded-lg
                               cursor-not-allowed">
                        Kuota Penuh
                    </span>

                <?php endif; ?>

            </div>

        </div>

    </div>

</div>