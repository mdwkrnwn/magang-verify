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
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                    <svg
                        class="h-7 w-7"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M20 7h-4l-1.5-2h-5L8 7H4a2 2 0 00-2 2v9a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z" />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M8 13h8" />
                    </svg>
                </div>

                <p class="mt-4 text-sm font-semibold text-slate-800">
                    Belum ada pengajuan magang
                </p>

                <p class="mt-1 text-sm text-slate-500">
                    Pengajuan magang Anda akan muncul di halaman ini.
                </p>
            </div>

        <?php endif; ?>

    </div>

</div>