<main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">

    <div class="px-4 py-6 mx-auto sm:px-6 sm:py-8 lg:px-8 max-w-[1600px]">

        <!-- Page Header -->
        <div class="mb-6 sm:mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">
                    Sertifikat
                </h1>

                <p class="mt-2 text-sm text-gray-500 sm:text-base">
                    Kelola sertifikat yang Anda miliki.
                </p>
            </div>

            <!-- Tambah Sertifikat -->
            <a
                href="<?= url('/portofolio/tambah') ?>"
                class="inline-flex items-center justify-center gap-2 w-full sm:w-auto
                       px-5 py-3 sm:py-4 shrink-0
                       bg-blue-600 text-white text-sm font-semibold
                       rounded-lg hover:bg-blue-700 transition">

                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 4v16m8-8H4" />
                </svg>

                Unggah Sertifikat
            </a>

        </div>


        <!-- Sertifikat Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 sm:gap-6">

            <!-- Sertifikat 1 -->
            <div class="bg-white border border-slate-200 rounded-xl p-4 sm:p-5
                flex flex-col sm:flex-row gap-4 sm:gap-5 hover:shadow-sm transition">

                <!-- Inisial Sertifikat -->
                <div class="w-full h-44 sm:w-40 sm:h-32 flex-shrink-0 rounded-lg
                    bg-blue-50 text-blue-600 text-3xl font-bold
                    flex items-center justify-center">

                    WD

                </div>

                <!-- Content -->
                <div class="flex flex-col flex-1 min-w-0">

                    <h2 class="text-lg font-bold text-gray-900 break-words">
                        Web Development Basic
                    </h2>

                    <p class="mt-2 text-sm text-gray-500">
                        Dicoding Indonesia
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Diterbitkan: 12 Jan 2025
                    </p>

                    <!-- Button -->
                    <div class="mt-4 flex flex-wrap items-center gap-2">

                        <a
                            href="<?= url('/sertifikat/web-development-basic') ?>"
                            class="inline-flex items-center px-3 py-1.5
                           text-sm font-medium text-blue-600
                           border border-blue-200 rounded-lg
                           hover:bg-blue-50 hover:border-blue-300
                           transition">
                            Lihat Sertifikat
                        </a>

                        <a
                            href="<?= url('/sertifikat/download/web-development-basic') ?>"
                            title="Unduh Sertifikat"
                            class="inline-flex items-center justify-center
                           w-9 h-9 text-blue-600
                           border border-blue-200 rounded-lg
                           hover:bg-blue-50 hover:border-blue-300
                           transition">

                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14" />
                            </svg>

                        </a>

                    </div>

                </div>

            </div>


            <!-- Sertifikat 2 -->
            <div class="bg-white border border-slate-200 rounded-xl p-4 sm:p-5
                flex flex-col sm:flex-row gap-4 sm:gap-5 hover:shadow-sm transition">

                <!-- Inisial Sertifikat -->
                <div class="w-full h-44 sm:w-40 sm:h-32 flex-shrink-0 rounded-lg
                    bg-blue-50 text-blue-600 text-3xl font-bold
                    flex items-center justify-center">

                    CS

                </div>

                <!-- Content -->
                <div class="flex flex-col flex-1 min-w-0">

                    <h2 class="text-lg font-bold text-gray-900 break-words">
                        Cyber Security Essentials
                    </h2>

                    <p class="mt-2 text-sm text-gray-500">
                        Cisco Networking Academy
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Diterbitkan: 20 Mar 2025
                    </p>

                    <!-- Button -->
                    <div class="mt-4 flex flex-wrap items-center gap-2">

                        <a
                            href="<?= url('/sertifikat/cyber-security-essentials') ?>"
                            class="inline-flex items-center px-3 py-1.5
                           text-sm font-medium text-blue-600
                           border border-blue-200 rounded-lg
                           hover:bg-blue-50 hover:border-blue-300
                           transition">
                            Lihat Sertifikat
                        </a>

                        <a
                            href="<?= url('/sertifikat/download/cyber-security-essentials') ?>"
                            title="Unduh Sertifikat"
                            class="inline-flex items-center justify-center
                           w-9 h-9 text-blue-600
                           border border-blue-200 rounded-lg
                           hover:bg-blue-50 hover:border-blue-300
                           transition">

                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14" />
                            </svg>

                        </a>

                    </div>

                </div>

            </div>


            <!-- Sertifikat 3 -->
            <div class="bg-white border border-slate-200 rounded-xl p-4 sm:p-5
                flex flex-col sm:flex-row gap-4 sm:gap-5 hover:shadow-sm transition">

                <!-- Inisial Sertifikat -->
                <div class="w-full h-44 sm:w-40 sm:h-32 flex-shrink-0 rounded-lg
                    bg-blue-50 text-blue-600 text-3xl font-bold
                    flex items-center justify-center">

                    UD

                </div>

                <!-- Content -->
                <div class="flex flex-col flex-1 min-w-0">

                    <h2 class="text-lg font-bold text-gray-900 break-words">
                        UI/UX Design Fundamental
                    </h2>

                    <p class="mt-2 text-sm text-gray-500">
                        Online Course (Coursera)
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Diterbitkan: 5 Feb 2025
                    </p>

                    <!-- Button -->
                    <div class="mt-4 flex flex-wrap items-center gap-2">

                        <a
                            href="<?= url('/sertifikat/ui-ux-design-fundamental') ?>"
                            class="inline-flex items-center px-3 py-1.5
                           text-sm font-medium text-blue-600
                           border border-blue-200 rounded-lg
                           hover:bg-blue-50 hover:border-blue-300
                           transition">
                            Lihat Sertifikat
                        </a>

                        <a
                            href="<?= url('/sertifikat/download/ui-ux-design-fundamental') ?>"
                            title="Unduh Sertifikat"
                            class="inline-flex items-center justify-center
                           w-9 h-9 text-blue-600
                           border border-blue-200 rounded-lg
                           hover:bg-blue-50 hover:border-blue-300
                           transition">

                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14" />
                            </svg>

                        </a>

                    </div>

                </div>

            </div>


            <!-- Sertifikat 4 -->
            <div class="bg-white border border-slate-200 rounded-xl p-4 sm:p-5
                flex flex-col sm:flex-row gap-4 sm:gap-5 hover:shadow-sm transition">

                <!-- Inisial Sertifikat -->
                <div class="w-full h-44 sm:w-40 sm:h-32 flex-shrink-0 rounded-lg
                    bg-blue-50 text-blue-600 text-3xl font-bold
                    flex items-center justify-center">

                    JP

                </div>

                <!-- Content -->
                <div class="flex flex-col flex-1 min-w-0">

                    <h2 class="text-lg font-bold text-gray-900 break-words">
                        Java Programming
                    </h2>

                    <p class="mt-2 text-sm text-gray-500">
                        Oracle Academy
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Diterbitkan: 25 Apr 2025
                    </p>

                    <!-- Button -->
                    <div class="mt-4 flex flex-wrap items-center gap-2">

                        <a
                            href="<?= url('/sertifikat/java-programming') ?>"
                            class="inline-flex items-center px-3 py-1.5
                           text-sm font-medium text-blue-600
                           border border-blue-200 rounded-lg
                           hover:bg-blue-50 hover:border-blue-300
                           transition">
                            Lihat Sertifikat
                        </a>

                        <a
                            href="<?= url('/sertifikat/download/java-programming') ?>"
                            title="Unduh Sertifikat"
                            class="inline-flex items-center justify-center
                           w-9 h-9 text-blue-600
                           border border-blue-200 rounded-lg
                           hover:bg-blue-50 hover:border-blue-300
                           transition">

                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14" />
                            </svg>

                        </a>
                    </div>
                </div>
            </div>
        </div>

        <?php include __DIR__ . "/../Footer.php"; ?>

    </div>
</main>