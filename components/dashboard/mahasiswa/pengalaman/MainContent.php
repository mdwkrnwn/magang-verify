<main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">
    <div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">
        <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Pengalaman</h1>
                <p class="mt-2 text-sm text-gray-500 sm:text-base">Kelola pengalaman Anda. Pengalaman magang yang sudah selesai akan muncul otomatis.</p>
            </div><a href="<?= e(url('/dashboard/mahasiswa/pengalaman/tambah')) ?>" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">+ Tambah Pengalaman</a>
        </div>
        <div class="grid gap-4">
            <?php if (empty($pengalaman)): ?><div class="rounded-2xl border border-gray-100 bg-white p-8 text-center shadow-sm">
                    <p class="font-medium text-gray-800">Belum ada pengalaman</p>
                    <p class="mt-1 text-sm text-gray-500">Tambahkan pengalaman atau selesaikan program magang Anda.</p>
                </div><?php endif; ?>
            <?php foreach ($pengalaman as $p): ?><article class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2"><span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700"><?= e(ucfirst($p['jenis'])) ?></span><?php if (!empty($p['is_otomatis'])): ?><span class="rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700">Otomatis dari Magang</span><?php endif; ?></div>
                            <h2 class="mt-2 text-lg font-semibold text-gray-900"><?= e($p['posisi']) ?></h2>
                            <p class="text-sm font-medium text-gray-600"><?= e($p['instansi']) ?></p>
                            <p class="mt-1 text-xs text-gray-400"><?= e($p['tanggal_mulai'] ?: '-') ?> — <?= e($p['tanggal_selesai'] ?: 'Sekarang') ?></p><?php if (!empty($p['lokasi'])): ?><p class="mt-2 text-sm text-gray-500"><?= e($p['lokasi']) ?></p><?php endif; ?><?php if (!empty($p['deskripsi'])): ?><p class="mt-3 whitespace-pre-line text-sm leading-6 text-gray-600"><?= e($p['deskripsi']) ?></p><?php endif; ?>
                        </div><?php if (empty($p['is_otomatis']) && !empty($p['id'])): ?><div class="flex shrink-0 gap-2"><a href="<?= e(url('/dashboard/mahasiswa/pengalaman/edit/' . $p['id'])) ?>" class="rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Edit</a>
                                <form method="POST" action="<?= e(url('/dashboard/mahasiswa/pengalaman/hapus/' . $p['id'])) ?>" onsubmit="return confirm('Hapus pengalaman ini?')"><input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>"><button class="rounded-lg border border-red-200 px-3 py-2 text-sm text-red-600 hover:bg-red-50">Hapus</button></form>
                            </div><?php endif; ?>
                    </div>
                </article><?php endforeach; ?>
        </div>
    </div>
</main>