
<?php
$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';
?>

<section class="relative z-10 order-1 mx-auto w-full min-w-0 max-w-xl lg:order-2 lg:ml-auto">

    <div class="w-full min-w-0 rounded-2xl bg-white/90 p-5 shadow-xl shadow-blue-900/5 ring-1 ring-blue-50 sm:rounded-3xl sm:p-8 lg:p-10">

        <!-- Icon -->
        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-100 text-blue-600">

            <svg
                class="h-7 w-7"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
                stroke-linecap="round"
                stroke-linejoin="round"
                viewBox="0 0 24 24"
                aria-hidden="true">

                <path d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75" />
                <rect x="3.75" y="10.5" width="16.5" height="10.5" rx="2.25" />
                <path d="M12 14.25v3" />

            </svg>

        </div>

        <!-- Judul -->
        <h2 class="mt-5 text-2xl font-bold leading-tight text-slate-900 sm:text-3xl">
            Lupa Password?
        </h2>

        <p class="mt-2 text-sm leading-6 text-slate-500">
            Masukkan NIM atau NIP yang terdaftar pada akun MagangVerify.
            Kami akan mengirimkan tautan pemulihan ke email Anda.
        </p>

        <!-- Pesan error -->
        <?php if ($error !== '') : ?>

            <div
                class="mt-5 w-full rounded-lg bg-red-50 px-4 py-3 text-sm leading-5 text-red-600"
                role="alert">

                <?php if ($error === 'invalid') : ?>
                    Format identitas tidak valid. Periksa kembali NIM atau NIP Anda.
                <?php elseif ($error === 'csrf') : ?>
                    Sesi tidak valid. Silakan muat ulang halaman.
                <?php else : ?>
                    Terjadi kesalahan. Silakan coba kembali.
                <?php endif; ?>

            </div>

        <?php endif; ?>

        <!-- Pesan berhasil -->
        <?php if ($success === 'sent') : ?>

            <div
                class="mt-5 w-full rounded-lg bg-green-50 px-4 py-3 text-sm leading-6 text-green-700"
                role="status">

                Jika akun ditemukan dan memenuhi persyaratan,
                instruksi pemulihan password akan dikirimkan ke email terdaftar.
                Silakan periksa kotak masuk atau folder spam.

            </div>

        <?php endif; ?>

        <!-- Form -->
        <form
            action=""
            method="POST"
            class="mt-7 w-full">

            <input
                type="hidden"
                name="_csrf_token"
                value="<?= htmlspecialchars(csrfToken(), ENT_QUOTES, 'UTF-8') ?>">

            <!-- Identitas -->
            <label
                for="identity"
                class="block text-sm font-medium text-slate-700">

                NIM / NIP

            </label>

            <div class="relative mt-2">

                <!-- Icon -->
                <svg
                    class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-500"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    viewBox="0 0 24 24"
                    aria-hidden="true">

                    <path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                    <path d="M4.5 20.1a7.5 7.5 0 0115 0" />

                </svg>

                <input
                    type="text"
                    id="identity"
                    name="identity"
                    required
                    autocomplete="username"
                    maxlength="50"
                    placeholder="Masukkan NIM atau NIP"
                    class="block w-full min-w-0 rounded-lg border border-slate-200 bg-white py-3.5 pl-12 pr-4 text-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">

            </div>

            <!-- Informasi -->
            <div class="mt-5 flex w-full items-start gap-3 rounded-lg bg-blue-50/70 px-3 py-3 text-xs leading-5 text-slate-600 sm:px-4 sm:py-4">

                <svg
                    class="mt-0.5 h-5 w-5 shrink-0 text-blue-600"
                    fill="currentColor"
                    viewBox="0 0 24 24"
                    aria-hidden="true">

                    <path
                        fill-rule="evenodd"
                        d="M2.25 12a9.75 9.75 0 1119.5 0 9.75 9.75 0 01-19.5 0zm9-4.5a.75.75 0 00-.75.75v.75a.75.75 0 001.5 0v-.75a.75.75 0 00-.75-.75zm0 4.5a.75.75 0 00-.75.75v3a.75.75 0 001.5 0v-3a.75.75 0 00-.75-.75z"
                        clip-rule="evenodd" />

                </svg>

                <p class="min-w-0 flex-1">
                    Pastikan NIM atau NIP yang dimasukkan sesuai dengan identitas
                    akun yang terdaftar di MagangVerify.
                </p>

            </div>

            <!-- Tombol -->
            <button
                type="submit"
                id="resetRequestButton"
                class="mt-6 flex min-h-12 w-full items-center justify-center gap-2 rounded-lg bg-blue-600 py-3.5 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-75">

                <span id="resetRequestButtonText">
                    Kirim Tautan Pemulihan
                </span>

                <svg
                    id="resetRequestArrow"
                    class="h-4 w-4 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    viewBox="0 0 24 24">

                    <path d="M5 12h14" />
                    <path d="M13 6l6 6-6 6" />

                </svg>

            </button>

        </form>

        <!-- Kembali ke login -->
        <div class="mt-6 border-t border-slate-100 pt-5">

            <a
                href="<?= e(url('/login')) ?>"
                class="flex items-center justify-center gap-2 text-sm font-medium text-slate-500 transition hover:text-blue-600">

                <svg
                    class="h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    viewBox="0 0 24 24"
                    aria-hidden="true">

                    <path d="M19 12H5" />
                    <path d="M12 19l-7-7 7-7" />

                </svg>

                Kembali ke halaman login

            </a>

        </div>

    </div>

</section>
