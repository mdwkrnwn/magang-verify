<?php

declare(strict_types=1);
$active = 'lowongan';
?>
<!DOCTYPE html>
<html lang="id">
<?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Head.php'; ?>

<body class="overflow-x-hidden bg-slate-50 font-[Poppins] text-slate-900 antialiased">
    <?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Sidebar.php'; ?>
    <?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Header.php'; ?>
    <main class="min-h-screen bg-slate-50 pt-20 lg:ml-64">

        <?php
        $statusLabels = [
            'draft' => ['Draft', 'bg-slate-100 text-slate-700'],
            'dibuka' => ['Dibuka', 'bg-emerald-50 text-emerald-700'],
            'ditutup' => ['Ditutup', 'bg-amber-50 text-amber-700'],
            'selesai' => ['Selesai', 'bg-blue-50 text-blue-700'],
            'dibatalkan' => ['Dibatalkan', 'bg-red-50 text-red-700'],
        ];
        $statusInfo = $statusLabels[$lowongan['status']] ?? [ucfirst((string) $lowongan['status']), 'bg-slate-100 text-slate-700'];
        $quota = (int) $lowongan['jumlah_kuota'];
        $accepted = (int) $lowongan['jumlah_diterima'];
        $progress = $quota > 0 ? min(100, ($accepted / $quota) * 100) : 0;
        ?>
        <div class="mx-auto max-w-8xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
            <a
                href="<?= e(url('/dashboard/koordinator-magang/lowongan')) ?>"
                class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-blue-600">

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

                <span>Kembali ke Lowongan</span>
            </a>

            <div class="mb-7 mt-5 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="mb-2 text-xs font-semibold uppercase tracking-[0.16em] text-blue-600">Detail Lowongan Magang</p>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl"><?= e((string) $lowongan['judul']) ?></h1>
                    <p class="mt-2 text-sm text-slate-500"><?= e((string) $lowongan['nama_perusahaan']) ?> · <?= e((string) ($lowongan['kode_perusahaan'] ?? '')) ?></p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex rounded-full px-3 py-1.5 text-xs font-semibold <?= e($statusInfo[1]) ?>"><?= e($statusInfo[0]) ?></span>
                    <a href="<?= e(url('/dashboard/koordinator-magang/lowongan/edit/' . (int) $lowongan['id'])) ?>" class="inline-flex min-h-[40px] items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">Edit Lowongan</a>
                </div>
            </div>

            <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Kuota tersedia</p>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-3xl font-bold text-slate-900"><?= max(0, $quota - $accepted) ?></span>
                        <span class="text-sm text-slate-400">dari <?= $quota ?> kuota</span>
                    </div>
                    <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full bg-blue-500" style="width: <?= $progress ?>%"></div>
                    </div>
                    <p class="mt-2 text-xs text-slate-400"><?= $accepted ?> mahasiswa diterima</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Sistem kerja</p>
                    <p class="mt-3 text-xl font-bold capitalize text-slate-900"><?= e((string) $lowongan['sistem_kerja']) ?></p>
                    <p class="mt-2 text-xs text-slate-400">Metode pelaksanaan magang</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Tahun akademik</p>
                    <p class="mt-3 text-xl font-bold text-slate-900"><?= e((string) $lowongan['tahun_akademik']) ?></p>
                    <p class="mt-2 text-xs text-slate-400">Periode akademik lowongan</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="space-y-6 lg:col-span-2">
                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <h2 class="font-semibold text-slate-900">Deskripsi Pekerjaan</h2>
                        <div class="mt-4 whitespace-pre-line text-sm leading-7 text-slate-600"><?= e((string) $lowongan['deskripsi']) ?></div>
                        <?php if (!empty($lowongan['bidang'])): ?>
                            <div class="mt-5 border-t border-slate-100 pt-4">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Bidang pekerjaan</p>
                                <p class="mt-1 text-sm font-medium text-slate-700"><?= e((string) $lowongan['bidang']) ?></p>
                            </div>
                        <?php endif; ?>
                    </section>

                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <h2 class="font-semibold text-slate-900">Persyaratan Peserta</h2>
                        <?php if (!empty($lowongan['persyaratan'])): ?>
                            <div class="mt-4 whitespace-pre-line text-sm leading-7 text-slate-600"><?= e((string) $lowongan['persyaratan']) ?></div>
                        <?php else: ?>
                            <p class="mt-3 text-sm text-slate-400">Belum ada persyaratan khusus yang dicantumkan.</p>
                        <?php endif; ?>
                    </section>
                </div>

                <aside class="space-y-6">
                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <h2 class="font-semibold text-slate-900">Informasi Pelaksanaan</h2>
                        <dl class="mt-4 space-y-4">
                            <div>
                                <dt class="text-xs text-slate-400">Perusahaan mitra</dt>
                                <dd class="mt-1 text-sm font-semibold text-slate-800"><?= e((string) $lowongan['nama_perusahaan']) ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-400">Lokasi magang</dt>
                                <dd class="mt-1 text-sm font-medium text-slate-700"><?= e((string) ($lowongan['lokasi_magang'] ?: 'Belum ditentukan')) ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-400">Tanggal mulai</dt>
                                <dd class="mt-1 text-sm font-medium text-slate-700"><?= e(date('d F Y', strtotime((string) $lowongan['tanggal_mulai']))) ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-400">Tanggal selesai</dt>
                                <dd class="mt-1 text-sm font-medium text-slate-700"><?= e(date('d F Y', strtotime((string) $lowongan['tanggal_selesai']))) ?></dd>
                            </div>
                        </dl>
                    </section>

                    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <h2 class="font-semibold text-slate-900">Informasi Pencatatan</h2>
                        <dl class="mt-4 space-y-3">
                            <div>
                                <dt class="text-xs text-slate-400">ID Lowongan</dt>
                                <dd class="mt-1 text-sm font-medium text-slate-700">#<?= (int) $lowongan['id'] ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-400">Dibuat pada</dt>
                                <dd class="mt-1 text-sm font-medium text-slate-700"><?= e(date('d F Y, H:i', strtotime((string) $lowongan['created_at']))) ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs text-slate-400">Terakhir diperbarui</dt>
                                <dd class="mt-1 text-sm font-medium text-slate-700"><?= e(date('d F Y, H:i', strtotime((string) $lowongan['updated_at']))) ?></dd>
                            </div>
                        </dl>
                    </section>
                </aside>
            </div>
        </div>
    </main>
</body>

</html>