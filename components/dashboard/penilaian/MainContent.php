<section class="space-y-6">

    <?php if (!empty($success)): ?>
        <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800" role="status">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            Penilaian berhasil disimpan.
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.86L1.82 18a2 2 0 001.7 3h16.96a2 2 0 001.7-3L13.7 3.86a2 2 0 00-3.4 0z"/>
            </svg>
            <?= e($error) ?>
        </div>
    <?php endif; ?>


    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <!-- Header -->
        <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div>
                <p class="mb-1 text-sm font-medium text-blue-600">
                    Evaluasi Mahasiswa
                </p>
                <h2 class="text-lg font-semibold text-gray-900">
                    Penilaian Magang
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Berikan penilaian berdasarkan performa mahasiswa selama menjalani kegiatan magang.
                </p>
            </div>

            <div class="flex w-fit items-center gap-2 rounded-lg bg-slate-50 px-3 py-2">
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 3c-2.755 0-5.293.936-7.312 2.508A12.025 12.025 0 003 12c0 2.136.558 4.142 1.535 5.883A11.963 11.963 0 0012 21c2.755 0 5.293-.936 7.312-2.508A12.025 12.025 0 0021 12c0-1.633-.326-3.19-.916-4.604"/>
                </svg>
                <span class="text-sm font-semibold text-slate-700">
                    <?= e((string) count($items ?? [])) ?>
                </span>
                <span class="text-xs text-slate-500">Mahasiswa</span>
            </div>
        </div>


        <?php if (!empty($items)): ?>

            <div class="divide-y divide-slate-100">

                <?php foreach ($items as $item): ?>

                    <?php
                    $status = (string) ($item['status_penilaian'] ?? '');
                    $editable = $status === '' || in_array($status, ['draft', 'perlu_revisi'], true);

                    $statusLabel = $status === ''
                        ? 'Belum dinilai'
                        : ucfirst(str_replace('_', ' ', $status));

                    $statusClass = match ($status) {
                        'draft' => 'bg-slate-100 text-slate-700',
                        'perlu_revisi' => 'bg-amber-50 text-amber-700',
                        'diajukan' => 'bg-blue-50 text-blue-700',
                        'selesai' => 'bg-emerald-50 text-emerald-700',
                        default => 'bg-slate-100 text-slate-600',
                    };
                    ?>

                    <article class="p-5 sm:p-6">

                        <!-- Student Information -->
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                            <div class="flex min-w-0 items-start gap-3">

                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-sm font-bold text-blue-700">
                                    <?= e(strtoupper(substr((string) ($item['mahasiswa_nama'] ?? 'M'), 0, 1))) ?>
                                </div>

                                <div class="min-w-0">
                                    <h3 class="font-semibold text-gray-900">
                                        <?= e((string) ($item['mahasiswa_nama'] ?? '-')) ?>
                                    </h3>

                                    <p class="mt-1 text-xs text-slate-500">
                                        NIM <?= e((string) ($item['nim'] ?? '-')) ?>
                                    </p>

                                    <p class="mt-2 text-sm font-medium text-slate-700">
                                        <?= e((string) ($item['judul'] ?? '-')) ?>
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        <?= e((string) ($item['nama_perusahaan'] ?? '-')) ?>
                                    </p>
                                </div>

                            </div>

                            <span class="w-fit shrink-0 rounded-full px-3 py-1.5 text-xs font-medium <?= $statusClass ?>">
                                <?= e($statusLabel) ?>
                            </span>

                        </div>


                        <!-- Placement Period -->
                        <div class="mt-5 grid grid-cols-1 gap-3 rounded-xl border border-slate-100 bg-slate-50/70 p-4 sm:grid-cols-2">

                            <div>
                                <p class="text-xs text-slate-500">
                                    ID Penempatan
                                </p>
                                <p class="mt-1 text-sm font-medium text-slate-700">
                                    #<?= e((string) ($item['penempatan_id'] ?? '-')) ?>
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-slate-500">
                                    Periode Magang
                                </p>
                                <p class="mt-1 text-sm font-medium text-slate-700">
                                    <?= e((string) ($item['tanggal_mulai'] ?? '-')) ?>
                                    <span class="mx-1 text-slate-400">–</span>
                                    <?= e((string) ($item['tanggal_selesai'] ?? '-')) ?>
                                </p>
                            </div>

                        </div>


                        <?php if ($editable): ?>

                            <!-- Evaluation Form -->
                            <form method="POST" class="mt-6 space-y-5">

                                <input
                                    type="hidden"
                                    name="_csrf_token"
                                    value="<?= e(csrfToken()) ?>"
                                >

                                <input
                                    type="hidden"
                                    name="penempatan_id"
                                    value="<?= e((string) $item['penempatan_id']) ?>"
                                >


                                <div>
                                    <div class="mb-4">
                                        <h4 class="text-sm font-semibold text-gray-900">
                                            Aspek Penilaian
                                        </h4>
                                        <p class="mt-1 text-xs text-slate-500">
                                            Masukkan nilai untuk setiap aspek dengan rentang 0–100.
                                        </p>
                                    </div>

                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

                                        <?php foreach ([
                                            'nilai_kedisiplinan' => 'Kedisiplinan',
                                            'nilai_komunikasi' => 'Komunikasi',
                                            'nilai_kerja_sama' => 'Kerja Sama',
                                            'nilai_tanggung_jawab' => 'Tanggung Jawab',
                                            'nilai_keterampilan' => 'Keterampilan',
                                        ] as $field => $label): ?>

                                            <label class="block">
                                                <span class="text-sm font-medium text-slate-700">
                                                    <?= e($label) ?>
                                                </span>

                                                <div class="relative mt-2">
                                                    <input
                                                        type="number"
                                                        name="<?= e($field) ?>"
                                                        min="0"
                                                        max="100"
                                                        step="0.01"
                                                        value="<?= e((string) ($item[$field] ?? '')) ?>"
                                                        class="block w-full rounded-lg border border-slate-300 px-3 py-2.5 pr-12 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                                                        placeholder="0–100"
                                                    >
                                                    <span class="absolute inset-y-0 right-3 flex items-center text-xs text-slate-400">
                                                        Poin
                                                    </span>
                                                </div>
                                            </label>

                                        <?php endforeach; ?>


                                        <label class="block">
                                            <span class="text-sm font-medium text-slate-700">
                                                Nilai Akhir Manual
                                            </span>

                                            <div class="relative mt-2">
                                                <input
                                                    type="number"
                                                    name="nilai_akhir"
                                                    min="0"
                                                    max="100"
                                                    step="0.01"
                                                    value="<?= e((string) ($item['nilai_akhir'] ?? '')) ?>"
                                                    class="block w-full rounded-lg border border-slate-300 px-3 py-2.5 pr-12 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                                                    placeholder="Opsional"
                                                >
                                                <span class="absolute inset-y-0 right-3 flex items-center text-xs text-slate-400">
                                                    Poin
                                                </span>
                                            </div>
                                        </label>

                                    </div>

                                    <p class="mt-3 text-xs text-slate-400">
                                        Nilai akhir diisi secara manual dan tidak dihitung otomatis oleh sistem.
                                    </p>
                                </div>


                                <!-- Notes -->
                                <div class="border-t border-slate-100 pt-5">

                                    <label class="block text-sm font-medium text-slate-700">
                                        Catatan Penilaian
                                        <span class="font-normal text-slate-400">(Opsional)</span>
                                    </label>

                                    <textarea
                                        name="catatan"
                                        rows="4"
                                        maxlength="5000"
                                        class="mt-2 block w-full resize-y rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-700 placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                                        placeholder="Tuliskan evaluasi, kelebihan, atau hal yang perlu ditingkatkan oleh mahasiswa..."
                                    ><?= e((string) ($item['catatan'] ?? '')) ?></textarea>

                                </div>


                                <!-- Actions -->
                                <div class="flex flex-col-reverse gap-2 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">

                                    <button
                                        type="submit"
                                        name="action"
                                        value="draft"
                                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Simpan Draft
                                    </button>

                                    <button
                                        type="submit"
                                        name="action"
                                        value="submit"
                                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700"
                                    >
                                        Ajukan Penilaian
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6l6 6-6 6"/>
                                        </svg>
                                    </button>

                                </div>

                            </form>


                        <?php else: ?>

                            <!-- Read-only Evaluation -->
                            <div class="mt-6">

                                <div class="mb-4">
                                    <h4 class="text-sm font-semibold text-gray-900">
                                        Hasil Penilaian
                                    </h4>
                                    <p class="mt-1 text-xs text-slate-500">
                                        Rincian nilai yang telah diajukan.
                                    </p>
                                </div>

                                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">

                                    <?php foreach ([
                                        'nilai_kedisiplinan' => 'Kedisiplinan',
                                        'nilai_komunikasi' => 'Komunikasi',
                                        'nilai_kerja_sama' => 'Kerja Sama',
                                        'nilai_tanggung_jawab' => 'Tanggung Jawab',
                                        'nilai_keterampilan' => 'Keterampilan',
                                        'nilai_akhir' => 'Nilai Akhir Manual',
                                    ] as $field => $label): ?>

                                        <div class="rounded-lg border border-slate-100 bg-slate-50 p-3">
                                            <p class="text-xs text-slate-500">
                                                <?= e($label) ?>
                                            </p>
                                            <p class="mt-2 text-lg font-semibold text-slate-800">
                                                <?= e((string) ($item[$field] ?? '-')) ?>
                                            </p>
                                        </div>

                                    <?php endforeach; ?>

                                </div>


                                <?php if (!empty($item['catatan'])): ?>

                                    <div class="mt-4 rounded-xl border border-slate-100 bg-slate-50 p-4">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                            Catatan Penilaian
                                        </p>
                                        <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-slate-600">
                                            <?= e((string) $item['catatan']) ?>
                                        </p>
                                    </div>

                                <?php endif; ?>

                            </div>

                        <?php endif; ?>

                    </article>

                <?php endforeach; ?>

            </div>


        <?php else: ?>

            <div class="px-5 py-16 text-center sm:px-6">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 3c-2.755 0-5.293.936-7.312 2.508A12.025 12.025 0 003 12c0 2.136.558 4.142 1.535 5.883A11.963 11.963 0 0012 21c2.755 0 5.293-.936 7.312-2.508A12.025 12.025 0 0021 12c0-1.633-.326-3.19-.916-4.604"/>
                    </svg>
                </div>

                <h3 class="mt-4 text-sm font-semibold text-gray-900">
                    Belum Ada Penempatan untuk Dinilai
                </h3>

                <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-slate-500">
                    Penilaian akan tersedia ketika mahasiswa telah memasuki tahap penilaian atau menyelesaikan kegiatan magang.
                </p>

            </div>

        <?php endif; ?>

    </div>

    <div class="rounded-lg border border-blue-100 bg-blue-50/60 px-4 py-3">
        <div class="flex items-start gap-2">
            <svg class="mt-0.5 h-4 w-4 shrink-0 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86l-8.2 14.2A2 2 0 003.82 21h16.36a2 2 0 001.73-2.94l-8.2-14.2a2 2 0 00-3.42 0z"/>
            </svg>
            <p class="text-xs leading-relaxed text-blue-800">
                Setiap aspek dinilai secara terpisah. Sistem tidak menghitung bobot maupun nilai akhir secara otomatis.
            </p>
        </div>
    </div>

</section>