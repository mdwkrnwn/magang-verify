
<?php
$e = static fn($value) => htmlspecialchars(
    (string) ($value ?? '-'),
    ENT_QUOTES,
    'UTF-8'
);

$laporanId = (int) $laporan['laporan_id'];

$namaJenis = match ($laporan['jenis_laporan']) {
    'laporan_mingguan' => 'Laporan Mingguan',
    'laporan_akhir' => 'Laporan Akhir',
    default => ucfirst(str_replace('_', ' ', $laporan['jenis_laporan'] ?? '-')),
};

$status = $laporan['status_laporan'] ?? 'diperiksa_dosen';

$statusLabel = match ($status) {
    'diperiksa_dosen' => 'Menunggu Verifikasi Tendik',
    'perlu_revisi' => 'Perlu Revisi',
    'terverifikasi' => 'Terverifikasi',
    'ditolak' => 'Ditolak',
    default => ucfirst(str_replace('_', ' ', $status)),
};

$statusStyle = match ($status) {
    'diperiksa_dosen' => 'bg-amber-50 text-amber-700',
    'perlu_revisi' => 'bg-orange-50 text-orange-700',
    'terverifikasi' => 'bg-emerald-50 text-emerald-700',
    'ditolak' => 'bg-rose-50 text-rose-700',
    default => 'bg-slate-100 text-slate-600',
};

$previewUrl = url(
    '/dashboard/tendik/verifikasi/laporan/berkas/' . $laporanId
);

$detailUrl = url('/dashboard/tendik/verifikasi/laporan');
?>

<main class="min-h-screen bg-slate-50 pt-20 lg:ml-64">

    <div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

        <!-- Header -->
        <div class="mb-6">

            <a
                href="<?= $e($detailUrl) ?>"
                class="mb-4 inline-flex items-center gap-2 text-sm text-slate-500 hover:text-blue-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke daftar laporan
            </a>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <p class="mb-1 text-sm font-medium text-blue-600">
                        Verifikasi Laporan Magang
                    </p>

                    <h1 class="text-2xl font-bold tracking-tight text-slate-800 sm:text-3xl">
                        Detail Laporan
                    </h1>

                    <p class="mt-2 text-sm text-slate-500 sm:text-base">
                        Periksa dokumen dan informasi laporan mahasiswa.
                    </p>
                </div>

                <span class="inline-flex w-fit rounded-full px-4 py-2 text-sm font-semibold <?= $statusStyle ?>">
                    <?= $e($statusLabel) ?>
                </span>

            </div>
        </div>

        <!-- Information and PDF -->
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

            <!-- Left Information -->
            <div class="space-y-6 xl:col-span-1">

                <!-- Student -->
                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                    <div class="mb-5 flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m8-10a4 4 0 100-8 4 4 0 000 8z"/>
                            </svg>
                        </div>

                        <div>
                            <h2 class="font-bold text-slate-800">Identitas Mahasiswa</h2>
                            <p class="text-xs text-slate-400">Informasi mahasiswa</p>
                        </div>
                    </div>

                    <div class="space-y-4 text-sm">

                        <div>
                            <p class="mb-1 text-slate-400">Nama mahasiswa</p>
                            <p class="font-semibold text-slate-800">
                                <?= $e($laporan['mahasiswa_nama']) ?>
                            </p>
                        </div>

                        <div>
                            <p class="mb-1 text-slate-400">NIM</p>
                            <p class="font-medium text-slate-700">
                                <?= $e($laporan['nim']) ?>
                            </p>
                        </div>

                        <div>
                            <p class="mb-1 text-slate-400">Program studi</p>
                            <p class="font-medium text-slate-700">
                                <?= $e($laporan['program_studi']) ?>
                            </p>
                        </div>

                    </div>
                </section>

                <!-- Internship -->
                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                    <div class="mb-5 flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M3 21h18M5 21V7l8-4v18m6 0V11l-6-4M9 9v.01M9 12v.01M9 15v.01M9 18v.01M15 14v.01M15 17v.01"/>
                            </svg>
                        </div>

                        <div>
                            <h2 class="font-bold text-slate-800">Informasi Magang</h2>
                            <p class="text-xs text-slate-400">Detail penempatan mahasiswa</p>
                        </div>
                    </div>

                    <div class="space-y-4 text-sm">

                        <div>
                            <p class="mb-1 text-slate-400">Perusahaan</p>
                            <p class="font-semibold text-slate-800">
                                <?= $e($laporan['nama_perusahaan']) ?>
                            </p>
                        </div>

                        <div>
                            <p class="mb-1 text-slate-400">Formasi magang</p>
                            <p class="font-medium text-slate-700">
                                <?= $e($laporan['judul_formasi']) ?>
                            </p>
                        </div>

                        <div>
                            <p class="mb-1 text-slate-400">Periode magang</p>
                            <p class="font-medium text-slate-700">
                                <?= $e($laporan['tanggal_mulai']) ?>
                                –
                                <?= $e($laporan['tanggal_selesai']) ?>
                            </p>
                        </div>

                    </div>
                </section>

                <!-- Report Information -->
                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                    <h2 class="mb-4 font-bold text-slate-800">
                        Informasi Laporan
                    </h2>

                    <div class="space-y-4 text-sm">

                        <div>
                            <p class="mb-1 text-slate-400">Jenis laporan</p>
                            <p class="font-medium text-slate-700">
                                <?= $e($namaJenis) ?>
                            </p>
                        </div>

                        <?php if ($laporan['minggu_ke'] !== null): ?>
                            <div>
                                <p class="mb-1 text-slate-400">Minggu ke</p>
                                <p class="font-medium text-slate-700">
                                    <?= $e($laporan['minggu_ke']) ?>
                                </p>
                            </div>
                        <?php endif; ?>

                        <div>
                            <p class="mb-1 text-slate-400">Versi laporan</p>
                            <p class="font-medium text-slate-700">
                                Versi <?= $e($laporan['versi_terkini']) ?>
                            </p>
                        </div>

                        <div>
                            <p class="mb-1 text-slate-400">Tanggal pengajuan</p>
                            <p class="font-medium text-slate-700">
                                <?= $e($laporan['diajukan_pada']) ?>
                            </p>
                        </div>

                        <div>
                            <p class="mb-1 text-slate-400">Judul laporan</p>
                            <p class="font-semibold text-slate-800">
                                <?= $e($laporan['judul_laporan']) ?>
                            </p>
                        </div>

                    </div>
                </section>

            </div>

            <!-- PDF Preview -->
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm xl:col-span-2">

                <div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <h2 class="font-bold text-slate-800">
                            Pratinjau Dokumen
                        </h2>

                        <p class="mt-1 text-xs text-slate-400">
                            Dokumen laporan mahasiswa dalam format PDF
                        </p>
                    </div>

                    <a
                        href="<?= $e($previewUrl) ?>"
                        target="_blank"
                        rel="noopener"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">

                        Buka PDF

                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 3h7v7m-11 4L21 3M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/>
                        </svg>
                    </a>

                </div>

                <div class="bg-slate-100 p-2">
                    <iframe
                        src="<?= $e($previewUrl) ?>"
                        class="h-[650px] w-full rounded-lg bg-white lg:h-[850px]"
                        title="Pratinjau laporan magang">
                    </iframe>
                </div>

            </section>

        </div>

        <!-- Verification Form -->
        <section class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <div class="mb-5 flex items-center gap-3">

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M9 12l2 2 4-4m5.5-3.5A9 9 0 1112 3"/>
                    </svg>
                </div>

                <div>
                    <h2 class="font-bold text-slate-800">
                        Pemeriksaan Tendik
                    </h2>

                    <p class="text-xs text-slate-400">
                        Berikan keputusan berdasarkan hasil pemeriksaan dokumen.
                    </p>
                </div>

            </div>

            <?php if (!empty($_SESSION['flash_error'])): ?>
                <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                    <?= $e($_SESSION['flash_error']) ?>
                </div>
                <?php unset($_SESSION['flash_error']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['flash_success'])): ?>
                <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
                    <?= $e($_SESSION['flash_success']) ?>
                </div>
                <?php unset($_SESSION['flash_success']); ?>
            <?php endif; ?>

            <?php if ($status === 'diperiksa_dosen'): ?>

                <form
                    action="<?= $e(url(
                        '/dashboard/tendik/verifikasi/laporan/detail/'
                        . $laporanId
                        . '/proses'
                    )) ?>"
                    method="POST"
                    class="space-y-5">

                    <input
                        type="hidden"
                        name="_csrf_token"
                        value="<?= $e(csrfToken()) ?>">

                    <div>
                        <label class="mb-3 block text-sm font-semibold text-slate-700">
                            Pilih Keputusan
                        </label>

                        <div class="grid grid-cols-1 gap-3 md:grid-cols-3">

                            <!-- Approve -->
                            <label class="cursor-pointer">
                                <input
                                    type="radio"
                                    name="keputusan"
                                    value="diterima"
                                    class="peer sr-only"
                                    required>

                                <div class="h-full rounded-xl border-2 border-slate-200 p-4 transition hover:border-emerald-300 peer-checked:border-emerald-500 peer-checked:bg-emerald-50">

                                    <div class="font-semibold text-emerald-700">
                                        Setujui
                                    </div>

                                    <p class="mt-2 text-xs text-slate-500">
                                        Laporan telah memenuhi ketentuan dan dinyatakan terverifikasi.
                                    </p>

                                </div>
                            </label>

                            <!-- Revision -->
                            <label class="cursor-pointer">
                                <input
                                    type="radio"
                                    name="keputusan"
                                    value="revisi"
                                    class="peer sr-only">

                                <div class="h-full rounded-xl border-2 border-slate-200 p-4 transition hover:border-amber-300 peer-checked:border-amber-500 peer-checked:bg-amber-50">

                                    <div class="font-semibold text-amber-700">
                                        Minta Revisi
                                    </div>

                                    <p class="mt-2 text-xs text-slate-500">
                                        Laporan perlu diperbaiki oleh mahasiswa.
                                    </p>

                                </div>
                            </label>

                            <!-- Reject -->
                            <label class="cursor-pointer">
                                <input
                                    type="radio"
                                    name="keputusan"
                                    value="ditolak"
                                    class="peer sr-only">

                                <div class="h-full rounded-xl border-2 border-slate-200 p-4 transition hover:border-rose-300 peer-checked:border-rose-500 peer-checked:bg-rose-50">

                                    <div class="font-semibold text-rose-700">
                                        Tolak
                                    </div>

                                    <p class="mt-2 text-xs text-slate-500">
                                        Laporan tidak memenuhi ketentuan untuk diterima.
                                    </p>

                                </div>
                            </label>

                        </div>
                    </div>

                    <div>
                        <label
                            for="catatan"
                            class="mb-2 block text-sm font-semibold text-slate-700">
                            Catatan Pemeriksaan
                        </label>

                        <textarea
                            name="catatan"
                            id="catatan"
                            rows="5"
                            maxlength="5000"
                            placeholder="Tuliskan catatan pemeriksaan, kekurangan laporan, atau alasan penolakan..."
                            class="w-full resize-y rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500"></textarea>

                        <p class="mt-2 text-xs text-slate-400">
                            Wajib diisi apabila memilih Minta Revisi atau Tolak.
                        </p>
                    </div>

                    <div class="flex flex-col justify-end gap-3 sm:flex-row">

                        <a
                            href="<?= $e($detailUrl) ?>"
                            class="rounded-xl border border-slate-200 px-5 py-3 text-center text-sm font-semibold text-slate-600 hover:bg-slate-50">
                            Batal
                        </a>

                        <button
                            type="submit"
                            onclick="return confirm('Apakah kamu yakin dengan keputusan pemeriksaan ini?')"
                            class="rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                            Simpan Keputusan
                        </button>

                    </div>

                </form>

            <?php else: ?>

                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-sm font-semibold text-slate-700">
                        Laporan sudah tidak menunggu verifikasi Tendik.
                    </p>

                    <?php if (!empty($laporan['catatan'])): ?>
                        <p class="mt-3 whitespace-pre-line text-sm text-slate-600">
                            <?= $e($laporan['catatan']) ?>
                        </p>
                    <?php endif; ?>
                </div>

            <?php endif; ?>

        </section>

        <?php include __DIR__ . '/../Footer.php'; ?>

    </div>

</main>
