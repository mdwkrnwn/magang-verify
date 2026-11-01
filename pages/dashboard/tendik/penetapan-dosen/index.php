<?php

$base = url('/');
$active = 'penetapan-dosen';

?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . '/../../../../components/dashboard/tendik/Head.php'; ?>

<body class="overflow-x-hidden bg-slate-50 font-[Poppins] text-gray-900">

    <?php include __DIR__ . '/../../../../components/dashboard/tendik/Sidebar.php'; ?>
    <?php include __DIR__ . '/../../../../components/dashboard/tendik/Header.php'; ?>

    <main class="min-h-screen bg-slate-50 pt-20 lg:ml-64">

        <div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

            <!-- Page Header -->
            <div class="mb-8">

                <div class="mb-2 flex items-center gap-2 text-sm font-medium text-blue-600">

                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m7-8a4 4 0 100-8 4 4 0 000 8Zm9-3v6m3-3h-6" />
                    </svg>

                    Administrasi Magang

                </div>

                <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">
                    Penetapan Dosen Pembimbing
                </h1>

                <p class="mt-2 max-w-3xl text-sm leading-relaxed text-gray-500 sm:text-base">
                    Kelola penugasan dosen pembimbing untuk setiap proses magang mahasiswa.
                    Setiap penetapan atau penggantian wajib disertai alasan dan tercatat dalam riwayat.
                </p>

            </div>


            <!-- Notification -->
            <?php if (!empty($success)): ?>

                <div class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4" role="status">

                    <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7" />
                    </svg>

                    <p class="text-sm font-medium text-emerald-700">
                        Penugasan dosen berhasil disimpan.
                    </p>

                </div>

            <?php endif; ?>


            <?php if (!empty($error)): ?>

                <div class="mb-6 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4" role="alert">

                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v4m0 4h.01M10.3 3.86 1.82 18a2 2 0 001.7 3h16.96a2 2 0 001.7-3L13.7 3.86a2 2 0 00-3.4 0z" />
                    </svg>

                    <p class="text-sm text-red-700">
                        <?= e($error) ?>
                    </p>

                </div>

            <?php endif; ?>


            <!-- Create Internship Process -->
            <section class="mb-6 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

                <div class="border-b border-gray-100 px-5 py-5 sm:px-7">

                    <div class="flex items-center gap-3">

                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">

                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M12 5v14m-7-7h14" />
                            </svg>

                        </div>

                        <div>

                            <h2 class="text-base font-semibold text-gray-900">
                                Buat Proses Magang
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Pilih mahasiswa untuk memulai proses penugasan pembimbing.
                            </p>

                        </div>

                    </div>

                </div>


                <form
                    method="POST"
                    action="<?= e(url('/dashboard/tendik/penetapan-dosen')) ?>"
                    class="flex flex-col gap-4 p-5 sm:p-7">

                    <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
                    <input type="hidden" name="mode" value="create_process">

                    <div class="max-w-2xl">

                        <label for="mahasiswa_id" class="block text-sm font-medium text-gray-700">
                            Pilih Mahasiswa
                        </label>

                        <select
                            id="mahasiswa_id"
                            name="mahasiswa_id"
                            required
                            class="mt-2 block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                            <option value="">
                                Pilih mahasiswa
                            </option>

                            <?php foreach ($eligibleStudents as $student): ?>

                                <option value="<?= e((string) $student['mahasiswa_id']) ?>">

                                    <?= e((string) $student['mahasiswa_nama']) ?>
                                    · <?= e((string) $student['nim']) ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                        <p class="mt-2 text-xs text-gray-400">
                            Hanya mahasiswa yang belum memiliki proses magang aktif yang dapat dipilih.
                        </p>

                    </div>

                    <div>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">

                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 5v14m-7-7h14" />
                            </svg>

                            Buat Proses Magang

                        </button>

                    </div>

                </form>

            </section>


            <!-- Active Internship Participants -->
            <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

                <!-- Section Header -->
                <div class="flex flex-col gap-3 border-b border-gray-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-7">

                    <div>

                        <h2 class="text-base font-semibold text-gray-900">
                            Penugasan Dosen Pembimbing
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Kelola pembimbing untuk mahasiswa dengan proses magang aktif.
                        </p>

                    </div>

                    <span class="w-fit rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">
                        <?= count($candidates) ?> Peserta
                    </span>

                </div>


                <?php if (!empty($candidates)): ?>

                    <div class="divide-y divide-gray-100">

                        <?php foreach ($candidates as $candidate): ?>

                            <?php $hasAssignment = !empty($candidate['penugasan_id']); ?>

                            <article class="p-5 transition-colors hover:bg-gray-50/50 sm:p-7">

                                <div class="flex flex-col gap-5 xl:flex-row xl:items-start xl:justify-between">

                                    <!-- Student Information -->
                                    <div class="min-w-0 flex-1">

                                        <div class="flex items-start gap-3">

                                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-sm font-semibold text-blue-600">

                                                <?= e(strtoupper(substr(
                                                    (string) ($candidate['mahasiswa_nama'] ?? 'M'),
                                                    0,
                                                    1
                                                ))) ?>

                                            </div>

                                            <div class="min-w-0">

                                                <h3 class="font-semibold text-gray-900">
                                                    <?= e((string) ($candidate['mahasiswa_nama'] ?? '-')) ?>
                                                </h3>

                                                <p class="mt-1 text-sm text-gray-500">
                                                    <?= e((string) ($candidate['nim'] ?? '-')) ?>
                                                </p>

                                                <p class="mt-2 text-xs text-gray-400">
                                                    ID Proses Magang:
                                                    #<?= e((string) ($candidate['proses_magang_id'] ?? '-')) ?>
                                                </p>

                                            </div>

                                        </div>


                                        <!-- Assignment Status -->
                                        <div class="mt-5">

                                            <?php if ($hasAssignment): ?>

                                                <div class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-medium text-emerald-700">

                                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>

                                                    Sudah Memiliki Pembimbing

                                                </div>

                                                <div class="mt-3 rounded-xl border border-gray-100 bg-gray-50 p-4">

                                                    <p class="text-xs font-medium text-gray-400">
                                                        DOSEN PEMBIMBING AKTIF
                                                    </p>

                                                    <p class="mt-2 text-sm font-semibold text-gray-900">
                                                        <?= e((string) ($candidate['dosen_nama'] ?? '-')) ?>
                                                    </p>

                                                    <?php if (!empty($candidate['nidn'])): ?>

                                                        <p class="mt-1 text-xs text-gray-500">
                                                            NIDN: <?= e((string) $candidate['nidn']) ?>
                                                        </p>

                                                    <?php endif; ?>

                                                </div>

                                            <?php else: ?>

                                                <div class="inline-flex items-center gap-2 rounded-full bg-amber-50 px-3 py-1.5 text-xs font-medium text-amber-700">

                                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>

                                                    Belum Memiliki Pembimbing

                                                </div>

                                                <p class="mt-2 text-sm text-gray-500">
                                                    Mahasiswa ini belum memiliki dosen pembimbing yang ditetapkan.
                                                </p>

                                            <?php endif; ?>

                                        </div>

                                    </div>


                                    <!-- Assignment Form -->
                                    <div class="w-full xl:max-w-xl">

                                        <form
                                            method="POST"
                                            action="<?= e(url('/dashboard/tendik/penetapan-dosen')) ?>"
                                            class="space-y-4 rounded-xl border border-gray-100 bg-gray-50/70 p-4 sm:p-5">

                                            <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">

                                            <input
                                                type="hidden"
                                                name="proses_magang_id"
                                                value="<?= e((string) $candidate['proses_magang_id']) ?>">

                                            <input
                                                type="hidden"
                                                name="mode"
                                                value="<?= $hasAssignment ? 'replace' : 'assign' ?>">

                                            <div>

                                                <label class="block text-sm font-medium text-gray-700">
                                                    <?= $hasAssignment ? 'Pilih Dosen Pengganti' : 'Pilih Dosen Pembimbing' ?>
                                                </label>

                                                <select
                                                    name="dosen_id"
                                                    required
                                                    class="mt-2 block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                                                    <option value="">
                                                        Pilih dosen
                                                    </option>

                                                    <?php foreach ($dosenOptions as $dosen): ?>

                                                        <option value="<?= e((string) $dosen['id']) ?>">

                                                            <?= e((string) $dosen['name']) ?>

                                                            <?= !empty($dosen['nidn'])
                                                                ? ' · ' . e((string) $dosen['nidn'])
                                                                : '' ?>

                                                        </option>

                                                    <?php endforeach; ?>

                                                </select>

                                            </div>


                                            <div>

                                                <label class="block text-sm font-medium text-gray-700">

                                                    Alasan <?= $hasAssignment ? 'Penggantian' : 'Penetapan' ?>

                                                </label>

                                                <textarea
                                                    name="alasan"
                                                    required
                                                    maxlength="5000"
                                                    rows="3"
                                                    placeholder="<?= $hasAssignment
                                                                        ? 'Jelaskan alasan penggantian dosen pembimbing...'
                                                                        : 'Tuliskan alasan penetapan dosen pembimbing...' ?>"
                                                    class="mt-2 block w-full resize-y rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"></textarea>

                                            </div>


                                            <button
                                                type="submit"
                                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 sm:w-auto">

                                                <?php if ($hasAssignment): ?>

                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M16 3l5 5-5 5M21 8H9a6 6 0 00-6 6v2" />
                                                    </svg>

                                                    Ganti Pembimbing

                                                <?php else: ?>

                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 5v14m-7-7h14" />
                                                    </svg>

                                                    Tetapkan Pembimbing

                                                <?php endif; ?>

                                            </button>

                                        </form>

                                    </div>

                                </div>

                            </article>

                        <?php endforeach; ?>

                    </div>

                <?php else: ?>

                    <!-- Empty State -->
                    <div class="px-5 py-16 text-center">

                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 text-gray-400">

                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m7-8a4 4 0 100-8 4 4 0 000 8Zm9-3v6m3-3h-6" />
                            </svg>

                        </div>

                        <h3 class="mt-4 text-sm font-semibold text-gray-900">
                            Belum Ada Peserta Magang
                        </h3>

                        <p class="mx-auto mt-1 max-w-md text-sm text-gray-500">
                            Peserta dengan proses magang aktif akan muncul di sini
                            untuk mendapatkan penetapan dosen pembimbing.
                        </p>

                    </div>

                <?php endif; ?>

            </section>


            <?php include __DIR__ . '/../../../../components/dashboard/tendik/Footer.php'; ?>

        </div>

    </main>

</body>

</html>