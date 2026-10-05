<?php
$daftarDokumen = [
    'Wajib diunggah' => [
        'pakta_integritas' => 'Pakta Integritas',
        'daftar_riwayat_hidup' => 'Daftar Riwayat Hidup',
        'khs' => 'KHS / Cetak Siakad',
        'ktp' => 'KTP',
        'ktm' => 'KTM',
        'surat_izin_orang_tua' => 'Surat Izin Orang Tua',
    ],
    'Opsional' => [
        'bpjs' => 'Kartu BPJS / Asuransi lainnya',
        'sktm_kip' => 'SKTM / KIP Kuliah',
        'proposal' => 'Proposal Magang',
        'sertifikat_kompetensi' => 'Sertifikat Kompetensi',
    ],
];
?>

<section class="p-4 sm:p-5 lg:p-6 bg-white border border-slate-200 rounded-xl">
    <div class="flex items-start gap-3 mb-5">
        <div class="flex items-center justify-center w-9 h-9 shrink-0 rounded-lg bg-amber-50 text-amber-600">
            <?= icon('folder', 'w-4 h-4') ?>
        </div>
        <div>
            <h2 class="text-base sm:text-lg font-semibold text-slate-900">Dokumen Pengajuan</h2>
            <p class="mt-1 text-xs sm:text-sm text-slate-500">Unggah dokumen dalam format PDF, maksimal 5 MB per file.</p>
        </div>
    </div>

    <div class="space-y-6">
        <?php foreach ($daftarDokumen as $kelompok => $dokumen): ?>
            <div>
                <h3 class="mb-3 text-sm font-semibold text-slate-800">
                    <?= e($kelompok) ?>
                    <?php if ($kelompok === 'Opsional'): ?>
                        <span class="font-normal text-slate-500">(jika ada)</span>
                    <?php endif; ?>
                </h3>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <?php foreach ($dokumen as $field => $label): ?>
                        <?php $wajib = $kelompok === 'Wajib diunggah'; ?>
                        <div class="min-w-0 rounded-lg border border-slate-200 p-3">
                            <label for="<?= e($field) ?>" class="block mb-2 text-sm font-medium text-slate-700">
                                <?= e($label) ?>
                                <?php if ($wajib): ?><span class="text-red-500">*</span><?php else: ?><span class="text-xs font-normal text-slate-400">Opsional</span><?php endif; ?>
                            </label>
                            <input
                                id="<?= e($field) ?>"
                                name="<?= e($field) ?>"
                                type="file"
                                accept=".pdf,application/pdf"
                                form="form-pengajuan"
                                <?= $wajib ? 'required' : '' ?>
                                class="block w-full min-w-0 text-xs text-slate-500 file:mr-2 file:mb-1 file:rounded-lg file:border-0 file:px-3 file:py-2 file:text-xs file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                            >
                            <?php if (!empty($errors[$field])): ?>
                                <p class="mt-2 text-xs text-red-600"><?= e($errors[$field]) ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
