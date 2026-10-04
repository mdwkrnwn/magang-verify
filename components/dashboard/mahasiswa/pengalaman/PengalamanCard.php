<?php

$pengalamanId = (int) ($pengalamanItem['id'] ?? 0);
$jenisItem = trim((string) ($pengalamanItem['jenis'] ?? 'lainnya'));
$posisi = trim((string) ($pengalamanItem['posisi'] ?? '-'));
$instansi = trim((string) ($pengalamanItem['instansi'] ?? '-'));
$lokasi = trim((string) ($pengalamanItem['lokasi'] ?? ''));
$deskripsi = trim((string) ($pengalamanItem['deskripsi'] ?? ''));
$isOtomatis = !empty($pengalamanItem['is_otomatis']);

$tanggalMulai = $pengalamanItem['tanggal_mulai'] ?? null;
$tanggalSelesai = $pengalamanItem['tanggal_selesai'] ?? null;

$formatTanggal = static function ($tanggal): string {
    if (!$tanggal) {
        return '';
    }

    $timestamp = strtotime((string) $tanggal);
    return $timestamp !== false ? date('d M Y', $timestamp) : (string) $tanggal;
};

$tanggalMulaiLabel = $formatTanggal($tanggalMulai);
$tanggalSelesaiLabel = $formatTanggal($tanggalSelesai);

$jenisLabels = [
    'magang' => 'Magang',
    'pekerjaan' => 'Pekerjaan',
    'organisasi' => 'Organisasi',
    'freelance' => 'Freelance',
    'proyek' => 'Proyek',
    'lainnya' => 'Lainnya',
];

$jenisLabel = $jenisLabels[$jenisItem] ?? ucfirst($jenisItem);

?>

<article class="rounded-xl border border-slate-200 bg-white p-4 transition hover:shadow-sm sm:p-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start">

        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-sm font-bold uppercase text-blue-600">
            <?= e(mb_strtoupper(mb_substr($jenisLabel, 0, 2))) ?>
        </div>

        <div class="min-w-0 flex-1">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-medium text-blue-700">
                            <?= e($jenisLabel) ?>
                        </span>

                        <?php if ($isOtomatis): ?>
                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-medium text-emerald-700">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4 4L19 6" />
                                </svg>
                                Dari Sistem
                            </span>
                        <?php endif; ?>
                    </div>

                    <h2 class="mt-2 break-words text-lg font-bold text-gray-900">
                        <?= e($posisi) ?>
                    </h2>

                    <p class="mt-1 break-words text-sm font-medium text-gray-600">
                        <?= e($instansi) ?>
                    </p>
                </div>

                <?php if (!$isOtomatis && $pengalamanId > 0): ?>
                    <div class="flex shrink-0 gap-2">
                        <a
                            href="<?= e(url('/dashboard/mahasiswa/pengalaman/edit/' . $pengalamanId)) ?>"
                            class="inline-flex items-center rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-slate-50">
                            ✏ Edit
                        </a>

                        <form
                            method="POST"
                            action="<?= e(url('/dashboard/mahasiswa/pengalaman/hapus/' . $pengalamanId)) ?>"
                            onsubmit="return confirm('Hapus pengalaman ini?')">
                            <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
                            <button
                                type="button"
                                class="inline-flex items-center rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-red-600 transition hover:bg-slate-50"
                                data-delete-pengalaman
                                data-id="<?= e((string) $item['id']) ?>"
                                data-nama="<?= e($item['posisi']) ?>">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    class="h-4 w-4">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M6 7h12M9 7V5.5A1.5 1.5 0 0 1 10.5 4h3A1.5 1.5 0 0 1 15 5.5V7m-7 0 .75 12h6.5L16 7M10 11v5m4-5v5" />
                                </svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-gray-500">
                <?php if ($tanggalMulaiLabel !== '' || $tanggalSelesaiLabel !== ''): ?>
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                        </svg>
                        <?= e($tanggalMulaiLabel ?: '-') ?>
                        <span>—</span>
                        <?= e($tanggalSelesaiLabel ?: 'Sekarang') ?>
                    </span>
                <?php endif; ?>

                <?php if ($lokasi !== ''): ?>
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 21a2 2 0 01-2.828 0l-4.243-4.343a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="break-words"><?= e($lokasi) ?></span>
                    </span>
                <?php endif; ?>
            </div>

            <?php if ($deskripsi !== ''): ?>
                <p class="mt-3 whitespace-pre-line text-sm leading-6 text-gray-600">
                    <?= e($deskripsi) ?>
                </p>
            <?php endif; ?>
        </div>

    </div>
</article>

<!-- MODAL HAPUS -->

<div
    id="deletePengalamanModal"
    class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-950/50 px-4"
    aria-hidden="true">
    <div
        class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl"
        role="dialog"
        aria-modal="true"
        aria-labelledby="deletePengalamanTitle">
        <div class="mb-5 flex items-start gap-4">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-red-50 text-red-600">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    class="h-5 w-5">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 9v4m0 4h.01M10.3 4.5 3.8 16a2 2 0 0 0 1.74 3h12.92a2 2 0 0 0 1.74-3L13.7 4.5a2 2 0 0 0-3.4 0Z" />
                </svg>
            </div>

            <div>
                <h2
                    id="deletePengalamanTitle"
                    class="text-lg font-semibold text-slate-900">
                    Hapus pengalaman?
                </h2>

                <p class="mt-1 text-sm leading-6 text-slate-500">
                    Pengalaman
                    <span
                        id="deletePengalamanName"
                        class="font-semibold text-slate-700"></span>
                    akan dihapus secara permanen.
                </p>
            </div>
        </div>

        <form
            id="deletePengalamanForm"
            method="POST"
            action="">
            <?= csrfToken() ?>

            <div class="flex justify-end gap-3">
                <button
                    type="button"
                    id="cancelDeletePengalaman"
                    class="rounded-lg border border-slate-200 bg-white px-4 py-2.5
                           text-sm font-medium text-slate-600 transition
                           hover:bg-slate-50">
                    Batal
                </button>

                <button
                    type="submit"
                    class="rounded-lg bg-red-600 px-4 py-2.5
                           text-sm font-medium text-white transition
                           hover:bg-red-700">
                    Hapus Pengalaman
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('deletePengalamanModal');
        const form = document.getElementById('deletePengalamanForm');
        const nameElement = document.getElementById('deletePengalamanName');
        const cancelButton = document.getElementById('cancelDeletePengalaman');

        const deleteButtons = document.querySelectorAll('[data-delete-pengalaman]');

        function openDeleteModal(id, nama) {
            nameElement.textContent = nama;

            form.action =
                '<?= e(url('/dashboard/mahasiswa/pengalaman/hapus')) ?>/' +
                encodeURIComponent(id);

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            modal.setAttribute('aria-hidden', 'false');

            document.body.classList.add('overflow-hidden');

            cancelButton.focus();
        }

        function closeDeleteModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            modal.setAttribute('aria-hidden', 'true');

            document.body.classList.remove('overflow-hidden');

            form.action = '';
            nameElement.textContent = '';
        }

        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                openDeleteModal(
                    this.dataset.id,
                    this.dataset.nama
                );
            });
        });

        cancelButton.addEventListener('click', closeDeleteModal);

        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeDeleteModal();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeDeleteModal();
            }
        });
    });
</script>