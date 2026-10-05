<?php if (!$tampil): ?>

<div class="rounded-2xl border border-dashed border-slate-200 py-16 px-6 text-center text-sm text-slate-500">
    Tidak ada mitra yang cocok dengan filter ini.
    <a href="<?= url('/mitra') ?>" class="text-blue-600 font-medium hover:underline">
        Reset filter
    </a>
</div>

<?php else: ?>

<div class="grid gap-4 sm:gap-5 sm:grid-cols-2 xl:grid-cols-3">

<?php foreach ($tampil as $m): ?>
    <?php
    $inisial = strtoupper(substr(preg_replace('/^(PT|CV)\.?\s+/i', '', $m['nama']), 0, 2));
    ?>

    <article class="relative bg-white rounded-2xl border border-slate-100 shadow-sm
       hover:-translate-y-1 hover:border-blue-200 hover:bg-blue-50/20 hover:shadow-lg
       transition-all duration-200 ease-out
       p-4 sm:p-5 flex flex-col">
        <!-- Status -->
        <div class="flex items-center gap-2 text-xs font-medium text-slate-700">
            <span class="w-6 h-6 rounded-full bg-blue-600 text-white grid place-items-center shrink-0">
                <?= icon('shield', 'w-3.5 h-3.5') ?>
            </span>
            <?= e($m['status']) ?>
        </div>

        <!-- Logo -->
        <div class="h-28 flex items-center justify-center my-4">
            <?php if (!empty($m['logo'])): ?>
                <img
                    src="<?= url('/assets/images/mitra/' . e($m['logo'])) ?>"
                    alt="Logo <?= e($m['nama']) ?>"
                    class="max-h-16 max-w-[70%] w-auto object-contain"
                >
            <?php else: ?>
                <span class="w-20 h-20 rounded-2xl bg-blue-50 text-blue-600 text-2xl font-bold grid place-items-center">
                    <?= e($inisial) ?>
                </span>
            <?php endif; ?>
        </div>

        <!-- Identitas -->
        <h3 class="font-semibold text-sm text-slate-900">
            <a
                href="<?= url('/mitra/detail/' . e($m['slug'])) ?>"
                class="after:absolute after:inset-0 after:rounded-2xl focus:outline-none
                focus-visible:after:ring-2 focus-visible:after:ring-blue-500"
            >
                <?= e($m['nama']) ?>
            </a>
        </h3>

        <p class="text-sm text-slate-500 mt-1 leading-relaxed line-clamp-3">
            <?= e($m['deskripsi']) ?>
        </p>

        <!-- Bidang -->
        <div class="flex flex-wrap gap-2 mt-3">
            <?php foreach ($m['bidang'] as $b): ?>
                <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs">
                    <?= e($b) ?>
                </span>
            <?php endforeach; ?>
        </div>

        <div class="flex-1"></div>

        <!-- Info -->
        <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between gap-3 text-xs text-slate-600">

            <span class="inline-flex items-center gap-1.5 min-w-0">
                <span class="text-blue-600 shrink-0"><?= icon('pin', 'w-4 h-4') ?></span>
                <span class="truncate"><?= e($m['lokasi']) ?></span>
            </span>

            <span class="inline-flex items-center gap-1.5 shrink-0">
                <span class="text-blue-600"><?= icon('briefcase', 'w-4 h-4') ?></span>
                <?= (int)$m['posisi'] ?> posisi dibuka
            </span>

        </div>

    </article>

<?php endforeach; ?>

</div>

<?php endif; ?>