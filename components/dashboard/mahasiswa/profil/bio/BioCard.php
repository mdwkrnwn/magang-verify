
<?php
$deskripsiProfil = trim($profil['deskripsi'] ?? '');
$keahlianProfil = $profil['keahlian'] ?? [];

if (!is_array($keahlianProfil)) {
    $keahlianProfil = [];
}
?>

<div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6 lg:p-7">
    <div class="mb-6 flex items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-bold text-gray-900">
                Tentang Saya
            </h2>
            <p class="mt-1 text-sm text-gray-400">
                Deskripsi diri dan keahlian yang Anda miliki
            </p>
        </div>

        <button
            type="button"
            data-open-modal="modal-bio"
            class="inline-flex shrink-0 items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-blue-600 transition hover:bg-blue-50"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"
                />
            </svg>
            Edit
        </button>
    </div>

    <!-- Deskripsi profil -->
    <div class="mb-6">
        <h3 class="mb-2 text-sm font-semibold text-gray-800">
            Deskripsi Profil
        </h3>

        <?php if ($deskripsiProfil !== ''): ?>
            <p class="whitespace-pre-line break-words text-sm leading-6 text-gray-600"><?= e($deskripsiProfil) ?></p>
        <?php else: ?>
            <p class="text-sm italic text-gray-400">
                Belum ada deskripsi profil. Tambahkan perkenalan singkat tentang diri Anda.
            </p>
        <?php endif; ?>
    </div>

    <!-- Keahlian -->
    <div>
        <h3 class="mb-3 text-sm font-semibold text-gray-800">
            Keahlian
        </h3>

        <div class="flex flex-wrap gap-2">
            <?php
            $adaKeahlian = false;
            ?>

            <?php foreach ($keahlianProfil as $keahlian): ?>
                <?php $namaKeahlian = trim($keahlian['nama'] ?? ''); ?>

                <?php if ($namaKeahlian !== ''): ?>
                    <?php $adaKeahlian = true; ?>

                    <span class="rounded-lg bg-blue-50 px-3 py-2 text-xs font-medium text-blue-700">
                        <?= e($namaKeahlian) ?>
                    </span>
                <?php endif; ?>
            <?php endforeach; ?>

            <?php if (!$adaKeahlian): ?>
                <p class="text-sm text-gray-400">
                    Belum ada keahlian yang ditambahkan.
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>