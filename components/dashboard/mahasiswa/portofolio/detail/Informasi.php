<div class="bg-white border border-slate-200 rounded-xl p-5 sm:p-6">

    <div class="flex items-center gap-3 mb-5">

        <div
            class="w-10 h-10 shrink-0
                   rounded-lg
                   bg-blue-50 text-blue-600
                   flex items-center justify-center
                   text-sm font-bold"
        >
            <?php
            $parts = preg_split(
                '/\s+/',
                trim($portofolioDetail['judul'] ?? '')
            );

            $inisial = '';

            foreach (array_slice($parts, 0, 2) as $part) {
                $inisial .= strtoupper(
                    mb_substr($part, 0, 1)
                );
            }
            ?>

            <?= e($inisial) ?>

        </div>


        <div class="min-w-0">

            <h2 class="text-base font-bold text-gray-900">
                Informasi Proyek
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Informasi utama portofolio.
            </p>

        </div>

    </div>


    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

        <div>

            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">
                Nama Proyek
            </p>

            <p class="mt-1 text-sm font-semibold text-gray-900 break-words">
                <?= e($portofolioDetail['judul'] ?? '-') ?>
            </p>

        </div>


        <div>

            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">
                Peran
            </p>

            <p class="mt-1 text-sm font-semibold text-gray-900">
                <?= e($portofolioDetail['peran'] ?? '-') ?>
            </p>

        </div>


        <div>

            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">
                Tahun
            </p>

            <p class="mt-1 text-sm font-semibold text-gray-900">
                <?= e($portofolioDetail['tahun'] ?? '-') ?>
            </p>

        </div>

    </div>

</div>