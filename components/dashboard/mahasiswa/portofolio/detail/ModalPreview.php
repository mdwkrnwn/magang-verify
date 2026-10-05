<?php if ($gambar !== ''): ?>
    <!-- Modal gambar -->
    <div
        id="modalGambar"
        class="fixed left-0 top-0 z-[9999] hidden h-[100dvh] w-screen items-center justify-center overflow-y-auto bg-black/80 p-4 sm:p-6"
        role="dialog"
        aria-modal="true"
        aria-label="Preview gambar portofolio"
        onclick="tutupModalGambar()">
        <button
            type="button"
            onclick="tutupModalGambar()"
            class="absolute right-4 top-4 flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-2xl text-white transition hover:bg-white/20"
            aria-label="Tutup preview">
            &times;
        </button>

        <img
            src="<?= e($gambarUrl) ?>"
            alt="<?= e('Gambar portofolio ' . ($portofolioDetail['judul'] ?? '')) ?>"
            class="max-h-[90vh] max-w-full rounded-lg object-contain shadow-2xl"
            onclick="event.stopPropagation()">
    </div>

    <script>
        const modalGambar = document.getElementById('modalGambar');

        function bukaModalGambar() {
            modalGambar.classList.remove('hidden');
            modalGambar.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function tutupModalGambar() {
            modalGambar.classList.add('hidden');
            modalGambar.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && !modalGambar.classList.contains('hidden')) {
                tutupModalGambar();
            }
        });
    </script>
<?php endif; ?>