
<div id="modal-bio" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-gray-900/50" role="dialog" aria-modal="true" aria-labelledby="title-bio">
    <div class="w-full max-w-lg max-h-[90vh] overflow-y-auto bg-white shadow-xl rounded-2xl">
        <div class="flex items-start justify-between p-5 border-b border-gray-100 sm:p-6">
            <div>
                <h2 id="title-bio" class="text-lg font-bold text-gray-900">Edit Bio dan Keahlian</h2>
                <p class="mt-1 text-sm text-gray-500">Ceritakan minat dan kemampuan Anda.</p>
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
            <input type="hidden" name="action" value="bio">

            <div>
                <label for="profil-bio" class="block mb-1.5 text-sm font-medium text-gray-700">Bio</label>
                <textarea
                    id="profil-bio"
                    name="bio"
                    rows="5"
                    maxlength="5000"
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Ceritakan minat, pengalaman, dan tujuan profesional Anda."
                ><?= e($profil['bio'] ?? '') ?></textarea>
                <p class="mt-1 text-xs text-gray-500">Maksimal 5.000 karakter.</p>
            </div>

            <div>
                <label for="profil-keahlian" class="block mb-1.5 text-sm font-medium text-gray-700">Keahlian</label>
                <input
                    id="profil-keahlian"
                    name="keahlian_input"
                    type="text"
                    value="<?= e(implode(', ', $profil['keahlian'] ?? [])) ?>"
                    maxlength="500"
                    placeholder="PHP, JavaScript, PostgreSQL"
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                <p class="mt-1 text-xs text-gray-500">Pisahkan setiap keahlian dengan koma, maksimal 30 keahlian.</p>
            </div>

            <p data-form-message class="hidden text-sm text-amber-700"></p>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" data-close-modal class="px-4 py-2.5 text-sm font-medium text-gray-700 border border-gray-300 rounded-xl hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>