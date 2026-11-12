<?php

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
        'url' => '/dashboard/dosen',
        'key' => 'dashboard',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/>',
    ],
    [
        'label' => 'Mahasiswa Bimbingan',
        'url' => '/dashboard/dosen/bimbingan',
        'key' => 'bimbingan',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-8a4 4 0 11-8 0 4 4 0 018 0m6 4a3 3 0 10-3-3"/>',
    ],
    [
        'label' => 'Pengajuan Magang',
        'url' => '/dashboard/dosen/pengajuan',
        'key' => 'pengajuan',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h4M7 3h8l4 4v14H7a2 2 0 01-2-2V5a2 2 0 012-2Zm8 0v5h4"/>',
    ],
    [
        'label' => 'Verifikasi Laporan',
        'url' => '/dashboard/dosen/verifikasi-laporan',
        'key' => 'verifikasi-laporan',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 14l2 2 4-4"/>',
    ],
    [
        'label' => 'Logbook',
        'url' => '/dashboard/dosen/logbook',
        'key' => 'logbook',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 3h8l4 4v14H7a2 2 0 01-2-2V5a2 2 0 012-2Zm8 0v5h4M9 13h6M9 17h6"/>',
    ],
    // [
    //     'label' => 'Monitoring & Penilaian',
    //     'url' => '/dashboard/dosen/monitoring',
    //     'key' => 'monitoring',
    //     'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V5a2 2 0 012-2h2a2 2 0 012 2v14M5 19v-8a2 2 0 012-2h2m6 10v-5a2 2 0 012-2h2M3 21h18"/>',
    // ],

];

?>

<aside
    id="sidebar"
    class="fixed inset-y-0 left-0 z-40 flex w-64 max-w-[85vw] -translate-x-full flex-col overflow-hidden border-r border-gray-200 bg-white transition-all duration-300 ease-in-out lg:translate-x-0">

    <!-- Logo -->
    <div class="flex h-20 shrink-0 items-center border-b border-gray-100 px-6">
        <div id="sidebar-logo" class="flex min-w-0 items-center gap-3">

            <div class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden">
                <img
                    src="<?= url('/assets/images/icon.png') ?>"
                    alt="VerifyMagang"
                    class="h-12 w-12 object-contain">
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
    <nav
        class="min-h-0 flex-1 overflow-x-hidden overflow-y-auto overscroll-contain px-4 py-6">

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
                    <?= $isActive ? 'aria-current="page"' : '' ?>>

                    <svg
                        class="h-5 w-5 shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        aria-hidden="true">
                        <?= $menu['icon'] ?>
                    </svg>

                    <span class="sidebar-label min-w-0 flex-1 whitespace-nowrap break-words text-sm leading-5">
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
    class="fixed inset-0 z-[35] hidden bg-black/50 lg:hidden"></div>