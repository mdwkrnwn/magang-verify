<?php
/** @var array<int, array<string, mixed>> $tampil */
?>
<div class="mt-4 grid gap-4 sm:gap-5 sm:grid-cols-2 xl:grid-cols-4">
    <?php foreach ($tampil as $m): ?>
        <?php $extra = max(0, count($m['skills']) - 2); ?>

        <article class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition p-4 sm:p-5 flex flex-col min-h-[290px]">

            <!-- Identitas -->
            <div class="flex items-center gap-3 sm:gap-4 min-h-[64px]">

                <?php $fotoMahasiswa = foto($m); ?>

                <?php if (!empty($fotoMahasiswa)): ?>

                    <img
                        src="<?= e($fotoMahasiswa) ?>"
                        alt="Foto <?= e($m['nama']) ?>"
                        class="w-14 h-14 sm:w-16 sm:h-16 rounded-full object-cover bg-blue-50 shrink-0">

                <?php else: ?>

                    <div
                        class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-semibold text-lg shrink-0"
                        aria-label="Inisial <?= e($m['nama']) ?>">
                        <?= e(initials($m['nama'])) ?>
                    </div>

                <?php endif; ?>

                <div class="min-w-0 flex-1">

                    <h3 class="font-semibold text-slate-900 truncate">
                        <?= e($m['nama']) ?>
                    </h3>

                    <p class="text-xs text-slate-500 mt-1 truncate">
                        <?= e($m['prodi']) ?>
                    </p>

                </div>

            </div>

            <!-- Keahlian -->
            <div class="mt-4 min-h-[28px] flex flex-wrap items-start gap-2">
                <?php foreach (array_slice($m['skills'], 0, 2) as $s): ?>
                    <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs whitespace-nowrap">
                        <?= e($s) ?>
                    </span>
                <?php endforeach; ?>

                <?php if ($extra > 0): ?>
                    <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs whitespace-nowrap">
                        +<?= $extra ?>
                    </span>
                <?php endif; ?>
            </div>

            <!-- Spacer -->
            <div class="flex-1"></div>

            <!-- Statistik -->
            <div class="grid grid-cols-3 border-t border-slate-100 border-b py-4 mt-5">

                <!-- Proyek -->
                <div class="flex flex-col items-center justify-center border-r border-slate-100">

                    <span class="text-blue-600 mb-1">
                        <?= icon('briefcase', 'w-4 h-4') ?>
                    </span>

                    <span class="text-sm font-semibold text-slate-900 leading-none">
                        <?= $m['proyek'] ?>
                    </span>

                    <span class="text-[11px] text-slate-500 mt-1">
                        Proyek
                    </span>

                </div>

                <!-- Sertifikat -->
                <div class="flex flex-col items-center justify-center border-r border-slate-100">
                    <span class="text-blue-600 mb-1">
                        <?= icon('award', 'w-4 h-4') ?>
                    </span>
                    <span class="text-sm font-semibold text-slate-900 leading-none">
                        <?= $m['sertifikat'] ?>
                    </span>

                    <span class="text-[11px] text-slate-500 mt-1">
                        Sertifikat
                    </span>
                </div>

                <!-- Profil -->
                <div class="flex flex-col items-center justify-center">
                    <span class="text-blue-600 mb-1">
                        <?= icon('user', 'w-4 h-4') ?>
                    </span>
                    <span class="text-[11px] text-slate-500 text-center leading-4">
                        Mahasiswa
                    </span>
                </div>
            </div>

            <!-- Tombol -->
            <div class="mt-4">
                <a
                    href="<?= url('/mahasiswa/profil/' . e($m['slug'])) ?>"
                    class="w-full inline-flex items-center justify-center gap-2 py-2.5 rounded-lg
                    border border-blue-500 bg-blue-50/60 text-blue-600 text-sm font-medium
                    hover:bg-blue-100 transition">
                    Lihat Profil
                    <?= icon('arrow', 'w-4 h-4') ?>
                </a>
            </div>
        </article>
    <?php endforeach; ?>
</div>