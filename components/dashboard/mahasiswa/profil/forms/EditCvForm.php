<div id="modal-cv" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900/50 p-4" role="dialog" aria-modal="true" aria-labelledby="title-cv">
    <div class="w-full max-w-lg max-h-[90vh] overflow-y-auto rounded-2xl bg-white shadow-xl">
        <div class="flex items-start justify-between border-b border-gray-100 p-5 sm:p-6">
            <div>
                <h2 id="title-cv" class="text-lg font-bold text-gray-900">Upload CV</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Upload CV terbaru Anda dalam format PDF.
                </p>
            </div>

            <button
                type="button"
                data-close-modal
                class="rounded-lg p-2 text-gray-400 hover:bg-gray-100"
                aria-label="Tutup"
            >
                ✕
            </button>
        </div>

        <form
            method="POST"
            action="<?= e(url('/dashboard/mahasiswa/profil')) ?>"
            enctype="multipart/form-data"
            data-profile-form
            class="space-y-5 p-5 sm:p-6"
        >
            <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
            <input type="hidden" name="action" value="cv">

            <div>
                <label for="profil-cv" class="mb-1.5 block text-sm font-medium text-gray-700">
                    File CV
                </label>

                <input
                    id="profil-cv"
                    name="cv"
                    type="file"
                    accept="application/pdf,.pdf"
                    required
                    class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-blue-700 hover:file:bg-blue-100"
                >

                <p class="mt-2 text-xs leading-5 text-gray-500">
                    Format PDF, ukuran maksimal 5 MB. Gunakan CV terbaru dan pastikan informasi di dalamnya dapat dibaca dengan jelas.
                </p>
            </div>

            <div class="rounded-xl bg-blue-50 p-4 text-xs leading-5 text-blue-700">
                CV akan digunakan sebagai bagian dari profil publik mahasiswa dan dapat dilihat oleh pengunjung/mitra melalui halaman profil Anda.
            </div>

            <p data-form-message class="hidden text-sm text-amber-700"></p>

            <div class="flex justify-end gap-3 pt-1">
                <button
                    type="button"
                    data-close-modal
                    class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700"
                >
                    Simpan CV
                </button>
            </div>
        </form>
    </div>
</div>
