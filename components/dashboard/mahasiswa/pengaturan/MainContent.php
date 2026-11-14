<?php

$errors = $errors ?? [];
$success = $success ?? null;

?>

<section class="px-4 py-6 sm:px-6 lg:px-8">

    <div class="mx-auto max-w-8xl space-y-6">

        <!-- Heading -->
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500">
                <span>Dashboard</span>
                <span>/</span>
                <span class="font-medium text-blue-600">Pengaturan</span>
            </div>

            <h1 class="mt-3 text-2xl font-bold text-slate-800">
                Pengaturan Akun
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Kelola keamanan dan kata sandi akun Anda.
            </p>
        </div>

        <!-- Success -->
        <?php if ($success): ?>
            <div class="flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="mt-0.5 h-5 w-5 shrink-0"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    aria-hidden="true">
                    <circle cx="12" cy="12" r="10" />
                    <path d="m9 12 2 2 4-4" />
                </svg>

                <p class="text-sm font-medium">
                    <?= e($success) ?>
                </p>
            </div>
        <?php endif; ?>

        <!-- Security Card -->
        <div class="rounded-2xl border border-blue-100 bg-white p-5 shadow-sm sm:p-6">

            <div class="flex items-center gap-4">

                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true">
                        <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z" />
                        <path d="m9 12 2 2 4-4" />
                    </svg>

                </div>

                <div>
                    <h2 class="font-semibold text-slate-800">
                        Keamanan Akun
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Jaga kerahasiaan kata sandi untuk melindungi akun Anda.
                    </p>
                </div>

            </div>
        </div>

        <!-- Password Form -->
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-100 px-5 py-5 sm:px-6">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true">
                            <rect x="3" y="11" width="18" height="11" rx="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            <circle cx="12" cy="16.5" r="1" />
                        </svg>

                    </div>

                    <div>
                        <h2 class="font-semibold text-slate-800">
                            Ubah Kata Sandi
                        </h2>

                        <p class="mt-1 text-xs text-slate-500">
                            Perbarui kata sandi akun Anda.
                        </p>
                    </div>

                </div>
            </div>

            <form method="POST" action="" class="space-y-5 p-5 sm:p-6">

                <!-- CSRF Token -->
                <input
                    type="hidden"
                    name="_csrf_token"
                    value="<?= e(csrfToken()) ?>">

                <!-- Password Lama -->
                <div>
                    <label for="current_password"
                        class="mb-2 block text-sm font-medium text-slate-700">
                        Kata Sandi Saat Ini
                    </label>

                    <div class="relative">

                        <input
                            type="password"
                            name="current_password"
                            id="current_password"
                            autocomplete="current-password"
                            placeholder="Masukkan kata sandi saat ini"
                            required
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 pr-12 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 <?= isset($errors['current_password']) ? 'border-red-400' : '' ?>">

                        <button
                            type="button"
                            data-password-toggle="current_password"
                            class="absolute inset-y-0 right-0 flex items-center px-4 text-slate-400 hover:text-blue-600"
                            aria-label="Tampilkan kata sandi">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true">
                                <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>

                        </button>
                    </div>

                    <?php if (isset($errors['current_password'])): ?>
                        <p class="mt-2 text-xs text-red-600">
                            <?= e($errors['current_password']) ?>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Password Baru -->
                <div>
                    <label for="new_password"
                        class="mb-2 block text-sm font-medium text-slate-700">
                        Kata Sandi Baru
                    </label>

                    <div class="relative">

                        <input
                            type="password"
                            name="new_password"
                            id="new_password"
                            autocomplete="new-password"
                            placeholder="Minimal 8 karakter"
                            minlength="8"
                            required
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 pr-12 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 <?= isset($errors['new_password']) ? 'border-red-400' : '' ?>">

                        <button
                            type="button"
                            data-password-toggle="new_password"
                            class="absolute inset-y-0 right-0 flex items-center px-4 text-slate-400 hover:text-blue-600"
                            aria-label="Tampilkan kata sandi">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true">
                                <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>

                        </button>
                    </div>

                    <?php if (isset($errors['new_password'])): ?>
                        <p class="mt-2 text-xs text-red-600">
                            <?= e($errors['new_password']) ?>
                        </p>
                    <?php endif; ?>

                    <p class="mt-2 text-xs text-slate-400">
                        Minimal 8 karakter.
                    </p>
                </div>

                <!-- Konfirmasi -->
                <div>
                    <label for="new_password_confirmation"
                        class="mb-2 block text-sm font-medium text-slate-700">
                        Konfirmasi Kata Sandi Baru
                    </label>

                    <div class="relative">

                        <input
                            type="password"
                            name="new_password_confirmation"
                            id="new_password_confirmation"
                            autocomplete="new-password"
                            placeholder="Ulangi kata sandi baru"
                            required
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 pr-12 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 <?= isset($errors['new_password_confirmation']) ? 'border-red-400' : '' ?>">

                        <button
                            type="button"
                            data-password-toggle="new_password_confirmation"
                            class="absolute inset-y-0 right-0 flex items-center px-4 text-slate-400 hover:text-blue-600"
                            aria-label="Tampilkan kata sandi">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true">
                                <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>

                        </button>
                    </div>

                    <?php if (isset($errors['new_password_confirmation'])): ?>
                        <p class="mt-2 text-xs text-red-600">
                            <?= e($errors['new_password_confirmation']) ?>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Panduan -->
                <div class="rounded-xl bg-slate-50 p-4">

                    <p class="mb-3 text-xs font-semibold text-slate-700">
                        Panduan kata sandi
                    </p>

                    <ul class="space-y-2 text-xs text-slate-500">

                        <li class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 shrink-0 text-emerald-500"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true">
                                <path d="m5 12 4 4L19 6" />
                            </svg>
                            Minimal 8 karakter.
                        </li>

                        <li class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 shrink-0 text-emerald-500"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true">
                                <path d="m5 12 4 4L19 6" />
                            </svg>
                            Gunakan kata sandi yang sulit ditebak.
                        </li>

                        <li class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 shrink-0 text-emerald-500"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true">
                                <path d="m5 12 4 4L19 6" />
                            </svg>
                            Jangan bagikan kata sandi kepada orang lain.
                        </li>

                    </ul>
                </div>

                <!-- Tombol -->
                <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">

                    <button
                        type="reset"
                        class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                        Reset
                    </button>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z" />
                            <path d="M17 21v-8H7v8M7 3v5h8" />
                        </svg>

                        Simpan Perubahan
                    </button>

                </div>

            </form>
        </div>

    </div>

    <!-- Tampilan -->
    <?php include __DIR__ . '/Tampilan.php'; ?>

</section>