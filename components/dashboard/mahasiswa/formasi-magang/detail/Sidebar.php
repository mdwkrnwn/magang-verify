<div
    class="p-4 sm:p-5
           bg-white border border-slate-200
           rounded-xl"
>

    <!-- Company -->
    <div class="flex items-start gap-3">

        <div
            class="flex items-center justify-center
                   w-11 h-11 shrink-0
                   rounded-xl
                   bg-blue-50
                   text-blue-600
                   text-sm font-bold"
        >
            <?= e(
                initials(
                    $formasi['perusahaan'] ?? ''
                )
            ) ?>
        </div>


        <div class="min-w-0">

            <h2
                class="text-sm sm:text-base
                       font-semibold
                       text-slate-900
                       break-words"
            >
                <?= e($formasi['perusahaan'] ?? '-') ?>
            </h2>

            <p
                class="mt-1
                       text-xs
                       text-slate-500"
            >
                Mitra Magang
            </p>

        </div>

    </div>


    <!-- Divider -->
    <div class="my-5 border-t border-slate-100"></div>


    <!-- Information -->
    <div class="space-y-4">

        <div>

            <p
                class="text-xs
                       font-medium
                       text-slate-400"
            >
                Posisi
            </p>

            <p
                class="mt-1
                       text-sm
                       font-medium
                       text-slate-700
                       break-words"
            >
                <?= e($formasi['posisi'] ?? '-') ?>
            </p>

        </div>


        <div>

            <p
                class="text-xs
                       font-medium
                       text-slate-400"
            >
                Lokasi
            </p>

            <p
                class="mt-1
                       text-sm
                       font-medium
                       text-slate-700
                       break-words"
            >
                <?= e($formasi['lokasi'] ?? '-') ?>
            </p>

        </div>

    </div>


    <!-- Note -->
    <div
        class="mt-5
               p-3 sm:p-4
               rounded-lg
               bg-slate-50
               border border-slate-100"
    >

        <p
            class="text-xs
                   leading-5
                   text-slate-500"
        >
            Setelah pengajuan dikirim, dosen memeriksa
            permohonan terlebih dahulu. Jika disetujui,
            respons dan jadwal seleksi mitra akan dicatat
            pada proses pendaftaran di sistem.
        </p>

    </div>

</div>