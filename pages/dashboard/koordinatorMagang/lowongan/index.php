<?php

declare(strict_types=1);

$active = 'lowongan';

/*
|--------------------------------------------------------------------------
| STATISTIK LOWONGAN
|--------------------------------------------------------------------------
*/

$totalLowongan = count($lowonganList);

$totalDibuka = 0;
$totalDraft = 0;
$totalSelesai = 0;

foreach ($lowonganList as $item) {
    if ($item['status'] === 'dibuka') {
        $totalDibuka++;
    } elseif ($item['status'] === 'draft') {
        $totalDraft++;
    } elseif ($item['status'] === 'selesai') {
        $totalSelesai++;
    }
}

/*
|--------------------------------------------------------------------------
| STATUS LABEL
|--------------------------------------------------------------------------
*/

$statusConfig = [
    'draft' => [
        'label' => 'Draft',
        'class' => 'bg-slate-100 text-slate-600',
    ],
    'dibuka' => [
        'label' => 'Dibuka',
        'class' => 'bg-emerald-50 text-emerald-700',
    ],
    'ditutup' => [
        'label' => 'Ditutup',
        'class' => 'bg-amber-50 text-amber-700',
    ],
    'selesai' => [
        'label' => 'Selesai',
        'class' => 'bg-blue-50 text-blue-700',
    ],
    'dibatalkan' => [
        'label' => 'Dibatalkan',
        'class' => 'bg-red-50 text-red-700',
    ],
];

$successMessages = [
    'created' => 'Lowongan berhasil ditambahkan.',
    'updated' => 'Lowongan berhasil diperbarui.',
    'status_updated' => 'Status lowongan berhasil diperbarui.',
];

$successMessage = $successMessages[$success] ?? null;

?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Head.php'; ?>

<body class="overflow-x-hidden bg-slate-50 font-[Poppins] text-slate-900 antialiased">

    <?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Sidebar.php'; ?>
    <?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Header.php'; ?>

    <main class="min-h-screen bg-slate-50 pt-20 lg:ml-64">

        <div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 lg:px-8 lg:py-8">

            <!-- ================================================= -->
            <!-- HEADER HALAMAN -->
            <!-- ================================================= -->

            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

                <div>
                    <div class="mb-2 flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.16em] text-blue-600">
                        <span>Manajemen Magang</span>
                        <span class="text-slate-300">/</span>
                        <span>Lowongan</span>
                    </div>

                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                        Lowongan Magang
                    </h1>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                        Kelola informasi posisi magang, kuota mahasiswa, periode pelaksanaan,
                        dan publikasi lowongan dari seluruh mitra.
                    </p>
                </div>

                <a
                    href="<?= e(url('/dashboard/koordinator-magang/lowongan/tambah')) ?>"
                    class="inline-flex min-h-[44px] items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm shadow-blue-200 transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20">

                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m-7-7h14" />
                    </svg>

                    Tambah Lowongan

                </a>

            </div>


            <!-- ================================================= -->
            <!-- NOTIFIKASI -->
            <!-- ================================================= -->

            <?php if ($successMessage): ?>

                <div role="status"
                    class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm text-emerald-800">

                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 13 4 4L19 7" />
                    </svg>

                    <?= e($successMessage) ?>

                </div>

            <?php elseif ($success === '0'): ?>

                <div role="alert"
                    class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm text-red-800">

                    Aksi belum berhasil dilakukan. Silakan periksa kembali data lowongan.

                </div>

            <?php endif; ?>


            <!-- ================================================= -->
            <!-- STATISTIK -->
            <!-- ================================================= -->

            <div class="mb-7 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

                <!-- TOTAL -->

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                    <div class="flex items-start justify-between">

                        <div>
                            <p class="text-sm font-medium text-slate-500">
                                Total Lowongan
                            </p>

                            <h3 class="mt-3 text-3xl font-bold tracking-tight text-slate-900 tabular-nums">
                                <?= number_format($totalLowongan) ?>
                            </h3>

                            <p class="mt-2 text-xs text-slate-400">
                                Seluruh lowongan terdaftar
                            </p>
                        </div>

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7h8m-8 4h8m-8 4h5M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2Z" />
                            </svg>
                        </div>

                    </div>

                </div>


                <!-- DIBUKA -->

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                    <div class="flex items-start justify-between">

                        <div>
                            <p class="text-sm font-medium text-slate-500">
                                Lowongan Dibuka
                            </p>

                            <h3 class="mt-3 text-3xl font-bold tracking-tight text-emerald-600 tabular-nums">
                                <?= number_format($totalDibuka) ?>
                            </h3>

                            <p class="mt-2 text-xs text-slate-400">
                                Sedang menerima pendaftaran
                            </p>
                        </div>

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12l4 4L19 6" />
                            </svg>
                        </div>

                    </div>

                </div>


                <!-- DRAFT -->

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                    <div class="flex items-start justify-between">

                        <div>
                            <p class="text-sm font-medium text-slate-500">
                                Draft
                            </p>

                            <h3 class="mt-3 text-3xl font-bold tracking-tight text-slate-600 tabular-nums">
                                <?= number_format($totalDraft) ?>
                            </h3>

                            <p class="mt-2 text-xs text-slate-400">
                                Belum dipublikasikan
                            </p>
                        </div>

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7h8m-8 4h8m-8 4h5M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2Z" />
                            </svg>
                        </div>

                    </div>

                </div>


                <!-- SELESAI -->

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                    <div class="flex items-start justify-between">

                        <div>
                            <p class="text-sm font-medium text-slate-500">
                                Selesai
                            </p>

                            <h3 class="mt-3 text-3xl font-bold tracking-tight text-blue-600 tabular-nums">
                                <?= number_format($totalSelesai) ?>
                            </h3>

                            <p class="mt-2 text-xs text-slate-400">
                                Periode magang berakhir
                            </p>
                        </div>

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5-5v5a8 8 0 11-16 0V5l8-3 8 3Z" />
                            </svg>
                        </div>

                    </div>

                </div>

            </div>


            <!-- ================================================= -->
            <!-- TABEL LOWONGAN -->
            <!-- ================================================= -->

            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <!-- TABLE HEADER -->

                <div class="flex flex-col gap-4 border-b border-slate-100 px-5 py-5 sm:px-6 lg:flex-row lg:items-center lg:justify-between">

                    <div>
                        <h2 class="font-semibold text-slate-900">
                            Daftar Lowongan Magang
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            <?= number_format($totalLowongan) ?> lowongan tercatat
                        </p>
                    </div>


                    <div class="relative w-full lg:max-w-xs">

                        <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <circle cx="11" cy="11" r="7" stroke-width="1.8" />
                            <path stroke-linecap="round" stroke-width="1.8" d="m16 16 4 4" />

                        </svg>

                        <input
                            id="searchLowongan"
                            type="search"
                            placeholder="Cari posisi atau perusahaan..."
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                    </div>
                </div>


                <?php if (!empty($lowonganList)): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[1100px] text-left text-sm">
                            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-6 py-4 font-semibold">
                                        Posisi Magang
                                    </th>

                                    <th class="px-6 py-4 font-semibold">
                                        Mitra
                                    </th>

                                    <th class="px-6 py-4 font-semibold">
                                        Kuota
                                    </th>

                                    <th class="px-6 py-4 font-semibold">
                                        Periode
                                    </th>

                                    <th class="px-6 py-4 font-semibold">
                                        Status
                                    </th>

                                    <th class="px-6 py-4 text-right font-semibold">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>


                            <tbody id="lowonganTable" class="divide-y divide-slate-100">

                                <?php foreach ($lowonganList as $item): ?>

                                    <?php

                                    $status = $item['status'];

                                    $config = $statusConfig[$status] ?? [
                                        'label' => ucfirst($status),
                                        'class' => 'bg-slate-100 text-slate-600',
                                    ];

                                    $jumlahKuota = (int) $item['jumlah_kuota'];
                                    $jumlahDiterima = (int) $item['jumlah_diterima'];

                                    $persentase = $jumlahKuota > 0
                                        ? min(100, ($jumlahDiterima / $jumlahKuota) * 100)
                                        : 0;

                                    $searchText = mb_strtolower(
                                        (string) $item['judul'] . ' ' .
                                            (string) $item['nama_perusahaan'] . ' ' .
                                            (string) ($item['bidang'] ?? ''),
                                        'UTF-8'
                                    );

                                    $tanggalMulai = date(
                                        'd M Y',
                                        strtotime($item['tanggal_mulai'])
                                    );

                                    $tanggalSelesai = date(
                                        'd M Y',
                                        strtotime($item['tanggal_selesai'])
                                    );

                                    ?>

                                    <tr
                                        data-lowongan
                                        data-search="<?= e($searchText) ?>"
                                        class="transition hover:bg-slate-50/70">

                                        <!-- POSISI -->

                                        <td class="px-6 py-4">

                                            <div class="max-w-[260px]">

                                                <div class="font-semibold text-slate-800">
                                                    <?= e((string) $item['judul']) ?>
                                                </div>

                                                <div class="mt-1 text-xs text-slate-400">
                                                    <?= e((string) ($item['bidang'] ?: 'Bidang belum ditentukan')) ?>
                                                </div>
                                            </div>
                                        </td>


                                        <!-- MITRA -->

                                        <td class="px-6 py-4">

                                            <div class="font-medium text-slate-700">
                                                <?= e((string) $item['nama_perusahaan']) ?>
                                            </div>

                                            <div class="mt-1 text-xs text-slate-400">
                                                <?= e((string) $item['kode_perusahaan']) ?>
                                            </div>

                                            <?php if (isset($item['mitra_is_active']) && !$item['mitra_is_active']): ?>

                                                <span class="mt-1 inline-block text-xs font-medium text-amber-600">
                                                    Mitra nonaktif
                                                </span>
                                            <?php endif; ?>
                                        </td>


                                        <!-- KUOTA -->

                                        <td class="px-6 py-4">

                                            <div class="flex items-center gap-2">=
                                                <span class="font-semibold text-slate-800 tabular-nums">
                                                    <?= $jumlahDiterima ?>
                                                </span>
                                                <span class="text-slate-400">
                                                    /
                                                </span>
                                                <span class="text-slate-600 tabular-nums">
                                                    <?= $jumlahKuota ?>
                                                </span>
                                            </div>

                                            <div class="mt-2 h-1.5 w-24 overflow-hidden rounded-full bg-slate-100">

                                                <div
                                                    class="h-full rounded-full bg-blue-500 transition-all"
                                                    style="width: <?= $persentase ?>%">
                                                </div>
                                            </div>
                                            <p class="mt-1 text-[11px] text-slate-400">
                                                <?= max(0, $jumlahKuota - $jumlahDiterima) ?> kuota tersisa
                                            </p>
                                        </td>


                                        <!-- PERIODE -->

                                        <td class="px-6 py-4">
                                            <div class="text-xs font-medium text-slate-700">
                                                <?= e($tanggalMulai) ?>
                                            </div>
                                            <div class="my-1 text-xs text-slate-400">
                                                sampai
                                            </div>
                                            <div class="text-xs font-medium text-slate-700">
                                                <?= e($tanggalSelesai) ?>
                                            </div>
                                        </td>


                                        <!-- STATUS -->

                                        <td class="px-6 py-4">

                                            <span class="inline-flex items-center rounded-full px-3 py-1.5 text-xs font-semibold <?= $config['class'] ?>">

                                                <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-current"></span>

                                                <?= e($config['label']) ?>
                                            </span>
                                        </td>


                                        <!-- AKSI -->

                                        <td class="px-6 py-4">

                                            <div class="flex items-center justify-end gap-2">

                                                <a
                                                    href="<?= e(url('/dashboard/koordinator-magang/lowongan/detail/' . (int) $item['id'])) ?>"
                                                    title="Detail"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600">

                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" />
                                                        <circle cx="12" cy="12" r="3" stroke-width="1.8" />
                                                    </svg>
                                                </a>


                                                <a
                                                    href="<?= e(url('/dashboard/koordinator-magang/lowongan/edit/' . (int) $item['id'])) ?>"
                                                    title="Edit"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600">

                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m15 5 4 4M4 20l4.5-1 10-10a2.1 2.1 0 00-3-3l-10 10L4 20Z" />
                                                    </svg>
                                                </a>


                                                <button
                                                    type="button"
                                                    title="Ubah status"
                                                    onclick="openStatusModal(<?= (int) $item['id'] ?>, '<?= e($status) ?>', '<?= e((string) $item['judul']) ?>')"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:border-amber-200 hover:bg-amber-50 hover:text-amber-600">

                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0Z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- EMPTY SEARCH -->

                    <div id="emptySearch" class="hidden px-6 py-12 text-center">

                        <p class="font-semibold text-slate-700">
                            Lowongan tidak ditemukan
                        </p>

                        <p class="mt-1 text-sm text-slate-400">
                            Coba gunakan kata kunci yang berbeda.
                        </p>
                    </div>


                <?php else: ?>
                    <!-- EMPTY STATE -->
                    <div class="px-5 py-16 text-center">

                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-blue-500">

                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M8 7h8m-8 4h8m-8 4h5M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2Z" />
                            </svg>
                        </div>

                        <h3 class="mt-5 font-semibold text-slate-800">
                            Belum Ada Lowongan Magang
                        </h3>

                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                            Belum ada lowongan yang dibuat. Mulai tambahkan posisi magang dari perusahaan atau instansi mitra.
                        </p>

                        <a
                            href="<?= e(url('/dashboard/koordinator-magang/lowongan/tambah')) ?>"
                            class="mt-5 inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">

                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m-7-7h14" />
                            </svg>

                            Tambah Lowongan Pertama
                        </a>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </main>


    <!-- ================================================= -->
    <!-- MODAL UBAH STATUS -->
    <!-- ================================================= -->

    <div
        id="statusModal"
        class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-900/40 p-4 backdrop-blur-sm">

        <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl">

            <div class="border-b border-slate-100 px-6 py-5">

                <h3 class="font-bold text-slate-900">
                    Ubah Status Lowongan
                </h3>

                <p id="statusModalTitle" class="mt-1 text-sm text-slate-500">
                    -
                </p>
            </div>

            <form
                method="POST"
                action="<?= e(url('/dashboard/koordinator-magang/lowongan/status')) ?>">

                <input
                    type="hidden"
                    name="_csrf_token"
                    value="<?= e(csrfToken()) ?>">

                <input
                    type="hidden"
                    name="lowongan_id"
                    id="statusLowonganId">

                <div class="space-y-2 px-6 py-5">

                    <label for="statusSelect" class="block text-sm font-semibold text-slate-700">
                        Pilih Status
                    </label>

                    <select
                        name="status"
                        id="statusSelect"
                        required
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">

                        <option value="draft">Draft</option>
                        <option value="dibuka">Dibuka</option>
                        <option value="ditutup">Ditutup</option>
                        <option value="selesai">Selesai</option>
                        <option value="dibatalkan">Dibatalkan</option>

                    </select>

                    <p class="text-xs leading-5 text-slate-400">
                        Perubahan status akan langsung disimpan pada data lowongan.
                    </p>
                </div>

                <div class="flex justify-end gap-3 border-t border-slate-100 bg-slate-50 px-6 py-4">
                    <button
                        type="button"
                        onclick="closeStatusModal()"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">

                        Batal
                    </button>
                    <button
                        type="submit"
                        class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">

                        Simpan Status
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- ================================================= -->
    <!-- JAVASCRIPT -->
    <!-- ================================================= -->

    <script>
        // SEARCH LOWONGAN
        (() => {
            const input = document.getElementById('searchLowongan');
            const rows = Array.from(
                document.querySelectorAll('[data-lowongan]')
            );
            const empty = document.getElementById('emptySearch');
            if (!input) return;
            input.addEventListener('input', () => {
                const keyword = input.value
                    .trim()
                    .toLocaleLowerCase('id');
                let visible = 0;
                rows.forEach((row) => {
                    const show = (row.dataset.search || '')
                        .includes(keyword);
                    row.classList.toggle('hidden', !show);
                    if (show) visible++;
                });
                empty?.classList.toggle('hidden', visible > 0);
            });

        })();


        // STATUS MODAL

        function openStatusModal(id, status, title) {

            const modal = document.getElementById('statusModal');
            document.getElementById('statusLowonganId').value = id;
            document.getElementById('statusSelect').value = status;
            document.getElementById('statusModalTitle').textContent = title;
            modal.classList.remove('hidden');
            modal.classList.add('flex');

        }


        function closeStatusModal() {

            const modal = document.getElementById('statusModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');

        }

        // TUTUP MODAL KETIKA KLIK DI LUAR
        document.getElementById('statusModal')?.addEventListener('click', (event) => {

            if (event.target.id === 'statusModal') {
                closeStatusModal();

            }

        });

        // TUTUP MODAL DENGAN ESCAPE
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeStatusModal();

            }

        });
    </script>

</body>

</html>