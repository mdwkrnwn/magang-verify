<?php
$tahapanPengajuan = [
    [
        'judul' => 'Pengajuan dikirim',
        'deskripsi' => 'Pengajuan dimulai dengan status menunggu persetujuan Dosen.',
    ],
    [
        'judul' => 'Persetujuan Dosen',
        'deskripsi' => 'Dosen memeriksa pengajuan dan dokumen wajib.',
    ],
    [
        'judul' => 'Respons Mitra',
        'deskripsi' => 'Setelah disetujui Dosen, Mitra dapat memproses seleksi, menerima, atau menolak.',
    ],
    [
        'judul' => 'Penempatan Magang',
        'deskripsi' => 'Jika diterima Mitra, pengajuan dilanjutkan ke pembuatan penempatan.',
    ],
];
?>
<section class="p-4 bg-white border border-slate-200 rounded-xl sm:p-5" aria-labelledby="application-flow-heading">
    <div class="flex items-start gap-3">
        <div class="flex items-center justify-center w-9 h-9 text-blue-600 bg-blue-50 rounded-lg" aria-hidden="true">
            <?= icon('layers', 'w-4 h-4') ?>
        </div>
        <div>
            <h2 id="application-flow-heading" class="text-base font-semibold text-slate-900">Alur Pengajuan Magang</h2>
            <p class="mt-1 text-xs text-slate-500">Tahapan setelah Anda mengirim pengajuan.</p>
        </div>
    </div>

    <ol class="mt-5 space-y-4">
        <?php foreach ($tahapanPengajuan as $index => $tahap): ?>
            <li class="flex items-start gap-3">
                <span class="flex items-center justify-center w-7 h-7 shrink-0 text-xs font-semibold <?= $index === 0 ? 'text-blue-700 bg-blue-100' : 'text-slate-600 bg-slate-100' ?> rounded-full" aria-hidden="true"><?= $index + 1 ?></span>
                <div class="min-w-0 pt-0.5">
                    <h3 class="text-sm font-medium text-slate-800"><?= e($tahap['judul']) ?></h3>
                    <p class="mt-0.5 text-xs leading-5 text-slate-500"><?= e($tahap['deskripsi']) ?></p>
                </div>
            </li>
        <?php endforeach; ?>
    </ol>
</section>
