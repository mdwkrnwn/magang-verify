<section class="overflow-hidden bg-white border border-slate-100 shadow-sm rounded-2xl">

    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">

        <div>

            <h2 class="font-semibold text-slate-900">
                Logbook Mahasiswa Bimbingan
            </h2>

            <p class="mt-1 text-xs text-slate-500">
                Status berdasarkan tahapan tanda tangan logbook.
            </p>

        </div>

        <span class="hidden px-3 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded-full sm:inline-flex">
            18 Logbook
        </span>

    </div>


    <div class="divide-y divide-slate-100">


        <!-- ============================== -->
        <!-- LOGBOOK 1 - MENUNGGU DOSEN -->
        <!-- ============================== -->

        <div
            data-logbook-row
            data-search="Ahmad Rizki Pratama PT Semarsoft Technology Indonesia Minggu 1"
            data-status="menunggu_dosen"
            class="p-5 transition hover:bg-slate-50/70"
        >

            <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">

                <!-- Identity -->

                <div class="flex items-start gap-4 min-w-0">

                    <div class="flex items-center justify-center flex-shrink-0 w-11 h-11 font-semibold text-blue-700 bg-blue-50 rounded-xl">
                        AR
                    </div>

                    <div class="min-w-0">

                        <div class="flex flex-wrap items-center gap-2">

                            <h3 class="font-semibold text-slate-900">
                                Ahmad Rizki Pratama
                            </h3>

                            <span class="text-xs text-slate-400">
                                Minggu 1
                            </span>

                        </div>

                        <p class="mt-1 text-sm text-slate-500">
                            PT Semarsoft Technology Indonesia
                        </p>

                        <p class="mt-2 text-xs text-slate-400">
                            7 Oktober – 11 Oktober 2026
                        </p>

                    </div>

                </div>


                <!-- Signature Workflow -->

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">

                    <!-- Mahasiswa -->

                    <div class="flex items-center gap-2">

                        <div class="flex items-center justify-center w-7 h-7 text-xs font-semibold text-emerald-700 bg-emerald-50 rounded-full">
                            ✓
                        </div>

                        <div>

                            <p class="text-xs font-medium text-slate-700">
                                Mahasiswa
                            </p>

                            <p class="text-[11px] text-emerald-600">
                                Sudah sign
                            </p>

                        </div>

                    </div>


                    <div class="hidden text-slate-300 sm:block">
                        →
                    </div>


                    <!-- Mitra -->

                    <div class="flex items-center gap-2">

                        <div class="flex items-center justify-center w-7 h-7 text-xs font-semibold text-emerald-700 bg-emerald-50 rounded-full">
                            ✓
                        </div>

                        <div>

                            <p class="text-xs font-medium text-slate-700">
                                Mitra
                            </p>

                            <p class="text-[11px] text-emerald-600">
                                Sudah sign
                            </p>

                        </div>

                    </div>


                    <div class="hidden text-slate-300 sm:block">
                        →
                    </div>


                    <!-- Dosen -->

                    <div class="flex items-center gap-2">

                        <div class="flex items-center justify-center w-7 h-7 text-xs font-semibold text-amber-700 bg-amber-50 rounded-full">
                            !
                        </div>

                        <div>

                            <p class="text-xs font-medium text-slate-700">
                                Dosen
                            </p>

                            <p class="text-[11px] text-amber-600">
                                Perlu tindakan
                            </p>

                        </div>

                    </div>

                </div>


                <!-- Action -->

                <div class="flex items-center gap-2">

                    <a
                        href="<?= url('/dashboard/dosen/logbook/detail/1') ?>"
                        class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-slate-600 transition border border-slate-200 rounded-xl hover:bg-slate-50"
                    >
                        Detail
                    </a>

                    <a
                        href="<?= url('/dashboard/dosen/logbook/tanda-tangan/1') ?>"
                        class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-white transition bg-blue-600 rounded-xl hover:bg-blue-700"
                    >
                        Review & Tanda Tangan

                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            />
                        </svg>

                    </a>

                </div>

            </div>

        </div>


        <!-- ============================== -->
        <!-- LOGBOOK 2 - MENUNGGU MITRA -->
        <!-- ============================== -->

        <div
            data-logbook-row
            data-search="Siti Aisyah PT Digital Nusantara Minggu 2"
            data-status="menunggu_mitra"
            class="p-5 transition hover:bg-slate-50/70"
        >

            <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">

                <div class="flex items-start gap-4 min-w-0">

                    <div class="flex items-center justify-center flex-shrink-0 w-11 h-11 font-semibold text-violet-700 bg-violet-50 rounded-xl">
                        SA
                    </div>

                    <div>

                        <div class="flex flex-wrap items-center gap-2">

                            <h3 class="font-semibold text-slate-900">
                                Siti Aisyah
                            </h3>

                            <span class="text-xs text-slate-400">
                                Minggu 2
                            </span>

                        </div>

                        <p class="mt-1 text-sm text-slate-500">
                            PT Digital Nusantara
                        </p>

                        <p class="mt-2 text-xs text-slate-400">
                            12 Oktober – 18 Oktober 2026
                        </p>

                    </div>

                </div>


                <div class="flex flex-wrap items-center gap-4">

                    <div class="flex items-center gap-2">

                        <div class="flex items-center justify-center w-7 h-7 text-xs font-semibold text-emerald-700 bg-emerald-50 rounded-full">
                            ✓
                        </div>

                        <div>

                            <p class="text-xs font-medium text-slate-700">
                                Mahasiswa
                            </p>

                            <p class="text-[11px] text-emerald-600">
                                Sudah sign
                            </p>

                        </div>

                    </div>

                    <span class="text-slate-300">
                        →
                    </span>

                    <div class="flex items-center gap-2">

                        <div class="flex items-center justify-center w-7 h-7 text-xs font-semibold text-amber-700 bg-amber-50 rounded-full">
                            !
                        </div>

                        <div>

                            <p class="text-xs font-medium text-slate-700">
                                Mitra
                            </p>

                            <p class="text-[11px] text-amber-600">
                                Menunggu
                            </p>

                        </div>

                    </div>

                    <span class="text-slate-300">
                        →
                    </span>

                    <div class="flex items-center gap-2 opacity-50">

                        <div class="flex items-center justify-center w-7 h-7 text-xs font-semibold bg-slate-100 text-slate-500 rounded-full">
                            3
                        </div>

                        <div>

                            <p class="text-xs font-medium text-slate-700">
                                Dosen
                            </p>

                            <p class="text-[11px] text-slate-400">
                                Belum tersedia
                            </p>

                        </div>

                    </div>

                    <a
                        href="<?= url('/dashboard/dosen/logbook/detail/2') ?>"
                        class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-blue-600 transition border border-blue-100 rounded-xl hover:bg-blue-50"
                    >
                        Lihat Detail
                    </a>

                </div>

            </div>

        </div>


        <!-- ============================== -->
        <!-- LOGBOOK 3 - DISETUJUI -->
        <!-- ============================== -->

        <div
            data-logbook-row
            data-search="Budi Santoso CV Kreasi Digital Minggu 3"
            data-status="disetujui"
            class="p-5 transition hover:bg-slate-50/70"
        >

            <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">

                <div class="flex items-start gap-4 min-w-0">

                    <div class="flex items-center justify-center flex-shrink-0 w-11 h-11 font-semibold text-cyan-700 bg-cyan-50 rounded-xl">
                        BS
                    </div>

                    <div>

                        <div class="flex flex-wrap items-center gap-2">

                            <h3 class="font-semibold text-slate-900">
                                Budi Santoso
                            </h3>

                            <span class="text-xs text-slate-400">
                                Minggu 3
                            </span>

                        </div>

                        <p class="mt-1 text-sm text-slate-500">
                            CV Kreasi Digital
                        </p>

                        <p class="mt-2 text-xs text-slate-400">
                            19 Oktober – 25 Oktober 2026
                        </p>

                    </div>

                </div>


                <div class="flex flex-wrap items-center gap-4">

                    <div class="flex items-center gap-2">

                        <div class="flex items-center justify-center w-7 h-7 text-xs font-semibold text-emerald-700 bg-emerald-50 rounded-full">
                            ✓
                        </div>

                        <div>
                            <p class="text-xs font-medium text-slate-700">
                                Mahasiswa
                            </p>
                            <p class="text-[11px] text-emerald-600">
                                Sudah sign
                            </p>
                        </div>

                    </div>

                    <span class="text-slate-300">→</span>

                    <div class="flex items-center gap-2">

                        <div class="flex items-center justify-center w-7 h-7 text-xs font-semibold text-emerald-700 bg-emerald-50 rounded-full">
                            ✓
                        </div>

                        <div>
                            <p class="text-xs font-medium text-slate-700">
                                Mitra
                            </p>
                            <p class="text-[11px] text-emerald-600">
                                Sudah sign
                            </p>
                        </div>

                    </div>

                    <span class="text-slate-300">→</span>

                    <div class="flex items-center gap-2">

                        <div class="flex items-center justify-center w-7 h-7 text-xs font-semibold text-emerald-700 bg-emerald-50 rounded-full">
                            ✓
                        </div>

                        <div>
                            <p class="text-xs font-medium text-slate-700">
                                Dosen
                            </p>
                            <p class="text-[11px] text-emerald-600">
                                Sudah sign
                            </p>
                        </div>

                    </div>

                    <a
                        href="<?= url('/dashboard/dosen/logbook/detail/3') ?>"
                        class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-blue-600 transition border border-blue-100 rounded-xl hover:bg-blue-50"
                    >
                        Lihat Detail
                    </a>

                </div>

            </div>

        </div>


        <!-- ============================== -->
        <!-- EMPTY STATE -->
        <!-- ============================== -->

        <div
            id="emptyLogbookState"
            class="hidden px-5 py-12 text-center"
        >

            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 text-slate-400 bg-slate-100 rounded-full">

                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"
                    />
                </svg>

            </div>

            <h3 class="font-semibold text-slate-700">
                Logbook tidak ditemukan
            </h3>

            <p class="mt-1 text-sm text-slate-400">
                Coba gunakan kata kunci atau filter status yang berbeda.
            </p>

        </div>

    </div>

</section>