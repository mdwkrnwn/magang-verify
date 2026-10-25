<?php

/**
 * Pure credential policy shared by the login controller and isolated tests.
 */
final class LoginAuthenticator
{
    public static function normalizeRoles(array $user): array
    {
        $roles = $user['roles'] ?? [$user['role'] ?? null];
        if (is_string($roles)) {
            $trimmed = trim($roles);
            if ($trimmed === '' || $trimmed === '{}') {
                $roles = [];
            } elseif (str_starts_with($trimmed, '{') && str_ends_with($trimmed, '}')) {
                $inner = substr($trimmed, 1, -1);
                $roles = $inner === '' ? [] : explode(',', $inner);
            } else {
                $roles = [$roles];
            }
        }
        $normalized = [];
        foreach ((array) $roles as $role) {
            $clean = is_string($role) ? trim($role, " \"'") : null;
            if (is_string($clean) && $clean !== '') {
                $normalized[] = $clean;
            }
        }
        return array_values(array_unique($normalized));
    }

    public static function accepts(string $requestedRole, array $user, string $password): bool
    {
        if (!in_array($requestedRole, self::normalizeRoles($user), true) || !self::isActive($user['is_active'] ?? null)) {
            return false;
        }

        if ($requestedRole === 'mitra') {
            return self::isActive($user['mitra_is_active'] ?? null);
        }

        $hash = $user['password'] ?? null;
        return $password !== ''
            && is_string($hash)
            && $hash !== ''
            && password_verify($password, $hash);
    }

    private static function isActive(mixed $value): bool
    {
        return in_array($value, [true, 1, '1', 't', 'true'], true);
    }
}
