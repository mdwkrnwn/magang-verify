<?php

$active = 'verifikasi-portofolio';

$items = $portfolios['items'] ?? [];
$status = $status ?? 'belum_diverifikasi';

$statusLabels = [
    'belum_diverifikasi' => 'Belum Diverifikasi',
    'perlu_perbaikan'    => 'Perlu Perbaikan',
];

$label = $statusLabels[$status] ?? $status;

?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . '/../../../../components/dashboard/tendik/Head.php'; ?>

<body class="overflow-x-hidden bg-slate-50 font-[Poppins] text-gray-900">

    <?php include __DIR__ . '/../../../../components/dashboard/tendik/Sidebar.php'; ?>
    <?php include __DIR__ . '/../../../../components/dashboard/shared/Header.php'; ?>

    <main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">

        <div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

            <!-- Page Header -->
            <div class="flex flex-col gap-5 mb-8 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <div class="flex items-center gap-2 mb-2 text-sm font-medium text-blue-600">

                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M3 7h4l2 3h12M5 7v12a1 1 0 001 1h12a1 1 0 001-1V7" />
                        </svg>

                        Verifikasi Dokumen

                    </div>

                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">
                        Verifikasi Portofolio
                    </h1>

                    <p class="mt-2 text-sm text-gray-500 sm:text-base">
                        Periksa kelengkapan dan validitas portofolio yang diajukan mahasiswa.
                    </p>

                </div>

                <a
                    href="<?= e(url('/dashboard/tendik/verifikasi')) ?>"
                    class="inline-flex w-fit items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-600 shadow-sm transition hover:bg-gray-50 hover:text-blue-600">

                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 19l-7-7 7-7" />
                    </svg>

                    Kembali ke Ringkasan

                </a>

            </div>


            <!-- Portfolio Queue -->
            <section class="overflow-hidden bg-white border border-gray-100 shadow-sm rounded-2xl">

                <!-- Section Header -->
                <div class="flex flex-col gap-4 px-5 py-5 border-b border-gray-100 sm:flex-row sm:items-center sm:justify-between sm:px-7">

                    <div>

                        <h2 class="text-base font-semibold text-gray-900">
                            Antrean Portofolio
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Daftar dokumen yang membutuhkan pemeriksaan Tendik.
                        </p>

                    </div>

                    <span class="w-fit rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">
                        <?= e((string) ($portfolios['total'] ?? count($items))) ?> Dokumen
                    </span>

                </div>


                <!-- Filter -->
                <div class="px-5 py-4 border-b border-gray-100 sm:px-7">

                    <form method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center">

                            <label for="status" class="text-sm font-medium text-gray-600">
                                Filter Status
                            </label>

                            <select
                                id="status"
                                name="status"
                                class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:w-56">

                                <option
                                    value="belum_diverifikasi"
                                    <?= $status === 'belum_diverifikasi' ? 'selected' : '' ?>>
                                    Belum Diverifikasi
                                </option>

                                <option
                                    value="perlu_perbaikan"
                                    <?= $status === 'perlu_perbaikan' ? 'selected' : '' ?>>
                                    Perlu Perbaikan
                                </option>

                            </select>

                        </div>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">

                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M7 12h10m-7 6h4" />
                            </svg>

                            Terapkan Filter

                        </button>

                    </form>

                </div>


                <!-- Portfolio Table -->
                <?php if (!empty($items)): ?>

                    <div class="overflow-x-auto">

                        <table class="w-full min-w-[900px] text-sm">

                            <thead class="bg-gray-50/80">

                                <tr class="text-left">

                                    <th class="px-6 py-4 text-xs font-semibold tracking-wider text-gray-500 uppercase">
                                        Mahasiswa
                                    </th>

                                    <th class="px-6 py-4 text-xs font-semibold tracking-wider text-gray-500 uppercase">
                                        Informasi Portofolio
                                    </th>

                                    <th class="px-6 py-4 text-xs font-semibold tracking-wider text-gray-500 uppercase">
                                        Jenis
                                    </th>

                                    <th class="px-6 py-4 text-xs font-semibold tracking-wider text-gray-500 uppercase">
                                        Tanggal Pengajuan
                                    </th>

                                    <th class="px-6 py-4 text-xs font-semibold tracking-wider text-gray-500 uppercase">
                                        Status
                                    </th>

                                    <th class="px-6 py-4 text-xs font-semibold tracking-wider text-right text-gray-500 uppercase">
                                        Aksi
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-100">

                                <?php foreach ($items as $item): ?>

                                    <tr class="transition-colors hover:bg-gray-50/70">

                                        <!-- Student -->
                                        <td class="px-6 py-4">

                                            <div class="flex items-center gap-3">

                                                <div class="flex items-center justify-center text-sm font-semibold text-blue-600 rounded-full h-9 w-9 shrink-0 bg-blue-50">

                                                    <?= e(strtoupper(substr(
                                                        (string) ($item['mahasiswa_nama'] ?? 'M'),
                                                        0,
                                                        1
                                                    ))) ?>

                                                </div>

                                                <div>

                                                    <p class="font-medium text-gray-900">
                                                        <?= e((string) ($item['mahasiswa_nama'] ?? '-')) ?>
                                                    </p>

                                                    <p class="mt-1 text-xs text-gray-400">
                                                        <?= e((string) ($item['nim'] ?? '-')) ?>
                                                    </p>

                                                </div>

                                            </div>

                                        </td>


                                        <!-- Portfolio Information -->
                                        <td class="max-w-xs px-6 py-4">

                                            <p class="font-medium text-gray-800">
                                                <?= e((string) ($item['judul'] ?? '-')) ?>
                                            </p>

                                            <p class="mt-1 text-xs leading-relaxed text-gray-400 line-clamp-2">
                                                <?= e((string) ($item['deskripsi'] ?? '-')) ?>
                                            </p>

                                        </td>


                                        <!-- Type -->
                                        <td class="px-6 py-4">

                                            <span class="inline-flex rounded-lg bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                                                <?= e((string) ($item['jenis'] ?? '-')) ?>
                                            </span>

                                        </td>


                                        <!-- Date -->
                                        <td class="px-6 py-4 text-gray-500">

                                            <?= e((string) ($item['updated_at'] ?? $item['created_at'] ?? '-')) ?>

                                        </td>


                                        <!-- Status -->
                                        <td class="px-6 py-4">

                                            <?php if ($status === 'perlu_perbaikan'): ?>

                                                <span class="inline-flex items-center gap-2 rounded-full bg-orange-50 px-3 py-1.5 text-xs font-medium text-orange-700">

                                                    <span class="h-1.5 w-1.5 rounded-full bg-orange-500"></span>

                                                    Perlu Perbaikan

                                                </span>

                                            <?php else: ?>

                                                <span class="inline-flex items-center gap-2 rounded-full bg-amber-50 px-3 py-1.5 text-xs font-medium text-amber-700">

                                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>

                                                    Belum Diverifikasi

                                                </span>

                                            <?php endif; ?>

                                        </td>


                                        <!-- Action -->
                                        <td class="px-6 py-4 text-right">

                                            <a
                                                href="<?= e(url('/dashboard/tendik/verifikasi/portofolio/' . (int) $item['id'])) ?>"
                                                class="inline-flex items-center gap-2 px-3 py-2 text-xs font-semibold text-blue-600 transition rounded-lg hover:bg-blue-50">

                                                Buka Detail

                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5l7 7-7 7" />
                                                </svg>

                                            </a>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>


                    <!-- Table Footer -->
                    <div class="px-6 py-4 border-t border-gray-100">

                        <p class="text-xs text-gray-400">
                            Menampilkan <?= count($items) ?> dokumen
                        </p>

                    </div>

                <?php else: ?>

                    <!-- Empty State -->
                    <div class="px-5 py-16 text-center">

                        <div class="flex items-center justify-center mx-auto text-gray-400 bg-gray-100 h-14 w-14 rounded-2xl">

                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M9 12l2 2 4-4m5 2a8 8 0 11-16 0 8 8 0 0116 0z" />
                            </svg>

                        </div>

                        <h3 class="mt-4 text-sm font-semibold text-gray-900">
                            Tidak Ada Portofolio
                        </h3>

                        <p class="max-w-md mx-auto mt-1 text-sm text-gray-500">
                            Tidak ada dokumen dengan status <?= e(strtolower($label)) ?> saat ini.
                            Dokumen baru akan muncul di antrean setelah diajukan mahasiswa.
                        </p>

                    </div>

                <?php endif; ?>

            </section>


            <?php include __DIR__ . '/../../../../components/dashboard/tendik/Footer.php'; ?>

        </div>

    </main>

</body>

</html>