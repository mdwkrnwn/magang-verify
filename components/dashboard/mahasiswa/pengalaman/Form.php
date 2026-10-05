<?php

$editing = ($mode ?? 'create') === 'edit';

/** @var string $mode */
/** @var array<string, mixed>|null $item */

$item = $item ?? [];

$jenis = $item['jenis'] ?? 'pekerjaan';
$posisi = $item['posisi'] ?? '';
$instansi = $item['instansi'] ?? '';
$lokasi = $item['lokasi'] ?? '';
$tanggalMulai = $item['tanggal_mulai'] ?? '';
$tanggalSelesai = $item['tanggal_selesai'] ?? '';
$deskripsi = $item['deskripsi'] ?? '';

?>

<main class="min-h-screen bg-slate-50 pt-20 lg:ml-64">

    <div class="px-4 py-6 sm:px-6 lg:px-8">

        <div class="mx-auto w-full max-w-4xl">

            <!-- Header -->
            <div class="mb-5">

                <!-- Back -->
                <a
                    href="<?= e(url('/dashboard/mahasiswa/pengalaman')) ?>"
                    class="mb-4 inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-blue-600"
                >

                    <span>
                        ← Kembali ke Pengalaman
                    </span>

                </a>


                <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">
                    <?= $editing ? 'Edit Pengalaman' : 'Tambah Pengalaman' ?>
                </h1>

                <p class="mt-2 text-sm leading-6 text-gray-500 sm:text-base">
                    <?= $editing
                        ? 'Perbarui informasi pengalaman yang sudah kamu simpan.'
                        : 'Tambahkan pengalaman organisasi, pekerjaan, freelance, proyek, atau pengalaman lainnya.'
                    ?>
                </p>

            </div>


            <!-- Info -->
            <div class="mb-6 rounded-2xl border border-blue-100 bg-blue-50 px-4 py-4 sm:px-5">

                <div class="flex gap-3">

                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600">

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M12 21a9 9 0 100-18 9 9 0 000 18z"
                            />
                        </svg>

                    </div>

                    <div>

                        <p class="text-sm font-semibold text-blue-900">
                            Informasi pengalaman
                        </p>

                        <p class="mt-1 text-xs leading-5 text-blue-700 sm:text-sm">
                            Pengalaman magang yang telah selesai dan terverifikasi
                            akan ditambahkan oleh sistem secara otomatis.
                            Gunakan form ini untuk pengalaman non-magang.
                        </p>

                    </div>

                </div>

            </div>


            <!-- Form -->
            <form
                method="POST"
                action="<?= e(
                    url(
                        $editing
                            ? '/dashboard/mahasiswa/pengalaman/edit/' . ($item['id'] ?? 0)
                            : '/dashboard/mahasiswa/pengalaman/tambah'
                    )
                ) ?>"
                class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm"
            >

                <input
                    type="hidden"
                    name="_csrf_token"
                    value="<?= e(csrfToken()) ?>"
                >


                <!-- Form Header -->
                <div class="border-b border-gray-100 px-5 py-5 sm:px-6">

                    <h2 class="text-base font-semibold text-gray-900">
                        Informasi Pengalaman
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Lengkapi informasi berikut sesuai dengan pengalamanmu.
                    </p>

                </div>


                <!-- Form Body -->
                <div class="space-y-6 px-5 py-6 sm:px-6">


                    <!-- Jenis + Posisi -->
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                        <!-- Jenis -->
                        <div>

                            <label
                                for="jenis"
                                class="mb-2 block text-sm font-semibold text-gray-700"
                            >
                                Jenis
                                <span class="text-red-500">*</span>
                            </label>

                            <select
                                id="jenis"
                                name="jenis"
                                required
                                class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                            >

                                <option
                                    value="pekerjaan"
                                    <?= $jenis === 'pekerjaan' ? 'selected' : '' ?>
                                >
                                    Pekerjaan
                                </option>

                                <option
                                    value="organisasi"
                                    <?= $jenis === 'organisasi' ? 'selected' : '' ?>
                                >
                                    Organisasi
                                </option>

                                <option
                                    value="freelance"
                                    <?= $jenis === 'freelance' ? 'selected' : '' ?>
                                >
                                    Freelance
                                </option>

                                <option
                                    value="proyek"
                                    <?= $jenis === 'proyek' ? 'selected' : '' ?>
                                >
                                    Proyek
                                </option>

                                <option
                                    value="lainnya"
                                    <?= $jenis === 'lainnya' ? 'selected' : '' ?>
                                >
                                    Lainnya
                                </option>

                            </select>

                        </div>


                        <!-- Posisi -->
                        <div>

                            <label
                                for="posisi"
                                class="mb-2 block text-sm font-semibold text-gray-700"
                            >
                                Posisi / Peran
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="posisi"
                                type="text"
                                name="posisi"
                                maxlength="200"
                                required
                                value="<?= e($posisi) ?>"
                                placeholder="Contoh: Frontend Developer"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                            >

                            <p class="mt-1.5 text-xs text-gray-400">
                                Posisi atau peran yang kamu jalankan.
                            </p>

                        </div>

                    </div>


                    <!-- Instansi -->
                    <div>

                        <label
                            for="instansi"
                            class="mb-2 block text-sm font-semibold text-gray-700"
                        >
                            Instansi / Perusahaan
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            id="instansi"
                            type="text"
                            name="instansi"
                            maxlength="200"
                            required
                            value="<?= e($instansi) ?>"
                            placeholder="Contoh: Himpunan Mahasiswa Teknologi Informasi"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                        >

                    </div>


                    <!-- Lokasi -->
                    <div>

                        <label
                            for="lokasi"
                            class="mb-2 block text-sm font-semibold text-gray-700"
                        >
                            Lokasi
                            <span class="font-normal text-gray-400">
                                (Opsional)
                            </span>
                        </label>

                        <input
                            id="lokasi"
                            type="text"
                            name="lokasi"
                            maxlength="200"
                            value="<?= e($lokasi) ?>"
                            placeholder="Contoh: Malang, Jawa Timur"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                        >

                    </div>


                    <!-- Periode -->
                    <div>

                        <div class="mb-3">

                            <label class="block text-sm font-semibold text-gray-700">
                                Periode
                            </label>

                            <p class="mt-1 text-xs text-gray-400">
                                Tentukan periode pengalaman jika tersedia.
                            </p>

                        </div>


                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                            <div>

                                <label
                                    for="tanggal_mulai"
                                    class="mb-2 block text-sm font-medium text-gray-600"
                                >
                                    Tanggal Mulai
                                </label>

                                <input
                                    id="tanggal_mulai"
                                    type="date"
                                    name="tanggal_mulai"
                                    value="<?= e($tanggalMulai) ?>"
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                                >

                            </div>


                            <div>

                                <label
                                    for="tanggal_selesai"
                                    class="mb-2 block text-sm font-medium text-gray-600"
                                >
                                    Tanggal Selesai
                                </label>

                                <input
                                    id="tanggal_selesai"
                                    type="date"
                                    name="tanggal_selesai"
                                    value="<?= e($tanggalSelesai) ?>"
                                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                                >

                            </div>

                        </div>

                    </div>


                    <!-- Deskripsi -->
                    <div>

                        <label
                            for="deskripsi"
                            class="mb-2 block text-sm font-semibold text-gray-700"
                        >
                            Deskripsi
                            <span class="font-normal text-gray-400">
                                (Opsional)
                            </span>
                        </label>

                        <p class="mb-2 text-xs text-gray-400">
                            Jelaskan tanggung jawab, kontribusi, atau aktivitas yang kamu lakukan.
                        </p>

                        <textarea
                            id="deskripsi"
                            name="deskripsi"
                            rows="6"
                            placeholder="Contoh: Mengembangkan website internal organisasi menggunakan Laravel dan PostgreSQL..."
                            class="w-full resize-y rounded-xl border border-gray-200 px-4 py-3 text-sm leading-6 text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                        ><?= e($deskripsi) ?></textarea>

                    </div>

                </div>


                <!-- Footer -->
                <div class="flex flex-col gap-3 border-t border-gray-100 bg-gray-50/70 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">

                    <p class="text-xs text-gray-400">
                        <span class="text-red-500">*</span>
                        Wajib diisi
                    </p>


                    <div class="flex flex-col-reverse gap-2 sm:flex-row">

                        <a
                            href="<?= e(url('/dashboard/mahasiswa/pengalaman')) ?>"
                            class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                        >
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20"
                        >
                            <?= $editing ? 'Simpan Perubahan' : 'Simpan Pengalaman' ?>
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</main>