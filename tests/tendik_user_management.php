<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/User.php';

function expectManagedUser(bool $expected, bool $actual, string $case): void
{
    if ($expected !== $actual) {
        throw new RuntimeException('Managed-user validation failed: ' . $case);
    }
}

final class ManagedUserFixtureStatement extends PDOStatement
{
    public array $bindings = [];

    public function __construct(private string $query = '', private array $row = [])
    {
    }

    public function execute(?array $params = null): bool
    {
        $this->bindings[] = $params ?? [];
        return true;
    }

    public function fetchColumn(int $column = 0): mixed
    {
        if (str_contains($this->query, 'to_regclass')) {
            return 'user_roles';
        }
        return $this->row[$column] ?? 31;
    }
}

final class ManagedUserFixturePdo extends PDO
{
    public array $queries = [];
    public array $bindings = [];
    public array $transactions = [];
    public bool $inTransaction = false;

    public function __construct()
    {
    }

    public function prepare(string $query, array $options = []): PDOStatement|false
    {
        $this->queries[] = $query;
        $statement = new ManagedUserFixtureStatement($query);
        $this->bindings[] = $statement;
        return $statement;
    }

    public function beginTransaction(): bool
    {
        $this->inTransaction = true;
        $this->transactions[] = 'begin';
        return true;
    }

    public function commit(): bool
    {
        $this->inTransaction = false;
        $this->transactions[] = 'commit';
        return true;
    }

    public function rollBack(): bool
    {
        $this->inTransaction = false;
        $this->transactions[] = 'rollback';
        return true;
    }

    public function inTransaction(): bool
    {
        return $this->inTransaction;
    }
}

$pdoDouble = new ManagedUserFixturePdo();
$model = new User($pdoDouble);
$userId = $model->createManaged([
    'login_id' => 'tendik.admin',
    'name' => 'Tendik Admin',
    'password' => 'InitialPass123!',
    'nim' => 'TEST2026999',
    'roles' => ['mahasiswa', 'tendik', 'koordinator_magang', 'tendik', 'unknown-role'],
], 7);
expectManagedUser(31 === $userId, true, 'managed user returns fixture id');
expectManagedUser($pdoDouble->transactions === ['begin', 'commit'], true, 'managed user commits user and roles together');
$inserted = implode("\n", $pdoDouble->queries);
expectManagedUser(str_contains($inserted, 'INSERT INTO users'), true, 'managed user persists account');
expectManagedUser(str_contains($inserted, 'INSERT INTO user_roles'), true, 'managed user persists every selected role');
expectManagedUser(str_contains($inserted, 'INSERT INTO profil_mahasiswa'), true, 'student role creates its required profile');
expectManagedUser(
    strpos($inserted, 'INSERT INTO users') < strpos($inserted, 'INSERT INTO profil_mahasiswa'),
    true,
    'student profile is inserted after the account'
);
expectManagedUser(
    str_contains($inserted, 'INSERT INTO profil_mahasiswa (user_id, nim)'),
    true,
    'student profile uses the schema user_id and nim columns'
);
expectManagedUser(
    in_array(['user_id' => 31, 'nim' => 'TEST2026999'], array_map(static fn($statement) => $statement->bindings[0] ?? null, $pdoDouble->bindings), true),
    true,
    'student profile binds the inserted account id and submitted NIM'
);
$roleBindings = [];
foreach ($pdoDouble->bindings as $statement) {
    foreach ($statement->bindings as $binding) {
        if (isset($binding['role'])) {
            $roleBindings[] = ($binding['user_id'] ?? '') . ':' . $binding['role'];
        }
    }
}
expectManagedUser(
    in_array('31:mahasiswa', $roleBindings, true)
        && in_array('31:tendik', $roleBindings, true)
        && in_array('31:koordinator_magang', $roleBindings, true),
    true,
    'all selected roles persist once inside the same transaction'
);
expectManagedUser(User::allowedRoles() === ['mahasiswa', 'dosen', 'koordinator_magang', 'tendik', 'mitra'], true, 'allowed roles match schema constraint');
foreach (['ab', 'bad login!', ''] as $badLogin) {
    try {
        $model->createManaged(['login_id' => $badLogin, 'name' => 'Name', 'password' => 'InitialPass123!', 'roles' => ['tendik']], 7);
        throw new RuntimeException('Expected invalid login to be rejected.');
    } catch (InvalidArgumentException) {
    }
}
try {
    $model->createManaged(['login_id' => 'valid.login', 'name' => 'Name', 'password' => 'short', 'roles' => ['tendik']], 7);
    throw new RuntimeException('Expected short password to be rejected.');
} catch (InvalidArgumentException) {
}
try {
    $model->createManaged(['login_id' => 'valid.login', 'name' => 'Name', 'password' => 'InitialPass123!', 'roles' => ['unknown-role']], 7);
    throw new RuntimeException('Expected missing role selection to be rejected.');
} catch (InvalidArgumentException) {
}

fwrite(STDOUT, "Managed-user validation and persistence-seam checks passed.\n");
