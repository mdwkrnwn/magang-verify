<?php

$q = trim($_GET['q'] ?? '');
$tahun = trim($_GET['tahun'] ?? '');
$status = trim($_GET['status'] ?? '');

?>

<div class="mb-6">

    <form
        id="sertifikatFilterForm"
        method="GET"
        action="<?= url('/dashboard/mahasiswa/sertifikat') ?>"
        class="bg-white border border-slate-200 rounded-xl p-4 sm:p-5">

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

            <!-- Search -->

            <div class="md:col-span-1">

                <label
                    for="sertifikat-search"
                    class="block mb-2 text-sm font-medium text-gray-700">

                    Cari Sertifikat

                </label>

                <div class="relative">

                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">

                        <svg
                            class="w-5 h-5 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z" />

                        </svg>

                    </div>

                    <input
                        id="sertifikat-search"
                        type="search"
                        name="q"
                        value="<?= e($q) ?>"
                        placeholder="Cari nama atau penerbit..."
                        autocomplete="off"
                        class="w-full pl-10 pr-4 py-2.5 text-sm
                               bg-white border border-slate-300
                               rounded-lg
                               text-gray-900
                               placeholder:text-gray-400
                               focus:outline-none
                               focus:ring-2
                               focus:ring-blue-500
                               focus:border-blue-500
                               transition">

                </div>

            </div>


            <!-- Tahun -->

            <div>

                <label
                    for="sertifikat-tahun"
                    class="block mb-2 text-sm font-medium text-gray-700">

                    Tahun

                </label>

                <select
                    id="sertifikat-tahun"
                    name="tahun"
                    class="w-full px-4 py-2.5 text-sm
                           bg-white border border-slate-300
                           rounded-lg
                           text-gray-900
                           focus:outline-none
                           focus:ring-2
                           focus:ring-blue-500
                           focus:border-blue-500
                           transition">

                    <option value="">
                        Semua Tahun
                    </option>

                    <?php foreach ($tahunList as $itemTahun): ?>

                        <option
                            value="<?= e($itemTahun) ?>"
                            <?= $tahun === (string) $itemTahun ? 'selected' : '' ?>>

                            <?= e($itemTahun) ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <!-- Status -->

            <div>

                <label
                    for="sertifikat-status"
                    class="block mb-2 text-sm font-medium text-gray-700">

                    Status Verifikasi

                </label>

                <select
                    id="sertifikat-status"
                    name="status"
                    class="w-full px-4 py-2.5 text-sm
                           bg-white border border-slate-300
                           rounded-lg
                           text-gray-900
                           focus:outline-none
                           focus:ring-2
                           focus:ring-blue-500
                           focus:border-blue-500
                           transition">

                    <option value="">
                        Semua Status
                    </option>

                    <option
                        value="terverifikasi"
                        <?= $status === 'terverifikasi' ? 'selected' : '' ?>>

                        Terverifikasi

                    </option>

                    <option
                        value="belum_terverifikasi"
                        <?= $status === 'belum_terverifikasi' ? 'selected' : '' ?>>

                        Belum Terverifikasi

                    </option>

                </select>

            </div>

        </div>

    </form>

</div>