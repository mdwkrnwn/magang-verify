<?php
$active = 'users';

$roles = $user['roles'] ?? [];
$roleLabels = [
    'mahasiswa' => 'Mahasiswa',
    'dosen' => 'Dosen',
    'koordinator_magang' => 'Koordinator Magang',
    'tendik' => 'Tendik',
    'mitra' => 'Mitra',
];

$isMahasiswa = in_array('mahasiswa', $roles, true);
$isDosen = in_array('dosen', $roles, true) || in_array('koordinator_magang', $roles, true);
$isTendik = in_array('tendik', $roles, true);

$mahasiswa = $user['mahasiswa'] ?? [];
$dosen = $user['dosen'] ?? [];
$tendik = $user['tendik'] ?? [];

$profile = $isMahasiswa ? $mahasiswa : ($isDosen ? $dosen : $tendik);
$identityLabel = $isMahasiswa ? 'NIM' : ($isDosen ? 'NIDN' : 'NIP');
$identityName = $isMahasiswa ? 'nim' : ($isDosen ? 'nidn' : 'nip');
$identityValue = $profile[$identityName] ?? ($user['login_id'] ?? '');

$formatDate = static function ($value): string {
    if (empty($value)) {
        return '-';
    }
    $timestamp = strtotime((string) $value);
    return $timestamp ? date('d M Y, H:i', $timestamp) : '-';
};
?>

<!DOCTYPE html>
<html lang="id">
<?php include __DIR__ . '/../../../../components/dashboard/tendik/Head.php'; ?>

<body class="overflow-x-hidden bg-slate-50 font-[Poppins] text-slate-800">
    <?php include __DIR__ . '/../../../../components/dashboard/tendik/Sidebar.php'; ?>
    <?php include __DIR__ . '/../../../../components/dashboard/shared/Header.php'; ?>

    <main class="min-h-screen pt-10 bg-slate-50 lg:ml-64">
        <div class="px-4 mx-auto max-w-8xl py-7 sm:px-6 lg:px-8">

            <div class="mb-7">
                <a href="<?= e(url('/dashboard/tendik/users')) ?>"
                    class="inline-flex items-center gap-2 mb-5 text-sm font-medium text-slate-500 hover:text-blue-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Manajemen User
                </a>

                <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                    Edit Pengguna
                </h1>
                <p class="mt-2 text-sm text-slate-500">
                    Perbarui informasi akun dan data profil pengguna.
                </p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="px-4 py-3 mb-5 text-sm text-red-700 border border-red-200 rounded-xl bg-red-50">
                    <?= e($error) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="px-4 py-3 mb-5 text-sm border rounded-xl border-emerald-200 bg-emerald-50 text-emerald-700">
                    <?= e($success) ?>
                </div>
            <?php endif; ?>

            <section class="p-5 mb-6 bg-white border shadow-sm rounded-2xl border-slate-200 sm:p-6">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
                    <div class="flex items-center justify-center w-16 h-16 text-blue-600 shrink-0 rounded-2xl bg-blue-50">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                d="M20 21a8 8 0 00-16 0m8-10a4 4 0 100-8 4 4 0 000 8z" />
                        </svg>
                    </div>

                    <div class="flex-1 min-w-0">
                        <h2 class="text-xl font-bold text-slate-900"><?= e($user['name'] ?? '-') ?></h2>
                        <p class="mt-1 text-sm text-slate-500">
                            ID Pengguna: #<?= (int) $user['id'] ?> · Login ID: <?= e($user['login_id'] ?? '-') ?>
                        </p>
                        <div class="flex flex-wrap gap-2 mt-3">
                            <?php foreach ($roles as $role): ?>
                                <span class="px-3 py-1 text-xs font-semibold text-blue-700 rounded-full bg-blue-50">
                                    <?= e($roleLabels[$role] ?? $role) ?>
                                </span>
                            <?php endforeach; ?>

                            <?php if (!empty($user['is_active'])): ?>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700">Aktif</span>
                            <?php else: ?>
                                <span class="px-3 py-1 text-xs font-semibold text-red-700 rounded-full bg-red-50">Nonaktif</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="sm:text-right">
                        <p class="text-xs text-slate-400">Terdaftar sejak</p>
                        <p class="mt-1 text-sm font-medium text-slate-700">
                            <?= e($formatDate($user['created_at'] ?? null)) ?>
                        </p>
                    </div>
                </div>
            </section>

            <form method="POST"
                action="<?= e(url('/dashboard/tendik/users/' . (int) $user['id'])) ?>"
                class="space-y-6">
                <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">

                <section class="overflow-hidden bg-white border shadow-sm rounded-2xl border-slate-200">
                    <div class="px-5 py-5 border-b border-slate-100 sm:px-6">
                        <h2 class="text-base font-semibold text-slate-900">Informasi Akun</h2>
                        <p class="mt-1 text-xs text-slate-500">
                            Nama dan identitas login dapat diperbarui. Role akun tidak diubah dari halaman ini.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 sm:p-6">
                        <div>
                            <label for="name" class="block mb-2 text-sm font-medium text-slate-600">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input id="name" name="name" type="text" required maxlength="150"
                                value="<?= e($user['name'] ?? '') ?>"
                                class="w-full px-4 py-3 text-sm border outline-none rounded-xl border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-slate-600">Role Pengguna</label>
                            <input type="text" readonly
                                value="<?= e(implode(', ', array_map(static fn($role) => $roleLabels[$role] ?? $role, $roles))) ?>"
                                class="w-full px-4 py-3 text-sm border outline-none rounded-xl border-slate-200 bg-slate-50 text-slate-600">
                        </div>

                        <div>
                            <label for="<?= e($identityName) ?>" class="block mb-2 text-sm font-medium text-slate-600">
                                <?= e($identityLabel) ?> <span class="text-red-500">*</span>
                            </label>
                            <input id="<?= e($identityName) ?>" name="<?= e($identityName) ?>" type="text"
                                required maxlength="30" pattern="[A-Za-z0-9.-]{3,30}"
                                value="<?= e($identityValue) ?>"
                                class="w-full px-4 py-3 text-sm border outline-none rounded-xl border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                            <p class="mt-1 text-xs text-slate-400">Identitas ini juga digunakan sebagai ID login.</p>
                        </div>

                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-slate-600">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input id="email" name="email" type="email" required maxlength="255"
                                value="<?= e($profile['email'] ?? '') ?>"
                                class="w-full px-4 py-3 text-sm border outline-none rounded-xl border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                        </div>

                        <div>
                            <label for="no_telepon" class="block mb-2 text-sm font-medium text-slate-600">
                                Nomor Telepon<?= $isMahasiswa ? '' : ' *' ?>
                            </label>
                            <input id="no_telepon" name="no_telepon" type="tel" maxlength="25"
                                <?= $isMahasiswa ? '' : 'required' ?>
                                value="<?= e($profile['no_telepon'] ?? '') ?>"
                                placeholder="Contoh: 081234567890"
                                class="w-full px-4 py-3 text-sm border outline-none rounded-xl border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                        </div>

                        <?php if ($isMahasiswa): ?>
                            <div>
                                <label for="program_studi" class="block mb-2 text-sm font-medium text-slate-600">
                                    Program Studi <span class="text-red-500">*</span>
                                </label>
                                <input id="program_studi" name="program_studi" type="text" required maxlength="150"
                                    value="<?= e($mahasiswa['program_studi'] ?? '') ?>"
                                    class="w-full px-4 py-3 text-sm border outline-none rounded-xl border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                            </div>

                            <div>
                                <label for="angkatan" class="block mb-2 text-sm font-medium text-slate-600">
                                    Angkatan <span class="text-red-500">*</span>
                                </label>
                                <input id="angkatan" name="angkatan" type="number" required min="2000" max="2100"
                                    value="<?= e($mahasiswa['angkatan'] ?? '') ?>"
                                    class="w-full px-4 py-3 text-sm border outline-none rounded-xl border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                            </div>
                        <?php endif; ?>
                    </div>
                </section>

                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <a href="<?= e(url('/dashboard/tendik/users')) ?>"
                        class="inline-flex items-center justify-center px-5 py-3 text-sm font-semibold transition bg-white border rounded-xl border-slate-200 text-slate-600 hover:bg-slate-100">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-semibold text-white transition bg-blue-600 shadow-sm rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>

            <?php include __DIR__ . '/../../../../components/dashboard/tendik/Footer.php'; ?>
        </div>
    </main>
</body>

</html>