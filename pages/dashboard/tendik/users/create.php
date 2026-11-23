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
                                Identitas NIM, NIDN, atau NIP menjadi identitas login akun; password tetap digunakan saat masuk.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

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

                            <!-- Student Identity -->
                            <div id="nim-field" hidden>
                                <label for="nim" class="mb-2 block text-sm font-medium text-gray-700">
                                    NIM <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="nim"
                                    name="nim"
                                    type="text"
                                    value="<?= e($form['nim'] ?? '') ?>"
                                    minlength="3"
                                    maxlength="30"
                                    placeholder="Masukkan NIM"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                <p class="mt-1.5 text-xs text-gray-400">
                                    NIM disimpan pada profil mahasiswa dan digunakan sebagai identitas login.
                                </p>
                            </div>

                            <!-- Lecturer Identity -->
                            <div id="nidn-field" hidden>
                                <label for="nidn" class="mb-2 block text-sm font-medium text-gray-700">
                                    NIDN <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="nidn"
                                    name="nidn"
                                    type="text"
                                    value="<?= e($form['nidn'] ?? '') ?>"
                                    minlength="3"
                                    maxlength="30"
                                    placeholder="Masukkan NIDN"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                <p class="mt-1.5 text-xs text-gray-400">
                                    NIDN digunakan bersama untuk role Dosen dan Koordinator Magang.
                                </p>
                            </div>

                            <!-- Staff Identity -->
                            <div id="nip-field" hidden>
                                <label for="nip" class="mb-2 block text-sm font-medium text-gray-700">
                                    NIP <span class="text-red-500">*</span>
                                </label>
                                <input
                                    id="nip"
                                    name="nip"
                                    type="text"
                                    value="<?= e($form['nip'] ?? '') ?>"
                                    maxlength="30"
                                    placeholder="Masukkan NIP"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                <p class="mt-1.5 text-xs text-gray-400">
                                    NIP disimpan pada profil tendik dan digunakan sebagai identitas login.
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
                                Pilih Mahasiswa, Dosen, Tendik, atau Dosen bersama Koordinator Magang.
                            </p>
                        </div>

                        <fieldset>
                            <legend class="sr-only">Role pengguna</legend>

                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <?php foreach ($roles as $roleOption): ?>
                                    <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 px-4 py-3.5 transition hover:border-blue-300 hover:bg-blue-50/40" data-role-label="<?= e($roleOption) ?>">
                                        <input
                                            type="checkbox"
                                            name="roles[]"
                                            value="<?= e($roleOption) ?>"
                                            data-role-option="<?= e($roleOption) ?>"
                                            <?= in_array($roleOption, (array) ($form['roles'] ?? []), true) ? 'checked' : '' ?>
                                            class="h-4 w-4 shrink-0 rounded border-gray-300 text-blue-600 focus:ring-blue-500">

                                        <span class="text-sm font-medium text-gray-700">
                                            <?= e($roleOption) ?>
                                        </span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                            <p id="role-selection-error" class="mt-3 text-sm text-red-600" role="alert" hidden>
                                Pilih Mahasiswa, Dosen, Tendik, atau Dosen bersama Koordinator Magang.
                            </p>
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

    <script>
        (() => {
            const form = document.querySelector('form[action*="/dashboard/tendik/users/create"]');
            if (!form) return;

            const roleInputs = Array.from(form.querySelectorAll('[data-role-option]'));
            const nimField = document.getElementById('nim-field');
            const nidnField = document.getElementById('nidn-field');
            const nipField = document.getElementById('nip-field');
            const nimInput = document.getElementById('nim');
            const nidnInput = document.getElementById('nidn');
            const nipInput = document.getElementById('nip');
            const roleError = document.getElementById('role-selection-error');
            const byRole = (role) => roleInputs.find((input) => input.value === role);

            const updateRoles = (changedRole) => {
                const mahasiswa = byRole('mahasiswa');
                const dosen = byRole('dosen');
                const koordinator = byRole('koordinator_magang');
                const tendik = byRole('tendik');

                if (changedRole === 'koordinator_magang' && koordinator.checked) {
                    dosen.checked = true;
                }
                if (changedRole === 'dosen' && !dosen.checked) {
                    koordinator.checked = false;
                }
                if (changedRole === 'tendik' && tendik.checked) {
                    mahasiswa.checked = false;
                    dosen.checked = false;
                    koordinator.checked = false;
                } else if (changedRole === 'mahasiswa' && mahasiswa.checked) {
                    dosen.checked = false;
                    koordinator.checked = false;
                    tendik.checked = false;
                } else if ((changedRole === 'dosen' && dosen.checked) || (changedRole === 'koordinator_magang' && koordinator.checked)) {
                    mahasiswa.checked = false;
                    tendik.checked = false;
                } else if (mahasiswa.checked) {
                    dosen.checked = false;
                    koordinator.checked = false;
                    tendik.checked = false;
                } else if (tendik.checked) {
                    mahasiswa.checked = false;
                    dosen.checked = false;
                    koordinator.checked = false;
                }

                dosen.disabled = mahasiswa.checked;
                koordinator.disabled = mahasiswa.checked || tendik.checked;
                tendik.disabled = false;
                nimField.hidden = !mahasiswa.checked;
                nidnField.hidden = !dosen.checked;
                nipField.hidden = !tendik.checked;
                nimInput.required = mahasiswa.checked;
                nidnInput.required = dosen.checked;
                nipInput.required = tendik.checked;
                nimInput.disabled = !mahasiswa.checked;
                nidnInput.disabled = !dosen.checked;
                nipInput.disabled = !tendik.checked;
            };

            roleInputs.forEach((input) => input.addEventListener('change', () => updateRoles(input.value)));
            updateRoles('');

            form.addEventListener('submit', (event) => {
                const selected = roleInputs.filter((input) => input.checked).map((input) => input.value);
                const supported = selected.length === 1
                    && ['mahasiswa', 'dosen', 'tendik'].includes(selected[0])
                    || selected.length === 2
                    && selected.includes('dosen')
                    && selected.includes('koordinator_magang');

                roleError.hidden = supported;
                if (!supported) {
                    event.preventDefault();
                }
            });
        })();
    </script>

</body>

</html>