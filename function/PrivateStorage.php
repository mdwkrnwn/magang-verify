<?php

declare(strict_types=1);

/**
 * Detect requests that must never be served as static files from private storage.
 * Repeated decoding handles proxies/servers that decode URL components before PHP.
 */
function isPrivateStorageRequestPath(string $requestUri): bool
{
    $path = parse_url($requestUri, PHP_URL_PATH);
    if (!is_string($path)) {
        return true;
    }

    for ($i = 0; $i < 16; $i++) {
        $decoded = rawurldecode($path);
        if ($decoded === $path) {
            break;
        }
        $path = $decoded;
        if ($i === 15 && rawurldecode($path) !== $path) {
            return true;
        }
    }

    if (str_contains($path, "\0")) {
        return true;
    }

    $path = str_replace('\\', '/', $path);
    $segments = [];
    foreach (explode('/', $path) as $segment) {
        if ($segment === '' || $segment === '.') {
            continue;
        }
        if ($segment === '..') {
            return true;
        }
        $segments[] = $segment;
    }

    $privateDirectories = ['private', 'logbook', 'pendaftaran', 'laporan'];
    $segments = array_map('strtolower', $segments);
    foreach ($segments as $index => $segment) {
        if (
            ($segment === 'storage'
                && isset($segments[$index + 1])
                && in_array($segments[$index + 1], $privateDirectories, true))
            || ($segment === 'uploads'
                && ($segments[$index + 1] ?? null) === 'cv')
        ) {
            return true;
        }
    }

    return false;
}

/** Resolve a stored relative path only when its canonical target is inside an allowed storage directory. */
function resolvePrivateStorageFile(string $projectRoot, string $relativePath, array $allowedDirectories): ?string
{
    $root = realpath($projectRoot);
    if ($root === false || $relativePath === '' || str_contains($relativePath, "\0")) {
        return null;
    }

    $relativePath = str_replace('\\', '/', $relativePath);
    if (str_starts_with($relativePath, '/') || preg_match('/^[A-Za-z]:\//', $relativePath)) {
        return null;
    }

    $segments = explode('/', $relativePath);
    if (in_array('..', $segments, true)) {
        return null;
    }

    foreach ($allowedDirectories as $directory) {
        $directory = trim(str_replace('\\', '/', (string) $directory), '/');
        if (
            $directory === ''
            || str_starts_with($directory, '../')
            || in_array('..', explode('/', $directory), true)
            || !str_starts_with($relativePath, $directory . '/')
        ) {
            continue;
        }

        $allowedRoot = realpath($root . '/' . $directory);
        $file = realpath($root . '/' . $relativePath);
        if (
            $allowedRoot === false
            || $allowedRoot !== $root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $directory)
            || !str_starts_with($allowedRoot, $root . DIRECTORY_SEPARATOR)
            || $file === false
            || !is_file($file)
            || !str_starts_with($file, $allowedRoot . DIRECTORY_SEPARATOR)
        ) {
            return null;
        }

        return $file;
    }

    return null;
}
