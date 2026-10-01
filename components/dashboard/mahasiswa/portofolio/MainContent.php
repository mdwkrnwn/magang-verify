<main class="min-h-screen pt-20 ml-64 bg-slate-50">

    <div class="px-8 py-8 mx-auto max-w-[1600px]">

        <!-- Page Header -->
        <div class="mb-8 flex items-end justify-between">

            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">
                    Portofolio
                </h1>

                <p class="mt-2 text-base text-gray-500">
                    Tampilkan karya dan project yang pernah Anda kerjakan.
                </p>
            </div>

            <!-- Tambah Portofolio -->
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

                Tambah Portofolio
            </a>

        </div>


        <!-- Portfolio Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            <!-- Portfolio 1 -->
            <div class="bg-white border border-slate-200 rounded-xl p-5
                        flex gap-5 hover:shadow-sm transition">

                <!-- Image -->
                <div class="w-40 h-32 flex-shrink-0 rounded-lg
                            bg-slate-100 overflow-hidden">

                    <img
                        src="<?= url('/assets/images/portfolio/ecommerce.jpg') ?>"
                        alt="Website E-Commerce Sederhana"
                        class="w-full h-full object-cover">

                </div>

                <!-- Content -->
                <div class="flex flex-col flex-1 min-w-0">

                    <h2 class="text-lg font-bold text-gray-900">
                        Website E-Commerce Sederhana
                    </h2>

                    <p class="mt-2 text-sm leading-relaxed text-gray-500">
                        Aplikasi web e-commerce sederhana untuk penjualan
                        produk fashion dengan fitur login, keranjang, dan pembayaran.
                    </p>

                    <!-- Teknologi -->
                    <div class="mt-3 flex flex-wrap gap-2">
                        <span class="px-2.5 py-1 text-xs font-medium
                                     rounded-md bg-blue-50 text-blue-600">
                            React
                        </span>

                        <span class="px-2.5 py-1 text-xs font-medium
                                     rounded-md bg-blue-50 text-blue-600">
                            Node.js
                        </span>

                        <span class="px-2.5 py-1 text-xs font-medium
                                     rounded-md bg-blue-50 text-blue-600">
                            MySQL
                        </span>
                    </div>

                    <!-- Button -->
                    <div class="mt-auto pt-4 flex justify-end">
                        <a
                            href="<?= url('/portofolio/detail') ?>"
                            class="inline-flex items-center gap-1 px-3 py-1.5
                            text-sm font-medium text-blue-600
                            border border-blue-200 rounded-lg
                            hover:bg-blue-50 hover:border-blue-300
                            transition">
                            Lihat Detail
                        </a>
                    </div>

                </div>

            </div>


            <!-- Portfolio 2 -->
            <div class="bg-white border border-slate-200 rounded-xl p-5
                        flex gap-5 hover:shadow-sm transition">

                <div class="w-40 h-32 flex-shrink-0 rounded-lg
                            bg-slate-100 overflow-hidden">

                    <img
                        src="<?= url('/assets/images/portfolio/ddos.jpg') ?>"
                        alt="Sistem Deteksi Serangan DDoS"
                        class="w-full h-full object-cover">

                </div>

                <div class="flex flex-col flex-1 min-w-0">

                    <h2 class="text-lg font-bold text-gray-900">
                        Sistem Deteksi Serangan DDoS
                    </h2>

                    <p class="mt-2 text-sm leading-relaxed text-gray-500">
                        Implementasi sistem sederhana untuk mendeteksi
                        serangan DDoS menggunakan metode threshold.
                    </p>

                    <div class="mt-3 flex flex-wrap gap-2">
                        <span class="px-2.5 py-1 text-xs font-medium
                                     rounded-md bg-blue-50 text-blue-600">
                            Python
                        </span>

                        <span class="px-2.5 py-1 text-xs font-medium
                                     rounded-md bg-blue-50 text-blue-600">
                            Scapy
                        </span>

                        <span class="px-2.5 py-1 text-xs font-medium
                                     rounded-md bg-blue-50 text-blue-600">
                            Linux
                        </span>
                    </div>

                    <div class="mt-auto pt-4 flex justify-end">
                        <a
                            href="<?= url('/portofolio/detail') ?>"
                            class="inline-flex items-center gap-1 px-3 py-1.5
                            text-sm font-medium text-blue-600
                            border border-blue-200 rounded-lg
                            hover:bg-blue-50 hover:border-blue-300
                            transition">
                            Lihat Detail
                        </a>
                    </div>

                </div>

            </div>


            <!-- Portfolio 3 -->
            <div class="bg-white border border-slate-200 rounded-xl p-5
                        flex gap-5 hover:shadow-sm transition">

                <div class="w-40 h-32 flex-shrink-0 rounded-lg
                            bg-slate-100 overflow-hidden">

                    <img
                        src="<?= url('/assets/images/portfolio/catatan.jpg') ?>"
                        alt="Aplikasi Catatan Harian"
                        class="w-full h-full object-cover">

                </div>

                <div class="flex flex-col flex-1 min-w-0">

                    <h2 class="text-lg font-bold text-gray-900">
                        Aplikasi Catatan Harian
                    </h2>

                    <p class="mt-2 text-sm leading-relaxed text-gray-500">
                        Aplikasi mobile untuk mencatat kegiatan harian
                        dengan fitur pengingat dan kategori.
                    </p>

                    <div class="mt-3 flex flex-wrap gap-2">
                        <span class="px-2.5 py-1 text-xs font-medium
                                     rounded-md bg-blue-50 text-blue-600">
                            Flutter
                        </span>

                        <span class="px-2.5 py-1 text-xs font-medium
                                     rounded-md bg-blue-50 text-blue-600">
                            Firebase
                        </span>
                    </div>

                    <div class="mt-auto pt-4 flex justify-end">
                        <a
                            href="<?= url('/portofolio/detail') ?>"
                            class="inline-flex items-center gap-1 px-3 py-1.5
                            text-sm font-medium text-blue-600
                            border border-blue-200 rounded-lg
                            hover:bg-blue-50 hover:border-blue-300
                            transition">
                            Lihat Detail
                        </a>
                    </div>

                </div>

            </div>


            <!-- Portfolio 4 -->
            <div class="bg-white border border-slate-200 rounded-xl p-5
                        flex gap-5 hover:shadow-sm transition">

                <div class="w-40 h-32 flex-shrink-0 rounded-lg
                            bg-slate-100 overflow-hidden">

                    <img
                        src="<?= url('/assets/images/portfolio/dashboard.jpg') ?>"
                        alt="Dashboard Analisis Data"
                        class="w-full h-full object-cover">

                </div>

                <div class="flex flex-col flex-1 min-w-0">

                    <h2 class="text-lg font-bold text-gray-900">
                        Dashboard Analisis Data
                    </h2>

                    <p class="mt-2 text-sm leading-relaxed text-gray-500">
                        Dashboard untuk visualisasi data penjualan
                        menggunakan library Chart.js.
                    </p>

                    <div class="mt-3 flex flex-wrap gap-2">
                        <span class="px-2.5 py-1 text-xs font-medium
                                     rounded-md bg-blue-50 text-blue-600">
                            JavaScript
                        </span>

                        <span class="px-2.5 py-1 text-xs font-medium
                                     rounded-md bg-blue-50 text-blue-600">
                            Chart.js
                        </span>
                    </div>

                    <div class="mt-auto pt-4 flex justify-end">
                        <a
                            href="<?= url('/portofolio/detail') ?>"
                            class="inline-flex items-center gap-1 px-3 py-1.5
                            text-sm font-medium text-blue-600
                            border border-blue-200 rounded-lg
                            hover:bg-blue-50 hover:border-blue-300
                            transition">
                            Lihat Detail
                        </a>
                    </div>

                </div>

            </div>

        </div>

    </div>

</main>