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

    <main class="min-h-screen bg-slate-50 pt-20 lg:ml-64">
        <div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

            <div class="mx-auto w-full">

                <!-- Page Header -->
                <div class="mb-6">
                    <a
                        href="<?= e(url('/dashboard/tendik/users')) ?>"
                        class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 transition hover:text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Manajemen User
                    </a>

                    <div class="mt-4">
                        <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">
                            Tambah User
                        </h1>
                        <p class="mt-2 text-sm leading-6 text-gray-500">
                            Buat akun baru dengan mengisi informasi pengguna dan menentukan role yang akan diberikan.
                        </p>
                    </div>
                </div>

                <!-- Alert -->
                <?php if (!empty($error)): ?>
                    <div class="mb-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                        </svg>
                        <p><?= e($error) ?></p>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="mb-5 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.003 12.003 0 003 9c0 5.591 3.824 10.29 9 11.622C17.176 19.29 21 14.591 21 9c0-1.042-.133-2.053-.382-3.016z" />
                        </svg>
                        <p><?= e($success) ?></p>
                    </div>
                <?php endif; ?>

                <!-- Form -->
                <form
                    method="POST"
                    action="<?= e(url('/dashboard/tendik/users/create')) ?>"
                    class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <input
                        type="hidden"
                        name="_csrf_token"
                        value="<?= e(csrfToken()) ?>">

                    <!-- Account Information -->
                    <div class="border-b border-slate-100 px-5 py-5 sm:px-7">
                        <div class="mb-6">
                            <h2 class="text-base font-semibold text-gray-900">
                                Informasi Akun
                            </h2>
                            <p class="mt-1 text-xs text-gray-500">
                                Masukkan identitas dan informasi login pengguna.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            <!-- Login ID -->
                            <div>
                                <label for="login_id" class="mb-2 block text-sm font-medium text-gray-700">
                                    ID Login <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="login_id"
                                    name="login_id"
                                    type="text"
                                    value="<?= e($form['login_id'] ?? '') ?>"
                                    required
                                    minlength="3"
                                    maxlength="50"
                                    placeholder="Masukkan ID login"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                <p class="mt-1.5 text-xs text-gray-400">
                                    Minimal 3 karakter.
                                </p>
                            </div>

                            <!-- Name -->
                            <div>
                                <label for="name" class="mb-2 block text-sm font-medium text-gray-700">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="name"
                                    name="name"
                                    type="text"
                                    value="<?= e($form['name'] ?? '') ?>"
                                    required
                                    maxlength="150"
                                    placeholder="Masukkan nama lengkap"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                            </div>

                            <!-- NIM -->
                            <div>
                                <label for="nim" class="mb-2 block text-sm font-medium text-gray-700">
                                    NIM
                                </label>
                                <input
                                    id="nim"
                                    name="nim"
                                    type="text"
                                    value="<?= e($form['nim'] ?? '') ?>"
                                    maxlength="30"
                                    placeholder="Masukkan NIM jika diperlukan"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                <p class="mt-1.5 text-xs text-gray-400">
                                    Diisi jika akun memiliki role mahasiswa.
                                </p>
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="mb-2 block text-sm font-medium text-gray-700">
                                    Password Awal <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    minlength="8"
                                    autocomplete="new-password"
                                    placeholder="Minimal 8 karakter"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                <p class="mt-1.5 text-xs text-gray-400">
                                    Password akan disimpan dalam bentuk hash.
                                </p>
                            </div>

                        </div>
                    </div>

                    <!-- Role Selection -->
                    <div class="px-5 py-5 sm:px-7">
                        <div class="mb-4">
                            <h2 class="text-base font-semibold text-gray-900">
                                Hak Akses Pengguna
                            </h2>
                            <p class="mt-1 text-xs text-gray-500">
                                Pilih satu atau beberapa role sesuai kebutuhan akun.
                            </p>
                        </div>

                        <fieldset>
                            <legend class="sr-only">Role pengguna</legend>

                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <?php foreach ($roles as $roleOption): ?>
                                    <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 px-4 py-3.5 transition hover:border-blue-300 hover:bg-blue-50/40">
                                        <input
                                            type="checkbox"
                                            name="roles[]"
                                            value="<?= e($roleOption) ?>"
                                            <?= in_array($roleOption, (array) ($form['roles'] ?? []), true) ? 'checked' : '' ?>
                                            class="h-4 w-4 shrink-0 rounded border-gray-300 text-blue-600 focus:ring-blue-500">

                                        <span class="text-sm font-medium text-gray-700">
                                            <?= e($roleOption) ?>
                                        </span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </fieldset>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col-reverse gap-3 border-t border-slate-100 bg-slate-50/70 px-5 py-4 sm:flex-row sm:justify-end sm:px-7">
                        <a
                            href="<?= e(url('/dashboard/tendik/users')) ?>"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-slate-50">
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Simpan User
                        </button>
                    </div>

                </form>

            </div>

            <?php include __DIR__ . '/../../../../components/dashboard/tendik/Footer.php'; ?>

        </div>
    </main>

</body>

</html>