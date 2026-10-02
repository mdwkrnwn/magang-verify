
<div id="modal-photo" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-gray-900/50" role="dialog" aria-modal="true" aria-labelledby="title-photo">
    <div class="w-full max-w-md bg-white shadow-xl rounded-2xl">
        <div class="flex items-start justify-between p-5 border-b border-gray-100 sm:p-6">
            <div>
                <h2 id="title-photo" class="text-lg font-bold text-gray-900">Ganti Foto Profil</h2>
                <p class="mt-1 text-sm text-gray-500">Pilih foto yang akan digunakan pada profil.</p>
            </div>
            <button type="button" data-close-modal class="p-2 text-gray-400 rounded-lg hover:bg-gray-100" aria-label="Tutup">✕</button>
        </div>

        <form
            method="POST"
            action="<?= e(url('/dashboard/mahasiswa/profil')) ?>"
            enctype="multipart/form-data"
            data-profile-form
            class="p-5 space-y-4 sm:p-6"
        >
            <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
            <input type="hidden" name="action" value="photo">

            <div>
                <label for="profil-foto" class="block mb-1.5 text-sm font-medium text-gray-700">File foto</label>
                <input
                    id="profil-foto"
                    name="foto_profil"
                    type="file"
                    accept="image/jpeg,image/png,image/webp"
                    required
                    class="block w-full text-sm text-gray-600 file:mr-4 file:px-4 file:py-2 file:border-0 file:rounded-lg file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                >
                <p class="mt-2 text-xs text-gray-500">Format JPG, PNG, atau WEBP. Ukuran maksimal 2 MB.</p>
            </div>

            <p data-form-message class="hidden text-sm text-amber-700"></p>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" data-close-modal class="px-4 py-2.5 text-sm font-medium text-gray-700 border border-gray-300 rounded-xl hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700">Gunakan Foto</button>
            </div>
        </form>
    </div>
</div>