<!-- Quick Actions -->

<div class="p-5 mt-4 bg-blue-600 shadow-sm sm:p-7 sm:mt-6 rounded-2xl">

    <div class="mb-5">

        <h2 class="text-lg font-semibold text-white">
            Akses Cepat
        </h2>

        <p class="mt-1 text-sm text-blue-100">
            Akses fitur utama untuk mengelola proses bimbingan dan magang.
        </p>

    </div>


    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">

        <!-- Mahasiswa Bimbingan -->

        <a
            href="<?= url('/dashboard/dosen/bimbingan') ?>"
            class="flex items-center gap-3 p-4 bg-white rounded-xl hover:bg-blue-50">

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
                        d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-8a4 4 0 11-8 0 4 4 0 018 0zm6 4a3 3 0 10-3-3" />

                </svg>

            </div>

            <div class="min-w-0">

                <p class="text-sm font-semibold text-gray-900">
                    Mahasiswa Bimbingan
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    Lihat mahasiswa
                </p>

            </div>

        </a>


        <!-- Pengajuan -->

        <a
            href="<?= url('/dashboard/dosen/pengajuan') ?>"
            class="flex items-center gap-3 p-4 bg-white rounded-xl hover:bg-blue-50">

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

                <p class="text-sm font-semibold text-gray-900">
                    Pengajuan Magang
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    Review pengajuan
                </p>

            </div>

        </a>


        <!-- Logbook -->

        <a
            href="<?= url('/dashboard/dosen/logbook') ?>"
            class="flex items-center gap-3 p-4 bg-white rounded-xl hover:bg-blue-50">

            <div class="flex items-center justify-center w-10 h-10 text-green-600 bg-green-50 rounded-lg shrink-0">

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

                <p class="text-sm font-semibold text-gray-900">
                    Logbook
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    Review & signature
                </p>

            </div>

        </a>

    </div>

</div>