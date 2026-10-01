<?php

// Butuh: $m (satu mitra, termasuk 'formasi')
$inisial = strtoupper(substr(preg_replace('/^(PT|CV)\.?\s+/i', '', $m['nama']), 0, 2));
$card = 'bg-white rounded-2xl border border-slate-100 shadow-sm p-5 md:p-6 scroll-mt-24';

$judul = function ($ic, $t) {
    echo '<h2 class="flex items-center gap-3 font-semibold text-slate-900 mb-4">'
        . '<span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 grid place-items-center">' . icon($ic, 'w-4 h-4') . '</span>'
        . $t . '</h2>';
};

$cek = fn($ok, $teks) => '<li class="flex gap-2.5 text-sm text-slate-700">'
    . '<span class="mt-0.5 shrink-0 ' . ($ok ? 'text-green-600' : 'text-slate-300') . '">'
    . '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m4.5 12.75 6 6 9-13.5"/></svg></span>'
    . e($teks) . '</li>';

$menu = [
    ['tentang', 'Tentang', 'briefcase'],
    ['formasi', 'Formasi Magang', 'layers'],
    ['alur', 'Alur Pengajuan', 'bolt'],
    ['kontak', 'Kontak', 'mail'],
];

$alur = [
    ['Pilih formasi', 'Pilih formasi yang sesuai dengan prodi dan minat Anda dari halaman ini.'],
    ['Lengkapi dokumen', 'Unggah proposal pengajuan dan fakta integritas melalui sistem.'],
    ['Diperiksa kampus', 'Koordinator Magang memeriksa kelengkapan berkas dan kesesuaian dengan capaian pembelajaran.'],
    ['Diproses perusahaan', 'Surat pengantar dikirim ke perusahaan. Keputusan penerimaan berada di pihak perusahaan, di luar sistem.'],
    ['Pantau status dan LOA', 'Unggah LOA bila diterima, lalu isi logbook selama magang.'],
];

?>

<div class="max-w-7xl mx-auto px-4 sm:px-6">

    <!-- Breadcrumb -->
    <nav class="text-xs text-slate-600 flex flex-wrap items-center gap-2 py-5" aria-label="Breadcrumb">
        <a href="<?= url('/') ?>" class="hover:text-blue-600">Beranda</a><span>›</span>
        <a href="<?= url('/mitra') ?>" class="hover:text-blue-600">Daftar Mitra</a><span>›</span>
        <span>Detail Mitra</span>
    </nav>


    <!-- Banner -->
    <section class="rounded-2xl bg-gradient-to-r from-blue-100/70 via-blue-50 to-white border border-blue-100 p-5 sm:p-8">

        <div class="flex flex-col md:flex-row items-center md:items-start gap-6 md:gap-8">

            <?php if (!empty($m['logo'])): ?>
                <img
                    src="<?= url('/assets/images/mitra/' . e($m['logo'])) ?>"
                    alt="Logo <?= e($m['nama']) ?>"
                    class="w-28 h-28 sm:w-32 sm:h-32 rounded-2xl bg-white border border-blue-100 object-contain p-3 shrink-0">
            <?php else: ?>
                <span class="w-28 h-28 sm:w-32 sm:h-32 rounded-2xl bg-white border border-blue-100 text-blue-600 text-4xl font-bold grid place-items-center shrink-0">
                    <?= e($inisial) ?>
                </span>
            <?php endif; ?>

            <div class="min-w-0 text-center md:text-left flex-1">

                <div class="inline-flex items-center gap-2 text-xs font-medium text-blue-700 bg-white/80 border border-blue-100 rounded-full pl-1.5 pr-3 py-1">
                    <span class="w-5 h-5 rounded-full bg-blue-600 text-white grid place-items-center">
                        <?= icon('shield', 'w-3 h-3') ?>
                    </span>
                    <?= e($m['status']) ?>
                </div>

                <h1 class="mt-3 text-2xl md:text-3xl font-bold text-slate-900 leading-tight">
                    <?= e($m['nama']) ?>
                </h1>

                <div class="flex flex-wrap justify-center md:justify-start gap-2 mt-3">
                    <?php foreach ([$m['kategori'] ?? '', 'Skala ' . ($m['skala'] ?? '-'), $m['lokasi']] as $chip): ?>
                        <?php if ($chip !== ''): ?>
                            <span class="px-3 py-1 rounded-full bg-white/80 border border-blue-100 text-xs font-medium text-slate-700"><?= e($chip) ?></span>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <?php foreach ($m['bidang'] as $b): ?>
                        <span class="px-3 py-1 rounded-full bg-blue-600/10 text-xs font-medium text-blue-700"><?= e($b) ?></span>
                    <?php endforeach; ?>
                </div>

            </div>

            <!-- Ringkasan angka -->
            <dl class="grid grid-cols-3 gap-3 w-full md:w-auto md:min-w-[270px] text-center">
                <?php foreach ([[(int)$m['posisi'], 'Kuota magang'], [count($m['formasi']), 'Formasi'], [(int)$m['magang'], 'Mahasiswa magang']] as [$n, $l]): ?>
                    <div class="rounded-xl bg-white/80 border border-blue-100 px-2 py-3">
                        <dd class="text-xl font-semibold text-slate-900 leading-none"><?= $n ?></dd>
                        <dt class="text-[11px] text-slate-500 mt-1.5 leading-4"><?= $l ?></dt>
                    </div>
                <?php endforeach; ?>
            </dl>

        </div>

    </section>


    <div
        id="profil-content-area"
        class="relative mt-6 grid gap-6 lg:grid-cols-[210px_1fr_290px] items-start">

        <!-- Menu samping -->
        <aside class="hidden lg:block w-[210px] min-w-0">
            <nav
                id="profil-sidebar"
                class="absolute top-0 left-0 w-[210px] flex flex-col gap-1 bg-white rounded-2xl border border-slate-100 p-2 shadow-sm"
                aria-label="Bagian detail mitra">
                <?php foreach ($menu as $i => [$id, $label, $ic]): ?>
                    <a
                        href="#<?= e($id) ?>"
                        data-section="<?= e($id) ?>"
                        class="profil-menu-item flex items-center gap-3 px-3 sm:px-4 py-2.5 rounded-lg text-sm whitespace-nowrap
                <?= $i === 0
                        ? 'bg-blue-50 text-blue-600 font-medium border-l-4 border-blue-600'
                        : 'text-slate-700 hover:bg-slate-50'
                ?>">
                        <?= icon($ic, 'w-5 h-5 shrink-0') ?>
                        <span><?= e($label) ?></span>
                    </a>
                <?php endforeach; ?>
            </nav>
        </aside>


        <!-- Konten utama -->
        <main class="space-y-4 min-w-0">

            <!-- Tentang -->
            <section id="tentang" class="<?= $card ?>">
                <?php $judul('briefcase', 'Tentang Mitra'); ?>

                <p class="text-sm text-slate-700 leading-relaxed">
                    <?= e($m['tentang']) ?>
                </p>

                <dl class="grid sm:grid-cols-2 gap-4 mt-5 pt-5 border-t border-slate-100 text-sm">
                    <?php foreach (
                        [
                            ['Kategori perusahaan', $m['kategori'] ?? '-'],
                            ['Skala perusahaan', $m['skala'] ?? '-'],
                            ['Wilayah', $m['wilayah']],
                            ['Tahun akademik', $m['tahun_akademik']],
                        ] as [$l, $v]
                    ): ?>
                        <div>
                            <dt class="text-xs text-slate-500"><?= $l ?></dt>
                            <dd class="text-slate-800 mt-0.5"><?= e($v) ?></dd>
                        </div>
                    <?php endforeach; ?>
                </dl>

                <div class="mt-5 pt-5 border-t border-slate-100">
                    <p class="text-xs text-slate-500 mb-2">Skema magang yang diterima</p>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($m['skema'] as $s): ?>
                            <span class="px-3 py-1 rounded-full bg-slate-100 text-xs text-slate-700"><?= e($s) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>


            <!-- Formasi -->
            <section id="formasi" class="<?= $card ?>">
                <?php $judul('layers', 'Formasi Magang'); ?>

                <?php if (!$m['formasi']): ?>
                    <p class="text-sm text-slate-500">Belum ada formasi yang dibuka oleh mitra ini.</p>
                <?php endif; ?>

                <div class="space-y-4">
                    <?php foreach ($m['formasi'] as $f): ?>

                        <article class="rounded-xl border border-slate-100 p-4 sm:p-5">

                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <h3 class="font-semibold text-slate-900"><?= e($f['posisi']) ?></h3>
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        <?php foreach ($f['prodi'] as $pr): ?>
                                            <span class="px-2.5 py-1 rounded-full bg-blue-50 text-xs text-slate-700"><?= e($pr) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <a
                                    href="<?= url('/login') ?>"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-blue-500 text-blue-600
                                    text-sm font-medium hover:bg-blue-50 transition whitespace-nowrap">
                                    Masuk untuk mengajukan
                                    <?= icon('arrow', 'w-4 h-4') ?>
                                </a>
                            </div>

                            <dl class="grid grid-cols-3 gap-3 mt-4 text-sm">
                                <?php foreach ([['Kuota', $f['kuota'] . ' orang'], ['Periode', $f['periode']], ['Batas daftar', $f['batas']]] as [$l, $v]): ?>
                                    <div>
                                        <dt class="text-xs text-slate-500"><?= $l ?></dt>
                                        <dd class="text-slate-800 mt-0.5"><?= e($v) ?></dd>
                                    </div>
                                <?php endforeach; ?>
                            </dl>

                            <div class="mt-4 pt-4 border-t border-slate-100 text-sm">
                                <p class="text-xs font-semibold text-slate-800 mb-1">Deskripsi pekerjaan</p>
                                <p class="text-slate-600 leading-relaxed"><?= e($f['jobdesc']) ?></p>

                                <p class="text-xs font-semibold text-slate-800 mt-4 mb-1">Persyaratan mahasiswa</p>
                                <ul class="list-disc pl-4 space-y-1 text-slate-600">
                                    <?php foreach ($f['syarat'] as $sy): ?>
                                        <li><?= e($sy) ?></li>
                                    <?php endforeach; ?>
                                </ul>

                                <p class="text-xs font-semibold text-slate-800 mt-4 mb-1">Tahapan seleksi</p>
                                <p class="text-slate-600"><?= e($f['seleksi']) ?></p>
                            </div>

                        </article>

                    <?php endforeach; ?>
                </div>
            </section>


            <!-- Alur -->
            <section id="alur" class="<?= $card ?>">
                <?php $judul('bolt', 'Alur Pengajuan Magang'); ?>

                <ol class="space-y-4">
                    <?php foreach ($alur as $i => [$t, $d]): ?>
                        <li class="flex gap-4">
                            <span class="w-7 h-7 rounded-full bg-blue-600 text-white text-xs font-semibold grid place-items-center shrink-0">
                                <?= $i + 1 ?>
                            </span>
                            <div class="min-w-0">
                                <h3 class="text-sm font-semibold text-slate-900"><?= e($t) ?></h3>
                                <p class="text-sm text-slate-600 mt-0.5 leading-relaxed"><?= e($d) ?></p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </section>

        </main>


        <!-- Kolom kanan -->
        <aside class="space-y-4">

            <!-- Verifikasi -->
            <div class="rounded-2xl border border-blue-100 bg-blue-50/60 p-5">
                <div class="flex gap-3">
                    <span class="text-blue-600 shrink-0"><?= icon('shield', 'w-8 h-8') ?></span>
                    <div>
                        <h3 class="font-semibold text-sm text-slate-900">Mitra Terverifikasi</h3>
                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                            Telah diperiksa Koordinator Magang POLINEMA pada <?= e($m['verifikasi']) ?>.
                        </p>
                    </div>
                </div>

                <ul class="mt-4 space-y-2.5">
                    <?= $cek(true, 'Tersedia supervisor atau pembimbing lapangan') ?>
                    <?= $cek(true, 'Deskripsi pekerjaan jelas dan sesuai capaian pembelajaran') ?>
                    <?= $cek(true, 'Skala dan kualifikasi perusahaan telah ditinjau') ?>
                </ul>
            </div>


            <!-- Kontak -->
            <section id="kontak" class="<?= $card ?>">
                <?php $judul('mail', 'Kontak Perusahaan'); ?>

                <ul class="space-y-4 text-sm text-slate-700">

                    <li class="flex gap-3">
                        <span class="text-slate-800 shrink-0 mt-0.5"><?= icon('pin', 'w-5 h-5') ?></span>
                        <span class="min-w-0"><?= e($m['alamat'] ?? $m['lokasi']) ?></span>
                    </li>

                    <?php if (!empty($m['email'])): ?>
                        <li class="flex gap-3 items-center min-w-0">
                            <span class="text-slate-800 shrink-0"><?= icon('mail', 'w-5 h-5') ?></span>
                            <a href="mailto:<?= e($m['email']) ?>" class="truncate hover:text-blue-600"><?= e($m['email']) ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if (!empty($m['website'])): ?>
                        <li class="flex gap-3 items-center min-w-0">
                            <span class="text-slate-800 shrink-0"><?= icon('globe', 'w-5 h-5') ?></span>
                            <a href="https://<?= e($m['website']) ?>" target="_blank" rel="noopener" class="truncate hover:text-blue-600"><?= e($m['website']) ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if (!empty($m['pic'])): ?>
                        <li class="flex gap-3 items-center">
                            <span class="text-slate-800 shrink-0"><?= icon('user', 'w-5 h-5') ?></span>
                            <span>
                                <?= e($m['pic'][0]) ?>
                                <span class="block text-xs text-slate-500"><?= e($m['pic'][1]) ?></span>
                            </span>
                        </li>
                    <?php endif; ?>

                </ul>
            </section>

            <a
                href="<?= url('/mitra') ?>"
                class="flex items-center justify-center gap-2 py-3 rounded-lg border border-blue-500 bg-white text-blue-600 text-sm font-medium hover:bg-blue-50 transition">
                <?= icon('left', 'w-4 h-4') ?>
                Kembali ke Daftar Mitra
            </a>

        </aside>

    </div>

</div>