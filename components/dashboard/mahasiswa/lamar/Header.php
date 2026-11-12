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
    <svg
        xmlns="http://www.w3.org/2000/svg"
        width="16"
        height="16"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
        class="shrink-0"
        aria-hidden="true"
    >
        <path d="m12 19-7-7 7-7" />
        <path d="M19 12H5" />
    </svg>

    <span>Kembali ke Detail Formasi</span>
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