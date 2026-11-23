
<?php

http_response_code(403);

// Default message
$errorTitle = $errorTitle ?? 'Akses tidak valid';

$errorMessage = $errorMessage
    ?? 'Link akses yang kamu gunakan tidak valid, sudah kedaluwarsa, atau akses tidak lagi tersedia. Silakan periksa kembali link yang diberikan atau hubungi administrator jika membutuhkan bantuan.';

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title><?= htmlspecialchars($errorTitle, ENT_QUOTES, 'UTF-8') ?> — VerifyMagang</title>

    <meta
        name="description"
        content="<?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>"
    >

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
                    class="w-auto h-14"
                >

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
                    class="flex items-center justify-center w-20 h-20 mx-auto mb-8 border border-orange-100 rounded-2xl bg-orange-50"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-10 h-10 text-orange-600"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M10 14a5 5 0 0 0 7 0l3-3a5 5 0 0 0-7"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M14 10a5 5 0 0 0-7l-3 3a5 5 0 0 0 7"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m9 15 6-6"
                        />
                    </svg>
                </div>


                <!-- Error -->
                <p class="text-sm font-semibold tracking-widest text-orange-600 uppercase">
                    Akses Mitra
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

                    <a
                        href="<?= url('/') ?>"
                        class="inline-flex w-full sm:w-auto items-center justify-center gap-2 px-5 py-3.5 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4"
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
