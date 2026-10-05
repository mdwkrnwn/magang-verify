<div class="bg-white border border-slate-200 rounded-xl p-5 sm:p-6">

    <div class="mb-5">

        <h2 class="text-base font-bold text-gray-900">
            Deskripsi Proyek
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Penjelasan mengenai proyek yang dikerjakan.
        </p>

    </div>


    <div class="text-sm leading-7 text-gray-600">

        <?= nl2br(
            e($portofolioDetail['deskripsi'] ?? '-')
        ) ?>

    </div>

</div>