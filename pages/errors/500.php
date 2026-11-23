<?php

http_response_code(500);

// Default message
$errorTitle = $errorTitle ?? 'Terjadi Kesalahan Server';

$errorMessage = $errorMessage
    ?? 'Maaf, terjadi kesalahan pada sistem kami. Permintaan Anda belum dapat diproses saat ini. Silakan coba beberapa saat lagi.';

$errorInfo = $errorInfo
    ?? 'Sistem sedang mengalami gangguan';

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($errorTitle, ENT_QUOTES, 'UTF-8') ?> | VerifyMagang</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">

    <div class="flex flex-col min-h-screen">

        <!-- Header -->
        <header class="bg-white border-b border-slate-200">
            <div class="px-6 py-5 mx-auto max-w-7xl">

                <div class="flex items-center gap-3">

                    <img
                        src="<?= url('/assets/images/icon.png') ?>"
                        alt="VerifyMagang Logo"
                        class="object-contain w-10 h-10">

                    <div>
                        <h1 class="text-lg font-bold text-slate-900">
                            VerifyMagang
                        </h1>

                        <p class="text-xs text-slate-500">
                            Portofolio Terverifikasi, Masa Depan Lebih Dekat
                        </p>
                    </div>

                </div>

            </div>
        </header>


        <!-- Main -->
        <main class="flex items-center justify-center flex-1 px-6 py-16">

            <div class="w-full max-w-2xl text-center">

                <!-- Icon -->
                <div class="flex justify-center mb-8">

                    <div class="flex items-center justify-center w-24 h-24 border border-red-100 rounded-3xl bg-red-50">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-12 h-12 text-red-500"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.7">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 9v4m0 4h.01M10.29 3.86
                                1.82 18.14A2 2 0 0 0 3.55
                                21h16.9a2 2 0 0 0 1.73-2.86L13.71
                                3.86a2 2 0 00-3.42 0Z" />
                        </svg>

                    </div>

                </div>


                <!-- Error Code -->
                <p class="mb-3 text-sm font-semibold tracking-widest text-red-500 uppercase">
                    Error 500
                </p>


                <!-- Heading -->
                <h2 class="mb-4 text-3xl font-bold tracking-tight md:text-4xl text-slate-900">
                    <?= htmlspecialchars($errorTitle, ENT_QUOTES, 'UTF-8') ?>
                </h2>


                <!-- Description -->
                <p class="max-w-lg mx-auto mb-8 text-base leading-relaxed text-slate-600">
                    <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
                </p>


                <!-- Action -->
                <div class="flex flex-col justify-center gap-3 sm:flex-row">

                    <!-- Retry -->
                    <button
                        onclick="window.location.reload()"
                        class="inline-flex items-center justify-center px-6 py-3 font-semibold text-white transition rounded-xl bg-slate-900 hover:bg-slate-800">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 mr-2"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M4 4v5h5M20 20v-5h-5
                                M5.5 9a7 7 0 0 1 12-3.5L20 9
                                M18.5 15a7 7 0 0 a1-12 3.5L4 15" />
                        </svg>

                        Coba Lagi

                    </button>


                    <!-- Home -->
                    <a
                        href="<?= url('/') ?>"
                        class="inline-flex items-center justify-center px-6 py-3 font-semibold transition bg-white border rounded-xl border-slate-200 text-slate-700 hover:bg-slate-100">
                        Kembali ke Beranda
                    </a>

                </div>


                <!-- Error Info -->
                <div class="inline-flex items-center gap-2 px-4 py-2 mt-10 text-sm rounded-full bg-slate-100 text-slate-500">

                    <span class="w-2 h-2 bg-red-400 rounded-full"></span>

                    <?= htmlspecialchars($errorInfo, ENT_QUOTES, 'UTF-8') ?>

                </div>

            </div>

        </main>


        <!-- Footer -->
        <footer class="bg-white border-t border-slate-200">

            <div class="px-6 py-5 mx-auto text-sm text-center max-w-7xl text-slate-500">

                &copy; <?= date('Y') ?> VerifyMagang.
                All rights reserved.

            </div>

        </footer>

    </div>

</body>

</html>