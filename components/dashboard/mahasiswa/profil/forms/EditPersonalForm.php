
<div id="modal-personal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-gray-900/50" role="dialog" aria-modal="true" aria-labelledby="title-personal">
    <div class="w-full max-w-xl max-h-[90vh] overflow-y-auto bg-white shadow-xl rounded-2xl">
        <div class="flex items-start justify-between p-5 border-b border-gray-100 sm:p-6">
            <div>
                <h2 id="title-personal" class="text-lg font-bold text-gray-900">Edit Data Diri</h2>
                <p class="mt-1 text-sm text-gray-500">Perbarui informasi pribadi Anda.</p>
            </div>
            <button type="button" data-close-modal class="p-2 text-gray-400 rounded-lg hover:bg-gray-100" aria-label="Tutup">✕</button>
        </div>

        <form
            method="POST"
            action="<?= e(url('/dashboard/mahasiswa/profil')) ?>"
            data-profile-form
            class="p-5 space-y-4 sm:p-6"
        >
            <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
            <input type="hidden" name="action" value="personal">

            <div>
                <label for="profil-nama" class="block mb-1.5 text-sm font-medium text-gray-700">Nama lengkap</label>
                <input
                    id="profil-nama"
                    name="nama_lengkap"
                    type="text"
                    value="<?= e($profil['nama_lengkap'] ?? '') ?>"
                    autocomplete="name"
                    required
                    maxlength="150"
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <div>
                <label for="profil-nim" class="block mb-1.5 text-sm font-medium text-gray-700">NIM</label>
                <input
                    id="profil-nim"
                    type="text"
                    value="<?= e($profil['nim'] ?? '') ?>"
                    readonly
                    class="w-full px-3 py-2.5 text-gray-500 bg-gray-100 border border-gray-200 rounded-xl"
                >
                <p class="mt-1 text-xs text-gray-500">NIM tidak dapat diubah melalui form profil.</p>
            </div>

            <div>
                <label for="profil-gender" class="block mb-1.5 text-sm font-medium text-gray-700">Jenis kelamin</label>
                <select
                    id="profil-gender"
                    name="jenis_kelamin"
                    class="w-full px-3 py-2.5 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="" <?= empty($profil['jenis_kelamin']) ? 'selected' : '' ?>>Pilih jenis kelamin</option>
                    <option value="Laki-laki" <?= ($profil['jenis_kelamin'] ?? '') === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                    <option value="Perempuan" <?= ($profil['jenis_kelamin'] ?? '') === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                </select>
            </div>

            <div>
                <label for="profil-lahir" class="block mb-1.5 text-sm font-medium text-gray-700">Tanggal lahir</label>
                <input
                    id="profil-lahir"
                    name="tanggal_lahir"
                    type="date"
                    value="<?= e($profil['tanggal_lahir'] ?? '') ?>"
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <div>
                <label for="profil-alamat" class="block mb-1.5 text-sm font-medium text-gray-700">Alamat</label>
                <textarea
                    id="profil-alamat"
                    name="alamat"
                    rows="3"
                    maxlength="1000"
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                ><?= e($profil['alamat'] ?? '') ?></textarea>
            </div>

            <p data-form-message class="hidden text-sm text-amber-700"></p>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" data-close-modal class="px-4 py-2.5 text-sm font-medium text-gray-700 border border-gray-300 rounded-xl hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>