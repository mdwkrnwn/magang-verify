(() => {
    const canvas = document.getElementById('signature-canvas');
    const form = document.getElementById('signature-form');
    const output = document.getElementById('signature-data');
    const clear = document.getElementById('signature-clear');

    if (!canvas || !form || !output || !clear) return;

    const ctx = canvas.getContext('2d');
    let drawing = false;
    let hasInk = false;

    const resize = () => {
        const previous = canvas.toDataURL('image/png');
        const rect = canvas.getBoundingClientRect();
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = Math.max(1, Math.round(rect.width * ratio));
        canvas.height = Math.max(1, Math.round(rect.height * ratio));
        ctx.setTransform(ratio, 0, 0, ratio, 0, 0);
        ctx.lineWidth = 3;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
        ctx.strokeStyle = '#111827';

        // Jangan memulihkan canvas kosong sebagai tanda tangan.
        if (previous && previous !== 'data:,') {
            const image = new Image();
            image.onload = () => ctx.drawImage(image, 0, 0, rect.width, rect.height);
            image.src = previous;
        }
    };

    const point = (event) => {
        const rect = canvas.getBoundingClientRect();
        const source = event.touches?.[0] ?? event;
        return {
            x: (source.clientX - rect.left) * (canvas.width / rect.width),
            y: (source.clientY - rect.top) * (canvas.height / rect.height),
        };
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

    const end = (event) => {
        if (!drawing) return;
        event?.preventDefault();
        drawing = false;
        ctx.closePath();
    };

    resize();
    window.addEventListener('resize', resize);
    canvas.addEventListener('mousedown', start);
    canvas.addEventListener('mousemove', move);
    window.addEventListener('mouseup', end);
    canvas.addEventListener('touchstart', start, { passive: false });
    canvas.addEventListener('touchmove', move, { passive: false });
    canvas.addEventListener('touchend', end, { passive: false });

    clear.addEventListener('click', () => {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        hasInk = false;
        output.value = '';
    });

    form.addEventListener('submit', (event) => {
        if (!hasInk) {
            event.preventDefault();
            alert('Silakan buat tanda tangan terlebih dahulu.');
            return;
        }
        output.value = canvas.toDataURL('image/png');
    });
})();
