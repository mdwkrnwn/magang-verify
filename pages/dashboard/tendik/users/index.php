<?php

$base = url('/');
$active = 'users';
$selectedRoleLabel = $roleLabels[$selectedRole] ?? 'Pengguna';
$roleTheme = [
    'mahasiswa' => ['icon' => '🎓', 'accent' => 'blue', 'description' => 'Kelola akun dan status mahasiswa'],
    'dosen' => ['icon' => '👨‍🏫', 'accent' => 'emerald', 'description' => 'Kelola akun dosen dan data akses'],
    'koordinator_magang' => ['icon' => '📋', 'accent' => 'indigo', 'description' => 'Kelola akun koordinator magang'],
    'tendik' => ['icon' => '🗂️', 'accent' => 'amber', 'description' => 'Kelola akun tenaga kependidikan'],
];
$theme = $roleTheme[$selectedRole] ?? $roleTheme['mahasiswa'];
$accentClasses = [
    'blue' => ['icon' => 'bg-blue-50 text-blue-600', 'link' => 'text-blue-700', 'border' => 'group-hover:border-blue-300', 'number' => 'text-blue-700'],
    'emerald' => ['icon' => 'bg-emerald-50 text-emerald-600', 'link' => 'text-emerald-700', 'border' => 'group-hover:border-emerald-300', 'number' => 'text-emerald-700'],
    'indigo' => ['icon' => 'bg-indigo-50 text-indigo-600', 'link' => 'text-indigo-700', 'border' => 'group-hover:border-indigo-300', 'number' => 'text-indigo-700'],
    'amber' => ['icon' => 'bg-amber-50 text-amber-600', 'link' => 'text-amber-700', 'border' => 'group-hover:border-amber-300', 'number' => 'text-amber-700'],
];

$buildPageUrl = static function (int $page) use ($selectedRole, $filters): string {
    $query = ['role' => $selectedRole, 'page' => $page];
    if ($filters['search'] !== '') $query['search'] = $filters['search'];
    if ($filters['status'] !== '') $query['status'] = $filters['status'];
    return url('/dashboard/tendik/users') . '?' . http_build_query($query);
};

?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . '/../../../../components/dashboard/tendik/Head.php'; ?>

<body class="overflow-x-hidden bg-slate-50 font-[Poppins] text-gray-900">
    <?php include __DIR__ . '/../../../../components/dashboard/tendik/Sidebar.php'; ?>
    <?php include __DIR__ . '/../../../../components/dashboard/shared/Header.php'; ?>

    <main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">
        <div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

            <div class="flex flex-col gap-4 mb-8 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="mb-1 text-xs font-semibold tracking-wider text-blue-600 uppercase">Manajemen Pengguna</p>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">
                        <?= $isRoleDetail ? e($selectedRoleLabel) : 'Kategori Pengguna' ?>
                    </h1>
                    <p class="mt-2 text-sm text-gray-500 sm:text-base">
                        <?= $isRoleDetail
                            ? 'Kelola akun, hak akses, dan status ' . e(strtolower($selectedRoleLabel)) . '.'
                            : 'Pilih kategori pengguna untuk melihat dan mengelola daftar akun.' ?>
                    </p>
                </div>

                <?php if ($isRoleDetail): ?>
                    <div class="flex flex-wrap gap-2">
                        <a href="<?= e(url('/dashboard/tendik/users')) ?>"
                            class="inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-semibold text-gray-700 transition bg-white border border-gray-200 rounded-xl hover:bg-gray-50">
                            <span aria-hidden="true">←</span> Kembali
                        </a>
                        <a href="<?= e(url('/dashboard/tendik/users/create') . '?role=' . rawurlencode($selectedRole)) ?>"
                            class="inline-flex items-center justify-center gap-2 px-5 py-3 text-sm font-semibold text-white transition bg-blue-600 shadow-sm rounded-xl hover:bg-blue-700">
                            <span class="text-lg leading-none">+</span> Tambah <?= e($selectedRoleLabel) ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($success)): ?>
                <div class="flex items-start gap-3 p-4 mb-6 border border-emerald-200 rounded-xl bg-emerald-50">
                    <span class="font-bold text-emerald-600">✓</span>
                    <p class="text-sm text-emerald-700"><?= e($success) ?></p>
                </div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="flex items-start gap-3 p-4 mb-6 border border-red-200 rounded-xl bg-red-50">
                    <span class="font-bold text-red-600">!</span>
                    <p class="text-sm text-red-700"><?= e($error) ?></p>
                </div>
            <?php endif; ?>

            <?php if (!$isRoleDetail): ?>
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
                    <?php foreach ($roles as $role): ?>
                        <?php
                        $cardTheme = $roleTheme[$role] ?? ['icon' => '👤', 'accent' => 'blue', 'description' => 'Kelola akun pengguna'];
                        $classes = $accentClasses[$cardTheme['accent']];
                        $stats = $roleStats[$role] ?? ['total' => 0, 'active' => 0, 'inactive' => 0];
                        ?>
                        <a href="<?= e(url('/dashboard/tendik/users') . '?role=' . rawurlencode($role)) ?>"
                            class="group block p-6 transition duration-200 bg-white border border-gray-100 shadow-sm rounded-2xl hover:-translate-y-1 hover:shadow-md <?= e($classes['border']) ?>">
                            <div class="flex items-start justify-between">
                                <span class="inline-flex items-center justify-center w-12 h-12 text-2xl rounded-xl <?= e($classes['icon']) ?>">
                                    <?= e($cardTheme['icon']) ?>
                                </span>
                                <span class="text-gray-300 transition group-hover:translate-x-1 group-hover:text-gray-500"></span>
                            </div>
                            <h2 class="mt-5 text-lg font-semibold text-gray-900"><?= e($roleLabels[$role] ?? $role) ?></h2>
                            <p class="mt-1 text-sm text-gray-500"><?= e($cardTheme['description']) ?></p>
                            <div class="flex items-end justify-between mt-6">
                                <div>
                                    <p class="text-xs text-gray-400">Total akun</p>
                                    <p class="mt-1 text-3xl font-bold <?= e($classes['number']) ?>"><?= number_format($stats['total']) ?></p>
                                </div>
                                <div class="text-right text-xs leading-5 text-gray-500">
                                    <p><span class="font-semibold text-emerald-600"><?= number_format($stats['active']) ?></span> aktif</p>
                                    <p><span class="font-semibold text-gray-600"><?= number_format($stats['inactive']) ?></span> nonaktif</p>
                                </div>
                            </div>
                            <p class="mt-5 text-sm font-semibold <?= e($classes['link']) ?>">Lihat daftar pengguna <span aria-hidden="true"></span></p>
                        </a>
                    <?php endforeach; ?>
                </div>

            <?php else: ?>
                <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-3">
                    <div class="p-5 bg-white border border-gray-100 shadow-sm rounded-2xl">
                        <p class="text-sm text-gray-500">Total akun</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900"><?= number_format(($roleStats[$selectedRole]['total'] ?? 0)) ?></p>
                    </div>
                    <div class="p-5 bg-white border border-gray-100 shadow-sm rounded-2xl">
                        <p class="text-sm text-gray-500">Akun aktif</p>
                        <p class="mt-2 text-2xl font-bold text-emerald-600"><?= number_format(($roleStats[$selectedRole]['active'] ?? 0)) ?></p>
                    </div>
                    <div class="p-5 bg-white border border-gray-100 shadow-sm rounded-2xl">
                        <p class="text-sm text-gray-500">Akun nonaktif</p>
                        <p class="mt-2 text-2xl font-bold text-gray-600"><?= number_format(($roleStats[$selectedRole]['inactive'] ?? 0)) ?></p>
                    </div>
                </div>

                <section class="p-5 mb-6 bg-white border border-gray-100 shadow-sm rounded-2xl">
                    <div class="mb-4">
                        <h2 class="text-sm font-semibold text-gray-900">Cari dan Filter <?= e($roleLabel) ?></h2>
                        <p class="mt-1 text-xs text-gray-400">Cari berdasarkan nama atau ID login, lalu filter berdasarkan status akun.</p>
                    </div>
                    <form method="GET" action="<?= e(url('/dashboard/tendik/users')) ?>"
                        class="grid grid-cols-1 gap-3 sm:grid-cols-[minmax(0,1fr)_220px_auto_auto]">
                        <input type="hidden" name="role" value="<?= e($selectedRole) ?>">
                        <input type="search" name="search" value="<?= e($filters['search']) ?>"
                            placeholder="Cari nama atau ID login..."
                            class="w-full px-4 py-3 text-sm bg-white border border-gray-200 outline-none rounded-xl placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                        <select name="status" class="w-full px-4 py-3 text-sm text-gray-700 bg-white border border-gray-200 outline-none rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                            <option value="">Semua status</option>
                            <option value="active" <?= $filters['status'] === 'active' ? 'selected' : '' ?>>Aktif</option>
                            <option value="inactive" <?= $filters['status'] === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                        <button type="submit" class="px-5 py-3 text-sm font-semibold text-white transition bg-blue-600 rounded-xl hover:bg-blue-700">Terapkan</button>
                        <a href="<?= e(url('/dashboard/tendik/users') . '?role=' . rawurlencode($selectedRole)) ?>"
                            class="inline-flex items-center justify-center px-5 py-3 text-sm font-semibold text-gray-600 transition border border-gray-200 rounded-xl hover:bg-gray-50">Reset</a>
                    </form>
                </section>

                <section class="overflow-hidden bg-white border border-gray-100 shadow-sm rounded-2xl">
                    <div class="flex flex-col gap-2 px-5 py-5 border-b border-gray-100 sm:flex-row sm:items-center sm:justify-between sm:px-7">
                        <div>
                            <h2 class="text-base font-semibold text-gray-900">Daftar <?= e($roleLabel) ?></h2>
                            <p class="mt-1 text-sm text-gray-500">Data ditampilkan 10 akun per halaman.</p>
                        </div>
                        <span class="text-xs font-medium text-gray-500">Total hasil: <?= number_format($totalItems) ?></span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[680px] text-sm">
                            <thead class="bg-gray-50/80">
                                <tr class="text-left">
                                    <th class="px-6 py-4 text-xs font-semibold tracking-wider text-gray-500 uppercase">Nama Pengguna</th>
                                    <th class="px-6 py-4 text-xs font-semibold tracking-wider text-gray-500 uppercase">ID Login</th>
                                    <th class="px-6 py-4 text-xs font-semibold tracking-wider text-gray-500 uppercase">Status Akun</th>
                                    <th class="px-6 py-4 text-xs font-semibold tracking-wider text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php if ($items !== []): ?>
                                    <?php foreach ($items as $item): ?>
                                        <tr class="transition-colors hover:bg-gray-50/70">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex items-center justify-center text-sm font-semibold text-blue-600 rounded-full h-9 w-9 shrink-0 bg-blue-50">
                                                        <?= e(strtoupper(substr((string) ($item['name'] ?? 'U'), 0, 1))) ?>
                                                    </div>
                                                    <span class="font-medium text-gray-800"><?= e((string) ($item['name'] ?? '-')) ?></span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-gray-600"><?= e((string) ($item['login_id'] ?? '-')) ?></td>
                                            <td class="px-6 py-4">
                                                <?php if (!empty($item['is_active'])): ?>
                                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-emerald-700 rounded-full bg-emerald-50"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Aktif</span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-gray-600 rounded-full bg-gray-100"><span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>Nonaktif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <a href="<?= e(url('/dashboard/tendik/users/' . (int) $item['id'])) ?>"
                                                        class="inline-flex items-center px-3 py-2 text-xs font-semibold text-blue-700 transition border border-blue-200 rounded-lg bg-blue-50 hover:bg-blue-100">Detail / Edit</a>
                                                    <form method="POST" action="<?= e(url('/dashboard/tendik/users/' . (int) $item['id'] . '/status')) ?>"
                                                        onsubmit="return confirm('Yakin ingin <?= !empty($item['is_active']) ? 'menonaktifkan' : 'mengaktifkan' ?> akun ini?');">
                                                        <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
                                                        <input type="hidden" name="status" value="<?= !empty($item['is_active']) ? 'inactive' : 'active' ?>">
                                                        <input type="hidden" name="return_role" value="<?= e($selectedRole) ?>">
                                                        <button type="submit" class="inline-flex items-center px-3 py-2 text-xs font-semibold transition border rounded-lg <?= !empty($item['is_active']) ? 'text-red-700 border-red-200 bg-red-50 hover:bg-red-100' : 'text-emerald-700 border-emerald-200 bg-emerald-50 hover:bg-emerald-100' ?>">
                                                            <?= !empty($item['is_active']) ? 'Nonaktifkan' : 'Aktifkan' ?>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="px-6 py-16 text-center">
                                            <div class="text-3xl">🔎</div>
                                            <h3 class="mt-3 text-sm font-semibold text-gray-900">Pengguna tidak ditemukan</h3>
                                            <p class="mt-1 text-sm text-gray-500">Coba ubah kata pencarian atau filter status.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex flex-col gap-3 px-5 py-4 border-t border-gray-100 sm:flex-row sm:items-center sm:justify-between sm:px-7">
                        <p class="text-xs text-gray-500">
                            <?php if ($totalItems > 0): ?>
                                Menampilkan <?= number_format((($currentPage - 1) * $perPage) + 1) ?>–<?= number_format(min($currentPage * $perPage, $totalItems)) ?> dari <?= number_format($totalItems) ?> pengguna
                            <?php else: ?>
                                Tidak ada data untuk ditampilkan
                            <?php endif; ?>
                        </p>
                        <?php if ($totalPages > 1): ?>
                            <nav class="flex items-center gap-1" aria-label="Pagination">
                                <a href="<?= e($buildPageUrl(max(1, $currentPage - 1))) ?>"
                                    class="px-3 py-2 text-xs font-semibold border rounded-lg <?= $currentPage <= 1 ? 'pointer-events-none text-gray-300 border-gray-100' : 'text-gray-600 border-gray-200 hover:bg-gray-50' ?>">Sebelumnya</a>
                                <?php
                                $startPage = max(1, $currentPage - 2);
                                $endPage = min($totalPages, $currentPage + 2);
                                for ($pageNumber = $startPage; $pageNumber <= $endPage; $pageNumber++):
                                ?>
                                    <a href="<?= e($buildPageUrl($pageNumber)) ?>"
                                        class="min-w-9 px-3 py-2 text-xs font-semibold text-center border rounded-lg <?= $pageNumber === $currentPage ? 'text-white bg-blue-600 border-blue-600' : 'text-gray-600 border-gray-200 hover:bg-gray-50' ?>"><?= $pageNumber ?></a>
                                <?php endfor; ?>
                                <a href="<?= e($buildPageUrl(min($totalPages, $currentPage + 1))) ?>"
                                    class="px-3 py-2 text-xs font-semibold border rounded-lg <?= $currentPage >= $totalPages ? 'pointer-events-none text-gray-300 border-gray-100' : 'text-gray-600 border-gray-200 hover:bg-gray-50' ?>">Berikutnya</a>
                            </nav>
                        <?php endif; ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php include __DIR__ . '/../../../../components/dashboard/tendik/Footer.php'; ?>
        </div>
    </main>
</body>

</html>