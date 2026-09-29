<nav class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-100">

    <div class="max-w-6xl mx-auto px-6 h-20 flex items-center justify-between">

        <!-- Logo -->
        <a href="/" class="flex items-center">
            <img
                src="assets/images/logo.png"
                alt="VerifyMagang"
                class="h-11 w-auto"
            >
        </a>

        <!-- Desktop -->
        <div class="hidden md:flex items-center gap-10 h-full">

            <a
                href="/"
                class="relative h-full flex items-center text-sm font-semibold text-blue-600"
            >
                Beranda
                <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-500"></span>
            </a>

            <a
                href="pages/mahasiswa.php"
                class="text-sm font-medium text-gray-700 hover:text-blue-600 transition"
            >
                Daftar Mahasiswa
            </a>

            <a
                href="pages/tentang.php"
                class="text-sm font-medium text-gray-700 hover:text-blue-600 transition"
            >
                Tentang
            </a>

            <a
                href="pages/login.php"
                class="ml-8 px-7 py-2.5 border border-blue-500 text-blue-600 rounded-md text-sm font-medium hover:bg-blue-50 transition"
            >
                Masuk
            </a>

        </div>

        <!-- Mobile Button -->
        <button
            id="menu-button"
            type="button"
            class="md:hidden p-2 text-gray-700 hover:text-blue-600"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-6 h-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M4 6h16M4 12h16M4 18h16"
                />
            </svg>
        </button>

    </div>

    <!-- Overlay -->
    <div
        id="menu-overlay"
        class="fixed inset-0 z-40 bg-black/30 opacity-0 invisible transition-all duration-300 md:hidden"
    ></div>

    <!-- Mobile Menu -->
    <div
        id="mobile-menu"
        class="fixed top-0 right-0 z-50 h-full w-72 bg-white shadow-xl translate-x-full transition-transform duration-300 md:hidden"
    >

        <div class="p-6">

            <div class="flex justify-end mb-8">
                <button
                    id="close-menu"
                    type="button"
                    class="p-2 text-gray-600 hover:text-blue-600"
                >
                    ✕
                </button>
            </div>

            <div class="space-y-2">

                <a
                    href="/"
                    class="block py-3 text-sm font-semibold text-blue-600"
                >
                    Beranda
                </a>

                <a
                    href="pages/mahasiswa.php"
                    class="block py-3 text-sm font-medium text-gray-700 hover:text-blue-600"
                >
                    Daftar Mahasiswa
                </a>

                <a
                    href="pages/tentang.php"
                    class="block py-3 text-sm font-medium text-gray-700 hover:text-blue-600"
                >
                    Tentang
                </a>

                <a
                    href="pages/login.php"
                    class="block text-center mt-5 px-5 py-2.5 border border-blue-500 text-blue-600 rounded-md text-sm font-medium"
                >
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

closeMenu.addEventListener('click', closeMobileMenu);

menuOverlay.addEventListener('click', closeMobileMenu);

</script>