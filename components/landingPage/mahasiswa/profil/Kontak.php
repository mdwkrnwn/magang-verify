<?php

$kontak = $m['kontak'] ?? [];

$k = [
    ['code', $kontak['github'] ?? null],
    ['briefcase', $kontak['linkedin'] ?? null],
    ['globe', $kontak['website'] ?? null],
    ['mail', $kontak['email'] ?? null],
];
?>

<aside class="space-y-4 min-w-0">

    <div class="rounded-2xl border border-blue-100 bg-blue-50/60 p-4 sm:p-5 flex gap-4">

        <span class="text-blue-600 shrink-0">
            <?= icon('shield', 'w-8 h-8 sm:w-9 sm:h-9') ?>
        </span>

        <div class="min-w-0">

            <h3 class="font-semibold text-sm text-slate-900">
                Profil Terverifikasi
            </h3>

            <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                Data mahasiswa ini telah diverifikasi oleh pihak kampus.
            </p>

        </div>

    </div>


    <section
        id="kontak"
        class="<?= $card ?>"
    >

        <?php judul('link', 'Kontak & Tautan'); ?>


        <ul class="space-y-4 text-sm text-slate-700">

            <?php foreach ($k as [$ic, $v]): ?>

                <?php if (!$v) continue; ?>

                <li class="flex items-center gap-3 min-w-0">

                    <span class="text-slate-800 shrink-0">
                        <?= icon($ic, 'w-5 h-5') ?>
                    </span>

                    <span class="truncate">
                        <?= e($v) ?>
                    </span>

                </li>

            <?php endforeach; ?>


            <?php if (!array_filter(array_column($k, 1))): ?>

                <li class="text-slate-500">
                    Belum ada kontak.
                </li>

            <?php endif; ?>

        </ul>

    </section>


    <a
        href="#"
        class="flex items-center justify-center gap-2 py-3 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition"
    >
        Lihat Portofolio Lengkap
        <?= icon('external', 'w-4 h-4') ?>
    </a>


    <?php if (!empty($m['cv_path'])): ?>
        <a
            href="<?= e(url('/mahasiswa/profil/' . ($m['slug'] ?? '') . '/cv?download=1')) ?>"
            class="flex items-center justify-center gap-2 py-3 rounded-lg border border-blue-500 bg-white text-blue-600 text-sm font-medium hover:bg-blue-50 transition"
        >
            <?= icon('download', 'w-4 h-4') ?>
            Unduh CV
        </a>
    <?php endif; ?>

</aside>