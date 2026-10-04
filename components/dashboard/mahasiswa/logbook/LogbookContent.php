<?php
/**
 * @var array|null $placement
 * @var bool $canAdd
 */

$placementStatus = $placement['status'] ?? null;
?>

<?php if (!$placement): ?>

    <!-- Belum memiliki penempatan magang -->
    <section class="flex flex-col items-center justify-center px-6 py-12 text-center bg-white border border-gray-100 shadow-sm rounded-2xl sm:py-16">

        <div class="flex items-center justify-center w-16 h-16 mb-5 text-blue-600 bg-blue-50 rounded-2xl">
            <svg
                class="w-8 h-8"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.8"
                    d="M12 8v4l2.5 2.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                />
            </svg>
        </div>

        <h2 class="text-lg font-bold text-gray-900 sm:text-xl">
            Anda Belum Menjalani Magang
        </h2>

        <p class="max-w-lg mt-2 text-sm leading-6 text-gray-500 sm:text-base">
            Logbook akan tersedia setelah Anda mendapatkan penempatan
            magang dan mulai menjalani kegiatan magang.
        </p>

        <a
            href="<?= e(url('/dashboard/mahasiswa/formasi-magang')) ?>"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 mt-6 text-sm font-semibold text-white transition bg-blue-600 rounded-xl hover:bg-blue-700"
        >
            <svg
                class="w-4 h-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.8"
                    d="M21 21l-4.35-4.35m1.35-5.4a6.75 6.75 0 1 1-13.5 0 6.75 6.75 0 0 1 13.5 0Z"
                />
            </svg>

            Cari Formasi Magang
        </a>

    </section>


<?php elseif ($placementStatus === 'persiapan'): ?>

    <!-- Sudah mendapatkan penempatan tetapi belum mulai -->
    <section class="flex flex-col items-center justify-center px-6 py-12 text-center bg-white border border-gray-100 shadow-sm rounded-2xl sm:py-16">

        <div class="flex items-center justify-center w-16 h-16 mb-5 text-amber-600 bg-amber-50 rounded-2xl">
            <svg
                class="w-8 h-8"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.8"
                    d="M12 8v4l2.5 2.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                />
            </svg>
        </div>

        <h2 class="text-lg font-bold text-gray-900 sm:text-xl">
            Magang Belum Dimulai
        </h2>

        <p class="max-w-lg mt-2 text-sm leading-6 text-gray-500 sm:text-base">
            Anda sudah mendapatkan penempatan magang.
            Logbook dapat digunakan setelah masa magang Anda dimulai.
        </p>

    </section>


<?php elseif (in_array($placementStatus, ['berlangsung', 'menunggu_penilaian'], true)): ?>

    <!-- Mahasiswa sedang menjalani / menyelesaikan magang -->

    <!-- Summary -->
    <?php include __DIR__ . '/SummaryCards.php'; ?>

    <!-- Calendar & Weekly Logbook -->
    <div class="grid grid-cols-1 gap-4 mt-4 lg:grid-cols-3 lg:gap-5">

        <!-- Calendar -->
        <?php include __DIR__ . '/Calendar.php'; ?>

        <!-- Weekly Logbook -->
        <?php include __DIR__ . '/WeeklyLogbook.php'; ?>

    </div>


<?php elseif ($placementStatus === 'selesai'): ?>

    <!-- Magang sudah selesai -->
    <section class="flex flex-col items-center justify-center px-6 py-12 text-center bg-white border border-gray-100 shadow-sm rounded-2xl sm:py-16">

        <div class="flex items-center justify-center w-16 h-16 mb-5 text-green-600 bg-green-50 rounded-2xl">
            <svg
                class="w-8 h-8"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.8"
                    d="m5 12 4 4L19 6"
                />
            </svg>
        </div>

        <h2 class="text-lg font-bold text-gray-900 sm:text-xl">
            Magang Telah Selesai
        </h2>

        <p class="max-w-lg mt-2 text-sm leading-6 text-gray-500 sm:text-base">
            Masa magang Anda telah selesai. Riwayat logbook tetap dapat
            digunakan untuk melihat aktivitas yang telah dicatat.
        </p>

        <!-- Tetap tampilkan riwayat -->
        <div class="w-full mt-8 text-left">
            <?php include __DIR__ . '/SummaryCards.php'; ?>

            <div class="grid grid-cols-1 gap-4 mt-4 lg:grid-cols-3 lg:gap-5">
                <?php include __DIR__ . '/Calendar.php'; ?>
                <?php include __DIR__ . '/WeeklyLogbook.php'; ?>
            </div>
        </div>

    </section>


<?php else: ?>

    <!-- Status penempatan lain / dibatalkan -->
    <section class="flex flex-col items-center justify-center px-6 py-12 text-center bg-white border border-gray-100 shadow-sm rounded-2xl sm:py-16">

        <div class="flex items-center justify-center w-16 h-16 mb-5 text-gray-500 bg-gray-100 rounded-2xl">
            <svg
                class="w-8 h-8"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1.8"
                    d="M12 9v3.5m0 3h.01M10.3 4.5 3.8 16a2 2 0 0 0 1.74 3h12.92a2 2 0 0 0 1.74-3l-6.5-11.5a2 2 0 0 0-3.4 0Z"
                />
            </svg>
        </div>

        <h2 class="text-lg font-bold text-gray-900 sm:text-xl">
            Logbook Belum Tersedia
        </h2>

        <p class="max-w-lg mt-2 text-sm leading-6 text-gray-500 sm:text-base">
            Logbook belum dapat digunakan berdasarkan status magang Anda saat ini.
        </p>

    </section>

<?php endif; ?>