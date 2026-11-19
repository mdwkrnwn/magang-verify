<?php

$token = $_GET['token'] ?? '';
$status = $_GET['status'] ?? '';

$validTokenFormat = is_string($token)
    && preg_match('/^[a-f0-9]{64}$/i', $token);

$csrf = csrfToken();
?>

<div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 sm:p-8">

    <?php if (!$validTokenFormat || $status === 'invalid'): ?>

        <div class="text-center">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-red-50 text-red-500">
                <svg class="w-7 h-7" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>

            <h2 class="text-lg font-bold text-slate-800">
                Tautan Tidak Valid
            </h2>

            <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                Tautan reset password tidak valid, sudah digunakan,
                atau telah kedaluwarsa.
            </p>

            <a href="<?= htmlspecialchars(url('/forgot-password'), ENT_QUOTES, 'UTF-8') ?>"
                class="mt-6 inline-flex w-full justify-center rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition">
                Minta Tautan Baru
            </a>
        </div>

    <?php else: ?>

        <?php if ($status === 'error'): ?>
            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                Password tidak valid atau konfirmasi password tidak cocok.
                Silakan coba kembali.
            </div>
        <?php endif; ?>

        <form
            id="resetPasswordForm"
            action="<?= htmlspecialchars(url('/reset-password'), ENT_QUOTES, 'UTF-8') ?>"
            method="POST"
            class="space-y-5">

            <!-- CSRF Token -->
            <input
                type="hidden"
                name="_csrf_token"
                value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">

            <!-- Reset Token -->
            <input
                type="hidden"
                name="token"
                value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">

            <!-- Password Baru -->
            <div>
                <label
                    for="password"
                    class="block text-sm font-semibold text-slate-700 mb-2">
                    Password Baru
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    minlength="8"
                    autocomplete="new-password"
                    placeholder="Minimal 8 karakter"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label
                    for="password_confirmation"
                    class="block text-sm font-semibold text-slate-700 mb-2">
                    Konfirmasi Password Baru
                </label>

                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                    minlength="8"
                    autocomplete="new-password"
                    placeholder="Ulangi password baru"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            </div>

            <!-- Submit -->
            <button
                type="submit"
                class="w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition">
                Simpan Password Baru
            </button>

        </form>

        <p class="mt-5 text-center text-xs text-slate-400">
            Password harus terdiri dari minimal 8 karakter.
        </p>

    <?php endif; ?>

</div>