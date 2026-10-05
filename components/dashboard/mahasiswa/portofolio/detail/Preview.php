<?php
$gambar = trim($portofolioDetail['gambar'] ?? '');
$gambarUrl = $gambar !== ''
    ? url('/' . ltrim($gambar, '/'))
    : '';
?>

<div class="bg-white border border-slate-200 rounded-xl p-5 sm:p-6">
    <div class="mb-5">
        <h2 class="text-base font-bold text-gray-900">Preview Portofolio</h2>
        <p class="mt-1 text-sm text-gray-500">
            Gambar atau dokumentasi proyek yang dikerjakan.
        </p>
    </div>

    <?php if ($gambar !== ''): ?>
        <button
            type="button"
            onclick="bukaModalGambar()"
            class="group block w-full overflow-hidden rounded-lg border border-slate-200 bg-slate-50 cursor-zoom-in"
            aria-label="Perbesar gambar portofolio">
            <img
                src="<?= e($gambarUrl) ?>"
                alt="<?= e('Preview portofolio ' . ($portofolioDetail['judul'] ?? '')) ?>"
                class="w-full max-h-[480px] object-contain transition duration-300 group-hover:scale-[1.02]"
                loading="lazy">
        </button>

        <p class="mt-3 text-xs text-gray-400">
            Klik gambar untuk melihat ukuran penuh.
        </p>
    <?php else: ?>
        <div class="flex min-h-52 flex-col items-center justify-center rounded-lg border border-dashed border-slate-300 bg-slate-50 px-4 py-8 text-center">
            <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.8">
                    <rect x="3" y="3" width="18" height="18" rx="2" />
                    <circle cx="8.5" cy="8.5" r="1.5" />
                    <path d="m21 15-5-5L5 21" />
                </svg>
            </div>

            <p class="text-sm font-semibold text-gray-700">
                Belum ada gambar portofolio
            </p>
            <p class="mt-1 max-w-sm text-xs leading-5 text-gray-500">
                Tambahkan gambar melalui halaman edit portofolio agar dokumentasi proyek dapat ditampilkan di sini.
            </p>
        </div>
    <?php endif; ?>
</div>