<?php
$cvPath = trim((string) ($profil['cv_path'] ?? ''));
$cvNama = trim((string) ($profil['cv_nama_asli'] ?? ''));
$cvUkuran = (int) ($profil['cv_ukuran_bytes'] ?? 0);
$cvUpdated = $profil['cv_updated_at'] ?? null;

$cvSlug = slugify($profil['nama_lengkap'] ?? '');
$cvUrl = $cvSlug !== ''
    ? url('/mahasiswa/profil/' . $cvSlug . '/cv')
    : '#';

$formatUkuran = static function (int $bytes): string {
    if ($bytes <= 0) {
        return '-';
    }

    if ($bytes >= 1024 * 1024) {
        return number_format($bytes / (1024 * 1024), 2, ',', '.') . ' MB';
    }

    return number_format($bytes / 1024, 0, ',', '.') . ' KB';
};
?>

<div class="mt-5 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6 lg:p-7">
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <div class="flex items-center gap-2">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                            d="M7 3.75h6l4 4v12.5H7a2 2 0 0 1-2-2v-12.5a2 2 0 0 1 2-2Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                            d="M13 3.75v4h4M8.5 13h7M8.5 16.5h7" />
                    </svg>
                </span>
                <h2 class="text-lg font-bold text-gray-900">CV Saya</h2>
            </div>

            <p class="mt-2 text-sm leading-6 text-gray-500">
                CV ini digunakan pada profil publik Anda agar dapat dilihat oleh mitra.
            </p>
        </div>

        <?php if ($cvPath !== ''): ?>
            <span class="shrink-0 rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                Tersedia
            </span>
        <?php endif; ?>
    </div>

    <?php if ($cvPath !== ''): ?>
        <div class="mt-5 rounded-xl border border-gray-100 bg-gray-50 p-4">
            <div class="flex items-start gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                            d="M7 3.75h6l4 4v12.5H7a2 2 0 0 1-2-2v-12.5a2 2 0 0 1 2-2Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                            d="M13 3.75v4h4" />
                    </svg>
                </div>

                <div class="min-w-0 flex-1">
                    <p class="break-words text-sm font-semibold text-gray-800">
                        <?= e($cvNama !== '' ? $cvNama : 'CV Mahasiswa.pdf') ?>
                    </p>
                    <p class="mt-1 text-xs text-gray-500">
                        PDF • <?= e($formatUkuran($cvUkuran)) ?>
                        <?php if (!empty($cvUpdated)): ?>
                            • Diperbarui <?= e(date('d M Y', strtotime($cvUpdated))) ?>
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-1 gap-2 sm:grid-cols-2">
                <a
                    href="<?= e($cvUrl) ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-blue-200 bg-white px-4 py-2.5 text-sm font-semibold text-blue-600 transition hover:bg-blue-50"
                >
                    Lihat CV
                </a>

                <button
                    type="button"
                    data-open-modal="modal-cv"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700"
                >
                    Ganti CV
                </button>
            </div>
        </div>
    <?php else: ?>
        <div class="mt-5 rounded-xl border border-dashed border-gray-200 bg-gray-50 p-5 text-center">
            <p class="text-sm font-medium text-gray-700">
                Belum ada CV yang diunggah.
            </p>
            <p class="mt-1 text-xs leading-5 text-gray-500">
                Upload CV PDF agar dapat ditampilkan pada profil publik Anda.
            </p>

            <button
                type="button"
                data-open-modal="modal-cv"
                class="mt-4 inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700"
            >
                + Upload CV
            </button>
        </div>
    <?php endif; ?>
</div>
