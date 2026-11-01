<main class="min-h-screen bg-slate-50 pt-20 lg:ml-64">
    <div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

        <!-- Page Header -->
        <div class="mb-7">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                Dashboard Koordinator Magang
            </h1>
            <p class="mt-2 text-sm text-slate-500 sm:text-base">
                Ringkasan pengajuan, penempatan, dan kebutuhan pembimbing berdasarkan rekap database.
            </p>
        </div>

        <?php $stats = $dashboard['statistics'] ?? []; ?>

        <!-- Statistics -->
        <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            <?php foreach ([
                [
                    'label' => 'Total Pengajuan',
                    'key' => 'total_pengajuan',
                    'caption' => 'Semua proses pendaftaran',
                    'icon' => '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>'
                ],
                [
                    'label' => 'Mahasiswa',
                    'key' => 'total_mahasiswa',
                    'caption' => 'Mahasiswa pada rekap magang',
                    'icon' => '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a4 4 0 00-5.5-3.7M9 20H2v-2a4 4 0 017.5-1.7M16 3.5a4 4 0 010 7.8M8 3.5a4 4 0 000 7.8m8 8.7v-2a4 4 0 00-8 0v2"/></svg>'
                ],
                [
                    'label' => 'Pengajuan Aktif',
                    'key' => 'pengajuan_aktif',
                    'caption' => 'Belum selesai diproses',
                    'icon' => '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                ],
                [
                    'label' => 'Tanpa Pembimbing',
                    'key' => 'tanpa_pembimbing',
                    'caption' => 'Perlu penetapan dosen',
                    'icon' => '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-5a2 2 0 00-2-2H6a2 2 0 00-2 2v5a2 2 0 002 2zm10-9V7a4 4 0 00-8 0v5"/></svg>'
                ],
            ] as $stat): ?>

                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-medium text-slate-500">
                                <?= e($stat['label']) ?>
                            </p>
                            <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">
                                <?= e((string) ($stats[$stat['key']] ?? 0)) ?>
                            </p>
                            <p class="mt-2 text-xs text-slate-400">
                                <?= e($stat['caption']) ?>
                            </p>
                        </div>

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                            <?= $stat['icon'] ?>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>

        </div>

        <!-- Recent Internship Summary -->
        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <!-- Section Header -->
            <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">

                <div>
                    <h2 class="text-base font-semibold text-slate-900 sm:text-lg">
                        Rekap Magang Terbaru
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Pendaftaran dan penempatan mahasiswa dari view rekap koordinator.
                    </p>
                </div>

                <a
                    href="<?= e(url('/dashboard/koordinator-magang/logbook')) ?>"
                    class="inline-flex w-fit items-center gap-2 text-sm font-medium text-blue-600 transition hover:text-blue-700"
                >
                    Monitoring Logbook
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6l6 6-6 6"/>
                    </svg>
                </a>

            </div>

            <?php $recent = $dashboard['recent'] ?? []; ?>

            <?php if ($recent): ?>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1050px] text-left text-sm">

                        <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-6 py-4">Mahasiswa</th>
                                <th class="px-6 py-4">Perusahaan / Formasi</th>
                                <th class="px-6 py-4">Pengajuan</th>
                                <th class="px-6 py-4">Penempatan</th>
                                <th class="px-6 py-4">Dosen Pembimbing</th>
                                <th class="px-6 py-4 text-center">Logbook</th>
                                <th class="px-6 py-4 text-center">Validasi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">

                            <?php foreach ($recent as $row): ?>

                                <tr class="transition hover:bg-slate-50/70">

                                    <!-- Student -->
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-slate-900">
                                            <?= e((string) ($row['nama_mahasiswa'] ?? '-')) ?>
                                        </p>
                                        <p class="mt-1 text-xs text-slate-500">
                                            <?= e((string) ($row['nim'] ?? '-')) ?>
                                            <span class="mx-1 text-slate-300">·</span>
                                            <?= e((string) ($row['program_studi'] ?? '-')) ?>
                                        </p>
                                    </td>

                                    <!-- Company -->
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-slate-800">
                                            <?= e((string) ($row['nama_perusahaan'] ?? '-')) ?>
                                        </p>
                                        <p class="mt-1 text-xs text-slate-500">
                                            <?= e((string) ($row['nama_formasi'] ?? '-')) ?>
                                        </p>
                                    </td>

                                    <!-- Registration Status -->
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center rounded-md bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                                            <?= e(ucfirst(str_replace('_', ' ', (string) ($row['status_pendaftaran'] ?? '-')))) ?>
                                        </span>
                                    </td>

                                    <!-- Placement Status -->
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center rounded-md bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700">
                                            <?= e(ucfirst(str_replace('_', ' ', (string) ($row['status_penempatan'] ?? 'belum ditempatkan')))) ?>
                                        </span>
                                    </td>

                                    <!-- Supervisor -->
                                    <td class="px-6 py-4">
                                        <?php if (!empty($row['nama_dosen_pembimbing'])): ?>
                                            <span class="font-medium text-slate-700">
                                                <?= e((string) $row['nama_dosen_pembimbing']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-xs font-medium text-amber-600">
                                                Belum ditetapkan
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Logbook -->
                                    <td class="px-6 py-4 text-center">
                                        <span class="font-medium text-slate-700">
                                            <?= e((string) ($row['total_minggu'] ?? 0)) ?>
                                        </span>
                                        <span class="text-xs text-slate-400">minggu</span>
                                    </td>

                                    <!-- Validation -->
                                    <td class="px-6 py-4 text-center">
                                        <?php $pending = (int) ($row['validasi_menunggu'] ?? 0); ?>

                                        <span class="inline-flex items-center rounded-md px-2.5 py-1 text-xs font-medium <?= $pending > 0 ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700' ?>">
                                            <?= e((string) $pending) ?> menunggu
                                        </span>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>

                <!-- Table Footer -->
                <div class="border-t border-slate-100 px-6 py-3">
                    <p class="text-xs text-slate-500">
                        Menampilkan <?= count($recent) ?> data magang terbaru.
                    </p>
                </div>

            <?php else: ?>

                <!-- Empty State -->
                <div class="px-5 py-16 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>

                    <p class="mt-4 text-sm font-semibold text-slate-700">
                        Belum ada data pengajuan magang
                    </p>
                    <p class="mt-1 text-sm text-slate-500">
                        Rekap akan terisi ketika pendaftaran dibuat.
                    </p>
                </div>

            <?php endif; ?>

        </section>

        <?php include __DIR__ . '/../Footer.php'; ?>

    </div>
</main>