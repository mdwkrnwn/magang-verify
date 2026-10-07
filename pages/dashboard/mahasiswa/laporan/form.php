<?php

$base = url('/');

$active = 'laporan';

$formAction = url('/dashboard/mahasiswa/laporan/tambah');

$weeklyTemplate = null;
$finalTemplate = null;

foreach ($templates as $template) {

    if (($template['jenis_laporan'] ?? '') === LaporanMagang::WEEKLY) {
        $weeklyTemplate = $template;
    }

    if (($template['jenis_laporan'] ?? '') === LaporanMagang::FINAL) {
        $finalTemplate = $template;
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . '/../../../../components/dashboard/mahasiswa/Head.php'; ?>

<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">

    <?php include __DIR__ . '/../../../../components/dashboard/mahasiswa/Sidebar.php'; ?>

    <?php include __DIR__ . '/../../../../components/dashboard/mahasiswa/Header.php'; ?>


    <!-- =========================================================
         MAIN CONTENT
    ========================================================== -->
    <main class="lg:ml-64 pt-20 min-h-screen">

        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">


            <!-- =================================================
                 BACK
            ================================================== -->
            <a
                href="<?= e(
                    url(
                        '/dashboard/mahasiswa/laporan?penempatan='
                        . (int) $placement['id']
                    )
                ) ?>"
                class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 transition hover:text-blue-700"
            >
                <span>←</span>
                Kembali
            </a>


            <!-- =================================================
                 CARD
            ================================================== -->
            <div class="mt-5 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">


                <!-- =================================================
                     HEADER
                ================================================== -->
                <div class="mb-7">

                    <h1 class="text-xl font-bold text-gray-900 sm:text-2xl">
                        Buat Laporan
                    </h1>

                    <p class="mt-1 text-sm leading-6 text-gray-500">
                        Pilih jenis laporan dan isi informasi laporan sebelum membuat draft.
                    </p>

                </div>


                <!-- =================================================
                     ERROR
                ================================================== -->
                <?php if (!empty($error)): ?>

                    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        <?= e($error) ?>
                    </div>

                <?php endif; ?>


                <!-- =================================================
                     FORM
                ================================================== -->
                <form
                    action="<?= e($formAction) ?>"
                    method="POST"
                    enctype="multipart/form-data"
                    class="space-y-6"
                >

                    <!-- =================================================
                         CSRF
                    ================================================== -->
                    <input
                        type="hidden"
                        name="_csrf_token"
                        value="<?= e(csrfToken()) ?>"
                    >


                    <!-- =================================================
                         JENIS LAPORAN
                    ================================================== -->
                    <div>

                        <label
                            for="jenis-laporan"
                            class="mb-2 block text-sm font-semibold text-gray-700"
                        >
                            Jenis Laporan
                        </label>

                        <select
                            name="jenis_laporan"
                            id="jenis-laporan"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                            required
                        >

                            <option
                                value="laporan_mingguan"
                                <?= $form['jenis_laporan'] === 'laporan_mingguan'
                                    ? 'selected'
                                    : '' ?>
                            >
                                Laporan Mingguan
                            </option>

                            <option
                                value="laporan_akhir"
                                <?= $form['jenis_laporan'] === 'laporan_akhir'
                                    ? 'selected'
                                    : '' ?>
                                <?= !$finalOpen ? 'disabled' : '' ?>
                            >
                                Laporan Akhir<?= !$finalOpen
                                    ? ' — belum terbuka'
                                    : '' ?>
                            </option>

                        </select>

                    </div>


                    <!-- =================================================
                         MINGGU KE
                    ================================================== -->
                    <div id="minggu-field">

                        <label
                            for="minggu-ke"
                            class="mb-2 block text-sm font-semibold text-gray-700"
                        >
                            Minggu Ke-
                        </label>


                        <?php if (!empty($logbookWeeks)): ?>

                            <select
                                id="minggu-ke"
                                name="minggu_ke"
                                class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                                required
                            >

                                <?php foreach ($logbookWeeks as $week): ?>

                                    <option
                                        value="<?= (int) $week['minggu_ke'] ?>"
                                        <?= (int) $form['minggu_ke']
                                            === (int) $week['minggu_ke']
                                            ? 'selected'
                                            : '' ?>
                                    >
                                        Minggu ke-<?= (int) $week['minggu_ke'] ?>
                                        —
                                        <?= e($week['tanggal_mulai']) ?>
                                        s/d
                                        <?= e($week['tanggal_selesai']) ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>


                            <p class="mt-2 text-xs leading-5 text-gray-500">
                                Pilih minggu Logbook yang akan digunakan
                                sebagai sumber kegiatan laporan.
                            </p>

                        <?php else: ?>

                            <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-700">
                                Belum ada Logbook mingguan untuk penempatan ini.
                            </div>

                        <?php endif; ?>

                    </div>


                    <!-- =================================================
                         KEGIATAN MAGANG DARI LOGBOOK
                    ================================================== -->
                    <?php if ($form['jenis_laporan'] === LaporanMagang::WEEKLY): ?>

                        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5">


                            <!-- Header -->
                            <div class="flex items-start gap-3">

                                <!-- Icon -->
                                <div class="mt-0.5 shrink-0 text-blue-600">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                        />
                                    </svg>

                                </div>


                                <!-- Text -->
                                <div class="min-w-0 flex-1">

                                    <h2 class="text-sm font-semibold text-gray-800">
                                        Kegiatan Magang
                                    </h2>

                                    <p class="mt-1 text-xs leading-5 text-gray-500">
                                        Kegiatan berikut diambil otomatis dari
                                        Logbook pada minggu yang dipilih.
                                    </p>

                                </div>

                            </div>


                            <!-- =================================================
                                 ADA LOGBOOK DAN KEGIATAN
                            ================================================== -->
                            <?php if ($selectedLogbook && !empty($weeklyActivities)): ?>

                                <div class="mt-4 overflow-hidden rounded-xl border border-gray-200 bg-white">

                                    <div class="overflow-x-auto">

                                        <table class="min-w-full text-sm">

                                            <thead class="bg-gray-50">

                                                <tr>

                                                    <th
                                                        class="px-4 py-3 text-left font-semibold text-gray-600"
                                                    >
                                                        No.
                                                    </th>

                                                    <th
                                                        class="px-4 py-3 text-left font-semibold text-gray-600"
                                                    >
                                                        Tanggal
                                                    </th>

                                                    <th
                                                        class="px-4 py-3 text-left font-semibold text-gray-600"
                                                    >
                                                        Kegiatan
                                                    </th>

                                                </tr>

                                            </thead>


                                            <tbody class="divide-y divide-gray-100">

                                                <?php foreach (
                                                    $weeklyActivities
                                                    as $index => $activity
                                                ): ?>

                                                    <tr>

                                                        <!-- No -->
                                                        <td class="px-4 py-3 align-top text-gray-500">
                                                            <?= $index + 1 ?>
                                                        </td>


                                                        <!-- Tanggal -->
                                                        <td class="whitespace-nowrap px-4 py-3 align-top font-medium text-gray-700">

                                                            <?php
                                                            $tanggal = $activity['tanggal'] ?? null;
                                                            ?>

                                                            <?= $tanggal
                                                                ? e(
                                                                    date(
                                                                        'd M Y',
                                                                        strtotime($tanggal)
                                                                    )
                                                                )
                                                                : '-'
                                                            ?>

                                                        </td>


                                                        <!-- Kegiatan -->
                                                        <td class="px-4 py-3 align-top text-gray-600">

                                                            <?= nl2br(
                                                                e(
                                                                    $activity['kegiatan']
                                                                    ?? '-'
                                                                )
                                                            ) ?>

                                                        </td>

                                                    </tr>

                                                <?php endforeach; ?>

                                            </tbody>

                                        </table>

                                    </div>

                                </div>


                            <!-- =================================================
                                 LOGBOOK ADA TAPI BELUM ADA KEGIATAN
                            ================================================== -->
                            <?php elseif ($selectedLogbook): ?>

                                <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-700">

                                    Logbook minggu ke-
                                    <?= (int) $selectedWeek ?>
                                    belum memiliki kegiatan.

                                </div>


                            <!-- =================================================
                                 LOGBOOK BELUM ADA
                            ================================================== -->
                            <?php else: ?>

                                <div class="mt-4 rounded-xl border border-gray-200 bg-white p-4 text-sm text-gray-500">

                                    Logbook untuk minggu ke-
                                    <?= (int) $selectedWeek ?>
                                    belum tersedia.

                                </div>

                            <?php endif; ?>

                        </div>

                    <?php endif; ?>


                    <!-- =================================================
                         JUDUL LAPORAN
                    ================================================== -->
                    <div>

                        <label
                            for="judul"
                            class="mb-2 block text-sm font-semibold text-gray-700"
                        >
                            Judul Laporan
                        </label>

                        <input
                            type="text"
                            id="judul"
                            name="judul"
                            maxlength="200"
                            value="<?= e($form['judul']) ?>"
                            placeholder="Masukkan judul laporan"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                            required
                        >

                    </div>


                    <!-- =================================================
                         TEMPLATE INFORMATION
                    ================================================== -->
                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">

                        <div class="flex items-start gap-3">


                            <!-- Icon -->
                            <div class="mt-0.5 shrink-0 text-blue-600">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                    />
                                </svg>

                            </div>


                            <!-- Information -->
                            <div class="min-w-0">

                                <h2 class="text-sm font-semibold text-gray-800">
                                    Template Laporan
                                </h2>

                                <div class="mt-2 space-y-2 text-sm text-gray-600">


                                    <!-- Template Mingguan -->
                                    <div>

                                        <span class="font-medium text-gray-700">
                                            Template mingguan:
                                        </span>


                                        <?php if ($weeklyTemplate): ?>

                                            <span>
                                                <?= e(
                                                    $weeklyTemplate['nama_template']
                                                ) ?>
                                            </span>

                                        <?php else: ?>

                                            <span class="text-gray-400">
                                                Belum tersedia
                                            </span>

                                        <?php endif; ?>

                                    </div>


                                    <!-- Template Akhir -->
                                    <div>

                                        <span class="font-medium text-gray-700">
                                            Template akhir:
                                        </span>


                                        <?php if ($finalTemplate): ?>

                                            <span>
                                                <?= e(
                                                    $finalTemplate['nama_template']
                                                ) ?>
                                            </span>

                                        <?php else: ?>

                                            <span class="text-gray-400">
                                                Belum tersedia
                                            </span>

                                        <?php endif; ?>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- =================================================
                         SUBMIT
                    ================================================== -->
                    <div class="pt-1">

                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        >
                            Buat Draft Laporan
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </main>


    <!-- =========================================================
         JAVASCRIPT
    ========================================================== -->
    <script>

        const type = document.getElementById('jenis-laporan');
        const weekField = document.getElementById('minggu-field');
        const weekInput = document.getElementById('minggu-ke');


        /**
         * Tampilkan / sembunyikan field minggu
         * berdasarkan jenis laporan.
         */
        function syncWeekField() {

            if (!type || !weekField) {
                return;
            }

            const weekly = type.value === 'laporan_mingguan';

            weekField.classList.toggle(
                'hidden',
                !weekly
            );

            if (weekInput) {
                weekInput.required = weekly;
            }
        }


        /**
         * Ketika jenis laporan berubah.
         */
        if (type) {

            type.addEventListener(
                'change',
                syncWeekField
            );

        }


        /**
         * Ketika minggu Logbook berubah,
         * reload halaman agar controller mengambil
         * kegiatan dari minggu yang dipilih.
         */
        if (weekInput) {

            weekInput.addEventListener(
                'change',
                function () {

                    if (
                        !type ||
                        type.value !== 'laporan_mingguan'
                    ) {
                        return;
                    }

                    const url = new URL(
                        window.location.href
                    );

                    url.searchParams.set(
                        'minggu',
                        this.value
                    );

                    window.location.href =
                        url.toString();

                }
            );

        }


        /**
         * Jalankan saat halaman pertama kali dibuka.
         */
        syncWeekField();

    </script>

</body>

</html>