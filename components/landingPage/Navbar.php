<?php

$active = $active ?? '';


$navD = fn($k) =>
$active === $k
    ? 'relative h-full flex items-center text-sm font-semibold text-blue-600 whitespace-nowrap'
    : 'h-full flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 transition whitespace-nowrap';


$navM = fn($k) =>
$active === $k
    ? 'block py-3 text-sm font-semibold text-blue-600'
    : 'block py-3 text-sm font-medium text-gray-700 hover:text-blue-600';


$navUnderline =
    '<span class="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-500"></span>';


/*
|--------------------------------------------------------------------------
| Navigation Links
|--------------------------------------------------------------------------
*/

$navLinks = [
    ['beranda', 'Beranda', url('/')],
    ['mahasiswa', 'Daftar Mahasiswa', url('/mahasiswa')],
    ['mitra', 'Daftar Mitra', url('/mitra')],
    ['tentang', 'Tentang', url('/tentang')],
];


$navLogin = url('/login');

$isLoggedIn = !empty($_SESSION['user']);

$userName = $isLoggedIn
    ? trim((string) ($_SESSION['user']['name'] ?? 'Pengguna'))
    : 'Pengguna';

$userRole = $isLoggedIn
    ? trim((string) ($_SESSION['user']['role'] ?? ''))
    : '';

/*
 * Dashboard berdasarkan role
 */
$dashboardUrl = match ($userRole) {
    'mahasiswa' => url('/dashboard/mahasiswa'),
    'dosen' => url('/dashboard/dosen'),
    'koordinator_magang' => url('/dashboard/koordinator-magang'),
    'tendik' => url('/dashboard/tendik'),
    'mitra' => url('/dashboard/mitra'),
    default => url('/'),
};

$initial = 'P';

$nameParts = preg_split('/\s+/', $userName, -1, PREG_SPLIT_NO_EMPTY);

if (!empty($nameParts)) {
    $initial = strtoupper(substr($nameParts[0], 0, 1));

    if (count($nameParts) > 1) {
        $initial .= strtoupper(
            substr($nameParts[count($nameParts) - 1], 0, 1)
        );
    }
}

?>


<nav class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-100">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 h-20 flex items-center justify-between gap-4">

        <!-- Logo -->
        <a href="<?= url('/') ?>" class="flex items-center min-w-0">
            <img
                src="<?= url('/assets/images/icon.png') ?>"
                alt="MagangVerify"
                class="h-14 w-auto shrink-0">

            <div class="ml-3 flex flex-col min-w-0">
                <h2 class="font-bold text-lg leading-tight">
                    MagangVerify
                </h2>

                <!-- Tagline disembunyikan di HP supaya tidak sesak -->
                <p class="hidden sm:block text-xs text-gray-500 mt-1">
                    Portofolio Terverifikasi, Masa Depan Lebih Dekat
                </p>
            </div>
        </a>


        <!-- Desktop (mulai 1024px) -->
        <div class="hidden lg:flex items-center gap-6 xl:gap-10 h-full shrink-0">

            <?php foreach ($navLinks as [$key, $label, $href]): ?>

                <a
                    href="<?= $href ?>"
                    class="<?= $navD($key) ?>">
                    <?= $label ?>

                    <?= $active === $key ? $navUnderline : '' ?>
                </a>

            <?php endforeach; ?>


            <?php if (!$isLoggedIn): ?>

                <a
                    href="<?= $navLogin ?>"
                    class="ml-2 xl:ml-8 px-6 xl:px-7 py-2.5 border border-blue-500 text-blue-600 rounded-md text-sm font-medium whitespace-nowrap hover:bg-blue-50 transition">
                    Masuk
                </a>

            <?php else: ?>

                <div class="relative ml-2 xl:ml-8" id="profile-dropdown-wrapper">

                    <button
                        type="button"
                        id="profile-dropdown-button"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-lg hover:bg-slate-50 transition"
                        aria-expanded="false">

                        <span
                            class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold shrink-0">
                            <?= e($initial) ?>
                        </span>

                        <span class="hidden xl:block max-w-[140px] truncate text-sm font-semibold text-slate-700">
                            <?= e($userName) ?>
                        </span>

                        <svg
                            id="profile-dropdown-icon"
                            class="w-4 h-4 text-slate-500 transition-transform"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m6 9 6 6 6-6" />
                        </svg>

                    </button>

                    <!-- Dropdown -->
                    <div
                        id="profile-dropdown"
                        class="hidden absolute right-0 top-full mt-2 w-56 bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden">

                        <div class="px-4 py-3 border-b border-slate-100">
                            <p class="text-sm font-semibold text-slate-900 truncate">
                                <?= e($userName) ?>
                            </p>

                            <p class="text-xs text-slate-500 mt-0.5">
                                <?= e(ucwords(str_replace('_', ' ', $userRole))) ?>
                            </p>
                        </div>

                        <div class="p-1.5">

                            <a
                                href="<?= e($dashboardUrl) ?>"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition">

                                <svg
                                    class="w-4 h-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.8">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3 13h8V3H3v10Zm10 8h8V11h-8v10ZM3 21h8v-6H3v6Zm10-10h8V3h-8v8Z" />
                                </svg>

                                Dashboard
                            </a>

                            <form
                                method="POST"
                                action="<?= e(url('/logout')) ?>">

                                <input
                                    type="hidden"
                                    name="_csrf_token"
                                    value="<?= e(csrfToken()) ?>">

                                <button
                                    type="submit"
                                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-red-600 hover:bg-red-50 transition">

                                    <svg
                                        class="w-4 h-4"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M15 12H3m0 0 4-4m-4 4 4 4M21 19V5a2 2 0 0 0-2-2h-6" />
                                    </svg>

                                    Keluar
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            <?php endif; ?>

        </div>


        <!-- Mobile / Tablet Button -->
        <button
            id="menu-button"
            type="button"
            aria-label="Buka menu"
            class="lg:hidden p-2 text-gray-700 hover:text-blue-600 shrink-0">

            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-6 h-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M4 6h16M4 12h16M4 18h16" />
            </svg>

        </button>

    </div>


    <!-- Overlay -->
    <div
        id="menu-overlay"
        class="fixed inset-0 z-40 bg-black/30 opacity-0 invisible transition-all duration-300 lg:hidden"></div>


    <!-- Mobile / Tablet Menu -->
    <div
        id="mobile-menu"
        class="fixed top-0 right-0 z-50 h-full w-[min(18rem,85vw)] overflow-y-auto bg-white shadow-xl translate-x-full transition-transform duration-300 lg:hidden">

        <div class="p-6">

            <!-- Close -->
            <div class="flex justify-end mb-6">

                <button
                    id="close-menu"
                    type="button"
                    aria-label="Tutup menu"
                    class="p-2 text-gray-600 hover:text-blue-600 transition">
                    ✕
                </button>

            </div>


            <?php if ($isLoggedIn): ?>

                <!-- User Profile -->
                <div class="pb-5 mb-4 border-b border-slate-100">

                    <div class="flex items-center gap-3">

                        <!-- Avatar -->
                        <div
                            class="w-11 h-11 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold shrink-0">
                            <?= e($initial) ?>
                        </div>

                        <!-- User Info -->
                        <div class="min-w-0">

                            <p class="text-sm font-semibold text-slate-900 truncate">
                                <?= e($userName) ?>
                            </p>

                            <p class="text-xs text-slate-500 mt-0.5">
                                <?= e(ucwords(str_replace('_', ' ', $userRole))) ?>
                            </p>

                        </div>

                    </div>

                </div>

            <?php endif; ?>


            <!-- Navigation -->
            <div class="space-y-1">

                <?php foreach ($navLinks as [$key, $label, $href]): ?>

                    <a
                        href="<?= $href ?>"
                        class="<?= $navM($key) ?>">
                        <?= $label ?>
                    </a>

                <?php endforeach; ?>

            </div>


            <!-- Account Actions -->
            <?php if ($isLoggedIn): ?>

                <div class="mt-5 pt-4 border-t border-slate-100">

                    <!-- Dashboard -->
                    <a
                        href="<?= e($dashboardUrl) ?>"
                        class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition">

                        <svg
                            class="w-5 h-5 shrink-0"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 13h8V3H3v10Zm10 8h8V11h-8v10ZM3 21h8v-6H3v6Zm10-10h8V3h-8v8Z" />

                        </svg>

                        Dashboard

                    </a>


                    <!-- Logout -->
                    <form
                        method="POST"
                        action="<?= e(url('/logout')) ?>"
                        class="mt-1">

                        <input
                            type="hidden"
                            name="_csrf_token"
                            value="<?= e(csrfToken()) ?>">

                        <button
                            type="submit"
                            class="w-full flex items-center gap-3 px-3 py-3 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50 transition">

                            <svg
                                class="w-5 h-5 shrink-0"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="1.8">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 12H3m0 0 4-4m-4 4 4 4M21 19V5a2 2 0 0 0-2-2h-6" />

                            </svg>

                            Keluar

                        </button>

                    </form>

                </div>

            <?php else: ?>

                <!-- Login -->
                <a
                    href="<?= e($navLogin) ?>"
                    class="block text-center mt-5 px-5 py-3 border border-blue-500 text-blue-600 rounded-lg text-sm font-semibold hover:bg-blue-50 transition">
                    Masuk
                </a>

            <?php endif; ?>


        </div>

    </div>

</nav>


<script>
    const menuButton = document.getElementById('menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const closeMenu = document.getElementById('close-menu');
    const menuOverlay = document.getElementById('menu-overlay');


    menuButton.addEventListener('click', () => {

        mobileMenu.classList.remove('translate-x-full');

        menuOverlay.classList.remove(
            'opacity-0',
            'invisible'
        );

        menuOverlay.classList.add(
            'opacity-100',
            'visible'
        );

    });


    function closeMobileMenu() {

        mobileMenu.classList.add('translate-x-full');

        menuOverlay.classList.remove(
            'opacity-100',
            'visible'
        );

        menuOverlay.classList.add(
            'opacity-0',
            'invisible'
        );

    }


    closeMenu.addEventListener(
        'click',
        closeMobileMenu
    );


    menuOverlay.addEventListener(
        'click',
        closeMobileMenu
    );


    // Tutup menu otomatis saat layar membesar ke desktop
    window.matchMedia('(min-width: 1024px)').addEventListener('change', (e) => {
        if (e.matches) closeMobileMenu();
    });
</script>

<script>
    const profileDropdownButton = document.getElementById('profile-dropdown-button');
    const profileDropdown = document.getElementById('profile-dropdown');
    const profileDropdownIcon = document.getElementById('profile-dropdown-icon');

    if (profileDropdownButton && profileDropdown) {

        profileDropdownButton.addEventListener('click', (event) => {
            event.stopPropagation();

            const isOpen = !profileDropdown.classList.contains('hidden');

            profileDropdown.classList.toggle('hidden', isOpen);

            profileDropdownButton.setAttribute(
                'aria-expanded',
                String(!isOpen)
            );

            if (profileDropdownIcon) {
                profileDropdownIcon.classList.toggle(
                    'rotate-180',
                    !isOpen
                );
            }
        });

        document.addEventListener('click', () => {

            profileDropdown.classList.add('hidden');

            profileDropdownButton.setAttribute(
                'aria-expanded',
                'false'
            );

            if (profileDropdownIcon) {
                profileDropdownIcon.classList.remove('rotate-180');
            }

        });
    }
</script>