<main class="min-h-screen pt-20 ml-64 bg-slate-50">

    <div class="px-8 py-8 mx-auto max-w-[1600px]">

        <!-- Page Header -->
        <div class="mb-8 flex items-end justify-between">

            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">
                    Sertifikat
                </h1>

                <p class="mt-2 text-base text-gray-500">
                    Kelola sertifikat yang Anda miliki.
                </p>
            </div>

            <!-- Tambah Sertifikat -->
            <a
                href="<?= url('/portofolio/tambah') ?>"
                class="inline-flex items-center gap-2 px-5 py-4
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
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">


            <!-- Sertifikat 1 -->
            <div class="bg-white border border-slate-200 rounded-xl p-5
                        flex gap-5 hover:shadow-sm transition">

                <!-- Image -->
                <div class="w-40 h-32 flex-shrink-0 rounded-lg
                            bg-slate-100 overflow-hidden">

                    <img
                        src="<?= url('/assets/images/sertifikat/web-development.jpg') ?>"
                        alt="Web Development Basic"
                        class="w-full h-full object-cover">

                </div>

                <!-- Content -->
                <div class="flex flex-col flex-1 min-w-0">

                    <h2 class="text-lg font-bold text-gray-900">
                        Web Development Basic
                    </h2>

                    <p class="mt-2 text-sm text-gray-500">
                        Dicoding Indonesia
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Diterbitkan: 12 Jan 2025
                    </p>

                    <!-- Button -->
                    <div class="mt-4 flex items-center gap-2">

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
            <div class="bg-white border border-slate-200 rounded-xl p-5
                        flex gap-5 hover:shadow-sm transition">

                <div class="w-40 h-32 flex-shrink-0 rounded-lg
                            bg-slate-100 overflow-hidden">

                    <img
                        src="<?= url('/assets/images/sertifikat/cyber-security.jpg') ?>"
                        alt="Cyber Security Essentials"
                        class="w-full h-full object-cover">

                </div>

                <div class="flex flex-col flex-1 min-w-0">

                    <h2 class="text-lg font-bold text-gray-900">
                        Cyber Security Essentials
                    </h2>

                    <p class="mt-2 text-sm text-gray-500">
                        Cisco Networking Academy
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Diterbitkan: 20 Mar 2025
                    </p>

                    <div class="mt-4 flex items-center gap-2">

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
            <div class="bg-white border border-slate-200 rounded-xl p-5
                        flex gap-5 hover:shadow-sm transition">

                <div class="w-40 h-32 flex-shrink-0 rounded-lg
                            bg-slate-100 overflow-hidden">

                    <img
                        src="<?= url('/assets/images/sertifikat/ui-ux.jpg') ?>"
                        alt="UI/UX Design Fundamental"
                        class="w-full h-full object-cover">

                </div>

                <div class="flex flex-col flex-1 min-w-0">

                    <h2 class="text-lg font-bold text-gray-900">
                        UI/UX Design Fundamental
                    </h2>

                    <p class="mt-2 text-sm text-gray-500">
                        Online Course (Coursera)
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Diterbitkan: 5 Feb 2025
                    </p>

                    <div class="mt-4 flex items-center gap-2">

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
            <div class="bg-white border border-slate-200 rounded-xl p-5
                        flex gap-5 hover:shadow-sm transition">

                <div class="w-40 h-32 flex-shrink-0 rounded-lg
                            bg-slate-100 overflow-hidden">

                    <img
                        src="<?= url('/assets/images/sertifikat/java-programming.jpg') ?>"
                        alt="Java Programming"
                        class="w-full h-full object-cover">
                </div>

                <div class="flex flex-col flex-1 min-w-0">

                    <h2 class="text-lg font-bold text-gray-900">
                        Java Programming
                    </h2>

                    <p class="mt-2 text-sm text-gray-500">
                        Oracle Academy
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Diterbitkan: 25 Apr 2025
                    </p>

                    <div class="mt-4 flex items-center gap-2">

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
    </div>
</main>