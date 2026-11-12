<?php
$reports = $reports ?? [];

$totalLaporan = count($reports);
$menunggu = 0;
$diterima = 0;
$revisi = 0;
$ditolak = 0;

foreach ($reports as $report) {
    $status = $report['status_pemeriksaan'] ?? 'menunggu';

    if ($status === 'diterima') {
        $diterima++;
    } elseif ($status === 'revisi') {
        $revisi++;
    } elseif ($status === 'ditolak') {
        $ditolak++;
    } else {
        $menunggu++;
    }
}
?>

<main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">
    <div class="px-4 py-6 mx-auto sm:px-6 sm:py-8 lg:px-8 max-w-[1600px]">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-sm font-medium text-blue-600 mb-1">
                    Verifikasi Laporan
                </p>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-800">
                    Verifikasi Laporan Magang
                </h1>
                <p class="text-sm text-slate-500 mt-2">
                    Periksa dan berikan keputusan terhadap laporan magang mahasiswa bimbingan.
                </p>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-5">

            <!-- Total -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5">
                <div class="flex items-center justify-between">
                    <div class="p-3 rounded-xl bg-slate-100 text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-6 h-6" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor"
                            stroke-width="1.8" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <path d="M14 2v6h6"/>
                            <path d="M8 13h8"/>
                            <path d="M8 17h8"/>
                        </svg>
                    </div>
                    <span class="text-xs text-slate-400">Total</span>
                </div>
                <p class="text-2xl font-bold text-slate-800 mt-4">
                    <?= $totalLaporan ?>
                </p>
                <p class="text-sm text-slate-500 mt-1">Total laporan</p>
            </div>

            <!-- Menunggu -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5">
                <div class="flex items-center justify-between">
                    <div class="p-3 rounded-xl bg-amber-50 text-amber-600">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-6 h-6" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor"
                            stroke-width="1.8" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 6v6l4 2"/>
                        </svg>
                    </div>
                    <span class="text-xs text-amber-600">Menunggu</span>
                </div>
                <p class="text-2xl font-bold text-slate-800 mt-4">
                    <?= $menunggu ?>
                </p>
                <p class="text-sm text-slate-500 mt-1">Belum diperiksa</p>
            </div>

            <!-- Diterima -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5">
                <div class="flex items-center justify-between">
                    <div class="p-3 rounded-xl bg-emerald-50 text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-6 h-6" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor"
                            stroke-width="1.8" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="m8 12 2.5 2.5L16 9"/>
                        </svg>
                    </div>
                    <span class="text-xs text-emerald-600">Diterima</span>
                </div>
                <p class="text-2xl font-bold text-slate-800 mt-4">
                    <?= $diterima ?>
                </p>
                <p class="text-sm text-slate-500 mt-1">Laporan diterima</p>
            </div>

            <!-- Perlu Tindak Lanjut -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5">
                <div class="flex items-center justify-between">
                    <div class="p-3 rounded-xl bg-rose-50 text-rose-600">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-6 h-6" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor"
                            stroke-width="1.8" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 8v4"/>
                            <path d="M12 16h.01"/>
                        </svg>
                    </div>
                    <span class="text-xs text-rose-600">Perlu tindak lanjut</span>
                </div>
                <p class="text-2xl font-bold text-slate-800 mt-4">
                    <?= $revisi + $ditolak ?>
                </p>
                <p class="text-sm text-slate-500 mt-1">Revisi dan ditolak</p>
            </div>

        </div>

        <!-- Report List -->
        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm mt-10">

            <div class="p-5 sm:p-6 border-b border-slate-100">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                    <div>
                        <h2 class="text-lg font-bold text-slate-800">
                            Daftar Laporan Mahasiswa
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">
                            Laporan dari mahasiswa yang berada dalam bimbingan Anda.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">

                        <!-- Search -->
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"
                                viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2"
                                stroke-linecap="round">
                                <circle cx="11" cy="11" r="8"/>
                                <path d="m21 21-4.35-4.35"/>
                            </svg>

                            <input
                                type="text"
                                id="searchLaporan"
                                placeholder="Cari mahasiswa atau laporan..."
                                class="w-full sm:w-64 pl-9 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                        </div>

                        <!-- Filter -->
                        <select
                            id="filterStatus"
                            class="px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none bg-white">
                            <option value="semua">Semua Status</option>
                            <option value="menunggu">Menunggu</option>
                            <option value="diterima">Diterima</option>
                            <option value="revisi">Revisi</option>
                            <option value="ditolak">Ditolak</option>
                        </select>

                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Mahasiswa</th>
                            <th class="px-6 py-4 font-semibold">Laporan</th>
                            <th class="px-6 py-4 font-semibold">Perusahaan</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                            <th class="px-6 py-4 font-semibold">Tanggal Pengajuan</th>
                            <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="listLaporan" class="divide-y divide-slate-100">

                        <?php if (empty($reports)): ?>

                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">

                                        <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center mb-3">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-7 h-7 text-slate-400"
                                                viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.6"
                                                stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                                <path d="M3 10h18"/>
                                            </svg>
                                        </div>

                                        <p class="font-semibold text-slate-700">
                                            Belum ada laporan magang
                                        </p>
                                        <p class="text-sm text-slate-400 mt-1">
                                            Laporan mahasiswa bimbingan akan muncul di sini.
                                        </p>
                                    </div>
                                </td>
                            </tr>

                        <?php else: ?>

                            <?php foreach ($reports as $report): ?>

                                <?php
                                $status = $report['status_pemeriksaan'] ?? 'menunggu';

                                $statusStyle = match ($status) {
                                    'diterima' => 'bg-emerald-50 text-emerald-700',
                                    'revisi' => 'bg-amber-50 text-amber-700',
                                    'ditolak' => 'bg-rose-50 text-rose-700',
                                    default => 'bg-slate-100 text-slate-600',
                                };

                                $statusLabel = match ($status) {
                                    'diterima' => 'Diterima',
                                    'revisi' => 'Perlu Revisi',
                                    'ditolak' => 'Ditolak',
                                    default => 'Menunggu',
                                };

                                $nama = $report['mahasiswa_nama'] ?? '-';
                                $judul = $report['judul_laporan'] ?? '-';
                                $perusahaan = $report['nama_perusahaan'] ?? '-';
                                $tanggal = $report['diajukan_pada'] ?? null;
                                ?>

                                <tr
                                    class="hover:bg-slate-50 transition"
                                    data-status="<?= htmlspecialchars($status) ?>"
                                    data-search="<?= htmlspecialchars(strtolower($nama . ' ' . $judul . ' ' . $perusahaan)) ?>">

                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-slate-800">
                                            <?= htmlspecialchars($nama) ?>
                                        </p>
                                        <p class="text-xs text-slate-400 mt-1">
                                            <?= htmlspecialchars($report['nim'] ?? '-') ?>
                                        </p>
                                    </td>

                                    <td class="px-6 py-4">
                                        <p class="font-medium text-slate-700">
                                            <?= htmlspecialchars($judul) ?>
                                        </p>
                                        <p class="text-xs text-slate-400 mt-1">
                                            <?= htmlspecialchars($report['jenis_laporan'] ?? '-') ?>
                                        </p>
                                    </td>

                                    <td class="px-6 py-4 text-slate-600">
                                        <?= htmlspecialchars($perusahaan) ?>
                                    </td>

                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold <?= $statusStyle ?>">
                                            <?= $statusLabel ?>
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-slate-500">
                                        <?= $tanggal ? date('d M Y', strtotime($tanggal)) : '-' ?>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <a
                                            href="<?= $base ?>/dashboard/dosen/verifikasi-laporan/detail/<?= (int) ($report['laporan_id'] ?? 0) ?>"
                                            class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold transition">

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-4 h-4"
                                                viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>

                                            Periksa
                                        </a>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </tbody>
                </table>
            </div>

            <div id="emptyFilter" class="hidden px-6 py-10 text-center text-sm text-slate-400">
                Tidak ada laporan yang sesuai dengan pencarian.
            </div>

        </section>

    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchLaporan');
    const statusFilter = document.getElementById('filterStatus');
    const rows = document.querySelectorAll('#listLaporan tr[data-status]');
    const emptyFilter = document.getElementById('emptyFilter');

    function filterReports() {
        const keyword = searchInput.value.toLowerCase().trim();
        const status = statusFilter.value;
        let visibleCount = 0;

        rows.forEach(row => {
            const rowStatus = row.dataset.status;
            const rowSearch = row.dataset.search || '';

            const matchKeyword = rowSearch.includes(keyword);
            const matchStatus = status === 'semua' || rowStatus === status;
            const visible = matchKeyword && matchStatus;

            row.classList.toggle('hidden', !visible);

            if (visible) visibleCount++;
        });

        emptyFilter.classList.toggle('hidden', visibleCount > 0);
    }

    searchInput.addEventListener('input', filterReports);
    statusFilter.addEventListener('change', filterReports);
});
</script>
