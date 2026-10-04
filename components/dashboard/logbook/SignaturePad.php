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
            <canvas id="signature-canvas" width="900" height="280" class="block w-full h-44 touch-none cursor-crosshair"></canvas>
        </div>

        <div class="flex flex-col gap-2 sm:flex-row sm:justify-end">
            <button type="button" id="signature-clear" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100">Bersihkan</button>
            <button type="submit" id="signature-submit" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"><?= e($signatureButtonLabel) ?></button>
        </div>
    </form>
</div>

<script>
(() => {
    const canvas = document.getElementById('signature-canvas');
    const form = document.getElementById('signature-form');
    const input = document.getElementById('signature-data');
    const clear = document.getElementById('signature-clear');
    if (!canvas || !form || !input || !clear) return;

    const ctx = canvas.getContext('2d');
    let drawing = false;
    let hasInk = false;

    ctx.lineWidth = 3;
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';
    ctx.strokeStyle = '#111827';

    function point(event) {
        const rect = canvas.getBoundingClientRect();
        const source = event.touches?.[0] ?? event;
        return {
            x: (source.clientX - rect.left) * (canvas.width / rect.width),
            y: (source.clientY - rect.top) * (canvas.height / rect.height),
        };
    }

    function start(event) {
        event.preventDefault();
        drawing = true;
        const p = point(event);
        ctx.beginPath();
        ctx.moveTo(p.x, p.y);
    }

    function move(event) {
        if (!drawing) return;
        event.preventDefault();
        const p = point(event);
        ctx.lineTo(p.x, p.y);
        ctx.stroke();
        hasInk = true;
    }

    function end(event) {
        if (!drawing) return;
        event?.preventDefault();
        drawing = false;
        ctx.closePath();
    }

    canvas.addEventListener('mousedown', start);
    canvas.addEventListener('mousemove', move);
    window.addEventListener('mouseup', end);
    canvas.addEventListener('touchstart', start, { passive: false });
    canvas.addEventListener('touchmove', move, { passive: false });
    canvas.addEventListener('touchend', end, { passive: false });

    clear.addEventListener('click', () => {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        hasInk = false;
        input.value = '';
    });

    form.addEventListener('submit', (event) => {
        if (!hasInk) {
            event.preventDefault();
            alert('Silakan buat tanda tangan terlebih dahulu.');
            return;
        }
        input.value = canvas.toDataURL('image/png');
    });
})();
</script>
