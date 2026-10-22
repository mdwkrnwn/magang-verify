<?php

/**
 * Pure credential policy shared by the login controller and isolated tests.
 */
final class LoginAuthenticator
{
    public static function accepts(string $requestedRole, array $user, string $password): bool
    {
        if (($user['role'] ?? null) !== $requestedRole || !self::isActive($user['is_active'] ?? null)) {
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
