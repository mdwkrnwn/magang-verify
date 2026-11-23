<?php

http_response_code(401);

// Default message
$errorTitle = $errorTitle ?? 'Sesi telah berakhir';

$errorMessage = $errorMessage
    ?? 'Sesi kamu telah berakhir demi keamanan akun. Silakan login kembali untuk melanjutkan aktivitas di VerifyMagang.';

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($errorTitle, ENT_QUOTES, 'UTF-8') ?> — VerifyMagang</title>

    <meta
        name="description"
        content="<?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>">

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">

    <!-- Header -->
    <header class="bg-white border-b border-slate-100">

        <div class="px-6 py-5 mx-auto max-w-7xl">

            <a href="<?= url('/') ?>" class="flex items-center">

                <img
                    src="<?= url('/assets/images/icon.png') ?>"
                    alt="VerifyMagang"
                    class="w-auto h-14">

                <div class="flex flex-col ml-3">
                    <h2 class="text-lg font-bold leading-tight">
                        VerifyMagang
                    </h2>

                    <p class="mt-1 text-xs text-gray-500">
                        Portofolio Terverifikasi, Masa Depan Lebih Dekat
                    </p>
                </div>

            </a>

        </div>

    </header>


    <!-- Main -->
    <main class="min-h-[calc(100vh-81px)] flex items-center">

        <div class="w-full px-6 py-16 mx-auto max-w-7xl">

            <div class="max-w-2xl mx-auto text-center">

                <!-- Icon -->
                <div
                    class="flex items-center justify-center w-20 h-20 mx-auto mb-8 border border-blue-100 rounded-2xl bg-blue-50">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-10 h-10 text-blue-600"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 8v4l2.5 1.5" />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M21 12a9 9 0 1 1-2.64-6.36" />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M21 3v6h-6" />
                    </svg>
                </div>


                <!-- Status -->
                <p class="text-sm font-semibold tracking-widest text-blue-600 uppercase">
                    Session Expired
                </p>


                <!-- Title -->
                <h1 class="mt-3 text-4xl font-bold tracking-tight sm:text-5xl text-slate-900">
                    <?= htmlspecialchars($errorTitle, ENT_QUOTES, 'UTF-8') ?>
                </h1>


                <!-- Description -->
                <p class="max-w-xl mx-auto mt-5 text-base leading-7 sm:text-lg text-slate-500">
                    <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
                </p>


                <!-- Actions -->
                <div class="flex flex-col items-center justify-center gap-3 mt-8 sm:flex-row">

                    <!-- Login -->
                    <a
                        href="<?= url('/login') ?>"
                        class="inline-flex w-full sm:w-auto items-center justify-center gap-2 px-5 py-3.5 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 3h6v6" />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M21 3 12 12" />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5 7v12h12v-5" />
                        </svg>

                        Login Kembali
                    </a>


                    <!-- Home -->
                    <a
                        href="<?= url('/') ?>"
                        class="inline-flex w-full sm:w-auto items-center justify-center gap-2 px-5 py-3.5 bg-white text-slate-700 text-sm font-medium rounded-md border border-slate-200 hover:bg-slate-50 transition">
                        Kembali ke Beranda
                    </a>

                </div>


                <!-- Footer Message -->
                <div class="pt-6 mt-10 border-t border-slate-200">

                    <p class="text-xs text-slate-400">
                        VerifyMagang · Portofolio Terverifikasi, Masa Depan Lebih Dekat
                    </p>

                </div>

            </div>

        </div>

    </main>

</body>

</html>