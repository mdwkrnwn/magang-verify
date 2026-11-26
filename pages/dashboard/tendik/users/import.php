<?php

$base = url('/');
$active = 'users';

$error = $error ?? null;
$success = $success ?? null;
$previewRows = $previewRows ?? [];
$importResult = $importResult ?? null;

?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . '/../../../../components/dashboard/tendik/Head.php'; ?>

<style>
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
                    <a href="<?= e(url('/dashboard/tendik/users/create')) ?>"
                        class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 transition hover:text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Tambah User
                    </a>

                    <div class="mt-4">
                        <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Import User melalui CSV</h1>
                        <p class="mt-2 text-sm leading-6 text-gray-500">
                            Tambahkan beberapa akun sekaligus menggunakan file CSV sesuai format yang disediakan.
                        </p>
                    </div>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="px-4 py-3 mb-5 text-sm text-red-700 border border-red-200 rounded-xl bg-red-50" role="alert">
                        <?= e($error) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="px-4 py-3 mb-5 text-sm border rounded-xl border-emerald-200 bg-emerald-50 text-emerald-700" role="status">
                        <?= e($success) ?>
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 gap-6 xl:grid-cols-5">
                    <section class="space-y-6 xl:col-span-3">
                        <div class="p-5 bg-white border shadow-sm rounded-2xl border-slate-200 sm:p-7">
                            <div class="flex items-start gap-3">
                                <div class="p-3 text-blue-600 rounded-xl bg-blue-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h8m-8 4h8m-8 4h5M6 3h9l4 4v14H6a2 2 0 01-2-2V5a2 2 0 012-2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-base font-semibold text-gray-900">1. Unduh template CSV</h2>
                                    <p class="mt-1 text-sm text-gray-500">Gunakan susunan kolom yang sudah ditentukan agar data dapat dibaca sistem.</p>
                                </div>
                            </div>

                            <a href="<?= e(url('/dashboard/tendik/users/import/template')) ?>"
                                class="mt-5 inline-flex items-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-4 py-2.5 text-sm font-semibold text-blue-700 transition hover:bg-blue-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0l-4-4m4 4l4-4M4 17v3h16v-3" />
                                </svg>
                                Download Template CSV
                            </a>
                        </div>

                        <div class="p-5 bg-white border shadow-sm rounded-2xl border-slate-200 sm:p-7">
                            <div class="flex items-start gap-3">
                                <div class="p-3 rounded-xl bg-emerald-50 text-emerald-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16.5V19a2 2 0 002 2h12a2 2 0 002-2v-2.5M7 10l5 5 5-5M12 15V3" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-base font-semibold text-gray-900">2. Upload file CSV</h2>
                                    <p class="mt-1 text-sm text-gray-500">Pilih file CSV yang sudah diisi berdasarkan template.</p>
                                </div>
                            </div>

                            <form method="POST" action="<?= e(url('/dashboard/tendik/users/import')) ?>" enctype="multipart/form-data" class="mt-5">
                                <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
                                <label for="csv_file" class="block mb-2 text-sm font-medium text-gray-700">File CSV</label>
                                <input id="csv_file" name="csv_file" type="file" accept=".csv,text/csv" required
                                    class="block w-full text-sm text-gray-700 bg-white border rounded-xl border-slate-200 file:mr-4 file:border-0 file:bg-slate-100 file:px-4 file:py-3 file:text-sm file:font-semibold file:text-gray-700 hover:file:bg-slate-200">
                                <p class="mt-2 text-xs text-gray-400">Format yang diterima: .csv. Disarankan menggunakan UTF-8.</p>

                                <button type="submit" name="action" value="preview"
                                    class="inline-flex items-center justify-center w-full gap-2 px-5 py-3 mt-5 text-sm font-semibold text-white transition bg-blue-600 rounded-xl hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200 sm:w-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Upload dan Preview Data
                                </button>
                            </form>
                        </div>
                    </section>

                    <aside class="xl:col-span-2">
                        <div class="p-5 bg-white border shadow-sm rounded-2xl border-slate-200 sm:p-7">
                            <h2 class="text-base font-semibold text-gray-900">Panduan pengisian CSV</h2>
                            <div class="mt-4 space-y-4 text-sm">
                                <div>
                                    <p class="font-semibold text-gray-800">Kolom wajib berdasarkan role</p>
                                    <ul class="mt-2 space-y-1.5 text-gray-600">
                                        <li><span class="font-medium text-gray-800">Semua role:</span> nama, email, role.</li>
                                        <li><span class="font-medium text-gray-800">Mahasiswa:</span> NIM, program_studi, angkatan.</li>
                                        <li><span class="font-medium text-gray-800">Dosen:</span> NIDN, no_telepon.</li>
                                        <li><span class="font-medium text-gray-800">Koordinator Magang:</span> NIDN, no_telepon.</li>
                                        <li><span class="font-medium text-gray-800">Tendik:</span> NIP, no_telepon.</li>
                                    </ul>
                                </div>
                                <div class="pt-4 border-t border-slate-100">
                                    <p class="font-semibold text-gray-800">Nilai kolom role</p>
                                    <p class="mt-1 text-gray-600">Gunakan salah satu nilai berikut secara persis:</p>
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        <?php foreach (['mahasiswa', 'dosen', 'koordinator_magang', 'tendik'] as $role): ?>
                                            <code class="px-2 py-1 text-xs rounded-md bg-slate-100 text-slate-700"><?= e($role) ?></code>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="pt-4 border-t border-slate-100">
                                    <p class="font-semibold text-gray-800">Password awal</p>
                                    <p class="mt-1 text-gray-600">Dibuat otomatis dari NIM, NIDN, atau NIP sesuai role. Password disimpan dalam bentuk hash.</p>
                                </div>
                                <div class="p-3 text-xs leading-5 border rounded-xl border-amber-200 bg-amber-50 text-amber-800">
                                    Kolom yang tidak berlaku untuk suatu role harus dikosongkan. Jangan mengubah nama atau urutan header template.
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>

                <?php if (!empty($importResult) && is_array($importResult)): ?>
                    <section class="mt-6 overflow-hidden bg-white border shadow-sm rounded-2xl border-slate-200">
                        <div class="px-5 py-5 border-b border-slate-100 sm:px-7">
                            <h2 class="text-base font-semibold text-gray-900">Hasil Import</h2>
                            <div class="flex flex-wrap gap-3 mt-4 text-sm">
                                <span class="px-3 py-2 font-medium rounded-lg bg-emerald-50 text-emerald-700">Berhasil: <?= e((string) $importResult['success']) ?></span>
                                <span class="px-3 py-2 font-medium text-red-700 rounded-lg bg-red-50">Gagal: <?= e((string) $importResult['failed']) ?></span>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm divide-y divide-slate-200">
                                <thead class="text-xs font-semibold tracking-wide text-left uppercase bg-slate-50 text-slate-500">
                                    <tr>
                                        <th class="px-4 py-3">Baris</th>
                                        <th class="px-4 py-3">Nama</th>
                                        <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <?php foreach ($importResult['details'] as $detail): ?>
                                        <tr>
                                            <td class="px-4 py-3 text-gray-500"><?= e((string) $detail['line']) ?></td>
                                            <td class="px-4 py-3 font-medium text-gray-800"><?= e($detail['name']) ?></td>
                                            <td class="px-4 py-3 <?= $detail['status'] === 'success' ? 'text-emerald-700' : 'text-red-700' ?> font-medium"><?= $detail['status'] === 'success' ? 'Berhasil' : 'Gagal' ?></td>
                                            <td class="px-4 py-3 text-gray-600"><?= e($detail['message']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                <?php endif; ?>

                <?php if (!empty($previewRows)): ?>
                    <?php $validCount = count(array_filter($previewRows, static fn($row) => !empty($row['valid']))); ?>
                    <?php $invalidCount = count($previewRows) - $validCount; ?>
                    <section class="mt-6 overflow-hidden bg-white border shadow-sm rounded-2xl border-slate-200">
                        <div class="px-5 py-5 border-b border-slate-100 sm:px-7">
                            <h2 class="text-base font-semibold text-gray-900">Preview Data</h2>
                            <p class="mt-1 text-sm text-gray-500">Periksa hasil validasi setiap baris. Belum ada akun yang disimpan ke database.</p>
                            <div class="flex flex-wrap gap-3 mt-4 text-sm">
                                <span class="px-3 py-2 font-medium rounded-lg bg-emerald-50 text-emerald-700">Valid: <?= e((string) $validCount) ?></span>
                                <span class="px-3 py-2 font-medium text-red-700 rounded-lg bg-red-50">Tidak valid: <?= e((string) $invalidCount) ?></span>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm divide-y divide-slate-200">
                                <thead class="text-xs font-semibold tracking-wide text-left uppercase bg-slate-50 text-slate-500">
                                    <tr>
                                        <th class="px-4 py-3">Baris</th>
                                        <th class="px-4 py-3">Nama</th>
                                        <th class="px-4 py-3">Email</th>
                                        <th class="px-4 py-3">Role</th>
                                        <th class="px-4 py-3">Identitas</th>
                                        <th class="px-4 py-3">Status validasi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <?php foreach ($previewRows as $preview): ?>
                                        <?php
                                        $data = $preview['data'];
                                        $identity = $data['nim'] ?: ($data['nidn'] ?: $data['nip']);
                                        ?>
                                        <tr class="align-top">
                                            <td class="px-4 py-3 text-gray-500 whitespace-nowrap"><?= e((string) $preview['line']) ?></td>
                                            <td class="px-4 py-3 font-medium text-gray-800 whitespace-nowrap"><?= e($data['nama']) ?></td>
                                            <td class="px-4 py-3 text-gray-600 whitespace-nowrap"><?= e($data['email']) ?></td>
                                            <td class="px-4 py-3 text-gray-600 whitespace-nowrap"><?= e($data['role']) ?></td>
                                            <td class="px-4 py-3 text-gray-600 whitespace-nowrap"><?= e($identity) ?></td>
                                            <td class="px-4 py-3 min-w-64">
                                                <?php if (!empty($preview['valid'])): ?>
                                                    <span class="font-medium text-emerald-700">Valid</span>
                                                <?php else: ?>
                                                    <span class="font-medium text-red-700">Perlu diperbaiki</span>
                                                    <ul class="mt-1 text-xs leading-5 text-red-600 list-disc list-inside">
                                                        <?php foreach ($preview['errors'] as $rowError): ?>
                                                            <li><?= e($rowError) ?></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if ($validCount > 0 && $invalidCount === 0): ?>
                            <div class="flex flex-col gap-3 px-5 py-4 border-t border-slate-100 bg-slate-50/70 sm:flex-row sm:items-center sm:justify-between sm:px-7">
                                <p class="text-sm text-gray-500">Semua baris valid. Periksa kembali sebelum membuat akun.</p>
                                <form method="POST" action="<?= e(url('/dashboard/tendik/users/import')) ?>" onsubmit="return confirm('Buat semua akun dari data CSV ini?');">
                                    <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
                                    <button type="submit" name="action" value="confirm" class="inline-flex w-full items-center justify-center rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700 sm:w-auto">
                                        Konfirmasi dan Import <?= e((string) $validCount) ?> User
                                    </button>
                                </form>
                            </div>
                        <?php else: ?>
                            <div class="px-5 py-4 text-sm text-red-700 border-t border-slate-100 bg-slate-50/70 sm:px-7">
                                Perbaiki semua baris yang tidak valid, lalu upload ulang file CSV sebelum melakukan import.
                            </div>
                        <?php endif; ?>
                    </section>
                <?php endif; ?>

                <?php include __DIR__ . '/../../../../components/dashboard/tendik/Footer.php'; ?>
            </div>
        </div>
    </main>
</body>

</html>