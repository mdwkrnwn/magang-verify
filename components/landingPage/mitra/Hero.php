<section class="bg-gradient-to-b from-blue-950 via-blue-900 to-blue-700">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-12 sm:py-16 text-center">

        <h1 class="text-3xl sm:text-4xl font-bold leading-tight text-white">
            Direktori Mitra Industri
        </h1>

        <p class="mt-4 text-sm sm:text-base leading-relaxed text-blue-100">
            Kenali perusahaan dan instansi yang bekerja sama dengan POLINEMA
            dan membuka kesempatan magang bagi mahasiswa. Temukan mitra
            berdasarkan bidang, lokasi, dan skema magang.
        </p>

        <!-- Search (ikut dikirim bersama filter karena satu form di index.php) -->
        <div class="relative mt-8 max-w-xl mx-auto">

            <label for="q" class="sr-only">Cari nama mitra</label>

            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">
                <?= icon('search') ?>
            </span>

            <input
                id="q"
                name="q"
                value="<?= e($fQ) ?>"
                placeholder="Cari nama mitra..."
                class="w-full h-12 pl-10 pr-24 rounded-md bg-white text-sm text-slate-800
                focus:outline-none focus:ring-2 focus:ring-blue-300"
            >

            <button
                type="submit"
                class="absolute right-1.5 top-1/2 -translate-y-1/2 h-9 px-4 rounded
                bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition"
            >
                Cari
            </button>

        </div>

    </div>
</section>