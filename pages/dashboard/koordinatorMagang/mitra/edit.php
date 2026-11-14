<?php


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
            <a
                href="<?= e(url('/dashboard/koordinator-magang/mitra')) ?>"
                class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-blue-600 transition-colors">

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

                <span>Kembali ke Data Mitra</span>
            </a>
            <div class="mb-6 mt-4">
                <p class="mb-2 text-xs font-semibold uppercase tracking-[0.16em] text-blue-600">Manajemen Mitra</p>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Edit Data Mitra</h1>
                <p class="mt-2 text-sm text-slate-500">Perbarui informasi perusahaan atau instansi mitra.</p>
            </div>
            <?php if ($error): ?>
                <div role="alert" class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><?= e($error) ?></div>
            <?php endif; ?>
            <form method="POST" action="<?= e(url('/dashboard/koordinator-magang/mitra/edit/' . (int) $mitra['id'])) ?>" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
                <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div class="">
                        <label for="nama_perusahaan" class="mb-2 block text-sm font-semibold text-slate-700">Nama Perusahaan / Instansi <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_perusahaan" id="nama_perusahaan" value="<?= e((string) ($form["nama_perusahaan"] ?? "")) ?>" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    </div>
                    <div class="">
                        <label for="kode_perusahaan" class="mb-2 block text-sm font-semibold text-slate-700">Kode Perusahaan <span class="text-red-500">*</span></label>
                        <input type="text" name="kode_perusahaan" id="kode_perusahaan" value="<?= e((string) ($form["kode_perusahaan"] ?? "")) ?>" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    </div>
                    <div class="">
                        <label for="kategori" class="mb-2 block text-sm font-semibold text-slate-700">Kategori Mitra <span class="text-red-500">*</span></label>
                        <select name="kategori" id="kategori" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                            <option value="">Pilih kategori</option>
                            <option value="BUMN" <?= $form["kategori"] === "BUMN" ? "selected" : "" ?>>BUMN</option>
                            <option value="BUMD" <?= $form["kategori"] === "BUMD" ? "selected" : "" ?>>BUMD</option>
                            <option value="Perusahaan Swasta" <?= $form["kategori"] === "Perusahaan Swasta" ? "selected" : "" ?>>Perusahaan Swasta</option>
                            <option value="Instansi Pemerintah" <?= $form["kategori"] === "Instansi Pemerintah" ? "selected" : "" ?>>Instansi Pemerintah</option>
                            <option value="Institusi Pendidikan" <?= $form["kategori"] === "Institusi Pendidikan" ? "selected" : "" ?>>Institusi Pendidikan</option>
                            <option value="UMKM" <?= $form["kategori"] === "UMKM" ? "selected" : "" ?>>UMKM</option>
                            <option value="Organisasi" <?= $form["kategori"] === "Organisasi" ? "selected" : "" ?>>Organisasi</option>
                            <option value="Lainnya" <?= $form["kategori"] === "Lainnya" ? "selected" : "" ?>>Lainnya</option>
                        </select>
                    </div>
                    <div class="">
                        <label for="bidang_usaha" class="mb-2 block text-sm font-semibold text-slate-700">Bidang Usaha</label>
                        <input type="text" name="bidang_usaha" id="bidang_usaha" value="<?= e((string) ($form["bidang_usaha"] ?? "")) ?>" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    </div>
                    <div class="">
                        <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                        <input type="email" name="email" id="email" value="<?= e((string) ($form["email"] ?? "")) ?>" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    </div>
                    <div class="">
                        <label for="no_telepon" class="mb-2 block text-sm font-semibold text-slate-700">Nomor Telepon</label>
                        <input type="tel" name="no_telepon" id="no_telepon" value="<?= e((string) ($form["no_telepon"] ?? "")) ?>" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    </div>
                    <div class="">
                        <label for="website" class="mb-2 block text-sm font-semibold text-slate-700">Website</label>
                        <input type="url" name="website" id="website" value="<?= e((string) ($form["website"] ?? "")) ?>" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    </div>
                    <div class="">
                        <label for="provinsi" class="mb-2 block text-sm font-semibold text-slate-700">Provinsi</label>
                        <input type="text" name="provinsi" id="provinsi" value="<?= e((string) ($form["provinsi"] ?? "")) ?>" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    </div>
                    <div class="">
                        <label for="kota" class="mb-2 block text-sm font-semibold text-slate-700">Kota / Kabupaten</label>
                        <input type="text" name="kota" id="kota" value="<?= e((string) ($form["kota"] ?? "")) ?>" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    </div>
                    <div class="md:col-span-2">
                        <label for="deskripsi" class="mb-2 block text-sm font-semibold text-slate-700">Deskripsi Perusahaan</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10"><?= e((string) ($form["deskripsi"] ?? "")) ?></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label for="alamat" class="mb-2 block text-sm font-semibold text-slate-700">Alamat Lengkap</label>
                        <textarea name="alamat" id="alamat" rows="3" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10"><?= e((string) ($form["alamat"] ?? "")) ?></textarea>
                    </div>
                </div>
                <div class="mt-7 flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                    <a href="<?= e(url('/dashboard/koordinator-magang/mitra')) ?>" class="inline-flex min-h-[44px] items-center justify-center rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50">Batal</a>
                    <button type="submit" class="inline-flex min-h-[44px] items-center justify-center rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-700">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>