<?php

$active = $active ?? '';

$role = $_SESSION['user']['role'] ?? '';

$roleLabels = [
    'mahasiswa' => 'Mahasiswa',
    'dosen' => 'Dosen',
    'tendik' => 'Tendik',
    'koordinator_magang' => 'Koordinator Magang',
    'mitra' => 'Mitra',
];

$roleLabel = $roleLabels[$role] ?? 'Pengguna';


$menuItems = [
    [
        'label' => 'Dashboard',
        'url' => '/dashboard/mahasiswa',
        'key' => 'dashboard',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/>',
    ],
    [
        'label' => 'Profil Saya',
        'url' => '/dashboard/mahasiswa/profil',
        'key' => 'profil',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>',
    ],
    [
        'label' => 'Portofolio',
        'url' => '/dashboard/mahasiswa/portofolio',
        'key' => 'portofolio',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h4l2 3h12M5 7v12a1 1 0 001 1h12a1 1 0 001-1V7M9 11h6"/>',
    ],
    [
        'label' => 'Sertifikat',
        'url' => '/dashboard/mahasiswa/sertifikat',
        'key' => 'sertifikat',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M7 3h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"/>',
    ],
    [
        'label' => 'Pengalaman',
        'url' => '/dashboard/mahasiswa/pengalaman',
        'key' => 'pengalaman',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 5v.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5v10a2 2 0 002 2h14"/>',
    ],
    [
        'label' => 'Formasi Magang',
        'url' => '/dashboard/mahasiswa/formasi-magang',
        'key' => 'formasi-magang',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 5v.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
    ],
    [
        'label' => 'Pengajuan Saya',
        'url' => '/dashboard/mahasiswa/pengajuan',
        'key' => 'pengajuan',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a3 3 0 006 0M9 5a3 3 0 016 0"/>',
    ],
    [
        'label' => 'Dosen Pembimbing    ',
        'url' => '/dashboard/mahasiswa/pembimbing',
        'key' => 'pembimbing',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
    ],
    [
        'label' => 'Laporan Magang',
        'url' => '/dashboard/mahasiswa/laporan',
        'key' => 'laporan',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"/>',
    ],
    [
        'label' => 'Logbook',
        'url' => '/dashboard/mahasiswa/logbook',
        'key' => 'logbook',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>',
    ],
];

?>

<aside
    id="sidebar"
    class="fixed inset-y-0 left-0 z-40 flex w-64 max-w-[85vw] -translate-x-full flex-col overflow-hidden border-r border-gray-200 bg-white transition-all duration-300 ease-in-out lg:translate-x-0"
>

    <!-- Logo -->
    <div class="flex h-20 shrink-0 items-center border-b border-gray-100 px-6">

        <div id="sidebar-logo" class="flex min-w-0 items-center gap-3">

            <div class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden">
                <img
                    src="<?= url('/assets/images/icon.png') ?>"
                    alt="VerifyMagang"
                    class="h-12 w-12 object-contain"
                >
            </div>

            <div class="sidebar-logo-text min-w-0">

                <h1 class="whitespace-nowrap text-lg font-bold text-gray-900">
                    VerifyMagang
                </h1>

                <p class="whitespace-nowrap text-xs text-gray-500">
                    <?= e($roleLabel) ?>
                </p>

            </div>

        </div>

    </div>


    <!-- Navigation -->
    <nav class="min-h-0 flex-1 overflow-x-hidden overflow-y-auto overscroll-contain px-4 py-6">

        <p class="sidebar-section-label mb-3 whitespace-nowrap px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">
            Menu Utama
        </p>

        <div class="space-y-1">

            <?php foreach ($menuItems as $menu): ?>

                <?php $isActive = $active === $menu['key']; ?>

                <a
                    href="<?= url($menu['url']) ?>"
                    title="<?= e($menu['label']) ?>"
                    class="sidebar-nav-item flex items-center gap-3 rounded-xl px-4 py-3 transition-colors duration-200
                        <?= $isActive
                            ? 'bg-blue-50 font-semibold text-blue-600'
                            : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?>"
                    <?= $isActive ? 'aria-current="page"' : '' ?>
                >

                    <svg
                        class="h-5 w-5 shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >
                        <?= $menu['icon'] ?>
                    </svg>

                    <span class="sidebar-label min-w-0 flex-1 whitespace-normal break-words text-sm leading-5">
                        <?= e($menu['label']) ?>
                    </span>

                </a>

            <?php endforeach; ?>

        </div>

    </nav>

</aside>


<!-- Overlay -->
<div
    id="sidebar-overlay"
    class="fixed inset-0 z-[35] hidden bg-black/50 lg:hidden"
></div>