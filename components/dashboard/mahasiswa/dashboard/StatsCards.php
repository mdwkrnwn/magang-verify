<?php
/** @var array $dashboard */
$stats = $dashboard['statistik'] ?? [];
$cards = [
    ['label' => 'Portofolio', 'value' => $stats['portofolio'] ?? 0, 'hint' => 'Total portofolio', 'tone' => 'blue', 'href' => '/dashboard/mahasiswa/portofolio', 'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 7.5h16.5v11.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V7.5Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8.25 7.5V5.25a1.5 1.5 0 0 1 1.5-1.5h4.5a1.5 1.5 0 0 1 1.5 1.5V7.5"/></svg>'],
    ['label' => 'Sertifikat', 'value' => $stats['sertifikat'] ?? 0, 'hint' => 'Sertifikat dimiliki', 'tone' => 'green', 'href' => '/dashboard/mahasiswa/sertifikat', 'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m12 3.75 2.18 4.42 4.88.71-3.53 3.44.83 4.86L12 14.88l-4.36 2.3.83-4.86-3.53-3.44 4.88-.71L12 3.75Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m9.5 15.5-.75 4.5L12 18.25 15.25 20l-.75-4.5"/></svg>'],
    ['label' => 'Pengajuan Magang', 'value' => $stats['pengajuan'] ?? 0, 'hint' => 'Total pengajuan', 'tone' => 'violet', 'href' => '/dashboard/mahasiswa/pengajuan', 'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 3.75h9l3 3v13.5H6a1.5 1.5 0 0 1-1.5-1.5V5.25A1.5 1.5 0 0 1 6 3.75Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 3.75v3h3M8.5 11h7M8.5 14.5h7"/></svg>'],
    ['label' => 'Logbook', 'value' => $stats['logbook'] ?? 0, 'hint' => 'Minggu tercatat', 'tone' => 'orange', 'href' => '/dashboard/mahasiswa/logbook', 'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 3.75h9l3 3v13.5H6a1.5 1.5 0 0 1-1.5-1.5V5.25A1.5 1.5 0 0 1 6 3.75Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 3.75v3h3M8.5 11h7M8.5 14.5h7M8.5 18h4"/></svg>'],
];
$tones = [
    'blue' => ['icon' => 'text-blue-600 bg-blue-50', 'value' => 'text-gray-900'],
    'green' => ['icon' => 'text-green-600 bg-green-50', 'value' => 'text-gray-900'],
    'violet' => ['icon' => 'text-violet-600 bg-violet-50', 'value' => 'text-gray-900'],
    'orange' => ['icon' => 'text-orange-600 bg-orange-50', 'value' => 'text-gray-900'],
];
?>

<section class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 sm:gap-6 sm:mb-8 xl:grid-cols-4">
    <?php foreach ($cards as $card): ?>
        <a href="<?= e(url($card['href'])) ?>" class="block p-5 transition bg-white border border-gray-100 shadow-sm sm:p-6 rounded-2xl hover:-translate-y-0.5 hover:shadow-md">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-500"><?= e($card['label']) ?></p>
                    <p class="mt-3 text-3xl font-bold <?= e($tones[$card['tone']]['value']) ?>"><?= e((string) $card['value']) ?></p>
                    <p class="mt-2 text-sm text-gray-400"><?= e($card['hint']) ?></p>
                </div>
                <div class="flex items-center justify-center w-12 h-12 rounded-xl shrink-0 <?= e($tones[$card['tone']]['icon']) ?>">
                    <?= $card['icon'] ?>
                </div>
            </div>
        </a>
    <?php endforeach; ?>
</section>
