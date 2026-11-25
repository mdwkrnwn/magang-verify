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

// =====================================================
// FORMAT TANGGAL
// =====================================================

$formatTanggal = static function ($tanggal): string {

    if (!$tanggal) {
        return '';
    }

    $timestamp = strtotime((string) $tanggal);

    return $timestamp !== false
        ? date('d M Y', $timestamp)
        : (string) $tanggal;
};

$tanggalMulaiLabel = $formatTanggal($tanggalMulai);
$tanggalSelesaiLabel = $formatTanggal($tanggalSelesai);

// =====================================================
// LABEL JENIS PENGALAMAN
// =====================================================

$jenisLabels = [
    'magang'     => 'Magang',
    'pekerjaan'  => 'Pekerjaan',
    'organisasi' => 'Organisasi',
    'freelance'  => 'Freelance',
    'proyek'     => 'Proyek',
    'lainnya'    => 'Lainnya',
];

$jenisLabel = $jenisLabels[$jenisItem] ?? ucfirst($jenisItem);

// =====================================================
// ID MODAL UNIK
// =====================================================

$modalId = 'deletePengalamanModal-' . $pengalamanId;

?>

<!-- =====================================================
     CARD PENGALAMAN
===================================================== -->

<article class="rounded-xl border border-slate-200 bg-white
                p-4 transition duration-200
                hover:border-blue-200 hover:shadow-md sm:p-5">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-start">

        <!-- ICON -->
        <div class="flex h-12 w-12 shrink-0 items-center
                    justify-center rounded-xl bg-blue-50
                    text-sm font-bold uppercase text-blue-600">

            <?= e(mb_strtoupper(mb_substr($jenisLabel, 0, 2))) ?>

        </div>

        <!-- KONTEN -->
        <div class="min-w-0 flex-1">

            <div class="flex flex-col gap-3
                        sm:flex-row sm:items-start
                        sm:justify-between">

                <div class="min-w-0">

                    <!-- BADGE -->
                    <div class="flex flex-wrap items-center gap-2">

                        <span class="inline-flex rounded-full
                                     bg-blue-50 px-2.5 py-1
                                     text-[11px] font-medium
                                     text-blue-700">

                            <?= e($jenisLabel) ?>

                        </span>

                        <?php if ($isOtomatis): ?>

                            <span class="inline-flex items-center
                                         gap-1 rounded-full bg-emerald-50
                                         px-2.5 py-1 text-[11px]
                                         font-medium text-emerald-700">

                                <svg class="h-3.5 w-3.5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    aria-hidden="true">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="m5 12 4 4L19 6" />

                                </svg>

                                Dari Sistem

                            </span>

                        <?php endif; ?>

                    </div>

                    <!-- POSISI -->
                    <h2 class="mt-2 break-words text-lg
                               font-bold text-gray-900">

                        <?= e($posisi) ?>

                    </h2>

                    <!-- INSTANSI -->
                    <p class="mt-1 break-words text-sm
                              font-medium text-gray-600">

                        <?= e($instansi) ?>

                    </p>

                </div>

                <!-- TOMBOL AKSI -->
                <?php if (!$isOtomatis && $pengalamanId > 0): ?>

                    <div class="flex shrink-0 gap-2">

                        <!-- EDIT -->
                        <a
                            href="<?= e(url('/dashboard/mahasiswa/pengalaman/edit/' . $pengalamanId)) ?>"
                            class="inline-flex items-center gap-2
                                   rounded-lg border border-slate-200
                                   px-3 py-2 text-sm font-medium
                                   text-gray-700 transition
                                   hover:border-blue-200
                                   hover:bg-blue-50
                                   hover:text-blue-600">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="h-4 w-4">

                                <path d="M12 20h9" />

                                <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L8 18l-4 1 1-4Z" />

                            </svg>

                            Edit

                        </a>

                        <!-- HAPUS -->
                        <button
                            type="button"
                            data-delete-pengalaman
                            data-modal-target="<?= e($modalId) ?>"
                            class="inline-flex items-center gap-2
                                   rounded-lg border border-slate-200
                                   px-3 py-2 text-sm font-medium
                                   text-red-600 transition
                                   hover:border-red-200
                                   hover:bg-red-50">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="h-4 w-4">

                                <path d="M3 6h18" />
                                <path d="M8 6V4h8v2" />
                                <path d="m19 6-1 14H6L5 6" />
                                <path d="M10 11v5M14 11v5" />

                            </svg>

                            Hapus

                        </button>

                    </div>

                <?php endif; ?>

            </div>

            <!-- TANGGAL DAN LOKASI -->
            <div class="mt-3 flex flex-wrap items-center
                        gap-x-4 gap-y-2 text-xs text-gray-500">

                <?php if ($tanggalMulaiLabel !== '' || $tanggalSelesaiLabel !== ''): ?>

                    <span class="inline-flex items-center gap-1.5">

                        <svg
                            class="h-4 w-4 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            aria-hidden="true">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />

                        </svg>

                        <?= e($tanggalMulaiLabel ?: '-') ?>

                        <span>—</span>

                        <?= e($tanggalSelesaiLabel ?: 'Sekarang') ?>

                    </span>

                <?php endif; ?>

                <?php if ($lokasi !== ''): ?>

                    <span class="inline-flex items-center gap-1.5">

                        <svg
                            class="h-4 w-4 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            aria-hidden="true">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17.657 16.657L13.414 21a2 2 0 0 1-2.828 0l-4.243-4.343a8 8 0 1 1 11.314 0z" />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 11a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />

                        </svg>

                        <span class="break-words">
                            <?= e($lokasi) ?>
                        </span>

                    </span>

                <?php endif; ?>

            </div>

            <!-- DESKRIPSI -->
            <?php if ($deskripsi !== ''): ?>

                <p class="mt-3 whitespace-pre-line
                          text-sm leading-6 text-gray-600">

                    <?= e($deskripsi) ?>

                </p>

            <?php endif; ?>

        </div>

    </div>

</article>


<!-- =====================================================
     MODAL KONFIRMASI HAPUS
===================================================== -->

<?php if (!$isOtomatis && $pengalamanId > 0): ?>

    <div
        id="<?= e($modalId) ?>"
        class="fixed inset-0 z-[99999] hidden
           h-[100dvh] min-h-screen w-screen
           items-center justify-center
           overflow-y-auto
           bg-slate-950/60
           px-4 py-6
           backdrop-blur-sm"
        aria-hidden="true">

        <!-- MODAL BOX -->
        <div
            class="relative my-auto w-full max-w-md
               overflow-hidden rounded-2xl
               border border-slate-100
               bg-white shadow-2xl"
            role="dialog"
            aria-modal="true"
            aria-labelledby="<?= e($modalId) ?>-title"
            aria-describedby="<?= e($modalId) ?>-description"
            tabindex="-1">

            <!-- TOP ACCENT -->
            <div class="h-1 w-full bg-red-500"></div>

            <div class="p-6 sm:p-7">

                <!-- HEADER -->
                <div class="flex items-start gap-4">

                    <!-- ICON WARNING -->
                    <div class="flex h-12 w-12 shrink-0
                            items-center justify-center
                            rounded-full bg-red-50
                            text-red-600">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="h-6 w-6">

                            <path d="M12 9v4" />
                            <path d="M12 17h.01" />

                            <path
                                d="M10.3 4.5 3.8 16a2 2 0 0 0 1.74 3h12.92a2 2 0 0 0 1.74-3L13.7 4.5a2 2 0 0 0-3.4 0Z" />

                        </svg>

                    </div>

                    <!-- TITLE -->
                    <div class="min-w-0 flex-1">

                        <h2
                            id="<?= e($modalId) ?>-title"
                            class="text-lg font-bold
                               tracking-tight text-slate-900">

                            Hapus pengalaman?

                        </h2>

                        <p
                            id="<?= e($modalId) ?>-description"
                            class="mt-1.5 text-sm leading-6
                               text-slate-500">

                            Apakah kamu yakin ingin menghapus
                            pengalaman ini? Data yang sudah dihapus
                            tidak dapat dikembalikan.

                        </p>

                    </div>

                </div>

                <!-- DETAIL PENGALAMAN -->
                <div class="mt-5 rounded-xl
                        border border-red-100
                        bg-red-50/70 p-4">

                    <div class="flex items-start gap-3">

                        <!-- ICON TRASH -->
                        <div class="mt-0.5 flex h-9 w-9 shrink-0
                                items-center justify-center
                                rounded-lg bg-white
                                text-red-500 shadow-sm">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="h-4 w-4">

                                <path d="M3 6h18" />
                                <path d="M8 6V4h8v2" />
                                <path d="m19 6-1 14H6L5 6" />

                            </svg>

                        </div>

                        <div class="min-w-0 flex-1">

                            <p class="text-[11px] font-semibold
                                  uppercase tracking-wider
                                  text-red-500">

                                Pengalaman yang akan dihapus

                            </p>

                            <p class="mt-1 break-words
                                  text-sm font-semibold
                                  text-slate-800">

                                <?= e($posisi) ?>

                            </p>

                            <p class="mt-0.5 break-words
                                  text-xs text-slate-500">

                                <?= e($instansi) ?>

                            </p>

                        </div>

                    </div>

                </div>

                <!-- FORM HAPUS -->
                <form
                    method="POST"
                    action="<?= e(url('/dashboard/mahasiswa/pengalaman/hapus/' . $pengalamanId)) ?>"
                    class="mt-6">

                    <!-- CSRF -->
                    <input
                        type="hidden"
                        name="_csrf_token"
                        value="<?= e(csrfToken()) ?>">

                    <!-- BUTTON -->
                    <div class="flex flex-col-reverse gap-2
                            sm:flex-row sm:justify-end">

                        <!-- BATAL -->
                        <button
                            type="button"
                            data-close-delete-modal
                            class="inline-flex items-center
                               justify-center rounded-xl
                               border border-slate-200
                               bg-white px-5 py-2.5
                               text-sm font-semibold
                               text-slate-600 transition
                               hover:bg-slate-50
                               hover:text-slate-800
                               focus:outline-none
                               focus:ring-2
                               focus:ring-slate-300
                               focus:ring-offset-2">

                            Batal

                        </button>

                        <!-- KONFIRMASI HAPUS -->
                        <button
                            type="submit"
                            class="inline-flex items-center
                               justify-center gap-2
                               rounded-xl bg-red-600
                               px-5 py-2.5 text-sm
                               font-semibold text-white
                               shadow-sm transition
                               hover:bg-red-700
                               hover:shadow-md
                               focus:outline-none
                               focus:ring-2
                               focus:ring-red-500
                               focus:ring-offset-2">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="h-4 w-4">

                                <path d="M3 6h18" />
                                <path d="M8 6V4h8v2" />
                                <path d="m19 6-1 14H6L5 6" />
                                <path d="M10 11v5M14 11v5" />

                            </svg>

                            Hapus Pengalaman

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

<?php endif; ?>


<!-- =====================================================
     JAVASCRIPT MODAL
     INITIALIZED ONCE
===================================================== -->

<script>
    (function() {

        // Hindari duplikasi event listener
        if (window.__pengalamanDeleteModalInitialized) {
            return;
        }

        window.__pengalamanDeleteModalInitialized = true;

        let activeModal = null;
        let previousActiveElement = null;
        let previousBodyOverflow = '';

        /**
         * BUKA MODAL
         */
        function openDeleteModal(modal) {

            if (!modal) return;

            // Tutup modal yang sedang terbuka
            if (activeModal) {
                closeDeleteModal();
            }

            // Simpan elemen fokus sebelumnya
            previousActiveElement = document.activeElement;

            // Simpan kondisi overflow sebelumnya
            previousBodyOverflow = document.body.style.overflow;

            // PENTING:
            // Pindahkan modal ke document.body
            // agar tidak terpengaruh parent container.
            if (modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }

            activeModal = modal;

            // Tampilkan modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            modal.setAttribute('aria-hidden', 'false');

            // Kunci scroll halaman
            document.body.style.overflow = 'hidden';

            // Fokus ke dialog
            const dialog = modal.querySelector('[role="dialog"]');

            if (dialog) {
                dialog.focus();
            }

        }

        /**
         * TUTUP MODAL
         */
        function closeDeleteModal() {

            if (!activeModal) return;

            const modal = activeModal;

            modal.classList.add('hidden');
            modal.classList.remove('flex');

            modal.setAttribute('aria-hidden', 'true');

            // Kembalikan scroll halaman
            document.body.style.overflow = previousBodyOverflow;

            activeModal = null;

            // Kembalikan fokus
            if (
                previousActiveElement &&
                document.contains(previousActiveElement)
            ) {
                previousActiveElement.focus();
            }

            previousActiveElement = null;

        }

        /**
         * EVENT DELEGATION
         */
        document.addEventListener('click', function(event) {

            // Tombol hapus
            const deleteButton = event.target.closest(
                '[data-delete-pengalaman]'
            );

            if (deleteButton) {

                const modalId = deleteButton.dataset.modalTarget;

                if (!modalId) return;

                const modal = document.getElementById(modalId);

                openDeleteModal(modal);

                return;
            }

            // Tombol batal
            const closeButton = event.target.closest(
                '[data-close-delete-modal]'
            );

            if (closeButton) {
                closeDeleteModal();
                return;
            }

            // Klik backdrop
            if (
                activeModal &&
                event.target === activeModal
            ) {
                closeDeleteModal();
            }

        });

        /**
         * TOMBOL ESCAPE
         */
        document.addEventListener('keydown', function(event) {

            if (
                event.key === 'Escape' &&
                activeModal
            ) {
                closeDeleteModal();
            }

        });

    })();
</script>