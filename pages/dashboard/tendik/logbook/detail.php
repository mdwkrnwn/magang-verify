<?php
$base = url('/');
$active = 'logbook';
?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . '/../../../../components/dashboard/tendik/Head.php'; ?>

<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">

    <?php include __DIR__ . '/../../../../components/dashboard/tendik/Sidebar.php'; ?>
    <?php include __DIR__ . '/../../../../components/dashboard/tendik/Header.php'; ?>

    <main class="min-h-screen bg-slate-50 pt-20 lg:ml-64">
        <div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

            <!-- Header -->
            <div class="mb-6">
                <a href="<?= e(url('/dashboard/tendik/logbook')) ?>"
                    class="inline-flex items-center gap-2 text-sm text-gray-500 transition hover:text-blue-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Validasi Logbook
                </a>

                <div class="mt-4">
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">
                        Validasi Logbook
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Pemeriksaan kehadiran mahasiswa pada minggu ke-<?= e($week['minggu_ke']) ?>.
                    </p>
                </div>
            </div>

            <!-- Student Information -->
            <section class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-gray-400">
                            Mahasiswa
                        </p>
                        <h2 class="mt-1 text-lg font-semibold text-gray-900">
                            <?= e($week['mahasiswa_nama']) ?>
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            NIM <?= e($week['nim']) ?>
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-4 border-t border-slate-100 pt-4 sm:grid-cols-2 sm:gap-10 lg:border-l lg:border-t-0 lg:pl-8 lg:pt-0">
                        <div>
                            <p class="text-xs text-gray-400">Perusahaan</p>
                            <p class="mt-1 text-sm font-medium text-gray-800">
                                <?= e($week['nama_perusahaan']) ?>
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400">Periode Logbook</p>
                            <p class="mt-1 text-sm font-medium text-gray-800">
                                Minggu ke-<?= e($week['minggu_ke']) ?>
                            </p>
                        </div>
                    </div>

                </div>
            </section>

            <!-- Daily Logbook -->
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="flex flex-col gap-1 border-b border-slate-100 px-5 py-5 sm:px-6">
                    <h2 class="text-base font-semibold text-gray-900">
                        Rincian Kehadiran Harian
                    </h2>
                    <p class="text-sm text-gray-500">
                        Periksa alasan dan bukti ketidakhadiran sebelum memberikan keputusan.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1050px] text-sm">

                        <thead>
                            <tr class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-gray-500">
                                <th class="w-40 px-5 py-4 text-left">Tanggal</th>
                                <th class="w-36 px-5 py-4 text-left">Kehadiran</th>
                                <th class="px-5 py-4 text-left">Kegiatan / Alasan</th>
                                <th class="w-32 px-5 py-4 text-left">Bukti</th>
                                <th class="w-64 px-5 py-4 text-left">Keputusan Validasi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">

                            <?php foreach ($week['daily'] as $day): ?>
                                <tr class="align-top hover:bg-slate-50/60">

                                    <!-- Date -->
                                    <td class="px-5 py-5">
                                        <p class="font-medium text-gray-800">
                                            <?= e(date('D, d M Y', strtotime($day['tanggal']))) ?>
                                        </p>
                                    </td>

                                    <!-- Attendance -->
                                    <td class="px-5 py-5">
                                        <?php if ($day['status_kehadiran'] === 'hadir'): ?>
                                            <span class="font-medium text-emerald-700">
                                                Hadir
                                            </span>
                                        <?php else: ?>
                                            <span class="font-medium text-amber-700">
                                                Tidak hadir
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Activity -->
                                    <td class="px-5 py-5 -mt-20">
                                        <p class="max-w-lg whitespace-pre-line leading-6 text-gray-700">
                                            <?= e($day['kegiatan']) ?>
                                        </p>

                                        <?php if ($day['status_kehadiran'] === 'tidak_hadir'): ?>
                                            <div class="mt-2">
                                                <p class="text-xs font-semibold text-gray-500">
                                                    Alasan ketidakhadiran
                                                </p>
                                                <p class="mt-1 max-w-lg whitespace-pre-line text-xs leading-5 text-gray-600">
                                                    <?= e($day['alasan_ketidakhadiran']) ?>
                                                </p>
                                            </div>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Evidence -->
                                    <td class="px-5 py-5">
                                        <?php if (!empty($day['bukti_path'])): ?>
                                            <a
                                                href="<?= e(url('/dashboard/logbook/file/evidence/' . (int) $day['id'])) ?>"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="inline-flex items-center gap-1.5 whitespace-nowrap font-medium text-blue-600 hover:text-blue-800">
                                                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Lihat bukti
                                            </a>
                                        <?php else: ?>
                                            <span class="text-gray-400">—</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Validation -->
                                    <td class="px-5 py-5">
                                        <?php if (
                                            $day['status_kehadiran'] === 'tidak_hadir'
                                            && $day['status_validasi'] === 'menunggu'
                                        ): ?>

                                            <form
                                                method="POST"
                                                action="<?= e(url('/dashboard/tendik/logbook/validasi/' . (int) $day['id'])) ?>"
                                                class="w-56 space-y-2">
                                                <input
                                                    type="hidden"
                                                    name="_csrf_token"
                                                    value="<?= e(csrfToken()) ?>">

                                                <input
                                                    type="hidden"
                                                    name="week_id"
                                                    value="<?= e((string) $week['id']) ?>">

                                                <textarea
                                                    name="catatan"
                                                    rows="2"
                                                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-gray-700 outline-none placeholder:text-gray-400 focus:border-blue-400 focus:ring-2 focus:ring-blue-100"
                                                    placeholder="Catatan validasi (opsional)"></textarea>

                                                <div class="flex items-center gap-2">
                                                    <button
                                                        type="submit"
                                                        name="decision"
                                                        value="disetujui"
                                                        class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-blue-700">
                                                        Setujui
                                                    </button>

                                                    <button
                                                        type="submit"
                                                        name="decision"
                                                        value="ditolak"
                                                        class="rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50">
                                                        Tolak
                                                    </button>
                                                </div>
                                            </form>

                                        <?php else: ?>

                                            <?php if ($day['status_validasi'] === 'disetujui'): ?>
                                                <span class="font-medium text-emerald-700">Disetujui</span>
                                            <?php elseif ($day['status_validasi'] === 'ditolak'): ?>
                                                <span class="font-medium text-red-600">Ditolak</span>
                                            <?php else: ?>
                                                <span class="text-gray-500">
                                                    <?= e($day['status_validasi']) ?>
                                                </span>
                                            <?php endif; ?>

                                        <?php endif; ?>
                                    </td>

                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>

                <?php if (empty($week['daily'])): ?>
                    <div class="px-5 py-14 text-center">
                        <p class="text-sm font-medium text-gray-700">
                            Belum ada data kehadiran.
                        </p>
                        <p class="mt-1 text-xs text-gray-500">
                            Data logbook harian mahasiswa akan muncul di sini.
                        </p>
                    </div>
                <?php endif; ?>

            </section>

        </div>
    </main>

</body>

</html>