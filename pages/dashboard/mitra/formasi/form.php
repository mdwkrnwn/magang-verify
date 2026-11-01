
<?php
$base = url('/');
$active = 'formasi';
$isEdit = $mode === 'edit';
?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . '/../../../../components/dashboard/mitra/Head.php'; ?>

<body class="overflow-x-hidden bg-slate-50 font-[Poppins] text-gray-900">

    <?php include __DIR__ . '/../../../../components/dashboard/mitra/Sidebar.php'; ?>
    <?php include __DIR__ . '/../../../../components/dashboard/mitra/Header.php'; ?>

    <main class="min-h-screen bg-slate-50 pt-20 lg:ml-64">
        <div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

            <!-- Back Navigation -->
            <a
                href="<?= e(url('/dashboard/mitra/formasi')) ?>"
                class="mb-5 inline-flex items-center gap-2 text-sm font-medium text-blue-600 transition hover:text-blue-700"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7 7-7-7 7-7"/>
                </svg>
                Kembali ke Formasi
            </a>

            <!-- Page Header -->
            <div class="mb-7">
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                    <?= $isEdit ? 'Edit Formasi Magang' : 'Tambah Formasi Magang' ?>
                </h1>

                <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500 sm:text-base">
                    <?= $isEdit
                        ? 'Perbarui informasi formasi magang yang telah dibuat.'
                        : 'Lengkapi informasi formasi magang untuk mulai menerima pengajuan mahasiswa.' ?>
                </p>
            </div>

            <!-- Error Notification -->
            <?php if ($error): ?>
                <div
                    class="mb-6 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm text-red-800"
                    role="alert"
                >
                    <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.3 3.8 1.9 18.2A2 2 0 0 0 3.6 21h16.8a2 2 0 0 0 1.7-2.8L13.7 3.8a2 2 0 0 0-3.4 0Z"/>
                    </svg>

                    <span><?= e($error) ?></span>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form
                method="POST"
                action="<?= e(url($isEdit
                    ? '/dashboard/mitra/formasi/edit/' . (int) $values['id']
                    : '/dashboard/mitra/formasi/tambah')) ?>"
                class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm"
            >
                <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">

                <!-- Section 1: Basic Information -->
                <div class="border-b border-slate-100 p-5 sm:p-7">

                    <div class="mb-6 flex items-start gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7h8m-8 4h8m-8 4h5M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2Z"/>
                            </svg>
                        </div>

                        <div>
                            <h2 class="font-semibold text-slate-900">
                                Informasi Formasi
                            </h2>
                            <p class="mt-1 text-sm text-slate-500">
                                Informasi dasar mengenai posisi magang yang ditawarkan.
                            </p>
                        </div>
                    </div>

                    <div class="space-y-5">

                        <!-- Title -->
                        <div>
                            <label for="judul" class="mb-2 block text-sm font-medium text-slate-700">
                                Judul Formasi <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="judul"
                                type="text"
                                name="judul"
                                maxlength="200"
                                required
                                value="<?= e((string) ($values['judul'] ?? '')) ?>"
                                placeholder="Contoh: Internship Web Developer"
                                class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-3 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            >
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="deskripsi" class="mb-2 block text-sm font-medium text-slate-700">
                                Deskripsi Pekerjaan <span class="text-red-500">*</span>
                            </label>

                            <textarea
                                id="deskripsi"
                                name="deskripsi"
                                rows="5"
                                required
                                placeholder="Jelaskan tugas, tanggung jawab, dan gambaran pekerjaan selama magang..."
                                class="block w-full resize-y rounded-lg border border-slate-300 bg-white px-3.5 py-3 text-sm leading-6 text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            ><?= e((string) ($values['deskripsi'] ?? '')) ?></textarea>
                        </div>

                        <!-- Field Grid -->
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            <div>
                                <label for="bidang" class="mb-2 block text-sm font-medium text-slate-700">
                                    Bidang
                                </label>

                                <input
                                    id="bidang"
                                    type="text"
                                    name="bidang"
                                    maxlength="150"
                                    value="<?= e((string) ($values['bidang'] ?? '')) ?>"
                                    placeholder="Contoh: Teknologi Informasi"
                                    class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-3 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                >
                            </div>

                            <div>
                                <label for="jumlah_kuota" class="mb-2 block text-sm font-medium text-slate-700">
                                    Jumlah Kuota <span class="text-red-500">*</span>
                                </label>

                                <input
                                    id="jumlah_kuota"
                                    type="number"
                                    name="jumlah_kuota"
                                    min="1"
                                    max="32767"
                                    required
                                    value="<?= e((string) ($values['jumlah_kuota'] ?? 1)) ?>"
                                    class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                >
                            </div>

                            <div>
                                <label for="lokasi_magang" class="mb-2 block text-sm font-medium text-slate-700">
                                    Lokasi Magang
                                </label>

                                <input
                                    id="lokasi_magang"
                                    type="text"
                                    name="lokasi_magang"
                                    maxlength="255"
                                    value="<?= e((string) ($values['lokasi_magang'] ?? '')) ?>"
                                    placeholder="Contoh: Surabaya, Jawa Timur"
                                    class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-3 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                >
                            </div>

                            <div>
                                <label for="sistem_kerja" class="mb-2 block text-sm font-medium text-slate-700">
                                    Sistem Kerja <span class="text-red-500">*</span>
                                </label>

                                <select
                                    id="sistem_kerja"
                                    name="sistem_kerja"
                                    required
                                    class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                >
                                    <?php foreach ([
                                        'onsite' => 'On-site',
                                        'hybrid' => 'Hybrid',
                                        'remote' => 'Remote'
                                    ] as $value => $label): ?>

                                        <option
                                            value="<?= e($value) ?>"
                                            <?= ($values['sistem_kerja'] ?? 'onsite') === $value ? 'selected' : '' ?>
                                        >
                                            <?= e($label) ?>
                                        </option>

                                    <?php endforeach; ?>
                                </select>
                            </div>

                        </div>

                    </div>
                </div>

                <!-- Section 2: Internship Period -->
                <div class="border-b border-slate-100 p-5 sm:p-7">

                    <div class="mb-6 flex items-start gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14H4V6a1 1 0 011-1Z"/>
                            </svg>
                        </div>

                        <div>
                            <h2 class="font-semibold text-slate-900">
                                Periode Magang
                            </h2>
                            <p class="mt-1 text-sm text-slate-500">
                                Tentukan jadwal pelaksanaan dan tahun akademik formasi.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                        <div>
                            <label for="tanggal_mulai" class="mb-2 block text-sm font-medium text-slate-700">
                                Tanggal Mulai <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="tanggal_mulai"
                                type="date"
                                name="tanggal_mulai"
                                required
                                value="<?= e((string) ($values['tanggal_mulai'] ?? '')) ?>"
                                class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            >
                        </div>

                        <div>
                            <label for="tanggal_selesai" class="mb-2 block text-sm font-medium text-slate-700">
                                Tanggal Selesai <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="tanggal_selesai"
                                type="date"
                                name="tanggal_selesai"
                                required
                                value="<?= e((string) ($values['tanggal_selesai'] ?? '')) ?>"
                                class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            >
                        </div>

                        <div class="sm:col-span-2">
                            <label for="tahun_akademik" class="mb-2 block text-sm font-medium text-slate-700">
                                Tahun Akademik <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="tahun_akademik"
                                type="text"
                                name="tahun_akademik"
                                maxlength="20"
                                required
                                placeholder="Contoh: 2026/2027"
                                value="<?= e((string) ($values['tahun_akademik'] ?? '')) ?>"
                                class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-3 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:max-w-md"
                            >
                        </div>

                    </div>
                </div>

                <!-- Section 3: Requirements -->
                <div class="p-5 sm:p-7">

                    <div class="mb-6 flex items-start gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6M7 3h8l4 4v14H7a2 2 0 01-2-2V5a2 2 0 012-2Zm8 0v5h4"/>
                            </svg>
                        </div>

                        <div>
                            <h2 class="font-semibold text-slate-900">
                                Persyaratan Pelamar
                            </h2>
                            <p class="mt-1 text-sm text-slate-500">
                                Tuliskan kualifikasi atau ketentuan yang harus dipenuhi calon mahasiswa magang.
                            </p>
                        </div>
                    </div>

                    <div>
                        <label for="persyaratan" class="mb-2 block text-sm font-medium text-slate-700">
                            Persyaratan
                        </label>

                        <textarea
                            id="persyaratan"
                            name="persyaratan"
                            rows="5"
                            placeholder="Contoh:&#10;1. Mahasiswa aktif minimal semester 5.&#10;2. Menguasai dasar HTML, CSS, dan JavaScript.&#10;3. Mampu bekerja dalam tim."
                            class="block w-full resize-y rounded-lg border border-slate-300 bg-white px-3.5 py-3 text-sm leading-6 text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        ><?= e((string) ($values['persyaratan'] ?? '')) ?></textarea>
                    </div>

                </div>

                <!-- Form Actions -->
                <div class="flex flex-col-reverse gap-3 border-t border-slate-100 bg-slate-50/70 px-5 py-5 sm:flex-row sm:justify-end sm:px-7">

                    <a
                        href="<?= e(url('/dashboard/mitra/formasi')) ?>"
                        class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-5 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-50"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>

                        <?= $isEdit ? 'Simpan Perubahan' : 'Simpan Formasi' ?>
                    </button>

                </div>

            </form>

            <?php include __DIR__ . '/../../../../components/dashboard/mitra/Footer.php'; ?>

        </div>
    </main>

</body>
</html>
