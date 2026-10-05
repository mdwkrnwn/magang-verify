<section class="bg-white py-14 sm:py-16">

    <div class="max-w-7xl mx-auto px-4 sm:px-6">

        <div class="max-w-2xl">

            <p class="text-sm font-medium text-blue-600">
                Fitur Utama
            </p>

            <h2 class="mt-2 text-2xl sm:text-3xl font-bold text-slate-900">
                Dirancang untuk mendukung perjalanan magang
            </h2>

            <p class="mt-4 text-sm sm:text-base text-slate-500 leading-6">
                Berbagai fitur dalam MagangVerify membantu setiap pihak
                mengelola informasi yang dibutuhkan dalam satu platform.
            </p>

        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 mt-10">

            <?php

            $fitur = [
                ['user', 'Profil Mahasiswa', 'Informasi mahasiswa tersusun dalam profil yang lebih terstruktur.'],
                ['folder', 'Portofolio', 'Dokumentasikan proyek, pengalaman, keahlian, dan pencapaian.'],
                ['briefcase', 'Direktori Mitra', 'Temukan informasi perusahaan dan instansi mitra industri.'],
                ['shield', 'Verifikasi Data', 'Mendukung penyajian informasi mahasiswa yang telah diverifikasi.'],
                ['layers', 'Peluang Magang', 'Informasi posisi dan formasi magang tersedia secara lebih terstruktur.'],
                ['link', 'Ekosistem Terhubung', 'Menghubungkan mahasiswa, kampus, dan mitra dalam satu platform.'],
            ];

            ?>

            <?php foreach ($fitur as [$ic, $judul, $deskripsi]): ?>

                <article class="rounded-2xl border border-slate-100 p-5 sm:p-6
                    hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg
                    transition-all duration-200">

                    <span class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 grid place-items-center">
                        <?= icon($ic, 'w-5 h-5') ?>
                    </span>

                    <h3 class="mt-4 font-semibold text-slate-900">
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