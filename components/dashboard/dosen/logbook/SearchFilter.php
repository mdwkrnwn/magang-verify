<section class="p-4 mb-6 bg-white border border-slate-100 shadow-sm rounded-2xl sm:p-5">

    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <h2 class="text-base font-semibold text-slate-900">
                Daftar Logbook
            </h2>

            <p class="mt-1 text-xs text-slate-500">
                Cari logbook mahasiswa bimbingan berdasarkan nama atau mitra.
            </p>

        </div>


        <div class="flex flex-col gap-3 sm:flex-row">

            <!-- Search -->

            <div class="relative">

                <svg
                    class="absolute w-4 h-4 -translate-y-1/2 left-3 top-1/2 text-slate-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"
                    />
                </svg>

                <input
                    type="text"
                    id="logbookSearch"
                    placeholder="Cari mahasiswa..."
                    class="w-full py-2.5 pl-9 pr-4 text-sm bg-white border rounded-xl border-slate-200 sm:w-64 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                >

            </div>


            <!-- Filter -->

            <select
                id="logbookStatusFilter"
                class="px-4 py-2.5 text-sm bg-white border rounded-xl border-slate-200 text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
            >

                <option value="all">
                    Semua Status
                </option>

                <option value="menunggu_dosen">
                    Menunggu Dosen
                </option>

                <option value="disetujui">
                    Disetujui
                </option>

                <option value="menunggu_mitra">
                    Menunggu Mitra
                </option>

                <option value="draft">
                    Draft
                </option>

            </select>

        </div>

    </div>

</section>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('logbookSearch');
    const statusFilter = document.getElementById('logbookStatusFilter');
    const rows = document.querySelectorAll('[data-logbook-row]');
    const emptyState = document.getElementById('emptyLogbookState');

    function filterLogbooks() {

        const search = searchInput.value.toLowerCase().trim();
        const status = statusFilter.value;

        let visibleCount = 0;

        rows.forEach(function (row) {

            const text = row.dataset.search.toLowerCase();
            const rowStatus = row.dataset.status;

            const matchesSearch = text.includes(search);
            const matchesStatus =
                status === 'all' || rowStatus === status;

            const visible = matchesSearch && matchesStatus;

            row.classList.toggle('hidden', !visible);

            if (visible) {
                visibleCount++;
            }

        });

        emptyState.classList.toggle(
            'hidden',
            visibleCount !== 0
        );
    }

    searchInput.addEventListener('input', filterLogbooks);
    statusFilter.addEventListener('change', filterLogbooks);

});
</script>