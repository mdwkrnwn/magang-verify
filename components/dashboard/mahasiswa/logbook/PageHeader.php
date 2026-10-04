<div class="flex flex-col gap-4 mb-6 sm:mb-8 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Logbook</h1>
        <p class="mt-2 text-sm text-gray-500 sm:text-base">Catat aktivitas magang mingguan dan pantau proses verifikasinya.</p>
    </div>

    <?php if (!empty($canAdd)): ?>
        <a href="<?= e(url('/dashboard/mahasiswa/logbook/tambah')) ?>" class="inline-flex items-center justify-center h-10 px-4 gap-2 text-xs sm:text-sm font-medium text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14M5 12h14" />
            </svg>
            Tambah Logbook
        </a>
    <?php endif; ?>
</div>