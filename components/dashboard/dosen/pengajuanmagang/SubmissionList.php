<section class="overflow-hidden bg-white border border-slate-100 shadow-sm rounded-2xl">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <div>
            <h2 class="font-semibold text-slate-900">Pengajuan Magang</h2>
            <p class="mt-1 text-xs text-slate-500">Pengajuan menunggu yang dapat ditinjau oleh dosen.</p>
        </div>
        <span class="hidden px-3 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded-full sm:inline-flex"><?= count($items ?? []) ?> Menunggu</span>
    </div>

    <div class="divide-y divide-slate-100">
        <?php foreach (($items ?? []) as $item): ?>
            <?php
                $name = (string) ($item['mahasiswa_nama'] ?? 'Mahasiswa');
                $submitted = strtotime((string) ($item['tanggal_pengajuan'] ?? ''));
                $docs = $item['dokumen'] ?? [];
                $requiredDocuments = [
                    'pakta_integritas' => 'Pakta Integritas',
                    'daftar_riwayat_hidup' => 'Daftar Riwayat Hidup',
                    'khs' => 'KHS / Cetak Siakad',
                    'ktp' => 'KTP',
                    'ktm' => 'KTM',
                    'surat_izin_orang_tua' => 'Surat Izin Orang Tua',
                ];
                $presentKinds = array_column($docs, 'jenis');
                $missingDocuments = array_diff_key($requiredDocuments, array_fill_keys($presentKinds, true));
            ?>
            <article data-submission-row data-search="<?= e($name . ' ' . ($item['nim'] ?? '') . ' ' . ($item['nama_perusahaan'] ?? '') . ' ' . ($item['judul'] ?? '')) ?>" data-status="menunggu" class="p-5 transition hover:bg-slate-50/70">
                <div class="grid grid-cols-1 gap-5 xl:grid-cols-[1fr_1.2fr]">
                    <div class="flex items-start gap-4 min-w-0">
                        <div class="flex items-center justify-center flex-shrink-0 w-11 h-11 font-semibold text-blue-700 bg-blue-50 rounded-xl">
                            <?= e(function_exists('initials') ? initials($name) : strtoupper(mb_substr($name, 0, 2))) ?>
                        </div>
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="font-semibold text-slate-900"><?= e($name) ?></h3>
                                <span class="text-xs text-slate-400"><?= e((string) ($item['nim'] ?? '-')) ?></span>
                            </div>
                            <p class="mt-1 text-sm text-slate-500"><?= e((string) ($item['judul'] ?? '-')) ?></p>
                            <p class="mt-2 text-xs text-slate-400"><?= e((string) ($item['nama_perusahaan'] ?? '-')) ?></p>
                            <p class="mt-2 text-xs text-gray-500">Diajukan <?= $submitted ? e(date('d M Y H:i', $submitted)) : '-' ?></p>
                            <?php if (!empty($item['catatan_dosen'])): ?><p class="mt-2 text-xs text-gray-500">Catatan: <?= e((string) $item['catatan_dosen']) ?></p><?php endif; ?>
                        </div>
                    </div>

                    <div>
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                            <p class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Dokumen Pendaftaran</p>
                            <span class="px-2.5 py-1 text-[11px] font-semibold <?= $missingDocuments ? 'text-amber-800 bg-amber-50' : 'text-emerald-800 bg-emerald-50' ?> rounded-full">
                                <?= count($requiredDocuments) - count($missingDocuments) ?>/<?= count($requiredDocuments) ?> dokumen wajib
                            </span>
                        </div>
                        <?php if ($missingDocuments): ?>
                            <p class="mb-3 text-xs text-amber-800">Belum tersedia: <?= e(implode(', ', $missingDocuments)) ?>. Persetujuan belum dapat diberikan.</p>
                        <?php endif; ?>
                        <?php if ($docs): ?>
                            <div class="flex flex-wrap gap-2">
                                <?php foreach ($docs as $doc): ?>
                                    <a href="<?= e(url('/dashboard/dosen/pengajuan/dokumen/' . (int) $doc['id'])) ?>" target="_blank" rel="noopener" class="inline-flex items-center px-3 py-2 text-xs font-medium text-blue-700 border border-blue-100 rounded-lg hover:bg-blue-50"><?= e((string) $doc['jenis']) ?> · <?= e((string) $doc['nama']) ?></a>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-xs text-amber-700">Dokumen belum terdaftar.</p>
                        <?php endif; ?>

                        <form method="POST" action="<?= e(url('/dashboard/dosen/pengajuan')) ?>" class="mt-4 space-y-3" onsubmit="const submitter=event.submitter;if(!submitter)return false;const message=submitter.value==='ditolak'?'Tolak pengajuan ini? Keputusan ini akan menutup proses pengajuan.':'Setujui pengajuan ini untuk diteruskan ke Mitra?';if(!window.confirm(message))return false;this.querySelector('[name=decision]').value=submitter.value;const buttons=this.querySelectorAll('button[type=submit]');buttons.forEach((button)=>{button.disabled=true;button.classList.add('opacity-60','cursor-not-allowed');});this.setAttribute('aria-busy','true');">
                            <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
                            <input type="hidden" name="application_id" value="<?= e((string) $item['id']) ?>">
                            <input type="hidden" name="decision" value="">
                            <label class="block text-sm font-medium text-gray-700">Catatan keputusan <span class="font-normal text-gray-400">(opsional saat menyetujui, wajib saat menolak)</span>
                                <textarea name="catatan_dosen" rows="2" maxlength="5000" aria-describedby="decisionNoteHelp-<?= (int) $item['id'] ?>" class="block w-full mt-2 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Catatan persetujuan atau alasan penolakan."><?= e($formValues[(int) ($item['id'] ?? 0)] ?? '') ?></textarea>
                                <span id="decisionNoteHelp-<?= (int) $item['id'] ?>" class="mt-1 block text-xs font-normal text-slate-500">Alasan penolakan harus diisi.</span>
                            </label>
                            <div class="flex flex-wrap gap-2">
                                <button type="submit" value="disetujui" onclick="const note=this.form.elements.catatan_dosen;note.required=false;note.setCustomValidity('');" <?= $missingDocuments ? 'disabled title="Lengkapi dokumen wajib terlebih dahulu"' : '' ?> class="px-4 py-2 text-sm font-semibold text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 disabled:opacity-60 disabled:cursor-not-allowed" aria-label="Setujui pengajuan <?= e($name) ?>"><?= $missingDocuments ? 'Dokumen Belum Lengkap' : 'Setujui' ?></button>
                                <button type="submit" value="ditolak" onclick="const note=this.form.elements.catatan_dosen;note.required=true;note.setCustomValidity(note.value.trim()?'':'Alasan penolakan wajib diisi.');if(!note.value.trim()){event.preventDefault();note.reportValidity();}" class="px-4 py-2 text-sm font-semibold text-red-700 border border-red-200 rounded-xl hover:bg-red-50 disabled:opacity-60" aria-label="Tolak pengajuan <?= e($name) ?>">Tolak</button>
                            </div>
                        </form>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>

        <?php if (empty($error)): ?>
            <div id="emptySubmissionState" class="<?= !empty($items) ? 'hidden' : '' ?> px-5 py-12 text-center">
                <h3 class="font-semibold text-slate-700">Tidak ada pengajuan yang menunggu persetujuan</h3>
                <p class="mt-1 text-sm text-slate-400">Pengajuan baru akan muncul setelah mahasiswa melengkapi persyaratan.</p>
            </div>
        <?php endif; ?>
    </div>
</section>
