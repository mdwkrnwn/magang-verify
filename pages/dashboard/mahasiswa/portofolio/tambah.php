<?php

$base = url('/');
$active = 'portofolio';

$isEdit = $isEdit ?? false;
$portofolioEdit = $portofolioEdit ?? null;

$formAction = $isEdit
    ? url(
        '/dashboard/mahasiswa/portofolio/edit/'
            . ($portofolioEdit['slug'] ?? '')
    )
    : url('/dashboard/mahasiswa/portofolio/tambah');

$pageTitle = $isEdit
    ? 'Edit Portofolio'
    : 'Tambah Portofolio';

$pageDescription = $isEdit
    ? 'Perbarui informasi portofolio yang sudah kamu tambahkan.'
    : 'Tambahkan proyek atau hasil karya yang pernah kamu kerjakan.';

$submitLabel = $isEdit
    ? 'Simpan Perubahan'
    : 'Simpan Portofolio';

$judul = $_POST['judul']
    ?? ($portofolioEdit['judul'] ?? '');

$deskripsi = $_POST['deskripsi']
    ?? ($portofolioEdit['deskripsi'] ?? '');

$teknologi = $_POST['teknologi']
    ?? implode(
        ', ',
        $portofolioEdit['teknologi'] ?? []
    );

$peran = $_POST['peran']
    ?? ($portofolioEdit['peran'] ?? '');

$tahun = $_POST['tahun']
    ?? ($portofolioEdit['tahun'] ?? date('Y'));

$github = $_POST['github']
    ?? ($portofolioEdit['tautan']['github'] ?? '');

$demo = $_POST['demo']
    ?? ($portofolioEdit['tautan']['demo'] ?? '');

?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . "/../../../../components/dashboard/mahasiswa/Head.php"; ?>

<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">

    <?php include __DIR__ . "/../../../../components/dashboard/mahasiswa/Sidebar.php"; ?>

    <?php include __DIR__ . '/../../../../components/dashboard/shared/Header.php'; ?>

    <main class="min-h-screen pt-20 ml-0 lg:ml-64">

        <div class="p-4 mx-auto sm:p-6 lg:p-8 max-w-8xl">

            <div class="mb-6">

                <a
                    href="<?= e(url('/dashboard/mahasiswa/portofolio')) ?>"
                    class="inline-flex items-center gap-2 text-sm text-gray-500 transition hover:text-blue-600">
                    <span>&lt;</span>
                    Kembali ke Portofolio
                </a>

                <h1 class="mt-4 text-2xl font-bold text-gray-900">
                    <?= e($pageTitle) ?>
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    <?= e($pageDescription) ?>
                </p>

            </div>


            <form
                method="POST"
                enctype="multipart/form-data"
                action="<?= e($formAction) ?>"
                class="p-4 bg-white border border-slate-200 rounded-xl sm:p-6">
                <input
                    type="hidden"
                    name="_csrf_token"
                    value="<?= e(csrfToken()) ?>">

                <div class="grid grid-cols-1 gap-5">

                    <div>

                        <label
                            for="judul"
                            class="block mb-2 text-sm font-medium text-gray-700">
                            Judul Portofolio
                        </label>

                        <input
                            type="text"
                            id="judul"
                            name="judul"
                            value="<?= e($judul) ?>"
                            placeholder="Contoh: Website E-Commerce Sederhana"
                            class="w-full px-3 py-2.5 border border-slate-200 rounded-lg
                                   text-sm outline-none
                                   focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                        <?php if (!empty($errors['judul'])): ?>

                            <p class="mt-1 text-xs text-red-500">
                                <?= e($errors['judul']) ?>
                            </p>

                        <?php endif; ?>

                    </div>


                    <div>

                        <label
                            for="deskripsi"
                            class="block mb-2 text-sm font-medium text-gray-700">
                            Deskripsi
                        </label>

                        <textarea
                            id="deskripsi"
                            name="deskripsi"
                            rows="5"
                            placeholder="Jelaskan secara singkat tentang portofolio ini."
                            class="w-full px-3 py-2.5 border border-slate-200 rounded-lg
                                   text-sm outline-none resize-y
                                   focus:border-blue-500 focus:ring-2 focus:ring-blue-100"><?= e($deskripsi) ?></textarea>

                        <?php if (!empty($errors['deskripsi'])): ?>

                            <p class="mt-1 text-xs text-red-500">
                                <?= e($errors['deskripsi']) ?>
                            </p>

                        <?php endif; ?>

                    </div>


                    <div>

                        <label
                            for="teknologi"
                            class="block mb-2 text-sm font-medium text-gray-700">
                            Teknologi
                        </label>

                        <input
                            type="text"
                            id="teknologi"
                            name="teknologi"
                            value="<?= e($teknologi) ?>"
                            placeholder="React, Node.js, MySQL"
                            class="w-full px-3 py-2.5 border border-slate-200 rounded-lg
                                   text-sm outline-none
                                   focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                        <p class="mt-1 text-xs text-gray-400">
                            Pisahkan setiap teknologi dengan koma.
                        </p>

                        <?php if (!empty($errors['teknologi'])): ?>

                            <p class="mt-1 text-xs text-red-500">
                                <?= e($errors['teknologi']) ?>
                            </p>

                        <?php endif; ?>

                    </div>


                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                        <div>

                            <label
                                for="peran"
                                class="block mb-2 text-sm font-medium text-gray-700">
                                Peran
                            </label>

                            <input
                                type="text"
                                id="peran"
                                name="peran"
                                value="<?= e($peran) ?>"
                                placeholder="Frontend Developer"
                                class="w-full px-3 py-2.5 border border-slate-200 rounded-lg
                                       text-sm outline-none
                                       focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                            <?php if (!empty($errors['peran'])): ?>

                                <p class="mt-1 text-xs text-red-500">
                                    <?= e($errors['peran']) ?>
                                </p>

                            <?php endif; ?>

                        </div>


                        <div>

                            <label
                                for="tahun"
                                class="block mb-2 text-sm font-medium text-gray-700">
                                Tahun
                            </label>

                            <input
                                type="number"
                                id="tahun"
                                name="tahun"
                                min="2000"
                                max="2100"
                                value="<?= e($tahun) ?>"
                                class="w-full px-3 py-2.5 border border-slate-200 rounded-lg
                                       text-sm outline-none
                                       focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                            <?php if (!empty($errors['tahun'])): ?>

                                <p class="mt-1 text-xs text-red-500">
                                    <?= e($errors['tahun']) ?>
                                </p>

                            <?php endif; ?>

                        </div>

                    </div>



                    <div>
                        <label
                            for="gambar"
                            class="block mb-2 text-sm font-medium text-gray-700">
                            Gambar Portofolio
                        </label>

                        <?php if (!empty($portofolioEdit['gambar'])): ?>
                            <div class="mb-3">
                                <p class="mb-2 text-xs text-gray-500">Gambar saat ini:</p>
                                <img
                                    src="<?= e(url('/' . ltrim($portofolioEdit['gambar'], '/'))) ?>"
                                    alt="Gambar portofolio saat ini"
                                    class="object-cover w-full max-w-xs border rounded-lg border-slate-200">
                            </div>
                        <?php endif; ?>

                        <input
                            type="file"
                            id="gambar"
                            name="gambar"
                            accept="image/jpeg,image/png,image/webp"
                            class="w-full px-3 py-2.5 border border-slate-200 rounded-lg
                                   text-sm outline-none
                                   file:mr-3 file:rounded-md file:border-0
                                   file:bg-blue-50 file:px-3 file:py-1.5
                                   file:text-sm file:font-medium file:text-blue-700
                                   hover:file:bg-blue-100
                                   focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                        <p class="mt-1 text-xs text-gray-400">
                            Format JPG, PNG, atau WEBP. Maksimal 2 MB.
                            Kosongkan jika tidak ingin mengganti gambar.
                        </p>

                        <?php if (!empty($errors['gambar'])): ?>
                            <p class="mt-1 text-xs text-red-500">
                                <?= e($errors['gambar']) ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div>

                        <label
                            for="github"
                            class="block mb-2 text-sm font-medium text-gray-700">
                            GitHub
                        </label>

                        <input
                            type="url"
                            id="github"
                            name="github"
                            value="<?= e($github) ?>"
                            placeholder="https://github.com/username/project"
                            class="w-full px-3 py-2.5 border border-slate-200 rounded-lg
                                   text-sm outline-none
                                   focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                    </div>


                    <div>

                        <label
                            for="demo"
                            class="block mb-2 text-sm font-medium text-gray-700">
                            Link Demo
                        </label>

                        <input
                            type="url"
                            id="demo"
                            name="demo"
                            value="<?= e($demo) ?>"
                            placeholder="https://example.com"
                            class="w-full px-3 py-2.5 border border-slate-200 rounded-lg
                                   text-sm outline-none
                                   focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                    </div>


                    <div class="flex flex-col-reverse gap-3 pt-4 border-t border-slate-100 sm:flex-row sm:justify-end">

                        <a
                            href="<?= e(url('/dashboard/mahasiswa/portofolio')) ?>"
                            class="inline-flex items-center justify-center
                                   px-4 py-2.5
                                   text-sm font-medium
                                   text-gray-600
                                   border border-slate-200
                                   rounded-lg
                                   hover:bg-slate-50
                                   transition">
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center
                                   px-4 py-2.5
                                   text-sm font-medium
                                   text-white
                                   bg-blue-600
                                   rounded-lg
                                   hover:bg-blue-700
                                   transition">
                            <?= e($submitLabel) ?>
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </main>

</body>

</html>