<section class="bg-white border border-slate-200 rounded-xl overflow-hidden">

    <div class="px-5 py-4 border-b border-slate-200">

        <h2 class="text-sm font-semibold text-slate-800">
            Informasi Pekerjaan
        </h2>

    </div>


    <div class="p-5 space-y-5">

        <div>

            <p class="text-xs font-medium text-slate-500">
                Deskripsi Pekerjaan
            </p>

            <p class="mt-2 text-sm leading-6 text-slate-600">
                <?= e($pengajuanDetail['deskripsi']) ?>
            </p>

        </div>


        <div>

            <p class="text-xs font-medium text-slate-500">
                Kesesuaian dengan JPL
            </p>

            <p class="mt-2 text-sm leading-6 text-slate-600">
                <?= e($pengajuanDetail['jpl']) ?>
            </p>

        </div>

    </div>

</section>