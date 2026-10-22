<?php

declare(strict_types=1);

/**
 * Resolve only explicitly-public assets from the repository assets directory.
 * PHP source, application files, uploads, and paths escaping through symlinks are denied.
 */
function resolvePublicAsset(string $requestPath, string $assetsRoot): ?array
{
    if (
        !str_starts_with($requestPath, '/assets/')
        || str_contains($requestPath, '\\')
        || preg_match('/[\x00-\x1F\x7F]/', $requestPath)
    ) {
        return null;
    }

    $relativePath = substr($requestPath, strlen('/assets/'));
    if ($relativePath === '' || str_contains($relativePath, '..')) {
        return null;
    }

    $canonicalRoot = realpath($assetsRoot);
    $canonicalFile = $canonicalRoot === false
        ? false
        : realpath($canonicalRoot . DIRECTORY_SEPARATOR . $relativePath);
    if (
        $canonicalRoot === false
        || $canonicalFile === false
        || !is_file($canonicalFile)
        || !str_starts_with($canonicalFile, $canonicalRoot . DIRECTORY_SEPARATOR)
    ) {
        return null;
    }

    $mime = match (strtolower(pathinfo($canonicalFile, PATHINFO_EXTENSION))) {
        'css' => 'text/css; charset=utf-8',
        'js' => 'application/javascript; charset=utf-8',
        'json' => 'application/json; charset=utf-8',
        'png' => 'image/png',
        'jpg', 'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        'otf' => 'font/otf',
        default => null,
    };

    return $mime === null ? null : ['path' => $canonicalFile, 'mime' => $mime];
}
