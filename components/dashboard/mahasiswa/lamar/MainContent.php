<div>

    <!-- Header -->
    <?php include __DIR__ . "/Header.php"; ?>


    <!-- Main Layout -->
    <div
        class="grid grid-cols-1 gap-5
               lg:grid-cols-[minmax(0,1fr)_320px]
               lg:items-start"
    >

        <!-- Left -->
        <div class="min-w-0 space-y-5">

            <?php include __DIR__ . "/FormasiInfo.php"; ?>

            <?php include __DIR__ . "/DataPengajuan.php"; ?>

            <?php include __DIR__ . "/Dokumen.php"; ?>

            <?php include __DIR__ . "/Pernyataan.php"; ?>

            <?php include __DIR__ . "/Action.php"; ?>

        </div>


        <!-- Right -->
        <aside class="min-w-0">

            <div
                class="lg:sticky lg:top-24"
            >

                <div
                    class="p-4 sm:p-5
                           bg-white
                           border border-slate-200
                           rounded-xl"
                >

                    <h2
                        class="text-base
                               font-semibold
                               text-slate-900"
                    >
                        Tahapan Pengajuan
                    </h2>

                    <p
                        class="mt-1
                               text-xs
                               text-slate-500"
                    >
                        Proses yang akan dilalui
                    </p>


                    <div class="mt-5 space-y-4">

                        <?php

                        $tahapan = [
                            'Pilih Formasi',
                            'Data Pengajuan',
                            'Unggah Dokumen',
                            'Pernyataan',
                            'Kirim Pengajuan',
                            'Verifikasi Koordinator',
                            'Persetujuan KPS',
                            'Persetujuan Kajur',
                            'Persetujuan Wadir 1',
                            'Surat Pengantar',
                            'LOA',
                        ];

                        ?>

                        <?php foreach (
                            $tahapan
                            as $index => $tahap
                        ): ?>

                            <div
                                class="flex items-start gap-3"
                            >

                                <div
                                    class="flex items-center
                                           justify-center
                                           w-7 h-7 shrink-0
                                           rounded-full
                                           <?= $index === 0
                                                ? 'bg-blue-600 text-white'
                                                : 'bg-slate-100 text-slate-500'
                                           ?>
                                           text-xs font-semibold"
                                >
                                    <?= $index + 1 ?>
                                </div>


                                <div
                                    class="min-w-0 pt-1"
                                >

                                    <p
                                        class="text-xs sm:text-sm
                                               <?= $index === 0
                                                    ? 'font-semibold text-blue-600'
                                                    : 'font-medium text-slate-600'
                                               ?>"
                                    >
                                        <?= e($tahap) ?>
                                    </p>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                </div>

            </div>

        </aside>

    </div>

</div>