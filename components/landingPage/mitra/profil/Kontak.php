<aside class="space-y-4">

    <!-- Verifikasi -->
    <div class="rounded-2xl border border-blue-100 bg-blue-50/60 p-5">

        <div class="flex gap-3">

            <span class="text-blue-600 shrink-0">
                <?= icon('shield', 'w-8 h-8') ?>
            </span>


            <div>

                <h3 class="font-semibold text-sm text-slate-900">
                    Mitra Terverifikasi
                </h3>

                <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                    Telah diperiksa Koordinator Magang POLINEMA pada
                    <?= e($m['verifikasi']) ?>.
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

                <span class="text-slate-800 shrink-0 mt-0.5">
                    <?= icon('pin', 'w-5 h-5') ?>
                </span>

                <span class="min-w-0">
                    <?= e($m['alamat'] ?? $m['lokasi']) ?>
                </span>

            </li>


            <?php if (!empty($m['email'])): ?>

                <li class="flex gap-3 items-center min-w-0">

                    <span class="text-slate-800 shrink-0">
                        <?= icon('mail', 'w-5 h-5') ?>
                    </span>

                    <a
                        href="mailto:<?= e($m['email']) ?>"
                        class="truncate hover:text-blue-600"
                    >
                        <?= e($m['email']) ?>
                    </a>

                </li>

            <?php endif; ?>


            <?php if (!empty($m['website'])): ?>

                <li class="flex gap-3 items-center min-w-0">

                    <span class="text-slate-800 shrink-0">
                        <?= icon('globe', 'w-5 h-5') ?>
                    </span>

                    <a
                        href="https://<?= e($m['website']) ?>"
                        target="_blank"
                        rel="noopener"
                        class="truncate hover:text-blue-600"
                    >
                        <?= e($m['website']) ?>
                    </a>

                </li>

            <?php endif; ?>


            <?php if (!empty($m['pic'])): ?>

                <li class="flex gap-3 items-center">

                    <span class="text-slate-800 shrink-0">
                        <?= icon('user', 'w-5 h-5') ?>
                    </span>

                    <span>

                        <?= e($m['pic'][0]) ?>

                        <span class="block text-xs text-slate-500">
                            <?= e($m['pic'][1]) ?>
                        </span>

                    </span>

                </li>

            <?php endif; ?>

        </ul>

    </section>


    <!-- Kembali -->
    <a
        href="<?= url('/mitra') ?>"
        class="flex items-center justify-center gap-2 py-3 rounded-lg border border-blue-500 bg-white text-blue-600 text-sm font-medium hover:bg-blue-50 transition"
    >
        <?= icon('left', 'w-4 h-4') ?>

        Kembali ke Daftar Mitra
    </a>

</aside>