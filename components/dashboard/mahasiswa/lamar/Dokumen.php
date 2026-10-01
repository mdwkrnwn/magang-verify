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
                   bg-amber-50
                   text-amber-600"
        >
            <?= icon('folder', 'w-4 h-4') ?>
        </div>


        <div>

            <h2
                class="text-base sm:text-lg
                       font-semibold
                       text-slate-900"
            >
                Dokumen Pengajuan
            </h2>

            <p
                class="mt-1
                       text-xs sm:text-sm
                       text-slate-500"
            >
                Unggah dokumen administratif yang diperlukan
            </p>

        </div>

    </div>


    <div class="space-y-4">

        <!-- Proposal -->
        <div>

            <label
                for="proposal"
                class="block mb-2
                       text-sm
                       font-medium
                       text-slate-700"
            >
                Proposal Pengajuan
                <span class="text-red-500">*</span>
            </label>

            <input
                id="proposal"
                name="proposal"
                type="file"
                accept=".pdf"
                form="form-pengajuan"
                class="block w-full
                       text-xs sm:text-sm
                       text-slate-500
                       file:mr-3
                       file:py-2
                       file:px-3
                       file:rounded-lg
                       file:border-0
                       file:text-xs
                       file:font-medium
                       file:bg-blue-50
                       file:text-blue-700
                       hover:file:bg-blue-100"
            >

            <p
                class="mt-1.5
                       text-xs
                       text-slate-400"
            >
                Format PDF.
            </p>

            <?php if (!empty($errors['proposal'])): ?>

                <p
                    class="mt-1
                           text-xs
                           text-red-600"
                >
                    <?= e($errors['proposal']) ?>
                </p>

            <?php endif; ?>

        </div>


        <!-- Fakta Integritas -->
        <div>

            <label
                for="fakta_integritas"
                class="block mb-2
                       text-sm
                       font-medium
                       text-slate-700"
            >
                Fakta Integritas
                <span class="text-red-500">*</span>
            </label>

            <input
                id="fakta_integritas"
                name="fakta_integritas"
                type="file"
                accept=".pdf"
                form="form-pengajuan"
                class="block w-full
                       text-xs sm:text-sm
                       text-slate-500
                       file:mr-3
                       file:py-2
                       file:px-3
                       file:rounded-lg
                       file:border-0
                       file:text-xs
                       file:font-medium
                       file:bg-blue-50
                       file:text-blue-700
                       hover:file:bg-blue-100"
            >

            <p
                class="mt-1.5
                       text-xs
                       text-slate-400"
            >
                Format PDF.
            </p>

            <?php if (
                !empty(
                    $errors['fakta_integritas']
                )
            ): ?>

                <p
                    class="mt-1
                           text-xs
                           text-red-600"
                >
                    <?= e(
                        $errors['fakta_integritas']
                    ) ?>
                </p>

            <?php endif; ?>

        </div>

    </div>

</div>