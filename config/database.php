<?php

require_once __DIR__ . '/app.php';
require_once __DIR__ . '/env.php';

$environment = strtolower(trim(getenv('APP_ENV') ?: 'local'));

$persistent = filter_var(
    getenv('DB_PERSISTENT') ?: 'false',
    FILTER_VALIDATE_BOOLEAN
);

$startedAt = microtime(true);

try {
    if ($environment === 'production') {
        $databaseUrl = getenv('DATABASE_URL');

        if ($databaseUrl === false || trim($databaseUrl) === '') {
            throw new RuntimeException(
                'DATABASE_URL is not configured.'
            );
        }

        $parts = parse_url($databaseUrl);

        if (
            $parts === false ||
            !in_array(
                strtolower($parts['scheme'] ?? ''),
                ['postgres', 'postgresql'],
                true
            )
        ) {
            throw new RuntimeException(
                'Invalid PostgreSQL DATABASE_URL.'
            );
        }

        $host = $parts['host'] ?? '';
        $port = $parts['port'] ?? 5432;
        $dbname = rawurldecode(
            ltrim($parts['path'] ?? '', '/')
        );
        $username = rawurldecode($parts['user'] ?? '');
        $password = rawurldecode($parts['pass'] ?? '');

        if (
            $host === '' ||
            $dbname === '' ||
            $username === ''
        ) {
            throw new RuntimeException(
                'Incomplete PostgreSQL connection URL.'
            );
        }

        // Neon wajib menggunakan koneksi SSL.
        $dsn = sprintf(
            'pgsql:host=%s;port=%d;dbname=%s;sslmode=require;connect_timeout=5',
            $host,
            $port,
            $dbname
        );

        // Koneksi production menggunakan URL Neon.
        $persistent = false;

    } else {
        // Pertahankan konfigurasi PostgreSQL lokal.
        $host = getenv('DB_HOST') ?: '172.21.192.1';
        $port = getenv('DB_PORT') ?: '5432';
        $dbname = getenv('DB_NAME') ?: 'magang_verify';
        $username = getenv('DB_USER') ?: 'magangverify';
        $password = getenv('DB_PASSWORD') ?: 'magangverify';

        $dsn = sprintf(
            'pgsql:host=%s;port=%s;dbname=%s;connect_timeout=5',
            $host,
            $port,
            $dbname
        );
    }

    $pdo = new PDO(
        $dsn,
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => $persistent,
        ]
    );

    $GLOBALS['pdo'] = $pdo;

    error_log(sprintf(
        '[DB PERF] PDO connection: %.3f s',
        microtime(true) - $startedAt
    ));

} catch (Throwable $e) {
    error_log(sprintf(
        '[DB ERROR] Connection failed after %.3f s: %s',
        microtime(true) - $startedAt,
        $e->getMessage()
    ));

    http_response_code(500);
    exit('Terjadi kesalahan koneksi database.');
}
