<div class="grid grid-cols-1 xl:grid-cols-2 gap-4 sm:gap-6">

    <?php if (empty($tampil)): ?>

        <div class="xl:col-span-2 bg-white border border-slate-200 rounded-xl p-8 text-center">

            <p class="text-sm font-medium text-gray-700">
                Portofolio tidak ditemukan.
            </p>

            <p class="mt-1 text-xs text-gray-500">
                Coba ubah kata pencarian atau filter yang digunakan.
            </p>

        </div>

    <?php else: ?>

        <?php foreach ($tampil as $item): ?>

            <?php
            $portofolioItem = $item;
            ?>

            <?php include __DIR__ . '/PortofolioCard.php'; ?>

        <?php endforeach; ?>

    <?php endif; ?>

</div>