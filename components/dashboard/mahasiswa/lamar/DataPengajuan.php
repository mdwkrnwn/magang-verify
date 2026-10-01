<div
    class="p-4 sm:p-5 lg:p-6
           bg-white
           border border-slate-200
           rounded-xl"
>

    <div class="flex items-start gap-3 mb-5">

        <div
            class="flex items-center justify-center
                   w-9 h-9 shrink-0
                   rounded-lg
                   bg-blue-50
                   text-blue-600"
        >
            <?= icon('user', 'w-4 h-4') ?>
        </div>


        <div>

            <h2
                class="text-base sm:text-lg
                       font-semibold
                       text-slate-900"
            >
                Data Pengajuan
            </h2>

            <p
                class="mt-1
                       text-xs sm:text-sm
                       text-slate-500"
            >
                Data mahasiswa yang digunakan dalam pengajuan
            </p>

        </div>

    </div>


    <div
        class="grid grid-cols-1 gap-4
               sm:grid-cols-2"
    >

        <!-- Nama -->
        <div>

            <label
                for="nama"
                class="block mb-1.5
                       text-xs sm:text-sm
                       font-medium
                       text-slate-700"
            >
                Nama Mahasiswa
            </label>

            <input
                id="nama"
                name="nama"
                type="text"
                value="<?= e($nama) ?>"
                readonly
                form="form-pengajuan"
                class="w-full h-10
                       px-3
                       text-sm
                       text-slate-600
                       bg-slate-50
                       border border-slate-200
                       rounded-lg
                       outline-none"
            >

            <?php if (!empty($errors['nama'])): ?>

                <p
                    class="mt-1
                           text-xs
                           text-red-600"
                >
                    <?= e($errors['nama']) ?>
                </p>

            <?php endif; ?>

        </div>


        <!-- NIM -->
        <div>

            <label
                for="nim"
                class="block mb-1.5
                       text-xs sm:text-sm
                       font-medium
                       text-slate-700"
            >
                NIM
            </label>

            <input
                id="nim"
                name="nim"
                type="text"
                value="<?= e($nim) ?>"
                readonly
                form="form-pengajuan"
                class="w-full h-10
                       px-3
                       text-sm
                       text-slate-600
                       bg-slate-50
                       border border-slate-200
                       rounded-lg
                       outline-none"
            >

        </div>


        <!-- Prodi -->
        <div class="sm:col-span-2">

            <label
                for="prodi"
                class="block mb-1.5
                       text-xs sm:text-sm
                       font-medium
                       text-slate-700"
            >
                Program Studi
            </label>

            <input
                id="prodi"
                name="prodi"
                type="text"
                value="<?= e($prodi) ?>"
                readonly
                form="form-pengajuan"
                class="w-full h-10
                       px-3
                       text-sm
                       text-slate-600
                       bg-slate-50
                       border border-slate-200
                       rounded-lg
                       outline-none"
            >

        </div>

    </div>


    <div
        class="mt-4
               p-3
               rounded-lg
               bg-blue-50
               border border-blue-100"
    >

        <p
            class="text-xs
                   leading-5
                   text-blue-700"
        >
            Data identitas ditampilkan dari data mahasiswa
            yang terdaftar pada sistem dan digunakan sebagai
            bagian dari informasi pengajuan magang.
        </p>

    </div>

</div>