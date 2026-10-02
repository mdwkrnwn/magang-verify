<?php

$sertifikatId =
    (int) ($sertifikatItem['id'] ?? 0);

$slug =
    $sertifikatItem['slug'] ?? '';

$nama =
    $sertifikatItem['nama'] ?? '';

$penerbit =
    $sertifikatItem['penerbit'] ?? '';

$tanggalTerbit =
    $sertifikatItem['tanggal_terbit'] ?? '';

$nomorSertifikat =
    $sertifikatItem['nomor_sertifikat'] ?? '';

$gambar =
    trim(
        (string) ($sertifikatItem['gambar'] ?? '')
    );

$status =
    $sertifikatItem['verifikasi']['status'] ?? '';

$statusLabel =
    $sertifikatItem['verifikasi']['label'] ?? '';

$inisial =
    initials($nama);


/*
|--------------------------------------------------------------------------
| Status
|--------------------------------------------------------------------------
*/

$canModify =
    $status === 'belum_terverifikasi';

/*
|--------------------------------------------------------------------------
| Format Tanggal
|--------------------------------------------------------------------------
*/

if ($tanggalTerbit !== '') {

    $timestamp =
        strtotime(
            $tanggalTerbit
        );

    if ($timestamp !== false) {

        $tanggalTerbit =
            date(
                'd M Y',
                $timestamp
            );
    }
}


/*
|--------------------------------------------------------------------------
| ID Modal
|--------------------------------------------------------------------------
*/

$deleteDialogId =
    'hapusSertifikatModal-' .
    $sertifikatId;

$menuId =
    'sertifikatMenu-' .
    $sertifikatId;

?>

<div
    class="bg-white border border-slate-200 rounded-xl p-4 sm:p-5
           flex flex-col sm:flex-row gap-4 sm:gap-5
           hover:shadow-sm transition">


    <!-- Inisial / Preview -->

    <div
        class="w-full h-44
               sm:w-40 sm:h-32
               flex-shrink-0
               rounded-lg
               bg-blue-50 text-blue-600
               text-3xl font-bold
               flex items-center justify-center">

        <?= e($inisial) ?>

    </div>


    <!-- Content -->

    <div class="flex flex-col flex-1 min-w-0">


        <!-- Header -->

        <div
            class="relative flex flex-col gap-3
                   sm:flex-row sm:items-start sm:justify-between">

            <div class="min-w-0 flex-1 pr-1">

                <div class="flex flex-col gap-2">

                    <h2
                        class="text-lg font-bold text-gray-900 break-words">
                        <?= e($nama) ?>
                    </h2>


                    <?php if ($status !== ''): ?>

                        <?php if ($status === 'terverifikasi'): ?>

                            <span
                                class="inline-flex w-fit max-w-full shrink-0
                                       items-center
                                       px-2.5 py-1
                                       rounded-full
                                       text-[11px] sm:text-xs
                                       leading-4
                                       font-medium
                                       whitespace-normal
                                       break-words
                                       bg-emerald-50
                                       text-emerald-700">

                                <svg
                                    class="w-3.5 h-3.5 mr-1.5 shrink-0"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    aria-hidden="true">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="m5 12 4 4L19 6" />

                                </svg>

                                <?= e(
                                    $statusLabel
                                        ?: 'Terverifikasi'
                                ) ?>

                            </span>

                        <?php else: ?>

                            <span
                                class="inline-flex w-fit max-w-full shrink-0
                                       items-center
                                       px-2.5 py-1
                                       rounded-full
                                       text-[11px] sm:text-xs
                                       leading-4
                                       font-medium
                                       whitespace-normal
                                       break-words
                                       bg-amber-50
                                       text-amber-700">

                                <svg
                                    class="w-3.5 h-3.5 mr-1.5 shrink-0"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    aria-hidden="true">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 9v3.75m0 3h.008M10.29 3.86 2.82 17.25A1.5 1.5 0 0 0 4.12 19.5h15.76a1.5 1.5 0 0 0 1.3-2.25L13.71 3.86a1.5 1.5 0 0 0-2.6 0Z" />

                                </svg>

                                <?= e(
                                    $statusLabel
                                        ?: 'Belum Terverifikasi'
                                ) ?>

                            </span>

                        <?php endif; ?>

                    <?php endif; ?>

                </div>

            </div>


            <!-- Menu Edit / Hapus -->

            <?php if ($canModify): ?>

                <div class="relative shrink-0">

                    <button
                        type="button"
                        id="<?= e($menuId) ?>Button"
                        class="inline-flex items-center justify-center
                               w-9 h-9
                               text-gray-500
                               border border-transparent
                               rounded-lg
                               hover:bg-gray-100
                               hover:text-gray-700
                               transition"
                        aria-label="Menu sertifikat"
                        aria-haspopup="true"
                        aria-expanded="false"
                        onclick="toggleSertifikatMenu('<?= e($menuId) ?>')">

                        <svg
                            class="w-5 h-5"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            aria-hidden="true">

                            <path
                                d="M10 6a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3ZM10 11.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3ZM10 17a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />

                        </svg>

                    </button>


                    <!-- Dropdown -->

                    <div
                        id="<?= e($menuId) ?>"
                        class="hidden absolute right-0 z-20 mt-2
                               w-40
                               overflow-hidden
                               bg-white
                               border border-gray-200
                               rounded-lg
                               shadow-lg">

                        <a
                            href="<?= url(
                                        '/dashboard/mahasiswa/sertifikat/edit/' .
                                            $slug
                                    ) ?>"
                            class="flex items-center gap-2
                                   px-3 py-2.5
                                   text-sm text-gray-700
                                   hover:bg-gray-50
                                   transition">

                            <svg
                                class="w-4 h-4 text-gray-500"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                viewBox="0 0 24 24"
                                aria-hidden="true">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.5 16.152 6 17.5l1.348-4.5L16.862 4.487Z" />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M18.75 12.75V19.5A1.5 1.5 0 0 1 17.25 21h-12A1.5 1.5 0 0 1 3.75 19.5v-12A1.5 1.5 0 0 1 5.25 6h6.75" />

                            </svg>

                            Edit

                        </a>


                        <button
                            type="button"
                            onclick="openHapusSertifikat('<?= e($deleteDialogId) ?>', '<?= e($menuId) ?>')"
                            class="flex items-center w-full gap-2
                                   px-3 py-2.5
                                   text-sm text-left text-red-600
                                   hover:bg-red-50
                                   transition">

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
                                    d="M4.5 7.5h15" />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 7.5V5.25h6V7.5" />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="m6.75 7.5.75 12h9l.75-12" />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9.75 11.25v5.25M14.25 11.25v5.25" />

                            </svg>

                            Hapus

                        </button>

                    </div>

                </div>

            <?php endif; ?>

        </div>


        <!-- Publisher -->

        <p class="mt-2 text-sm text-gray-500">

            <?= e($penerbit) ?>

        </p>


        <!-- Date -->

        <p class="mt-1 text-sm text-gray-500">

            Diterbitkan:

            <?= e($tanggalTerbit) ?>

        </p>


        <!-- Certificate Number -->

        <?php if ($nomorSertifikat !== ''): ?>

            <p class="mt-1 text-xs text-gray-400 break-all">

                No. Sertifikat:

                <?= e($nomorSertifikat) ?>

            </p>

        <?php endif; ?>


        <!-- Actions -->

        <div
            class="mt-4 flex flex-wrap items-center gap-2">

            <a
                href="<?= url(
                            '/dashboard/mahasiswa/sertifikat/detail/' .
                                $slug
                        ) ?>"
                class="inline-flex items-center
                       px-3 py-1.5
                       text-sm font-medium
                       text-blue-600
                       border border-blue-200
                       rounded-lg
                       hover:bg-blue-50
                       hover:border-blue-300
                       transition">

                Lihat Sertifikat

            </a>


            <?php if ($gambar !== ''): ?>

                <a
                    href="<?= url(
                                '/dashboard/mahasiswa/sertifikat/download/' .
                                    $slug
                            ) ?>"
                    title="Unduh Sertifikat PDF"
                    class="inline-flex items-center justify-center
           w-9 h-9
           text-blue-600
           border border-blue-200
           rounded-lg
           hover:bg-blue-50
           hover:border-blue-300
           transition">

                    <svg
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        aria-hidden="true">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14" />

                    </svg>

                </a>

            <?php endif; ?>

        </div>

    </div>

</div>


<?php if ($canModify): ?>

    <!-- Delete Modal -->

    <dialog
        id="<?= e($deleteDialogId) ?>"
        class="w-full max-w-md p-0 m-auto
               overflow-hidden
               bg-white
               rounded-xl
               shadow-2xl
               backdrop:bg-black/50">

        <div class="p-5 sm:p-6">

            <div class="flex items-start gap-4">

                <div
                    class="flex items-center justify-center
                           w-10 h-10
                           text-red-600
                           bg-red-50
                           rounded-full
                           shrink-0">

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
                            d="M4.5 7.5h15" />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 7.5V5.25h6V7.5" />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m6.75 7.5.75 12h9l.75-12" />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9.75 11.25v5.25M14.25 11.25v5.25" />

                    </svg>

                </div>


                <div class="min-w-0">

                    <h2 class="text-lg font-semibold text-gray-900">
                        Hapus Sertifikat?
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-gray-500">

                        Sertifikat

                        <strong class="text-gray-700 break-words">
                            <?= e($nama) ?>
                        </strong>

                        akan dihapus dari daftar.

                        Tindakan ini tidak dapat dibatalkan.

                    </p>

                </div>

            </div>


            <div
                class="flex flex-col-reverse gap-2 mt-6
                       sm:flex-row sm:justify-end">

                <button
                    type="button"
                    onclick="document.getElementById('<?= e($deleteDialogId) ?>').close()"
                    class="inline-flex items-center justify-center
                           px-4 py-2.5
                           text-sm font-medium
                           text-gray-700
                           transition
                           bg-white
                           border border-gray-200
                           rounded-lg
                           hover:bg-gray-50">
                    Batal
                </button>


                <form
                    action="<?= url(
                                '/dashboard/mahasiswa/sertifikat/hapus/' .
                                    $slug
                            ) ?>"
                    method="POST">
                    <input
                        type="hidden"
                        name="_csrf_token"
                        value="<?= e(csrfToken()) ?>">

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center
                               w-full
                               px-4 py-2.5
                               text-sm font-medium
                               text-white
                               transition
                               bg-red-600
                               rounded-lg
                               hover:bg-red-700
                               sm:w-auto">
                        Ya, Hapus
                    </button>

                </form>

            </div>

        </div>

    </dialog>

<?php endif; ?>


<script>
    function toggleSertifikatMenu(menuId) {

        const menu =
            document.getElementById(menuId);

        const button =
            document.getElementById(
                menuId + 'Button'
            );


        if (!menu || !button) {
            return;
        }


        const isHidden =
            menu.classList.contains('hidden');


        document
            .querySelectorAll(
                '[id^="sertifikatMenu-"]:not([id$="Button"])'
            )
            .forEach((element) => {

                element.classList.add('hidden');

            });


        document
            .querySelectorAll(
                '[id$="Button"][id^="sertifikatMenu-"]'
            )
            .forEach((element) => {

                element.setAttribute(
                    'aria-expanded',
                    'false'
                );

            });


        if (isHidden) {

            menu.classList.remove('hidden');

            button.setAttribute(
                'aria-expanded',
                'true'
            );

        }

    }


    function openHapusSertifikat(
        dialogId,
        menuId
    ) {

        const dialog =
            document.getElementById(
                dialogId
            );

        const menu =
            document.getElementById(
                menuId
            );

        const button =
            document.getElementById(
                menuId + 'Button'
            );


        if (menu) {
            menu.classList.add('hidden');
        }


        if (button) {

            button.setAttribute(
                'aria-expanded',
                'false'
            );
        }


        if (dialog) {
            dialog.showModal();
        }

    }


    document.addEventListener(
        'click',
        function(event) {

            const clickedMenuButton =
                event.target.closest(
                    '[id$="Button"][id^="sertifikatMenu-"]'
                );


            const clickedInsideMenu =
                event.target.closest(
                    '[id^="sertifikatMenu-"]:not([id$="Button"])'
                );


            if (
                !clickedMenuButton &&
                !clickedInsideMenu
            ) {

                document
                    .querySelectorAll(
                        '[id^="sertifikatMenu-"]:not([id$="Button"])'
                    )
                    .forEach((element) => {

                        element.classList.add(
                            'hidden'
                        );

                    });


                document
                    .querySelectorAll(
                        '[id$="Button"][id^="sertifikatMenu-"]'
                    )
                    .forEach((element) => {

                        element.setAttribute(
                            'aria-expanded',
                            'false'
                        );

                    });

            }

        }
    );


    document.addEventListener(
        'keydown',
        function(event) {

            if (event.key !== 'Escape') {
                return;
            }


            document
                .querySelectorAll(
                    '[id^="sertifikatMenu-"]:not([id$="Button"])'
                )
                .forEach((element) => {

                    element.classList.add(
                        'hidden'
                    );

                });


            document
                .querySelectorAll(
                    '[id$="Button"][id^="sertifikatMenu-"]'
                )
                .forEach((element) => {

                    element.setAttribute(
                        'aria-expanded',
                        'false'
                    );

                });

        }
    );
</script>