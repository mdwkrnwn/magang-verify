<?php
$base = url('/');
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
$isDosen = in_array('dosen', $roles, true)
    || in_array('koordinator_magang', $roles, true);

$mahasiswa = $user['mahasiswa'] ?? [];
$dosen = $user['dosen'] ?? [];

$formatDate = static function ($value): string {
    if (empty($value)) {
        return '-';
    }

    try {
        return date('d M Y, H:i', strtotime($value));
    } catch (Throwable $exception) {
        return '-';
    }
};
?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . '/../../../../components/dashboard/tendik/Head.php'; ?>

<body class="overflow-x-hidden bg-slate-50 font-[Poppins] text-slate-800">

    <?php include __DIR__ . '/../../../../components/dashboard/tendik/Sidebar.php'; ?>
    <?php include __DIR__ . '/../../../../components/dashboard/tendik/Header.php'; ?>

    <main class="min-h-screen bg-slate-50 pt-20 lg:ml-64">
        <div class="mx-auto max-w-8xl px-4 py-7 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-7">
                <a
                    href="<?= e(url('/dashboard/tendik/users')) ?>"
                    class="mb-5 inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-blue-600">

                    <svg class="h-4 w-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>

                    Kembali ke Manajemen User
                </a>

                <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                    Detail Pengguna
                </h1>

                <p class="mt-2 text-sm text-slate-500">
                    Lihat dan kelola informasi dasar pengguna.
                </p>
            </div>

            <!-- Alert -->
            <?php if (!empty($error)): ?>
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <?= e($error) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    <?= e($success) ?>
                </div>
            <?php endif; ?>

            <!-- User Identity Card -->
            <section class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-center">

                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="1.6"
                                d="M20 21a8 8 0 00-16 0m8-10a4 4 0 100-8 4 4 0 000 8z" />
                        </svg>
                    </div>

                    <div class="min-w-0 flex-1">
                        <h2 class="text-xl font-bold text-slate-900">
                            <?= e($user['name']) ?>
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            ID Pengguna: #<?= (int) $user['id'] ?>
                        </p>

                        <div class="mt-3 flex flex-wrap gap-2">
                            <?php foreach ($roles as $role): ?>
                                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                    <?= e($roleLabels[$role] ?? $role) ?>
                                </span>
                            <?php endforeach; ?>

                            <?php if (!empty($user['is_active'])): ?>
                                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                    Aktif
                                </span>
                            <?php else: ?>
                                <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                                    Tidak Aktif
                                </span>
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

            <!-- Form Detail -->
            <form
                method="POST"
                action="<?= e(url('/dashboard/tendik/users/' . (int) $user['id'])) ?>"
                class="space-y-6">

                <input
                    type="hidden"
                    name="_csrf_token"
                    value="<?= e(csrfToken()) ?>">

                <!-- Account Information -->
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-100 px-5 py-5 sm:px-6">
                        <h2 class="text-base font-semibold text-slate-900">
                            Informasi Akun
                        </h2>
                        <p class="mt-1 text-xs text-slate-500">
                            Identitas dasar pengguna dalam sistem.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 sm:p-6">

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-600">
                                Nama Lengkap
                            </label>
                            <input
                                type="text"
                                value="<?= e($user['name']) ?>"
                                readonly
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600 outline-none">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-600">
                                Role Pengguna
                            </label>
                            <input
                                type="text"
                                value="<?= e(implode(', ', array_map(
                                            fn($role) => $roleLabels[$role] ?? $role,
                                            $roles
                                        ))) ?>"
                                readonly
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600 outline-none">
                        </div>

                    </div>
                </section>

                <!-- Student Profile -->
                <?php if ($isMahasiswa): ?>
                    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                        <div class="border-b border-slate-100 px-5 py-5 sm:px-6">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="1.7"
                                            d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.42A12.08 12.08 0 0118 17.5a12 12 0 01-12 0 12.08 12.08 0 01-.16-6.92L12 14zm0 0v6" />
                                    </svg>
                                </div>

                                <div>
                                    <h2 class="text-base font-semibold text-slate-900">
                                        Informasi Akademik
                                    </h2>
                                    <p class="mt-1 text-xs text-slate-500">
                                        Data dasar mahasiswa yang dikelola Tendik.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 sm:p-6">

                            <div>
                                <label for="nim" class="mb-2 block text-sm font-medium text-slate-600">
                                    NIM <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="nim"
                                    name="nim"
                                    type="text"
                                    required
                                    maxlength="30"
                                    value="<?= e($mahasiswa['nim'] ?? '') ?>"
                                    placeholder="Masukkan NIM"
                                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                            </div>

                            <div>
                                <label for="program_studi" class="mb-2 block text-sm font-medium text-slate-600">
                                    Program Studi
                                </label>
                                <input
                                    id="program_studi"
                                    name="program_studi"
                                    type="text"
                                    maxlength="150"
                                    value="<?= e($mahasiswa['program_studi'] ?? '') ?>"
                                    placeholder="Contoh: Teknik Informatika"
                                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                            </div>

                            <div>
                                <label for="angkatan" class="mb-2 block text-sm font-medium text-slate-600">
                                    Angkatan
                                </label>
                                <input
                                    id="angkatan"
                                    name="angkatan"
                                    type="number"
                                    min="2000"
                                    max="2100"
                                    value="<?= e($mahasiswa['angkatan'] ?? '') ?>"
                                    placeholder="Contoh: 2023"
                                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-600">
                                    Email
                                </label>
                                <input
                                    type="text"
                                    value="<?= e($mahasiswa['email'] ?? '') ?>"
                                    readonly
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-500 outline-none">
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-600">
                                    Nomor Telepon
                                </label>
                                <input
                                    type="text"
                                    value="<?= e($mahasiswa['no_telepon'] ?? '') ?>"
                                    readonly
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-500 outline-none">
                            </div>

                        </div>
                    </section>
                <?php endif; ?>

                <!-- Lecturer Profile -->
                <?php if ($isDosen): ?>
                    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                        <div class="border-b border-slate-100 px-5 py-5 sm:px-6">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="1.7"
                                            d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m-6-9.33V17a6 3 0 0012 0v-6.33" />
                                    </svg>
                                </div>

                                <div>
                                    <h2 class="text-base font-semibold text-slate-900">
                                        Informasi Dosen
                                    </h2>
                                    <p class="mt-1 text-xs text-slate-500">
                                        Data dosen, termasuk jika memiliki role Koordinator Magang.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 sm:p-6">

                            <div>
                                <label for="nidn" class="mb-2 block text-sm font-medium text-slate-600">
                                    NIDN
                                </label>
                                <input
                                    id="nidn"
                                    name="nidn"
                                    type="text"
                                    maxlength="30"
                                    value="<?= e($dosen['nidn'] ?? '') ?>"
                                    placeholder="Masukkan NIDN"
                                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-600">
                                    Email
                                </label>
                                <input
                                    type="text"
                                    value="<?= e($dosen['email'] ?? '') ?>"
                                    readonly
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-500 outline-none">
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-600">
                                    Nomor Telepon
                                </label>
                                <input
                                    type="text"
                                    value="<?= e($dosen['no_telepon'] ?? '') ?>"
                                    readonly
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-500 outline-none">
                            </div>

                        </div>
                    </section>
                <?php endif; ?>

                <?php if (!$isMahasiswa && !$isDosen): ?>
                    <section class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
                        <p class="text-sm font-medium text-amber-800">
                            Detail profil untuk role ini belum tersedia.
                        </p>
                        <p class="mt-1 text-xs text-amber-700">
                            Pengelolaan profil Tendik dan Mitra akan ditambahkan pada tahap berikutnya.
                        </p>
                    </section>
                <?php endif; ?>

                <!-- Actions -->
                <?php if ($isMahasiswa || $isDosen): ?>
                    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <a
                            href="<?= e(url('/dashboard/tendik/users')) ?>"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                            Kembali
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200">

                            <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>

                            Simpan Perubahan
                        </button>
                    </div>
                <?php endif; ?>

            </form>

            <?php include __DIR__ . '/../../../../components/dashboard/tendik/Footer.php'; ?>

        </div>
    </main>

</body>

</html>