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
        <div class="px-4 py-6 mx-auto sm:px-6 sm:py-8 lg:px-8 max-w-[900px]">
            <a href="<?= e(url('/dashboard/tendik/users')) ?>" class="text-sm text-blue-600">← Kembali</a>
            <h1 class="mt-3 text-2xl font-bold text-gray-900 sm:text-3xl">Tambah User</h1>
            <p class="mt-2 text-sm text-gray-500">Buat akun baru dan pilih satu atau beberapa role.</p>
            <?php if (!empty($error)): ?>
                <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700"><?= e($error) ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700"><?= e($success) ?></div>
            <?php endif; ?>
            <form method="POST" action="<?= e(url('/dashboard/tendik/users/create')) ?>" class="mt-6 space-y-5 bg-white border border-gray-100 rounded-2xl shadow-sm p-5 sm:p-7">
                <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
                <div>
                    <label for="login_id" class="block mb-2 text-sm font-medium text-gray-700">ID Login</label>
                    <input id="login_id" name="login_id" value="<?= e($form['login_id'] ?? '') ?>" required minlength="3" maxlength="50" class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-blue-500" />
                </div>
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-700">Nama</label>
                    <input id="name" name="name" value="<?= e($form['name'] ?? '') ?>" required maxlength="150" class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-blue-500" />
                </div>
                <div>
                    <label for="nim" class="block mb-2 text-sm font-medium text-gray-700">NIM (untuk role mahasiswa)</label>
                    <input id="nim" name="nim" value="<?= e($form['nim'] ?? '') ?>" maxlength="30" class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-blue-500" />
                </div>
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password awal</label>
                    <input id="password" type="password" name="password" required minlength="8" autocomplete="new-password" class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-blue-500" />
                    <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter; disimpan sebagai hash.</p>
                </div>
                <fieldset>
                    <legend class="mb-2 text-sm font-medium text-gray-700">Role</legend>
                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                        <?php foreach ($roles as $roleOption): ?>
                            <label class="flex items-center gap-3 px-4 py-3 text-sm border border-gray-200 rounded-xl hover:border-blue-200">
                                <input type="checkbox" name="roles[]" value="<?= e($roleOption) ?>" <?= in_array($roleOption, (array) ($form['roles'] ?? []), true) ? 'checked' : '' ?> />
                                <span><?= e($roleOption) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </fieldset>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700">Simpan User</button>
            </form>
            <?php include __DIR__ . '/../../../../components/dashboard/tendik/Footer.php'; ?>
        </div>
    </main>
</body>
</html>
