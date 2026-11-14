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

            <div class="mb-7 mt-5">
                <p class="mb-2 text-xs font-semibold uppercase tracking-[0.16em] text-blue-600">Manajemen Magang</p>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Edit Lowongan Magang</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">Perbarui informasi posisi, mitra, kuota, dan periode pelaksanaan.</p>
            </div>

            <?php if (!empty($error)): ?>
                <div role="alert" class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= e(url('/dashboard/koordinator-magang/lowongan/edit/' . (int) $lowongan['id'])) ?>" class="space-y-6">
                <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">

                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
                        <h2 class="font-semibold text-slate-900">Informasi Posisi</h2>
                        <p class="mt-1 text-xs text-slate-500">Informasi utama lowongan magang.</p>
                    </div>
                    <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 sm:p-6">
                        <div class="sm:col-span-2">
                            <label for="judul" class="mb-2 block text-sm font-semibold text-slate-700">Judul Posisi <span class="text-red-500">*</span></label>
                            <input id="judul" name="judul" type="text" maxlength="200" required value="<?= e((string) ($form['judul'] ?? '')) ?>" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="mitra_id" class="mb-2 block text-sm font-semibold text-slate-700">Perusahaan / Instansi Mitra <span class="text-red-500">*</span></label>
                            <select id="mitra_id" name="mitra_id" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                                <?php foreach ($mitraList as $mitra): ?>
                                    <option value="<?= (int) $mitra['id'] ?>" <?= (string) ($form['mitra_id'] ?? '') === (string) $mitra['id'] ? 'selected' : '' ?>>
                                        <?= e((string) $mitra['nama_perusahaan']) ?> (<?= e((string) $mitra['kode_perusahaan']) ?>)
                                    </option>
                                <?php endforeach; ?>
                                <?php if (!empty($lowongan['mitra_id']) && !in_array((int) $lowongan['mitra_id'], array_map(static fn($m) => (int) $m['id'], $mitraList), true)): ?>
                                    <option value="<?= (int) $lowongan['mitra_id'] ?>" selected disabled><?= e((string) $lowongan['nama_perusahaan']) ?> (mitra nonaktif - tidak dapat dipilih)</option>
                                <?php endif; ?>
                            </select>
                            <p class="mt-1.5 text-xs text-slate-400">Mitra nonaktif tidak dapat dipilih sebagai mitra baru.</p>
                        </div>

                        <div>
                            <label for="bidang" class="mb-2 block text-sm font-semibold text-slate-700">Bidang Pekerjaan</label>
                            <input id="bidang" name="bidang" type="text" maxlength="150" value="<?= e((string) ($form['bidang'] ?? '')) ?>" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                        </div>

                        <div>
                            <label for="lokasi_magang" class="mb-2 block text-sm font-semibold text-slate-700">Lokasi Magang</label>
                            <input id="lokasi_magang" name="lokasi_magang" type="text" maxlength="255" value="<?= e((string) ($form['lokasi_magang'] ?? '')) ?>" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="deskripsi" class="mb-2 block text-sm font-semibold text-slate-700">Deskripsi Pekerjaan <span class="text-red-500">*</span></label>
                            <textarea id="deskripsi" name="deskripsi" rows="5" required class="w-full resize-y rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm leading-6 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"><?= e((string) ($form['deskripsi'] ?? '')) ?></textarea>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="persyaratan" class="mb-2 block text-sm font-semibold text-slate-700">Persyaratan Peserta</label>
                            <textarea id="persyaratan" name="persyaratan" rows="4" class="w-full resize-y rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm leading-6 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"><?= e((string) ($form['persyaratan'] ?? '')) ?></textarea>
                        </div>
                    </div>
                </section>

                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
                        <h2 class="font-semibold text-slate-900">Kuota dan Periode</h2>
                    </div>
                    <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 sm:p-6">
                        <div>
                            <label for="jumlah_kuota" class="mb-2 block text-sm font-semibold text-slate-700">Jumlah Kuota <span class="text-red-500">*</span></label>
                            <input id="jumlah_kuota" name="jumlah_kuota" type="number" min="1" max="32767" step="1" required value="<?= e((string) ($form['jumlah_kuota'] ?? '1')) ?>" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                            <p class="mt-1.5 text-xs text-slate-400">Kuota tidak boleh lebih kecil daripada jumlah mahasiswa yang sudah diterima: <?= (int) $lowongan['jumlah_diterima'] ?>.</p>
                        </div>

                        <div>
                            <label for="sistem_kerja" class="mb-2 block text-sm font-semibold text-slate-700">Sistem Kerja <span class="text-red-500">*</span></label>
                            <select id="sistem_kerja" name="sistem_kerja" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                                <option value="onsite" <?= ($form['sistem_kerja'] ?? '') === 'onsite' ? 'selected' : '' ?>>Onsite</option>
                                <option value="hybrid" <?= ($form['sistem_kerja'] ?? '') === 'hybrid' ? 'selected' : '' ?>>Hybrid</option>
                                <option value="remote" <?= ($form['sistem_kerja'] ?? '') === 'remote' ? 'selected' : '' ?>>Remote</option>
                            </select>
                        </div>

                        <div>
                            <label for="tanggal_mulai" class="mb-2 block text-sm font-semibold text-slate-700">Tanggal Mulai <span class="text-red-500">*</span></label>
                            <input id="tanggal_mulai" name="tanggal_mulai" type="date" required value="<?= e((string) ($form['tanggal_mulai'] ?? '')) ?>" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                        </div>

                        <div>
                            <label for="tanggal_selesai" class="mb-2 block text-sm font-semibold text-slate-700">Tanggal Selesai <span class="text-red-500">*</span></label>
                            <input id="tanggal_selesai" name="tanggal_selesai" type="date" required value="<?= e((string) ($form['tanggal_selesai'] ?? '')) ?>" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                        </div>

                        <div>
                            <label for="tahun_akademik" class="mb-2 block text-sm font-semibold text-slate-700">Tahun Akademik <span class="text-red-500">*</span></label>
                            <input id="tahun_akademik" name="tahun_akademik" type="text" maxlength="20" required value="<?= e((string) ($form['tahun_akademik'] ?? '')) ?>" placeholder="Contoh: 2026/2027" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                        </div>

                        <div>
                            <label for="status" class="mb-2 block text-sm font-semibold text-slate-700">Status <span class="text-red-500">*</span></label>
                            <select id="status" name="status" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                                <?php foreach (['draft' => 'Draft', 'dibuka' => 'Dibuka', 'ditutup' => 'Ditutup', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'] as $value => $label): ?>
                                    <option value="<?= e($value) ?>" <?= ($form['status'] ?? '') === $value ? 'selected' : '' ?>><?= e($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </section>

                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <a href="<?= e(url('/dashboard/koordinator-magang/lowongan')) ?>" class="inline-flex min-h-[46px] items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">Batal</a>
                    <button type="submit" class="inline-flex min-h-[46px] items-center justify-center rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </main>
    <script>
        (() => {
            const start = document.getElementById('tanggal_mulai');
            const end = document.getElementById('tanggal_selesai');
            if (!start || !end) return;
            start.addEventListener('change', () => {
                end.min = start.value;
                if (end.value && end.value < start.value) end.value = '';
            });
        })();
    </script>
</body>

</html>