<section class="bg-white border border-slate-200 rounded-xl overflow-hidden">

    <div class="px-5 py-4 border-b border-slate-200">

        <h2 class="text-sm font-semibold text-slate-800">
            Dokumen Pengajuan
        </h2>

    </div>


    <div class="divide-y divide-slate-100">

        <?php foreach ($pengajuanDetail['dokumen'] as $jenis => $namaDokumen): ?>

            <?php

            /*
             * Jika dokumen belum tersedia,
             * jangan tampilkan.
             */
            if (empty($namaDokumen)) {
                continue;
            }

            $namaJenis = [
                'proposal' => 'Proposal Pengajuan',
                'faktaIntegritas' => 'Fakta Integritas',
                'suratPengantar' => 'Surat Pengantar Magang',
                'loa' => 'LOA',
            ];

            $namaTampilan =
                $namaJenis[$jenis]
                ?? ucfirst($jenis);

            ?>

            <div
                class="flex flex-col gap-3 p-5
                       sm:flex-row sm:items-center sm:justify-between"
            >

                <div>

                    <p class="text-sm font-medium text-slate-800">
                        <?= e($namaTampilan) ?>
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        <?= e($namaDokumen) ?>
                    </p>

                </div>


                <!--
                 * Untuk sementara URL file belum tersedia
                 * pada data dummy.
                 *
                 * Ketika file sudah benar-benar disimpan,
                 * bagian ini dapat diganti menjadi link download.
                 -->

                <span
                    class="inline-flex items-center justify-center
                           h-9 px-4
                           text-xs font-medium
                           text-slate-500
                           bg-slate-50
                           border border-slate-200
                           rounded-lg"
                >
                    Tersedia
                </span>

            </div>

        <?php endforeach; ?>

    </div>

</section>