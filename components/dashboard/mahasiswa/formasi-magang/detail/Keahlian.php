<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <section class="p-4 sm:p-5 bg-white border border-slate-200 rounded-xl">
        <h2 class="text-sm sm:text-base font-semibold text-slate-900">Bidang Magang</h2>
        <p class="mt-2 text-sm text-slate-600"><?= e($formasi['bidang'] ?: 'Bidang belum ditentukan oleh mitra.') ?></p>
    </section>
    <section class="p-4 sm:p-5 bg-white border border-slate-200 rounded-xl">
        <h2 class="text-sm sm:text-base font-semibold text-slate-900">Informasi Mitra</h2>
        <p class="mt-2 text-sm text-slate-600"><?= e($formasi['kategori_mitra'] ?? '-') ?></p>
        <?php if (!empty($formasi['website_mitra'])): ?>
            <a class="inline-block mt-2 text-sm text-blue-600 hover:underline" href="<?= e($formasi['website_mitra']) ?>" target="_blank" rel="noopener noreferrer">Kunjungi situs mitra</a>
        <?php endif; ?>
    </section>
</div>
