<?php


$active = 'generate-login';

require_once __DIR__ . '/../../../../function/Helpers.php';

$base = url('/');
?>

<?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Head.php'; ?>

<body class="bg-slate-50 font-[Poppins] text-slate-800 overflow-x-hidden">

    <?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Sidebar.php'; ?>

    <!-- MAIN CONTENT -->
    <main class="lg:ml-64 pt-20 min-h-screen">

        <!-- CONTENT WRAPPER -->
        <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <?php include __DIR__ . '/../../../../components/dashboard/koordinatorMagang/Header.php'; ?>

            <div class="space-y-6">

                <!-- PAGE HEADER -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                    <div>
                        <p class="text-sm font-medium text-blue-600 mb-1">
                            Manajemen Mitra
                        </p>

                        <h1 class="text-2xl md:text-3xl font-bold text-slate-800">
                            Generate Login Mitra
                        </h1>

                        <p class="text-sm text-slate-500 mt-2 max-w-2xl">
                            Buat link akses sementara agar mitra dapat masuk ke dashboard
                            tanpa menggunakan username dan password.
                        </p>
                    </div>

                    <!-- DURASI LINK -->
                    <!-- <div class="flex items-center gap-2 bg-white border border-slate-200 rounded-xl px-4 py-3 shadow-sm shrink-0">

                        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 15v2m-7-3V7a7 7 0 1 1 14 0v7m-14 0h14a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2z" />
                            </svg>
                        </div>

                        <div>
                            <p class="text-xs text-slate-500">
                                Masa berlaku link
                            </p>
                            <p class="font-semibold text-slate-800">
                                15 menit
                            </p>
                        </div>

                    </div> -->

                </div>

                <!-- ALERT ERROR -->
                <?php if (!empty($error)): ?>
                    <div class="flex gap-3 items-start p-4 rounded-xl border border-red-200 bg-red-50 text-red-700">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 mt-0.5 shrink-0"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                        </svg>

                        <p class="text-sm font-medium">
                            <?= e($error) ?>
                        </p>

                    </div>
                <?php endif; ?>

                <!-- ALERT SUCCESS -->
                <?php if (!empty($success)): ?>
                    <div class="p-4 rounded-xl border border-blue-200 bg-blue-50 text-blue-800">

                        <div class="flex gap-3 items-start">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5 mt-0.5 shrink-0"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>

                            <div class="flex-1">

                                <p class="font-semibold">
                                    <?= e($success) ?>
                                </p>

                                <?php if (!empty($generatedLink)): ?>
                                    <p class="text-sm mt-1">
                                        Link akses untuk
                                        <strong><?= e($generatedMitraName ?? 'Mitra') ?></strong>
                                        berhasil dibuat.
                                    </p>
                                <?php endif; ?>

                            </div>

                        </div>

                        <!-- GENERATED LINK -->
                        <?php if (!empty($generatedLink)): ?>
                            <div class="mt-4">

                                <label class="text-xs font-semibold text-blue-700 mb-2 block">
                                    Link akses mitra
                                </label>

                                <div class="flex flex-col sm:flex-row gap-2">

                                    <input
                                        type="text"
                                        id="generated-link"
                                        readonly
                                        value="<?= e($generatedLink) ?>"
                                        class="flex-1 bg-white border border-blue-200 rounded-lg px-3 py-2.5 text-sm text-slate-700 focus:outline-none">

                                    <button
                                        type="button"
                                        onclick="copyGeneratedLink()"
                                        class="px-4 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition flex items-center justify-center gap-2">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-4 h-4"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M8 16H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2m-6 8h8a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2h-8a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2z" />
                                        </svg>

                                        <span id="copy-button-text">
                                            Salin Link
                                        </span>

                                    </button>

                                </div>

                                <p class="text-xs text-blue-700 mt-2">
                                    Link hanya berlaku selama 15 menit dan hanya dapat digunakan satu kali.
                                </p>

                            </div>
                        <?php endif; ?>

                    </div>
                <?php endif; ?>

                <!-- INFO CARD -->
                <div class="bg-white rounded-2xl border border-slate-200 p-5 md:p-6 shadow-sm">

                    <div class="flex items-start gap-4">

                        <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                            </svg>
                        </div>

                        <div class="flex-1 min-w-0">

                            <h3 class="font-semibold text-slate-800">
                                Cara kerja akses mitra
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">

                                <!-- STEP 1 -->
                                <div class="flex gap-3">

                                    <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        1
                                    </div>

                                    <div>
                                        <p class="text-sm font-medium text-slate-700">
                                            Generate link
                                        </p>

                                        <p class="text-xs text-slate-500 mt-1">
                                            Buat link akses untuk mitra yang dipilih.
                                        </p>
                                    </div>

                                </div>

                                <!-- STEP 2 -->
                                <div class="flex gap-3">

                                    <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        2
                                    </div>

                                    <div>
                                        <p class="text-sm font-medium text-slate-700">
                                            Bagikan link
                                        </p>

                                        <p class="text-xs text-slate-500 mt-1">
                                            Kirim link kepada mitra melalui WhatsApp atau email.
                                        </p>
                                    </div>

                                </div>

                                <!-- STEP 3 -->
                                <div class="flex gap-3">

                                    <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        3
                                    </div>

                                    <div>
                                        <p class="text-sm font-medium text-slate-700">
                                            Mitra masuk
                                        </p>

                                        <p class="text-xs text-slate-500 mt-1">
                                            Mitra membuka link untuk memperoleh sesi akses dashboard.
                                        </p>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- LIST MITRA -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

                    <!-- TABLE HEADER -->
                    <div class="p-5 md:p-6 border-b border-slate-100">

                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                            <div>
                                <h2 class="text-lg font-bold text-slate-800">
                                    Daftar Mitra
                                </h2>

                                <p class="text-sm text-slate-500 mt-1">
                                    Kelola link akses untuk masing-masing mitra.
                                </p>
                            </div>

                            <!-- SEARCH -->
                            <div class="relative w-full md:w-72">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M21 21l-4.35-4.35M11 19a8 8 0 1 0 0-16 8 8 0 0 0 0 16z" />
                                </svg>

                                <input
                                    type="text"
                                    id="search-mitra"
                                    placeholder="Cari nama mitra..."
                                    class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">

                            </div>

                        </div>

                    </div>

                    <?php if (empty($mitraList)): ?>

                        <!-- EMPTY STATE -->
                        <div class="py-16 text-center px-6">

                            <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-8 h-8"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 7h18M3 12h18M3 17h18" />
                                </svg>
                            </div>

                            <h3 class="font-semibold text-slate-700">
                                Belum ada mitra aktif
                            </h3>

                            <p class="text-sm text-slate-500 mt-1">
                                Tambahkan mitra terlebih dahulu untuk membuat link login.
                            </p>

                        </div>

                    <?php else: ?>

                        <!-- TABLE -->
                        <div class="overflow-x-auto">

                            <table class="w-full min-w-[900px]">

                                <thead class="bg-slate-50">

                                    <tr class="text-left text-xs uppercase tracking-wide text-slate-500">

                                        <th class="px-6 py-4 font-semibold">
                                            Mitra
                                        </th>

                                        <th class="px-6 py-4 font-semibold">
                                            Kode
                                        </th>

                                        <th class="px-6 py-4 font-semibold">
                                            Status Akses
                                        </th>

                                        <th class="px-6 py-4 font-semibold">
                                            Link Akses
                                        </th>

                                        <th class="px-6 py-4 font-semibold">
                                            Terakhir Dibuat
                                        </th>

                                        <th class="px-6 py-4 font-semibold text-center">
                                            Aksi
                                        </th>

                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-slate-100">

                                    <?php foreach ($mitraList as $mitra): ?>

                                        <?php
                                        $status = $mitra['access_status'] ?? 'belum_dibuat';

                                        $statusConfig = [
                                            'belum_dibuat' => [
                                                'label' => 'Belum dibuat',
                                                'class' => 'bg-slate-100 text-slate-600'
                                            ],
                                            'aktif' => [
                                                'label' => 'Link aktif',
                                                'class' => 'bg-blue-100 text-blue-700'
                                            ],
                                            'sudah_digunakan' => [
                                                'label' => 'Sudah digunakan',
                                                'class' => 'bg-blue-100 text-blue-700'
                                            ],
                                            'kedaluwarsa' => [
                                                'label' => 'Kedaluwarsa',
                                                'class' => 'bg-amber-100 text-amber-700'
                                            ],
                                            'dicabut' => [
                                                'label' => 'Dicabut',
                                                'class' => 'bg-red-100 text-red-700'
                                            ]
                                        ];

                                        $config = $statusConfig[$status] ?? $statusConfig['belum_dibuat'];
                                        ?>

                                        <tr
                                            class="mitra-row hover:bg-slate-50 transition"
                                            data-search="<?= e(strtolower($mitra['nama_perusahaan'] . ' ' . ($mitra['kode_perusahaan'] ?? ''))) ?>">

                                            <!-- MITRA -->
                                            <td class="px-6 py-4">

                                                <div class="flex items-center gap-3">

                                                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm shrink-0">
                                                        <?= e(strtoupper(substr($mitra['nama_perusahaan'], 0, 1))) ?>
                                                    </div>

                                                    <div>
                                                        <p class="font-semibold text-slate-800 text-sm">
                                                            <?= e($mitra['nama_perusahaan']) ?>
                                                        </p>

                                                        <p class="text-xs text-slate-500 mt-0.5">
                                                            <?= e($mitra['kategori'] ?? '-') ?>
                                                        </p>
                                                    </div>

                                                </div>

                                            </td>

                                            <!-- KODE -->
                                            <td class="px-6 py-4">
                                                <span class="text-sm text-slate-600 font-mono">
                                                    <?= e($mitra['kode_perusahaan'] ?? '-') ?>
                                                </span>
                                            </td>

                                            <!-- STATUS -->
                                            <td class="px-6 py-4">

                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold <?= e($config['class']) ?>">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                                    <?= e($config['label']) ?>
                                                </span>

                                                <?php if (!empty($mitra['expires_at']) && $status === 'aktif'): ?>
                                                    <p class="text-xs text-slate-400 mt-1">
                                                        Exp:
                                                        <?= e(date('d/m/Y H:i', strtotime($mitra['expires_at']))) ?>
                                                    </p>
                                                <?php endif; ?>

                                            </td>

                                            <!-- LINK AKSES -->
                                            <td class="px-6 py-4 min-w-[280px]">

                                                <?php if (!empty($mitra['access_link'])): ?>

                                                    <div class="flex items-center gap-2">

                                                        <a
                                                            href="<?= e($mitra['access_link']) ?>"
                                                            target="_blank"
                                                            class="text-xs font-medium text-blue-600 hover:text-blue-800 hover:underline truncate max-w-[200px]"
                                                            title="<?= e($mitra['access_link']) ?>">
                                                            Buka Link
                                                        </a>

                                                        <button
                                                            type="button"
                                                            onclick="copyAccessLink(this, <?= htmlspecialchars(json_encode($mitra['access_link']), ENT_QUOTES, 'UTF-8') ?>)"
                                                            class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-blue-100 hover:text-blue-700 transition whitespace-nowrap">
                                                            Salin
                                                        </button>

                                                    </div>

                                                <?php else: ?>

                                                    <span class="text-xs text-slate-400">
                                                        Link belum tersedia
                                                    </span>

                                                <?php endif; ?>

                                            </td>

                                            <!-- CREATED -->
                                            <td class="px-6 py-4">

                                                <p class="text-sm text-slate-600">
                                                    <?php if (!empty($mitra['access_created_at'])): ?>
                                                        <?= e(date('d/m/Y', strtotime($mitra['access_created_at']))) ?>
                                                    <?php else: ?>
                                                        <span class="text-slate-400">-</span>
                                                    <?php endif; ?>
                                                </p>

                                            </td>




                                            <!-- ACTION -->
                                            <td class="px-6 py-4">

                                                <div class="flex justify-end items-center gap-2">

                                                    <!-- GENERATE -->
                                                    <form method="POST"
                                                        action="<?= e(url('/dashboard/koordinator-magang/generate-login')) ?>">

                                                        <input type="hidden"
                                                            <input type="hidden"
                                                            name="_csrf_token"
                                                            value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                                                        <input type="hidden"
                                                            name="action"
                                                            value="generate">

                                                        <input type="hidden"
                                                            name="mitra_id"
                                                            value="<?= (int)$mitra['id'] ?>">

                                                        <button type="submit"
                                                            class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 transition whitespace-nowrap">

                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="w-4 h-4"
                                                                fill="none"
                                                                viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 4v16m8-8H4" />
                                                            </svg>

                                                            <?= in_array($status, ['aktif', 'sudah_digunakan', 'kedaluwarsa', 'dicabut'], true)
                                                                ? 'Generate Ulang'
                                                                : 'Generate' ?>

                                                        </button>

                                                    </form>

                                                    <!-- CABUT -->
                                                    <?php if ($status === 'aktif'): ?>

                                                        <form method="POST"
                                                            action="<?= e(url('/dashboard/koordinator-magang/generate-login')) ?>"
                                                            onsubmit="return confirm('Cabut link akses mitra ini?');">

                                                            <input type="hidden"
                                                                name="_csrf_token"
                                                                value="<?= e($_SESSION['csrf_token'] ?? '') ?>">

                                                            <input type="hidden"
                                                                name="action"
                                                                value="revoke">

                                                            <input type="hidden"
                                                                name="mitra_id"
                                                                value="<?= (int)$mitra['id'] ?>">

                                                            <button type="submit"
                                                                class="px-3 py-2 rounded-lg text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 transition whitespace-nowrap">
                                                                Cabut
                                                            </button>

                                                        </form>

                                                    <?php endif; ?>

                                                </div>

                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                </tbody>

                            </table>

                        </div>

                        <!-- SEARCH EMPTY -->
                        <div id="search-empty"
                            class="hidden py-12 text-center text-sm text-slate-500">
                            Mitra tidak ditemukan.
                        </div>

                    <?php endif; ?>

                </div>

                <!-- FOOTER NOTE -->
                <div class="flex items-start gap-3 px-1">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4 text-slate-400 mt-0.5 shrink-0"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                    </svg>

                    <p class="text-xs text-slate-500">
                        Link akses bersifat sementara. Setelah digunakan, mitra akan memiliki akses sesi
                        yang terpisah dari link tersebut.
                    </p>

                </div>

            </div>

        </div>

    </main>

    <!-- SCRIPT -->
    <script>
        function copyGeneratedLink() {
            const input = document.getElementById('generated-link');
            const buttonText = document.getElementById('copy-button-text');

            if (!input) return;

            navigator.clipboard.writeText(input.value)
                .then(() => {
                    if (buttonText) {
                        buttonText.textContent = 'Berhasil disalin!';

                        setTimeout(() => {
                            buttonText.textContent = 'Salin Link';
                        }, 2000);
                    }
                })
                .catch(() => {
                    input.select();
                    document.execCommand('copy');

                    if (buttonText) {
                        buttonText.textContent = 'Berhasil disalin!';

                        setTimeout(() => {
                            buttonText.textContent = 'Salin Link';
                        }, 2000);
                    }
                });
        }

        // SEARCH MITRA
        const searchInput = document.getElementById('search-mitra');
        const mitraRows = document.querySelectorAll('.mitra-row');
        const searchEmpty = document.getElementById('search-empty');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const keyword = this.value.toLowerCase().trim();
                let visibleCount = 0;

                mitraRows.forEach(row => {
                    const text = row.dataset.search || '';
                    const visible = text.includes(keyword);

                    row.classList.toggle('hidden', !visible);

                    if (visible) visibleCount++;
                });

                if (searchEmpty) {
                    searchEmpty.classList.toggle('hidden', visibleCount !== 0);
                }
            });
        }

        async function copyAccessLink(button, link) {
            try {
                await navigator.clipboard.writeText(link);

                const originalText = button.innerText;
                button.innerText = 'Tersalin!';
                button.classList.add('bg-green-100', 'text-green-700');

                setTimeout(() => {
                    button.innerText = originalText;
                    button.classList.remove('bg-green-100', 'text-green-700');
                }, 1500);

            } catch (error) {
                alert('Gagal menyalin link. Silakan salin secara manual.');
            }
        }
    </script>

</body>

</html>