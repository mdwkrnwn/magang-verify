<?php

$base = url('/');
$active = 'penetapan-dosen';

$totalPeserta = is_countable($assignments) ? count($assignments) : 0;
$totalSudahAdaPembimbing = 0;
$totalBelumAdaPembimbing = 0;

foreach ($assignments as $assignment) {
    if (!empty($assignment['penugasan_id'])) {
        $totalSudahAdaPembimbing++;
    } else {
        $totalBelumAdaPembimbing++;
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Head.php'; ?>

<body class="overflow-x-hidden bg-slate-50 font-[Poppins] text-slate-900 antialiased">

    <?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Sidebar.php'; ?>
    <?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Header.php'; ?>

    <main class="min-h-screen bg-slate-50 pt-20 lg:ml-64">
        <div class="mx-auto max-w-[1500px] px-4 py-6 sm:px-6 lg:px-8 lg:py-8">

            <!-- Page heading -->
            <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <div class="mb-2 flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.16em] text-blue-600">
                        <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>
                        Manajemen Magang
                    </div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                        Penetapan Dosen Pembimbing
                    </h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                        Kelola proses magang dan atur dosen pembimbing mahasiswa dalam satu tempat.
                        Setiap penetapan atau penggantian disertai alasan.
                    </p>
                </div>
            </div>

            <!-- Notifications -->
            <?php if ($success): ?>
                <div role="status" class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm text-emerald-800 shadow-sm">
                    <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 13 4 4L19 7"/>
                    </svg>
                    <span>Penugasan dosen berhasil disimpan.</span>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div role="alert" class="mb-6 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm text-red-800 shadow-sm">
                    <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.3 3.8 1.9 18.2A2 2 0 0 0 3.6 21h16.8a2 2 0 0 0 1.7-2.8L13.7 3.8a2 2 0 0 0-3.4 0Z"/>
                    </svg>
                    <span><?= e($error) ?></span>
                </div>
            <?php endif; ?>

            <!-- Overview -->
            <section class="mb-7 grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-500">Total Peserta</p>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m16 0v-2a4 4 0 0 0-3-3.87M14 3.13a4 4 0 0 1 0 7.75M10 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z"/>
                            </svg>
                        </span>
                    </div>
                    <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900"><?= $totalPeserta ?></p>
                    <p class="mt-1 text-xs text-slate-400">Mahasiswa dalam proses magang</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-500">Sudah Ditugaskan</p>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m5 12 4 4L19 6"/>
                            </svg>
                        </span>
                    </div>
                    <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900"><?= $totalSudahAdaPembimbing ?></p>
                    <p class="mt-1 text-xs text-slate-400">Telah memiliki dosen pembimbing</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-500">Belum Ditugaskan</p>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4m0 4h.01M10.3 3.8 1.9 18.2A2 2 0 0 0 3.6 21h16.8a2 2 0 0 0 1.7-2.8L13.7 3.8a2 2 0 0 0-3.4 0Z"/>
                            </svg>
                        </span>
                    </div>
                    <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900"><?= $totalBelumAdaPembimbing ?></p>
                    <p class="mt-1 text-xs text-slate-400">Menunggu penetapan pembimbing</p>
                </div>
            </section>

            <!-- Create internship process -->
            <section class="mb-7 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-start gap-3 border-b border-slate-100 px-5 py-5 sm:px-6">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm shadow-blue-200">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 5v14m-7-7h14"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-slate-900">Buat Proses Magang</h2>
                        <p class="mt-1 text-sm leading-5 text-slate-500">
                            Tambahkan mahasiswa ke dalam proses magang aktif.
                        </p>
                    </div>
                </div>

                <form method="POST"
                    action="<?= e(url('/dashboard/koordinator-magang/penetapan-dosen')) ?>"
                    class="grid grid-cols-1 gap-4 p-5 sm:p-6 md:grid-cols-[minmax(0,1fr)_auto] md:items-end">

                    <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
                    <input type="hidden" name="mode" value="create_process">

                    <div>
                        <label for="mahasiswa_id" class="mb-2 block text-sm font-semibold text-slate-700">
                            Pilih Mahasiswa
                        </label>
                        <select id="mahasiswa_id" name="mahasiswa_id" required
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                            <option value="">Cari atau pilih mahasiswa</option>
                            <?php foreach ($eligibleStudents as $student): ?>
                                <option value="<?= e((string) $student['mahasiswa_id']) ?>">
                                    <?= e((string) $student['mahasiswa_nama']) ?> · <?= e((string) $student['nim']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <?php if (empty($eligibleStudents)): ?>
                            <p class="mt-2 flex items-center gap-1.5 text-xs text-amber-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01"/>
                                </svg>
                                Tidak ada mahasiswa yang tersedia untuk dibuatkan proses magang.
                            </p>
                        <?php else: ?>
                            <p class="mt-2 text-xs text-slate-400">Hanya mahasiswa yang belum memiliki proses magang aktif yang ditampilkan.</p>
                        <?php endif; ?>
                    </div>

                    <button type="submit"
                        <?= empty($eligibleStudents) ? 'disabled' : '' ?>
                        class="inline-flex min-h-[46px] items-center justify-center mb-6 gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm shadow-blue-200 transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20 disabled:cursor-not-allowed disabled:bg-slate-300 disabled:shadow-none">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m-7-7h14"/>
                        </svg>
                        Buat Proses Magang
                    </button>
                </form>
            </section>

            <!-- Participants -->
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-5 sm:px-6">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <h2 class="font-semibold text-slate-900">Peserta Proses Magang</h2>
                            <p class="mt-1 text-sm text-slate-500">
                                Pantau status pembimbing dan kelola penetapan setiap mahasiswa.
                            </p>
                        </div>

                        <?php if (!empty($assignments)): ?>
                            <div class="flex w-full flex-col gap-2 sm:flex-row lg:w-auto">
                                <div class="relative flex-1 lg:w-72">
                                    <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <circle cx="11" cy="11" r="7" stroke-width="1.8"/>
                                        <path stroke-linecap="round" stroke-width="1.8" d="m16 16 4 4"/>
                                    </svg>
                                    <input id="searchParticipant" type="search" placeholder="Cari nama atau NIM..."
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-3 text-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                                </div>
                                <select id="statusFilter"
                                    class="rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-600 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                                    <option value="all">Semua status</option>
                                    <option value="assigned">Sudah ada pembimbing</option>
                                    <option value="unassigned">Belum ada pembimbing</option>
                                </select>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!empty($assignments)): ?>
                    <div id="participantList" class="divide-y divide-slate-100">
                        <?php foreach ($assignments as $item): ?>
                            <?php
                                $hasSupervisor = !empty($item['penugasan_id']);
                                $studentName = (string) $item['mahasiswa_nama'];
                                $studentInitial = function_exists('mb_substr')
                                    ? mb_strtoupper(mb_substr($studentName, 0, 1, 'UTF-8'), 'UTF-8')
                                    : strtoupper(substr($studentName, 0, 1));
                            ?>

                            <article
                                data-participant
                                data-status="<?= $hasSupervisor ? 'assigned' : 'unassigned' ?>"
                                data-search="<?= e(strtolower($studentName . ' ' . (string) $item['nim'] . ' ' . (string) $item['proses_magang_id'])) ?>"
                                class="p-5 transition-colors hover:bg-slate-50/60 sm:p-6">

                                <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
                                    <!-- Student profile -->
                                    <div class="flex min-w-0 items-start gap-3.5">
                                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-base font-bold text-blue-700">
                                            <?= e($studentInitial) ?>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <h3 class="font-semibold text-slate-900">
                                                    <?= e($studentName) ?>
                                                </h3>

                                                <?php if ($hasSupervisor): ?>
                                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-semibold text-emerald-700">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                        Sudah ditugaskan
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-semibold text-amber-700">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                                        Menunggu pembimbing
                                                    </span>
                                                <?php endif; ?>
                                            </div>

                                            <div class="mt-1.5 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500">
                                                <span>NIM: <strong class="font-medium text-slate-700"><?= e((string) $item['nim']) ?></strong></span>
                                                <span class="text-slate-300">•</span>
                                                <span>Proses #<?= e((string) $item['proses_magang_id']) ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Supervisor summary -->
                                    <div class="xl:min-w-[280px] xl:max-w-[360px]">
                                        <?php if ($hasSupervisor): ?>
                                            <div class="flex items-start gap-3 rounded-xl border border-emerald-100 bg-emerald-50/60 px-4 py-3">
                                                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white text-emerald-600">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m16 0v-2a4 4 0 0 0-3-3.87M14 3.13a4 4 0 0 1 0 7.75M10 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z"/>
                                                    </svg>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-[11px] font-medium uppercase tracking-wide text-emerald-700">Dosen pembimbing</p>
                                                    <p class="mt-0.5 truncate text-sm font-semibold text-slate-800"><?= e((string) $item['dosen_nama']) ?></p>
                                                    <?php if (!empty($item['nidn'])): ?>
                                                        <p class="mt-0.5 text-xs text-slate-500">NIDN: <?= e((string) $item['nidn']) ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="flex items-center gap-2 rounded-xl border border-amber-100 bg-amber-50/60 px-4 py-3 text-sm text-amber-800">
                                                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4m0 4h.01M10.3 3.8 1.9 18.2A2 2 0 0 0 3.6 21h16.8a2 2 0 0 0 1.7-2.8L13.7 3.8a2 2 0 0 0-3.4 0Z"/>
                                                </svg>
                                                Belum ada dosen pembimbing.
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Assignment editor -->
                                <details class="assignment-details mt-4 overflow-hidden rounded-xl border border-slate-200 bg-white" <?= !$hasSupervisor ? 'open' : '' ?>>
                                    <summary class="flex cursor-pointer list-none items-center justify-between gap-3 px-4 py-3.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 [&::-webkit-details-marker]:hidden">
                                        <span class="flex items-center gap-2">
                                            <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 5v14m-7-7h14"/>
                                            </svg>
                                            <?= $hasSupervisor ? 'Ubah dosen pembimbing' : 'Tetapkan dosen pembimbing' ?>
                                        </span>
                                        <svg class="details-chevron h-4 w-4 shrink-0 text-slate-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6"/>
                                        </svg>
                                    </summary>

                                    <div class="border-t border-slate-100 bg-slate-50/70 p-4 sm:p-5">
                                        <form method="POST" action="<?= e(url('/dashboard/koordinator-magang/penetapan-dosen')) ?>">
                                            <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
                                            <input type="hidden" name="proses_magang_id" value="<?= e((string) $item['proses_magang_id']) ?>">
                                            <input type="hidden" name="mode" value="<?= $hasSupervisor ? 'replace' : 'assign' ?>">

                                            <div class="mb-4">
                                                <h4 class="text-sm font-semibold text-slate-800">
                                                    <?= $hasSupervisor ? 'Penggantian Dosen Pembimbing' : 'Form Penetapan Dosen Pembimbing' ?>
                                                </h4>
                                                <p class="mt-1 text-xs leading-5 text-slate-500">
                                                    <?= $hasSupervisor
                                                        ? 'Pilih dosen pengganti dan jelaskan alasan perubahan penugasan.'
                                                        : 'Pilih dosen yang akan membimbing mahasiswa ini dan tuliskan alasan penetapannya.' ?>
                                                </p>
                                            </div>

                                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                                                <div>
                                                    <label for="dosen_<?= e((string) $item['proses_magang_id']) ?>" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-600">
                                                        Dosen Pembimbing <span class="text-red-500">*</span>
                                                    </label>
                                                    <select id="dosen_<?= e((string) $item['proses_magang_id']) ?>" name="dosen_id" required
                                                        class="block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
                                                        <option value="">Pilih dosen pembimbing</option>
                                                        <?php foreach ($dosenOptions as $dosen): ?>
                                                            <option value="<?= e((string) $dosen['id']) ?>">
                                                                <?= e((string) $dosen['name']) ?><?= !empty($dosen['nidn']) ? ' · ' . e((string) $dosen['nidn']) : '' ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div>
                                                    <label for="alasan_<?= e((string) $item['proses_magang_id']) ?>" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-600">
                                                        Alasan <?= $hasSupervisor ? 'Penggantian' : 'Penetapan' ?> <span class="text-red-500">*</span>
                                                    </label>
                                                    <textarea id="alasan_<?= e((string) $item['proses_magang_id']) ?>" name="alasan" required maxlength="5000" rows="3"
                                                        placeholder="Tuliskan alasan penetapan atau penggantian dosen..."
                                                        class="block w-full resize-y rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"></textarea>
                                                </div>
                                            </div>

                                            <div class="mt-4 flex flex-col-reverse gap-2 border-t border-slate-200 pt-4 sm:flex-row sm:items-center sm:justify-between">
                                                <p class="text-xs text-slate-400"><span class="text-red-500">*</span> Wajib diisi</p>
                                                <button type="submit"
                                                    class="inline-flex min-h-[42px] items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm shadow-blue-200 transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 13 4 4L19 7"/>
                                                    </svg>
                                                    <?= $hasSupervisor ? 'Simpan Perubahan' : 'Tetapkan Pembimbing' ?>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </details>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <div id="noSearchResult" class="hidden px-5 py-12 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="7" stroke-width="1.8"/>
                                <path stroke-linecap="round" stroke-width="1.8" d="m16 16 4 4"/>
                            </svg>
                        </div>
                        <h3 class="mt-3 text-sm font-semibold text-slate-800">Data tidak ditemukan</h3>
                        <p class="mt-1 text-xs text-slate-500">Coba gunakan kata kunci atau filter status yang berbeda.</p>
                    </div>

                <?php else: ?>
                    <div class="px-5 py-16 text-center sm:py-20">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M17 20h5v-2a4 4 0 0 0-5.5-3.7M9 20H2v-2a4 4 0 0 1 7.5-1.7M16 3.5a4 4 0 0 1 0 7.8M8 3.5a4 4 0 0 1 0 7.8m8 8.7v-2a4 4 0 0 0-8 0v2"/>
                            </svg>
                        </div>
                        <h3 class="mt-4 text-base font-semibold text-slate-800">Belum Ada Proses Magang</h3>
                        <p class="mx-auto mt-1 max-w-md text-sm leading-6 text-slate-500">
                            Belum terdapat mahasiswa dengan proses magang aktif. Buat proses magang terlebih dahulu melalui formulir di atas.
                        </p>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </main>

    <style>
        .assignment-details[open] .details-chevron {
            transform: rotate(180deg);
        }
        .assignment-details summary:focus-visible {
            outline: 2px solid #3b82f6;
            outline-offset: -2px;
            border-radius: 0.75rem;
        }
    </style>

    <script>
        (() => {
            const searchInput = document.getElementById('searchParticipant');
            const statusFilter = document.getElementById('statusFilter');
            const participantList = document.getElementById('participantList');
            const emptyResult = document.getElementById('noSearchResult');

            if (!searchInput || !statusFilter || !participantList) return;

            const cards = Array.from(participantList.querySelectorAll('[data-participant]'));

            function filterParticipants() {
                const keyword = searchInput.value.trim().toLocaleLowerCase('id');
                const status = statusFilter.value;
                let visibleCount = 0;

                cards.forEach((card) => {
                    const matchesKeyword = (card.dataset.search || '').includes(keyword);
                    const matchesStatus = status === 'all' || card.dataset.status === status;
                    const visible = matchesKeyword && matchesStatus;

                    card.classList.toggle('hidden', !visible);
                    if (visible) visibleCount++;
                });

                emptyResult?.classList.toggle('hidden', visibleCount !== 0);
            }

            searchInput.addEventListener('input', filterParticipants);
            statusFilter.addEventListener('change', filterParticipants);
        })();
    </script>

</body>
</html>
