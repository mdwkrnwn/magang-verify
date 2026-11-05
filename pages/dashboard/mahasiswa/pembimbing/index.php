<?php

$base = url('/');
$active = 'pembimbing';

?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . '/../../../../components/dashboard/mahasiswa/Head.php'; ?>

<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">

    <?php include __DIR__ . '/../../../../components/dashboard/mahasiswa/Sidebar.php'; ?>
    <?php include __DIR__ . '/../../../../components/dashboard/mahasiswa/Header.php'; ?>

    <main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">

            <div class="px-4 py-6 mx-auto sm:px-6 sm:py-8 lg:px-8 max-w-[1600px]">

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">
                    Dosen Pembimbing
                </h1>

                <p class="mt-2 text-sm text-gray-500">
                    Informasi dosen pembimbing yang telah ditetapkan oleh Koordinator Magang.
                </p>
            </div>

            <!-- Informasi Penempatan -->
            <section class="p-5 bg-white border border-gray-100 shadow-sm sm:p-7 rounded-2xl">

                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">
                            Informasi Penempatan Magang
                        </h2>

                        <p class="mt-1 text-xs text-gray-500">
                            Daftar penempatan dan dosen pembimbing Anda.
                        </p>
                    </div>

                    <div class="flex items-center justify-center w-11 h-11 text-blue-600 bg-blue-50 rounded-xl">
                        <i class="fas fa-user-graduate text-lg"></i>
                    </div>
                </div>

                <div class="space-y-5">

                    <?php if (!empty($placements)): ?>

                        <?php foreach ($placements as $placement): ?>

                            <?php
                            $start = !empty($placement['tanggal_mulai'])
                                ? strtotime((string) $placement['tanggal_mulai'])
                                : false;

                            $end = !empty($placement['tanggal_selesai'])
                                ? strtotime((string) $placement['tanggal_selesai'])
                                : false;

                            $status = (string) ($placement['status_penempatan'] ?? '');

                            $statusClass = match ($status) {
                                'berlangsung' => 'text-emerald-700 bg-emerald-50',
                                'persiapan' => 'text-blue-700 bg-blue-50',
                                'selesai' => 'text-gray-600 bg-gray-100',
                                default => 'text-gray-600 bg-gray-100'
                            };

                            $dosenNama = $placement['dosen_nama'] ?? null;
                            $dosenNidn = $placement['dosen_nidn'] ?? null;
                            ?>

                            <article class="overflow-hidden border border-gray-200 rounded-2xl">

                                <!-- Informasi Magang -->
                                <div class="p-5 sm:p-6">

                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                                        <div class="min-w-0">

                                            <div class="flex flex-wrap items-center gap-2 mb-2">

                                                <span class="px-3 py-1 text-xs font-medium rounded-full <?= e($statusClass) ?>">
                                                    <?= e(ucfirst(str_replace('_', ' ', $status ?: 'Belum diketahui'))) ?>
                                                </span>

                                            </div>

                                            <h3 class="text-lg font-bold text-gray-900">
                                                <?= e((string) ($placement['judul'] ?? '-')) ?>
                                            </h3>

                                            <p class="mt-1 text-sm text-gray-500">
                                                <i class="fas fa-building mr-1.5"></i>
                                                <?= e((string) ($placement['nama_perusahaan'] ?? '-')) ?>
                                            </p>

                                            <p class="mt-3 text-xs text-gray-400">
                                                <i class="far fa-calendar-alt mr-1.5"></i>

                                                <?= $start && $end
                                                    ? e(date('d M Y', $start) . ' – ' . date('d M Y', $end))
                                                    : 'Jadwal belum tersedia'
                                                ?>
                                            </p>

                                        </div>

                                    </div>

                                </div>

                                <!-- Informasi Dosen -->
                                <div class="px-5 py-5 border-t border-gray-100 bg-slate-50 sm:px-6">

                                    <p class="mb-4 text-xs font-semibold tracking-wider text-gray-400 uppercase">
                                        Dosen Pembimbing
                                    </p>

                                    <?php if (!empty($placement['dosen_pembimbing_id']) && $dosenNama): ?>

                                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center">

                                            <div class="flex items-center justify-center flex-shrink-0 w-14 h-14 text-blue-600 bg-blue-100 rounded-2xl">
                                                <i class="fas fa-user-tie text-2xl"></i>
                                            </div>

                                            <div class="min-w-0 flex-1">

                                                <h4 class="text-base font-bold text-gray-900">
                                                    <?= e((string) $dosenNama) ?>
                                                </h4>

                                                <p class="mt-1 text-sm text-gray-500">
                                                    <?= !empty($dosenNidn)
                                                        ? 'NIDN: ' . e((string) $dosenNidn)
                                                        : 'Dosen Pembimbing Magang'
                                                    ?>
                                                </p>

                                            </div>

                                            <span class="inline-flex items-center gap-2 px-3 py-2 text-xs font-medium text-emerald-700 bg-emerald-50 rounded-xl">
                                                <i class="fas fa-check-circle"></i>
                                                Telah Ditetapkan
                                            </span>

                                        </div>

                                    <?php else: ?>

                                        <div class="flex items-center gap-4">

                                            <div class="flex items-center justify-center flex-shrink-0 w-14 h-14 text-amber-600 bg-amber-100 rounded-2xl">
                                                <i class="fas fa-user-clock text-2xl"></i>
                                            </div>

                                            <div>
                                                <h4 class="text-sm font-semibold text-gray-800">
                                                    Belum Ada Dosen Pembimbing
                                                </h4>

                                                <p class="mt-1 text-xs text-gray-500">
                                                    Dosen pembimbing belum ditetapkan oleh Koordinator Magang.
                                                </p>
                                            </div>

                                        </div>

                                    <?php endif; ?>

                                </div>

                            </article>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <!-- Empty State -->
                        <div class="px-4 py-14 text-center border border-dashed border-gray-200 rounded-2xl">

                            <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 text-gray-400 bg-gray-100 rounded-2xl">
                                <i class="fas fa-clipboard-list text-2xl"></i>
                            </div>

                            <h3 class="text-base font-semibold text-gray-800">
                                Belum Ada Penempatan Magang
                            </h3>

                            <p class="max-w-sm mx-auto mt-2 text-sm text-gray-500">
                                Informasi dosen pembimbing akan ditampilkan setelah Anda memiliki penempatan magang.
                            </p>

                        </div>

                    <?php endif; ?>

                </div>

            </section>

            <!-- Informasi Tambahan -->
            <div class="flex items-start gap-3 p-4 mt-5 border border-blue-100 bg-blue-50 rounded-xl">

                <i class="mt-0.5 text-blue-600 fas fa-info-circle"></i>

                <p class="text-xs leading-relaxed text-blue-800">
                    Dosen pembimbing ditentukan oleh Koordinator Magang.
                    Silakan hubungi Koordinator Magang apabila terdapat ketidaksesuaian informasi dosen pembimbing.
                </p>

            </div>

        </div>

    </main>

</body>

</html>