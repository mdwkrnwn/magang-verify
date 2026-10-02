<?php

$nama =
    $sertifikat['nama'] ?? 'Sertifikat';

$status =
    $sertifikat['verifikasi']['status'] ?? '';

$labelStatus =
    $sertifikat['verifikasi']['label'] ?? '';

$slug =
    $sertifikat['slug'] ?? '';

$isVerified =
    $status === 'terverifikasi';

$canModify =
    $status === 'belum_terverifikasi';

$deleteDialogId =
    'hapusSertifikatModal-' . $slug;

?>

<div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

    <div class="min-w-0">

        <a
            href="<?= url('/dashboard/mahasiswa/sertifikat') ?>"
            class="inline-flex items-center gap-2 mb-4 text-sm font-medium text-gray-500 transition hover:text-gray-900">

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
                    d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>

            Kembali ke Sertifikat

        </a>


        <div class="flex flex-col gap-3">

            <h1 class="text-2xl font-bold leading-tight text-gray-900 break-words sm:text-3xl">
                <?= e($nama) ?>
            </h1>


            <?php if ($labelStatus !== ''): ?>

                <div>

                    <span
                        class="inline-flex items-center w-fit max-w-full px-3 py-1.5 text-xs font-medium leading-4 break-words rounded-full sm:text-sm <?= $canModify
                                                                                                                                                            ? 'bg-amber-50 text-amber-700'
                                                                                                                                                            : 'bg-emerald-50 text-emerald-700'
                                                                                                                                                        ?>">

                        <?php if ($canModify): ?>

                            <svg
                                class="w-4 h-4 mr-1.5 shrink-0"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                                aria-hidden="true">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 9v3.75m0 3h.008M10.29 3.86 2.82 17.25A1.5 1.5 0 0 0 4.12 19.5h15.76a1.5 1.5 0 0 0 1.3-2.25L13.71 3.86a1.5 1.5 0 0 0-2.6 0Z" />
                            </svg>

                        <?php else: ?>

                            <svg
                                class="w-4 h-4 mr-1.5 shrink-0"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                                aria-hidden="true">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="m5 12 4 4L19 6" />
                            </svg>

                        <?php endif; ?>

                        <?= e($labelStatus) ?>

                    </span>

                </div>

            <?php endif; ?>

        </div>

    </div>


    <?php if ($canModify): ?>

        <div class="flex flex-wrap gap-2 sm:justify-end">

            <a
                href="<?= url('/dashboard/mahasiswa/sertifikat/edit/' . $slug) ?>"
                class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 transition bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50">

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
                onclick="document.getElementById('<?= e($deleteDialogId) ?>').showModal()"
                class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-red-700 transition bg-white border border-red-200 rounded-lg shadow-sm hover:bg-red-50">

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
                        d="M4.5 7.5h15M9.75 11.25v5.25M14.25 11.25v5.25M6.75 7.5l.75 12h9l.75-12M9 7.5V5.25h6V7.5" />
                </svg>

                Hapus

            </button>

        </div>


        <dialog
            id="<?= e($deleteDialogId) ?>"
            class="w-full max-w-md p-0 m-auto overflow-hidden bg-white rounded-xl shadow-2xl backdrop:bg-black/50">

            <div class="p-5 sm:p-6">

                <div class="flex items-start gap-4">

                    <div class="flex items-center justify-center w-10 h-10 text-red-600 bg-red-50 rounded-full shrink-0">

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
                                d="M4.5 7.5h15M9.75 11.25v5.25M14.25 11.25v5.25M6.75 7.5l.75 12h9l.75-12M9 7.5V5.25h6V7.5" />
                        </svg>

                    </div>


                    <div class="min-w-0">

                        <h2 class="text-lg font-semibold text-gray-900">
                            Hapus Sertifikat?
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-gray-500">
                            Sertifikat
                            <strong class="text-gray-700">
                                <?= e($nama) ?>
                            </strong>
                            akan dihapus dari daftar.
                            Tindakan ini tidak dapat dibatalkan.
                        </p>

                    </div>

                </div>


                <div class="flex flex-col-reverse gap-2 mt-6 sm:flex-row sm:justify-end">

                    <button
                        type="button"
                        onclick="document.getElementById('<?= e($deleteDialogId) ?>').close()"
                        class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-gray-700 transition bg-white border border-gray-200 rounded-lg hover:bg-gray-50">
                        Batal
                    </button>


                    <form
                        action="<?= url('/dashboard/mahasiswa/sertifikat/hapus/' . $slug) ?>"
                        method="POST">
                        <input
                            type="hidden"
                            name="_csrf_token"
                            value="<?= e(csrfToken()) ?>">

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center w-full px-4 py-2.5 text-sm font-medium text-white transition bg-red-600 rounded-lg hover:bg-red-700 sm:w-auto">
                            Ya, Hapus
                        </button>

                    </form>

                </div>

            </div>

        </dialog>

    <?php endif; ?>

</div>