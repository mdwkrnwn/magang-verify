<?php

$base = url('/');

$active = 'users';

?>
<!DOCTYPE html>
<html lang="id">
<?php include __DIR__ . '/../../../../components/dashboard/tendik/Head.php'; ?>
<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">
    <?php include __DIR__ . '/../../../../components/dashboard/tendik/Sidebar.php'; ?>
    <?php include __DIR__ . '/../../../../components/dashboard/tendik/Header.php'; ?>
    <main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">
        <div class="px-4 py-6 mx-auto sm:px-6 sm:py-8 lg:px-8 max-w-[1600px]">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between sm:mb-8">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Manajemen User</h1>
                    <p class="mt-2 text-sm text-gray-500 sm:text-base">Kelola akun dan role pengguna aplikasi.</p>
                </div>
                <a href="<?= e(url('/dashboard/tendik/users/create')) ?>" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700">Tambah User</a>
            </div>
            <form method="GET" action="<?= e(url('/dashboard/tendik/users')) ?>" class="mb-6 grid grid-cols-1 gap-3 sm:grid-cols-[1fr_220px_auto] bg-white border border-gray-100 rounded-2xl shadow-sm p-4 sm:p-5">
                <input type="search" name="search" value="<?= e($filters['search'] ?? '') ?>" placeholder="Cari ID login atau nama" class="px-4 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-blue-500" />
                <select name="role" class="px-4 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-blue-500">
                    <option value="">Semua role</option>
                    <?php foreach ($roles as $roleOption): ?>
                        <option value="<?= e($roleOption) ?>" <?= (($filters['role'] ?? '') === $roleOption) ? 'selected' : '' ?>><?= e($roleOption) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="px-4 py-2.5 text-sm font-semibold text-blue-700 border border-blue-100 rounded-xl hover:bg-blue-50">Filter</button>
            </form>
            <?php if (!empty($error)): ?>
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700"><?= e($error) ?></div>
            <?php endif; ?>
            <section class="overflow-hidden bg-white border border-gray-100 shadow-sm rounded-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[720px] text-sm">
                        <thead class="bg-gray-50 text-left">
                            <tr>
                                <th class="px-5 py-3 font-semibold text-gray-600">ID Login</th>
                                <th class="px-5 py-3 font-semibold text-gray-600">Nama</th>
                                <th class="px-5 py-3 font-semibold text-gray-600">Role</th>
                                <th class="px-5 py-3 font-semibold text-gray-600">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if (!empty($items)): ?>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td class="px-5 py-4 font-medium text-gray-900"><?= e((string) ($item['login_id'] ?? '')) ?></td>
                                        <td class="px-5 py-4 text-gray-700"><?= e((string) ($item['name'] ?? '')) ?></td>
                                        <td class="px-5 py-4">
                                            <div class="flex flex-wrap gap-1.5">
                                                <?php $rolesValue = $item['roles'] ?? []; if (is_string($rolesValue)) { $rolesValue = trim($rolesValue, '{}') === '' ? [] : explode(',', trim($rolesValue, '{}')); } ?>
                                                <?php foreach ((array) $rolesValue as $itemRole): ?>
                                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-blue-50 text-blue-700"><?= e(trim((string) $itemRole, '" ')) ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 text-gray-700"><?= !empty($item['is_active']) ? 'Aktif' : 'Nonaktif' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="px-5 py-12 text-center text-sm text-gray-500">Belum ada user yang cocok dengan filter.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
            <?php include __DIR__ . '/../../../../components/dashboard/tendik/Footer.php'; ?>
        </div>
    </main>
</body>
</html>
