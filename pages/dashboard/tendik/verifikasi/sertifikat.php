<?php
$base = url('/');
$active = 'verifikasi-sertifikat';

$certificateId = (int) ($certificateDetailData['id'] ?? 0);
$certificateName = (string) ($certificateDetailData['nama'] ?? '-');
$studentName = (string) ($certificateDetailData['mahasiswa_nama'] ?? '-');
$studentNim = (string) ($certificateDetailData['nim'] ?? '-');
$publisher = (string) ($certificateDetailData['penerbit'] ?? '-');
$description = (string) ($certificateDetailData['deskripsi'] ?? '');
$issueDate = $certificateDetailData['tanggal_terbit'] ?? null;
$expiryDate = $certificateDetailData['tanggal_kedaluwarsa'] ?? null;
$certificateNumber = (string) ($certificateDetailData['nomor_sertifikat'] ?? '-');
$status = (string) ($certificateDetailData['status_verifikasi'] ?? 'belum_terverifikasi');

$statusConfig = [
    'belum_terverifikasi' => [
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

$currentStatus = $statusConfig[$status] ?? $statusConfig['belum_terverifikasi'];

$canVerify = in_array(
    $status,
    ['belum_terverifikasi', 'dalam_peninjauan', 'perlu_perbaikan'],
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

                <a href="<?= e(url('/dashboard/tendik/verifikasi/sertifikat')) ?>"
                    class="text-slate-400 transition hover:text-blue-600">
                    Sertifikat
                </a>

                <span class="text-slate-300">/</span>

                <span class="font-medium text-slate-700">Detail</span>
            </div>

            <!-- Page Header -->
            <div class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

                <div>
                    <div class="mb-2 flex items-center gap-2 text-sm font-semibold text-blue-600">
                        <span class="h-2 w-2 rounded-full bg-blue-600"></span>
                        MANAJEMEN DOKUMEN
                    </div>

                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                        Detail Sertifikat
                    </h1>

                    <p class="mt-2 text-sm text-slate-500">
                        Periksa informasi dan keabsahan sertifikat mahasiswa.
                    </p>
                </div>

                <!-- <a href="<?= e(url('/dashboard/tendik/verifikasi/sertifikat')) ?>"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">

                    <svg class="h-4 w-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>

                    Kembali
                </a> -->
            </div>

            <!-- Main Grid -->
            <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-3">

                <!-- Left Content -->
                <div class="space-y-6 lg:col-span-2">

                    <!-- Certificate Overview -->
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
                                                d="M7 3h8l5 5v11a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                                            <path stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.7"
                                                d="M15 3v5h5M9 13h6m-6 4h6" />
                                        </svg>

                                    </div>

                                    <div>
                                        <p class="text-xs font-medium uppercase tracking-widest text-slate-400">
                                            Dokumen Sertifikat
                                        </p>

                                        <h2 class="mt-1 text-lg font-bold text-white sm:text-xl">
                                            Informasi Dokumen
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

                            <div class="mb-6">
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Nama Sertifikat
                                </p>

                                <h3 class="mt-2 text-xl font-bold leading-relaxed text-slate-900 sm:text-2xl">
                                    <?= e($certificateName) ?>
                                </h3>
                            </div>

                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                                <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-4">
                                    <p class="text-xs font-medium text-slate-400">
                                        Penerbit
                                    </p>

                                    <p class="mt-2 font-semibold text-slate-800">
                                        <?= e($publisher) ?>
                                    </p>
                                </div>

                                <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-4">
                                    <p class="text-xs font-medium text-slate-400">
                                        Nomor Sertifikat
                                    </p>

                                    <p class="mt-2 break-all font-semibold text-slate-800">
                                        <?= e($certificateNumber) ?>
                                    </p>
                                </div>

                                <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-4">
                                    <p class="text-xs font-medium text-slate-400">
                                        Tanggal Terbit
                                    </p>

                                    <p class="mt-2 font-semibold text-slate-800">
                                        <?= e((string) ($issueDate ?: '-')) ?>
                                    </p>
                                </div>

                                <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-4">
                                    <p class="text-xs font-medium text-slate-400">
                                        Tanggal Kedaluwarsa
                                    </p>

                                    <p class="mt-2 font-semibold text-slate-800">
                                        <?= e((string) ($expiryDate ?: 'Tidak ada')) ?>
                                    </p>
                                </div>

                            </div>

                            <div class="mt-6 border-t border-slate-100 pt-5">

                                <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Deskripsi Sertifikat
                                </p>

                                <p class="text-sm leading-7 text-slate-600">
                                    <?= $description !== ''
                                        ? nl2br(e($description))
                                        : 'Tidak ada deskripsi yang ditambahkan.' ?>
                                </p>

                            </div>

                        </div>

                    </section>

                    <!-- Student Information -->
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">

                        <div class="mb-5 flex items-center gap-3">

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600">

                                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
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
                                    Identitas pemilik sertifikat
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

                    <!-- Document File -->
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">

                        <div class="mb-4 flex items-center gap-3">

                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">

                                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M12 16V4m0 0L7 9m5-5l5 5M5 16v3a2 2 0 002 2h10a2 2 0 002-2v-3" />
                                </svg>

                            </div>

                            <div>
                                <h3 class="font-bold text-slate-900">
                                    Berkas Sertifikat
                                </h3>

                                <p class="mt-1 text-xs text-slate-400">
                                    Dokumen bukti yang diunggah mahasiswa
                                </p>
                            </div>

                        </div>

                        <a href="<?= e(url('/dashboard/tendik/verifikasi/sertifikat/' . $certificateId . '/berkas')) ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="group flex flex-col justify-between gap-4 rounded-xl border border-dashed border-emerald-200 bg-emerald-50/50 p-5 transition hover:border-emerald-400 hover:bg-emerald-50 sm:flex-row sm:items-center">

                            <div class="flex items-center gap-3">

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

                                <div>
                                    <p class="text-sm font-semibold text-slate-800">
                                        Buka Dokumen Sertifikat
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        Lihat berkas asli untuk pemeriksaan
                                    </p>
                                </div>

                            </div>

                            <span class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 transition group-hover:gap-3">

                                Lihat Dokumen

                                <svg class="h-4 w-4" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M7 17L17 7M7 7h10v10" />
                                </svg>

                            </span>

                        </a>

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
                                Tentukan hasil pemeriksaan dokumen sertifikat.
                            </p>

                        </div>

                        <div class="p-5">

                            <?php if ($canVerify): ?>

                                <form
                                    method="POST"
                                    action="<?= e(url('/dashboard/tendik/verifikasi/sertifikat/' . $certificateId . '/keputusan')) ?>"
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
                                            placeholder="Tuliskan alasan atau catatan jika dokumen memerlukan perbaikan..."></textarea>

                                        <p class="mt-2 text-xs leading-relaxed text-slate-400">
                                            Wajib diisi apabila memilih Revisi atau Tolak.
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

                                            Setujui Sertifikat
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

                                            Tolak Sertifikat
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

                                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.8"
                                        d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>

                            </div>

                            <div>
                                <h3 class="font-bold text-slate-900">
                                    Riwayat Aktivitas
                                </h3>

                                <p class="mt-1 text-xs text-slate-400">
                                    Catatan pemeriksaan dokumen
                                </p>
                            </div>

                        </div>

                        <?php if (!empty($history)): ?>

                            <div class="relative space-y-5 border-l-2 border-slate-100 pl-5">

                                <?php foreach ($history as $entry): ?>

                                    <div class="relative">

                                        <span class="absolute -left-[27px] top-1 h-3 w-3 rounded-full border-2 border-white bg-blue-500 ring-2 ring-blue-100"></span>

                                        <p class="text-sm font-semibold text-slate-800">
                                            <?= e((string) ($entry['aksi'] ?? 'Aktivitas verifikasi')) ?>
                                        </p>

                                        <p class="mt-1 text-xs leading-relaxed text-slate-500">
                                            <?= e((string) ($entry['deskripsi'] ?? '')) ?>
                                        </p>

                                        <div class="mt-2 flex flex-wrap items-center gap-2 text-[11px] text-slate-400">

                                            <span>
                                                <?= e((string) ($entry['petugas'] ?? 'Petugas')) ?>
                                            </span>

                                            <span>•</span>

                                            <span>
                                                <?= e((string) ($entry['created_at'] ?? '-')) ?>
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

            <?php include __DIR__ . '/../../../../components/dashboard/tendik/Footer.php'; ?>

        </div>

    </main>

</body>

</html>