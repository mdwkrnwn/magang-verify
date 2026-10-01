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

/*
|-----------------------
| Initials Helper
|-----------------------
*/

function initials($name)
{
    $name = trim((string) $name);

    if ($name === '') {
        return '';
    }

    $words = preg_split('/\s+/', $name);

    if (count($words) === 1) {
        return strtoupper(
            mb_substr($words[0], 0, 1)
        );
    }

    return strtoupper(
        mb_substr($words[0], 0, 1)
        . mb_substr($words[1], 0, 1)
    );
}