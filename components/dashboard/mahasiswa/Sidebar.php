<aside class="fixed top-0 left-0 z-40 w-64 h-screen bg-white border-r border-gray-200">

    <!-- Logo -->
    <div class="flex items-center h-20 px-7 border-b border-gray-100">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-blue-600">
                <svg
                    class="w-6 h-6 text-white"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622C17.176 19.29 21 14.591 21 9c0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>

            <div>
                <h1 class="text-lg font-bold text-gray-900">
                    VerifyMagang
                </h1>
                <p class="text-xs text-gray-500">
                    Mahasiswa
                </p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="px-4 py-6">

        <p class="px-3 mb-3 text-xs font-semibold tracking-wider text-gray-400 uppercase">
            Menu Utama
        </p>

        <div class="space-y-1">

            <!-- Dashboard -->
            <a
                href="<?= url('/dashboard/mahasiswa') ?>"
                class="flex items-center gap-3 px-4 py-3 rounded-xl
    <?= $active === 'dashboard'
        ? 'bg-blue-50 text-blue-600 font-semibold'
        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?>">
                <svg
                    class="w-5 h-5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4" />
                </svg>

                <span class="text-sm">
                    Dashboard
                </span>
            </a>


            <!-- Profil -->
            <a
                href="<?= url('/dashboard/mahasiswa/profil') ?>"
                class="flex items-center gap-3 px-4 py-3 rounded-xl
    <?= $active === 'profil'
        ? 'bg-blue-50 text-blue-600 font-semibold'
        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?>">
                <svg
                    class="w-5 h-5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>

                <span class="text-sm">
                    Profil Saya
                </span>
            </a>


            <!-- Portofolio -->
            <a
                href="<?= url('/dashboard/mahasiswa/portofolio') ?>"
                class="flex items-center gap-3 px-4 py-3 rounded-xl
    <?= $active === 'portofolio'
        ? 'bg-blue-50 text-blue-600 font-semibold'
        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?>">
                <svg
                    class="w-5 h-5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 7h4l2 3h12M5 7v12a1 1 0 001 1h12a1 1 0 001-1V7M9 11h6" />
                </svg>

                <span class="text-sm">
                    Portofolio
                </span>
            </a>


            <!-- Sertifikat -->
            <a
                href="<?= url('/dashboard/mahasiswa/sertifikat') ?>"
                class="flex items-center gap-3 px-4 py-3 rounded-xl
    <?= $active === 'sertifikat'
        ? 'bg-blue-50 text-blue-600 font-semibold'
        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?>">
                <svg
                    class="w-5 h-5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12h6m-6 4h6M7 3h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                </svg>

                <span class="text-sm">
                    Sertifikat
                </span>
            </a>


            <!-- Formasi Magang -->
            <a
                href="<?= url('/dashboard/mahasiswa/formasi-magang') ?>"
                class="flex items-center gap-3 px-4 py-3 rounded-xl
    <?= $active === 'formasi-magang'
        ? 'bg-blue-50 text-blue-600 font-semibold'
        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?>">
                <svg
                    class="w-5 h-5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 5v.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>

                <span class="text-sm">
                    Formasi Magang
                </span>
            </a>


            <!-- Pengajuan Saya -->
            <a
                href="<?= url('/dashboard/mahasiswa/pengajuan') ?>"
                class="flex items-center gap-3 px-4 py-3 rounded-xl
    <?= $active === 'pengajuan'
        ? 'bg-blue-50 text-blue-600 font-semibold'
        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?>">
                <svg
                    class="w-5 h-5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a3 3 0 006 0M9 5a3 3 0 016 0" />
                </svg>

                <span class="text-sm">
                    Pengajuan Saya
                </span>
            </a>


            <!-- Logbook -->
            <a
                href="<?= url('/dashboard/mahasiswa/logbook') ?>"
                class="flex items-center gap-3 px-4 py-3 rounded-xl
       <?= $active === 'logbook'
        ? 'bg-blue-50 text-blue-600 font-semibold'
        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?>">
                <svg
                    class="w-5 h-5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                </svg>

                <span class="text-sm">
                    Logbook
                </span>
            </a>

        </div>

        <div class="my-6 border-t border-gray-100"></div>

        <p class="px-3 mb-3 text-xs font-semibold tracking-wider text-gray-400 uppercase">
            Lainnya
        </p>

        <!-- Pengaturan -->
        <a
            href="#"
            class="flex items-center gap-3 px-4 py-3 text-gray-600 rounded-xl hover:bg-gray-50">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M10.325 4.317a1.724 1.724 0 013.35 0 1.724 1.724 0 002.573 1.066 1.724 1.724 0 012.366 2.366 1.724 1.724 0 001.066 2.573 1.724 1.724 0 010 3.35 1.724 1.724 0 00-1.066 2.573 1.724 1.724 0 01-2.366 2.366 1.724 1.724 0 00-2.573 1.066 1.724 1.724 0 01-3.35 0 1.724 1.724 0 00-2.573-1.066 1.724 1.724 0 01-2.366-2.366 1.724 1.724 0 00-1.066-2.573 1.724 1.724 0 010-3.35 1.724 1.724 0 001.066-2.573 1.724 1.724 0 012.366-2.366 1.724 1.724 0 002.573-1.066z" />
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>

            <span class="text-sm">
                Pengaturan
            </span>
        </a>

        <!-- Logout -->
        <a
            href="#"
            class="flex items-center gap-3 px-4 py-3 mt-1 text-red-500 rounded-xl hover:bg-red-50">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1" />
            </svg>

            <span class="text-sm">
                Keluar
            </span>
        </a>

    </nav>

</aside>