<div class="mb-6">

    <a
        href="<?= url('/dashboard/mahasiswa/pengajuan') ?>"
        class="inline-flex items-center gap-2
               text-sm text-slate-500
               hover:text-blue-600 transition">
        ← Kembali ke Pengajuan Saya
    </a>

</div>


<!-- Header -->
<div class="mb-6 sm:mb-8">

    <div
        class="flex flex-col gap-4
               lg:flex-row lg:items-start lg:justify-between">

        <div>

            <p class="text-sm text-slate-500">
                Detail Pengajuan Magang
            </p>

            <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">
                <?= e($pengajuanDetail['perusahaan']) ?>
            </h1>

            <p class="mt-2 text-sm text-slate-500 sm:text-base">
                <?= e($pengajuanDetail['posisi']) ?>
            </p>

        </div>


        <span
            class="inline-flex w-fit px-4 py-2
                   text-xs font-medium rounded-lg
                   <?= $pengajuanDetail['statusClass'] ?>">
            <?= e($pengajuanDetail['status']) ?>
        </span>

    </div>

</div>


<div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">


    <!-- Informasi -->
    <div class="space-y-6">


        <!-- Informasi Pengajuan -->
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


        <!-- Informasi Pekerjaan -->
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


        <!-- Dokumen -->
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
 * Jika dokumen belum tersedia, jangan tampilkan.
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
           sm:flex-row sm:items-center sm:justify-between">

                        <div>

                            <p class="text-sm font-medium text-slate-800">
                                <?= e($namaTampilan) ?>
                            </p>

                            <p class="mt-1 text-xs text-slate-500">
                                <?= e($namaDokumen) ?>
                            </p>

                        </div>


                        <!--
     * Untuk sementara URL belum tersedia
     * di data dummy.
     *
     * Nanti ketika file sudah benar-benar
     * disimpan/upload, bagian ini bisa diarahkan
     * ke URL file.
     -->

                        <span
                            class="inline-flex items-center justify-center
               h-9 px-4
               text-xs font-medium
               text-slate-500
               bg-slate-50
               border border-slate-200
               rounded-lg">
                            Tersedia
                        </span>

                    </div>

                <?php endforeach; ?>
            </div>

        </section>

    </div>


    <!-- Timeline -->
    <aside>

        <section class="bg-white border border-slate-200 rounded-xl overflow-hidden">

            <div class="px-5 py-4 border-b border-slate-200">

                <h2 class="text-sm font-semibold text-slate-800">
                    Status Pengajuan
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    Perkembangan proses pengajuan Anda.
                </p>

            </div>


            <div class="p-5">

                <div class="space-y-6">

                    <?php foreach ($pengajuanDetail['timeline'] as $index => $timeline): ?>

                        <?php
                        /*
 * Mapping status dari data ke tampilan.
 */

                        $timelineStatus =
                            $timeline['status'] ?? 'waiting';

                        $statusClass = match ($timelineStatus) {

                            'completed' => [
                                'dot' => 'bg-blue-600 border-blue-600',
                                'text' => 'text-slate-800',
                            ],

                            'process' => [
                                'dot' => 'bg-white border-blue-600',
                                'text' => 'text-slate-800',
                            ],

                            'rejected' => [
                                'dot' => 'bg-red-500 border-red-500',
                                'text' => 'text-slate-800',
                            ],

                            default => [
                                'dot' => 'bg-white border-slate-300',
                                'text' => 'text-slate-500',
                            ],
                        };
                        ?>

                        <div class="relative flex gap-3">

                            <?php if (
                                $index <
                                count($pengajuanDetail['timeline']) - 1
                            ): ?>

                                <div
                                    class="absolute left-[7px] top-5
                   w-px h-[calc(100%+8px)]
                   bg-slate-200"></div>

                            <?php endif; ?>


                            <div
                                class="relative z-10
               w-4 h-4 mt-0.5
               rounded-full border-2
               flex-shrink-0
               <?= e($statusClass['dot']) ?>"></div>


                            <div>

                                <p
                                    class="text-sm font-medium
                   <?= e($statusClass['text']) ?>">
                                    <?= e($timeline['title'] ?? '-') ?>
                                </p>


                                <p class="mt-1 text-xs text-slate-400">

                                    <?= !empty($timeline['date'])
                                        ? e($timeline['date'])
                                        : 'Belum diproses'
                                    ?>

                                </p>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            </div>

        </section>

    </aside>

</div>