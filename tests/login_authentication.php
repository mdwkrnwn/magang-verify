<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/MitraAuthentication.php';
require_once __DIR__ . '/../models/MitraSessionManager.php';
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

final class MitraCodeFixtureStatement extends PDOStatement
{
    public array $parameters = [];

    public function __construct(
        public string $query,
        private array $mitras
    ) {
    }

    public function execute(?array $params = null): bool
    {
        $this->parameters = $params ?? [];
        return true;
    }

    public function fetch(int $mode = PDO::FETCH_DEFAULT, int $cursorOrientation = PDO::FETCH_ORI_NEXT, int $cursorOffset = 0): mixed
    {
        foreach ($this->mitras as $mitra) {
            if (
                $mitra['kode_perusahaan'] === ($this->parameters['kode_perusahaan'] ?? null)
                && $mitra['is_active'] === true
            ) {
                return [
                    'id' => $mitra['id'],
                    'nama_perusahaan' => $mitra['nama_perusahaan'],
                    'is_active' => $mitra['is_active'],
                ];
            }
        }

        return false;
    }
}

final class MitraCodeFixturePdo extends PDO
{
    public array $queries = [];
    public ?MitraCodeFixtureStatement $statement = null;

    public function __construct(private array $mitras)
    {
    }

    public function prepare(string $query, array $options = []): PDOStatement|false
    {
        $this->queries[] = $query;
        $this->statement = new MitraCodeFixtureStatement($query, $this->mitras);
        return $this->statement;
    }
}

$mitraFixturePdo = new MitraCodeFixturePdo([
    ['id' => 43, 'nama_perusahaan' => 'Fixture Company', 'kode_perusahaan' => 'COMPANY-CODE-FIXTURE', 'is_active' => true],
    ['id' => 44, 'nama_perusahaan' => 'Inactive Company', 'kode_perusahaan' => 'INACTIVE-CODE-FIXTURE', 'is_active' => false],
]);
$mitraAuthentication = new MitraAuthentication($mitraFixturePdo);
$mitra = $mitraAuthentication->findActiveByCode(' COMPANY-CODE-FIXTURE ');
if (!$mitra || (int) $mitra['id'] !== 43) {
    throw new RuntimeException('Valid Mitra code did not resolve its own active profile.');
}
$mitraQuery = implode("\n", $mitraFixturePdo->queries);
foreach ([
    'FROM mitra',
    'WHERE kode_perusahaan = :kode_perusahaan',
    'AND is_active = TRUE',
] as $requiredQueryClause) {
    if (!str_contains($mitraQuery, $requiredQueryClause)) {
        throw new RuntimeException('Mitra code lookup is missing its direct profile/active check.');
    }
}
foreach (['users', 'user_roles', 'profil_mahasiswa', 'profil_dosen', 'profil_tendik', 'password'] as $forbiddenDependency) {
    if (str_contains(strtolower($mitraQuery), $forbiddenDependency)) {
        throw new RuntimeException('Mitra code lookup must not depend on internal account data.');
    }
}
expectAuthentication($mitraAuthentication->findActiveByCode('UNKNOWN-CODE-FIXTURE') === null, true, 'unknown Mitra code is rejected');
expectAuthentication($mitraAuthentication->findActiveByCode('INACTIVE-CODE-FIXTURE') === null, true, 'inactive Mitra code is rejected');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$_SESSION = ['user' => ['id' => 9001], 'unrelated_session_value' => 'preserved'];
$sessionPdo = new class extends PDO {
    public array $queries = [];
    public array $executions = [];
    public function __construct() {}
    public function prepare(string $query, array $options = []): PDOStatement|false
    {
        $this->queries[] = $query;
        return new class($this) extends PDOStatement {
            public function __construct(private object $pdo) {}
            public function execute(?array $params = null): bool
            {
                $this->pdo->executions[] = $params ?? [];
                return true;
            }
        };
    }
};
MitraSessionManager::createSession($sessionPdo, 43, 'Fixture Company');
expectAuthentication(!isset($_SESSION['user']), true, 'Mitra session does not create an internal user session');
expectAuthentication(($_SESSION['mitra']['id'] ?? null) === 43, true, 'Mitra session stores the mitra table identity');
expectAuthentication(
    str_contains($sessionPdo->queries[0], 'mitra_id')
        && ($sessionPdo->executions[0][':mitra_id'] ?? null) === 43
        && ($sessionPdo->executions[0][':access_token_id'] ?? null) === null,
    true,
    'manual code login persists a session keyed by Mitra id without a link token'
);
MitraSessionManager::createSession($sessionPdo, 43, 'Fixture Company', 77);
expectAuthentication(
    count(array_filter($sessionPdo->queries, static fn(string $query): bool => str_contains($query, 'UPDATE mitra_sessions'))) === 1
        && ($sessionPdo->executions[2][':mitra_id'] ?? null) === 43
        && ($sessionPdo->executions[2][':access_token_id'] ?? null) === 77,
    true,
    'generated-link login retains its token association and replaces the previous Mitra session'
);
expectAuthentication(!isset($_SESSION['user']) && ($_SESSION['mitra']['id'] ?? null) === 43, true, 'generated-link session also remains Mitra-only');
$_SESSION = [];

$hash = password_hash('test-role-password', PASSWORD_DEFAULT);
foreach (['mahasiswa', 'dosen', 'tendik'] as $role) {
    $user = ['role' => $role, 'roles' => [$role], 'is_active' => true, 'password' => $hash];
    expectAuthentication(true, LoginAuthenticator::accepts($role, $user, 'test-role-password'), $role . ' retains password authentication');
    expectAuthentication(false, LoginAuthenticator::accepts($role, $user, ''), $role . ' cannot bypass password authentication');
    expectAuthentication(false, LoginAuthenticator::accepts('mitra', $user, ''), 'internal credentials cannot authenticate Mitra');
}
expectAuthentication(
    true,
    LoginAuthenticator::accepts('koordinator_magang', [
        'role' => 'dosen',
        'roles' => ['dosen', 'koordinator_magang'],
        'is_active' => true,
        'password' => $hash,
    ], 'test-role-password'),
    'Coordinator role authenticates with its lecturer identity and role'
);
expectAuthentication(
    false,
    LoginAuthenticator::accepts('koordinator_magang', [
        'role' => 'koordinator_magang',
        'roles' => ['koordinator_magang'],
        'is_active' => true,
        'password' => $hash,
    ], 'test-role-password'),
    'Coordinator without lecturer role is rejected'
);
expectAuthentication(
    true,
    LoginAuthenticator::accepts('koordinator_magang', [
        'role' => 'koordinator_magang',
        'roles' => ['koordinator_magang'],
        'has_dosen_profile' => true,
        'is_active' => true,
        'password' => $hash,
    ], 'test-role-password'),
    'Coordinator primary role authenticates when its Dosen profile exists'
);

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
    false,
    LoginAuthenticator::accepts('mitra', [
        'role' => 'mahasiswa',
        'roles' => '{mahasiswa,mitra}',
        'is_active' => true,
        'mitra_is_active' => true,
    ], ''),
    'internal role authentication policy never accepts Mitra'
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

$orphanCoordinatorLookup = new LoginLookupFixturePdo([
    'id' => 92, 'login_id' => 'orphan-coordinator', 'name' => 'Invalid Coordinator',
    'role' => 'koordinator_magang', 'is_active' => true, 'password' => $hash,
], []);
expectAuthentication(
    (new User($orphanCoordinatorLookup))->findByIdentityAndRole('NIDN-2026-092', 'koordinator_magang') === null,
    true,
    'coordinator login is rejected without the Dosen role'
);

$validCoordinatorLookup = new LoginLookupFixturePdo([
    'id' => 93, 'login_id' => 'coordinator', 'name' => 'Valid Coordinator',
    'role' => 'dosen', 'is_active' => true, 'password' => $hash,
], ['koordinator_magang']);
$validCoordinator = (new User($validCoordinatorLookup))->findByIdentityAndRole('NIDN-2026-093', 'koordinator_magang');
expectAuthentication(
    $validCoordinator !== null
        && LoginAuthenticator::accepts('koordinator_magang', $validCoordinator, 'test-role-password'),
    true,
    'Dosen with the additional coordinator role can authenticate using the lecturer identity'
);

$primaryCoordinatorLookup = new LoginLookupFixturePdo([
    'id' => 94, 'login_id' => 'NIDN-2026-094', 'name' => 'Primary Coordinator',
    'role' => 'koordinator_magang', 'dosen_profile_id' => 194,
    'is_active' => true, 'password' => $hash,
], []);
$primaryCoordinator = (new User($primaryCoordinatorLookup))
    ->findByIdentityAndRole('NIDN-2026-094', 'koordinator_magang');
expectAuthentication(
    $primaryCoordinator !== null
        && in_array('dosen', $primaryCoordinator['roles'], true)
        && LoginAuthenticator::accepts('koordinator_magang', $primaryCoordinator, 'test-role-password'),
    true,
    'legacy coordinator primary role authenticates through its Dosen profile'
);

fwrite(STDOUT, "Isolated login authentication policy checks passed.\n");
