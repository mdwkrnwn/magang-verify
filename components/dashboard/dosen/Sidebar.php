<aside
    id="sidebar"
    class="fixed inset-y-0 left-0 z-40 flex flex-col
           w-64
           max-w-[85vw]
           bg-white
           border-r border-gray-200
           -translate-x-full
           lg:translate-x-0
           transition-all
           duration-300
           ease-in-out
           overflow-hidden">

    <!-- Logo -->
    <div
        class="flex items-center h-20 px-7
               border-b border-gray-100 shrink-0">

        <div
            id="sidebar-logo"
            class="flex items-center gap-3 min-w-0">

            <!-- Logo -->
            <div
                class="flex items-center justify-center
                       w-10 h-10
                       rounded-xl
                       overflow-hidden
                       shrink-0">

                <img
                    src="<?= url('/assets/images/icon.png') ?>"
                    alt="VerifyMagang"
                    class="w-14 h-14 object-contain">

            </div>

            <!-- Nama -->
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
            ?>

            <div class="sidebar-logo-text min-w-0">

                <h1 class="text-lg font-bold text-gray-900 whitespace-nowrap">
                    VerifyMagang
                </h1>

                <p class="text-xs text-gray-500 whitespace-nowrap">
                    <?= e($roleLabel) ?>
                </p>

            </div>

        </div>

    </div>


    <!-- Navigation -->
    <nav
        class="flex-1 min-h-0
               px-4 py-6
               overflow-y-auto
               overflow-x-hidden
               overscroll-contain">

        <!-- Menu Utama -->
        <p
            class="sidebar-section-label
                   px-3 mb-3
                   text-xs font-semibold
                   tracking-wider
                   text-gray-400
                   uppercase
                   whitespace-nowrap">

            Menu Utama

        </p>


        <div class="space-y-1">

            <!-- Dashboard -->
            <a
                href="<?= url('/dashboard/dosen') ?>"
                title="Dashboard"
                class="sidebar-nav-item
                       flex items-center gap-3
                       px-4 py-3
                       rounded-xl
                       <?= $active === 'dashboard'
                           ? 'bg-blue-50 text-blue-600 font-semibold'
                           : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?>">

                <svg
                    class="w-5 h-5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    aria-hidden="true">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4" />

                </svg>

                <span class="sidebar-label text-sm whitespace-nowrap">
                    Dashboard
                </span>

            </a>


            <!-- Mahasiswa Bimbingan -->
            <a
                href="<?= url('/dashboard/dosen/bimbingan') ?>"
                title="Mahasiswa Bimbingan"
                class="sidebar-nav-item
                       flex items-center gap-3
                       px-4 py-3
                       rounded-xl
                       <?= $active === 'bimbingan'
                           ? 'bg-blue-50 text-blue-600 font-semibold'
                           : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?>">

                <svg
                    class="w-5 h-5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    aria-hidden="true">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 20h5v-2a4 4 0 00-4-4h-1
                           M9 20H4v-2a4 4 0 014-4h1
                           m4-8a4 4 0 11-8 0 4 4 0 018 0
                           m6 4a3 3 0 10-3-3" />

                </svg>

                <span class="sidebar-label text-sm whitespace-nowrap">
                    Mahasiswa Bimbingan
                </span>

            </a>


            <!-- Pengajuan Magang -->
            <a
                href="<?= url('/dashboard/dosen/pengajuan') ?>"
                title="Pengajuan Magang"
                class="sidebar-nav-item
                       flex items-center gap-3
                       px-4 py-3
                       rounded-xl
                       <?= $active === 'pengajuan'
                           ? 'bg-blue-50 text-blue-600 font-semibold'
                           : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?>">

                <svg
                    class="w-5 h-5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    aria-hidden="true">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12h6
                           m-6 4h4
                           M7 3h8l4 4v14H7a2 2 0 01-2-2V5a2 2 0 012-2Zm8 0v5h4" />

                </svg>

                <span class="sidebar-label text-sm whitespace-nowrap">
                    Pengajuan Magang
                </span>

            </a>


            <!-- Logbook -->
            <a
                href="<?= url('/dashboard/dosen/logbook') ?>"
                title="Logbook"
                class="sidebar-nav-item
                       flex items-center gap-3
                       px-4 py-3
                       rounded-xl
                       <?= $active === 'logbook'
                           ? 'bg-blue-50 text-blue-600 font-semibold'
                           : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?>">

                <svg
                    class="w-5 h-5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    aria-hidden="true">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M7 3h8l4 4v14H7a2 2 0 01-2-2V5a2 2 0 012-2Zm8 0v5h4
                           M9 13h6
                           M9 17h6" />

                </svg>

                <span class="sidebar-label text-sm whitespace-nowrap">
                    Logbook
                </span>

            </a>


            <!-- Monitoring / Penilaian -->
            <a
                href="<?= url('/dashboard/dosen/monitoring') ?>"
                title="Monitoring & Penilaian"
                class="sidebar-nav-item
                       flex items-center gap-3
                       px-4 py-3
                       rounded-xl
                       <?= $active === 'monitoring'
                           ? 'bg-blue-50 text-blue-600 font-semibold'
                           : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?>">

                <svg
                    class="w-5 h-5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    aria-hidden="true">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 19V5
                           a2 2 0 012-2h2a2 2 0 012 2v14
                           M5 19v-8a2 2 0 012-2h2
                           M15 19v-5a2 2 0 012-2h2
                           M3 21h18" />

                </svg>

                <span class="sidebar-label text-sm whitespace-nowrap">
                    Monitoring & Penilaian
                </span>

            </a>

        </div>

    </nav>

</aside>



<!-- Overlay (mobile/tablet) -->
<div
    id="sidebar-overlay"
    class="fixed inset-0 z-[35] hidden bg-black/50 lg:hidden">
</div>