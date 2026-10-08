<?php
/** @var string $signatureAction */
/** @var string $signatureButtonLabel */
/** @var string $signatureStageLabel */
$signatureAction = $signatureAction ?? '#';
$signatureButtonLabel = $signatureButtonLabel ?? 'Tanda Tangani';
$signatureStageLabel = $signatureStageLabel ?? 'Tanda Tangan Digital';
?>
<div class="p-5 bg-white border border-gray-200 shadow-sm rounded-2xl">
    <div class="mb-4">
        <h3 class="text-base font-semibold text-gray-900"><?= e($signatureStageLabel) ?></h3>
        <p class="mt-1 text-xs leading-5 text-gray-500">Buat tanda tangan langsung di platform. Data tanda tangan akan dikaitkan dengan versi logbook yang sedang disahkan.</p>
    </div>

    <form method="POST" action="<?= e($signatureAction) ?>" id="signature-form" class="space-y-4">
        <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
        <input type="hidden" name="signature_data" id="signature-data">

        <div class="overflow-hidden bg-white border border-dashed border-gray-300 rounded-xl">
            <div class="px-3 py-2 text-[11px] text-gray-400 border-b border-gray-100">Gambar tanda tangan di area berikut menggunakan mouse, touchpad, atau layar sentuh.</div>
            <canvas id="signature-canvas" width="900" height="280" class="block w-full h-44 touch-none cursor-crosshair"></canvas>
        </div>

        <div class="flex flex-col gap-2 sm:flex-row sm:justify-end">
            <button type="button" id="signature-clear" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100">Bersihkan</button>
            <button type="submit" id="signature-submit" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"><?= e($signatureButtonLabel) ?></button>
        </div>
    </form>
</div>

<script src="<?= e(url('/assets/js/logbook-signature.js')) ?>"></script>
