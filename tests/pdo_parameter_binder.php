<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/PdoParameterBinder.php';

final class TypedBindingFixture extends PDOStatement
{
    public array $bindings = [];
    public bool $executed = false;

    public function __construct()
    {
    }

    public function bindValue(string|int $param, mixed $value, int $type = PDO::PARAM_STR): bool
    {
        $this->bindings[(string) $param] = ['value' => $value, 'type' => $type];
        return true;
    }

    public function execute(?array $params = null): bool
    {
        if ($params !== null) {
            throw new RuntimeException('Typed binder must bind first and execute without an untyped parameter array.');
        }
        $this->executed = true;
        return true;
    }
}

$statement = new TypedBindingFixture();
PdoParameterBinder::execute($statement, [
    'accept' => false,
    'reject' => true,
    'optional' => null,
    'id' => 27,
    'label' => 'example',
]);

$expected = [
    ':accept' => ['value' => false, 'type' => PDO::PARAM_BOOL],
    ':reject' => ['value' => true, 'type' => PDO::PARAM_BOOL],
    ':optional' => ['value' => null, 'type' => PDO::PARAM_NULL],
    ':id' => ['value' => 27, 'type' => PDO::PARAM_INT],
    ':label' => ['value' => 'example', 'type' => PDO::PARAM_STR],
];
if ($statement->bindings !== $expected || !$statement->executed) {
    throw new RuntimeException('Typed PDO parameter binding regression failed.');
}

fwrite(STDOUT, "Typed PDO parameter binder checks passed.\n");
