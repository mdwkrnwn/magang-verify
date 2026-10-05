<?php

// Butuh: $m (satu mitra, termasuk 'formasi')

$inisial = strtoupper(
    substr(
        preg_replace(
            '/^(PT|CV)\.?\s+/i',
            '',
            $m['nama']
        ),
        0,
        2
    )
);

$card = 'bg-white rounded-2xl border border-slate-100 shadow-sm p-5 md:p-6 scroll-mt-24';

$judul = function ($ic, $t) {
    echo '<h2 class="flex items-center gap-3 font-semibold text-slate-900 mb-4">'
        . '<span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 grid place-items-center">'
        . icon($ic, 'w-4 h-4')
        . '</span>'
        . e($t)
        . '</h2>';
};

$cek = fn($ok, $teks) =>
    '<li class="flex gap-2.5 text-sm text-slate-700">'
    . '<span class="mt-0.5 shrink-0 '
    . ($ok ? 'text-green-600' : 'text-slate-300')
    . '">'
    . '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">'
    . '<path d="m4.5 12.75 6 6 9-13.5"/>'
    . '</svg>'
    . '</span>'
    . e($teks)
    . '</li>';

$alur = [
    [
        'Pilih formasi',
        'Pilih formasi yang sesuai dengan prodi dan minat Anda dari halaman ini.'
    ],
    [
        'Lengkapi dokumen',
        'Unggah proposal pengajuan dan fakta integritas melalui sistem.'
    ],
    [
        'Diperiksa kampus',
        'Koordinator Magang memeriksa kelengkapan berkas dan kesesuaian dengan capaian pembelajaran.'
    ],
    [
        'Diproses perusahaan',
        'Surat pengantar dikirim ke perusahaan. Keputusan penerimaan berada di pihak perusahaan, di luar sistem.'
    ],
    [
        'Pantau status dan LOA',
        'Unggah LOA bila diterima, lalu isi logbook selama magang.'
    ],
];

?>

<div class="max-w-7xl mx-auto px-4 sm:px-6">

    <?php include __DIR__ . '/Breadcrumb.php'; ?>

    <?php include __DIR__ . '/Hero.php'; ?>

    <div
        id="profil-content-area"
        class="relative mt-6 grid gap-6 lg:grid-cols-[210px_1fr_290px] items-start"
    >

        <?php include __DIR__ . '/Sidebar.php'; ?>

        <main class="space-y-4 min-w-0">

            <?php include __DIR__ . '/Tentang.php'; ?>

            <?php include __DIR__ . '/Formasi.php'; ?>

            <?php include __DIR__ . '/Alur.php'; ?>

        </main>

        <?php include __DIR__ . '/Kontak.php'; ?>

    </div>

</div>