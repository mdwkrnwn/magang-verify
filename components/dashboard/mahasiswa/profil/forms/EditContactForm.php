<div id="modal-contact" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-gray-900/50" role="dialog" aria-modal="true" aria-labelledby="title-contact">
    <div class="w-full max-w-lg max-h-[90vh] overflow-y-auto bg-white shadow-xl rounded-2xl">
        <div class="flex items-start justify-between p-5 border-b border-gray-100 sm:p-6">
            <div>
                <h2 id="title-contact" class="text-lg font-bold text-gray-900">Edit Kontak</h2>
                <p class="mt-1 text-sm text-gray-500">Perbarui informasi kontak Anda.</p>
            </div>
            <button type="button" data-close-modal class="p-2 text-gray-400 rounded-lg hover:bg-gray-100" aria-label="Tutup">✕</button>
        </div>

        <form
            method="POST"
            action="<?= e(url('/dashboard/mahasiswa/profil')) ?>"
            data-profile-form
            class="p-5 space-y-4 sm:p-6">
            <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
            <input type="hidden" name="action" value="contact">


            <div>
                <label for="profil-email" class="block mb-1.5 text-sm font-medium text-gray-700">
                    Email
                </label>
                <input
                    id="profil-email"
                    name="email"
                    type="email"
                    value="<?= e($profil['email'] ?? '') ?>"
                    maxlength="254"
                    autocomplete="email"
                    placeholder="nama@kampus.ac.id"
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="profil-hp" class="block mb-1.5 text-sm font-medium text-gray-700">Nomor HP</label>
                <input
                    id="profil-hp"
                    name="no_hp"
                    type="tel"
                    value="<?= e($profil['no_hp'] ?? '') ?>"
                    autocomplete="tel"
                    maxlength="20"
                    placeholder="Contoh: 081234567890"
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="profil-instagram" class="block mb-1.5 text-sm font-medium text-gray-700">Instagram</label>
                <input
                    id="profil-instagram"
                    name="instagram"
                    type="text"
                    value="<?= e($profil['instagram'] ?? '') ?>"
                    maxlength="100"
                    placeholder="@username"
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <p data-form-message class="hidden text-sm text-amber-700"></p>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" data-close-modal class="px-4 py-2.5 text-sm font-medium text-gray-700 border border-gray-300 rounded-xl hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>