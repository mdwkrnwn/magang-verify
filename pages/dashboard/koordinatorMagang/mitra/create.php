<?php

declare(strict_types=1);

$active = 'mitra';

?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Head.php'; ?>

<body class="overflow-x-hidden bg-slate-50 font-[Poppins] text-slate-900 antialiased">

    <?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Sidebar.php'; ?>
    <?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Header.php'; ?>

    <main class="min-h-screen bg-slate-50 pt-20 lg:ml-64">
        <div class="mx-auto max-w-8xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">

            <div class="mb-6">
                <a href="<?= e(url('/dashboard/koordinator-magang/mitra')) ?>" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-blue-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 18-6-6 6-6"/>
                    </svg>
                    Kembali ke Data Mitra
                </a>
                <div class="mt-4">
                    <p class="mb-2 text-xs font-semibold uppercase tracking-[0.16em] text-blue-600">Manajemen Mitra</p>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Tambah Mitra Baru</h1>
                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Lengkapi informasi perusahaan atau instansi yang akan menjadi mitra program magang.
                    </p>
                </div>
            </div>

            <?php if ($error): ?>
                <div role="alert" class="mb-6 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm text-red-800">
                    <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.3 3.8 1.9 18.2A2 2 0 0 0 3.6 21h16.8a2 2 0 0 0 1.7-2.8L13.7 3.8a2 2 0 0 0-3.4 0Z"/>
                    </svg>
                    <span><?= e($error) ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= e(url('/dashboard/koordinator-magang/mitra/tambah')) ?>" class="space-y-6">
                <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">

                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-start gap-3 border-b border-slate-100 px-5 py-5 sm:px-6">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 21h18M5 21V7l8-4v18m6 0V11l-6-4M9 9v.01M9 12v.01M9 15v.01M9 18v.01"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-semibold text-slate-900">Informasi Perusahaan</h2>
                            <p class="mt-1 text-sm text-slate-500">Identitas dasar dan klasifikasi mitra.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 p-5 sm:p-6 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label for="nama_perusahaan" class="mb-2 block text-sm font-semibold text-slate-700">Nama Perusahaan / Instansi <span class="text-red-500">*</span></label>
                            <input id="nama_perusahaan" name="nama_perusahaan" required maxlength="200" value="<?= e($form['nama_perusahaan']) ?>" placeholder="Contoh: PT Teknologi Indonesia"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                        </div>

                        <div>
                            <label for="kode_perusahaan" class="mb-2 block text-sm font-semibold text-slate-700">Kode Perusahaan <span class="text-red-500">*</span></label>
                            <input id="kode_perusahaan" name="kode_perusahaan" required maxlength="50" value="<?= e($form['kode_perusahaan']) ?>" placeholder="Contoh: PTI-001"
                                pattern="[A-Za-z0-9][A-Za-z0-9_-]{1,49}"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm uppercase outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                            <p class="mt-1.5 text-xs text-slate-400">Unik untuk setiap mitra. Gunakan huruf, angka, - atau _.</p>
                        </div>

                        <div>
                            <label for="kategori" class="mb-2 block text-sm font-semibold text-slate-700">Kategori Mitra <span class="text-red-500">*</span></label>
                            <select id="kategori" name="kategori" required
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                                <option value="">Pilih kategori</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= e($category) ?>" <?= $form['kategori'] === $category ? 'selected' : '' ?>><?= e($category) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label for="bidang_usaha" class="mb-2 block text-sm font-semibold text-slate-700">Bidang Usaha</label>
                            <input id="bidang_usaha" name="bidang_usaha" maxlength="150" value="<?= e($form['bidang_usaha']) ?>" placeholder="Contoh: Teknologi Informasi, Manufaktur, Pendidikan"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                        </div>

                        <div class="md:col-span-2">
                            <label for="deskripsi" class="mb-2 block text-sm font-semibold text-slate-700">Deskripsi Perusahaan</label>
                            <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Jelaskan profil singkat, kegiatan, atau layanan perusahaan..."
                                class="w-full resize-y rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"><?= e($form['deskripsi']) ?></textarea>
                        </div>
                    </div>
                </section>

                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-start gap-3 border-b border-slate-100 px-5 py-5 sm:px-6">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l9 6 9-6M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H3a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2Z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-semibold text-slate-900">Informasi Kontak</h2>
                            <p class="mt-1 text-sm text-slate-500">Kontak resmi yang dapat dihubungi.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 p-5 sm:p-6 md:grid-cols-2">
                        <div>
                            <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                            <input id="email" name="email" type="email" maxlength="255" value="<?= e($form['email']) ?>" placeholder="nama@perusahaan.com"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                        </div>
                        <div>
                            <label for="no_telepon" class="mb-2 block text-sm font-semibold text-slate-700">Nomor Telepon</label>
                            <input id="no_telepon" name="no_telepon" type="tel" maxlength="30" value="<?= e($form['no_telepon']) ?>" placeholder="Contoh: 081234567890"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                        </div>
                        <div class="md:col-span-2">
                            <label for="website" class="mb-2 block text-sm font-semibold text-slate-700">Website</label>
                            <input id="website" name="website" type="url" maxlength="255" value="<?= e($form['website']) ?>" placeholder="https://www.perusahaan.com"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                        </div>
                    </div>
                </section>

                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-start gap-3 border-b border-slate-100 px-5 py-5 sm:px-6">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 21s8-4.5 8-11a8 8 0 1 0-16 0c0 6.5 8 11 8 11Zm0-8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-semibold text-slate-900">Lokasi Perusahaan</h2>
                            <p class="mt-1 text-sm text-slate-500">Alamat kantor atau lokasi operasional mitra.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 p-5 sm:p-6 md:grid-cols-2">
                        <div>
                            <label for="provinsi" class="mb-2 block text-sm font-semibold text-slate-700">Provinsi</label>
                            <input id="provinsi" name="provinsi" maxlength="100" value="<?= e($form['provinsi']) ?>" placeholder="Contoh: Jawa Timur"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                        </div>
                        <div>
                            <label for="kota" class="mb-2 block text-sm font-semibold text-slate-700">Kota / Kabupaten</label>
                            <input id="kota" name="kota" maxlength="100" value="<?= e($form['kota']) ?>" placeholder="Contoh: Pasuruan"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                        </div>
                        <div class="md:col-span-2">
                            <label for="alamat" class="mb-2 block text-sm font-semibold text-slate-700">Alamat Lengkap</label>
                            <textarea id="alamat" name="alamat" rows="3" placeholder="Nama jalan, nomor, kelurahan/desa, kecamatan, dan kode pos..."
                                class="w-full resize-y rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"><?= e($form['alamat']) ?></textarea>
                        </div>
                    </div>
                </section>

                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-xs text-slate-400"><span class="text-red-500">*</span> Wajib diisi. Status awal mitra: Dalam peninjauan.</p>
                    <div class="flex flex-col-reverse gap-2 sm:flex-row">
                        <a href="<?= e(url('/dashboard/koordinator-magang/mitra')) ?>"
                            class="inline-flex min-h-[44px] items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex min-h-[44px] items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm shadow-blue-200 transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 13 4 4L19 7"/>
                            </svg>
                            Simpan Data Mitra
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>

</body>
</html>
