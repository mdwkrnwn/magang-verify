<?php

$mahasiswaUnggulan = [
    [
        'nama' => 'Ahmad Rizki',
        'jurusan' => 'D4 Teknik Informatika',
        'foto' => 'https://api.dicebear.com/10.x/lorelei/svg?seed=Ahmad-Rizki',
        'keahlian' => ['Web Development', 'UI/UX'],
        'link' => '#'
    ],

    [
        'nama' => 'Salsabila Putri',
        'jurusan' => 'D4 Teknik Informatika',
        'foto' => 'https://api.dicebear.com/10.x/lorelei/svg?seed=Salsabila-Putri',
        'keahlian' => ['Data Analysis', 'Machine Learning'],
        'link' => '#'
    ],

    [
        'nama' => 'Farhan Maulana',
        'jurusan' => 'D4 Teknik Informatika',
        'foto' => 'https://api.dicebear.com/10.x/lorelei/svg?seed=Farhan-Maulana',
        'keahlian' => ['Mobile Development', 'Database'],
        'link' => '#'
    ],      
    //  [
    //     'nama' => 'Farhan Maulana',
    //     'jurusan' => 'D4 Teknik Informatika',
    //     'foto' => 'assets/images/farhan.jpg',
    //     'keahlian' => ['Mobile Development', 'Database'],
    //     'link' => '#'
    // ]
];

?>

<section class="bg-[#f4f9ff] py-16 w-full">
    <div class="max-w-7xl mx-auto px-6">

        <!-- HEADER -->
        <div class="flex items-end justify-between mb-8">

            <div>
                <!-- Label -->
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-sm font-bold tracking-wide text-blue-600">
                        TALENTA KAMI
                    </span>

                    <span class="w-12 h-[2px] bg-blue-400"></span>
                </div>

                <!-- Title -->
                <h2 class="text-3xl font-bold tracking-tight text-slate-900">
                    Daftar Mahasiswa Unggulan
                </h2>

                <!-- Description -->
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-500">
                    Temukan mahasiswa dengan berbagai keahlian dan pencapaian
                    yang siap berkontribusi di dunia industri.
                </p>
            </div>

            <!-- Lihat Semua -->
            <a
                href="pages/mahasiswa.php"
                class="hidden sm:inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700 transition">
                Lihat Semua

                <span class="text-xl leading-none">
                    →
                </span>
            </a>

        </div>


        <!-- CARD -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <?php foreach ($mahasiswaUnggulan as $mahasiswa): ?>

                <div
                    class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm hover:shadow-md transition duration-300">

                    <!-- PROFILE -->
                    <div class="flex items-center gap-4">
                        <img
                            src="<?= htmlspecialchars($mahasiswa['foto']) ?>"
                            alt="<?= htmlspecialchars($mahasiswa['nama']) ?>"
                            class="w-20 h-20 rounded-full object-cover">
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-slate-900">
                            <?= htmlspecialchars($mahasiswa['nama']) ?>
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            <?= htmlspecialchars($mahasiswa['jurusan']) ?>
                        </p>
                    </div>
                    <!-- SKILLS -->
                    <div class="flex flex-wrap gap-2 mt-5">

                        <?php foreach ($mahasiswa['keahlian'] as $skill): ?>

                            <span
                                class="px-3 py-1.5 rounded-full bg-blue-50 text-slate-700 text-xs font-medium">
                                <?= htmlspecialchars($skill) ?>
                            </span>

                        <?php endforeach; ?>

                    </div>


                    <!-- PROFILE LINK -->
                    <a
                        href="<?= htmlspecialchars($mahasiswa['link']) ?>"
                        class="inline-flex items-center gap-2 mt-6 text-sm font-semibold text-blue-600 hover:text-blue-700 transition">
                        Lihat Profil
                        <span class="text-lg leading-none">
                            →
                        </span>
                    </a>

                </div>

            <?php endforeach; ?>

        </div>

    </div>
</section>