<div
    class="p-4 sm:p-5 lg:p-6
           bg-white border border-slate-200
           rounded-xl"
>

    <!-- Header -->
    <div class="flex items-start gap-3 mb-6">

        <div
            class="flex items-center justify-center
                   w-9 h-9 shrink-0
                   rounded-lg
                   bg-blue-50 text-blue-600"
        >
            <?= icon('layers', 'w-4 h-4') ?>
        </div>


        <div class="min-w-0">

            <h2
                class="text-base sm:text-lg
                       font-semibold
                       text-slate-900"
            >
                Alur Pendaftaran Magang
            </h2>

            <p
                class="mt-0.5
                       text-xs sm:text-sm
                       text-slate-500"
            >
                Tahapan yang akan dilalui setelah memilih formasi
            </p>

        </div>

    </div>


    <?php

    $alur = [
        [
            'nomor' => 1,
            'judul' => 'Pilih Formasi',
            'deskripsi' => 'Mahasiswa memilih formasi magang yang sesuai dengan program studi dan kompetensi.'
        ],
        [
            'nomor' => 2,
            'judul' => 'Ajukan Pendaftaran',
            'deskripsi' => 'Mahasiswa mengajukan pendaftaran pada formasi yang dipilih melalui sistem.'
        ],
        [
            'nomor' => 3,
            'judul' => 'Unggah Dokumen',
            'deskripsi' => 'Mahasiswa melengkapi dokumen persyaratan yang diperlukan untuk proses pengajuan.'
        ],
        [
            'nomor' => 4,
            'judul' => 'Pemeriksaan Koordinator Magang',
            'deskripsi' => 'Pengajuan diperiksa dan diverifikasi oleh Koordinator Magang.'
        ],
        [
            'nomor' => 5,
            'judul' => 'Persetujuan Berjenjang',
            'deskripsi' => 'Pengajuan dilanjutkan melalui tahapan persetujuan KPS, Kajur, dan Wadir 1.'
        ],
        [
            'nomor' => 6,
            'judul' => 'Surat Pengantar Magang',
            'deskripsi' => 'Setelah proses persetujuan selesai, mahasiswa memperoleh Surat Pengantar Magang.'
        ],
        [
            'nomor' => 7,
            'judul' => 'LOA',
            'deskripsi' => 'Setelah proses dengan mitra selesai, dokumen Letter of Acceptance (LOA) dapat diunggah ke sistem.'
        ],
    ];

    ?>


    <!-- Timeline -->
    <div class="relative">

        <!-- Vertical Line -->
        <div
            class="absolute
                   left-3.5
                   top-2
                   bottom-2
                   w-px
                   bg-slate-200"
        ></div>


        <div class="space-y-6">

            <?php foreach ($alur as $item): ?>

                <div
                    class="relative
                           flex items-start
                           gap-3 sm:gap-4"
                >

                    <!-- Number -->
                    <div
                        class="relative z-10
                               flex items-center justify-center
                               w-7 h-7 shrink-0
                               rounded-full
                               bg-blue-600
                               text-xs font-semibold
                               text-white
                               ring-4 ring-white"
                    >
                        <?= e($item['nomor']) ?>
                    </div>


                    <!-- Content -->
                    <div
                        class="min-w-0
                               pt-0.5"
                    >

                        <h3
                            class="text-sm sm:text-base
                                   font-semibold
                                   text-slate-800"
                        >
                            <?= e($item['judul']) ?>
                        </h3>


                        <p
                            class="mt-1
                                   text-xs sm:text-sm
                                   leading-6
                                   text-slate-500
                                   break-words"
                        >
                            <?= e($item['deskripsi']) ?>
                        </p>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

</div>