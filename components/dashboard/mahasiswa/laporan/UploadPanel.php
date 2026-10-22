<?php
$uploadFormValues = is_array($uploadFormValues ?? null) ? $uploadFormValues : [];
$uploadHeading = (string) ($uploadHeading ?? 'Unggah Laporan');
?>
<section class="mt-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
    <h2 class="font-bold"><?= e($uploadHeading) ?></h2>
    <p class="mt-1 text-sm text-gray-500">Pilih file PDF, maksimal 10 MB. Jika validasi gagal, ringkasan dan catatan teks akan tetap terisi.</p>
    <form action="<?= e($formAction) ?>" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4" onsubmit="const button=this.querySelector('button[type=submit]');if(!button||button.disabled)return false;button.disabled=true;button.setAttribute('aria-busy','true');button.textContent='Mengunggah...';this.setAttribute('aria-busy','true');">
        <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
        <div>
            <label for="laporan-pdf" class="block text-sm font-medium text-gray-700">File laporan PDF</label>
            <input id="laporan-pdf" type="file" name="file_laporan" accept="application/pdf,.pdf" required aria-describedby="laporan-pdf-help" class="block w-full rounded-xl border border-gray-300 px-3 py-2 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-blue-50 file:px-3 file:py-2 file:text-blue-700">
            <p id="laporan-pdf-help" class="mt-1 text-xs text-gray-500">PDF hingga 10 MB. Untuk revisi, pilih versi terbaru berkas.</p>
        </div>
        <div>
            <label for="ringkasan-laporan" class="block text-sm font-medium text-gray-700">Ringkasan / perubahan <span class="font-normal text-gray-400">(opsional)</span></label>
            <textarea id="ringkasan-laporan" name="ringkasan" rows="3" maxlength="5000" placeholder="Ringkasan laporan atau perubahan yang dilakukan." class="mt-1 w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"><?= e((string) ($uploadFormValues['ringkasan'] ?? '')) ?></textarea>
        </div>
        <?php if (!empty($hasRevisionContext)): ?>
            <div>
                <label for="catatan-revisi" class="block text-sm font-medium text-gray-700">Catatan revisi <span class="font-normal text-gray-400">(opsional)</span></label>
                <textarea id="catatan-revisi" name="catatan_revisi" rows="3" maxlength="5000" placeholder="Catatan untuk menjelaskan revisi." class="mt-1 w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"><?= e((string) ($uploadFormValues['catatan_revisi'] ?? '')) ?></textarea>
            </div>
        <?php endif; ?>
        <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Unggah dan Ajukan</button>
    </form>
</section>
