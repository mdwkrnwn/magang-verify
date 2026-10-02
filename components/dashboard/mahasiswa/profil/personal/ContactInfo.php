
<?php
$noHpProfil = trim($profil['no_hp'] ?? '');
$instagramProfil = trim($profil['instagram'] ?? '');

// Email belum diambil dari database pada model profil saat ini.
$emailProfil = trim($profil['email'] ?? '');
?>

<section>
    <div class="mb-5 flex items-center justify-between gap-3">
        <div>
            <h3 class="text-base font-semibold text-gray-900">
                Kontak
            </h3>
            <p class="mt-1 text-sm text-gray-400">
                Informasi kontak yang dapat dihubungi
            </p>
        </div>

        <button
            type="button"
            data-open-modal="modal-contact"
            class="inline-flex shrink-0 items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-blue-600 transition hover:bg-blue-50"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
            </svg>
            Edit
        </button>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3 xl:gap-6">

        <!-- Email -->
        <div class="min-w-0">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <span class="text-sm text-gray-400">Email</span>
            </div>

            <p class="mt-3 break-all text-sm font-medium leading-6 text-gray-800">
                <?= e($emailProfil !== '' ? $emailProfil : 'Belum tersedia') ?>
            </p>
        </div>

        <!-- Nomor HP -->
        <div class="min-w-0">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-green-50 text-green-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a2 2 0 011.789 1.106l1.063 2.126a2 2 0 01-.363 2.31L9.5 9.81a16.016 16.016 0 006.69 6.69l1.268-1.269a2 2 0 012.31-.363l2.126 1.063A2 2 0 0123 17.72V21a2 2 0 01-2 2h-1C10.163 23 1 13.837 1 3V2a2 2 0 012-2z" />
                    </svg>
                </div>
                <span class="text-sm text-gray-400">No. HP</span>
            </div>

            <p class="mt-3 break-words text-sm font-medium leading-6 text-gray-800">
                <?= e($noHpProfil !== '' ? $noHpProfil : 'Belum diatur') ?>
            </p>
        </div>

        <!-- Instagram -->
        <div class="min-w-0">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-pink-50 text-pink-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="5" stroke-width="2" />
                        <circle cx="12" cy="12" r="4" stroke-width="2" />
                        <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none" />
                    </svg>
                </div>
                <span class="text-sm text-gray-400">Instagram</span>
            </div>

            <p class="mt-3 break-all text-sm font-medium leading-6 text-gray-800">
                <?= e($instagramProfil !== '' ? $instagramProfil : 'Belum diatur') ?>
            </p>
        </div>

    </div>
</section>