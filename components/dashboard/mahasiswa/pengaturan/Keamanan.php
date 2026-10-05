<section
    class="mb-5 overflow-hidden
           rounded-2xl border border-slate-200
           bg-white shadow-sm"
>

    <div class="p-5 sm:p-6 lg:p-7">

        <!-- Header -->

        <div class="flex items-start gap-4">

            <div
                class="flex h-14 w-14 shrink-0
                       items-center justify-center
                       rounded-2xl bg-blue-50
                       text-blue-600"
            >

                <svg
                    class="h-7 w-7"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M12 15v2m-6 4h12a2 2 0 002-2V9a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2Zm3-10V7a3 3 0 116 0v2"
                    />

                </svg>

            </div>


            <div>

                <h2
                    class="text-lg font-bold text-slate-950"
                >
                    Keamanan
                </h2>

                <p
                    class="mt-1 text-sm text-slate-500"
                >
                    Ubah kata sandi untuk menjaga keamanan akun Anda.
                </p>

            </div>

        </div>


        <div
            class="my-6 border-t border-slate-100"
        ></div>


        <!-- Success -->

        <?php if (!empty($success)): ?>

            <div
                class="mb-5 rounded-xl
                       border border-emerald-200
                       bg-emerald-50
                       px-4 py-3
                       text-sm text-emerald-700"
            >

                <?= e($success) ?>

            </div>

        <?php endif; ?>


        <!-- Form -->

        <form
            method="POST"
            action="<?= e(
                url('/dashboard/mahasiswa/pengaturan')
            ) ?>"
            class="space-y-5"
        >

            <input
                type="hidden"
                name="_csrf_token"
                value="<?= e(csrfToken()) ?>"
            >


            <!-- Password Lama -->

            <div
                class="grid grid-cols-1 gap-2
                       sm:grid-cols-[240px_minmax(0,1fr)]
                       sm:items-center sm:gap-5"
            >

                <label
                    for="current_password"
                    class="text-sm font-semibold
                           text-slate-800"
                >
                    Kata Sandi Saat Ini
                </label>


                <div>

                    <div class="relative">

                        <input
                            type="password"
                            id="current_password"
                            name="current_password"
                            placeholder="Masukkan kata sandi saat ini"
                            autocomplete="current-password"
                            class="w-full rounded-xl
                                   border border-slate-200
                                   bg-white
                                   px-4 py-3 pr-12
                                   text-sm text-slate-900
                                   outline-none transition
                                   placeholder:text-slate-400
                                   focus:border-blue-500
                                   focus:ring-4
                                   focus:ring-blue-50"
                        >


                        <button
                            type="button"
                            data-password-toggle="current_password"
                            class="absolute inset-y-0 right-0
                                   flex w-12 items-center
                                   justify-center
                                   text-slate-400
                                   transition
                                   hover:text-slate-700"
                            aria-label="Tampilkan kata sandi"
                        >

                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M2.5 12s3.5-5.5 9.5-5.5 9.5 5.5 9.5 5.5-3.5 5.5-9.5 5.5S2.5 12 2.5 12Z"
                                />

                                <circle
                                    cx="12"
                                    cy="12"
                                    r="2.5"
                                    stroke-width="1.8"
                                />

                            </svg>

                        </button>

                    </div>


                    <?php if (!empty($errors['current_password'])): ?>

                        <p
                            class="mt-1.5 text-xs
                                   text-red-600"
                        >
                            <?= e(
                                $errors['current_password']
                            ) ?>
                        </p>

                    <?php endif; ?>

                </div>

            </div>


            <!-- Password Baru -->

            <div
                class="grid grid-cols-1 gap-2
                       sm:grid-cols-[240px_minmax(0,1fr)]
                       sm:items-center sm:gap-5"
            >

                <label
                    for="new_password"
                    class="text-sm font-semibold
                           text-slate-800"
                >
                    Kata Sandi Baru
                </label>


                <div>

                    <div class="relative">

                        <input
                            type="password"
                            id="new_password"
                            name="new_password"
                            placeholder="Masukkan kata sandi baru"
                            autocomplete="new-password"
                            class="w-full rounded-xl
                                   border border-slate-200
                                   bg-white
                                   px-4 py-3 pr-12
                                   text-sm text-slate-900
                                   outline-none transition
                                   placeholder:text-slate-400
                                   focus:border-blue-500
                                   focus:ring-4
                                   focus:ring-blue-50"
                        >


                        <button
                            type="button"
                            data-password-toggle="new_password"
                            class="absolute inset-y-0 right-0
                                   flex w-12 items-center
                                   justify-center
                                   text-slate-400
                                   transition
                                   hover:text-slate-700"
                            aria-label="Tampilkan kata sandi"
                        >

                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M2.5 12s3.5-5.5 9.5-5.5 9.5 5.5 9.5 5.5-3.5 5.5-9.5 5.5S2.5 12 2.5 12Z"
                                />

                                <circle
                                    cx="12"
                                    cy="12"
                                    r="2.5"
                                    stroke-width="1.8"
                                />

                            </svg>

                        </button>

                    </div>


                    <?php if (!empty($errors['new_password'])): ?>

                        <p
                            class="mt-1.5 text-xs
                                   text-red-600"
                        >
                            <?= e(
                                $errors['new_password']
                            ) ?>
                        </p>

                    <?php endif; ?>

                </div>

            </div>


            <!-- Konfirmasi -->

            <div
                class="grid grid-cols-1 gap-2
                       sm:grid-cols-[240px_minmax(0,1fr)]
                       sm:items-center sm:gap-5"
            >

                <label
                    for="new_password_confirmation"
                    class="text-sm font-semibold
                           text-slate-800"
                >
                    Konfirmasi Kata Sandi Baru
                </label>


                <div>

                    <div class="relative">

                        <input
                            type="password"
                            id="new_password_confirmation"
                            name="new_password_confirmation"
                            placeholder="Masukkan kembali kata sandi baru"
                            autocomplete="new-password"
                            class="w-full rounded-xl
                                   border border-slate-200
                                   bg-white
                                   px-4 py-3 pr-12
                                   text-sm text-slate-900
                                   outline-none transition
                                   placeholder:text-slate-400
                                   focus:border-blue-500
                                   focus:ring-4
                                   focus:ring-blue-50"
                        >


                        <button
                            type="button"
                            data-password-toggle="new_password_confirmation"
                            class="absolute inset-y-0 right-0
                                   flex w-12 items-center
                                   justify-center
                                   text-slate-400
                                   transition
                                   hover:text-slate-700"
                            aria-label="Tampilkan kata sandi"
                        >

                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M2.5 12s3.5-5.5 9.5-5.5 9.5 5.5 9.5 5.5-3.5 5.5-9.5 5.5S2.5 12 2.5 12Z"
                                />

                                <circle
                                    cx="12"
                                    cy="12"
                                    r="2.5"
                                    stroke-width="1.8"
                                />

                            </svg>

                        </button>

                    </div>


                    <?php if (!empty($errors['new_password_confirmation'])): ?>

                        <p
                            class="mt-1.5 text-xs
                                   text-red-600"
                        >
                            <?= e(
                                $errors[
                                    'new_password_confirmation'
                                ]
                            ) ?>
                        </p>

                    <?php endif; ?>

                </div>

            </div>


            <!-- Submit -->

            <div
                class="flex justify-end pt-2"
            >

                <button
                    type="submit"
                    class="inline-flex
                           items-center justify-center
                           rounded-xl
                           bg-blue-600
                           px-5 py-3
                           text-sm font-semibold
                           text-white
                           shadow-sm transition
                           hover:bg-blue-700
                           focus:outline-none
                           focus:ring-4
                           focus:ring-blue-100"
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</section>