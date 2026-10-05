
<?php
$namaLengkap = trim($profil['nama_lengkap'] ?? '');
$nim = trim($profil['nim'] ?? '');
$programStudi = trim($profil['program_studi'] ?? '');
$angkatan = trim((string) ($profil['angkatan'] ?? ''));
$alamat = trim($profil['alamat'] ?? '');
?>

<section>
    <div class="mb-6 flex items-center justify-between gap-3">
        <div>
            <h3 class="text-base font-semibold text-gray-900">
                Informasi Pribadi
            </h3>
            <p class="mt-1 text-sm text-gray-400">
                Informasi dasar mahasiswa
            </p>
        </div>

        <button
            type="button"
            data-open-modal="modal-personal"
            class="inline-flex shrink-0 items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-blue-600 transition hover:bg-blue-50"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
            </svg>
            Edit
        </button>
    </div>

    <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
        <div class="min-w-0">
            <p class="text-sm text-gray-400">Nama Lengkap</p>
            <p class="mt-2 break-words text-base font-medium text-gray-900">
                <?= e($namaLengkap !== '' ? $namaLengkap : 'Belum diatur') ?>
            </p>
        </div>

        <div class="min-w-0">
            <p class="text-sm text-gray-400">NIM</p>
            <p class="mt-2 break-words text-base font-medium text-gray-900">
                <?= e($nim !== '' ? $nim : '-') ?>
            </p>
        </div>

        <div class="min-w-0">
            <p class="text-sm text-gray-400">Program Studi</p>
            <p class="mt-2 break-words text-base font-medium text-gray-900">
                <?= e($programStudi !== '' ? $programStudi : 'Belum diatur') ?>
            </p>
        </div>

        <div class="min-w-0">
            <p class="text-sm text-gray-400">Angkatan</p>
            <p class="mt-2 break-words text-base font-medium text-gray-900">
                <?= e($angkatan !== '' ? $angkatan : 'Belum diatur') ?>
            </p>
        </div>

        <div class="min-w-0 sm:col-span-2">
            <p class="text-sm text-gray-400">Alamat</p>
            <p class="mt-2 break-words whitespace-pre-line text-base font-medium leading-relaxed text-gray-900">
                <?= e($alamat !== '' ? $alamat : 'Belum diatur') ?>
            </p>
        </div>
    </div>
</section>