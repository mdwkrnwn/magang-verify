<div class="grid grid-cols-1 xl:grid-cols-2 gap-4 sm:gap-6">

    <?php if (empty($tampil)): ?>

        <div class="xl:col-span-2 bg-white border border-slate-200 rounded-xl p-8 text-center">

            <div class="mx-auto flex items-center justify-center w-12 h-12 rounded-full bg-slate-100">

                <svg
                    class="w-6 h-6 text-slate-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 14v.01M12 10v2m0 9a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" />

                </svg>

            </div>

            <p class="mt-4 text-sm font-medium text-gray-700">
                Sertifikat tidak ditemukan.
            </p>

            <p class="mt-1 text-xs text-gray-500">
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