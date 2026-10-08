<!-- Perlu Tindakan -->

<div class="p-5 bg-white border border-gray-100 shadow-sm sm:p-7 rounded-2xl">

    <div class="flex flex-wrap items-start justify-between gap-3 mb-6">

        <div>

            <h2 class="text-lg font-semibold text-gray-900">
                Perlu Tindakan
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Pengajuan dan logbook yang membutuhkan tindakan Anda.
            </p>

        </div>

        <span class="px-3 py-1 text-xs font-medium text-orange-700 bg-orange-50 rounded-full">
            8 Tindakan
        </span>

    </div>


    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">

        <!-- Pengajuan Magang -->

        <div class="p-4 border border-gray-100 rounded-xl">

            <div class="flex items-start justify-between gap-3">

                <div class="flex items-start gap-3 min-w-0">

                    <div class="flex items-center justify-center w-10 h-10 text-orange-600 bg-orange-50 rounded-lg shrink-0">

                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12h6m-6 4h4M7 3h8l4 4v14H7a2 2 0 01-2-2V5a2 2 0 012-2Zm8 0v5h4" />

                        </svg>

                    </div>

                    <div class="min-w-0">

                        <p class="text-xs font-medium tracking-wide text-orange-600 uppercase">
                            Pengajuan Magang
                        </p>

                        <h3 class="mt-1 text-sm font-semibold text-gray-900">
                            Ahmad Rizki
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            PT Teknologi Nusantara
                        </p>

                        <p class="mt-2 text-xs text-gray-400">
                            Menunggu persetujuan dosen
                        </p>

                    </div>

                </div>

            </div>

            <a
                href="<?= url('/dashboard/dosen/pengajuan') ?>"
                class="inline-flex items-center mt-4 text-sm font-medium text-blue-600 hover:text-blue-700">

                Tinjau Pengajuan

                <span class="ml-1">
                    →
                </span>

            </a>

        </div>


        <!-- Logbook -->

        <div class="p-4 border border-gray-100 rounded-xl">

            <div class="flex items-start gap-3">

                <div class="flex items-center justify-center w-10 h-10 text-blue-600 bg-blue-50 rounded-lg shrink-0">

                    <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M7 3h8l4 4v14H7a2 2 0 01-2-2V5a2 2 0 012-2Zm8 0v5h4M9 13h6M9 17h6" />

                    </svg>

                </div>

                <div class="min-w-0">

                    <p class="text-xs font-medium tracking-wide text-blue-600 uppercase">
                        Logbook
                    </p>

                    <h3 class="mt-1 text-sm font-semibold text-gray-900">
                        Salsabila Putri
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Minggu ke-4
                    </p>

                    <div class="flex flex-wrap gap-2 mt-2">

                        <span class="px-2 py-1 text-xs text-green-700 bg-green-50 rounded-md">
                            Mahasiswa ✓
                        </span>

                        <span class="px-2 py-1 text-xs text-green-700 bg-green-50 rounded-md">
                            Mitra ✓
                        </span>

                        <span class="px-2 py-1 text-xs text-yellow-700 bg-yellow-50 rounded-md">
                            Dosen —
                        </span>

                    </div>

                </div>

            </div>

            <a
                href="<?= url('/dashboard/dosen/logbook') ?>"
                class="inline-flex items-center mt-4 text-sm font-medium text-blue-600 hover:text-blue-700">

                Tinjau Logbook

                <span class="ml-1">
                    →
                </span>

            </a>

        </div>

    </div>


    <div class="mt-5 text-center">

        <a
            href="<?= url('/dashboard/dosen/logbook') ?>"
            class="inline-flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-blue-600 border border-blue-100 rounded-xl hover:bg-blue-50">

            Lihat Semua Tindakan

        </a>

    </div>

</div>