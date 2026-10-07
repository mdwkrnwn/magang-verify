(() => {
    const canvas = document.getElementById('signatureCanvas');
    const output = document.getElementById('signatureData');
    const clear = document.getElementById('clearSignature');
    if (!canvas || !output) return;

    const ctx = canvas.getContext('2d');
    let drawing = false;
    let hasInk = false;

    const resize = () => {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        const rect = canvas.getBoundingClientRect();
        canvas.width = rect.width * ratio;
        canvas.height = rect.height * ratio;
        ctx.scale(ratio, ratio);
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
    };
    resize();
    window.addEventListener('resize', resize);

    const point = (event) => {
        const rect = canvas.getBoundingClientRect();
        const touch = event.touches?.[0] || event.changedTouches?.[0];
        const clientX = touch ? touch.clientX : event.clientX;
        const clientY = touch ? touch.clientY : event.clientY;
        return { x: clientX - rect.left, y: clientY - rect.top };
    };

    const start = (event) => {
        event.preventDefault();
        drawing = true;
        const p = point(event);
        ctx.beginPath();
        ctx.moveTo(p.x, p.y);
    };
    const move = (event) => {
        if (!drawing) return;
        event.preventDefault();
        const p = point(event);
        ctx.lineTo(p.x, p.y);
        ctx.stroke();
        hasInk = true;
    };
    const end = () => { drawing = false; };

    canvas.addEventListener('mousedown', start);
    canvas.addEventListener('mousemove', move);
    canvas.addEventListener('mouseup', end);
    canvas.addEventListener('mouseleave', end);
    canvas.addEventListener('touchstart', start, { passive: false });
    canvas.addEventListener('touchmove', move, { passive: false });
    canvas.addEventListener('touchend', end);

    clear?.addEventListener('click', () => {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        hasInk = false;
        output.value = '';
    });

    canvas.closest('form')?.addEventListener('submit', (event) => {
        if (!hasInk) {
            event.preventDefault();
            alert('Silakan buat tanda tangan terlebih dahulu.');
            return;
        }
        output.value = canvas.toDataURL('image/png');
    });
})();
