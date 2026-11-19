<form
    id="form-pengajuan"
    method="POST"
    action="<?= e(
                url(
                    '/dashboard/mahasiswa/lamar/'
                        . $formasi['slug']
                )
            ) ?>"
    enctype="multipart/form-data"
    onsubmit="const button=this.querySelector('button[name=submit_pengajuan]');if(!button||button.disabled)return false;button.disabled=true;button.setAttribute('aria-busy','true');button.querySelector('span').textContent='Mengirim...';this.setAttribute('aria-busy','true');">
    <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">

    <?php if (!empty($errors['umum'])): ?>
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <?= e($errors['umum']) ?>
        </div>
    <?php endif; ?>

    <div
        class="p-4 sm:p-5 lg:p-6
               bg-white
               border border-slate-200
               rounded-xl">

        <?php if ($success): ?>

            <div
                class="p-4
                       mb-4
                       rounded-lg
                       bg-emerald-50
                       border border-emerald-100">

                <div class="flex items-start gap-3">

                    <div
                        class="flex items-center justify-center
                               w-8 h-8 shrink-0
                               rounded-full
                               bg-emerald-100
                               text-emerald-600">
                        <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                    </div>

                    <div>

                        <h3
                            class="text-sm
                                   font-semibold
                                   text-emerald-800">
                            Pengajuan siap diproses
                        </h3>

                        <p
                            class="mt-1
                                   text-xs
                                   leading-5
                                   text-emerald-700">
                            Pengajuan Anda telah tersimpan dan menunggu pemeriksaan dosen.
                        </p>

                    </div>

                </div>

            </div>

        <?php endif; ?>

        <div
            class="flex flex-col gap-3
                   sm:flex-row
                   sm:items-center
                   sm:justify-end">

            <a
                href="<?= e(
                            url(
                                '/dashboard/mahasiswa/formasi-magang/detail/'
                                    . $formasi['slug']
                            )
                        ) ?>"
                class="inline-flex items-center
                       justify-center
                       w-full sm:w-auto
                       h-10
                       px-4
                       text-sm
                       font-medium
                       text-slate-600
                       bg-white
                       border border-slate-200
                       rounded-lg
                       hover:bg-slate-50
                       transition">
                Batal
            </a>

            <button
                type="submit"
                name="submit_pengajuan"
                <?= $existingApplication ? 'disabled' : '' ?>
                value="1"
                class="inline-flex items-center
                       justify-center
                       gap-2
                       w-full sm:w-auto
                       min-w-[170px]
                       h-10
                       px-5
                       text-sm
                       font-semibold
                       text-white
                       bg-blue-600
                       disabled:opacity-50 disabled:cursor-not-allowed
                       rounded-lg
                       hover:bg-blue-700
                       active:bg-blue-800
                       transition">

                <svg
                    class="w-4 h-4 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    aria-hidden="true">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M22 2L11 13" />
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M22 2l-7 20-4-9-9-4 20-7z" />
                </svg>

                <span>
                    Ajukan Magang
                </span>

            </button>

        </div>

    </div>

</form>