<div class="space-y-3">

    <?php if (empty($tampil)): ?>

        <div class="p-10 text-center bg-white border border-slate-200 rounded-xl">

            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 rounded-full bg-slate-100">

                <svg
                    class="w-6 h-6 text-slate-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 6.05 6.05a7.5 7.5 0 0 0 10.6 10.6Z" />
                </svg>

            </div>

            <h2 class="text-sm font-semibold text-slate-800">
                Formasi tidak ditemukan
            </h2>

            <p class="mt-1 text-xs text-slate-500">
                Tidak ada formasi yang sesuai dengan pencarian atau filter Anda.
            </p>

        </div>

    <?php else: ?>

        <?php foreach ($tampil as $item): ?>

            <?php
            $logo = initials($item['perusahaan']);

            $logoClass =
                'text-sm font-bold text-blue-600';

            $perusahaan = $item['perusahaan'];
            $posisi = $item['posisi'];
            $lokasi = $item['lokasi'];
            $durasi = $item['durasi'];

            $detailUrl = url(
                '/dashboard/mahasiswa/formasi-magang/detail/'
                . $item['slug']
            );

            $lamarUrl = url(
                '/dashboard/mahasiswa/lamar/'
                . $item['slug']
            );


            $status = $item['status'];
            ?>

            <?php include __DIR__ . "/FormasiCard.php"; ?>

        <?php endforeach; ?>

    <?php endif; ?>

</div>

<?php include __DIR__ . "/Pagination.php"; ?>