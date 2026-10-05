<?php

$nama = $sertifikat['nama'] ?? 'Sertifikat';

$gambar = trim(
    $sertifikat['gambar'] ?? ''
);

?>

<section class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm sm:p-6">

    <div class="mb-5">

        <h2 class="text-lg font-bold text-gray-900">
            Foto Sertifikat
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Klik foto untuk melihat sertifikat dalam ukuran lebih besar.
        </p>

    </div>


    <?php if ($gambar !== ''): ?>

        <?php
        $gambarUrl = url(
            '/' . ltrim($gambar, '/')
        );
        ?>

        <button
            type="button"
            id="openSertifikatPreview"
            class="group block w-full overflow-hidden text-left border border-gray-200 rounded-xl bg-slate-50 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2"
            aria-label="Lihat foto sertifikat <?= e($nama) ?>">

            <div class="flex items-center justify-center p-3 sm:p-5">

                <img
                    src="<?= e($gambarUrl) ?>"
                    alt="Foto sertifikat <?= e($nama) ?>"
                    class="object-contain w-full h-auto max-h-[420px] transition duration-200 group-hover:scale-[1.01]"
                    loading="lazy">

            </div>

            <div class="flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-gray-600 transition border-t border-gray-200 group-hover:bg-gray-100">

                <svg
                    class="w-4 h-4"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    viewBox="0 0 24 24"
                    aria-hidden="true">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3.75 3.75h4.5m-4.5 0v4.5m0-4.5 5.25 5.25M20.25 20.25h-4.5m4.5 0v-4.5m0 4.5-5.25-5.25M20.25 3.75v4.5m0-4.5h-4.5m4.5 0-5.25 5.25M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15" />
                </svg>

                Klik untuk melihat lebih besar

            </div>

        </button>


        <dialog
            id="sertifikatPreviewModal"
            class="w-full max-w-5xl p-0 m-auto overflow-hidden bg-transparent backdrop:bg-black/70">

            <div class="relative flex flex-col w-full max-h-[92vh] overflow-hidden bg-white rounded-xl shadow-2xl">

                <div class="flex items-center justify-between gap-4 px-4 py-3 border-b border-gray-200 sm:px-5">

                    <div class="min-w-0">

                        <h3 class="text-base font-semibold text-gray-900 truncate sm:text-lg">
                            <?= e($nama) ?>
                        </h3>

                    </div>


                    <button
                        type="button"
                        id="closeSertifikatPreview"
                        class="inline-flex items-center justify-center w-9 h-9 text-gray-500 transition rounded-lg shrink-0 hover:bg-gray-100 hover:text-gray-900"
                        aria-label="Tutup preview sertifikat">

                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m6 6 12 12M18 6 6 18" />
                        </svg>

                    </button>

                </div>


                <div class="flex items-center justify-center p-3 overflow-auto bg-slate-100 sm:p-6">

                    <img
                        src="<?= e($gambarUrl) ?>"
                        alt="Foto sertifikat <?= e($nama) ?>"
                        class="object-contain max-w-full max-h-[calc(92vh-90px)]">

                </div>

            </div>

        </dialog>


        <script>
            (() => {

                const openButton =
                    document.getElementById('openSertifikatPreview');

                const closeButton =
                    document.getElementById('closeSertifikatPreview');

                const modal =
                    document.getElementById('sertifikatPreviewModal');


                if (!openButton || !closeButton || !modal) {
                    return;
                }


                openButton.addEventListener('click', () => {

                    modal.showModal();

                });


                closeButton.addEventListener('click', () => {

                    modal.close();

                });


                modal.addEventListener('click', (event) => {

                    if (event.target === modal) {

                        modal.close();

                    }

                });


                document.addEventListener('keydown', (event) => {

                    if (
                        event.key === 'Escape' &&
                        modal.open
                    ) {

                        modal.close();

                    }

                });

            })();
        </script>


    <?php else: ?>

        <div class="flex flex-col items-center justify-center px-6 py-12 text-center border border-gray-200 border-dashed rounded-xl bg-slate-50">

            <div class="flex items-center justify-center w-12 h-12 mb-4 text-gray-400 bg-white border border-gray-200 rounded-full">

                <svg
                    class="w-6 h-6"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    viewBox="0 0 24 24"
                    aria-hidden="true">
                    <rect
                        x="3.75"
                        y="4.5"
                        width="16.5"
                        height="15"
                        rx="1.5" />
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="m8.25 15 2.25-2.25 2.25 2.25 2.25-3 2.25 3" />
                </svg>

            </div>

            <p class="text-sm font-medium text-gray-700">
                Foto sertifikat belum tersedia
            </p>

            <p class="max-w-md mt-1 text-sm text-gray-400">
                Foto atau scan sertifikat belum ditambahkan pada data sertifikat ini.
            </p>

        </div>

    <?php endif; ?>

</section>