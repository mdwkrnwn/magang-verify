<?php

$base = url('/');
$active = 'verifikasi-portofolio';

$portfolioId = (int) ($portfolioDetailData['id'] ?? 0);
$portfolioTitle = (string) ($portfolioDetailData['judul'] ?? '-');
$studentName = (string) ($portfolioDetailData['mahasiswa_nama'] ?? '-');
$studentNim = (string) ($portfolioDetailData['nim'] ?? '-');
$description = (string) ($portfolioDetailData['deskripsi'] ?? '');
$status = (string) ($portfolioDetailData['status_verifikasi'] ?? 'belum_diverifikasi');
$portfolioType = (string) ($portfolioDetailData['jenis'] ?? '-');
$role = (string) ($portfolioDetailData['peran'] ?? '-');
$organizer = (string) ($portfolioDetailData['penyelenggara'] ?? '-');
$year = !empty($portfolioDetailData['tanggal_perolehan'])
    ? date('Y', strtotime($portfolioDetailData['tanggal_perolehan']))
    : '-';

$files = is_array($portfolioDetailData['berkas'] ?? null)
    ? $portfolioDetailData['berkas']
    : [];

$statusConfig = [
    'belum_diverifikasi' => [
        'label' => 'Menunggu Verifikasi',
        'class' => 'bg-amber-50 text-amber-700 ring-amber-200',
        'dot' => 'bg-amber-500',
    ],
    'dalam_peninjauan' => [
        'label' => 'Dalam Peninjauan',
        'class' => 'bg-blue-50 text-blue-700 ring-blue-200',
        'dot' => 'bg-blue-500',
    ],
    'perlu_perbaikan' => [
        'label' => 'Perlu Perbaikan',
        'class' => 'bg-orange-50 text-orange-700 ring-orange-200',
        'dot' => 'bg-orange-500',
    ],
    'terverifikasi' => [
        'label' => 'Terverifikasi',
        'class' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'dot' => 'bg-emerald-500',
    ],
    'ditolak' => [
        'label' => 'Ditolak',
        'class' => 'bg-red-50 text-red-700 ring-red-200',
        'dot' => 'bg-red-500',
    ],
];

$currentStatus = $statusConfig[$status] ?? $statusConfig['belum_diverifikasi'];

$canVerify = in_array(
    $status,
    ['belum_diverifikasi', 'perlu_perbaikan'],
    true
);

?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . '/../../../../components/dashboard/tendik/Head.php'; ?>

<body class="min-h-screen bg-slate-50 font-[Poppins] text-slate-800">

    <?php include __DIR__ . '/../../../../components/dashboard/tendik/Sidebar.php'; ?>
    <?php include __DIR__ . '/../../../../components/dashboard/tendik/Header.php'; ?>

    <main class="min-h-screen pt-20 lg:ml-64">
        <div class="mx-auto max-w-8xl px-4 py-7 sm:px-6 lg:px-8">

            <!-- Breadcrumb -->
            <div class="mb-6 flex flex-wrap items-center gap-2 text-sm">
                <a href="<?= e(url('/dashboard/tendik/verifikasi')) ?>"
                    class="text-slate-400 transition hover:text-blue-600">
                    Verifikasi Dokumen
                </a>

                <span class="text-slate-300">/</span>

                <a href="<?= e(url('/dashboard/tendik/verifikasi/portofolio')) ?>"
                    class="text-slate-400 transition hover:text-blue-600">
                    Portofolio
                </a>

                <span class="text-slate-300">/</span>

                <span class="font-medium text-slate-700">Detail</span>
            </div>

            <!-- Page Header -->
            <div class="mb-7">
                <div class="mb-2 flex items-center gap-2 text-sm font-semibold text-blue-600">
                    <span class="h-2 w-2 rounded-full bg-blue-600"></span>
                    MANAJEMEN DOKUMEN
                </div>

                <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                    Detail Portofolio
                </h1>

                <p class="mt-2 text-sm text-slate-500">
                    Periksa informasi, hasil karya, dan berkas pendukung mahasiswa.
                </p>
            </div>

            <!-- Main Grid -->
            <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-3">

                <!-- Left Content -->
                <div class="space-y-6 lg:col-span-2">

                    <!-- Portfolio Overview -->
                    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                        <div class="border-b border-slate-100 bg-gradient-to-r from-slate-900 to-slate-800 px-6 py-6 sm:px-7">

                            <div class="flex flex-wrap items-start justify-between gap-4">

                                <div class="flex items-center gap-4">

                                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/10 text-blue-300 ring-1 ring-white/10">
                                        <svg class="h-7 w-7" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.7"
                                                d="M3 7h7l2 2h9v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                                            <path stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.7"
                                                d="M3 7V5a2 2 0 012-2h5l2 2h5" />
                                        </svg>
                                    </div>

                                    <div>
                                        <p class="text-xs font-medium uppercase tracking-widest text-slate-400">
                                            Dokumen Portofolio
                                        </p>

                                        <h2 class="mt-1 text-lg font-bold text-white sm:text-xl">
                                            Informasi Portofolio
                                        </h2>
                                    </div>

                                </div>

                                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-2 text-xs font-semibold text-white ring-1 ring-white/10">
                                    <span class="h-2 w-2 rounded-full <?= e($currentStatus['dot']) ?>"></span>
                                    <?= e($currentStatus['label']) ?>
                                </span>

                            </div>
                        </div>

                        <div class="p-6 sm:p-7">

                            <!-- Cover -->
                            <?php if (!empty($portfolioDetailData['gambar_sampul'])): ?>

                                <div class="mb-6 overflow-hidden rounded-xl border border-slate-100 bg-slate-50">
                                    <img
                                        src="<?= e(url('/dashboard/tendik/verifikasi/portofolio/' . $portfolioId . '/sampul')) ?>"
                                        alt="Sampul <?= e($portfolioTitle) ?>"
                                        class="h-64 w-full object-cover"
                                        onerror="this.hidden=true;this.nextElementSibling.hidden=false">

                                    <div hidden class="px-4 py-8 text-center text-sm text-slate-500">
                                        Gambar sampul tidak tersedia.
                                    </div>
                                </div>

                            <?php endif; ?>

                            <div class="mb-6">
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Judul Portofolio
                                </p>

                                <h3 class="mt-2 text-xl font-bold leading-relaxed text-slate-900 sm:text-2xl">
                                    <?= e($portfolioTitle) ?>
                                </h3>
                            </div>

                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                                <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-4">
                                    <p class="text-xs font-medium text-slate-400">
                                        Jenis Portofolio
                                    </p>

                                    <p class="mt-2 font-semibold text-slate-800">
                                        <?= e($portfolioType) ?>
                                    </p>
                                </div>

                                <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-4">
                                    <p class="text-xs font-medium text-slate-400">
                                        Tahun Perolehan
                                    </p>

                                    <p class="mt-2 font-semibold text-slate-800">
                                        <?= e($year) ?>
                                    </p>
                                </div>

                                <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-4">
                                    <p class="text-xs font-medium text-slate-400">
                                        Peran Mahasiswa
                                    </p>

                                    <p class="mt-2 font-semibold text-slate-800">
                                        <?= e($role) ?>
                                    </p>
                                </div>

                                <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-4">
                                    <p class="text-xs font-medium text-slate-400">
                                        Penyelenggara
                                    </p>

                                    <p class="mt-2 font-semibold text-slate-800">
                                        <?= e($organizer) ?>
                                    </p>
                                </div>

                            </div>

                            <!-- Description -->
                            <div class="mt-6 border-t border-slate-100 pt-5">
                                <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Deskripsi Portofolio
                                </p>

                                <p class="text-sm leading-7 text-slate-600">
                                    <?= $description !== ''
                                        ? nl2br(e($description))
                                        : 'Tidak ada deskripsi yang ditambahkan.' ?>
                                </p>
                            </div>

                            <!-- Links -->
                            <?php if (!empty($portfolioDetailData['tautan']) || !empty($portfolioDetailData['tautan_github']) || !empty($portfolioDetailData['tautan_demo'])): ?>

                                <div class="mt-6 border-t border-slate-100 pt-5">

                                    <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-slate-400">
                                        Tautan Portofolio
                                    </p>

                                    <div class="flex flex-wrap gap-3">

                                        <?php if (!empty($portfolioDetailData['tautan_github'])): ?>
                                            <a href="<?= e($portfolioDetailData['tautan_github']) ?>"
                                                target="_blank" rel="noopener noreferrer"
                                                class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                                GitHub
                                                <span>↗</span>
                                            </a>
                                        <?php endif; ?>

                                        <?php if (!empty($portfolioDetailData['tautan_demo'])): ?>
                                            <a href="<?= e($portfolioDetailData['tautan_demo']) ?>"
                                                target="_blank" rel="noopener noreferrer"
                                                class="inline-flex items-center gap-2 rounded-lg border border-blue-200 bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700 transition hover:bg-blue-100">
                                                Demo
                                                <span>↗</span>
                                            </a>
                                        <?php endif; ?>

                                        <?php if (!empty($portfolioDetailData['tautan']) && empty($portfolioDetailData['tautan_github']) && empty($portfolioDetailData['tautan_demo'])): ?>
                                            <a href="<?= e($portfolioDetailData['tautan']) ?>"
                                                target="_blank" rel="noopener noreferrer"
                                                class="inline-flex items-center gap-2 rounded-lg border border-blue-200 bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700 transition hover:bg-blue-100">
                                                Buka Tautan
                                                <span>↗</span>
                                            </a>
                                        <?php endif; ?>

                                    </div>
                                </div>

                            <?php endif; ?>

                        </div>
                    </section>

                    <!-- Student Information -->
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">

                        <div class="mb-5 flex items-center gap-3">

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                                <svg class="h-5 w-5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m8-10a4 4 0 100-8 4 4 0 000 8zm12 10v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                                </svg>
                            </div>

                            <div>
                                <h3 class="font-bold text-slate-900">
                                    Informasi Mahasiswa
                                </h3>

                                <p class="mt-1 text-xs text-slate-400">
                                    Identitas pemilik portofolio
                                </p>
                            </div>

                        </div>

                        <div class="flex items-center gap-4 rounded-xl border border-slate-100 bg-slate-50 p-4">

                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-blue-100 text-lg font-bold text-blue-700">
                                <?= e(strtoupper(substr($studentName, 0, 1))) ?>
                            </div>

                            <div class="min-w-0">
                                <p class="font-semibold text-slate-900">
                                    <?= e($studentName) ?>
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    NIM: <?= e($studentNim) ?>
                                </p>
                            </div>

                        </div>

                    </section>

                    <!-- Supporting Files -->
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">

                        <div class="mb-4 flex items-center gap-3">

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                                <svg class="h-5 w-5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M12 16V4m0 0L7 9m5-5l5 5M5 16v3a2 2 0 002 2h10a2 2 0 002-2v-3" />
                                </svg>
                            </div>

                            <div>
                                <h3 class="font-bold text-slate-900">
                                    Berkas Pendukung
                                </h3>

                                <p class="mt-1 text-xs text-slate-400">
                                    Dokumen bukti yang diunggah mahasiswa
                                </p>
                            </div>

                        </div>

                        <?php if (!empty($files)): ?>

                            <div class="space-y-3">

                                <?php foreach ($files as $file): ?>

                                    <a href="<?= e(url('/dashboard/tendik/verifikasi/portofolio/' . $portfolioId . '/berkas/' . (int) $file['id'])) ?>"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="group flex flex-col justify-between gap-4 rounded-xl border border-dashed border-emerald-200 bg-emerald-50/50 p-5 transition hover:border-emerald-400 hover:bg-emerald-50 sm:flex-row sm:items-center">

                                        <div class="flex min-w-0 items-center gap-3">

                                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-emerald-600 shadow-sm">
                                                <svg class="h-5 w-5" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="1.8"
                                                        d="M7 3h8l5 5v11a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                                                    <path stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="1.8"
                                                        d="M15 3v5h5" />
                                                </svg>
                                            </div>

                                            <div class="min-w-0">
                                                <p class="break-words text-sm font-semibold text-slate-800">
                                                    <?= e((string) ($file['nama_berkas'] ?? 'Berkas portofolio')) ?>
                                                </p>

                                                <p class="mt-1 text-xs text-slate-500">
                                                    <?= e((string) ($file['tipe_mime'] ?? 'Dokumen')) ?>
                                                </p>
                                            </div>

                                        </div>

                                        <span class="inline-flex shrink-0 items-center gap-2 text-sm font-semibold text-emerald-700 transition group-hover:gap-3">
                                            Lihat Berkas
                                            <span>↗</span>
                                        </span>

                                    </a>

                                <?php endforeach; ?>

                            </div>

                        <?php else: ?>

                            <div class="rounded-xl bg-slate-50 px-4 py-7 text-center">
                                <p class="text-sm text-slate-500">
                                    Belum ada berkas pendukung yang diunggah.
                                </p>
                            </div>

                        <?php endif; ?>

                    </section>

                </div>

                <!-- Right Sidebar -->
                <div class="space-y-6">

                    <!-- Verification Decision -->
                    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                        <div class="border-b border-slate-100 px-5 py-5">

                            <h3 class="font-bold text-slate-900">
                                Keputusan Verifikasi
                            </h3>

                            <p class="mt-1 text-xs leading-relaxed text-slate-500">
                                Tentukan hasil pemeriksaan dokumen portofolio.
                            </p>

                        </div>

                        <div class="p-5">

                            <?php if ($canVerify): ?>

                                <form
                                    method="POST"
                                    action="<?= e(url('/dashboard/tendik/verifikasi/portofolio/' . $portfolioId . '/keputusan')) ?>"
                                    id="verificationForm"
                                    class="space-y-4">

                                    <input type="hidden"
                                        name="_csrf_token"
                                        value="<?= e(csrfToken()) ?>">

                                    <div>

                                        <label for="note" class="mb-2 block text-sm font-semibold text-slate-700">
                                            Catatan Verifikasi
                                        </label>

                                        <textarea
                                            id="note"
                                            name="note"
                                            maxlength="5000"
                                            rows="5"
                                            class="w-full resize-y rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-400 focus:bg-white focus:ring-4 focus:ring-blue-50"
                                            placeholder="Tuliskan alasan atau catatan jika portofolio memerlukan perbaikan..."></textarea>

                                        <p class="mt-2 text-xs leading-relaxed text-slate-400">
                                            Wajib diisi apabila memilih Perlu Perbaikan atau Tolak.
                                        </p>

                                    </div>

                                    <div class="space-y-3">

                                        <button
                                            type="submit"
                                            name="decision"
                                            value="terverifikasi"
                                            class="flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-100">

                                            <svg class="h-4 w-4" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>

                                            Setujui Portofolio
                                        </button>

                                        <button
                                            type="submit"
                                            name="decision"
                                            value="perlu_perbaikan"
                                            class="flex w-full items-center justify-center gap-2 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-700 transition hover:bg-amber-100 focus:outline-none focus:ring-4 focus:ring-amber-100">

                                            <svg class="h-4 w-4" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 9v4m0 4h.01M10.3 3.86L1.82 18a2 2 0 001.7 3h16.96a2 2 0 001.7-3L13.7 3.86a2 2 0 00-3.4 0z" />
                                            </svg>

                                            Minta Perbaikan
                                        </button>

                                        <button
                                            type="submit"
                                            name="decision"
                                            value="ditolak"
                                            class="flex w-full items-center justify-center gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700 transition hover:bg-red-100 focus:outline-none focus:ring-4 focus:ring-red-100">

                                            <svg class="h-4 w-4" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>

                                            Tolak Portofolio
                                        </button>

                                    </div>

                                </form>

                            <?php else: ?>

                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">

                                    <p class="text-sm font-semibold text-slate-700">
                                        Dokumen sudah diputuskan
                                    </p>

                                    <p class="mt-2 text-xs leading-relaxed text-slate-500">
                                        Status saat ini:
                                        <?= e($currentStatus['label']) ?>.
                                        Keputusan ulang tidak tersedia.
                                    </p>

                                </div>

                            <?php endif; ?>

                        </div>

                    </section>

                    <!-- Verification History -->
                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                        <div class="mb-5 flex items-center gap-3">

                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                                <svg class="h-5 w-5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>

                            <div>
                                <h3 class="font-bold text-slate-900">
                                    Riwayat Verifikasi
                                </h3>

                                <p class="mt-1 text-xs text-slate-400">
                                    Catatan pemeriksaan portofolio
                                </p>
                            </div>

                        </div>

                        <?php if (!empty($history)): ?>

                            <div class="relative space-y-5 border-l-2 border-slate-100 pl-5">

                                <?php foreach ($history as $entry): ?>

                                    <div class="relative">

                                        <span class="absolute -left-[27px] top-1 h-3 w-3 rounded-full border-2 border-white bg-blue-500 ring-2 ring-blue-100"></span>

                                        <p class="text-sm font-semibold text-slate-800">
                                            <?= e((string) ($entry['status_sebelumnya'] ?? '-')) ?>
                                            →
                                            <?= e((string) ($entry['status_baru'] ?? '-')) ?>
                                        </p>

                                        <p class="mt-1 text-xs leading-relaxed text-slate-500">
                                            <?= e((string) ($entry['catatan'] ?? 'Tidak ada catatan.')) ?>
                                        </p>

                                        <div class="mt-2 flex flex-wrap items-center gap-2 text-[11px] text-slate-400">

                                            <span>
                                                <?= e((string) ($entry['petugas'] ?? 'Petugas')) ?>
                                            </span>

                                            <span>•</span>

                                            <span>
                                                <?= e((string) ($entry['waktu_verifikasi'] ?? '-')) ?>
                                            </span>

                                        </div>

                                    </div>

                                <?php endforeach; ?>

                            </div>

                        <?php else: ?>

                            <div class="rounded-xl bg-slate-50 px-4 py-7 text-center">

                                <div class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-full bg-white text-slate-400">
                                    <svg class="h-5 w-5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.8"
                                            d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>

                                <p class="text-xs font-medium text-slate-500">
                                    Belum ada riwayat verifikasi.
                                </p>

                            </div>

                        <?php endif; ?>

                    </section>

                </div>

            </div>

        </div>
    </main>

</body>

</html>