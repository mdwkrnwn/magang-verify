<section class="space-y-5">
    <?php if (!empty($success)): ?><div class="p-4 text-sm text-emerald-800 border border-emerald-200 bg-emerald-50 rounded-xl" role="status">Penilaian berhasil disimpan.</div><?php endif; ?>
    <?php if (!empty($error)): ?><div class="p-4 text-sm text-red-800 border border-red-200 bg-red-50 rounded-xl" role="alert"><?= e($error) ?></div><?php endif; ?>

    <div class="p-5 bg-white border border-gray-100 shadow-sm sm:p-7 rounded-2xl">
        <div class="mb-5"><h2 class="text-lg font-semibold text-gray-900">Penilaian Magang</h2><p class="mt-1 text-sm text-gray-500">Nilai tiap aspek dicatat sesuai penilaian Anda. Sistem tidak menghitung bobot atau nilai akhir otomatis.</p></div>
        <?php if (!empty($items)): ?>
            <div class="space-y-5">
                <?php foreach ($items as $item): ?>
                    <?php
                        $status = (string) ($item['status_penilaian'] ?? '');
                        $editable = $status === '' || in_array($status, ['draft', 'perlu_revisi'], true);
                        $statusLabel = $status === '' ? 'Belum dinilai' : ucfirst(str_replace('_', ' ', $status));
                    ?>
                    <article class="p-4 border border-gray-100 rounded-xl sm:p-5">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-900"><?= e((string) $item['mahasiswa_nama']) ?> · <?= e((string) $item['nim']) ?></h3>
                                <p class="mt-1 text-sm text-gray-500"><?= e((string) $item['nama_perusahaan']) ?> · <?= e((string) $item['judul']) ?></p>
                                <p class="mt-1 text-xs text-gray-400">Penempatan #<?= e((string) $item['penempatan_id']) ?> · <?= e((string) $item['tanggal_mulai']) ?> – <?= e((string) $item['tanggal_selesai']) ?></p>
                            </div>
                            <span class="px-3 py-1 text-xs font-medium text-blue-700 bg-blue-50 rounded-full"><?= e($statusLabel) ?></span>
                        </div>

                        <?php if ($editable): ?>
                            <form method="POST" class="mt-5 space-y-4">
                                <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
                                <input type="hidden" name="penempatan_id" value="<?= e((string) $item['penempatan_id']) ?>">
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                    <?php foreach ([
                                        'nilai_kedisiplinan' => 'Kedisiplinan',
                                        'nilai_komunikasi' => 'Komunikasi',
                                        'nilai_kerja_sama' => 'Kerja Sama',
                                        'nilai_tanggung_jawab' => 'Tanggung Jawab',
                                        'nilai_keterampilan' => 'Keterampilan',
                                    ] as $field => $label): ?>
                                        <label class="block text-sm font-medium text-gray-700"><?= e($label) ?> (0–100)
                                            <input type="number" name="<?= e($field) ?>" min="0" max="100" step="0.01" value="<?= e((string) ($item[$field] ?? '')) ?>" class="block w-full mt-2 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                        </label>
                                    <?php endforeach; ?>
                                    <label class="block text-sm font-medium text-gray-700">Nilai akhir manual (opsional; tidak dihitung sistem)
                                        <input type="number" name="nilai_akhir" min="0" max="100" step="0.01" value="<?= e((string) ($item['nilai_akhir'] ?? '')) ?>" class="block w-full mt-2 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                    </label>
                                </div>
                                <label class="block text-sm font-medium text-gray-700">Catatan
                                    <textarea name="catatan" rows="3" maxlength="5000" class="block w-full mt-2 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"><?= e((string) ($item['catatan'] ?? '')) ?></textarea>
                                </label>
                                <div class="flex flex-wrap gap-3">
                                    <button type="submit" name="action" value="draft" class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-200 rounded-xl hover:bg-gray-50">Simpan Draft</button>
                                    <button type="submit" name="action" value="submit" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700">Ajukan Penilaian</button>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="grid grid-cols-2 gap-3 mt-5 text-sm sm:grid-cols-3">
                                <?php foreach ([
                                    'nilai_kedisiplinan' => 'Kedisiplinan',
                                    'nilai_komunikasi' => 'Komunikasi',
                                    'nilai_kerja_sama' => 'Kerja Sama',
                                    'nilai_tanggung_jawab' => 'Tanggung Jawab',
                                    'nilai_keterampilan' => 'Keterampilan',
                                    'nilai_akhir' => 'Nilai Akhir Manual',
                                ] as $field => $label): ?>
                                    <div class="p-3 bg-gray-50 rounded-lg"><p class="text-xs text-gray-500"><?= e($label) ?></p><p class="mt-1 font-semibold text-gray-800"><?= e((string) ($item[$field] ?? '-')) ?></p></div>
                                <?php endforeach; ?>
                            </div>
                            <?php if (!empty($item['catatan'])): ?><p class="mt-3 text-sm text-gray-600 whitespace-pre-line"><?= e((string) $item['catatan']) ?></p><?php endif; ?>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="px-4 py-10 text-center border border-dashed border-gray-200 rounded-xl"><p class="text-sm font-medium text-gray-700">Belum ada penempatan yang memasuki tahap penilaian.</p><p class="mt-1 text-xs text-gray-500">Penilaian tersedia ketika status penempatan menunggu penilaian atau selesai.</p></div>
        <?php endif; ?>
    </div>
</section>
