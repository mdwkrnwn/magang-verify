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
                        d="M4 5.5A2.5 2.5 0 016.5 3h11A2.5 2.5 0 0120 5.5v13a2.5 2.5 0 01-2.5 2.5h-11A2.5 2.5 0 014 18.5v-13z" />
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M8 8h8M8 12h8M8 16h5" />
                </svg>
            </div>

            <p class="mt-4 text-sm font-semibold text-gray-800">
                Portofolio tidak ditemukan.
            </p>

            <p class="mx-auto mt-1 max-w-md text-sm leading-6 text-gray-500">
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