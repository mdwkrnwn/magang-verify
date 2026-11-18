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

$status = $laporan['status_laporan'] ?? 'diajukan';

$statusLabel = match ($status) {
    'diajukan' => 'Menunggu Pemeriksaan Dosen',
    'diperiksa_dosen' => 'Sudah Diperiksa Dosen',
    'perlu_revisi' => 'Perlu Revisi',
    'menunggu_verifikasi' => 'Menunggu Verifikasi Tendik',
    'terverifikasi' => 'Terverifikasi',
    'ditolak' => 'Ditolak',
    default => ucfirst(str_replace('_', ' ', $status)),
};

$statusStyle = match ($status) {
    'diajukan' => 'bg-blue-50 text-blue-700',
    'diperiksa_dosen' => 'bg-emerald-50 text-emerald-700',
    'perlu_revisi' => 'bg-amber-50 text-amber-700',
    'menunggu_verifikasi' => 'bg-indigo-50 text-indigo-700',
    'terverifikasi' => 'bg-green-50 text-green-700',
    'ditolak' => 'bg-rose-50 text-rose-700',
    default => 'bg-slate-100 text-slate-600',
};

$previewUrl = url(
    '/dashboard/dosen/verifikasi-laporan/berkas/' . $laporanId
);

?>

<main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">
    <div class="max-w-[1600px] mx-auto px-4 py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <a
                href="<?= $e(url('/dashboard/dosen/verifikasi-laporan')) ?>"
                class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-blue-600 mb-4">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 shrink-0"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    aria-hidden="true">
                    <path d="m12 19-7-7 7-7" />
                    <path d="M19 12H5" />
                </svg>
                Kembali ke daftar laporan
            </a>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-blue-600 mb-1">
                        Verifikasi Laporan Magang
                    </p>

                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-800">
                        Detail Laporan
                    </h1>

                    <p class="text-sm text-slate-500 mt-1">
                        Periksa dokumen dan informasi laporan mahasiswa.
                    </p>
                </div>

                <span class="inline-flex w-fit px-4 py-2 rounded-full text-sm font-semibold <?= $statusStyle ?>">
                    <?= $e($statusLabel) ?>
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            <!-- Informasi laporan -->
            <div class="xl:col-span-1 space-y-6">

                <!-- Identitas Mahasiswa -->
                <section class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                            <i class="fa-regular fa-user text-lg"></i>
                        </div>

                        <div>
                            <h2 class="font-bold text-slate-800">
                                Identitas Mahasiswa
                            </h2>
                            <p class="text-xs text-slate-400">
                                Informasi mahasiswa bimbingan
                            </p>
                        </div>
                    </div>

                    <div class="space-y-4 text-sm">
                        <div>
                            <p class="text-slate-400 mb-1">Nama mahasiswa</p>
                            <p class="font-semibold text-slate-800">
                                <?= $e($laporan['mahasiswa_nama']) ?>
                            </p>
                        </div>

                        <div>
                            <p class="text-slate-400 mb-1">NIM</p>
                            <p class="font-medium text-slate-700">
                                <?= $e($laporan['nim']) ?>
                            </p>
                        </div>

                        <div>
                            <p class="text-slate-400 mb-1">Program studi</p>
                            <p class="font-medium text-slate-700">
                                <?= $e($laporan['program_studi']) ?>
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Informasi Magang -->
                <section class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <i class="fa-regular fa-building text-lg"></i>
                        </div>

                        <div>
                            <h2 class="font-bold text-slate-800">
                                Informasi Magang
                            </h2>
                            <p class="text-xs text-slate-400">
                                Detail penempatan mahasiswa
                            </p>
                        </div>
                    </div>

                    <div class="space-y-4 text-sm">
                        <div>
                            <p class="text-slate-400 mb-1">Perusahaan</p>
                            <p class="font-semibold text-slate-800">
                                <?= $e($laporan['nama_perusahaan']) ?>
                            </p>
                        </div>

                        <div>
                            <p class="text-slate-400 mb-1">Formasi magang</p>
                            <p class="font-medium text-slate-700">
                                <?= $e($laporan['judul_formasi']) ?>
                            </p>
                        </div>

                        <div>
                            <p class="text-slate-400 mb-1">Periode magang</p>
                            <p class="font-medium text-slate-700">
                                <?= $e($laporan['tanggal_mulai']) ?>
                                –
                                <?= $e($laporan['tanggal_selesai']) ?>
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Informasi Laporan -->
                <section class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                    <h2 class="font-bold text-slate-800 mb-4">
                        Informasi Laporan
                    </h2>

                    <div class="space-y-4 text-sm">
                        <div>
                            <p class="text-slate-400 mb-1">Jenis laporan</p>
                            <p class="font-medium text-slate-700">
                                <?= $e($namaJenis) ?>
                            </p>
                        </div>

                        <?php if ($laporan['minggu_ke'] !== null): ?>
                            <div>
                                <p class="text-slate-400 mb-1">Minggu ke</p>
                                <p class="font-medium text-slate-700">
                                    <?= $e($laporan['minggu_ke']) ?>
                                </p>
                            </div>
                        <?php endif; ?>

                        <div>
                            <p class="text-slate-400 mb-1">Versi laporan</p>
                            <p class="font-medium text-slate-700">
                                Versi <?= $e($laporan['versi_terkini']) ?>
                            </p>
                        </div>

                        <div>
                            <p class="text-slate-400 mb-1">Tanggal pengajuan</p>
                            <p class="font-medium text-slate-700">
                                <?= $e($laporan['diajukan_pada']) ?>
                            </p>
                        </div>

                        <div>
                            <p class="text-slate-400 mb-1">Judul laporan</p>
                            <p class="font-semibold text-slate-800">
                                <?= $e($laporan['judul_laporan']) ?>
                            </p>
                        </div>
                    </div>
                </section>

            </div>

            <!-- PDF Preview -->
            <section class="xl:col-span-2 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

                <div class="px-5 py-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="font-bold text-slate-800">
                            Pratinjau Dokumen
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">
                            Dokumen laporan mahasiswa dalam format PDF
                        </p>
                    </div>

                    <a
                        href="<?= $e($previewUrl) ?>"
                        target="_blank"
                        rel="noopener"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">
                        <i class="fa-solid fa-up-right-from-square"></i>
                        Buka PDF
                    </a>
                </div>

                <div class="bg-slate-100 p-2">
                    <iframe
                        src="<?= $e($previewUrl) ?>"
                        class="w-full h-[650px] lg:h-[850px] rounded-lg bg-white"
                        title="Pratinjau laporan magang"></iframe>
                </div>
            </section>

        </div>

        <!-- Area Pemeriksaan -->
        <section class="mt-6 bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">

            <div class="flex items-center gap-3 mb-5">
                <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i class="fa-solid fa-clipboard-check text-lg"></i>
                </div>

                <div>
                    <h2 class="font-bold text-slate-800">
                        Pemeriksaan Dosen
                    </h2>
                    <p class="text-xs text-slate-400">
                        Berikan keputusan berdasarkan hasil pemeriksaan dokumen.
                    </p>
                </div>
            </div>

            <?php if (!empty($_SESSION['flash_error'])): ?>
                <div class="mb-4 rounded-xl bg-rose-50 border border-rose-200 p-4 text-sm text-rose-700">
                    <?= $e($_SESSION['flash_error']) ?>
                </div>
                <?php unset($_SESSION['flash_error']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['flash_success'])): ?>
                <div class="mb-4 rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-700">
                    <?= $e($_SESSION['flash_success']) ?>
                </div>
                <?php unset($_SESSION['flash_success']); ?>
            <?php endif; ?>

            <?php if (($laporan['status_laporan'] ?? '') === 'diajukan'): ?>

                <form
                    action="<?= $e(url(
                                '/dashboard/dosen/verifikasi-laporan/detail/'
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
                        <label class="block text-sm font-semibold text-slate-700 mb-3">
                            Pilih Keputusan
                        </label>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                            <label class="cursor-pointer">
                                <input
                                    type="radio"
                                    name="keputusan"
                                    value="diterima"
                                    class="peer sr-only"
                                    required>

                                <div class="h-full border-2 border-slate-200 rounded-xl p-4 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 hover:border-emerald-300 transition">
                                    <div class="flex items-center gap-2 text-emerald-700 font-semibold">
                                        <i class="fa-solid fa-circle-check"></i>
                                        Terima
                                    </div>

                                    <p class="text-xs text-slate-500 mt-2">
                                        Laporan memenuhi ketentuan dan dapat diteruskan ke tahap berikutnya.
                                    </p>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input
                                    type="radio"
                                    name="keputusan"
                                    value="revisi"
                                    class="peer sr-only">

                                <div class="h-full border-2 border-slate-200 rounded-xl p-4 peer-checked:border-amber-500 peer-checked:bg-amber-50 hover:border-amber-300 transition">
                                    <div class="flex items-center gap-2 text-amber-700 font-semibold">
                                        <i class="fa-solid fa-rotate"></i>
                                        Minta Revisi
                                    </div>

                                    <p class="text-xs text-slate-500 mt-2">
                                        Laporan perlu diperbaiki oleh mahasiswa.
                                    </p>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input
                                    type="radio"
                                    name="keputusan"
                                    value="ditolak"
                                    class="peer sr-only">

                                <div class="h-full border-2 border-slate-200 rounded-xl p-4 peer-checked:border-rose-500 peer-checked:bg-rose-50 hover:border-rose-300 transition">
                                    <div class="flex items-center gap-2 text-rose-700 font-semibold">
                                        <i class="fa-solid fa-circle-xmark"></i>
                                        Tolak
                                    </div>

                                    <p class="text-xs text-slate-500 mt-2">
                                        Laporan tidak memenuhi ketentuan untuk diterima.
                                    </p>
                                </div>
                            </label>

                        </div>
                    </div>

                    <div>
                        <label
                            for="catatan"
                            class="block text-sm font-semibold text-slate-700 mb-2">
                            Catatan Pemeriksaan
                        </label>

                        <textarea
                            name="catatan"
                            id="catatan"
                            rows="5"
                            maxlength="5000"
                            placeholder="Tuliskan catatan pemeriksaan, kekurangan laporan, atau alasan penolakan..."
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-y"></textarea>

                        <p class="text-xs text-slate-400 mt-2">
                            Wajib diisi apabila memilih Minta Revisi atau Tolak.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end gap-3">

                        <a
                            href="<?= $e(url('/dashboard/dosen/verifikasi-laporan')) ?>"
                            class="px-5 py-3 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold text-center hover:bg-slate-50">
                            Batal
                        </a>

                        <button
                            type="submit"
                            onclick="return confirm('Apakah kamu yakin dengan keputusan pemeriksaan ini?')"
                            class="px-6 py-3 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">
                            Simpan Keputusan
                        </button>

                    </div>

                </form>

            <?php else: ?>

                <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                    <p class="font-semibold text-slate-700 text-sm">
                        Laporan sudah tidak menunggu pemeriksaan dosen.
                    </p>

                    <?php if (!empty($laporan['catatan'])): ?>
                        <p class="text-sm text-slate-600 mt-3 whitespace-pre-line">
                            <?= $e($laporan['catatan']) ?>
                        </p>
                    <?php endif; ?>
                </div>

            <?php endif; ?>

        </section>

    </div>
</main>