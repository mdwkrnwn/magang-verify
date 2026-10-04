<?php
/** @var array $dashboard */
$profil = $dashboard['profil'] ?? [];
$nama = trim((string) ($profil['nama_lengkap'] ?? 'Mahasiswa'));
$prodi = trim((string) ($profil['program_studi'] ?? ''));
$angkatan = trim((string) ($profil['angkatan'] ?? ''));
$foto = trim((string) ($profil['foto_path'] ?? ''));
$bagianNama = preg_split('/\s+/', $nama, -1, PREG_SPLIT_NO_EMPTY);
$inisial = '';
if (!empty($bagianNama)) {
    $inisial = mb_strtoupper(mb_substr($bagianNama[0], 0, 1));
    if (count($bagianNama) > 1) {
        $inisial .= mb_strtoupper(mb_substr($bagianNama[count($bagianNama) - 1], 0, 1));
    }
}
?>

<section class="mb-6 sm:mb-8">
    <div class="flex flex-col gap-5 p-5 overflow-hidden bg-white border border-gray-100 shadow-sm rounded-2xl sm:p-7 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex items-center min-w-0 gap-4 sm:gap-5">
            <div class="flex items-center justify-center w-14 h-14 overflow-hidden text-lg font-bold text-blue-600 bg-blue-100 rounded-2xl shrink-0 sm:w-16 sm:h-16 sm:text-xl">
                <?php if ($foto !== ''): ?>
                    <img
                        src="<?= e(url('/' . ltrim($foto, '/'))) ?>"
                        alt="Foto <?= e($nama) ?>"
                        class="object-cover w-full h-full"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                    >
                    <span class="items-center justify-center hidden w-full h-full"><?= e($inisial ?: 'M') ?></span>
                <?php else: ?>
                    <?= e($inisial ?: 'M') ?>
                <?php endif; ?>
            </div>

            <div class="min-w-0">
                <p class="text-sm font-medium text-blue-600">Dashboard Mahasiswa</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900 break-words sm:text-3xl">
                    Selamat datang, <?= e($nama) ?> 👋
                </h1>
                <p class="mt-1 text-sm text-gray-500 sm:text-base">
                    <?= e($prodi ?: 'Program studi belum diisi') ?>
                    <?php if ($angkatan !== ''): ?> · Angkatan <?= e($angkatan) ?><?php endif; ?>
                </p>
            </div>
        </div>

        <a href="<?= e(url('/dashboard/mahasiswa/profil')) ?>" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-blue-600 border border-blue-100 rounded-xl hover:bg-blue-50 sm:w-auto">
            Lihat Profil
            <span aria-hidden="true">→</span>
        </a>
    </div>
</section>
