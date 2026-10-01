<?php

$penerbit = $sertifikat['penerbit'] ?? '-';
$tanggalTerbit = $sertifikat['tanggal_terbit'] ?? '-';
$nomorSertifikat = $sertifikat['nomor_sertifikat'] ?? '-';

?>

<section class="p-5 bg-white border border-gray-200 rounded-xl shadow-sm sm:p-6">

    <div class="mb-5">

        <h2 class="text-lg font-bold text-gray-900">
            Informasi Sertifikat
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Informasi utama mengenai sertifikat ini.
        </p>

    </div>


    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">

        <div>

            <p class="mb-1 text-xs font-medium tracking-wide text-gray-500 uppercase">
                Penerbit
            </p>

            <p class="text-sm font-semibold leading-6 text-gray-900 break-words">
                <?= e($penerbit) ?>
            </p>

        </div>


        <div>

            <p class="mb-1 text-xs font-medium tracking-wide text-gray-500 uppercase">
                Tanggal Terbit
            </p>

            <p class="text-sm font-semibold leading-6 text-gray-900">
                <?= e($tanggalTerbit) ?>
            </p>

        </div>


        <div>

            <p class="mb-1 text-xs font-medium tracking-wide text-gray-500 uppercase">
                Nomor Sertifikat
            </p>

            <p class="text-sm font-semibold leading-6 text-gray-900 break-all">
                <?= e($nomorSertifikat) ?>
            </p>

        </div>

    </div>

</section>