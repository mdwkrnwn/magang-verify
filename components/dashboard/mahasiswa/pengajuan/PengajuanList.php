<?php

$pengajuan = [

    [
        'logo' => 'semarsoft',
        'logoClass' => 'text-xs font-bold text-slate-500',
        'perusahaan' => 'PT. Semarsoft Technology Indonesia',
        'posisi' => 'Frontend Developer Intern',
        'tanggal' => '12 Mei 2025',
        'status' => 'Dalam Proses',
        'statusClass' => 'text-blue-600 bg-blue-50',
        'detailUrl' => '/pengajuan/detail/semarsoft'
    ],

    [
        'logo' => 'NDS',
        'logoClass' => 'text-lg font-bold text-blue-600',
        'perusahaan' => 'PT. Nusantara Digital Solusi',
        'posisi' => 'Backend Developer Intern',
        'tanggal' => '5 Mei 2025',
        'status' => 'Diterima',
        'statusClass' => 'text-emerald-600 bg-emerald-50',
        'detailUrl' => '/pengajuan/detail/nusantara-digital'
    ],

    [
        'logo' => 'Kreatif',
        'logoClass' => 'text-sm font-bold text-slate-600',
        'perusahaan' => 'CV. Kreatif Teknologi',
        'posisi' => 'UI/UX Designer Intern',
        'tanggal' => '28 Apr 2025',
        'status' => 'Ditolak',
        'statusClass' => 'text-red-600 bg-red-50',
        'detailUrl' => '/pengajuan/detail/kreatif-teknologi'
    ],

    [
        'logo' => 'DSI',
        'logoClass' => 'text-lg font-bold text-slate-700',
        'perusahaan' => 'PT. Data Solusi Indonesia',
        'posisi' => 'Fullstack Developer Intern',
        'tanggal' => '20 Apr 2025',
        'status' => 'Dalam Proses',
        'statusClass' => 'text-blue-600 bg-blue-50',
        'detailUrl' => '/pengajuan/detail/data-solusi'
    ]

];

?>


<div class="overflow-hidden bg-white border border-slate-200 rounded-xl">

    <!-- Table Header -->
    <div
        class="hidden lg:grid lg:grid-cols-[minmax(300px,1fr)_170px_170px_150px]
               items-center px-5 py-3
               bg-white border-b border-slate-200">

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


    <!-- Data Pengajuan -->
    <div>

        <?php foreach ($pengajuan as $item): ?>

            <?php

            $logo = $item['logo'];
            $logoClass = $item['logoClass'];
            $perusahaan = $item['perusahaan'];
            $posisi = $item['posisi'];
            $tanggal = $item['tanggal'];
            $status = $item['status'];
            $statusClass = $item['statusClass'];
            $detailUrl = $item['detailUrl'];

            include __DIR__ . "/PengajuanCard.php";

            ?>

        <?php endforeach; ?>

    </div>

</div>