<?php

/*
|-----------------------
| URL Helper
|-----------------------
*/

function url($path = '')
{
    $base = rtrim(APP_URL, '/');

    if ($path === '' || $path === '/') {
        return $base . '/';
    }

    return $base . '/' . ltrim($path, '/');
}

/*
|-----------------------
| Escape HTML
|-----------------------
*/

function e($value)
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

/*
|---------------------
| Slug Helper
|---------------------
*/

function slugify($text)
{
    $text = trim((string) $text);

    $text = strtolower($text);

    $text = preg_replace(
        '/[^a-z0-9]+/i',
        '-',
        $text
    );

    return trim($text, '-');
}