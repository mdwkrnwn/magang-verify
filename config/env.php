<?php

$envPath = dirname(__DIR__) . '/.env';

if (!is_file($envPath)) {
    throw new RuntimeException('File .env tidak ditemukan.');
}

$env = parse_ini_file($envPath, false, INI_SCANNER_RAW);

if ($env === false) {
    throw new RuntimeException('File .env gagal dibaca.');
}

foreach ($env as $key => $value) {
    if (!is_string($value)) {
        continue;
    }

    putenv("$key=$value");
    $_ENV[$key] = $value;
}