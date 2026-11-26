<?php

$base = url('/');
$active = 'users';

$roleLabels = [
    'mahasiswa' => 'Mahasiswa',
    'dosen' => 'Dosen',
    'koordinator_magang' => 'Koordinator Magang',
    'tendik' => 'Tenaga Kependidikan',
];

$currentRole = (string) ($form['role'] ?? '');
?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . '/../../../../components/dashboard/tendik/Head.php'; ?>

<style>
    /* Pastikan atribut hidden tetap berlaku meskipun elemen memakai class Tailwind seperti grid. */
    [hidden] {
        display: none !important;
    }
</style>

<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">

    <?php include __DIR__ . '/../../../../components/dashboard/tendik/Sidebar.php'; ?>
    <?php include __DIR__ . '/../../../../components/dashboard/shared/Header.php'; ?>

    <main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">
        <div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">
            <div class="w-full mx-auto">

                <div class="mb-6">
                    <a href="<?= e(url('/dashboard/tendik/users')) ?>"
                        class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 transition hover:text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Manajemen User
                    </a>

                    <div class="flex flex-col gap-4 mt-4 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Tambah User</h1>
                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Buat satu akun pengguna secara manual atau import beberapa akun sekaligus melalui CSV.
                            </p>
                        </div>

                        <a href="<?= e(url('/dashboard/tendik/users/import')) ?>"
                            class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-4 py-2.5 text-sm font-semibold text-blue-700 transition hover:border-blue-300 hover:bg-blue-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 0L8 8m4-4l4 4M4 16v3a1 1 0 001 1h14a1 1 0 001-1v-3" />
                            </svg>
                            Import CSV
                        </a>
                    </div>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="flex items-start gap-3 px-4 py-3 mb-5 text-sm text-red-700 border border-red-200 rounded-xl bg-red-50">
                        <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                        </svg>
                        <p><?= e($error) ?></p>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="flex items-start gap-3 px-4 py-3 mb-5 text-sm border rounded-xl border-emerald-200 bg-emerald-50 text-emerald-700">
                        <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.003 12.003 0 003 9c0 5.591 3.824 10.29 9 11.622C17.176 19.29 21 14.591 21 9c0-1.042-.133-2.053-.382-3.016z" />
                        </svg>
                        <p><?= e($success) ?></p>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= e(url('/dashboard/tendik/users/create')) ?>"
                    class="overflow-hidden bg-white border shadow-sm rounded-2xl border-slate-200">
                    <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">

                    <div class="px-5 py-5 border-b border-slate-100 sm:px-7">
                        <div class="mb-6">
                            <h2 class="text-base font-semibold text-gray-900">Informasi Akun</h2>
                            <p class="mt-1 text-xs text-gray-500">
                                Masukkan nama dan email pengguna. Identitas login serta password awal dibuat berdasarkan role.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                            <div>
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-700">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input id="name" name="name" type="text" value="<?= e($form['name'] ?? '') ?>"
                                    required maxlength="150" placeholder="Masukkan nama lengkap"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                            </div>

                            <div>
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-700">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input id="email" name="email" type="email" value="<?= e($form['email'] ?? '') ?>"
                                    required maxlength="150" autocomplete="email" placeholder="nama@email.com"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                            </div>

                            <div class="sm:col-span-2">
                                <label for="role" class="block mb-2 text-sm font-medium text-gray-700">
                                    Role Pengguna <span class="text-red-500">*</span>
                                </label>
                                <select id="role" name="role" required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                    <option value="">Pilih role pengguna</option>
                                    <?php foreach ($roles as $roleOption): ?>
                                        <option value="<?= e($roleOption) ?>" <?= $currentRole === $roleOption ? 'selected' : '' ?>>
                                            <?= e($roleLabels[$roleOption] ?? ucfirst(str_replace('_', ' ', $roleOption))) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <p class="mt-1.5 text-xs text-gray-400">
                                    Setiap akun hanya memiliki satu role. Akun Mitra dikelola oleh Koordinator Magang.
                                </p>
                            </div>

                            <div id="nim-field" class="sm:col-span-2" hidden>
                                <label for="nim" class="block mb-2 text-sm font-medium text-gray-700">
                                    NIM <span class="text-red-500">*</span>
                                </label>
                                <input id="nim" name="nim" type="text" value="<?= e($form['nim'] ?? '') ?>"
                                    minlength="3" maxlength="30" placeholder="Masukkan NIM"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                <p class="mt-1.5 text-xs text-gray-400">NIM menjadi identitas login dan password awal mahasiswa.</p>
                            </div>

                            <div id="nidn-field" class="sm:col-span-2" hidden>
                                <label for="nidn" class="block mb-2 text-sm font-medium text-gray-700">
                                    NIDN <span class="text-red-500">*</span>
                                </label>
                                <input id="nidn" name="nidn" type="text" value="<?= e($form['nidn'] ?? '') ?>"
                                    minlength="3" maxlength="30" placeholder="Masukkan NIDN"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                <p class="mt-1.5 text-xs text-gray-400">NIDN menjadi identitas login dan password awal dosen atau koordinator magang.</p>
                            </div>

                            <div id="no-telepon-field" class="sm:col-span-2" hidden>
                                <label for="no_telepon" class="block mb-2 text-sm font-medium text-gray-700">
                                    Nomor Telepon <span class="text-red-500">*</span>
                                </label>
                                <input id="no_telepon" name="no_telepon" type="tel"
                                    value="<?= e($form['no_telepon'] ?? '') ?>" maxlength="20"
                                    inputmode="tel" autocomplete="tel" placeholder="Contoh: 081234567890"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                <p class="mt-1.5 text-xs text-gray-400">Nomor telepon disimpan pada profil dosen atau tenaga kependidikan.</p>
                            </div>

                            <div id="nip-field" class="sm:col-span-2" hidden>
                                <label for="nip" class="block mb-2 text-sm font-medium text-gray-700">
                                    NIP <span class="text-red-500">*</span>
                                </label>
                                <input id="nip" name="nip" type="text" value="<?= e($form['nip'] ?? '') ?>"
                                    maxlength="30" placeholder="Masukkan NIP"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                <p class="mt-1.5 text-xs text-gray-400">NIP menjadi identitas login dan password awal tenaga kependidikan.</p>
                            </div>

                            <div id="student-extra-fields" class="grid grid-cols-1 gap-5 sm:col-span-2 sm:grid-cols-2" hidden>
                                <div>
                                    <label for="program_studi" class="block mb-2 text-sm font-medium text-gray-700">
                                        Program Studi <span class="text-red-500">*</span>
                                    </label>
                                    <input id="program_studi" name="program_studi" type="text"
                                        value="<?= e($form['program_studi'] ?? '') ?>" maxlength="150"
                                        placeholder="Masukkan program studi"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                </div>
                                <div>
                                    <label for="angkatan" class="block mb-2 text-sm font-medium text-gray-700">
                                        Angkatan <span class="text-red-500">*</span>
                                    </label>
                                    <input id="angkatan" name="angkatan" type="number"
                                        value="<?= e($form['angkatan'] ?? '') ?>" min="1900" max="2100"
                                        placeholder="Contoh: 2024"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-5 py-5 border-b border-slate-100 bg-blue-50/60 sm:px-7">
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c0 1.105-.895 2-2 2s-2-.895-2-2  .895-2 2-2 2 .895 2 2zm0 0v2m0-9a7 7 0 100 14 7 7 0 000-14z" />
                            </svg>
                            <div>
                                <h2 class="text-sm font-semibold text-gray-900">Password Awal Dibuat Otomatis</h2>
                                <p class="mt-1 text-xs leading-5 text-gray-600">
                                    Password awal mengikuti identitas akun: NIM untuk Mahasiswa, NIDN untuk Dosen dan Koordinator Magang, serta NIP untuk Tendik. Password disimpan dalam bentuk hash.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col-reverse gap-3 px-5 py-4 border-t border-slate-100 bg-slate-50/70 sm:flex-row sm:justify-end sm:px-7">
                        <a href="<?= e(url('/dashboard/tendik/users')) ?>"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-slate-50">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
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
            const roleSelect = document.getElementById('role');
            if (!roleSelect) return;

            const fields = {
                mahasiswa: ['nim-field', 'student-extra-fields'],
                dosen: ['nidn-field', 'no-telepon-field'],
                koordinator_magang: ['nidn-field', 'no-telepon-field'],
                tendik: ['nip-field', 'no-telepon-field'],
            };

            const inputIds = ['nim', 'nidn', 'nip', 'program_studi', 'angkatan', 'no_telepon'];

            const updateRoleFields = () => {
                const selectedRole = roleSelect.value;
                const visibleIds = fields[selectedRole] || [];

                ['nim-field', 'nidn-field', 'nip-field', 'student-extra-fields', 'no-telepon-field'].forEach((fieldId) => {
                    const field = document.getElementById(fieldId);
                    if (field) field.hidden = !visibleIds.includes(fieldId);
                });

                inputIds.forEach((inputId) => {
                    const input = document.getElementById(inputId);
                    if (!input) return;

                    const isRequired = selectedRole === 'mahasiswa' ?
                        ['nim', 'program_studi', 'angkatan'].includes(inputId) :
                        selectedRole === 'dosen' || selectedRole === 'koordinator_magang' ?
                        inputId === 'nidn' :
                        selectedRole === 'tendik' ?
                        ['nip', 'no_telepon'].includes(inputId) :
                        false;

                    const isStaffPhone = ['dosen', 'koordinator_magang', 'tendik'].includes(selectedRole) && inputId === 'no_telepon';

                    const isRelevant = isRequired || isStaffPhone;
                    input.required = isRequired || isStaffPhone;
                    input.disabled = !isRelevant;
                });
            };

            roleSelect.addEventListener('change', updateRoleFields);
            updateRoleFields();
        })();
    </script>

</body>

</html>