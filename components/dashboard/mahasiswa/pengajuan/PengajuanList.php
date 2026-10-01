<div class="overflow-hidden bg-white border border-slate-200 rounded-xl">

    <div
        class="hidden lg:grid
               lg:grid-cols-[minmax(300px,1fr)_170px_170px_150px]
               items-center
               px-5 py-3
               bg-white
               border-b border-slate-200">

        <div class="text-xs font-semibold text-blue-600">
            Perusahaan & Posisi
        </div>

        <div class="text-xs font-semibold text-slate-500">
            Tanggal Pengajuan
        </div>

        <div class="text-xs font-semibold text-slate-500">
            Status
        </div>

        <div class="text-xs font-semibold text-slate-500">
            Aksi
        </div>

    </div>


    <div id="pengajuan-results">

        <?php foreach ($pengajuan as $item): ?>

            <?php
            $logo = $item['logo'];
            $logoClass = $item['logoClass'];
            $perusahaan = $item['perusahaan'];
            $posisi = $item['posisi'];
            $tanggal = $item['tanggal'];
            $status = $item['status'];
            $statusClass = $item['statusClass'];

            $detailUrl =
                '/dashboard/mahasiswa/pengajuan/detail/' .
                $item['slug'];
            ?>

            <div>
                <?php include __DIR__ . '/PengajuanCard.php'; ?>
            </div>

        <?php endforeach; ?>


        <?php if (empty($pengajuan)): ?>

            <div
                id="pengajuan-empty"
                class="px-5 py-12 text-center">

                <p class="text-sm font-medium text-slate-700">
                    Belum ada pengajuan magang
                </p>

                <p class="mt-1 text-xs text-slate-500">
                    Pengajuan magang Anda akan muncul di halaman ini.
                </p>

            </div>

        <?php endif; ?>

    </div>

</div>