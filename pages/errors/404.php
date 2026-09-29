<?php
http_response_code(404);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Halaman Tidak Ditemukan — VerifyMagang</title>

    <meta
        name="description"
        content="Halaman yang Anda cari tidak ditemukan di VerifyMagang."
    >

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">

    <!-- Header -->
    <header class="bg-white border-b border-slate-100">

        <div class="max-w-7xl mx-auto px-6 py-5">

            <a
                href="<?= url('/') ?>"
                class="inline-flex items-center gap-3"
            >

                <img
                    src="<?= url('/assets/images/logo.png') ?>"
                    alt="VerifyMagang"
                    class="h-10 w-auto"
                >

            </a>

        </div>

    </header>


    <!-- Main -->
    <main class="min-h-[calc(100vh-81px)] flex items-center">

        <div class="max-w-7xl w-full mx-auto px-6 py-16">

            <div class="max-w-2xl mx-auto text-center">

                <!-- Icon -->
                <div
                    class="mx-auto mb-8 flex h-20 w-20 items-center justify-center rounded-2xl bg-blue-50 border border-blue-100"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-10 w-10 text-blue-600"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9.75 9.75a3 3 0 1 0 4.5 0M8.25 15.25a5.5 5.5 0 0 1 7.5 0"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M4.75 5.75 6.5 4.5m12.75 1.25L17.5 4.5M12 3.25v-1"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M4.5 8.5A8.5 8.5 0 1 0 19.5 8.5"
                        />
                    </svg>
                </div>


                <!-- 404 -->
                <p class="text-sm font-semibold tracking-widest text-blue-600 uppercase">
                    Error 404
                </p>


                <!-- Title -->
                <h1 class="mt-3 text-4xl sm:text-5xl font-bold tracking-tight text-slate-900">
                    Halaman tidak ditemukan
                </h1>


                <!-- Description -->
                <p class="mt-5 text-base sm:text-lg leading-7 text-slate-500 max-w-xl mx-auto">
                    Maaf, halaman yang kamu cari tidak tersedia atau
                    mungkin sudah dipindahkan. Coba kembali ke halaman
                    utama untuk melanjutkan.
                </p>


                <!-- Actions -->
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">

                    <a
                        href="<?= url('/') ?>"
                        class="inline-flex w-full sm:w-auto items-center justify-center gap-2 px-5 py-3.5 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m10.5 19.5-7-7 7-7"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3.5 12.5h17"
                            />
                        </svg>

                        Kembali ke Beranda
                    </a>


                    <a
                        href="<?= url('/mahasiswa') ?>"
                        class="inline-flex w-full sm:w-auto items-center justify-center gap-2 px-5 py-3.5 bg-white text-slate-700 text-sm font-medium rounded-md border border-slate-200 hover:bg-slate-50 transition"
                    >
                        Lihat Mahasiswa

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5 12h14"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m13 6 6 6-6 6"
                            />
                        </svg>

                    </a>

                </div>


                <!-- Small message -->
                <div class="mt-10 pt-6 border-t border-slate-200">

                    <p class="text-xs text-slate-400">
                        VerifyMagang · Portofolio Terverifikasi, Masa Depan Lebih Dekat
                    </p>

                </div>

            </div>

        </div>

    </main>

</body>

</html>