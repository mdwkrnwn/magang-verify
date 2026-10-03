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
        ['nomor' => 1, 'judul' => 'Pilih Formasi', 'deskripsi' => 'Pilih formasi yang sedang dibuka, berasal dari mitra terverifikasi, dan masih memiliki kuota.'],
        ['nomor' => 2, 'judul' => 'Kirim Pengajuan', 'deskripsi' => 'Lengkapi dokumen yang diminta dan kirim pendaftaran melalui sistem.'],
        ['nomor' => 3, 'judul' => 'Persetujuan Dosen', 'deskripsi' => 'Pengajuan menunggu pemeriksaan dan keputusan dosen. Dosen dapat menyetujui, menolak, atau meminta revisi.'],
        ['nomor' => 4, 'judul' => 'Respons Mitra', 'deskripsi' => 'Jika disetujui dosen, pengajuan menunggu respons mitra. Mitra dapat menerima, menolak, atau mengundang mahasiswa ke tahap seleksi.'],
        ['nomor' => 5, 'judul' => 'Hasil dan Tindak Lanjut', 'deskripsi' => 'Status akhir pengajuan ditampilkan pada menu Pengajuan Saya. Tahap administrasi berikutnya mengikuti prosedur magang kampus.'],
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