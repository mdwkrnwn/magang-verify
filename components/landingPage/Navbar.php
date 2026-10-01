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

?>


<nav class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-100">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 h-20 flex items-center justify-between gap-4">

        <!-- Logo -->
        <a href="<?= url('/') ?>" class="flex items-center min-w-0">
            <img
                src="<?= url('/assets/images/icon.png') ?>"
                alt="VerifyMagang"
                class="h-14 w-auto shrink-0">

            <div class="ml-3 flex flex-col min-w-0">
                <h2 class="font-bold text-lg leading-tight">
                    VerifyMagang
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


            <a
                href="<?= $navLogin ?>"
                class="ml-2 xl:ml-8 px-6 xl:px-7 py-2.5 border border-blue-500 text-blue-600 rounded-md text-sm font-medium whitespace-nowrap hover:bg-blue-50 transition">
                Masuk
            </a>

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

            <div class="flex justify-end mb-8">

                <button
                    id="close-menu"
                    type="button"
                    aria-label="Tutup menu"
                    class="p-2 text-gray-600 hover:text-blue-600">
                    ✕
                </button>

            </div>


            <div class="space-y-2">

                <?php foreach ($navLinks as [$key, $label, $href]): ?>

                    <a
                        href="<?= $href ?>"
                        class="<?= $navM($key) ?>">
                        <?= $label ?>
                    </a>

                <?php endforeach; ?>


                <a
                    href="<?= $navLogin ?>"
                    class="block text-center mt-5 px-5 py-2.5 border border-blue-500 text-blue-600 rounded-md text-sm font-medium">
                    Masuk
                </a>

            </div>

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