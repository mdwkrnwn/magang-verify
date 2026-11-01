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
        'url' => url('/dashboard/koordinator-magang'),
        'key' => 'dashboard',
        'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4',
    ],
    [
        'label' => 'Penetapan Dosen Pembimbing',
        'url' => url('/dashboard/koordinator-magang/penetapan-dosen'),
        'key' => 'penetapan-dosen',
        'icon' => 'M12 14a4 4 0 100-8 4 4 0 000 8Zm-7 7a7 7 0 0114 0',
    ],
    [
        'label' => 'Penempatan Magang',
        'url' => url('/dashboard/koordinator-magang/penempatan'),
        'key' => 'penempatan',
        'icon' => 'M3 7h4l2 3h12M5 7v12a1 1 0 001 1h12a1 1 0 001-1V7M9 11h6',
    ],
    [
        'label' => 'Logbook',
        'url' => url('/dashboard/koordinator-magang/logbook'),
        'key' => 'logbook',
        'icon' => 'M7 3h8l4 4v14H7a2 2 0 01-2-2V5a2 2 0 012-2Zm8 0v5h4M9 13h6M9 17h6',
    ],
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
                    src="<?= e(url('/assets/images/icon.png')) ?>"
                    alt="VerifyMagang"
                    class="h-14 w-14 object-contain">
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

            <?php foreach ($menuItems as $item): ?>
                <?php $isActive = $active === $item['key']; ?>

                <a
                    href="<?= e($item['url']) ?>"
                    title="<?= e($item['label']) ?>"
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
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="<?= e($item['icon']) ?>" />
                    </svg>

                    <span class="sidebar-label min-w-0 flex-1 whitespace-normal break-words text-sm leading-5">
                        <?= e($item['label']) ?>
                    </span>
                </a>

            <?php endforeach; ?>

        </div>

    </nav>
</aside>

<!-- Overlay (Mobile / Tablet) -->
<div
    id="sidebar-overlay"
    class="fixed inset-0 z-[35] hidden bg-black/50 lg:hidden"></div>