<?php $editing = ($mode ?? 'create') === 'edit'; ?>
<main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">
    <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8">
        <div class="mb-7">
            <h1 class="text-2xl font-bold text-gray-900"><?= $editing ? 'Edit Pengalaman' : 'Tambah Pengalaman' ?></h1>
            <p class="mt-2 text-sm text-gray-500">Isi pengalaman non-magang. Data magang selesai dikelola otomatis oleh sistem.</p>
        </div>
        <form method="POST" action="<?= e(url($editing ? '/dashboard/mahasiswa/pengalaman/edit/' . ($item['id'] ?? 0) : '/dashboard/mahasiswa/pengalaman/tambah')) ?>" class="space-y-5 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-7"><input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
            <div class="grid gap-5 sm:grid-cols-2">
                <div><label class="mb-1.5 block text-sm font-medium">Jenis</label><select name="jenis" class="w-full rounded-xl border border-gray-300 px-3 py-2.5">
                        <option value="pekerjaan">Pekerjaan</option>
                        <option value="organisasi">Organisasi</option>
                        <option value="freelance">Freelance</option>
                        <option value="proyek">Proyek</option>
                        <option value="lainnya">Lainnya</option>
                    </select></div>
                <div><label class="mb-1.5 block text-sm font-medium">Posisi / Peran</label><input required name="posisi" maxlength="200" value="<?= e($item['posisi'] ?? '') ?>" class="w-full rounded-xl border border-gray-300 px-3 py-2.5"></div>
            </div>
            <div><label class="mb-1.5 block text-sm font-medium">Instansi / Perusahaan</label><input required name="instansi" maxlength="200" value="<?= e($item['instansi'] ?? '') ?>" class="w-full rounded-xl border border-gray-300 px-3 py-2.5"></div>
            <div><label class="mb-1.5 block text-sm font-medium">Lokasi</label><input name="lokasi" maxlength="200" value="<?= e($item['lokasi'] ?? '') ?>" class="w-full rounded-xl border border-gray-300 px-3 py-2.5"></div>
            <div class="grid gap-5 sm:grid-cols-2">
                <div><label class="mb-1.5 block text-sm font-medium">Tanggal Mulai</label><input type="date" name="tanggal_mulai" value="<?= e($item['tanggal_mulai'] ?? '') ?>" class="w-full rounded-xl border border-gray-300 px-3 py-2.5"></div>
                <div><label class="mb-1.5 block text-sm font-medium">Tanggal Selesai</label><input type="date" name="tanggal_selesai" value="<?= e($item['tanggal_selesai'] ?? '') ?>" class="w-full rounded-xl border border-gray-300 px-3 py-2.5"></div>
            </div>
            <div><label class="mb-1.5 block text-sm font-medium">Deskripsi</label><textarea name="deskripsi" rows="5" class="w-full rounded-xl border border-gray-300 px-3 py-2.5"><?= e($item['deskripsi'] ?? '') ?></textarea></div>
            <div class="flex justify-end gap-3"><a href="<?= e(url('/dashboard/mahasiswa/pengalaman')) ?>" class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-700">Batal</a><button class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Simpan</button></div>
        </form>
    </div>
</main>