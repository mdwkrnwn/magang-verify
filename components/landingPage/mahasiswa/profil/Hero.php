<?php
/**
 * @var array<string, mixed> $m
 */
?>

<section
    id="profil"
    class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-100/70 via-blue-50 to-white border border-blue-100 p-5 sm:p-6 md:p-8 scroll-mt-24">

    <img
        src="<?= url('/assets/images/polinema.png') ?>"
        alt=""
        class="hidden lg:block absolute right-0 top-0 h-full w-1/3 object-cover opacity-70 [mask-image:linear-gradient(to_right,transparent,black)]">

    <div class="relative flex flex-col md:flex-row items-center md:items-start gap-5 sm:gap-6 md:gap-10">

        <?php
        $fotoMahasiswa = trim((string) foto($m));
        ?>

        <?php if ($fotoMahasiswa !== ''): ?>

            <img
                src="<?= e($fotoMahasiswa) ?>"
                alt="Foto <?= e($m['nama']) ?>"
                class="w-28 h-28 sm:w-36 sm:h-36 md:w-44 md:h-44 rounded-full object-cover border-4 border-white shadow bg-blue-100 shrink-0"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

            <div
                class="hidden w-28 h-28 sm:w-36 sm:h-36 md:w-44 md:h-44 rounded-full border-4 border-white shadow bg-blue-100 text-blue-600 items-center justify-center shrink-0 font-bold text-3xl sm:text-4xl md:text-5xl"
                aria-label="Inisial <?= e($m['nama']) ?>">
                <?= e(initials($m['nama'])) ?>
            </div>

        <?php else: ?>

            <div
                class="w-28 h-28 sm:w-36 sm:h-36 md:w-44 md:h-44 rounded-full border-4 border-white shadow bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 font-bold text-3xl sm:text-4xl md:text-5xl"
                aria-label="Inisial <?= e($m['nama']) ?>">
                <?= e(initials($m['nama'])) ?>
            </div>

        <?php endif; ?>

        <div class="min-w-0 w-full text-center md:text-left">

            <h1 class="flex items-center justify-center md:justify-start gap-2 text-xl sm:text-2xl md:text-3xl font-bold text-slate-900">

                <span class="break-words">
                    <?= e($m['nama']) ?>
                </span>

                <span
                    class="text-blue-600 shrink-0"
                    title="Terverifikasi">
                    <?= icon('shield', 'w-5 h-5 sm:w-6 sm:h-6') ?>
                </span>

            </h1>

            <p class="mt-1 text-sm sm:text-base text-slate-700">
                <?= e($m['prodi']) ?>
                <br>
                Politeknik Negeri Malang
            </p>

            <p class="mt-3 text-sm text-slate-600 leading-relaxed max-w-xl mx-auto md:mx-0">
                <?= e($m['bio'] ?? '') ?>
            </p>

            <div class="flex flex-wrap justify-center md:justify-start gap-2 mt-4">

                <?php foreach ($m['skills'] as $s): ?>

                    <span class="px-3 py-1 rounded-full bg-white/80 border border-blue-100 text-xs font-medium text-slate-700">
                        <?= e($s) ?>
                    </span>

                <?php endforeach; ?>

            </div>

        </div>

        <?php if (!empty($m['motto'])): ?>

            <p class="hidden lg:block absolute right-8 top-2 text-blue-700 italic text-sm -rotate-6">
                “<?= e($m['motto']) ?>”
            </p>

        <?php endif; ?>

    </div>

</section>