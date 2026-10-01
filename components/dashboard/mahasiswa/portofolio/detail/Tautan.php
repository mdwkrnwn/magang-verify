<div class="bg-white border border-slate-200 rounded-xl p-5 sm:p-6">

    <div class="mb-5">

        <h2 class="text-base font-bold text-gray-900">
            Tautan Proyek
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Referensi proyek yang dapat diakses.
        </p>

    </div>


    <div class="space-y-3">

        <?php if (!empty($portofolioDetail['tautan']['github'])): ?>

            <a
                href="<?= e($portofolioDetail['tautan']['github']) ?>"
                target="_blank"
                rel="noopener noreferrer"
                class="flex items-center justify-between gap-3
                       p-3 rounded-lg
                       border border-slate-200
                       hover:bg-slate-50
                       transition"
            >

                <div class="flex items-center gap-3 min-w-0">

                    <div
                        class="w-9 h-9 shrink-0
                               rounded-lg
                               bg-slate-100
                               flex items-center justify-center"
                    >

                        <svg
                            class="w-4 h-4 text-gray-600"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M12 .5C5.65.5.5 5.65.5 12c0 5.08
                                   3.29 9.39 7.86 10.91.58.11.79-.25.79-.56
                                   0-.28-.01-1.02-.02-2-3.2.7-3.88-1.54
                                   -3.88-1.54-.53-1.33-1.28-1.68-1.28-1.68
                                   -1.04-.71.08-.7.08-.7 1.15.08 1.75
                                   1.18 1.75 1.18 1.02 1.75 2.68 1.25
                                   3.33.96.1-.75.4-1.25.73-1.54-2.55-.29
                                   -5.23-1.28-5.23-5.7 0-1.26.45-2.29
                                   1.18-3.1-.12-.29-.51-1.47.11-3.07
                                   0 0 .96-.31 3.15 1.18a10.9 10.9 0 0 1
                                   5.74 0c2.19-1.49 3.15-1.18 3.15-1.18
                                   .62 1.6.23 2.78.11 3.07.73.81 1.18
                                   1.84 1.18 3.1 0 4.43-2.69 5.4-5.25
                                   5.69.41.35.77 1.05.77 2.12 0 1.53-.01
                                   2.76-.01 3.14 0 .31.21.67.8.56A10.99
                                   10.99 0 0 0 23.5 12C23.5 5.65 18.35.5 12 .5Z"
                            />
                        </svg>

                    </div>


                    <div class="min-w-0">

                        <p class="text-sm font-semibold text-gray-900">
                            GitHub
                        </p>

                        <p class="mt-0.5 text-xs text-gray-500 truncate">
                            Lihat source code proyek
                        </p>

                    </div>

                </div>


                <svg
                    class="w-4 h-4 shrink-0 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5l7 7-7 7"
                    />
                </svg>

            </a>

        <?php endif; ?>


        <?php if (!empty($portofolioDetail['tautan']['demo'])): ?>

            <a
                href="<?= e($portofolioDetail['tautan']['demo']) ?>"
                target="_blank"
                rel="noopener noreferrer"
                class="flex items-center justify-between gap-3
                       p-3 rounded-lg
                       border border-slate-200
                       hover:bg-slate-50
                       transition"
            >

                <div class="flex items-center gap-3 min-w-0">

                    <div
                        class="w-9 h-9 shrink-0
                               rounded-lg
                               bg-blue-50 text-blue-600
                               flex items-center justify-center"
                    >

                        <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13.5 6H6a2 2 0 0 0-2 2v8a2 2
                                   0 0 0 2 2h8a2 2 0 0 0 2-2v-3.5M14
                                   4h6m0 0v6m0-6-8 8"
                            />
                        </svg>

                    </div>


                    <div class="min-w-0">

                        <p class="text-sm font-semibold text-gray-900">
                            Demo Proyek
                        </p>

                        <p class="mt-0.5 text-xs text-gray-500 truncate">
                            Lihat hasil proyek
                        </p>

                    </div>

                </div>


                <svg
                    class="w-4 h-4 shrink-0 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5l7 7-7 7"
                    />
                </svg>

            </a>

        <?php endif; ?>

    </div>

</div>