<?php
/** @var array $dashboard */
$penempatan = $dashboard['penempatan'] ?? null;
$unread = (int) ($dashboard['notifikasi_belum_dibaca'] ?? 0);
?>

<section class="p-5 bg-blue-600 shadow-sm sm:p-7 rounded-2xl">
    <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-lg font-semibold text-white">Aksi Cepat</h2>
            <p class="mt-1 text-sm text-blue-100">
                <?= $penempatan && $penempatan['status'] === 'berlangsung'
                    ? 'Magang sedang berlangsung. Jangan lupa memperbarui logbook Anda.'
                    : 'Kelola profil, portofolio, dan proses magang Anda dari sini.' ?>
            </p>
        </div>

        <div class="grid grid-cols-2 gap-2 sm:flex sm:flex-wrap">
            <a href="<?= e(url('/dashboard/mahasiswa/formasi-magang')) ?>" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white transition bg-blue-500 border border-blue-400 rounded-xl hover:bg-blue-400">
                Cari Formasi
            </a>
            <a href="<?= e(url('/dashboard/mahasiswa/profil')) ?>" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white transition bg-blue-700/50 border border-blue-400/60 rounded-xl hover:bg-blue-700/70">
                Profil
            </a>
            <?php if ($penempatan && in_array($penempatan['status'], ['berlangsung', 'menunggu_penilaian'], true)): ?>
                <a href="<?= e(url('/dashboard/mahasiswa/logbook')) ?>" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white transition bg-blue-700/50 border border-blue-400/60 rounded-xl hover:bg-blue-700/70">
                    Logbook
                </a>
            <?php endif; ?>
            <?php if ($unread > 0): ?>
                <span class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-blue-100 bg-blue-800/60 border border-blue-500/50 rounded-xl">
                    <?= e((string) $unread) ?> notifikasi
                </span>
            <?php endif; ?>
        </div>
    </div>
</section>
