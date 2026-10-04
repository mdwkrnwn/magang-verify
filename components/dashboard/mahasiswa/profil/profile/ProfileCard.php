<?php
$namaProfil = trim($profil['nama_lengkap'] ?? '');
$nimProfil = trim($profil['nim'] ?? '');
$programStudiProfil = trim($profil['program_studi'] ?? '');
$angkatanProfil = trim((string) ($profil['angkatan'] ?? ''));
$fotoProfil = trim($profil['foto_path'] ?? '');

$inisialProfil = 'M';

if ($namaProfil !== '') {
    $kataNama = preg_split('/\s+/', $namaProfil);

    $inisialProfil = mb_strtoupper(
        mb_substr($kataNama[0], 0, 1) .
            (count($kataNama) > 1
                ? mb_substr($kataNama[count($kataNama) - 1], 0, 1)
                : '')
    );
}
?>

<div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6 lg:p-7">
    <div class="flex flex-col items-center text-center">
        <div class="relative">
            <div class="flex h-32 w-32 items-center justify-center overflow-hidden rounded-full bg-blue-100 sm:h-36 sm:w-36">

                <?php if ($fotoProfil !== ''): ?>

                    <img
                        src="<?= e(url('/' . ltrim($fotoProfil, '/'))) ?>"
                        alt="Foto profil <?= e($namaProfil !== '' ? $namaProfil : 'mahasiswa') ?>"
                        class="h-full w-full object-cover"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                    <span
                        class="hidden h-full w-full items-center justify-center text-4xl font-bold text-blue-600">
                        <?= e($inisialProfil) ?>
                    </span>

                <?php else: ?>

                    <span class="text-4xl font-bold text-blue-600">
                        <?= e($inisialProfil) ?>
                    </span>

                <?php endif; ?>

            </div>

            <button
                type="button"
                data-open-modal="modal-photo"
                class="absolute bottom-1 right-1 flex h-10 w-10 items-center justify-center rounded-full border-4 border-white bg-blue-600 text-white shadow-sm transition hover:bg-blue-700"
                title="Edit foto profil"
                aria-label="Edit foto profil">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 7h2l2-3h6l2 3h4a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2z" />
                    <circle cx="12" cy="13" r="3" stroke-width="2" />
                </svg>
            </button>
        </div>

        <div class="mt-5 w-full">
            <h2 class="break-words text-xl font-bold text-gray-900 sm:text-2xl">
                <?= e($namaProfil !== '' ? $namaProfil : 'Nama belum diatur') ?>
            </h2>

            <p class="mt-2 text-sm text-gray-500">
                NIM <?= e($nimProfil !== '' ? $nimProfil : '-') ?>
            </p>
        </div>

        <button
            type="button"
            data-open-modal="modal-personal"
            class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
            </svg>
            Edit profil
        </button>
    </div>

    <div class="my-6 border-t border-gray-100"></div>

    <div class="profile-info-card space-y-5 rounded-xl bg-[#EFF7FE] p-5 sm:p-6">
        <div>
            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                Program Studi
            </p>
            <p class="mt-2 break-words text-sm font-semibold leading-6 text-gray-800">
                <?= e($programStudiProfil !== '' ? $programStudiProfil : 'Belum diatur') ?>
            </p>
        </div>

        <div>
            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                Angkatan
            </p>
            <p class="mt-2 break-words text-sm font-semibold leading-6 text-gray-800">
                <?= e($angkatanProfil !== '' ? $angkatanProfil : 'Belum diatur') ?>
            </p>
        </div>
    </div>
</div>