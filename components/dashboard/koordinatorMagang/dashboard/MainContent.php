<main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">
    <div class="px-4 py-6 mx-auto sm:px-6 sm:py-8 lg:px-8 max-w-[1600px]">
        <div class="mb-6 sm:mb-8">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Dashboard Koordinator Magang</h1>
            <p class="mt-2 text-sm text-gray-500 sm:text-base">Ringkasan pengajuan, penempatan, dan kebutuhan pembimbing berdasarkan rekap database.</p>
        </div>

        <?php $stats = $dashboard['statistics'] ?? []; ?>
        <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 sm:gap-6 xl:grid-cols-4 sm:mb-8">
            <?php foreach ([
                ['Total Pengajuan', 'total_pengajuan', 'Semua proses pendaftaran'],
                ['Mahasiswa', 'total_mahasiswa', 'Mahasiswa pada rekap magang'],
                ['Pengajuan Aktif', 'pengajuan_aktif', 'Belum selesai diproses'],
                ['Tanpa Pembimbing', 'tanpa_pembimbing', 'Perlu penetapan dosen'],
            ] as [$label, $key, $caption]): ?>
                <div class="p-5 bg-white border border-gray-100 shadow-sm sm:p-6 rounded-2xl">
                    <p class="text-sm font-medium text-gray-500"><?= e($label) ?></p>
                    <p class="mt-3 text-3xl font-bold text-gray-900"><?= e((string) ($stats[$key] ?? 0)) ?></p>
                    <p class="mt-2 text-sm text-gray-400"><?= e($caption) ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <section class="overflow-hidden bg-white border border-gray-100 shadow-sm rounded-2xl">
            <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 border-b border-gray-100 sm:px-7">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Rekap Magang Terbaru</h2>
                    <p class="mt-1 text-sm text-gray-500">Pendaftaran dan penempatan mahasiswa dari view rekap koordinator.</p>
                </div>
                <a href="<?= e(url('/dashboard/koordinator-magang/logbook')) ?>" class="text-sm font-medium text-blue-600 hover:text-blue-700">Monitoring Logbook →</a>
            </div>
            <?php $recent = $dashboard['recent'] ?? []; ?>
            <?php if ($recent): ?>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[920px] text-sm text-left">
                        <thead class="text-xs uppercase bg-gray-50 text-gray-500"><tr>
                            <th class="px-5 py-3 font-semibold">Mahasiswa</th>
                            <th class="px-5 py-3 font-semibold">Perusahaan / Formasi</th>
                            <th class="px-5 py-3 font-semibold">Pengajuan</th>
                            <th class="px-5 py-3 font-semibold">Penempatan</th>
                            <th class="px-5 py-3 font-semibold">Dosen Pembimbing</th>
                            <th class="px-5 py-3 font-semibold">Logbook</th>
                            <th class="px-5 py-3 font-semibold">Validasi</th>
                        </tr></thead>
                        <tbody class="divide-y divide-gray-100">
                        <?php foreach ($recent as $row): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4"><p class="font-medium text-gray-900"><?= e((string) ($row['nama_mahasiswa'] ?? '-')) ?></p><p class="mt-1 text-xs text-gray-500"><?= e((string) ($row['nim'] ?? '-')) ?> · <?= e((string) ($row['program_studi'] ?? '-')) ?></p></td>
                                <td class="px-5 py-4"><p class="font-medium text-gray-800"><?= e((string) ($row['nama_perusahaan'] ?? '-')) ?></p><p class="mt-1 text-xs text-gray-500"><?= e((string) ($row['nama_formasi'] ?? '-')) ?></p></td>
                                <td class="px-5 py-4"><?= e(ucfirst(str_replace('_', ' ', (string) ($row['status_pendaftaran'] ?? '-')))) ?></td>
                                <td class="px-5 py-4"><?= e(ucfirst(str_replace('_', ' ', (string) ($row['status_penempatan'] ?? 'belum ditempatkan')))) ?></td>
                                <td class="px-5 py-4"><?= e((string) ($row['nama_dosen_pembimbing'] ?? 'Belum ditetapkan')) ?></td>
                                <td class="px-5 py-4"><?= e((string) ($row['total_minggu'] ?? 0)) ?> minggu</td>
                                <td class="px-5 py-4"><?= e((string) ($row['validasi_menunggu'] ?? 0)) ?> menunggu</td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="px-5 py-12 text-center">
                    <p class="text-sm font-medium text-gray-700">Belum ada data pengajuan magang.</p>
                    <p class="mt-1 text-xs text-gray-500">Rekap akan terisi ketika pendaftaran dibuat.</p>
                </div>
            <?php endif; ?>
        </section>

        <?php include __DIR__ . '/../Footer.php'; ?>
    </div>
</main>
