
<?php
$base = url('/');
$active = 'penetapan-dosen';
?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Head.php'; ?>

<body class="overflow-x-hidden bg-slate-50 font-[Poppins] text-gray-900">

    <?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Sidebar.php'; ?>
    <?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Header.php'; ?>

    <main class="min-h-screen bg-slate-50 pt-20 lg:ml-64">
        <div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

            <!-- Page Header -->
            <div class="mb-7">
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                    Penetapan Dosen Pembimbing
                </h1>
                <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500 sm:text-base">
                    Kelola penugasan dosen pembimbing untuk setiap proses magang mahasiswa.
                    Setiap penetapan dan penggantian pembimbing akan disertai alasan.
                </p>
            </div>

            <!-- Notification -->
            <?php if ($success): ?>
                <div
                    class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm text-emerald-800"
                    role="status"
                >
                    <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 13 4 4L19 7"/>
                    </svg>
                    <span>Penugasan dosen berhasil disimpan.</span>
                </div>
            <?php endif; ?>

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

            <!-- Create Internship Process -->
            <section class="mb-7 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-100 px-5 py-5 sm:px-6">
                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 5v14m-7-7h14"/>
                            </svg>
                        </div>

                        <div>
                            <h2 class="font-semibold text-slate-900">
                                Buat Proses Magang
                            </h2>
                            <p class="mt-1 text-sm text-slate-500">
                                Pilih mahasiswa yang belum memiliki proses magang aktif.
                            </p>
                        </div>
                    </div>
                </div>

                <form
                    method="POST"
                    action="<?= e(url('/dashboard/koordinator-magang/penetapan-dosen')) ?>"
                    class="flex flex-col gap-4 p-5 sm:p-6 lg:flex-row lg:items-end"
                >
                    <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
                    <input type="hidden" name="mode" value="create_process">

                    <div class="min-w-0 flex-1">
                        <label for="mahasiswa_id" class="mb-2 block text-sm font-medium text-slate-700">
                            Mahasiswa
                        </label>

                        <select
                            id="mahasiswa_id"
                            name="mahasiswa_id"
                            required
                            class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        >
                            <option value="">Pilih mahasiswa</option>

                            <?php foreach ($eligibleStudents as $student): ?>
                                <option value="<?= e((string) $student['mahasiswa_id']) ?>">
                                    <?= e((string) $student['mahasiswa_nama']) ?>
                                    · <?= e((string) $student['nim']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <?php if (empty($eligibleStudents)): ?>
                            <p class="mt-2 text-xs text-amber-600">
                                Tidak ada mahasiswa yang tersedia untuk dibuatkan proses magang.
                            </p>
                        <?php endif; ?>
                    </div>

                    <button
                        type="submit"
                        class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m-7-7h14"/>
                        </svg>
                        Buat Proses Magang
                    </button>
                </form>
            </section>

            <!-- Internship Participants -->
            <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

                <!-- Section Header -->
                <div class="border-b border-slate-100 px-5 py-5 sm:px-6">
                    <h2 class="font-semibold text-slate-900">
                        Peserta Proses Magang
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Daftar mahasiswa beserta status penugasan dosen pembimbing.
                    </p>
                </div>

                <?php if ($assignments): ?>

                    <div class="divide-y divide-slate-100">

                        <?php foreach ($assignments as $item): ?>

                            <article class="p-5 transition hover:bg-slate-50/40 sm:p-6">

                                <!-- Student Information -->
                                <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">

                                    <div class="flex min-w-0 items-start gap-3">

                                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-50 text-sm font-semibold text-blue-700">
                                            <?= e(strtoupper(substr((string) $item['mahasiswa_nama'], 0, 1))) ?>
                                        </div>

                                        <div class="min-w-0">
                                            <h3 class="font-semibold text-slate-900">
                                                <?= e((string) $item['mahasiswa_nama']) ?>
                                            </h3>

                                            <p class="mt-1 text-sm text-slate-500">
                                                NIM: <?= e((string) $item['nim']) ?>
                                            </p>

                                            <p class="mt-1 text-xs text-slate-400">
                                                Proses Magang #<?= e((string) $item['proses_magang_id']) ?>
                                            </p>
                                        </div>

                                    </div>

                                    <?php if (!empty($item['penugasan_id'])): ?>

                                        <span class="inline-flex w-fit shrink-0 items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-medium text-emerald-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            Sudah memiliki pembimbing
                                        </span>

                                    <?php else: ?>

                                        <span class="inline-flex w-fit shrink-0 items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1.5 text-xs font-medium text-amber-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                            Belum memiliki pembimbing
                                        </span>

                                    <?php endif; ?>

                                </div>

                                <!-- Current Supervisor -->
                                <?php if (!empty($item['penugasan_id'])): ?>

                                    <div class="mb-5 rounded-lg border border-emerald-100 bg-emerald-50/50 px-4 py-3">
                                        <p class="text-xs font-medium text-emerald-700">
                                            Dosen Pembimbing Saat Ini
                                        </p>

                                        <p class="mt-1 text-sm font-semibold text-slate-800">
                                            <?= e((string) $item['dosen_nama']) ?>
                                        </p>

                                        <?php if (!empty($item['nidn'])): ?>
                                            <p class="mt-1 text-xs text-slate-500">
                                                NIDN: <?= e((string) $item['nidn']) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>

                                <?php else: ?>

                                    <div class="mb-5 rounded-lg border border-amber-100 bg-amber-50/50 px-4 py-3">
                                        <p class="text-sm text-amber-700">
                                            Mahasiswa ini belum memiliki dosen pembimbing.
                                            Silakan lakukan penetapan.
                                        </p>
                                    </div>

                                <?php endif; ?>

                                <!-- Assignment Form -->
                                <form
                                    method="POST"
                                    action="<?= e(url('/dashboard/koordinator-magang/penetapan-dosen')) ?>"
                                    class="rounded-xl border border-slate-200 bg-slate-50/70 p-4 sm:p-5"
                                >
                                    <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
                                    <input type="hidden" name="proses_magang_id" value="<?= e((string) $item['proses_magang_id']) ?>">
                                    <input type="hidden" name="mode" value="<?= !empty($item['penugasan_id']) ? 'replace' : 'assign' ?>">

                                    <div class="mb-4">
                                        <h4 class="text-sm font-semibold text-slate-800">
                                            <?= !empty($item['penugasan_id']) ? 'Penggantian Dosen Pembimbing' : 'Penetapan Dosen Pembimbing' ?>
                                        </h4>
                                        <p class="mt-1 text-xs text-slate-500">
                                            <?= !empty($item['penugasan_id'])
                                                ? 'Pilih dosen baru dan sertakan alasan penggantian.'
                                                : 'Pilih dosen yang akan membimbing mahasiswa ini.' ?>
                                        </p>
                                    </div>

                                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">

                                        <!-- Lecturer Select -->
                                        <div>
                                            <label
                                                for="dosen_<?= e((string) $item['proses_magang_id']) ?>"
                                                class="mb-2 block text-sm font-medium text-slate-700"
                                            >
                                                Dosen Pembimbing
                                            </label>

                                            <select
                                                id="dosen_<?= e((string) $item['proses_magang_id']) ?>"
                                                name="dosen_id"
                                                required
                                                class="block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                            >
                                                <option value="">Pilih dosen</option>

                                                <?php foreach ($dosenOptions as $dosen): ?>
                                                    <option value="<?= e((string) $dosen['id']) ?>">
                                                        <?= e((string) $dosen['name']) ?>
                                                        <?= !empty($dosen['nidn']) ? ' · ' . e((string) $dosen['nidn']) : '' ?>
                                                    </option>
                                                <?php endforeach; ?>

                                            </select>
                                        </div>

                                        <!-- Reason -->
                                        <div>
                                            <label
                                                for="alasan_<?= e((string) $item['proses_magang_id']) ?>"
                                                class="mb-2 block text-sm font-medium text-slate-700"
                                            >
                                                Alasan <?= !empty($item['penugasan_id']) ? 'Penggantian' : 'Penetapan' ?>
                                            </label>

                                            <textarea
                                                id="alasan_<?= e((string) $item['proses_magang_id']) ?>"
                                                name="alasan"
                                                required
                                                maxlength="5000"
                                                rows="3"
                                                placeholder="Tuliskan alasan penetapan atau penggantian dosen..."
                                                class="block w-full resize-y rounded-lg border border-slate-300 bg-white px-3.5 py-3 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                            ></textarea>
                                        </div>

                                    </div>

                                    <!-- Submit -->
                                    <div class="mt-4 flex justify-end border-t border-slate-200 pt-4">
                                        <button
                                            type="submit"
                                            class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:w-auto"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>

                                            <?= !empty($item['penugasan_id'])
                                                ? 'Ganti Pembimbing'
                                                : 'Tetapkan Pembimbing' ?>
                                        </button>
                                    </div>

                                </form>

                            </article>

                        <?php endforeach; ?>

                    </div>

                <?php else: ?>

                    <!-- Empty State -->
                    <div class="px-5 py-16 text-center">

                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M17 20h5v-2a4 4 0 00-5.5-3.7M9 20H2v-2a4 4 0 017.5-1.7M16 3.5a4 4 0 010 7.8M8 3.5a4 4 0 000 7.8m8 8.7v-2a4 4 0 00-8 0v2"/>
                            </svg>
                        </div>

                        <h3 class="mt-4 text-sm font-semibold text-slate-800">
                            Belum Ada Proses Magang
                        </h3>

                        <p class="mx-auto mt-1 max-w-md text-sm text-slate-500">
                            Belum terdapat mahasiswa dengan proses magang aktif.
                            Buat proses magang terlebih dahulu melalui formulir di atas.
                        </p>

                    </div>

                <?php endif; ?>

            </section>

        </div>
    </main>

</body>
</html>
