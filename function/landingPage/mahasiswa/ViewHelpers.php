<?php

require_once __DIR__ . '/../../../function/Helpers.php';


function foto(array $mahasiswa): string
{
    $path = trim((string) ($mahasiswa['foto_path'] ?? ''));

    if ($path === '') {
        return '';
    }

    if (preg_match('#^https?://#i', $path)) {
        return '';
    }

    if (!str_starts_with($path, 'uploads/profil/')) {
        return '';
    }

    return url('/mahasiswa/profil/foto/' . rawurlencode(basename($path)));
}
