<?php

$items = is_array($items ?? null) ? $items : [];
$formValues = is_array($formValues ?? null) ? $formValues : [];

?>

<div class="mb-8">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="mb-2 text-sm font-medium text-blue-600">
                Manajemen Pengajuan
            </p>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">
                Pelamar / Pengajuan
            </h1>
            <p class="mt-2 text-sm text-gray-500">
                Tinjau pengajuan mahasiswa dan berikan respons terhadap permohonan magang.
            </p>
        </div>

        <div class="inline-flex w-fit items-center gap-2 rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-xs font-medium text-amber-700">
            <span class="h-2 w-2 rounded-full bg-amber-500"></span>
            Menunggu respons
        </div>
    </div>
</div>

<?php if (!empty($success)): ?>
    <div
        class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800"
        role="status"
    >
        <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
        <p>Respons pengajuan berhasil disimpan.</p>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div
        class="mb-6 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800"
        role="alert"
    >
        <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.86L1.82 18a2 2 0 001.7 3h16.96a2 2 0 001.7-3L13.7 3.86a2 2 0 00-3.4 0z" />
        </svg>
        <p><?= e($error) ?></p>
    </div>
<?php endif; ?>


<section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    <!-- Section Header -->
    <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <div>
            <h2 class="font-semibold text-gray-900">
                Daftar Pengajuan
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Pengajuan yang telah disetujui dosen dan memerlukan keputusan perusahaan.
            </p>
        </div>

        <div class="flex w-fit items-center gap-2 rounded-lg bg-slate-50 px-3 py-2">
            <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span class="text-sm font-semibold text-slate-700">
                <?= e((string) count($items)) ?>
            </span>
            <span class="text-xs text-slate-500">Pengajuan</span>
        </div>
    </div>


    <?php if ($items): ?>

        <div class="divide-y divide-slate-100">

            <?php foreach ($items as $item): ?>

                <?php
                $applicationId = (int) ($item['id'] ?? 0);
                $applicantName = (string) ($item['mahasiswa_nama'] ?? 'Mahasiswa');
                $applicationStatus = (string) ($item['status_pendaftaran'] ?? 'menunggu_respons_mitra');

                $applicationStatusLabel = $applicationStatus === 'seleksi'
                    ? 'Tahap Seleksi'
                    : 'Menunggu Respons';

                $submitted = strtotime((string) ($item['tanggal_pengajuan'] ?? ''));
                $start = strtotime((string) ($item['tanggal_mulai'] ?? ''));
                $end = strtotime((string) ($item['tanggal_selesai'] ?? ''));

                $documents = is_array($item['dokumen'] ?? null)
                    ? $item['dokumen']
                    : [];

                $initial = strtoupper(substr(trim($applicantName), 0, 1));
                ?>

                <article class="p-5 sm:p-6">

                    <div class="grid grid-cols-1 gap-6 xl:grid-cols-[1fr_1.05fr]">

                        <!-- Applicant Information -->
                        <div class="min-w-0">

                            <div class="flex items-start gap-3">

                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-sm font-bold text-blue-700">
                                    <?= e($initial) ?>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="font-semibold text-gray-900">
                                            <?= e($applicantName) ?>
                                        </h3>

                                        <span class="rounded-full px-2.5 py-1 text-[11px] font-medium
                                            <?= $applicationStatus === 'seleksi'
                                                ? 'bg-blue-50 text-blue-700'
                                                : 'bg-amber-50 text-amber-700' ?>">
                                            <?= e($applicationStatusLabel) ?>
                                        </span>
                                    </div>

                                    <p class="mt-1 text-xs text-slate-500">
                                        <?= e((string) ($item['nim'] ?? '-')) ?>

                                        <?php if (!empty($item['program_studi'])): ?>
                                            <span class="mx-1 text-slate-300">|</span>
                                            <?= e((string) $item['program_studi']) ?>
                                        <?php endif; ?>
                                    </p>
                                </div>

                            </div>


                            <div class="mt-5 rounded-xl border border-slate-100 bg-slate-50/70 p-4">

                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                    Formasi yang Dilamar
                                </p>

                                <p class="mt-2 text-sm font-semibold text-gray-900">
                                    <?= e((string) ($item['judul'] ?? '-')) ?>
                                </p>

                                <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">

                                    <div>
                                        <p class="text-xs text-slate-500">
                                            Periode Magang
                                        </p>
                                        <p class="mt-1 text-sm font-medium text-slate-700">
                                            <?= $start && $end
                                                ? e(date('d M Y', $start) . ' – ' . date('d M Y', $end))
                                                : '-' ?>
                                        </p>
                                    </div>

                                    <div>
                                        <p class="text-xs text-slate-500">
                                            Tanggal Pengajuan
                                        </p>
                                        <p class="mt-1 text-sm font-medium text-slate-700">
                                            <?= $submitted
                                                ? e(date('d M Y, H:i', $submitted))
                                                : '-' ?>
                                        </p>
                                    </div>

                                </div>

                            </div>


                            <?php if (!empty($item['catatan_dosen'])): ?>

                                <div class="mt-4 rounded-xl border border-blue-100 bg-blue-50/60 p-4">

                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4 shrink-0 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5m-8 7l3.5-3.5H19a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v13a2 2 0 002 2z" />
                                        </svg>

                                        <p class="text-xs font-semibold text-blue-800">
                                            Catatan Persetujuan Dosen
                                        </p>
                                    </div>

                                    <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-slate-600">
                                        <?= e((string) $item['catatan_dosen']) ?>
                                    </p>

                                </div>

                            <?php endif; ?>


                            <!-- Documents -->
                            <div class="mt-5">

                                <div class="mb-3 flex items-center gap-2">
                                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7V3m10 4V3M4 11h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z" />
                                    </svg>

                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-600">
                                        Dokumen Pendaftaran
                                    </p>
                                </div>

                                <?php if ($documents): ?>

                                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">

                                        <?php foreach ($documents as $document): ?>

                                            <a
                                                href="<?= e(url('/dashboard/mitra/pengajuan/dokumen/' . (int) ($document['id'] ?? 0))) ?>"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="group flex min-w-0 items-center gap-3 rounded-lg border border-slate-200 p-3 transition hover:border-blue-300 hover:bg-blue-50/50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            >

                                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600 group-hover:bg-blue-100">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 2v6h6M8 13h8M8 17h8" />
                                                    </svg>
                                                </div>

                                                <div class="min-w-0 flex-1">
                                                    <p class="truncate text-xs font-semibold text-slate-700">
                                                        <?= e((string) ($document['jenis'] ?? 'Dokumen')) ?>
                                                    </p>
                                                    <p class="mt-0.5 truncate text-[11px] text-slate-400">
                                                        <?= e((string) ($document['nama'] ?? 'file')) ?>
                                                    </p>
                                                </div>

                                                <svg class="h-4 w-4 shrink-0 text-slate-400 group-hover:text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17L17 7M7 7h10v10" />
                                                </svg>

                                            </a>

                                        <?php endforeach; ?>

                                    </div>

                                <?php else: ?>

                                    <div class="rounded-lg border border-dashed border-amber-200 bg-amber-50/50 px-4 py-3">
                                        <p class="text-xs text-amber-700">
                                            Dokumen belum terdaftar pada pengajuan ini.
                                        </p>
                                    </div>

                                <?php endif; ?>

                            </div>

                        </div>


                        <!-- Response Form -->
                        <div class="rounded-xl border border-slate-200 bg-white p-4 sm:p-5">

                            <div class="mb-5">
                                <h4 class="text-sm font-semibold text-gray-900">
                                    Respons Perusahaan
                                </h4>
                                <p class="mt-1 text-xs leading-relaxed text-slate-500">
                                    Tentukan keputusan terhadap pengajuan mahasiswa ini.
                                </p>
                            </div>


                            <form
                                method="POST"
                                action="<?= e(url('/dashboard/mitra/pengajuan')) ?>"
                                class="space-y-4"
                                onsubmit="const submitter=event.submitter;if(!submitter)return false;const message=submitter.value==='ditolak'?'Tolak pengajuan ini? Keputusan ini akan menutup proses pengajuan.':(submitter.value==='diterima'?'Terima pengajuan ini? Kuota formasi akan bertambah.':'');if(message&&!window.confirm(message))return false;this.querySelector('[name=decision]').value=submitter.value;const buttons=this.querySelectorAll('button[type=submit]');buttons.forEach((button)=>{button.disabled=true;button.classList.add('opacity-60','cursor-not-allowed');});this.setAttribute('aria-busy','true');"
                            >

                                <input
                                    type="hidden"
                                    name="_csrf_token"
                                    value="<?= e(csrfToken()) ?>"
                                >

                                <input
                                    type="hidden"
                                    name="application_id"
                                    value="<?= $applicationId ?>"
                                >

                                <input
                                    type="hidden"
                                    name="decision"
                                    value=""
                                >


                                <div>
                                    <label class="block text-sm font-medium text-slate-700">
                                        Catatan Respons
                                        <span class="font-normal text-slate-400">(Opsional)</span>
                                    </label>

                                    <textarea
                                        name="catatan_mitra"
                                        rows="4"
                                        maxlength="5000"
                                        class="mt-2 block w-full resize-y rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-700 placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                                        placeholder="Tuliskan catatan atau informasi tambahan untuk mahasiswa..."
                                    ><?= e($formValues[$applicationId] ?? '') ?></textarea>

                                    <p class="mt-1.5 text-xs text-slate-400">
                                        Maksimal 5.000 karakter.
                                    </p>
                                </div>


                                <div class="border-t border-slate-100 pt-4">

                                    <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Pilihan Keputusan
                                    </p>

                                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">

                                        <button
                                            type="submit"
                                            value="diterima"
                                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700 disabled:opacity-60"
                                            aria-label="Terima pengajuan <?= e($applicantName) ?>"
                                        >
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Terima
                                        </button>


                                        <?php if ($applicationStatus !== 'seleksi'): ?>

                                            <button
                                                type="submit"
                                                value="seleksi"
                                                class="inline-flex items-center justify-center gap-2 rounded-lg border border-blue-200 bg-white px-4 py-2.5 text-sm font-semibold text-blue-700 transition hover:bg-blue-50 disabled:opacity-60"
                                                aria-label="Pindahkan ke tahap seleksi untuk <?= e($applicantName) ?>"
                                            >
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                                </svg>
                                                Tahap Seleksi
                                            </button>

                                        <?php endif; ?>

                                    </div>

                                    <button
                                        type="submit"
                                        value="ditolak"
                                        class="mt-2 inline-flex w-full items-center justify-center gap-2 rounded-lg border border-red-200 bg-white px-4 py-2.5 text-sm font-semibold text-red-700 transition hover:bg-red-50 disabled:opacity-60"
                                        aria-label="Tolak pengajuan <?= e($applicantName) ?>"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Tolak Pengajuan
                                    </button>

                                </div>


                                <div class="flex items-start gap-2 rounded-lg bg-slate-50 p-3">
                                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86l-8.2 14.2A2 2 0 003.82 21h16.36a2 2 0 001.73-2.94l-8.2-14.2a2 2 0 00-3.42 0z" />
                                    </svg>
                                    <p class="text-xs leading-relaxed text-slate-500">
                                        Kuota formasi hanya bertambah ketika pengajuan diterima.
                                    </p>
                                </div>

                            </form>

                        </div>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>


    <?php elseif (empty($error)): ?>

        <div class="px-5 py-16 text-center sm:px-6">

            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5m-8 7l3.5-3.5H19a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v13a2 2 0 002 2z" />
                </svg>
            </div>

            <h3 class="mt-4 text-sm font-semibold text-gray-900">
                Belum Ada Pengajuan
            </h3>

            <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-slate-500">
                Saat ini tidak ada pengajuan yang menunggu respons.
                Pengajuan baru akan muncul setelah proses persetujuan dosen selesai.
            </p>

        </div>

    <?php endif; ?>

</section>