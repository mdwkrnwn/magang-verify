<div class="bg-white border border-slate-200 rounded-xl p-5 sm:p-6">

    <div class="mb-5">

        <h2 class="text-base font-bold text-gray-900">
            Teknologi dan Keahlian
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Teknologi atau keahlian yang digunakan dalam proyek.
        </p>

    </div>


    <div class="flex flex-wrap gap-2">

        <?php foreach (($portofolioDetail['teknologi'] ?? []) as $teknologi): ?>

            <span
                class="px-3 py-1.5
                       text-xs font-medium
                       rounded-lg
                       bg-blue-50 text-blue-600"
            >
                <?= e($teknologi) ?>
            </span>

        <?php endforeach; ?>

    </div>

</div>