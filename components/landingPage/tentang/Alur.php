<section class="bg-slate-50/70 py-14 sm:py-16 border-y border-slate-100">

    <div class="max-w-7xl mx-auto px-4 sm:px-6">

        <div class="text-center max-w-2xl mx-auto">

            <p class="text-sm font-medium text-blue-600">
                Cara Kerja
            </p>

            <h2 class="mt-2 text-2xl sm:text-3xl font-bold text-slate-900">
                Dari profil hingga peluang magang
            </h2>

        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mt-10">

            <?php

            $alur = [
                ['01', 'Bangun Profil', 'Mahasiswa melengkapi informasi diri, keahlian, dan pengalaman.'],
                ['02', 'Verifikasi', 'Informasi mahasiswa dapat melalui proses verifikasi kampus.'],
                ['03', 'Temukan Peluang', 'Mahasiswa dapat melihat mitra dan posisi magang yang tersedia.'],
                ['04', 'Terhubung', 'Mahasiswa dan mitra dapat terhubung melalui proses magang.'],
            ];

            ?>

            <?php foreach ($alur as [$nomor, $judul, $deskripsi]): ?>

                <article class="relative bg-white rounded-2xl border border-slate-100 p-5 sm:p-6">

                    <span class="text-3xl font-bold text-blue-100">
                        <?= e($nomor) ?>
                    </span>

                    <h3 class="mt-3 font-semibold text-slate-900">
                        <?= e($judul) ?>
                    </h3>

                    <p class="mt-2 text-sm text-slate-500 leading-6">
                        <?= e($deskripsi) ?>
                    </p>

                </article>

            <?php endforeach; ?>

        </div>

    </div>

</section>