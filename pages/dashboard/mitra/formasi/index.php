
<?php
$base = url('/');
$active = 'formasi';
?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . '/../../../../components/dashboard/mitra/Head.php'; ?>

<body class="overflow-x-hidden bg-slate-50 font-[Poppins] text-gray-900">

    <?php include __DIR__ . '/../../../../components/dashboard/mitra/Sidebar.php'; ?>
    <?php include __DIR__ . '/../../../../components/dashboard/mitra/Header.php'; ?>

    <main class="min-h-screen bg-slate-50 pt-20 lg:ml-64">
        <div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

            <!-- Page Header -->
            <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                        Formasi Magang
                    </h1>

                    <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500 sm:text-base">
                        Kelola formasi magang perusahaan. Pengajuan yang sudah masuk akan tetap dipertahankan sebagai histori.
                    </p>
                </div>

                <a
                    href="<?= e(url('/dashboard/mitra/formasi/tambah')) ?>"
                    class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m-7-7h14"/>
                    </svg>
                    Tambah Formasi
                </a>

            </div>

            <!-- Success Notification -->
            <?php if (($_GET['success'] ?? '') === '1'): ?>

                <div
                    class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm text-emerald-800"
                    role="status"
                >
                    <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 13 4 4L19 7"/>
                    </svg>

                    <span>Perubahan formasi berhasil disimpan.</span>
                </div>

            <?php endif; ?>

            <!-- Formation List -->
            <?php if ($items): ?>

                <div class="space-y-5">

                    <?php foreach ($items as $item): ?>

                        <?php
                        $statusClass = match ($item['status']) {
                            'dibuka' => 'bg-emerald-50 text-emerald-700',
                            'draft' => 'bg-slate-100 text-slate-600',
                            'ditutup' => 'bg-amber-50 text-amber-700',
                            'selesai' => 'bg-blue-50 text-blue-700',
                            default => 'bg-red-50 text-red-700',
                        };
                        ?>

                        <article class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md">

                            <!-- Formation Information -->
                            <div class="p-5 sm:p-6">

                                <!-- Title and Status -->
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">

                                    <div class="min-w-0 flex-1">

                                        <div class="flex flex-wrap items-center gap-2.5">

                                            <h2 class="text-lg font-semibold leading-6 text-slate-900">
                                                <?= e((string) $item['judul']) ?>
                                            </h2>

                                            <span class="inline-flex shrink-0 items-center rounded-full px-3 py-1 text-xs font-medium <?= e($statusClass) ?>">
                                                <?= e(ucfirst((string) $item['status'])) ?>
                                            </span>

                                        </div>

                                        <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-slate-500">

                                            <span class="inline-flex items-center gap-1.5">
                                                <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7h4l2 3h12M5 7v12a1 1 0 001 1h12a1 1 0 001-1V7M9 11h6"/>
                                                </svg>

                                                <?= e((string) ($item['bidang'] ?? 'Bidang belum diisi')) ?>
                                            </span>

                                            <span class="hidden text-slate-300 sm:inline">•</span>

                                            <span class="inline-flex items-center gap-1.5">
                                                <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21s7-4.4 7-11a7 7 0 10-14 0c0 6.6 7 11 7 11Z"/><circle cx="12" cy="10" r="2.5"/>
                                                </svg>

                                                <?= e((string) ($item['lokasi_magang'] ?? 'Lokasi belum diisi')) ?>
                                            </span>

                                        </div>

                                    </div>

                                    <span class="shrink-0 text-xs text-slate-400">
                                        Formasi #<?= e((string) $item['id']) ?>
                                    </span>

                                </div>

                                <!-- Description -->
                                <div class="mt-5">
                                    <p class="whitespace-pre-line break-words text-sm leading-6 text-slate-600"><?= e((string) $item['deskripsi']) ?></p>
                                </div>

                                <!-- Formation Statistics -->
                                <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-4">

                                    <!-- Accepted -->
                                    <div class="rounded-lg border border-slate-100 bg-slate-50 p-3.5">
                                        <p class="text-xs text-slate-500">
                                            Diterima
                                        </p>

                                        <p class="mt-1.5 text-xl font-bold text-slate-900">
                                            <?= e((string) $item['jumlah_diterima']) ?>
                                            <span class="text-sm font-medium text-slate-400">
                                                / <?= e((string) $item['jumlah_kuota']) ?>
                                            </span>
                                        </p>
                                    </div>

                                    <!-- Applicants -->
                                    <div class="rounded-lg border border-slate-100 bg-slate-50 p-3.5">
                                        <p class="text-xs text-slate-500">
                                            Total Pelamar
                                        </p>

                                        <p class="mt-1.5 text-xl font-bold text-slate-900">
                                            <?= e((string) $item['jumlah_pelamar']) ?>
                                        </p>
                                    </div>

                                    <!-- Start Date -->
                                    <div class="rounded-lg border border-slate-100 bg-slate-50 p-3.5">
                                        <p class="text-xs text-slate-500">
                                            Tanggal Mulai
                                        </p>

                                        <p class="mt-1.5 text-sm font-semibold text-slate-800">
                                            <?= e((string) $item['tanggal_mulai']) ?>
                                        </p>
                                    </div>

                                    <!-- End Date -->
                                    <div class="rounded-lg border border-slate-100 bg-slate-50 p-3.5">
                                        <p class="text-xs text-slate-500">
                                            Tanggal Selesai
                                        </p>

                                        <p class="mt-1.5 text-sm font-semibold text-slate-800">
                                            <?= e((string) $item['tanggal_selesai']) ?>
                                        </p>
                                    </div>

                                </div>

                                <!-- Academic Year -->
                                <div class="mt-4 flex items-center gap-2 text-xs text-slate-500">
                                    <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14H4V6a1 1 0 011-1Z"/>
                                    </svg>

                                    Tahun Akademik:
                                    <span class="font-medium text-slate-700">
                                        <?= e((string) $item['tahun_akademik']) ?>
                                    </span>
                                </div>

                            </div>

                            <!-- Formation Actions -->
                            <div class="flex flex-col gap-3 border-t border-slate-100 bg-slate-50/70 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">

                                <p class="text-xs leading-5 text-slate-500">
                                    <?php if ((int) $item['jumlah_pelamar'] > 0): ?>
                                        Formasi memiliki histori pengajuan sehingga pengeditan dan penghapusan dibatasi.
                                    <?php else: ?>
                                        Formasi belum memiliki pelamar. Pengelolaan formasi masih tersedia.
                                    <?php endif; ?>
                                </p>

                                <div class="flex flex-wrap items-center gap-2">

                                    <!-- Edit and Delete -->
                                    <?php if ((int) $item['jumlah_pelamar'] === 0): ?>

                                        <a
                                            href="<?= e(url('/dashboard/mitra/formasi/edit/' . (int) $item['id'])) ?>"
                                            class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-blue-200 bg-white px-4 py-2 text-sm font-medium text-blue-700 transition hover:bg-blue-50"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m16 4 4 4M4 20l4-.8L19 8a2.1 2.1 0 00-3-3L5 16l-1 4Z"/>
                                            </svg>
                                            Edit
                                        </a>

                                        <?php if ((int) $item['jumlah_diterima'] === 0): ?>

                                            <form
                                                method="POST"
                                                action="<?= e(url('/dashboard/mitra/formasi/hapus/' . (int) $item['id'])) ?>"
                                                onsubmit="return confirm('Hapus formasi ini? Hanya formasi tanpa histori pengajuan yang dapat dihapus.');"
                                            >
                                                <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">

                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-red-200 bg-white px-4 py-2 text-sm font-medium text-red-700 transition hover:bg-red-50"
                                                >
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7h16M10 11v6m4-6v6M5 7l1 14h12l1-14M9 7V4h6v3"/>
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>

                                        <?php endif; ?>

                                    <?php endif; ?>

                                    <!-- Open / Close Formation -->
                                    <?php if (in_array($item['status'], ['draft', 'dibuka', 'ditutup'], true)): ?>

                                        <form
                                            method="POST"
                                            action="<?= e(url('/dashboard/mitra/formasi/status/' . (int) $item['id'])) ?>"
                                        >
                                            <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">

                                            <input
                                                type="hidden"
                                                name="status"
                                                value="<?= $item['status'] === 'dibuka' ? 'ditutup' : 'dibuka' ?>"
                                            >

                                            <button
                                                type="submit"
                                                class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700"
                                            >
                                                <?php if ($item['status'] === 'dibuka'): ?>

                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 6l12 12M18 6 6 18"/>
                                                    </svg>

                                                    Tutup Formasi

                                                <?php else: ?>

                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h14m-7-7 7 7-7 7"/>
                                                    </svg>

                                                    Buka Formasi

                                                <?php endif; ?>
                                            </button>
                                        </form>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <!-- Empty State -->
                <div class="rounded-xl border border-dashed border-slate-300 bg-white px-5 py-16 text-center">

                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7h4l2 3h12M5 7v12a1 1 0 001 1h12a1 1 0 001-1V7M9 11h6"/>
                        </svg>
                    </div>

                    <h3 class="mt-4 text-sm font-semibold text-slate-800">
                        Belum Ada Formasi Magang
                    </h3>

                    <p class="mx-auto mt-1 max-w-md text-sm text-slate-500">
                        Perusahaan belum memiliki formasi magang.
                        Tambahkan formasi untuk mulai menerima pelamar.
                    </p>

                    <a
                        href="<?= e(url('/dashboard/mitra/formasi/tambah')) ?>"
                        class="mt-5 inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m-7-7h14"/>
                        </svg>
                        Tambah Formasi
                    </a>

                </div>

            <?php endif; ?>

            <?php include __DIR__ . '/../../../../components/dashboard/mitra/Footer.php'; ?>

        </div>
    </main>

</body>
</html>
