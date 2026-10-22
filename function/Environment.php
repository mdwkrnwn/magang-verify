<?php

declare(strict_types=1);

function hasEnvironmentValues(array $names): bool
{
    foreach ($names as $name) {
        if (!is_string($name) || $name === '') {
            return false;
        }

        $value = getenv($name);
        if ($value === false || $value === '' || $value === '0') {
            return false;
        }
    }

    return true;
}

function loadEnvironmentFileIfRequired(array $requiredNames, string $envPath): void
{
    if (hasEnvironmentValues($requiredNames)) {
        return;
    }

    if (!is_file($envPath)) {
        throw new RuntimeException('Required environment values are incomplete and no environment file is available.');
    }

    $values = parse_ini_file($envPath, false, INI_SCANNER_RAW);
    if ($values === false) {
        throw new RuntimeException('Environment file could not be parsed.');
    }

    foreach ($values as $key => $value) {
        if (!is_string($value)) {
            continue;
        }

        $currentValue = getenv((string) $key);
        if ($currentValue !== false && $currentValue !== '') {
            continue;
        }

        putenv($key . '=' . $value);
        $_ENV[$key] = $value;
    }

    if (!hasEnvironmentValues($requiredNames)) {
        throw new RuntimeException('Environment file does not provide all required non-empty values.');
    }
}
