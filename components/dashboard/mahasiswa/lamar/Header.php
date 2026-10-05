<div class="mb-5">

    <a
        href="<?= e(url(
            '/dashboard/mahasiswa/formasi-magang/detail/'
            . $formasi['slug']
        )) ?>"
        class="inline-flex items-center gap-2 mb-4
               text-sm font-medium
               text-slate-500
               hover:text-blue-600
               transition"
    >

        <?= icon('arrow-left', 'w-4 h-4 shrink-0') ?>

        <span>
            Kembali ke Detail Formasi
        </span>

    </a>


    <div>

        <h1
            class="text-xl sm:text-2xl lg:text-3xl
                   font-bold tracking-tight
                   text-slate-900"
        >
            Pengajuan Magang
        </h1>

        <p
            class="mt-2
                   max-w-2xl
                   text-sm sm:text-base
                   leading-6
                   text-slate-500"
        >
            Lengkapi data dan dokumen yang diperlukan
            untuk mengajukan pendaftaran magang.
        </p>

    </div>

</div>