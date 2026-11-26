<?php

$base = url('/');
$active = 'users';

?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . '/../../../../components/dashboard/tendik/Head.php'; ?>

<body class="overflow-x-hidden bg-slate-50 font-[Poppins] text-gray-900">

    <?php include __DIR__ . '/../../../../components/dashboard/tendik/Sidebar.php'; ?>
    <?php include __DIR__ . '/../../../../components/dashboard/shared/Header.php'; ?>


    <main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">

        <div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

            <!-- Page Header -->
            <div class="flex flex-col gap-5 mb-8 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">
                        Manajemen User
                    </h1>

                    <p class="mt-2 text-sm text-gray-500 sm:text-base">
                        Kelola akun, hak akses, dan status pengguna aplikasi.
                    </p>
                </div>

                <a
                    href="<?= e(url('/dashboard/tendik/users/create')) ?>"
                    class="inline-flex items-center justify-center gap-2 px-5 py-3 text-sm font-semibold text-white transition bg-blue-600 shadow-sm rounded-xl hover:bg-blue-700">

                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 5v14m-7-7h14" />
                    </svg>

                    Tambah User
                </a>

            </div>

            <?php if (!empty($success)): ?>
                <div class="mb-5 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                    <?= e($success) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <?= e($error) ?>
                </div>
            <?php endif; ?>


            <!-- Search & Filter -->
            <section class="p-5 mb-6 bg-white border border-gray-100 shadow-sm rounded-2xl">

                <div class="mb-4">
                    <h2 class="text-sm font-semibold text-gray-900">
                        Pencarian Pengguna
                    </h2>

                    <p class="mt-1 text-xs text-gray-400">
                        Temukan pengguna berdasarkan ID login, nama, atau role.
                    </p>
                </div>

                <form
                    method="GET"
                    action="<?= e(url('/dashboard/tendik/users')) ?>"
                    class="grid grid-cols-1 gap-3 sm:grid-cols-[1fr_220px_auto]">

                    <!-- Search -->
                    <div class="relative">

                        <svg
                            class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <circle cx="11" cy="11" r="7" stroke-width="1.8" />
                            <path stroke-linecap="round" stroke-width="1.8" d="m16 16 4 4" />

                        </svg>

                        <input
                            type="search"
                            name="search"
                            value="<?= e($filters['search'] ?? '') ?>"
                            placeholder="Cari ID login atau nama..."
                            class="w-full py-3 pl-10 pr-4 text-sm transition bg-white border border-gray-200 outline-none rounded-xl placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                    </div>

                    <!-- Role Filter -->
                    <select
                        name="role"
                        class="w-full px-4 py-3 text-sm text-gray-700 transition bg-white border border-gray-200 outline-none rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                        <option value="">Semua Role</option>

                        <?php foreach ($roles as $roleOption): ?>

                            <option
                                value="<?= e($roleOption) ?>"
                                <?= (($filters['role'] ?? '') === $roleOption) ? 'selected' : '' ?>>

                                <?= e($roleOption) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                    <!-- Submit -->
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 px-5 py-3 text-sm font-semibold text-gray-700 transition border border-gray-200 rounded-xl bg-gray-50 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">

                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M7 12h10m-7 6h4" />
                        </svg>
                        Filter
                    </button>
                </form>
            </section>

            <!-- Success Message -->
            <?php if (!empty($success)): ?>
                <div class="flex items-start gap-3 p-4 mb-6 border border-emerald-200 rounded-xl bg-emerald-50">
                    <svg
                        class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7" />
                    </svg>

                    <p class="text-sm text-emerald-700">
                        <?= e($success) ?>
                    </p>
                </div>
            <?php endif; ?>


            <!-- Error Message -->
            <?php if (!empty($error)): ?>
                <div class="flex items-start gap-3 p-4 mb-6 border border-red-200 rounded-xl bg-red-50">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v4m0 4h.01M10.3 3.86 1.82 18a2 2 0 001.7 3h16.96a2 2 0 001.7-3L13.7 3.86a2 2 0 00-3.4 0z" />
                    </svg>
                    <p class="text-sm text-red-700">
                        <?= e($error) ?>
                    </p>
                </div>
            <?php endif; ?>


            <!-- User Table -->
            <section class="overflow-hidden bg-white border border-gray-100 shadow-sm rounded-2xl">
                <!-- Table Header -->
                <div class="flex flex-col gap-1 px-5 py-5 border-b border-gray-100 sm:px-7">
                    <h2 class="text-base font-semibold text-gray-900">
                        Daftar Pengguna
                    </h2>
                    <p class="text-sm text-gray-500">
                        Informasi akun dan hak akses pengguna yang terdaftar.
                    </p>
                </div>


                <div class="overflow-x-auto">
                    <table class="w-full min-w-[720px] text-sm">
                        <thead class="bg-gray-50/80">
                            <tr class="text-left">
                                <!-- <th class="px-6 py-4 text-xs font-semibold tracking-wider text-gray-500 uppercase">
                                    ID Login
                                </th> -->
                                <th class="px-6 py-4 text-xs font-semibold tracking-wider text-gray-500 uppercase">
                                    Nama Pengguna
                                </th>
                                <th class="px-6 py-4 text-xs font-semibold tracking-wider text-gray-500 uppercase">
                                    Role / Hak Akses
                                </th>
                                <th class="px-6 py-4 text-xs font-semibold tracking-wider text-gray-500 uppercase">
                                    Status Akun
                                </th>
                                <th class="px-6 py-4 text-xs font-semibold tracking-wider text-gray-500 uppercase">
                                    Aksi
                                </th>
                            </tr>
                        </thead>


                        <tbody class="divide-y divide-gray-100">
                            <?php if (!empty($items)): ?>
                                <?php foreach ($items as $item): ?>
                                    <?php
                                    $rolesValue = $item['roles'] ?? [];
                                    if (is_string($rolesValue)) {
                                        $rolesValue = trim($rolesValue, '{}') === ''
                                            ? []
                                            : explode(',', trim($rolesValue, '{}'));
                                    }
                                    ?>

                                    <tr class="transition-colors hover:bg-gray-50/70">
                                        <!-- Login ID -->
                                        <!-- <td class="px-6 py-4">
                                            <span class="font-medium text-gray-800">
                                                <?= e((string) ($item['login_id'] ?? '-')) ?>
                                            </span>
                                        </td> -->


                                        <!-- Name -->
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="flex items-center justify-center text-sm font-semibold text-blue-600 rounded-full h-9 w-9 shrink-0 bg-blue-50">
                                                    <?= e(strtoupper(substr(
                                                        (string) ($item['name'] ?? 'U'),
                                                        0,
                                                        1
                                                    ))) ?>

                                                </div>
                                                <span class="font-medium text-gray-800">
                                                    <?= e((string) ($item['name'] ?? '-')) ?>
                                                </span>
                                            </div>
                                        </td>
                                        <!-- Roles -->
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-1.5">
                                                <?php foreach ((array) $rolesValue as $itemRole): ?>
                                                    <?php
                                                    $itemRole = trim((string) $itemRole, '" ');
                                                    $roleColors = [
                                                        'admin' => 'bg-purple-50 text-purple-700',
                                                        'mahasiswa' => 'bg-blue-50 text-blue-700',
                                                        'dosen' => 'bg-emerald-50 text-emerald-700',
                                                        'tendik' => 'bg-amber-50 text-amber-700',
                                                        'koordinator_magang' => 'bg-indigo-50 text-indigo-700',
                                                        'mitra' => 'bg-gray-100 text-gray-700',
                                                    ];
                                                    $roleColor = $roleColors[$itemRole]
                                                        ?? 'bg-gray-100 text-gray-600';

                                                    ?>
                                                    <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-medium <?= $roleColor ?>">
                                                        <?= e(ucwords(str_replace('_', ' ', $itemRole))) ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            </div>
                                        </td>

                                        <!-- Account Status -->
                                        <td class="px-6 py-4">
                                            <?php if (!empty($item['is_active'])): ?>
                                                <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-medium text-emerald-700">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                    Aktif
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-2 rounded-full bg-gray-100 px-3 py-1.5 text-xs font-medium text-gray-600">

                                                    <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>

                                                    Nonaktif
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <a
                                                    href="<?= e(url('/dashboard/tendik/users/' . (int) $item['id'])) ?>"
                                                    class="inline-flex items-center gap-2 px-3 py-2 text-xs font-semibold text-blue-700 transition border border-blue-200 rounded-lg bg-blue-50 hover:bg-blue-100">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.1 2.1 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                    </svg>
                                                    Edit
                                                </a>

                                                <?php if (!empty($item['is_active'])): ?>
                                                    <form
                                                        method="POST"
                                                        action="<?= e(url('/dashboard/tendik/users/' . (int) $item['id'] . '/status')) ?>"
                                                        onsubmit="return confirm('Yakin ingin menonaktifkan akun ini?');">

                                                        <input type="hidden" name="_csrf_token"
                                                            value="<?= e(csrfToken()) ?>">
                                                        <input type="hidden" name="status" value="inactive">

                                                        <button type="submit"
                                                            class="inline-flex items-center gap-2 px-3 py-2 text-xs font-semibold text-red-700 transition border border-red-200 rounded-lg bg-red-50 hover:bg-red-100">
                                                            Nonaktifkan
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <form
                                                        method="POST"
                                                        action="<?= e(url('/dashboard/tendik/users/' . (int) $item['id'] . '/status')) ?>"
                                                        onsubmit="return confirm('Yakin ingin mengaktifkan akun ini?');">

                                                        <input type="hidden" name="_csrf_token"
                                                            value="<?= e(csrfToken()) ?>">
                                                        <input type="hidden" name="status" value="active">

                                                        <button type="submit"
                                                            class="inline-flex items-center gap-2 px-3 py-2 text-xs font-semibold transition border rounded-lg text-emerald-700 border-emerald-200 bg-emerald-50 hover:bg-emerald-100">
                                                            Aktifkan
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <!-- Empty State -->
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center">

                                        <div class="flex items-center justify-center mx-auto text-gray-400 bg-gray-100 h-14 w-14 rounded-2xl">

                                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                    d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m7-8a4 4 0 100-8 4 4 0 000 8Zm9-3v6m3-3h-6" />
                                            </svg>
                                        </div>
                                        <h3 class="mt-4 text-sm font-semibold text-gray-900">
                                            Pengguna Tidak Ditemukan
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Tidak ada pengguna yang sesuai dengan pencarian atau filter.
                                        </p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if (!empty($items)): ?>
                    <div class="px-6 py-4 border-t border-gray-100">
                        <p class="text-xs text-gray-400">
                            Menampilkan <?= count($items) ?> pengguna
                        </p>
                    </div>
                <?php endif; ?>
            </section>

            <?php include __DIR__ . '/../../../../components/dashboard/tendik/Footer.php'; ?>
        </div>
    </main>
</body>

</html>