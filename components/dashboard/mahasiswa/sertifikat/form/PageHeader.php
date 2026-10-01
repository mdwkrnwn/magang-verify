<?php

$isEdit =
    $mode === 'edit';

$title =
    $isEdit
        ? 'Edit Sertifikat'
        : 'Tambah Sertifikat';

$description =
    $isEdit
        ? 'Perbarui informasi sertifikat yang belum terverifikasi.'
        : 'Tambahkan sertifikat baru ke daftar sertifikat Anda.';

?>

<div>

    <a
        href="<?= $isEdit
            ? url('/dashboard/mahasiswa/sertifikat/detail/' . $slug)
            : url('/dashboard/mahasiswa/sertifikat') ?>"
        class="inline-flex items-center gap-2 mb-4 text-sm font-medium text-gray-500 transition hover:text-gray-900"
    >

        <svg
            class="w-4 h-4"
            fill="none"
            stroke="currentColor"
            stroke-width="1.8"
            viewBox="0 0 24 24"
            aria-hidden="true"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M15.75 19.5 8.25 12l7.5-7.5"
            />
        </svg>

        Kembali

    </a>

    <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">
        <?= e($title) ?>
    </h1>

    <p class="mt-2 max-w-2xl text-sm leading-6 text-gray-500">
        <?= e($description) ?>
    </p>

</div>