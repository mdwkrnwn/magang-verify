<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Database Host Resolution Cache
|--------------------------------------------------------------------------
|
| Resolving the remote database hostname can take several seconds on some
| networks. PDO performs a fresh lookup for every request, so a dashboard
| that also loads a PHP-served image can pay that cost more than once.
|
| The resolved IPv4 address is cached on disk for a short TTL and passed to
| libpq as "hostaddr" while the original hostname is kept as "host", so TLS
| SNI and certificate validation still use the hostname.
|
*/

function isIpv4Address(string $host): bool
{
    return filter_var(
        $host,
        FILTER_VALIDATE_IP,
        FILTER_FLAG_IPV4
    ) !== false;
}

function databaseHostCacheFile(string $host, string $cacheDir): string
{
    return rtrim($cacheDir, '/\\')
        . DIRECTORY_SEPARATOR
        . 'db-host-'
        . sha1($host)
        . '.json';
}

/**
 * Resolve a database hostname to an IPv4 address using a short-lived
 * on-disk cache. Returns null when the host is already an IP address or
 * cannot be resolved, in which case the caller should fall back to the
 * hostname as-is.
 */
function resolveDatabaseHost(
    string $host,
    string $cacheDir,
    int $ttlSeconds = 300,
    bool $forceRefresh = false
): ?string {
    if ($host === '' || isIpv4Address($host)) {
        return null;
    }

    $cacheFile = databaseHostCacheFile($host, $cacheDir);

    if (!$forceRefresh && is_file($cacheFile)) {
        $raw = @file_get_contents($cacheFile);
        $cached = is_string($raw)
            ? json_decode($raw, true)
            : null;

        if (
            is_array($cached)
            && ($cached['host'] ?? null) === $host
            && is_string($cached['ip'] ?? null)
            && isIpv4Address($cached['ip'])
            && (int) ($cached['expires_at'] ?? 0) > time()
        ) {
            return $cached['ip'];
        }
    }

    $resolved = @gethostbyname($host);

    if (!is_string($resolved) || !isIpv4Address($resolved)) {
        return null;
    }

    if (is_dir($cacheDir) || @mkdir($cacheDir, 0775, true)) {
        $temporaryFile = @tempnam($cacheDir, 'dbhost');

        if ($temporaryFile !== false) {
            $payload = json_encode([
                'host' => $host,
                'ip' => $resolved,
                'expires_at' => time() + max(30, $ttlSeconds),
            ], JSON_UNESCAPED_SLASHES);

            if (
                is_string($payload)
                && @file_put_contents($temporaryFile, $payload) !== false
            ) {
                @rename($temporaryFile, $cacheFile);
            } else {
                @unlink($temporaryFile);
            }
        }
    }

    return $resolved;
}

function invalidateDatabaseHostCache(string $host, string $cacheDir): void
{
    $cacheFile = databaseHostCacheFile($host, $cacheDir);

    if (is_file($cacheFile)) {
        @unlink($cacheFile);
    }
}

/**
 * True when a PDO exception indicates the connection itself failed (so a
 * cached address may be stale), as opposed to invalid credentials or a
 * query error.
 */
function isDatabaseConnectionFailure(PDOException $exception): bool
{
    $code = (string) $exception->getCode();
    $message = strtolower($exception->getMessage());

    if (in_array($code, ['08001', '08004', '08006', '57P01'], true)) {
        return true;
    }

    foreach ([
        'could not connect',
        'connection refused',
        'server closed the connection',
        'no route to host',
        'timeout expired',
        'could not translate host name',
    ] as $needle) {
        if (str_contains($message, $needle)) {
            return true;
        }
    }

    return false;
}
