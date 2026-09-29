<?php
// Daftar peran: [nilai, label, path ikon]
$peran = [
    ['mahasiswa', 'Mahasiswa',
        '<path d="M4.26 10.15a60 60 0 00-.49 6.35 49 49 0 018.23 2.5 49 49 0 018.23-2.5c-.1-2.2-.26-4.3-.49-6.35M12 3L1.5 8.6 12 14.2l10.5-5.6L12 3z"/>'],
    ['dosen', 'Dosen / Koordinator',
        '<path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.1a7.5 7.5 0 0115 0"/>'],
    ['tendik', 'Tendik / Manajemen',
        '<path d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.4c0-.6.5-1.1 1.1-1.1h3.8c.6 0 1.1.5 1.1 1.1V21"/>'],
    ['mitra', 'Mitra / Perusahaan',
        '<path d="M20.25 14.15v4.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25v-4.25m16.5 0a2.25 2.25 0 00.75-1.7V8.9a2.25 2.25 0 00-2.25-2.25h-3.5m5 7.5H3.75m0 0a2.25 2.25 0 01-.75-1.7V8.9a2.25 2.25 0 012.25-2.25h3.5m0 0V5.25A2.25 2.25 0 0110 3h4a2.25 2.25 0 012.25 2.25v1.4m-8.5 0h8.5"/>'],
];

$error = $_GET['error'] ?? '';
?>

    <form action="" method="POST" class="mt-6">

        <!-- Pilihan peran -->
        <input type="hidden" name="role" id="role" value="mahasiswa">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2" role="radiogroup" aria-label="Peran">
            <?php foreach ($peran as $i => [$nilai, $label, $icon]) : ?>
                <button type="button" data-role="<?= $nilai ?>" role="radio"
                    aria-checked="<?= $i === 0 ? 'true' : 'false' ?>"
                    class="flex flex-col items-center justify-center gap-2 rounded-xl px-2 py-4 text-xs font-medium text-center transition
                    <?= $i === 0 ? 'bg-blue-600 text-white shadow-md' : 'bg-blue-50 text-slate-600 hover:bg-blue-100' ?>">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><?= $icon ?></svg>
                    <?= $label ?>
                </button>
            <?php endforeach; ?>
        </div>

        <?php if ($error) : ?>
            <div class="mt-5 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-600" role="alert">
                Email/username atau password salah. Periksa kembali lalu coba lagi.
            </div>
        <?php endif; ?>

        <!-- Email / Username -->
        <label for="username" class="mt-6 block text-sm font-medium">Email / Username</label>
        <div class="relative mt-2">
            <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.24a2.25 2.25 0 01-1.07 1.92l-7.5 4.62a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.92V6.75"/></svg>
            <input type="text" id="username" name="username" required autocomplete="username"
                placeholder="Masukkan email atau username"
                class="w-full rounded-lg border border-slate-200 bg-white py-3.5 pl-12 pr-4 text-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
        </div>

        <!-- Password -->
        <label for="password" class="mt-5 block text-sm font-medium">Password</label>
        <div class="relative mt-2">
            <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
            <input type="password" id="password" name="password" required autocomplete="current-password"
                placeholder="Masukkan password"
                class="w-full rounded-lg border border-slate-200 bg-white py-3.5 pl-12 pr-12 text-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
            <button type="button" id="togglePassword" aria-label="Tampilkan password"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-blue-600 transition">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M2.04 12.32a1 1 0 010-.64C3.42 7.51 7.36 4.5 12 4.5c4.64 0 8.57 3.01 9.96 7.18.07.21.07.43 0 .64C20.58 16.49 16.64 19.5 12 19.5c-4.64 0-8.57-3.01-9.96-7.18z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </button>
        </div>

        <!-- Ingat saya + Lupa password -->
        <div class="mt-4 flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-200">
                Ingat saya
            </label>
            <a href="" class="text-blue-600 hover:text-blue-700 transition">Lupa password?</a>
        </div>

        <!-- Tombol masuk -->
        <button type="submit"
            class="mt-6 flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 py-4 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition">
            Masuk <span>→</span>
        </button>

        <!-- Info -->
        <div class="mt-5 flex items-start gap-3 rounded-lg bg-blue-50/70 px-4 py-4 text-xs leading-5 text-slate-600">
            <svg class="mt-0.5 h-5 w-5 shrink-0 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M2.25 12a9.75 9.75 0 1119.5 0 9.75 9.75 0 01-19.5 0zm8.93-4.19a.75.75 0 011.14-.55c.4.27.68.7.68 1.2 0 .8-.65 1.44-1.44 1.44a.75.75 0 01-.38-1.4zM10.5 11.25a.75.75 0 000 1.5h.75v3.75h-.75a.75.75 0 000 1.5h3a.75.75 0 000-1.5h-.75v-4.5a.75.75 0 00-.75-.75h-1.5z" clip-rule="evenodd"/></svg>
            <p>
                Gunakan akun yang telah diberikan oleh pihak kampus.<br>
                Jika mengalami kendala, silakan hubungi admin kampus.
            </p>
        </div>

    </form>