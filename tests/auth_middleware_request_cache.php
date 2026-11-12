<?php

declare(strict_types=1);

require_once __DIR__ . '/../middleware/AuthMiddleware.php';

function expectAuthCache(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

final class AuthCacheStatement extends PDOStatement
{
    public function __construct(private string $sql) {}
    public function execute(?array $params = null): bool { return true; }
    public function fetch(int $mode = PDO::FETCH_DEFAULT, int $cursorOrientation = PDO::FETCH_ORI_NEXT, int $cursorOffset = 0): mixed
    {
        return ['name' => 'Fixture', 'role' => 'mahasiswa', 'is_active' => true];
    }
    public function fetchAll(int $mode = PDO::FETCH_DEFAULT, mixed ...$args): array
    {
        return $this->sql === 'SELECT role FROM user_roles WHERE user_id = :id' ? ['mahasiswa'] : [];
    }
}

final class AuthCachePdo extends PDO
{
    public array $queries = [];
    public function __construct() {}
    public function prepare(string $query, array $options = []): PDOStatement|false
    {
        $this->queries[] = $query;
        return new AuthCacheStatement($query);
    }
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$_SESSION = ['user' => ['id' => 78231, 'role' => 'mahasiswa']];
$GLOBALS['pdo'] = new AuthCachePdo();

AuthMiddleware::handle('mahasiswa');
AuthMiddleware::handle('mahasiswa');

expectAuthCache(count($GLOBALS['pdo']->queries) === 2, 'Repeated authorization for the same user/request should fetch user and roles only once.');
expectAuthCache($_SESSION['user']['role'] === 'mahasiswa', 'Authorization must continue refreshing the active role in session.');

fwrite(STDOUT, "Auth middleware request-cache check passed.\n");
