<section
    class="overflow-hidden
           rounded-2xl border border-slate-200
           bg-white shadow-sm"
>

    <div class="p-5 sm:p-6 lg:p-7">

        <div class="flex items-start gap-4">

            <div
                class="flex h-14 w-14 shrink-0
                       items-center justify-center
                       rounded-2xl bg-violet-50
                       text-violet-600"
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
                        d="M12 3a9 9 0 100 18 2.5 2.5 0 100-5h-1a2 2 0 010-4h4a4 4 0 004-4 9 9 0 00-7-5Z"
                    />

                    <circle
                        cx="7.5"
                        cy="11"
                        r="1"
                        fill="currentColor"
                        stroke="none"
                    />

                    <circle
                        cx="9.5"
                        cy="7.5"
                        r="1"
                        fill="currentColor"
                        stroke="none"
                    />

                    <circle
                        cx="14"
                        cy="7"
                        r="1"
                        fill="currentColor"
                        stroke="none"
                    />

                </svg>

            </div>


            <div>

                <h2
                    class="text-lg font-bold
                           text-slate-950"
                >
                    Tampilan
                </h2>

                <p
                    class="mt-1 text-sm
                           text-slate-500"
                >
                    Pilih tampilan aplikasi sesuai preferensi Anda.
                </p>

            </div>

        </div>


        <div
            class="my-6
                   border-t border-slate-100"
        ></div>


        <div
            class="grid grid-cols-1 gap-4
                   sm:grid-cols-[240px_minmax(0,1fr)]
                   sm:items-center sm:gap-5"
        >

            <div>

                <p
                    class="text-sm font-semibold
                           text-slate-800"
                >
                    Mode Tampilan
                </p>

                <p
                    class="mt-1 text-sm
                           text-slate-500"
                >
                    Ubah tampilan aplikasi menjadi terang atau gelap.
                </p>

            </div>


            <div
                id="theme-switcher"
                class="grid grid-cols-2
                       rounded-xl
                       bg-slate-50 p-1"
            >

                <!-- Terang -->

                <button
                    type="button"
                    data-theme="light"
                    class="theme-button
                           inline-flex
                           items-center
                           justify-center
                           gap-2
                           rounded-lg
                           px-4 py-3
                           text-sm font-semibold
                           text-slate-500
                           transition"
                >

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >

                        <circle
                            cx="12"
                            cy="12"
                            r="4"
                            stroke-width="1.8"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-width="1.8"
                            d="M12 2v2m0 16v2M4.93 4.93l1.41 1.41m11.32 11.32 1.41 1.41M2 12h2m16 0h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"
                        />

                    </svg>

                    Terang

                </button>


                <!-- Gelap -->

                <button
                    type="button"
                    data-theme="dark"
                    class="theme-button
                           inline-flex
                           items-center
                           justify-center
                           gap-2
                           rounded-lg
                           px-4 py-3
                           text-sm font-semibold
                           text-slate-500
                           transition"
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
                            d="M20.5 15.5A8.5 8.5 0 018.5 3.5 8.5 8.5 0 1020.5 15.5Z"
                        />

                    </svg>

                    Gelap

                </button>

            </div>

        </div>

    </div>

</section>