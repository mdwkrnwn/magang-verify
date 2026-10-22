<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/LoginAuthenticator.php';
require_once __DIR__ . '/../models/User.php';

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

$activeMitra = [
    'role' => 'mitra',
    'is_active' => true,
    'mitra_is_active' => true,
];
$fixturePdo = new LoginFixturePdo($activeMitra);
$mitra = (new User($fixturePdo))->findActiveMitraByCode('COMPANY-CODE-FIXTURE');
if ($mitra !== $activeMitra || $fixturePdo->statement === null) {
    throw new RuntimeException('Mitra code lookup did not return its linked account fixture.');
}
foreach ([
    'm.kode_perusahaan = :kode_perusahaan',
    'INNER JOIN users u ON u.id = m.user_id',
    "u.role = 'mitra'",
    'u.is_active = TRUE',
    'm.is_active = TRUE',
] as $requiredQueryClause) {
    if (!str_contains($fixturePdo->statement->query, $requiredQueryClause)) {
        throw new RuntimeException('Mitra lookup query missing required guard/join.');
    }
}
if (str_contains($fixturePdo->statement->query, 'u.password')) {
    throw new RuntimeException('Code-only Mitra authentication must not load password hashes.');
}
if ($fixturePdo->statement->parameters !== ['kode_perusahaan' => 'COMPANY-CODE-FIXTURE']) {
    throw new RuntimeException('Mitra lookup did not bind the submitted company code.');
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
    $user = ['role' => $role, 'is_active' => true, 'password' => $hash];
    expectAuthentication(true, LoginAuthenticator::accepts($role, $user, 'test-role-password'), $role . ' retains password authentication');
    expectAuthentication(false, LoginAuthenticator::accepts($role, $user, ''), $role . ' cannot bypass password authentication');
    expectAuthentication(false, LoginAuthenticator::accepts('mitra', $user, ''), 'non-Mitra identity cannot use Mitra authentication');
}

fwrite(STDOUT, "Isolated login authentication policy checks passed.\n");
