
<div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6 lg:p-7">

    <div class="mb-6 flex items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-bold text-gray-900">
                Bio dan Keahlian
            </h2>
            <p class="mt-1 text-sm text-gray-400">
                Ceritakan tentang diri dan kemampuan Anda
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

    <!-- Deskripsi bio -->
    <div class="rounded-xl bg-gray-50 p-4 sm:p-5">
        <p class="break-words whitespace-pre-line text-sm leading-7 text-gray-600 sm:text-base sm:leading-8"><?php
            $bioProfil = trim($profil['bio'] ?? '');
            echo $bioProfil !== ''
                ? e($bioProfil)
                : 'Belum ada bio. Tambahkan deskripsi singkat tentang diri Anda.';
        ?></p>
    </div>

    <!-- Keahlian -->
    <div class="mt-6">
        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
            Keahlian
        </p>

        <div class="mt-3 flex flex-wrap gap-2">
            <?php
            $keahlianProfil = $profil['keahlian'] ?? [];

            if (is_array($keahlianProfil)) {
                $keahlianProfil = array_filter(
                    $keahlianProfil,
                    static fn($item) => is_string($item) && trim($item) !== ''
                );
            } else {
                $keahlianProfil = [];
            }
            ?>

            <?php if (!empty($keahlianProfil)): ?>
                <?php foreach ($keahlianProfil as $keahlian): ?>
                    <span class="rounded-lg bg-blue-50 px-3 py-2 text-xs font-medium text-blue-700">
                        <?= e(trim($keahlian)) ?>
                    </span>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-sm text-gray-400">
                    Belum ada keahlian yang ditambahkan.
                </p>
            <?php endif; ?>
        </div>
    </div>

</div>