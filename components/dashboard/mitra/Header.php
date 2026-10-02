<header
    class="fixed inset-x-0 top-0 z-30 h-20
           bg-white
           border-b border-gray-200
           lg:left-64">

    <div
        class="flex items-center justify-between
               h-full
               px-4 sm:px-8">

        <!-- Sidebar Toggle -->
        <button
            id="sidebar-toggle"
            type="button"
            aria-label="Tutup sidebar"
            aria-expanded="true"
            class="flex items-center justify-center
           text-gray-600
           transition
           bg-white
           border border-gray-200
           w-11 h-11
           rounded-xl
           hover:bg-gray-50
           shrink-0">
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
                   ml-auto
                   sm:gap-6">

            <!-- Notification -->
            <button
                type="button"
                class="relative
                       flex items-center justify-center
                       text-gray-500
                       transition
                       bg-white
                       border border-gray-200
                       w-11 h-11
                       rounded-xl
                       hover:bg-gray-50">

                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />

                </svg>

                <span
                    class="absolute w-2 h-2
                           bg-red-500
                           rounded-full
                           top-2 right-2">
                </span>

            </button>


            <!-- Profile -->
            <div
                class="flex items-center gap-3
                       pl-3
                       border-l border-gray-200
                       sm:pl-6">

                <!-- Avatar -->
                <?php

                require_once __DIR__ . '/../../../models/ProfilMahasiswa.php';

                $namaPengguna = trim($_SESSION['user']['name'] ?? 'Pengguna');
                $fotoProfil = '';

                try {
                    global $pdo;

                    $profilModel = new ProfilMahasiswa($pdo);
                    $profil = $profilModel->getOrCreateByUserId(
                        (int) $_SESSION['user']['id']
                    );

                    $fotoProfil = $profil['foto_profil'] ?? '';
                } catch (Throwable $e) {
                    error_log('Gagal mengambil foto profil sidebar: ' . $e->getMessage());
                }

                // Buat inisial nama.
                $bagianNama = preg_split(
                    '/\s+/',
                    $namaPengguna,
                    -1,
                    PREG_SPLIT_NO_EMPTY
                );

                $inisial = '';

                if (!empty($bagianNama)) {
                    $inisial = mb_strtoupper(mb_substr($bagianNama[0], 0, 1));

                    if (count($bagianNama) > 1) {
                        $inisial .= mb_strtoupper(
                            mb_substr($bagianNama[count($bagianNama) - 1], 0, 1)
                        );
                    }
                }
                ?>

                <div class="flex items-center justify-center
            text-sm font-semibold text-blue-600 bg-blue-100
            rounded-full w-11 h-11 shrink-0 overflow-hidden">

                    <?php if ($fotoProfil !== ''): ?>
                        <img
                            src="<?= e(url('/' . ltrim($fotoProfil, '/'))) ?>"
                            alt="Foto profil <?= e($namaPengguna) ?>"
                            class="w-full h-full object-cover">
                    <?php else: ?>
                        <?= e($inisial ?: 'P') ?>
                    <?php endif; ?>

                </div>


                <!-- User Information -->
                <div class="hidden sm:block">

                    <p class="text-sm font-semibold text-gray-800">
                        <?= e($_SESSION['user']['name'] ?? 'Pengguna') ?>
                    </p>

                    <p class="text-xs text-gray-500">
                        <?php
                        $role = $_SESSION['user']['role'] ?? '';

                        $roleLabels = [
                            'mahasiswa' => 'Mahasiswa',
                            'dosen' => 'Dosen',
                            'tendik' => 'Tendik',
                            'koordinator_magang' => 'Koordinator Magang',
                            'mitra' => 'Mitra',
                        ];
                        ?>

                        <?= e($roleLabels[$role] ?? 'Pengguna') ?>
                    </p>

                </div>

                <!-- Dropdown Icon -->
                <svg
                    class="hidden w-4 h-4 ml-1
                           text-gray-400
                           sm:block"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 9l-7 7-7-7" />

                </svg>

            </div>

        </div>

    </div>

</header>