<?php
$base = url('/');
$active = 'penempatan';
?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Head.php'; ?>

<body class="overflow-x-hidden bg-slate-50 font-[Poppins] text-gray-900">

    <?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Sidebar.php'; ?>
    <?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Header.php'; ?>

    <main class="min-h-screen bg-slate-50 pt-20 lg:ml-64">
        <div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

            <!-- Page Header -->
            <div class="mb-7">
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                    Penempatan Magang
                </h1>

                <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500 sm:text-base">
                    Kelola penempatan mahasiswa setelah pengajuan diterima oleh mitra.
                    Penetapan dosen pembimbing dilakukan melalui menu Penetapan Dosen Pembimbing.
                </p>
            </div>

            <!-- Notification -->
            <?php if ($success): ?>
                <div
                    class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm text-emerald-800"
                    role="status">
                    <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 13 4 4L19 7" />
                    </svg>
                    <span>Penempatan magang berhasil dibuat.</span>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div
                    class="mb-6 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm text-red-800"
                    role="alert">
                    <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.3 3.8 1.9 18.2A2 2 0 0 0 3.6 21h16.8a2 2 0 0 0 1.7-2.8L13.7 3.8a2 2 0 0 0-3.4 0Z" />
                    </svg>
                    <span><?= e($error) ?></span>
                </div>
            <?php endif; ?>

            <!-- Placement Queue -->
            <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

                <!-- Section Header -->
                <div class="border-b border-slate-100 px-5 py-5 sm:px-6">
                    <div class="flex items-start gap-3">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7h4l2 3h12M5 7v12a1 1 0 001 1h12a1 1 0 001-1V7M9 11h6" />
                            </svg>
                        </div>

                        <div>
                            <h2 class="font-semibold text-slate-900">
                                Pengajuan Diterima — Belum Ditempatkan
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Daftar mahasiswa yang pengajuannya telah diterima mitra dan menunggu penempatan.
                            </p>
                        </div>

                    </div>
                </div>

                <?php if ($items): ?>

                    <div class="divide-y divide-slate-100">

                        <?php foreach ($items as $item): ?>

                            <article class="p-5 transition hover:bg-slate-50/40 sm:p-6">

                                <!-- Student and Company Information -->
                                <div class="mb-5 flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">

                                    <div class="flex min-w-0 items-start gap-3">

                                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-50 text-sm font-semibold text-blue-700">
                                            <?= e(strtoupper(substr((string) $item['mahasiswa_nama'], 0, 1))) ?>
                                        </div>

                                        <div class="min-w-0">
                                            <h3 class="font-semibold text-slate-900">
                                                <?= e((string) $item['mahasiswa_nama']) ?>
                                            </h3>

                                            <p class="mt-1 text-sm text-slate-500">
                                                NIM: <?= e((string) $item['nim']) ?>
                                            </p>

                                            <div class="mt-3 space-y-1.5">

                                                <p class="flex items-start gap-2 text-sm text-slate-700">
                                                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 21h18M5 21V7l8-4v18m6 0V11l-6-4M9 9v.01M9 12v.01M9 15v.01M9 18v.01M13 9v.01M13 12v.01M13 15v.01M13 18v.01" />
                                                    </svg>

                                                    <span class="font-medium">
                                                        <?= e((string) $item['nama_perusahaan']) ?>
                                                    </span>
                                                </p>

                                                <p class="flex items-start gap-2 text-sm text-slate-500">
                                                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h10" />
                                                    </svg>

                                                    <span>
                                                        <?= e((string) $item['judul']) ?>
                                                    </span>
                                                </p>

                                            </div>
                                        </div>

                                    </div>

                                    <span class="inline-flex w-fit shrink-0 items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1.5 text-xs font-medium text-amber-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                        Menunggu penempatan
                                    </span>

                                </div>

                                <!-- Formation Period -->
                                <div class="mb-5 rounded-lg border border-slate-200 bg-slate-50/70 p-4">

                                    <div class="mb-3 flex items-center gap-2">
                                        <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14H4V6a1 1 0 011-1Z" />
                                        </svg>

                                        <p class="text-xs font-semibold text-slate-600">
                                            Periode Formasi Mitra
                                        </p>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-2 text-sm">
                                        <span class="font-medium text-slate-800">
                                            <?= e((string) $item['formasi_mulai']) ?>
                                        </span>

                                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h14m-6-6 6 6-6 6" />
                                        </svg>

                                        <span class="font-medium text-slate-800">
                                            <?= e((string) $item['formasi_selesai']) ?>
                                        </span>
                                    </div>

                                    <p class="mt-2 text-xs text-slate-500">
                                        Tanggal tersebut merupakan saran awal dari formasi mitra.
                                        Koordinator dapat menyesuaikannya dengan jadwal pelaksanaan aktual.
                                    </p>

                                </div>

                                <!-- Placement Form -->
                                <form
                                    method="POST"
                                    action="<?= e(url('/dashboard/koordinator-magang/penempatan')) ?>"
                                    class="rounded-xl border border-slate-200 bg-white p-4 sm:p-5">
                                    <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
                                    <input type="hidden" name="pendaftaran_id" value="<?= e((string) $item['pendaftaran_id']) ?>">

                                    <div class="mb-4">
                                        <h4 class="text-sm font-semibold text-slate-800">
                                            Atur Periode Penempatan
                                        </h4>

                                        <p class="mt-1 text-xs text-slate-500">
                                            Tentukan tanggal mulai dan selesai pelaksanaan magang mahasiswa.
                                        </p>
                                    </div>

                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                                        <!-- Start Date -->
                                        <div>
                                            <label
                                                for="tanggal_mulai_<?= e((string) $item['pendaftaran_id']) ?>"
                                                class="mb-2 block text-sm font-medium text-slate-700">
                                                Tanggal Mulai
                                            </label>

                                            <input
                                                id="tanggal_mulai_<?= e((string) $item['pendaftaran_id']) ?>"
                                                type="date"
                                                name="tanggal_mulai"
                                                value="<?= e((string) $item['formasi_mulai']) ?>"
                                                required
                                                class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                        </div>

                                        <!-- End Date -->
                                        <div>
                                            <label
                                                for="tanggal_selesai_<?= e((string) $item['pendaftaran_id']) ?>"
                                                class="mb-2 block text-sm font-medium text-slate-700">
                                                Tanggal Selesai
                                            </label>

                                            <input
                                                id="tanggal_selesai_<?= e((string) $item['pendaftaran_id']) ?>"
                                                type="date"
                                                name="tanggal_selesai"
                                                value="<?= e((string) $item['formasi_selesai']) ?>"
                                                required
                                                class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                        </div>

                                    </div>

                                    <!-- Submit -->
                                    <div class="mt-5 flex justify-end border-t border-slate-100 pt-4">
                                        <button
                                            type="submit"
                                            class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:w-auto">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>

                                            Buat Penempatan
                                        </button>
                                    </div>

                                </form>

                            </article>

                        <?php endforeach; ?>

                    </div>

                    <!-- List Footer -->
                    <div class="border-t border-slate-100 px-5 py-3 sm:px-6">
                        <p class="text-xs text-slate-500">
                            Menampilkan <?= count($items) ?> pengajuan yang menunggu penempatan.
                        </p>
                    </div>

                <?php else: ?>

                    <!-- Empty State -->
                    <div class="px-5 py-16 text-center">

                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M3 7h4l2 3h12M5 7v12a1 1 0 001 1h12a1 1 0 001-1V7M9 11h6" />
                            </svg>
                        </div>

                        <h3 class="mt-4 text-sm font-semibold text-slate-800">
                            Tidak Ada Pengajuan Menunggu Penempatan
                        </h3>

                        <p class="mx-auto mt-1 max-w-md text-sm text-slate-500">
                            Semua pengajuan yang diterima mitra sudah diproses.
                            Pengajuan baru akan muncul di sini setelah diterima mitra.
                        </p>

                    </div>

                <?php endif; ?>

            </section>

            <?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Footer.php'; ?>

        </div>
    </main>

</body>

</html>