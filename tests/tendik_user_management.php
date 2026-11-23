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

    public function __construct(
        public string $query = '',
        private array $row = [],
        private array $rows = [],
        private bool $throwOnExecute = false,
        private bool $duplicateUserLoginId = false,
        private bool $duplicateTendikNip = false
    ) {}

    public function execute(?array $params = null): bool
    {
        $this->bindings[] = $params ?? [];
        if ($this->throwOnExecute) {
            throw new PDOException('relation "user_roles" does not exist');
        }
        if ($this->duplicateUserLoginId && str_contains($this->query, 'INSERT INTO users')) {
            throw new PDOException('duplicate key value violates unique constraint "users_login_id_key"', 23505);
        }
        if ($this->duplicateTendikNip && str_contains($this->query, 'INSERT INTO profil_tendik')) {
            throw new PDOException('duplicate key value violates unique constraint "profil_tendik_nip_key"', 23505);
        }
        return true;
    }

    public function fetchColumn(int $column = 0): mixed
    {
        if (str_contains($this->query, 'to_regclass')) {
            return 'user_roles';
        }
        return $this->row[$column] ?? 31;
    }

    public function fetch(int $mode = PDO::FETCH_DEFAULT, int $cursorOrientation = PDO::FETCH_ORI_NEXT, int $cursorOffset = 0): mixed
    {
        return $this->row ?: false;
    }

    public function fetchAll(int $mode = PDO::FETCH_DEFAULT, mixed ...$args): array
    {
        return $this->rows;
    }
}

final class ManagedUserFixturePdo extends PDO
{
    public array $queries = [];
    public array $bindings = [];
    public array $transactions = [];
    public array $managementUsers = [];
    public array $managementRoles = [];
    public array $managedUserRow = [];
    public bool $missingUserRolesTable = false;
    public bool $duplicateUserLoginId = false;
    public bool $duplicateTendikNip = false;
    public bool $inTransaction = false;

    public function __construct()
    {
    }

    public function prepare(string $query, array $options = []): PDOStatement|false
    {
        $this->queries[] = $query;
        $rows = [];
        $row = [];
        if (str_contains($query, 'FROM users u') && str_contains($query, 'ORDER BY u.created_at')) {
            $rows = $this->managementUsers;
        } elseif (str_contains($query, 'FROM user_roles') && str_contains($query, 'user_id = ANY')) {
            $rows = $this->managementRoles;
        } elseif (str_contains($query, 'FROM users') && str_contains($query, 'WHERE id = :id')) {
            $row = $this->managedUserRow;
        }
        $throwOnExecute = $this->missingUserRolesTable
            && str_contains($query, 'FROM user_roles')
            && str_contains($query, 'user_id = ANY');
        $duplicateUserLoginId = $this->duplicateUserLoginId && str_contains($query, 'INSERT INTO users');
        $duplicateTendikNip = $this->duplicateTendikNip && str_contains($query, 'INSERT INTO profil_tendik');
        $statement = new ManagedUserFixtureStatement($query, $row, $rows, $throwOnExecute, $duplicateUserLoginId, $duplicateTendikNip);
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
    'name' => 'Tendik Admin',
    'password' => 'InitialPass123!',
    'nim' => 'TEST2026999',
    'roles' => ['mahasiswa'],
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
$userInsertBindings = [];
foreach ($pdoDouble->bindings as $statement) {
    if (str_contains($statement->query, 'INSERT INTO users')) {
        $userInsertBindings = $statement->bindings[0] ?? [];
    }
}
expectManagedUser(($userInsertBindings['login_id'] ?? null) === 'TEST2026999', true, 'NIM supplies the required legacy login_id without manual form input');
expectManagedUser(password_verify('InitialPass123!', (string) ($userInsertBindings['password'] ?? '')), true, 'managed user password remains hashed');
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
        && !in_array('31:tendik', $roleBindings, true)
        && !in_array('31:koordinator_magang', $roleBindings, true),
    true,
    'single supported student role persists inside the transaction'
);
expectManagedUser(User::allowedRoles() === ['mahasiswa', 'dosen', 'koordinator_magang', 'tendik', 'mitra'], true, 'global allowed roles remain unchanged for legacy accounts');
expectManagedUser(User::managedCreationRoles() === ['mahasiswa', 'dosen', 'koordinator_magang', 'tendik'], true, 'creation form exposes managed roles with verified identity storage');
expectManagedUser(User::isSupportedManagedRoleSelection(['mahasiswa']), true, 'student-only role selection is supported');
expectManagedUser(User::isSupportedManagedRoleSelection(['dosen']), true, 'lecturer-only role selection is supported');
expectManagedUser(User::isSupportedManagedRoleSelection(['tendik']), true, 'tendik-only role selection is supported');
expectManagedUser(User::isSupportedManagedRoleSelection(['koordinator_magang', 'dosen']), true, 'coordinator is supported as an additional lecturer role');
foreach ([
    [],
    ['koordinator_magang'],
    ['mahasiswa', 'dosen'],
    ['mahasiswa', 'koordinator_magang'],
    ['mahasiswa', 'dosen', 'koordinator_magang'],
    ['tendik', 'dosen'],
    ['tendik', 'mahasiswa'],
    ['tendik', 'koordinator_magang'],
    ['mitra'],
    ['dosen', 'mitra'],
    ['koordinator_magang', 'mitra'],
    ['unknown-role'],
] as $unsupportedRoles) {
    expectManagedUser(User::isSupportedManagedRoleSelection($unsupportedRoles), false, 'unsupported role combination is rejected: ' . implode(',', $unsupportedRoles));
}
$createFormSource = file_get_contents(__DIR__ . '/../pages/dashboard/tendik/users/create.php');
$createControllerSource = file_get_contents(__DIR__ . '/../controllers/dashboard/tendik/UserController.php');
expectManagedUser(is_string($createFormSource) && !str_contains($createFormSource, 'name="login_id"'), true, 'create form no longer asks for a manually supplied login ID');
expectManagedUser(is_string($createControllerSource) && !str_contains($createControllerSource, "\$_POST['login_id']"), true, 'controller never reads or passes an empty login ID');
expectManagedUser(
    str_contains((string) $createFormSource, 'data-role-option')
        && str_contains((string) $createFormSource, 'role-selection-error')
        && str_contains((string) $createFormSource, 'koordinator_magang')
        && str_contains((string) $createFormSource, 'id="nip-field"')
        && str_contains((string) $createFormSource, 'name="nip"')
        && str_contains((string) $createFormSource, 'nipInput.required = tendik.checked')
        && str_contains((string) $createFormSource, 'nipInput.disabled = !tendik.checked')
        && str_contains((string) $createFormSource, "['mahasiswa', 'dosen', 'tendik'].includes(selected[0])"),
    true,
    'frontend provides the required NIP field only for Tendik and permits only supported role selections'
);

$dosenPdo = new ManagedUserFixturePdo();
(new User($dosenPdo))->createManaged([
    'name' => 'Dosen Fixture',
    'nidn' => 'NIDN-2026-001',
    'password' => 'InitialPass123!',
    'roles' => ['dosen'],
], 7);
$dosenInsertBindings = [];
foreach ($dosenPdo->bindings as $statement) {
    if (str_contains($statement->query, 'INSERT INTO users')) {
        $dosenInsertBindings = $statement->bindings[0] ?? [];
    }
}
expectManagedUser(($dosenInsertBindings['login_id'] ?? null) === 'NIDN-2026-001', true, 'NIDN supplies the required legacy login_id for a lecturer');
expectManagedUser(str_contains(implode("\n", $dosenPdo->queries), 'INSERT INTO profil_dosen (user_id, nidn)'), true, 'lecturer identity persists to the verified lecturer profile');

$tendikPdo = new ManagedUserFixturePdo();
(new User($tendikPdo))->createManaged([
    'name' => 'Tendik Fixture',
    'nip' => 'NIP / A-2026',
    'password' => 'InitialPass123!',
    'roles' => ['tendik'],
], 7);
$tendikInsertBindings = [];
$tendikProfileBindings = [];
foreach ($tendikPdo->bindings as $statement) {
    if (str_contains($statement->query, 'INSERT INTO users')) {
        $tendikInsertBindings = $statement->bindings[0] ?? [];
    }
    if (str_contains($statement->query, 'INSERT INTO profil_tendik')) {
        $tendikProfileBindings = $statement->bindings[0] ?? [];
    }
}
expectManagedUser(($tendikInsertBindings['login_id'] ?? null) === 'NIP / A-2026', true, 'NIP supplies the required legacy login_id without imposing an unverified format');
expectManagedUser(str_contains(implode("\n", $tendikPdo->queries), 'INSERT INTO profil_tendik (user_id, nip)'), true, 'tendik identity persists to the verified tendik profile');
expectManagedUser(($tendikProfileBindings['user_id'] ?? null) === 31 && ($tendikProfileBindings['nip'] ?? null) === 'NIP / A-2026', true, 'tendik profile binds the inserted account id and submitted NIP');
expectManagedUser($tendikPdo->transactions === ['begin', 'commit'], true, 'tendik account, role, and profile commit in one transaction');

$coordinatorPdo = new ManagedUserFixturePdo();
(new User($coordinatorPdo))->createManaged([
    'name' => 'Koordinator Fixture',
    'nidn' => 'NIDN-2026-002',
    'password' => 'InitialPass123!',
    'roles' => ['koordinator_magang', 'dosen'],
], 7);
$coordinatorRoleBindings = [];
$coordinatorUserBindings = [];
foreach ($coordinatorPdo->bindings as $statement) {
    if (str_contains($statement->query, 'INSERT INTO users')) {
        $coordinatorUserBindings = $statement->bindings[0] ?? [];
    }
    foreach ($statement->bindings as $binding) {
        if (isset($binding['role'])) {
            $coordinatorRoleBindings[] = $binding['role'];
        }
    }
}
expectManagedUser(
    ($coordinatorUserBindings['role'] ?? null) === 'koordinator_magang',
    true,
    'Tendik-created coordinator account stores koordinator_magang as its primary users.role'
);
expectManagedUser(in_array('dosen', $coordinatorRoleBindings, true) && in_array('koordinator_magang', $coordinatorRoleBindings, true), true, 'dosen and coordinator roles are both recorded for one lecturer identity');
expectManagedUser(count(array_filter($coordinatorPdo->queries, static fn($query) => str_contains($query, 'INSERT INTO profil_dosen'))) === 1, true, 'coordinator shares one lecturer profile');

$updatePdo = new ManagedUserFixturePdo();
$updatePdo->managedUserRow = [
    'id' => 51,
    'login_id' => 'NIDN-OLD',
    'name' => 'Dosen Lama',
    'password' => password_hash('ExistingPass123!', PASSWORD_DEFAULT),
    'role' => 'dosen',
    'is_active' => true,
];
(new User($updatePdo))->updateManaged(51, [
    'name' => 'Dosen Koordinator',
    'nidn' => 'NIDN-2026-051',
    'roles' => ['dosen', 'koordinator_magang'],
    'is_active' => true,
], 7);
$updatedRoleBindings = [];
$updatedUserBindings = [];
foreach ($updatePdo->bindings as $statement) {
    foreach ($statement->bindings as $binding) {
        if (isset($binding['role'])) {
            $updatedRoleBindings[] = $binding['role'];
        }
        if (isset($binding['login_id'])) {
            $updatedUserBindings = $binding;
        }
    }
}
expectManagedUser(
    in_array('dosen', $updatedRoleBindings, true)
        && in_array('koordinator_magang', $updatedRoleBindings, true),
    true,
    'user update persists both Dosen and coordinator roles'
);
expectManagedUser(($updatedUserBindings['login_id'] ?? null) === 'NIDN-2026-051', true, 'user update keeps login_id aligned with the lecturer identity');
expectManagedUser(($updatedUserBindings['role'] ?? null) === 'koordinator_magang', true, 'user update preserves the coordinator primary role');
expectManagedUser(
    count(array_filter($updatePdo->queries, static fn($query) => str_contains($query, 'UPDATE profil_dosen'))) === 1,
    true,
    'coordinator update reuses the existing lecturer profile'
);

$invalidCoordinatorUpdatePdo = new ManagedUserFixturePdo();
$invalidCoordinatorUpdatePdo->managedUserRow = $updatePdo->managedUserRow;
try {
    (new User($invalidCoordinatorUpdatePdo))->updateManaged(51, [
        'name' => 'Koordinator Tanpa Dosen',
        'nidn' => 'NIDN-2026-051',
        'roles' => ['koordinator_magang'],
        'is_active' => true,
    ], 7);
    throw new RuntimeException('Expected coordinator-only user update to be rejected.');
} catch (InvalidArgumentException) {
    expectManagedUser($invalidCoordinatorUpdatePdo->transactions === [], true, 'invalid coordinator update is rejected before persistence');
}

$managementPdo = new ManagedUserFixturePdo();
$managementPdo->managementUsers = [
    ['id' => 31, 'primary_role' => 'tendik'],
    ['id' => 32, 'primary_role' => 'mahasiswa'],
];
$managementPdo->managementRoles = [
    ['user_id' => 31, 'role' => 'tendik'],
    ['user_id' => 31, 'role' => 'koordinator_magang'],
    ['user_id' => 32, 'role' => 'dosen'],
    ['user_id' => 32, 'role' => 'unknown'],
];
$managedUsers = (new User($managementPdo))->getForManagement();
expectManagedUser(count($managementPdo->queries) === 2, true, 'managed-user listing fetches users and all roles in two queries');
expectManagedUser($managedUsers[0]['roles'] === ['tendik', 'koordinator_magang'], true, 'managed-user listing deduplicates and preserves allowed roles');
expectManagedUser($managedUsers[1]['roles'] === ['mahasiswa', 'dosen'], true, 'managed-user listing ignores unknown roles and includes the primary role');

$legacyManagementPdo = new ManagedUserFixturePdo();
$legacyManagementPdo->managementUsers = [['id' => 33, 'primary_role' => 'tendik']];
$legacyManagementPdo->missingUserRolesTable = true;
$legacyManagedUsers = (new User($legacyManagementPdo))->getForManagement();
expectManagedUser($legacyManagedUsers[0]['roles'] === ['tendik'], true, 'managed-user listing falls back to primary role when role table is absent');

foreach ([
    ['roles' => ['mahasiswa'], 'nim' => ''],
    ['roles' => ['mahasiswa'], 'nim' => 'invalid identity!'],
    ['roles' => ['dosen'], 'nidn' => ''],
    ['roles' => ['dosen'], 'nidn' => 'invalid identity!'],
    ['roles' => ['mahasiswa', 'dosen'], 'nim' => 'NIM-2026', 'nidn' => 'NIDN-2026'],
    ['roles' => ['tendik'], 'nip' => ''],
    ['roles' => ['tendik'], 'nip' => str_repeat('N', 31)],
    ['roles' => ['mitra']],
    ['roles' => ['dosen', 'unknown-role'], 'nidn' => 'NIDN-2026'],
] as $invalidData) {
    try {
        $model->createManaged($invalidData + [
            'name' => 'Name',
            'password' => 'InitialPass123!',
        ], 7);
        throw new RuntimeException('Expected invalid identity/role to be rejected.');
    } catch (InvalidArgumentException) {
    }
}
try {
    $model->createManaged(['name' => 'Name', 'password' => 'short', 'nim' => 'NIM-2026', 'roles' => ['mahasiswa']], 7);
    throw new RuntimeException('Expected short password to be rejected.');
} catch (InvalidArgumentException) {
}
try {
    $model->createManaged(['name' => 'Name', 'password' => 'InitialPass123!', 'roles' => ['unknown-role']], 7);
    throw new RuntimeException('Expected missing role selection to be rejected.');
} catch (InvalidArgumentException) {
}

$duplicateTendikPdo = new ManagedUserFixturePdo();
$duplicateTendikPdo->duplicateTendikNip = true;
try {
    (new User($duplicateTendikPdo))->createManaged([
        'name' => 'Duplicate Tendik Fixture',
        'nip' => 'NIP-2026-001',
        'password' => 'InitialPass123!',
        'roles' => ['tendik'],
    ], 7);
    throw new RuntimeException('Expected duplicate Tendik NIP to be rejected by the unique constraint.');
} catch (PDOException $exception) {
    expectManagedUser((string) $exception->getCode() === '23505', true, 'duplicate Tendik NIP unique violation is preserved');
    expectManagedUser($duplicateTendikPdo->transactions === ['begin', 'rollback'], true, 'duplicate Tendik NIP rolls back the account, role, and profile transaction');
}

$duplicatePdo = new ManagedUserFixturePdo();
$duplicatePdo->duplicateUserLoginId = true;
try {
    (new User($duplicatePdo))->createManaged([
        'name' => 'Duplicate Fixture',
        'nim' => 'TEST2026999',
        'password' => 'InitialPass123!',
        'roles' => ['mahasiswa'],
    ], 7);
    throw new RuntimeException('Expected duplicate login identity to be rejected by the unique constraint.');
} catch (PDOException $exception) {
    expectManagedUser((string) $exception->getCode() === '23505', true, 'database unique collision is preserved for a clear controller error');
    expectManagedUser($duplicatePdo->transactions === ['begin', 'rollback'], true, 'duplicate identity failure rolls back the account transaction');
}

fwrite(STDOUT, "Managed-user validation and persistence-seam checks passed.\n");
