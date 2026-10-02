<?php

require_once __DIR__ . '/app.php';
require_once __DIR__ . '/env.php';

$host = getenv('DB_HOST') ?: '172.21.192.1';
$port = getenv('DB_PORT') ?: '5432';
$dbname = getenv('DB_NAME') ?: 'magang_verify';
$username = getenv('DB_USER') ?: 'magangverify';
$password = getenv('DB_PASSWORD') ?: 'magangverify';

try {
    // saat hosting
//     $dsn = "pgsql:host={$host};port={$port};dbname={$dbname};options='--search_path=public'";

// $pdo = new PDO(
//     $dsn,

    $pdo = new PDO(
        "pgsql:host={$host};port={$port};dbname={$dbname}",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    error_log('Koneksi database gagal: ' . $e->getMessage());

    http_response_code(500);
    exit('Terjadi kesalahan koneksi database.');
}