<?php

/*
|--------------------------------------------------------------------------
| Data pengguna dari session
|--------------------------------------------------------------------------
*/

$namaPengguna = trim(
    (string) ($_SESSION['user']['name'] ?? 'Pengguna')
);

$role = $_SESSION['user']['role'] ?? 'mahasiswa';

$roleLabels = [
    'mahasiswa' => 'Mahasiswa',
    'dosen' => 'Dosen',
    'tendik' => 'Tendik',
    'koordinator_magang' => 'Koordinator Magang',
    'mitra' => 'Mitra',
];

/*
|--------------------------------------------------------------------------
| Foto profil
|--------------------------------------------------------------------------
*/

$fotoProfil = '';

try {

    require_once __DIR__ . '/../../../models/ProfilMahasiswa.php';

    global $pdo;

    $profilModel = new ProfilMahasiswa($pdo);

    $profil = $profilModel->getByUserId(
        (int) ($_SESSION['user']['id'] ?? 0)
    );

    $fotoProfil = trim(
        (string) ($profil['foto_path'] ?? '')
    );
} catch (Throwable $e) {

    error_log(
        'Gagal mengambil foto profil header: '
            . $e->getMessage()
    );
}

/*
|--------------------------------------------------------------------------
| Inisial nama
|--------------------------------------------------------------------------
*/

$bagianNama = preg_split(
    '/\s+/',
    $namaPengguna,
    -1,
    PREG_SPLIT_NO_EMPTY
);

$inisial = 'P';

if (!empty($bagianNama)) {

    $inisial = mb_strtoupper(
        mb_substr($bagianNama[0], 0, 1)
    );

    if (count($bagianNama) > 1) {

        $inisial .= mb_strtoupper(
            mb_substr(
                $bagianNama[count($bagianNama) - 1],
                0,
                1
            )
        );
    }
}

?>

<header
    class="fixed inset-x-0 top-0 z-30 h-20
           bg-white border-b border-gray-200
           lg:left-64">

    <div
        class="flex items-center justify-between
               h-full px-4 sm:px-8">

        <!-- Sidebar Toggle -->
        <button
            id="sidebar-toggle"
            type="button"
            aria-label="Tutup sidebar"
            aria-expanded="true"
            class="flex items-center justify-center
                   text-gray-600 transition
                   bg-white border border-gray-200
                   w-11 h-11 rounded-xl
                   hover:bg-gray-50 shrink-0">

            <svg
                id="sidebar-toggle-icon"
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                aria-hidden="true">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />

            </svg>

        </button>


        <!-- Right -->
        <div
            class="flex items-center gap-3
                   ml-auto sm:gap-6">

            <?php include __DIR__ . '/../shared/NotificationBell.php'; ?>


            <!-- Profile -->
            <div
                class="relative pl-3
                       border-l border-gray-200 sm:pl-6"
                id="profile-dropdown-wrapper">

                <!-- Profile Button -->
                <button
                    type="button"
                    id="profile-dropdown-button"
                    class="flex items-center gap-3
                           rounded-xl px-2 py-1.5
                           hover:bg-gray-50 transition"
                    aria-expanded="false"
                    aria-haspopup="true">

                    <!-- Avatar -->
                    <div
                        class="flex items-center justify-center
                               text-sm font-semibold
                               text-blue-600 bg-blue-100
                               rounded-full
                               w-11 h-11
                               shrink-0 overflow-hidden">

                        <?php if ($fotoProfil !== ''): ?>

                            <img
                                src="<?= e(url('/mahasiswa/profil/foto/' . rawurlencode(basename($fotoProfil)))) ?>"
                                alt="Foto profil <?= e($namaPengguna) ?>"
                                class="w-full h-full object-cover"
                                onerror="
                                    this.onerror = null;
                                    this.remove();
                                    const fallback = this.parentElement?.querySelector('[data-profile-fallback]');
                                    if (fallback) {
                                        fallback.classList.remove('hidden');
                                        fallback.classList.add('flex');
                                    }
                                ">

                            <span
                                class="hidden w-full h-full
                                       items-center justify-center"
                                data-profile-fallback>
                                <?= e($inisial) ?>
                            </span>

                        <?php else: ?>

                            <span
                                class="flex w-full h-full
                                       items-center justify-center">
                                <?= e($inisial) ?>
                            </span>

                        <?php endif; ?>

                    </div>


                    <!-- User Information -->
                    <div class="hidden sm:block text-left">

                        <p class="text-sm font-semibold text-gray-800">
                            <?= e($namaPengguna) ?>
                        </p>

                        <p class="text-xs text-gray-500">
                            <?= e($roleLabels[$role] ?? 'Pengguna') ?>
                        </p>

                    </div>


                    <!-- Arrow -->
                    <svg
                        id="profile-dropdown-icon"
                        class="hidden sm:block
                               w-4 h-4 ml-1
                               text-gray-400
                               transition-transform"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 9l-7 7-7-7" />

                    </svg>

                </button>


                <!-- Dropdown -->
                <div
                    id="profile-dropdown"
                    class="hidden absolute
                           right-0 top-full mt-3
                           w-60 bg-white
                           border border-gray-200
                           rounded-xl shadow-lg
                           overflow-hidden z-50">

                    <!-- User Header -->
                    <div
                        class="px-4 py-3
                               border-b border-gray-100">

                        <p
                            class="text-sm font-semibold
                                   text-gray-900 truncate">
                            <?= e($namaPengguna) ?>
                        </p>

                        <p
                            class="text-xs text-gray-500 mt-0.5">
                            <?= e($roleLabels[$role] ?? 'Pengguna') ?>
                        </p>

                    </div>


                    <!-- Menu -->
                    <div class="p-1.5">

                        <!-- Beranda -->
                        <a
                            href="<?= e(url('/')) ?>"
                            class="flex items-center gap-3
                                   px-3 py-2.5
                                   rounded-lg
                                   text-sm text-gray-700
                                   hover:bg-blue-50
                                   hover:text-blue-600
                                   transition">

                            <svg
                                class="w-5 h-5 shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M3 10.5 12 3l9 7.5M5 9.5V21h14V9.5M9 21v-6h6v6" />

                            </svg>

                            <span>Beranda</span>

                        </a>


                        <!-- Pengaturan -->
                        <?php if ($role === 'mahasiswa'): ?>
                        <a
                            href="<?= e(url('/dashboard/mahasiswa/pengaturan')) ?>"
                            class="flex items-center gap-3
                                   px-3 py-2.5
                                   rounded-lg
                                   text-sm text-gray-700
                                   hover:bg-blue-50
                                   hover:text-blue-600
                                   transition">

                            <svg
                                class="w-5 h-5 shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z" />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06-1.8 1.8-.06-.06a1.7 1.7 0 0 0-1.88-.34 1.7 1.7 0 0 0-1.04 1.56V20H11v-.1a1.7 1.7 0 0 0-1.04-1.56 1.7 1.7 0 0 0-1.88.34l-.06.06-1.8-1.8.06-.06A1.7 1.7 0 0 0 6.62 15a1.7 1.7 0 0 0-1.56-1.04H5V11h.06A1.7 1.7 0 0 0 6.62 10a1.7 1.7 0 0 0-.34-1.88l-.06-.06 1.8-1.8.06.06A1.7 1.7 0 0 0 9.1 6.46 1.7 1.7 0 0 0 11 5.1V5h3v.1a1.7 1.7 0 0 0 1.04 1.56 1.7 1.7 0 0 0 1.88-.34l.06-.06 1.8 1.8-.06.06A1.7 1.7 0 0 0 18.38 10a1.7 1.7 0 0 0 1.56 1.04H20v3h-.06A1.7 1.7 0 0 0 19.4 15Z" />

                            </svg>

                            <span>Pengaturan</span>

                        </a>
                        <?php endif; ?>


                        <!-- Separator -->
                        <div
                            class="my-1.5
                                   border-t border-gray-100"></div>


                        <!-- Logout -->
                        <form
                            method="POST"
                            action="<?= e(url('/logout')) ?>">

                            <input
                                type="hidden"
                                name="_csrf_token"
                                value="<?= e(csrfToken()) ?>">

                            <button
                                type="submit"
                                class="w-full flex items-center gap-3
                                       px-3 py-2.5
                                       rounded-lg
                                       text-sm text-red-600
                                       hover:bg-red-50
                                       transition text-left">

                                <svg
                                    class="w-5 h-5 shrink-0"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M17 16l4-4m0 0-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1" />

                                </svg>

                                <span>Keluar</span>

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</header>


<script>
    const profileDropdownButton =
        document.getElementById('profile-dropdown-button');

    const profileDropdown =
        document.getElementById('profile-dropdown');

    const profileDropdownIcon =
        document.getElementById('profile-dropdown-icon');


    if (profileDropdownButton && profileDropdown) {

        profileDropdownButton.addEventListener(
            'click',
            function(event) {

                event.stopPropagation();

                const isOpen = !profileDropdown.classList.contains('hidden');

                profileDropdown.classList.toggle(
                    'hidden',
                    isOpen
                );

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

            }
        );


        document.addEventListener(
            'click',
            function() {

                profileDropdown.classList.add('hidden');

                profileDropdownButton.setAttribute(
                    'aria-expanded',
                    'false'
                );

                if (profileDropdownIcon) {

                    profileDropdownIcon.classList.remove(
                        'rotate-180'
                    );

                }

            }
        );


        document.addEventListener(
            'keydown',
            function(event) {

                if (event.key === 'Escape') {

                    profileDropdown.classList.add(
                        'hidden'
                    );

                    profileDropdownButton.setAttribute(
                        'aria-expanded',
                        'false'
                    );

                    if (profileDropdownIcon) {

                        profileDropdownIcon.classList.remove(
                            'rotate-180'
                        );

                    }

                }

            }
        );

    }
</script>