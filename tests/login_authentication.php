<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/LoginAuthenticator.php';

function expectAuthentication(bool $expected, bool $actual, string $case): void
{
    if ($expected !== $actual) {
        throw new RuntimeException('Authentication policy failed: ' . $case);
    }
}

final class LoginFixtureStatement extends PDOStatement
{
    public array $parameters = [];

    public function __construct(public string $query, private array $row)
    {
    }

    public function execute(?array $params = null): bool
    {
        $this->parameters = $params ?? [];
        return true;
    }

    public function fetch(int $mode = PDO::FETCH_DEFAULT, int $cursorOrientation = PDO::FETCH_ORI_NEXT, int $cursorOffset = 0): mixed
    {
        return $this->row;
    }
}

final class LoginFixturePdo extends PDO
{
    public ?LoginFixtureStatement $statement = null;

    public function __construct(private array $row)
    {
    }

    public function prepare(string $query, array $options = []): PDOStatement|false
    {
        $this->statement = new LoginFixtureStatement($query, $this->row);
        return $this->statement;
    }
}

final class LoginRolesFixtureStatement extends PDOStatement
{
    public function __construct(private array $rows = []) {}
    public function execute(?array $params = null): bool { return true; }
    public function fetchAll(int $mode = PDO::FETCH_DEFAULT, mixed ...$args): array { return $this->rows === [[]] ? [] : $this->rows; }
}

final class LoginAccountFixtureStatement extends PDOStatement
{
    public function __construct(private array $row) {}
    public function execute(?array $params = null): bool { return true; }
    public function fetch(int $mode = PDO::FETCH_DEFAULT, int $cursorOrientation = PDO::FETCH_ORI_NEXT, int $cursorOffset = 0): mixed { return $this->row; }
}

final class LoginLookupFixturePdo extends PDO
{
    public array $queries = [];
    public function __construct(private array $account, private array $roles, private bool $missingRolesTable = false) {}
    public function prepare(string $query, array $options = []): PDOStatement|false
    {
        $this->queries[] = $query;
        if ($this->missingRolesTable && str_contains($query, 'SELECT role FROM user_roles')) {
            $exception = new PDOException('SQLSTATE[42P01]: relation "user_roles" does not exist');
            $exception->errorInfo = ['42P01', 0, 'relation "user_roles" does not exist'];
            throw $exception;
        }
        return str_contains($query, 'SELECT role FROM user_roles')
            ? new LoginRolesFixtureStatement(array_map(static fn(string $role): string => $role, $this->roles))
            : new LoginAccountFixtureStatement($this->account);
    }
}

$activeMitra = [
    'role' => 'mitra',
    'roles' => ['mitra'],
    'is_active' => true,
    'mitra_is_active' => true,
];
$fixturePdo = new LoginLookupFixturePdo([
    'id' => 43, 'role' => 'mitra', 'is_active' => true, 'mitra_is_active' => true,
], []);
$mitra = (new User($fixturePdo))->findActiveMitraByCode('COMPANY-CODE-FIXTURE');
if ($mitra['roles'] !== ['mitra'] || end($fixturePdo->queries) === false) {
    throw new RuntimeException('Mitra code lookup did not return its linked account fixture.');
}
foreach ([
    'm.kode_perusahaan = :kode_perusahaan',
    'INNER JOIN users u ON u.id = m.user_id',
    "EXISTS (\n                        SELECT 1 FROM user_roles ur",
    'u.is_active = TRUE',
    'm.is_active = TRUE',
] as $requiredQueryClause) {
    if (!str_contains(implode("\n", $fixturePdo->queries), $requiredQueryClause)) {
        throw new RuntimeException('Mitra lookup query missing required guard/join.');
    }
}
if (str_contains(implode("\n", $fixturePdo->queries), 'u.password')) {
    throw new RuntimeException('Code-only Mitra authentication must not load password hashes.');
}
if ($mitra['roles'] !== ['mitra']) {
    throw new RuntimeException('Mitra lookup did not normalize company roles.');
}
expectAuthentication(true, LoginAuthenticator::accepts('mitra', $activeMitra, ''), 'active linked Mitra accepts code-only authentication');
expectAuthentication(false, LoginAuthenticator::accepts('dosen', $activeMitra, ''), 'Mitra code cannot authenticate as another role');
$inactiveUser = $activeMitra;
$inactiveUser['is_active'] = false;
expectAuthentication(false, LoginAuthenticator::accepts('mitra', $inactiveUser, ''), 'inactive account is rejected');

$inactiveMitra = $activeMitra;
$inactiveMitra['mitra_is_active'] = false;
expectAuthentication(false, LoginAuthenticator::accepts('mitra', $inactiveMitra, ''), 'inactive company profile is rejected');

$hash = password_hash('test-role-password', PASSWORD_DEFAULT);
foreach (['mahasiswa', 'dosen', 'koordinator_magang', 'tendik'] as $role) {
    $user = ['role' => $role, 'roles' => [$role], 'is_active' => true, 'password' => $hash];
    expectAuthentication(true, LoginAuthenticator::accepts($role, $user, 'test-role-password'), $role . ' retains password authentication');
    expectAuthentication(false, LoginAuthenticator::accepts($role, $user, ''), $role . ' cannot bypass password authentication');
    expectAuthentication(false, LoginAuthenticator::accepts('mitra', $user, ''), 'non-Mitra identity cannot use Mitra authentication');
}

expectAuthentication(
    true,
    LoginAuthenticator::accepts('dosen', [
        'role' => 'mahasiswa',
        'roles' => '{mahasiswa,dosen}',
        'is_active' => true,
        'password' => $hash,
    ], 'test-role-password'),
    'PostgreSQL text-array roles authenticate a secondary role'
);
expectAuthentication(
    false,
    LoginAuthenticator::accepts('tendik', [
        'role' => 'mahasiswa',
        'roles' => '{mahasiswa,dosen}',
        'is_active' => true,
        'password' => $hash,
    ], 'test-role-password'),
    'unassigned role remains rejected for a multi-role account'
);
expectAuthentication(
    true,
    LoginAuthenticator::accepts('dosen', [
        'role' => 'mahasiswa',
        'roles' => ['mahasiswa', 'dosen'],
        'is_active' => true,
        'password' => $hash,
    ], 'test-role-password'),
    'native role arrays remain supported in isolated tests'
);
expectAuthentication(
    true,
    LoginAuthenticator::accepts('mahasiswa', [
        'role' => 'mahasiswa',
        'is_active' => true,
        'password' => $hash,
    ], 'test-role-password'),
    'legacy account without a roles column falls back to users.role'
);

expectAuthentication(
    true,
    LoginAuthenticator::accepts('mitra', [
        'role' => 'mahasiswa',
        'roles' => '{mahasiswa,mitra}',
        'is_active' => true,
        'mitra_is_active' => true,
    ], ''),
    'active secondary Mitra role accepts its existing code-only authentication policy'
);

$legacyLookup = new LoginLookupFixturePdo([
    'id' => 90, 'login_id' => 'legacy', 'name' => 'Legacy', 'role' => 'mahasiswa',
    'is_active' => true, 'password' => $hash,
], [], true);
$legacyAccount = (new User($legacyLookup))->findByLoginId('legacy');
expectAuthentication(true, LoginAuthenticator::accepts('mahasiswa', $legacyAccount, 'test-role-password'), 'legacy database account authenticates via primary role');
expectAuthentication(false, LoginAuthenticator::accepts('dosen', $legacyAccount, 'test-role-password'), 'legacy account has no implicit secondary role');

$multiRoleLookup = new LoginLookupFixturePdo([
    'id' => 91, 'login_id' => 'multi', 'name' => 'Multi', 'role' => 'mahasiswa',
    'is_active' => true, 'password' => $hash,
], ['dosen']);
$multiRoleAccount = (new User($multiRoleLookup))->findByLoginId('multi');
expectAuthentication(true, LoginAuthenticator::accepts('dosen', $multiRoleAccount, 'test-role-password'), 'account lookup reads assigned secondary role from relation');
expectAuthentication(false, LoginAuthenticator::accepts('tendik', $multiRoleAccount, 'test-role-password'), 'account lookup rejects unassigned secondary role');

fwrite(STDOUT, "Isolated login authentication policy checks passed.\n");
