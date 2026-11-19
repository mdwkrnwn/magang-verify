<div class="grid grid-cols-1 xl:grid-cols-2 gap-4 sm:gap-6">

    <?php if (empty($tampil)): ?>

        <div class="xl:col-span-2 rounded-xl border border-slate-200 bg-white px-6 py-12 text-center">

            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                <svg
                    class="h-7 w-7"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    aria-hidden="true">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M12 14v.01M12 10v2m0 9a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" />
                </svg>
            </div>

            <p class="mt-4 text-sm font-semibold text-gray-800">
                Sertifikat tidak ditemukan.
            </p>

            <p class="mx-auto mt-1 max-w-md text-sm leading-6 text-gray-500">
                Coba ubah kata pencarian atau filter yang digunakan.
            </p>

        </div>

    <?php else: ?>

        <?php foreach ($tampil as $item): ?>

            <?php
            $sertifikatItem = $item;
            ?>

            <?php include __DIR__ . '/SertifikatCard.php'; ?>

        <?php endforeach; ?>

    <?php endif; ?>

</div>