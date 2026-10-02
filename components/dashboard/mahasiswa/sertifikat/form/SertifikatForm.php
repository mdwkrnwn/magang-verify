<?php

$isEdit =
    $mode === 'edit';

$action =
    $isEdit
    ? url('/dashboard/mahasiswa/sertifikat/edit/' . $slug)
    : url('/dashboard/mahasiswa/sertifikat/tambah');

$nama =
    $old['nama'] ?? '';

$penerbit =
    $old['penerbit'] ?? '';

$tanggalTerbit =
    $old['tanggal_terbit'] ?? '';

$nomorSertifikat =
    $old['nomor_sertifikat'] ?? '';

$deskripsi =
    $old['deskripsi'] ?? '';

$tautan =
    $old['tautan'] ?? '';

$gambar =
    $old['gambar'] ?? '';

?>

<form
    action="<?= e($action) ?>"
    method="POST"
    enctype="multipart/form-data"
    class="space-y-6">
    <input
        type="hidden"
        name="_csrf_token"
        value="<?= e(csrfToken()) ?>">

    <section class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm sm:p-6">

        <div class="mb-6">

            <h2 class="text-lg font-bold text-gray-900">
                Informasi Sertifikat
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Lengkapi informasi utama sertifikat.
            </p>

        </div>


        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            <!-- Nama -->

            <div class="md:col-span-2">

                <label
                    for="nama"
                    class="block mb-2 text-sm font-medium text-gray-700">
                    Nama Sertifikat
                </label>

                <input
                    type="text"
                    id="nama"
                    name="nama"
                    value="<?= e($nama) ?>"
                    placeholder="Contoh: Web Development Basic"
                    required
                    class="w-full px-3.5 py-2.5 text-sm text-gray-900 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-gray-900 focus:ring-1 focus:ring-gray-900">

                <?php if (!empty($errors['nama'])): ?>

                    <p class="mt-1.5 text-sm text-red-600">
                        <?= e($errors['nama']) ?>
                    </p>

                <?php endif; ?>

            </div>


            <!-- Penerbit -->

            <div>

                <label
                    for="penerbit"
                    class="block mb-2 text-sm font-medium text-gray-700">
                    Penerbit
                </label>

                <input
                    type="text"
                    id="penerbit"
                    name="penerbit"
                    value="<?= e($penerbit) ?>"
                    placeholder="Contoh: Dicoding Indonesia"
                    required
                    class="w-full px-3.5 py-2.5 text-sm text-gray-900 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-gray-900 focus:ring-1 focus:ring-gray-900">

                <?php if (!empty($errors['penerbit'])): ?>

                    <p class="mt-1.5 text-sm text-red-600">
                        <?= e($errors['penerbit']) ?>
                    </p>

                <?php endif; ?>

            </div>


            <!-- Tanggal -->

            <div>

                <label
                    for="tanggal_terbit"
                    class="block mb-2 text-sm font-medium text-gray-700">
                    Tanggal Terbit
                </label>

                <input
                    type="date"
                    id="tanggal_terbit"
                    name="tanggal_terbit"
                    value="<?= e($tanggalTerbit) ?>"
                    required
                    class="w-full px-3.5 py-2.5 text-sm text-gray-900 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-gray-900 focus:ring-1 focus:ring-gray-900">

                <?php if (!empty($errors['tanggal_terbit'])): ?>

                    <p class="mt-1.5 text-sm text-red-600">
                        <?= e($errors['tanggal_terbit']) ?>
                    </p>

                <?php endif; ?>

            </div>


            <!-- Nomor -->

            <div class="md:col-span-2">

                <label
                    for="nomor_sertifikat"
                    class="block mb-2 text-sm font-medium text-gray-700">
                    Nomor Sertifikat
                </label>

                <input
                    type="text"
                    id="nomor_sertifikat"
                    name="nomor_sertifikat"
                    value="<?= e($nomorSertifikat) ?>"
                    placeholder="Contoh: CERT-WEB-2025-001"
                    required
                    class="w-full px-3.5 py-2.5 text-sm text-gray-900 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-gray-900 focus:ring-1 focus:ring-gray-900">

                <?php if (!empty($errors['nomor_sertifikat'])): ?>

                    <p class="mt-1.5 text-sm text-red-600">
                        <?= e($errors['nomor_sertifikat']) ?>
                    </p>

                <?php endif; ?>

            </div>


            <!-- Deskripsi -->

            <div class="md:col-span-2">

                <label
                    for="deskripsi"
                    class="block mb-2 text-sm font-medium text-gray-700">
                    Deskripsi
                </label>

                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    rows="5"
                    placeholder="Tuliskan deskripsi singkat mengenai sertifikat."
                    class="w-full px-3.5 py-2.5 text-sm text-gray-900 bg-white border border-gray-300 rounded-lg outline-none transition resize-y focus:border-gray-900 focus:ring-1 focus:ring-gray-900"><?= e($deskripsi) ?></textarea>

            </div>


            <!-- Tautan -->

            <div class="md:col-span-2">

                <label
                    for="tautan"
                    class="block mb-2 text-sm font-medium text-gray-700">
                    Tautan Sertifikat
                </label>

                <input
                    type="url"
                    id="tautan"
                    name="tautan"
                    value="<?= e($tautan) ?>"
                    placeholder="https://example.com/sertifikat/..."
                    class="w-full px-3.5 py-2.5 text-sm text-gray-900 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-gray-900 focus:ring-1 focus:ring-gray-900">

                <p class="mt-1.5 text-xs text-gray-500">
                    Opsional. Digunakan untuk membuka sertifikat secara online.
                </p>

                <?php if (!empty($errors['tautan'])): ?>

                    <p class="mt-1.5 text-sm text-red-600">
                        <?= e($errors['tautan']) ?>
                    </p>

                <?php endif; ?>

            </div>


            <!-- Gambar -->

            <div class="md:col-span-2">

                <label
                    for="gambar"
                    class="block mb-2 text-sm font-medium text-gray-700">
                    Foto Sertifikat
                </label>

                <?php if ($isEdit && $gambar !== ''): ?>

                    <?php
                    $projectRoot = dirname(__DIR__, 5);
                    $relativeFilePath = 'uploads/sertifikat/' . basename($gambar);
                    $filePath = $projectRoot . '/' . $relativeFilePath;
                    $fileUrl = url('/' . $relativeFilePath);
                    $fileExtension = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
                    ?>

                    <?php if (is_file($filePath)): ?>

                        <div class="p-3 mb-4 border border-gray-200 rounded-xl bg-slate-50">

                            <p class="mb-3 text-xs font-medium text-gray-500">
                                File sertifikat saat ini
                            </p>

                            <?php if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'webp'], true)): ?>

                                <img
                                    src="<?= e($fileUrl) ?>"
                                    alt="Foto sertifikat <?= e($nama) ?>"
                                    class="object-contain w-full max-h-[280px] rounded-lg">

                            <?php elseif ($fileExtension === 'pdf'): ?>

                                <div class="flex items-center gap-3 p-4 bg-white border border-gray-200 rounded-lg">
                                    <span class="text-sm font-medium text-gray-700">Dokumen PDF</span>
                                    <a
                                        href="<?= e($fileUrl) ?>"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="text-sm font-medium text-blue-600 hover:text-blue-700">
                                        Buka file
                                    </a>
                                </div>

                            <?php endif; ?>

                        </div>

                    <?php endif; ?>

                <?php endif; ?>


                <input
                    type="file"
                    id="gambar"
                    name="gambar"
                    accept=".jpg,.jpeg,.png,.webp,.pdf,image/jpeg,image/png,image/webp,application/pdf"
                    <?= $isEdit ? '' : 'required' ?>
                    class="block w-full text-sm text-gray-600 border border-gray-300 rounded-lg cursor-pointer bg-white file:mr-4 file:px-4 file:py-2.5 file:text-sm file:font-medium file:text-gray-700 file:bg-gray-100 file:border-0 hover:file:bg-gray-200">

                <p class="mt-1.5 text-xs text-gray-500">
                    JPG, PNG, WEBP, atau PDF. Maksimal 5 MB.
                    <?= $isEdit ? 'Kosongkan jika tetap menggunakan foto saat ini.' : '' ?>
                </p>

                <?php if (!empty($errors['gambar'])): ?>

                    <p class="mt-1.5 text-sm text-red-600">
                        <?= e($errors['gambar']) ?>
                    </p>

                <?php endif; ?>

            </div>

        </div>

    </section>


    <!-- Status -->

    <section class="p-5 border border-amber-200 rounded-xl bg-amber-50 sm:p-6">

        <div class="flex gap-3">

            <svg
                class="w-5 h-5 mt-0.5 text-amber-600 shrink-0"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
                viewBox="0 0 24 24"
                aria-hidden="true">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 9v3.75m0 3h.008M10.29 3.86 2.82 17.25A1.5 1.5 0 0 0 4.12 19.5h15.76a1.5 1.5 0 0 0 1.3-2.25L13.71 3.86a1.5 1.5 0 0 0-2.6 0Z" />
            </svg>

            <div>

                <p class="text-sm font-semibold text-amber-800">
                    Status Verifikasi
                </p>

                <p class="mt-1 text-sm leading-6 text-amber-700">
                    Sertifikat baru akan berstatus <strong>Belum Terverifikasi</strong>.
                    Setelah sertifikat diverifikasi, data tidak dapat lagi diedit atau dihapus.
                </p>
                    
            </div>

        </div>

    </section>


    <!-- Action -->

    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

        <a
            href="<?= $isEdit
                        ? url('/dashboard/mahasiswa/sertifikat/detail/' . $slug)
                        : url('/dashboard/mahasiswa/sertifikat') ?>"
            class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-gray-700 transition bg-white border border-gray-200 rounded-lg hover:bg-gray-50">
            Batal
        </a>

        <button
            type="submit"
            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-medium text-white transition bg-gray-900 rounded-lg hover:bg-gray-800">

            <svg
                class="w-4 h-4"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
                viewBox="0 0 24 24"
                aria-hidden="true">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M5 12h14M12 5v14" />
            </svg>

            <?= $isEdit
                ? 'Simpan Perubahan'
                : 'Tambah Sertifikat' ?>

        </button>

    </div>

</form>