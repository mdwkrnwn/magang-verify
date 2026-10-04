<section class="bg-[#f4f9ff] py-16 w-full">
    <div class="max-w-7xl mx-auto px-6">

        <!-- HEADER -->
        <div class="flex items-end justify-between mb-8">

            <div>
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-sm font-bold tracking-wide text-blue-600">
                        TALENTA KAMI
                    </span>

                    <span class="w-12 h-[2px] bg-blue-400"></span>
                </div>

                <h2 class="text-3xl font-bold tracking-tight text-slate-900">
                    Daftar Mahasiswa Unggulan
                </h2>

                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-500">
                    Temukan mahasiswa dengan berbagai keahlian dan pencapaian
                    yang siap berkontribusi di dunia industri.
                </p>
            </div>

            <a
                href="<?= url('/mahasiswa') ?>"
                class="hidden sm:inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700 transition">
                Lihat Semua
                <span class="text-xl leading-none">→</span>
            </a>

        </div>

        <?php if (empty($mahasiswaUnggulan)): ?>
            <div class="rounded-xl border border-slate-200 bg-white p-8 text-center text-sm text-slate-500">
                Belum ada data mahasiswa yang dapat ditampilkan.
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <?php foreach ($mahasiswaUnggulan as $mahasiswa): ?>
                    <?php
                    $nama = trim((string) ($mahasiswa['nama'] ?? 'Mahasiswa'));
                    $prodi = trim((string) ($mahasiswa['prodi'] ?? ''));
                    $foto = trim((string) ($mahasiswa['foto_path'] ?? ''));
                    $skills = is_array($mahasiswa['keahlian'] ?? null)
                        ? $mahasiswa['keahlian']
                        : [];
                    $slug = trim((string) ($mahasiswa['slug'] ?? slugify($nama)));

                    $namaParts = preg_split('/\s+/', $nama);
                    $inisial = '';
                    foreach (array_slice($namaParts ?: [], 0, 2) as $part) {
                        $inisial .= strtoupper(substr($part, 0, 1));
                    }
                    $inisial = $inisial !== '' ? $inisial : 'M';
                    ?>

                    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm hover:shadow-md transition duration-300">

                        <div class="flex items-center gap-4">
                            <div class="w-20 h-20 rounded-full overflow-hidden bg-blue-50 shrink-0 grid place-items-center">
                                <?php if ($foto !== ''): ?>
                                    <img
                                        src="<?= e(url('/' . ltrim($foto, '/'))) ?>"
                                        alt="Foto <?= e($nama) ?>"
                                        class="w-full h-full object-cover">
                                <?php else: ?>
                                    <span class="text-xl font-bold text-blue-600">
                                        <?= e($inisial) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h3 class="text-lg font-bold text-slate-900">
                                <?= e($nama) ?>
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                <?= e($prodi !== '' ? $prodi : 'Program studi belum diisi') ?>
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-2 mt-5">
                            <?php foreach (array_slice($skills, 0, 4) as $skill): ?>
                                <span class="px-3 py-1.5 rounded-full bg-blue-50 text-slate-700 text-xs font-medium">
                                    <?= e($skill) ?>
                                </span>
                            <?php endforeach; ?>

                            <?php if (empty($skills)): ?>
                                <span class="text-xs text-slate-400">Keahlian belum diisi</span>
                            <?php endif; ?>
                        </div>

                        <a
                            href="<?= e(url('/mahasiswa/profil/' . $slug)) ?>"
                            class="inline-flex items-center gap-2 mt-6 text-sm font-semibold text-blue-600 hover:text-blue-700 transition">
                            Lihat Profil
                            <span class="text-lg leading-none">→</span>
                        </a>

                    </div>
                <?php endforeach; ?>

            </div>
        <?php endif; ?>

    </div>
</section>
