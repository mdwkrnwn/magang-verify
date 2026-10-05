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
                   bg-emerald-50
                   text-emerald-600"
        >
            <?= icon('shield', 'w-4 h-4') ?>
        </div>


        <div>

            <h2
                class="text-base sm:text-lg
                       font-semibold
                       text-slate-900"
            >
                Pernyataan
            </h2>

            <p
                class="mt-1
                       text-xs sm:text-sm
                       text-slate-500"
            >
                Pastikan seluruh data dan dokumen telah benar
            </p>

        </div>

    </div>


    <?php $setuju = ($_POST['pernyataan'] ?? '') === '1'; ?>

    <label
        class="flex items-start gap-3
               cursor-pointer"
    >

        <input
            type="checkbox"
            name="pernyataan"
            value="1"
            form="form-pengajuan"
            <?= $setuju ? 'checked' : '' ?>
            class="w-4 h-4 mt-1
                   shrink-0
                   rounded
                   border-slate-300
                   text-blue-600
                   focus:ring-blue-500"
        >


        <span
            class="text-xs sm:text-sm
                   leading-6
                   text-slate-600"
        >
            Saya menyatakan bahwa data yang saya
            berikan dalam pengajuan magang ini adalah
            benar dan dokumen yang diunggah merupakan
            dokumen yang dapat dipertanggungjawabkan.
        </span>

    </label>


    <?php if (!empty($errors['pernyataan'])): ?>

        <p
            class="mt-2
                   text-xs
                   text-red-600"
        >
            <?= e($errors['pernyataan']) ?>
        </p>

    <?php endif; ?>

</div>