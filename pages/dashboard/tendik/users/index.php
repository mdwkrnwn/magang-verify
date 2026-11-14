<?php

$base = url('/');
$active = 'users';

?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . '/../../../../components/dashboard/tendik/Head.php'; ?>

<body class="overflow-x-hidden bg-slate-50 font-[Poppins] text-gray-900">

    <?php include __DIR__ . '/../../../../components/dashboard/tendik/Sidebar.php'; ?>
    <?php include __DIR__ . '/../../../../components/dashboard/tendik/Header.php'; ?>

    <main class="min-h-screen bg-slate-50 pt-20 lg:ml-64">

        <div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

            <!-- Page Header -->
            <div class="mb-8 flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

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
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">

                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 5v14m-7-7h14" />
                    </svg>

                    Tambah User
                </a>

            </div>


            <!-- Search & Filter -->
            <section class="mb-6 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">

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
                            class="w-full rounded-xl border border-gray-200 bg-white py-3 pl-10 pr-4 text-sm outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                    </div>

                    <!-- Role Filter -->
                    <select
                        name="role"
                        class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

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
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-gray-50 px-5 py-3 text-sm font-semibold text-gray-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">

                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M7 12h10m-7 6h4" />
                        </svg>
                        Filter
                    </button>
                </form>
            </section>


            <!-- Error Message -->
            <?php if (!empty($error)): ?>
                <div class="mb-6 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4">
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
            <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                <!-- Table Header -->
                <div class="flex flex-col gap-1 border-b border-gray-100 px-5 py-5 sm:px-7">
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
                                <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    ID Login
                                </th>
                                <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Nama Pengguna
                                </th>
                                <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Role / Hak Akses
                                </th>
                                <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Status Akun
                                </th>
                                <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">
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
                                        <td class="px-6 py-4">
                                            <span class="font-medium text-gray-800">
                                                <?= e((string) ($item['login_id'] ?? '-')) ?>
                                            </span>
                                        </td>


                                        <!-- Name -->
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-50 text-sm font-semibold text-blue-600">
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
                                            <a
                                                href="<?= e(url('/dashboard/tendik/users/' . (int) $item['id'])) ?>"
                                                class="inline-flex items-center gap-2 rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 transition hover:bg-blue-100">

                                                <svg
                                                    class="h-4 w-4"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="1.8"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0Z" />
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="1.8"
                                                        d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7Z" />
                                                </svg>

                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <!-- Empty State -->
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center">

                                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 text-gray-400">

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
                    <div class="border-t border-gray-100 px-6 py-4">
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