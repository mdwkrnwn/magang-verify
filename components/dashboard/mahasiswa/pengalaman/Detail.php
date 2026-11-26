<?php

$base = url('/');
$active = 'pengalaman';

/** @var array<string, mixed> $item */

$jenis = trim((string) ($item['jenis'] ?? 'lainnya'));
$posisi = trim((string) ($item['posisi'] ?? '-'));
$instansi = trim((string) ($item['instansi'] ?? '-'));
$lokasi = trim((string) ($item['lokasi'] ?? '-'));
$deskripsi = trim((string) ($item['deskripsi'] ?? ''));
$tanggalMulai = $item['tanggal_mulai'] ?? null;
$tanggalSelesai = $item['tanggal_selesai'] ?? null;
$linkPendukung = trim((string) ($item['link_pendukung'] ?? ''));
$buktiPath = trim((string) ($item['bukti_path'] ?? ''));

$fileUrl = $buktiPath !== ''
    ? '/' . ltrim($buktiPath, '/')
    : '';

$extension = strtolower(pathinfo($buktiPath, PATHINFO_EXTENSION));

$isGambar = in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true);
$isPdf = $extension === 'pdf';

$isOtomatis = !empty($item['is_otomatis']);

$jenisLabels = [
    'magang' => 'Magang',
    'pekerjaan' => 'Pekerjaan',
    'organisasi' => 'Organisasi',
    'freelance' => 'Freelance',
    'proyek' => 'Proyek',
    'lainnya' => 'Lainnya',
];

$jenisLabel = $jenisLabels[$jenis] ?? ucfirst($jenis);

$formatTanggal = static function ($tanggal): string {
    if (!$tanggal) {
        return '-';
    }

    $timestamp = strtotime((string) $tanggal);

    return $timestamp !== false
        ? date('d M Y', $timestamp)
        : (string) $tanggal;
};

$tanggalMulaiLabel = $formatTanggal($tanggalMulai);
$tanggalSelesaiLabel = $formatTanggal($tanggalSelesai);

$buktiUrl = $buktiPath !== ''
    ? url('/' . ltrim($buktiPath, '/'))
    : '';

$ekstensiBukti = strtolower(pathinfo($buktiPath, PATHINFO_EXTENSION));

$isGambar = in_array($ekstensiBukti, ['jpg', 'jpeg', 'png', 'webp'], true);
$isPdf = $ekstensiBukti === 'pdf';

?>

<main class="min-h-screen bg-slate-50 pt-20 lg:ml-64">

    <div class="px-4 py-6 sm:px-6 lg:px-8">

        <!-- BREADCRUMB -->
        <div class="mb-6">
            <a
                href="<?= e(url('/dashboard/mahasiswa/pengalaman')) ?>"
                class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 transition hover:text-blue-600">

                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="m15 18-6-6 6-6" />
                </svg>

                Kembali ke Pengalaman

            </a>
        </div>

        <!-- HEADER DETAIL -->
        <section class="mb-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-start">

                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-lg font-bold uppercase text-blue-600">
                    <?= e(mb_strtoupper(mb_substr($jenisLabel, 0, 2))) ?>
                </div>

                <div class="min-w-0 flex-1">

                    <div class="mb-3 flex flex-wrap items-center gap-2">

                        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                            <?= e($jenisLabel) ?>
                        </span>

                        <?php if ($isOtomatis): ?>
                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                Dari Sistem
                            </span>
                        <?php else: ?>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                Input Manual
                            </span>
                        <?php endif; ?>

                    </div>

                    <h1 class="break-words text-2xl font-bold tracking-tight text-gray-900">
                        <?= e($posisi) ?>
                    </h1>

                    <p class="mt-2 text-base font-medium text-gray-600">
                        <?= e($instansi) ?>
                    </p>

                    <p class="mt-2 text-sm text-gray-500">
                        <?= e($lokasi) ?>
                    </p>

                </div>

                <?php if (!$isOtomatis): ?>
                    <a
                        href="<?= e(url('/dashboard/mahasiswa/pengalaman/edit/' . abs((int) ($item['id'] ?? 0)))) ?>"
                        class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-slate-50">

                        <svg class="h-4 w-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="m16.5 3.5 4 4L8 20H4v-4L16.5 3.5Z" />
                        </svg>

                        Edit Pengalaman
                    </a>
                <?php endif; ?>

            </div>

        </section>

        <!-- INFORMASI PENGALAMAN -->
        <section class="mb-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            <h2 class="text-lg font-bold text-gray-900">
                Informasi Pengalaman
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Informasi lengkap mengenai pengalaman yang kamu miliki.
            </p>

            <div class="mt-6 grid gap-6 sm:grid-cols-2">

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Jenis Pengalaman
                    </p>
                    <p class="mt-2 text-sm font-medium text-gray-800">
                        <?= e($jenisLabel) ?>
                    </p>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Posisi / Peran
                    </p>
                    <p class="mt-2 text-sm font-medium text-gray-800">
                        <?= e($posisi) ?>
                    </p>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Instansi
                    </p>
                    <p class="mt-2 text-sm font-medium text-gray-800">
                        <?= e($instansi) ?>
                    </p>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Lokasi
                    </p>
                    <p class="mt-2 text-sm font-medium text-gray-800">
                        <?= e($lokasi) ?>
                    </p>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Tanggal Mulai
                    </p>
                    <p class="mt-2 text-sm font-medium text-gray-800">
                        <?= e($tanggalMulaiLabel) ?>
                    </p>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Tanggal Selesai
                    </p>
                    <p class="mt-2 text-sm font-medium text-gray-800">
                        <?= e($tanggalSelesaiLabel) ?>
                    </p>
                </div>

            </div>

        </section>

        <!-- DESKRIPSI -->
        <section class="mb-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            <h2 class="text-lg font-bold text-gray-900">
                Deskripsi Pengalaman
            </h2>

            <div class="mt-4 rounded-xl bg-slate-50 p-5">

                <?php if ($deskripsi !== ''): ?>
                    <p class="whitespace-pre-line break-words text-sm leading-7 text-gray-600"><?= e($deskripsi) ?></p>
                <?php else: ?>
                    <p class="text-sm italic text-gray-400">
                        Belum ada deskripsi pengalaman.
                    </p>
                <?php endif; ?>

            </div>

        </section>

        <!-- LINK PENDUKUNG -->
        <section class="mb-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            <h2 class="text-lg font-bold text-gray-900">
                Link Pendukung
            </h2>

            <?php if ($linkPendukung !== '' && filter_var($linkPendukung, FILTER_VALIDATE_URL)): ?>

                <a
                    href="<?= e($linkPendukung) ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="mt-4 inline-flex items-center gap-2 break-all text-sm font-medium text-blue-600 hover:text-blue-700">

                    <?= e($linkPendukung) ?>

                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M14 3h7v7m-1-6L10 14M19 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h6" />
                    </svg>

                </a>

            <?php else: ?>

                <p class="mt-3 text-sm italic text-gray-400">
                    Tidak ada link pendukung.
                </p>

            <?php endif; ?>

        </section>

        <!-- BUKTI PENGALAMAN -->
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

            <h2 class="text-lg font-bold text-gray-900">
                Bukti Pengalaman
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Dokumen atau gambar bukti pengalaman.
            </p>

            <?php if ($buktiUrl !== ''): ?>

                <div class="mt-5">

                    <!-- <?php if ($isGambar): ?>

                        <?php
                                $gambarUrl = '/' . ltrim($buktiPath, '/');
                        ?>

                        <button
                            type="button"
                            onclick="openBuktiModal()"
                            class="block w-full cursor-zoom-in text-left">
                            <img
                                src="<?= e($gambarUrl) ?>"
                                alt="Bukti pengalaman"
                                class="max-h-[500px] w-full rounded-xl border border-slate-200 bg-slate-50 object-contain">
                        </button>

                        <p class="mt-3 text-xs text-gray-400">
                            Klik gambar untuk melihat ukuran penuh.
                        </p>

                <?php elseif ($isPdf): ?>

                    <div class="mt-5 overflow-hidden rounded-xl border border-slate-200 bg-white">
                        <iframe
                            src="<?= e($fileUrl) ?>"
                            title="Dokumen bukti pengalaman"
                            class="h-[700px] w-full"
                            loading="lazy"></iframe>
                    </div>

                <?php else: ?>

                    <p class="text-sm text-gray-500">
                        Format file belum didukung untuk pratinjau.
                    </p>

                <?php endif; ?> -->

                <a
                    href="<?= e($fileUrl) ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="mt-4 inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-slate-50">
                    Buka File Bukti
                </a>

                </div>

            <?php else: ?>

                <div class="mt-5 rounded-xl border border-dashed border-slate-300 bg-slate-50 px-5 py-10 text-center">

                    <p class="text-sm font-medium text-gray-500">
                        Belum ada bukti pengalaman yang dilampirkan.
                    </p>

                </div>

            <?php endif; ?>

        </section>

    </div>

</main>