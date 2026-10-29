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
                href="<?= url('/dashboard/mitra') ?>"
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
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4" />

                </svg>

                <span
                    class="sidebar-label text-sm whitespace-nowrap">

                    Dashboard

                </span>

            </a>

        </div>



        <div class="mt-1">
            <a href="<?= url('/dashboard/mitra/formasi') ?>" title="Formasi Magang" class="sidebar-nav-item flex items-center gap-3 px-4 py-3 rounded-xl <?= $active === 'formasi' ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?>">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7h4l2 3h12M5 7v12a1 1 0 001 1h12a1 1 0 001-1V7M9 11h6"/></svg>
                <span class="sidebar-label text-sm whitespace-nowrap">Formasi Magang</span>
            </a>
        </div>

        <div class="mt-1">
            <a href="<?= url('/dashboard/mitra/pengajuan') ?>" title="Pelamar / Pengajuan" class="sidebar-nav-item flex items-center gap-3 px-4 py-3 rounded-xl <?= $active === 'pengajuan' ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?>">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h4M7 3h8l4 4v14H7a2 2 0 01-2-2V5a2 2 0 012-2Zm8 0v5h4"/></svg>
                <span class="sidebar-label text-sm whitespace-nowrap">Pelamar / Pengajuan</span>
            </a>
        </div>

        <div class="mt-1">
            <a href="<?= url('/dashboard/mitra/penilaian') ?>" title="Penilaian Magang" class="sidebar-nav-item flex items-center gap-3 px-4 py-3 rounded-xl <?= $active === 'penilaian' ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?>">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5 0a8 8 0 11-16 0 8 8 0 0116 0z"/></svg>
                <span class="sidebar-label text-sm whitespace-nowrap">Penilaian Magang</span>
            </a>
        </div>

        <div class="mt-1">
            <a
                href="<?= url('/dashboard/mitra/logbook') ?>"
                title="Logbook"
                class="sidebar-nav-item flex items-center gap-3 px-4 py-3 rounded-xl <?= $active === 'logbook' ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?>">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 3h8l4 4v14H7a2 2 0 01-2-2V5a2 2 0 012-2Zm8 0v5h4M9 13h6M9 17h6" />
                </svg>
                <span class="sidebar-label text-sm whitespace-nowrap">Logbook</span>
            </a>
        </div>

    </nav>

</aside>


<!-- Overlay (mobile/tablet) -->
<div
    id="sidebar-overlay"
    class="fixed inset-0 z-[35] hidden bg-black/50 lg:hidden">
</div>