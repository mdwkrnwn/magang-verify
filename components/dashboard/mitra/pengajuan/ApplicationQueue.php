<?php

$items = is_array($items ?? null) ? $items : [];
$formValues = is_array($formValues ?? null) ? $formValues : [];
?>
<div class="mb-6">
    <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Pelamar / Pengajuan</h1>
    <p class="mt-2 text-sm text-gray-500">Pengajuan yang telah disetujui dosen dan menunggu respons perusahaan Anda.</p>
</div>
<?php if (!empty($success)): ?>
    <div class="p-4 mb-5 text-sm text-emerald-800 border border-emerald-200 bg-emerald-50 rounded-xl" role="status">Respons pengajuan berhasil disimpan.</div>
<?php endif; ?>
<?php if (!empty($error)): ?>
    <div class="p-4 mb-5 text-sm text-red-800 border border-red-200 bg-red-50 rounded-xl" role="alert"><?= e($error) ?></div>
<?php endif; ?>

<section class="overflow-hidden bg-white border border-gray-100 shadow-sm rounded-2xl" aria-labelledby="mitra-applications-heading">
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
        <div>
            <h2 id="mitra-applications-heading" class="font-semibold text-gray-900">Menunggu Respons</h2>
            <p class="mt-1 text-xs text-gray-500"><?= e((string) count($items)) ?> pengajuan dapat ditinjau</p>
        </div>
    </div>
    <?php if ($items): ?>
        <div class="divide-y divide-gray-100">
            <?php foreach ($items as $item): ?>
                <?php
                $applicationId = (int) ($item['id'] ?? 0);
                $applicantName = (string) ($item['mahasiswa_nama'] ?? 'Mahasiswa');
                $applicationStatus = (string) ($item['status_pendaftaran'] ?? 'menunggu_respons_mitra');
                $applicationStatusLabel = $applicationStatus === 'seleksi' ? 'Tahap Seleksi' : 'Menunggu Respons';
                $submitted = strtotime((string) ($item['tanggal_pengajuan'] ?? ''));
                $start = strtotime((string) ($item['tanggal_mulai'] ?? ''));
                $end = strtotime((string) ($item['tanggal_selesai'] ?? ''));
                $documents = is_array($item['dokumen'] ?? null) ? $item['dokumen'] : [];
                ?>
                <article class="p-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-5 lg:grid-cols-[1fr_1.2fr]">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="font-semibold text-gray-900"><?= e($applicantName) ?></h3>
                                <span class="px-2.5 py-1 text-[11px] font-medium text-blue-700 bg-blue-50 rounded-full"><?= e($applicationStatusLabel) ?></span>
                            </div>
                            <p class="mt-1 text-xs text-gray-500"><?= e((string) ($item['nim'] ?? '-')) ?><?= !empty($item['program_studi']) ? ' · ' . e((string) $item['program_studi']) : '' ?></p>
                            <p class="mt-3 text-sm font-medium text-gray-800"><?= e((string) ($item['judul'] ?? '-')) ?></p>
                            <p class="mt-1 text-xs text-gray-500">Periode: <?= $start && $end ? e(date('d M Y', $start) . ' – ' . date('d M Y', $end)) : '-' ?></p>
                            <p class="mt-1 text-xs text-gray-400">Diajukan <?= $submitted ? e(date('d M Y H:i', $submitted)) : '-' ?></p>
                            <?php if (!empty($item['catatan_dosen'])): ?>
                                <div class="p-3 mt-4 text-xs text-gray-600 bg-gray-50 rounded-lg">Catatan persetujuan dosen: <?= e((string) $item['catatan_dosen']) ?></div>
                            <?php endif; ?>
                            <?php if ($documents): ?>
                                <div class="mt-4">
                                    <p class="mb-2 text-xs font-semibold tracking-wide text-gray-500 uppercase">Dokumen Pendaftaran</p>
                                    <div class="flex flex-wrap gap-2">
                                        <?php foreach ($documents as $document): ?>
                                            <a href="<?= e(url('/dashboard/mitra/pengajuan/dokumen/' . (int) ($document['id'] ?? 0))) ?>" target="_blank" rel="noopener noreferrer" class="inline-flex px-3 py-2 text-xs font-medium text-blue-700 border border-blue-100 rounded-lg hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500"><?= e((string) ($document['jenis'] ?? 'Dokumen')) ?> · <?= e((string) ($document['nama'] ?? 'file')) ?></a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <p class="mt-4 text-xs text-amber-700">Dokumen belum terdaftar pada pengajuan ini.</p>
                            <?php endif; ?>
                        </div>
                        <form method="POST" action="<?= e(url('/dashboard/mitra/pengajuan')) ?>" class="space-y-3" onsubmit="const submitter=event.submitter;if(!submitter)return false;const message=submitter.value==='ditolak'?'Tolak pengajuan ini? Keputusan ini akan menutup proses pengajuan.':(submitter.value==='diterima'?'Terima pengajuan ini? Kuota formasi akan bertambah.':'');if(message&&!window.confirm(message))return false;this.querySelector('[name=decision]').value=submitter.value;const buttons=this.querySelectorAll('button[type=submit]');buttons.forEach((button)=>{button.disabled=true;button.classList.add('opacity-60','cursor-not-allowed');});this.setAttribute('aria-busy','true');">
                            <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
                            <input type="hidden" name="application_id" value="<?= $applicationId ?>">
                            <input type="hidden" name="decision" value="">
                            <label class="block text-sm font-medium text-gray-700">Catatan respons <span class="font-normal text-gray-400">(opsional)</span>
                                <textarea name="catatan_mitra" rows="3" maxlength="5000" class="block w-full mt-2 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Catatan untuk mahasiswa."><?= e($formValues[$applicationId] ?? '') ?></textarea>
                            </label>
                            <div class="flex flex-wrap gap-2">
                                <button type="submit" value="diterima" class="px-4 py-2 text-sm font-semibold text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 disabled:opacity-60" aria-label="Terima pengajuan <?= e($applicantName) ?>">Terima</button>
                                <?php if ($applicationStatus !== 'seleksi'): ?>
                                    <button type="submit" value="seleksi" class="px-4 py-2 text-sm font-semibold text-blue-700 border border-blue-200 rounded-xl hover:bg-blue-50 disabled:opacity-60" aria-label="Pindahkan ke tahap seleksi untuk <?= e($applicantName) ?>">Tahap Seleksi</button>
                                <?php endif; ?>
                                <button type="submit" value="ditolak" class="px-4 py-2 text-sm font-semibold text-red-700 border border-red-200 rounded-xl hover:bg-red-50 disabled:opacity-60" aria-label="Tolak pengajuan <?= e($applicantName) ?>">Tolak</button>
                            </div>
                            <p class="text-xs text-gray-400">Kuota hanya berkurang ketika pengajuan diterima.</p>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php elseif (empty($error)): ?>
        <div class="px-5 py-12 text-center">
            <p class="text-sm font-medium text-gray-700">Tidak ada pengajuan yang menunggu respons.</p>
            <p class="mt-1 text-xs text-gray-500">Pengajuan akan muncul setelah tahap persetujuan dosen selesai.</p>
        </div>
    <?php endif; ?>
</section>
