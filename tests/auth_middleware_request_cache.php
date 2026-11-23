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
    private array $parameters = [];

    public function __construct(
        private AuthCachePdo $pdo,
        private string $sql
    ) {
    }

    public function execute(?array $params = null): bool
    {
        $this->parameters = $params ?? [];
        return true;
    }

    public function fetch(int $mode = PDO::FETCH_DEFAULT, int $cursorOrientation = PDO::FETCH_ORI_NEXT, int $cursorOffset = 0): mixed
    {
        return $this->pdo->row((int) ($this->parameters['id'] ?? 0));
    }
}

final class AuthCachePdo extends PDO
{
    public array $queries = [];
    public array $rows = [];

    public function __construct()
    {
    }

    public function prepare(string $query, array $options = []): PDOStatement|false
    {
        $this->queries[] = $query;
        return new AuthCacheStatement($this, $query);
    }

    public function row(int $userId): array|false
    {
        return $this->rows[$userId] ?? false;
    }
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$pdo = new AuthCachePdo();
$pdo->rows = [
    7823101 => [
        'name' => 'Fixture Mahasiswa',
        'role' => 'mahasiswa',
        'is_active' => true,
        'roles' => 'mahasiswa,dosen',
        'has_dosen_profile' => false,
        'unread_notifications' => 0,
    ],
    7823102 => [
        'name' => 'Fixture Koordinator',
        'role' => 'dosen',
        'is_active' => true,
        'roles' => 'dosen,koordinator_magang',
        'has_dosen_profile' => true,
        'unread_notifications' => 2,
    ],
    7823103 => [
        'name' => 'Legacy Coordinator',
        'role' => 'koordinator_magang',
        'is_active' => true,
        'roles' => 'koordinator_magang',
        'has_dosen_profile' => true,
        'unread_notifications' => 0,
    ],
];
$GLOBALS['pdo'] = $pdo;

$_SESSION = ['user' => ['id' => 7823101, 'role' => 'mahasiswa']];
AuthMiddleware::handle('mahasiswa');
AuthMiddleware::handle('mahasiswa');

expectAuthCache(count($pdo->queries) === 1, 'Repeated authorization for one user reuses the loaded request context.');
expectAuthCache(
    $_SESSION['user']['roles'] === ['mahasiswa', 'dosen'],
    'Authorization refreshes the complete role set from the aggregate query.'
);

$_SESSION['user'] = ['id' => 7823102, 'role' => 'dosen'];
AuthMiddleware::handle('koordinator_magang');
expectAuthCache(count($pdo->queries) === 2, 'A different user gets an independent auth-context query.');
expectAuthCache(
    $_SESSION['user']['role'] === 'koordinator_magang',
    'A Dosen with the coordinator role and profile can select the coordinator authorization context.'
);
expectAuthCache(
    $GLOBALS['auth_context']['unread_notifications'] === 2,
    'The aggregate query continues to refresh the unread notification count.'
);

$_SESSION['user'] = ['id' => 7823103, 'role' => 'koordinator_magang'];
AuthMiddleware::handle('koordinator_magang');
expectAuthCache(count($pdo->queries) === 3, 'A coordinator-primary account gets its own authorization context.');
expectAuthCache(
    in_array('dosen', $_SESSION['user']['roles'], true),
    'A coordinator-primary account with a Dosen profile retains its lecturer role.'
);

fwrite(STDOUT, "Auth middleware request-cache checks passed.\n");
