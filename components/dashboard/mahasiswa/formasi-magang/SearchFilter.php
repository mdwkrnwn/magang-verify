<div class="mb-5 p-4 bg-white border border-slate-200 rounded-xl">

    <div class="flex flex-col gap-3 lg:flex-row lg:items-center">

        <!-- Search -->
        <div class="relative flex-1">

            <svg
                class="absolute w-4 h-4 text-slate-400 left-3 top-1/2 -translate-y-1/2"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 6.05 6.05a7.5 7.5 0 0 0 10.6 10.6Z" />

            </svg>

            <input
                type="text"
                placeholder="Cari nama perusahaan, posisi, atau kata kunci..."
                class="w-full h-10 pl-9 pr-4 text-sm text-slate-700
                       bg-white border border-slate-200 rounded-lg
                       outline-none focus:ring-2 focus:ring-blue-100
                       focus:border-blue-400">

        </div>


        <!-- Lokasi -->
        <select
            class="w-full lg:w-36 h-10 px-3 text-sm text-slate-600
                   bg-white border border-slate-200 rounded-lg
                   outline-none focus:ring-2 focus:ring-blue-100
                   focus:border-blue-400">

            <option>Semua Lokasi</option>
            <option>Malang</option>
            <option>Surabaya</option>
            <option>Jakarta</option>

        </select>


        <!-- Durasi -->
        <select
            class="w-full lg:w-36 h-10 px-3 text-sm text-slate-600
                   bg-white border border-slate-200 rounded-lg
                   outline-none focus:ring-2 focus:ring-blue-100
                   focus:border-blue-400">

            <option>Semua Durasi</option>
            <option>3 Bulan</option>
            <option>4 Bulan</option>
            <option>6 Bulan</option>

        </select>


        <!-- Status -->
        <select
            class="w-full lg:w-36 h-10 px-3 text-sm text-slate-600
                   bg-white border border-slate-200 rounded-lg
                   outline-none focus:ring-2 focus:ring-blue-100
                   focus:border-blue-400">

            <option>Semua Status</option>
            <option>Tersedia</option>
            <option>Penuh</option>

        </select>


        <!-- Reset -->
        <button
            type="button"
            class="h-10 px-2 text-xs font-medium text-blue-500
                   hover:text-blue-700 transition">

            Reset

        </button>

    </div>

</div>