<?php
$base = url('/');
$active = 'bimbingan';

$studentName = (string) ($placement['mahasiswa_nama'] ?? 'Mahasiswa');
$nim = (string) ($placement['nim'] ?? '-');
$prodi = (string) ($placement['program_studi'] ?? '-');
$status = (string) ($placement['status_penempatan'] ?? '');
$statusLabel = (string) ($placement['status_label'] ?? 'Belum tersedia');

$startDate = !empty($placement['tanggal_mulai'])
    ? date('d M Y', strtotime((string) $placement['tanggal_mulai']))
    : '-';

$endDate = !empty($placement['tanggal_selesai'])
    ? date('d M Y', strtotime((string) $placement['tanggal_selesai']))
    : '-';

$initials = function_exists('initials')
    ? initials($studentName)
    : strtoupper(substr($studentName, 0, 2));

[$badgeClass, $dotClass] = match ($status) {
    'berlangsung' => ['bg-emerald-50 text-emerald-700', 'bg-emerald-500'],
    'selesai' => ['bg-violet-50 text-violet-700', 'bg-violet-500'],
    'dibatalkan' => ['bg-red-50 text-red-700', 'bg-red-500'],
    default => ['bg-amber-50 text-amber-700', 'bg-amber-500'],
};
?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . '/../../../../components/dashboard/dosen/Head.php'; ?>

<body class="overflow-x-hidden font-[Poppins] text-gray-900 bg-slate-50">

    <?php include __DIR__ . '/../../../../components/dashboard/dosen/Sidebar.php'; ?>
    <?php include __DIR__ . '/../../../../components/dashboard/dosen/Header.php'; ?>

    <main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">

        <div class="px-4 py-6 mx-auto sm:px-6 sm:py-8 lg:px-8 max-w-[1600px]">

            <!-- Breadcrumb -->
            <div class="mb-6">
                <a href="<?= e(url('/dashboard/dosen/bimbingan')) ?>"
                    class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-blue-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="m15 18-6-6 6-6" />
                    </svg>

                    Kembali
                </a>
            </div>
            <!-- Header Profil -->
            <section class="relative mb-6 overflow-hidden bg-white border border-slate-200 rounded-2xl">

                <div class="h-28 bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-600"></div>

                <div class="relative px-5 pb-6 sm:px-7">

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-end">

                        <div class="flex items-center justify-center flex-shrink-0 w-20 h-20 -mt-10 text-2xl font-bold text-blue-700 bg-blue-50 border-4 border-white rounded-2xl shadow-sm">
                            <?= e($initials) ?>
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold tracking-wider text-blue-700 uppercase">
                                Profil Mahasiswa Bimbingan
                            </p>

                            <h1 class="mt-1 text-xl font-bold text-slate-900 sm:text-2xl">
                                <?= e($studentName) ?>
                            </h1>

                            <p class="mt-1 text-sm text-slate-500">
                                <?= e($nim) ?> <span class="mx-1">·</span> <?= e($prodi) ?>
                            </p>
                        </div>

                        <div class="flex items-center gap-2 px-3 py-2 text-xs font-semibold rounded-xl <?= e($badgeClass) ?>">
                            <span class="w-2 h-2 rounded-full <?= e($dotClass) ?>"></span>
                            <?= e($statusLabel) ?>
                        </div>

                    </div>

                </div>
            </section>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                <!-- Kolom Kiri -->
                <div class="space-y-6 lg:col-span-2">

                    <!-- Informasi Akademik -->
                    <section class="p-5 bg-white border border-slate-200 rounded-2xl sm:p-6">

                        <div class="flex items-center gap-3 mb-5">
                            <div class="flex items-center justify-center w-10 h-10 text-blue-700 bg-blue-50 rounded-xl">
                                <!-- Graduation Cap Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m22 10-10-5L2 10l10 5 10-5Z" />
                                    <path d="M6 12v5c3.3 2.7 8.7 2.7 12 0v-5" />
                                    <path d="M22 10v6" />
                                </svg>
                            </div>

                            <div>
                                <h2 class="font-semibold text-slate-900">
                                    Informasi Akademik
                                </h2>
                                <p class="text-xs text-slate-500">
                                    Identitas mahasiswa yang dibimbing
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            <div>
                                <p class="text-xs text-slate-400">Nama Lengkap</p>
                                <p class="mt-1 text-sm font-semibold text-slate-800">
                                    <?= e($studentName) ?>
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-slate-400">Nomor Induk Mahasiswa</p>
                                <p class="mt-1 text-sm font-semibold text-slate-800">
                                    <?= e($nim) ?>
                                </p>
                            </div>

                            <div class="sm:col-span-2">
                                <p class="text-xs text-slate-400">Program Studi</p>
                                <p class="mt-1 text-sm font-semibold text-slate-800">
                                    <?= e($prodi) ?>
                                </p>
                            </div>

                        </div>
                    </section>

                    <!-- Informasi Penempatan -->
                    <section class="p-5 bg-white border border-slate-200 rounded-2xl sm:p-6">

                        <div class="flex items-center gap-3 mb-5">
                            <div class="flex items-center justify-center w-10 h-10 text-indigo-700 bg-indigo-50 rounded-xl">
                                <!-- Building Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="18" height="18" rx="2" />
                                    <path d="M9 21V9h6v12" />
                                    <path d="M7 7h.01M17 7h.01M7 11h.01M17 11h.01" />
                                </svg>
                            </div>

                            <div>
                                <h2 class="font-semibold text-slate-900">
                                    Informasi Penempatan Magang
                                </h2>
                                <p class="text-xs text-slate-500">
                                    Lokasi dan periode pelaksanaan magang
                                </p>
                            </div>
                        </div>

                        <div class="space-y-5">

                            <div>
                                <p class="text-xs text-slate-400">Nama Perusahaan / Mitra</p>
                                <p class="mt-1 text-sm font-semibold text-slate-800">
                                    <?= e((string) ($placement['nama_perusahaan'] ?? '-')) ?>
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-slate-400">Posisi / Judul Magang</p>
                                <p class="mt-1 text-sm font-semibold text-slate-800">
                                    <?= e((string) ($placement['judul'] ?? '-')) ?>
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-slate-400">Lokasi Perusahaan</p>
                                <p class="mt-1 text-sm font-semibold text-slate-800">
                                    <?= e((string) ($placement['lokasi_perusahaan'] ?? '-')) ?>
                                </p>
                            </div>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                                <div class="p-4 border border-slate-100 bg-slate-50 rounded-xl">
                                    <div class="flex items-center gap-2 text-xs text-slate-500">
                                        <!-- Calendar Check Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                                            class="text-emerald-600">
                                            <rect x="3" y="4" width="18" height="17" rx="2" />
                                            <path d="M16 2v4M8 2v4M3 10h18" />
                                            <path d="m9 16 2 2 4-4" />
                                        </svg>
                                        Tanggal Mulai
                                    </div>

                                    <p class="mt-2 text-sm font-semibold text-slate-800">
                                        <?= e($startDate) ?>
                                    </p>
                                </div>

                                <div class="p-4 border border-slate-100 bg-slate-50 rounded-xl">
                                    <div class="flex items-center gap-2 text-xs text-slate-500">
                                        <!-- Calendar X Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                                            class="text-red-500">
                                            <rect x="3" y="4" width="18" height="17" rx="2" />
                                            <path d="M16 2v4M8 2v4M3 10h18" />
                                            <path d="m10 14 4 4m0-4-4 4" />
                                        </svg>
                                        Tanggal Selesai
                                    </div>

                                    <p class="mt-2 text-sm font-semibold text-slate-800">
                                        <?= e($endDate) ?>
                                    </p>
                                </div>

                            </div>
                        </div>
                    </section>

                </div>

                <!-- Kolom Kanan -->
                <div class="space-y-6">

                    <!-- Ringkasan Bimbingan -->
                    <section class="p-5 bg-white border border-slate-200 rounded-2xl">

                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex items-center justify-center w-10 h-10 text-emerald-700 bg-emerald-50 rounded-xl">
                                <!-- Users / Teaching Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="10" cy="7" r="4" />
                                    <path d="M20 21v-2a4 4 0 0 0-3-3.87" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg>
                            </div>

                            <h2 class="font-semibold text-slate-900">
                                Ringkasan Bimbingan
                            </h2>
                        </div>

                        <p class="text-sm leading-relaxed text-slate-500">
                            Mahasiswa ini terdaftar sebagai mahasiswa bimbingan Anda
                            dalam kegiatan penempatan magang.
                        </p>

                        <div class="pt-4 mt-4 border-t border-slate-100">
                            <p class="text-xs text-slate-400">ID Penempatan</p>
                            <p class="mt-1 text-sm font-semibold text-slate-800">
                                #<?= e((string) ($placement['penempatan_id'] ?? '-')) ?>
                            </p>
                        </div>

                    </section>

                    <!-- Aksi -->
                    <section class="p-5 bg-white border border-slate-200 rounded-2xl">

                        <h2 class="font-semibold text-slate-900">
                            Aksi
                        </h2>

                        <p class="mt-1 mb-4 text-xs text-slate-500">
                            Pilih tindakan untuk mahasiswa ini.
                        </p>

                        <?php if ($status === 'menunggu_penilaian'): ?>

                            <a href="<?= e(url('/dashboard/dosen/penilaian')) ?>"
                                class="flex items-center justify-center w-full gap-2 px-4 py-3 text-sm font-semibold text-white transition bg-blue-600 rounded-xl hover:bg-blue-700">

                                <!-- Clipboard Check Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="4" y="4" width="16" height="18" rx="2" />
                                    <path d="M9 4.5V3h6v1.5" />
                                    <path d="m8 13 2 2 5-5" />
                                </svg>

                                Buka Penilaian
                            </a>

                        <?php else: ?>

                            <div class="flex items-center gap-2 p-3 text-xs leading-relaxed text-slate-500 bg-slate-50 rounded-xl">

                                <!-- Info Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                                    class="shrink-0 text-blue-500">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 16v-4M12 8h.01" />
                                </svg>

                                Belum ada tindakan penilaian yang tersedia untuk status penempatan ini.
                            </div>

                        <?php endif; ?>

                        <a href="<?= e(url('/dashboard/dosen/bimbingan')) ?>"
                            class="flex items-center justify-center w-full gap-2 px-4 py-3 mt-3 text-sm font-semibold transition border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50">

                            <!-- Arrow Left Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m15 18-6-6 6-6" />
                            </svg>

                            Kembali ke Daftar
                        </a>

                    </section>

                </div>

            </div>

        </div>

    </main>

</body>

</html>