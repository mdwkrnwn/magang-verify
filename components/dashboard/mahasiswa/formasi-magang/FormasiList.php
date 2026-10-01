<div class="space-y-3">

    <?php

    $formasi = [

        [
            'logo' => 'semarsoft',
            'logoClass' => 'text-xs font-bold text-slate-500',
            'perusahaan' => 'PT. Semarsoft Technology Indonesia',
            'posisi' => 'Frontend Developer Intern',
            'lokasi' => 'Malang, Jawa Timur',
            'durasi' => '3 Bulan',
            'detailUrl' => '/formasi/detail/semarsoft',
            'lamarUrl' => '/formasi/lamar/semarsoft'
        ],

        [
            'logo' => 'NDS',
            'logoClass' => 'text-lg font-bold text-blue-600',
            'perusahaan' => 'PT. Nusantara Digital Solusi',
            'posisi' => 'Backend Developer Intern',
            'lokasi' => 'Surabaya, Jawa Timur',
            'durasi' => '4 Bulan',
            'detailUrl' => '/formasi/detail/nusantara-digital',
            'lamarUrl' => '/formasi/lamar/nusantara-digital'
        ],

        [
            'logo' => 'Kreatif',
            'logoClass' => 'text-sm font-bold text-slate-600',
            'perusahaan' => 'CV. Kreatif Teknologi',
            'posisi' => 'UI/UX Designer Intern',
            'lokasi' => 'Malang, Jawa Timur',
            'durasi' => '3 Bulan',
            'detailUrl' => '/formasi/detail/kreatif-teknologi',
            'lamarUrl' => '/formasi/lamar/kreatif-teknologi'
        ],

        [
            'logo' => 'DSI',
            'logoClass' => 'text-lg font-bold text-slate-700',
            'perusahaan' => 'PT. Data Solusi Indonesia',
            'posisi' => 'Fullstack Developer Intern',
            'lokasi' => 'Jakarta Selatan, DKI Jakarta',
            'durasi' => '6 Bulan',
            'detailUrl' => '/formasi/detail/data-solusi',
            'lamarUrl' => '/formasi/lamar/data-solusi'
        ]

    ];

    ?>

    <?php foreach ($formasi as $item): ?>

        <?php
        $logo = $item['logo'];
        $logoClass = $item['logoClass'];
        $perusahaan = $item['perusahaan'];
        $posisi = $item['posisi'];
        $lokasi = $item['lokasi'];
        $durasi = $item['durasi'];
        $detailUrl = $item['detailUrl'];
        $lamarUrl = $item['lamarUrl'];

        include __DIR__ . "/FormasiCard.php";
        ?>

    <?php endforeach; ?>

</div>