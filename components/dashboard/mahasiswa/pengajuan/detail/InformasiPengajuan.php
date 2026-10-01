<section class="bg-white border border-slate-200 rounded-xl overflow-hidden">

    <div class="px-5 py-4 border-b border-slate-200">

        <h2 class="text-sm font-semibold text-slate-800">
            Informasi Pengajuan
        </h2>

    </div>


    <div class="grid gap-5 p-5 sm:grid-cols-2">

        <div>

            <p class="text-xs text-slate-400">
                Perusahaan
            </p>

            <p class="mt-1 text-sm font-medium text-slate-800">
                <?= e($pengajuanDetail['perusahaan']) ?>
            </p>

        </div>


        <div>

            <p class="text-xs text-slate-400">
                Posisi
            </p>

            <p class="mt-1 text-sm font-medium text-slate-800">
                <?= e($pengajuanDetail['posisi']) ?>
            </p>

        </div>


        <div>

            <p class="text-xs text-slate-400">
                Tanggal Pengajuan
            </p>

            <p class="mt-1 text-sm font-medium text-slate-800">
                <?= e($pengajuanDetail['tanggal']) ?>
            </p>

        </div>


        <div>

            <p class="text-xs text-slate-400">
                Periode Magang
            </p>

            <p class="mt-1 text-sm font-medium text-slate-800">
                <?= e($pengajuanDetail['periode']) ?>
            </p>

        </div>

    </div>

</section>